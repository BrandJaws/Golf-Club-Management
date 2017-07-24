<?php
namespace App\Http\Controllers\ClubAdmin\Events;

use App\Http\Controllers\Controller;
use App\Http\Models\Member;
use App\Http\Models\ReservationPlayer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\Event;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Http\Models\Coach;

class EventsController extends Controller
{

    public function index(Request $request)
    {
        if (Auth()->user()->canNot('event', 'App\Model')) {
            return Redirect::route('admin.dashboard')->with([
                'error' => \trans('message.unauthorized_access')
            ]);
        }
        $search = $request->has('search') ? $request->get('search') : false;
        $currentPage = $request->has('current_page') ? $request->get('current_page') : 0;
        $perPage = $request->has('per_page') ? $request->get('per_page') : \Config::get('global.portal_items_per_page');
        $events = (new Event())->paginatedList(Auth::user()->club_id, $currentPage, $perPage, $search);
        if ($request->ajax()) {
            return $events;
        } else {
            if ($events->count() > 0) {
                $events = json_encode($events);
            }
            return view('admin.events.events-list', compact('events'));
        }
    }

    public function create()
    {
        if (Auth()->user()->canNot('event', 'App\Model')) {
            return Redirect::route('admin.dashboard')->with([
                'error' => \trans('message.unauthorized_access')
            ]);
        }
        $coaches = (new Coach())->getCoachDropDownList(Auth::user()->club_id);
        return view('admin.events.create', compact('coaches'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:1,max:99',
            'eventDescription' => 'required|min:1,max:250',
            'coach' => 'required|numeric',
            'numberOfSeats' => 'required|numeric',
            'promotionImage' => 'required_if:eventMedia,image|image|mimes:jpeg,bmp,png,jpg|max:1024',
            'videoUrl' => 'required_if:eventMedia,videoUrl|active_url',
            'startDate' => 'required|date_format:Y-m-d',
            'endDate' => 'required|date_format:Y-m-d',
            'numberOfSessions' => 'required|numeric',
            'price' => 'required|numeric'
        ]);
        
        if ($validator->fails()) {
            $this->error = $validator->errors();
            return \Redirect::back()->withInput()->withErrors($this->error);
        }
        try {
            $event = new Event();
            
            $data['name'] = $request->get('name');
            $data['description'] = $request->get('eventDescription');
            $data['seats'] = $request->get('numberOfSeats');
            $data['promotionType'] = $request->get('eventMedia');
            ;
            $data['startDate'] = $request->get('startDate');
            $data['endDate'] = $request->get('endDate');
            $data['sessions'] = $request->get('numberOfSessions');
            $data['coach_id'] = $request->get('coach');
            $data['price'] = $request->get('price');
            $data['club_id'] = \Auth::user()->club_id;
            if ($request->get('eventMedia') == 'image' && $request->hasFile('promotionImage')) {
                $image = $request->file('promotionImage');
                $fileName = time() . '.' . $image->getClientOriginalExtension();
                $image->move('uploads/event/', $fileName);
                $event->promotionContent = 'uploads/event/' . $fileName;
                $event->promotionType = config('global.contentType.image');
            } else {
                $event->promotionContent = $request->get('videoUrl');
                $event->promotionType = config('global.contentType.video');
            }
            
            $event->fill($data)->save();
            return \Redirect::route('admin.events.index')->with([
                'success' => \trans('message.event_created_success.message')
            ]);
        } catch (\Exception $exp) {
            return \Redirect::back()->withInput()->with([
                'error' => $exp->getMessage()
            ]);
        }
    }

    public function edit(Request $request, $id)
    {
        if (Auth()->user()->canNot('event', 'App\Model')) {
            return Redirect::route('admin.dashboard')->with([
                'error' => \trans('message.unauthorized_access')
            ]);
        }
        
        try {
            $event = Event::findOrFail($id);
            $players = $event->getPlayersForEventPaginated(\Config::get('global.portal_items_per_page'),1);
            $coaches = (new Coach())->getCoachDropDownList(Auth::user()->club_id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exp) {
            return Redirect::back()->with([
                'error' => \trans('message.not_found')
            ]);
        } catch (\Exception $exp) {
            return Redirect::back()->with([
                'error' => $exp->getMessage()
            ]);
        }
        
        return view('admin.events.edit', compact('event', 'coaches','players'));
    }

    public function update(Request $request, $id)
    {
        try {
            $conditonalRules = [];
            $event = Event::findOrFail($id);
            if (strtolower($event->promotionType) != $request->get('eventMedia')) {
                if ($request->get('eventMedia') == 'image') {
                    $conditonalRules = ['promotionImage' => 'required_if:eventMedia,image|image|max:1024'];
                } else {
                    $conditonalRules = [
                        'videoUrl' => 'required_if:eventMedia,videoUrl|active_url'
                    ];
                }
            }
            $validator = Validator::make($request->all(), array_merge([
                'name' => 'required|min:1,max:99',
                'eventDescription' => 'required|min:1,max:250',
                'coach' => 'required|numeric',
                'numberOfSeats' => 'required|numeric',
                'startDate' => 'required|date_format:Y-m-d',
                'endDate' => 'required|date_format:Y-m-d',
                'numberOfSessions' => 'required|numeric',
                'price' => 'required|numeric'
            ], $conditonalRules));
            
            if ($validator->fails()) {
                $this->error = $validator->errors();
                return \Redirect::back()->withInput()->withErrors($this->error);
            }
            $data['name'] = $request->get('name');
            $data['description'] = $request->get('eventDescription');
            $data['seats'] = $request->get('numberOfSeats');
            $data['promotionType'] = $request->get('eventMedia');
            $data['startDate'] = $request->get('startDate');
            $data['endDate'] = $request->get('endDate');
            $data['sessions'] = $request->get('numberOfSessions');
            $data['coach_id'] = $request->get('coach');
            $data['price'] = $request->get('price');
            /*
             * delete image if the promotion content changed from image to video
             * and the previon entry is image
             */
            if ($event->promotionType == config('global.contentType.image') && $request->get('eventMedia') == 'video') {
                if (! is_null($event->promotionContent) && file_exists($event->promotionContent)) {
                    @unlink($event->promotionContent);
                }
            }
            if ($request->get('eventMedia') == 'image' && $request->hasFile('promotionImage')) {
                $image = $request->file('promotionImage');
                $fileName = time() . '.' . $image->getClientOriginalExtension();
                $image->move('uploads/event/', $fileName);
                if (! is_null($event->promotionContent) && file_exists($event->promotionContent)) {
                    @unlink($event->promotionContent);
                }
                $event->promotionContent = 'uploads/event/' . $fileName;
                $event->promotionType = config('global.contentType.image');
            } else {
                $event->promotionContent = $request->get('videoUrl');
                $event->promotionType = config('global.contentType.video');
            }
            
            $event->fill($data)->save();
            return \Redirect::route('admin.events.index')->with([
                'success' => \trans('message.event_updated_success.message')
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exp) {
            return Redirect::back()->with([
                'error' => \trans('message.not_found')
            ]);
        } catch (\Exception $exp) {
            return \Redirect::back()->withInput()->with([
                'error' => $exp->getMessage()
            ]);
        }
    }

    public function destroy($memberId)
    {
        try {
            Event::find($id)->delete();
            return "success";
        } catch (\Exception $e) {
            return $e->getMessage();
            return "failure";
        }
    }

    public function reservePlaceForAEvent(Request $request){
        if(!$request->has('event_id')){
            $this->error  ="event_id_missing";
            return $this->response();
        }

        if(!$request->has('member_id')){
            $this->error  ="member_id_missing";
            return $this->response();
        }

        $memberToBeAdded = Member::find($request->get('member_id'));
        if(!$memberToBeAdded){
            $this->error  ="member_not_exists";
            return $this->response();

        }
        $event = Event::find($request->get('event_id'));
        if(!$event){
            $this->error  ="no_events_found";
            return $this->response();

        }

        if($event->club_id != $memberToBeAdded->club_id){
            $this->error  ="event_doesnt_belong_to_users_club";
            return $this->response();
        }
        //validate if event is not in the past
        if(Carbon::parse($event->endDate) <= Carbon::today()  ){
            $this->error  ="event_is_not_available";
            return $this->response();
        }

        //validate if there are vacant places on reservation
        if($event->reservation_players->count() >= $event->seats  ){
            $this->error  ="event_slots_full";
            return $this->response();
        }

        foreach($event->reservation_players as $reservation_player){
            if($reservation_player->member_id == $memberToBeAdded->id ){
                $this->error  ="already_reserved_for_event";
                return $this->response();
            }

        }

        try{
            DB::beginTransaction();
            $reservationPlayer = $event->attachPlayer($memberToBeAdded->id);
            $reservationPlayer = ReservationPlayer:: where('reservation_players.id', '=', $reservationPlayer->id)
                ->leftJoin('member', 'member.id', '=', 'reservation_players.member_id')
                ->select('reservation_players.id', \DB::raw('CONCAT(member.firstName," ",member.lastName ) as name'), 'member.email', 'member.phone')
                ->first();
            $this->response = $reservationPlayer;

            DB::commit();
        }catch(\Exception $e){
            dd($e);
            \DB::rollback();

            \Log::info(__METHOD__, [
                'error' => $e->getMessage()
            ]);
            $this->error =  "exception";
        }

        return $this->response();

    }

    public function cancelPlaceForReservation(Request $request){
        if(!$request->has('reservation_player_id')){
            $this->error  ="reservation_player_id_missing";
            return $this->response();
        }

        $reservationPlayer = ReservationPlayer::find($request->get('reservation_player_id'));
        if(!$reservationPlayer){
            $this->error  ="no_reservations_found_for_member";
            return $this->response();

        }

        try{
            $reservationPlayer->delete();
            return "success";
        }catch(\Exception $e){
            \Log::info(__METHOD__, [
                'error' => $e->getMessage()
            ]);
            return "failure";
        }




    }

    public function playersForEventPaginated(Event $event,Request $request){

        $perPage = $request->has('per_page') ? $request->get('per_page') : \Config::get('global.portal_items_per_page');
        $currentPage = $request->has('current_page') ? $request->get('current_page') : 0;

         return $event->getPlayersForEventPaginated($perPage,$currentPage);

    }
}
