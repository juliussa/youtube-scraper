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

class ScrapeChannel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $channel;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Channel $channel)
    {
        $this->channel = $channel;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $scraper = new Scraper(new GuzzleClient);

        $data = $scraper->scrapeChannelVideos($this->channel);
        $data = json_decode($data->getContents());
        dd($data);
    }
}
