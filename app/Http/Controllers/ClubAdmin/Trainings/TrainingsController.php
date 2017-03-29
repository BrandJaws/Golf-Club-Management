<?php
namespace App\Http\Controllers\ClubAdmin\Trainings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\Training;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Http\Models\Coach;

class TrainingsController extends Controller
{

    public function index(Request $request)
    {
        if (Auth()->user()->canNot('training', 'App\Model')) {
            return Redirect::route('admin.dashboard')->with([
                'error' => \trans('message.unauthorized_access')
            ]);
        }
        $search = $request->has('search') ? $request->get('search') : false;
        $currentPage = $request->has('current_page') ? $request->get('current_page') : 0;
        $perPage = $request->has('per_page') ? $request->get('per_page') : \Config::get('global.portal_items_per_page');
        $trainings = (new Training())->paginatedList(Auth::user()->club_id, $currentPage, $perPage, $search);
        if ($request->ajax()) {
            return $trainings;
        } else {
            if ($trainings->count() > 0) {
                $trainings = json_encode($trainings);
            }
            return view('admin.trainings.trainings-list', compact('trainings'));
        }
    }

    public function create()
    {
        if (Auth()->user()->canNot('training', 'App\Model')) {
            return Redirect::route('admin.dashboard')->with([
                'error' => \trans('message.unauthorized_access')
            ]);
        }
        $coaches = (new Coach())->getCoachDropDownList(Auth::user()->club_id);
        return view('admin.trainings.create', compact('coaches'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:1,max:99',
            'lessonDescription' => 'required|min:1,max:250',
            'coach' => 'required|numeric',
            'numberOfSeats' => 'required|numeric',
            'promotionImage' => 'required_if:lessonMedia,image|image|mimes:jpeg,bmp,png,jpg|max:1024',
            'videoUrl' => 'required_if:lessonMedia,videoUrl|active_url',
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
            $training = new Training();
            
            $data['name'] = $request->get('name');
            $data['description'] = $request->get('lessonDescription');
            $data['seats'] = $request->get('numberOfSeats');
            $data['promotionType'] = $request->get('lessonMedia');
            ;
            $data['startDate'] = $request->get('startDate');
            $data['endDate'] = $request->get('endDate');
            $data['sessions'] = $request->get('numberOfSessions');
            $data['coach_id'] = $request->get('coach');
            $data['price'] = $request->get('price');
            $data['club_id'] = \Auth::user()->club_id;
            if ($request->get('lessonMedia') == 'image' && $request->hasFile('promotionImage')) {
                $image = $request->file('promotionImage');
                $fileName = time() . '.' . $image->getClientOriginalExtension();
                $image->move('uploads/training/', $fileName);
                $training->promotionContent = 'uploads/training/' . $fileName;
                $training->promotionType = config('global.contentType.image');
            } else {
                $training->promotionContent = $request->get('videoUrl');
                $training->promotionType = config('global.contentType.video');
            }
            
            $training->fill($data)->save();
            dd($training->toArray());
            return \Redirect::route('admin.trainings.index')->with([
                'success' => \trans('message.training_created_success.message')
            ]);
        } catch (\Exception $exp) {
            return \Redirect::back()->withInput()->with([
                'error' => $exp->getMessage()
            ]);
        }
    }

    public function edit(Request $request, $id)
    {
        if (Auth()->user()->canNot('training', 'App\Model')) {
            return Redirect::route('admin.dashboard')->with([
                'error' => \trans('message.unauthorized_access')
            ]);
        }
        
        try {
            $training = Training::findOrFail($id);
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
        
        return view('admin.trainings.edit', compact('training', 'coaches'));
    }

    public function update(Request $request, $id)
    {
        try {
            $conditonalRules = [];
            $training = Training::findOrFail($id);
            if (strtolower($training->promotionType) != $request->get('lessonMedia')) {
                if ($request->get('lessonMedia') == 'image') {
                    $conditonalRules = ['promotionImage' => 'required_if:lessonMedia,image|image|mimes:jpeg,bmp,png,jpg|max:1024'];
                } else {
                    $conditonalRules = [
                        'videoUrl' => 'required_if:lessonMedia,videoUrl|active_url'
                    ];
                }
            }
            $validator = Validator::make($request->all(), array_merge([
                'name' => 'required|min:1,max:99',
                'lessonDescription' => 'required|min:1,max:250',
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
            $data['description'] = $request->get('lessonDescription');
            $data['seats'] = $request->get('numberOfSeats');
            $data['promotionType'] = $request->get('lessonMedia');
            $data['startDate'] = $request->get('startDate');
            $data['endDate'] = $request->get('endDate');
            $data['sessions'] = $request->get('numberOfSessions');
            $data['coach_id'] = $request->get('coach');
            $data['price'] = $request->get('price');
            /*
             * delete image if the promotion content changed from image to video
             * and the previon entry is image
             */
            if ($training->promotionType == config('global.contentType.image') && $request->get('lessonMedia') == 'video') {
                if (! is_null($training->promotionContent) && file_exists($training->promotionContent)) {
                    @unlink($training->promotionContent);
                }
            }
            if ($request->get('lessonMedia') == 'image' && $request->hasFile('promotionImage')) {
                $image = $request->file('promotionImage');
                $fileName = time() . '.' . $image->getClientOriginalExtension();
                $image->move('uploads/training/', $fileName);
                if (! is_null($training->promotionContent) && file_exists($training->promotionContent)) {
                    @unlink($training->promotionContent);
                }
                $training->promotionContent = 'uploads/training/' . $fileName;
                $training->promotionType = config('global.contentType.image');
            } else {
                $training->promotionContent = $request->get('videoUrl');
                $training->promotionType = config('global.contentType.video');
            }
            
            $training->fill($data)->save();
            return \Redirect::route('admin.trainings.index')->with([
                'success' => \trans('message.training_updated_success.message')
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

    public function destroy($id)
    {
        try {
            Training::find($id)->delete();
            return "success";
        } catch (\Exception $e) {
            return $e->getMessage();
            return "failure";
        }
    }
}
