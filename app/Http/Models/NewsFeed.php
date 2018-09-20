<?php
namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class NewsFeed extends Model
{

    protected $fillable = [
        'club_id',
        'title',
        'description',
        'image'
    ];

    public function club()
    {
        return $this->belongsTo('App\Http\Models\Club');
    }

    public static function getNewsFeedsPaginated($clubId, $currentPage, $perPage, $isWeb = false)
    {
        $newsfeeds = NewsFeed::where('club_id', $clubId)->orderBy('created_at','desc')->paginate($perPage, [
            'id',
            'title',
            'description',
            'image',
            'created_at'
        ], 'page', $currentPage);
        foreach ($newsfeeds as $index => $feed) {
            
            if ($isWeb) {
                $newsfeeds[$index]->date = $feed->created_at->format('F d, Y');
                $newsfeeds[$index]->description = strip_tags($feed->description);
            } else {
                $newsfeeds[$index]->created_at = \Carbon\Carbon::parse($newsfeeds[$index]->created_at)->toDateTimeString();
            }
            if (! is_null($newsfeeds[$index]->image) && $newsfeeds[$index]->image != '') {
                $newsfeeds[$index]->image = asset($newsfeeds[$index]->image);
            }

        }
        
        return $newsfeeds;
    }

    public function getLatestNews($howMany = 1, $clubId)
    {
        return NewsFeed::where('club_id', $clubId)->take($howMany)
            ->latest()
            ->select([
            'id',
            'title',
            'description',
            'image',
            \DB::raw("DATE_FORMAT(DATE(created_at),'%b %d, %Y') as date")
        ])
            ->get();
    }
}
