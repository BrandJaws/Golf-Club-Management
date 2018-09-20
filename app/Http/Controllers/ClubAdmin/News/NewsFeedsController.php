<?php
namespace App\Http\Controllers\ClubAdmin\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Http\Models\NewsFeed;

class NewsFeedsController extends Controller
{

	public function index(Request $request)
	{
		if (Auth()->user()->canNot('news_feed', 'App\Model')) {
			return Redirect::route('admin.dashboard')->with([
				'error' => \trans('message.unauthorized_access')
			]);
		}
		$clubId = Auth::user()->club->id;
		$search = $request->has('search') ? $request->get('search') : false;
		$currentPage = $request->has('current_page') ? $request->get('current_page') : 0;
		$perPage = $request->has('per_page') ? $request->get('per_page') : \Config::get('global.portal_items_per_page');
		$newsFeed = NewsFeed::getNewsFeedsPaginated($clubId, $currentPage, $perPage, true);
		if ($request->ajax()) {
			return $newsFeed;
		} else {
			if ($newsFeed->count() > 0) {
				$newsFeed = json_encode($newsFeed);
			}
			return view('admin.news.list', compact('newsFeed'));
		}
	}

	public function create(Request $request)
	{
		if (Auth()->user()->canNot('news_feed', 'App\Model')) {
			return Redirect::route('admin.dashboard')->with([
				'error' => \trans('message.unauthorized_access')
			]);
		}
		return view('admin.news.create');
	}

	public function store(Request $request)
	{
		$validationRules = [
			'title' => 'required|string',
			'description' => 'required|string',
			'image' => 'sometimes|image|mimes:jpeg,bmp,png,svg|max:1024'
		];
		$validator = Validator::make($request->all(), $validationRules);
		if ($validator->fails()) {
			$this->error = $validator->errors();
			return \Redirect::back()->withInput()->withErrors($this->error);
		}
		
		$newsFeed = NewsFeed::create([
			'club_id' => Auth::user()->club->id,
			'title' => $request->get('title'),
			'description' => $request->get('description')
		]);
		if ($request->hasFile('image')) {
			$image = $request->file('image');
			$fileName = time() . '.' . $image->getClientOriginalExtension();
			$image->move('uploads/newsfeed/' . $newsFeed->id . '/', $fileName);
			if (! is_null($newsFeed->image) && $newsFeed->image != '' && file_exists($newsFeed->image)) {
				@unlink($newsFeed->image);
			}
			$newsFeed->image = 'uploads/newsfeed/' . $newsFeed->id . '/' . $fileName;
		}
		
		try {
			$newsFeed->save();
			return \Redirect::route('admin.newsfeeds.list')->with([
				'success' => \trans('message.news_messages.news_created_successfully')
			]);
		} catch (\Exception $exp) {
			return \Redirect::back()->withInput()->with([
				'error' => $exp->getMessage()
			]);
		}
	}

	public function edit($id, Request $request)
	{
		$news = NewsFeed::find($id);
		if ($news) {
			return view('admin.news.edit', compact('news'));
		} else {
			return \Redirect::back()->with([
				'error' => \trans('message.not_found')
			]);
		}
	}

	public function update($id, Request $request)
	{
		$validator = Validator::make($request->all(), [
			'title' => 'required|string',
			'description' => 'required|string',
			'image' => 'sometimes|image|mimes:jpeg,bmp,png,svg|max:1024'
		]);
		if ($validator->fails()) {
			$this->error = $validator->errors();
			return \Redirect::back()->withInput()->withErrors($this->error);
		}
		
		$newsFeed = NewsFeed::find($id);
		$newsFeed->title = $request->get('title');
		$newsFeed->description = $request->get('description');
		if ($request->hasFile('image')) {
			$image = $request->file('image');
			$fileName = time() . '.' . $image->getClientOriginalExtension();
			$image->move('uploads/newsfeed/' . $newsFeed->id . '/', $fileName);
			if (! is_null($newsFeed->image) && $newsFeed->image != '' && file_exists($newsFeed->image)) {
				@unlink($newsFeed->image);
			}
			$newsFeed->image = 'uploads/newsfeed/' . $newsFeed->id . '/' . $fileName;
		}
		
		try {
			$newsFeed->save();
			return \Redirect::route('admin.newsfeeds.list')->with([
				'success' => \trans('message.news_messages.news_updated_successfully')
			]);
		} catch (\Exception $exp) {
			return \Redirect::back()->withInput()->with([
				'error' => $exp->getMessage()
			]);
		}
	}

	public function destroy($id)
	{
		$newsFeed = NewsFeed::find($id);
		if ($newsFeed) {
			try {
				\Illuminate\Support\Facades\File::delete(public_path() . "/" . $newsFeed->image);
				$newsFeed->delete();
				return "success";
			} catch (\Exception $e) {
				return "failure";
			}
		}
	}
}

?>