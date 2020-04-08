<?php

namespace App;

use App\Contracts\ClientInterface;
use App\Contracts\ScraperInterface;

class Scraper implements ScraperInterface
{
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }


    public function scrapeChannel($channelId)
    {
        $channelData = $this->client->getChannel($channelId);

        if (empty($channelData->items)) {
            throw new \Exception("Channel not found", 1);
        }

        $channel = Channel::updateOrCreate([
            'yt_id' => $channelData->items[0]->id
        ], [
            'title' => $channelData->items[0]->snippet->title,
            'published_at' => date('Y-m-d h:i:s', strtotime($channelData->items[0]->snippet->publishedAt)),
        ]);

        return $channel;
    }


    public function scrapeChannelVideos(Channel $channel)
    {
        $finished = false;
        $token = null;

        while (!$finished) {
            $data = $this->client->getChannelVideos(
                $channel->yt_id,
                ['id', 'snippet'],
                $token
            );

            foreach ($data->items as $item) {
                $channel->videos()
                ->updateOrCreate(
                    [
                        'yt_id' => $item->id->videoId
                    ],
                    [
                        'title' => $item->snippet->title,
                    ]
                );
            }
            if (isset($data->nextPageToken)) {
                $token = $data->nextPageToken;
            } else {
                $finished = true;
            }
        }

        $this->updateChannelVideoTags($channel);

        return;
    }

    protected function updateChannelVideoTags(Channel $channel)
    {
        $videoids = Video::where('channel_id', $channel->id)
                    ->pluck('yt_id')
                    ->chunk(10)
                    ->toArray();

        foreach ($videoids as $ids) {
            $this->updateVideoTags($ids);
        }
    }

    protected function updateVideoTags($ids)
    {
        $data = $this->client->getVideos($ids, ['id', 'snippet']);
        foreach ($data->items as $item) {
            if (isset($item->snippet->tags)) {
                Video::where('yt_id', $item->id)
                    ->first()
                    ->updateTags($item->snippet->tags)
                    ->update([
                        'published_at' =>date('Y-m-d h:i:s', strtotime($item->snippet->publishedAt))
                    ]);
            }
        }
    }

    public function scrapeChannelVideoStats(Channel $channel)
    {
        $videoids = Video::where('channel_id', $channel->id)
                    ->pluck('yt_id')
                    ->chunk(10)
                    ->toArray();

        foreach ($videoids as $ids) {
            $this->scrapeVideosStats($ids);
        }
    }

    protected function scrapeVideosStats($ids)
    {
        $data = $this->client->getVideos($ids);
        foreach ($data->items as $item) {
            $video = Video::where('yt_id', $item->id)->first();
            $video->videoStats()->create([
                'previews' => $item->statistics->viewCount
            ]);
        }
    }
}
