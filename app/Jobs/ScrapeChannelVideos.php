<?php

namespace App\Jobs;

use App\Channel;
use App\GuzzleClient;
use App\Scraper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ScrapeChannelVideos implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $scraper = new Scraper(new GuzzleClient);
        $channels = Channel::all();
        foreach ($channels as $channel) {
            $scraper->scrapeChannelVideoStats($channel);
        }
    }
}
