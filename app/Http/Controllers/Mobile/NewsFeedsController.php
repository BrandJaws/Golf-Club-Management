<?php
namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Models\NewsFeed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsFeedsController extends Controller
{

	public function index(Request $request)
	{
		$clubId = Auth::user()->club->id;
		$currentPage = $request->has('page') ? $request->get('page') : 0;
		$perPage = $request->has('perPage') ? $request->get('perPage') : \Config::get('global.mobile_items_per_page');
		$this->response = NewsFeed::getNewsFeedsPaginated($clubId, $currentPage, $perPage);
		return $this->response();
	}

	public function show($newsfeed_id)
	{
		$newsfeed = NewsFeed::find($newsfeed_id);
		if ($newsfeed) {
			$this->response = $newsfeed;
		} else {
			$this->error = "newsfeed_not_found";
		}
		return $this->response();
	}
}

?>