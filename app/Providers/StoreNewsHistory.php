<?php

namespace App\Providers;

use App\Providers\NewsHistory;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class StoreNewsHistory
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Providers\NewsHistory  $event
     * @return void
     */
    public function handle(NewsHistory $event)
    {
        $current_timestamp = Carbon::now()->toDateTimeString();

        $event = (object)$event->news;
        $saveHistory = DB::table('news_history')->insert(
            [
                'email' => $event->email,
                'user_id' => $event->user_id,
                'news_id' => $event->news_id,
                'news_method' => $event->news_method,
                'created_at' => $current_timestamp,
                'updated_at' => $current_timestamp
            ]
        );
        return $saveHistory;
    }
}
