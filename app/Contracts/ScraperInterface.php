<?php
namespace App\Contracts;

use App\Channel;

interface ScraperInterface
{
    public function scrapeChannel(string $channelId);

    public function scrapeChannelVideos(Channel $channel);

    public function scrapeChannelVideoStats(Channel $channel);
}
