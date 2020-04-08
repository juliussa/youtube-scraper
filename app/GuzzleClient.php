<?php

namespace App;

use App\Contracts\ClientInterface;

class GuzzleClient implements ClientInterface
{
    const CHANNEL_URL = 'https://www.googleapis.com/youtube/v3/channels';
    const SEARCH_URL ='https://www.googleapis.com/youtube/v3/search';
    const VIDEOS_URL ='https://www.googleapis.com/youtube/v3/videos';

    private $client;

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client();
    }


    /**
     * Get channel
     * @param  string $channelId
     * @return array
     */
    public function getChannel(string $channelId)
    {
        $response = $this->client->request('GET', self::CHANNEL_URL, [
            'query' => [
                'part' => 'snippet,contentDetails,statistics',
                'key' => env('YOUTUBE_API_KEY'),
                'id' => $channelId
            ]
        ]);

        return json_decode($response->getBody()->getContents());
    }


    /**
     * Get Channel videos
     */
    public function getChannelVideos(
        $channelId,
        $part = ['id', 'snippet'],
        $nextToken
    ) {
        $response = $this->client->request('GET', self::SEARCH_URL, [
                'query' => [
                    'key' =>  env('YOUTUBE_API_KEY'),
                    'type' => 'video',
                    'channelId' => $channelId,
                    'part' => implode(', ', $part),
                    'pageToken' => $nextToken,
                    'maxResults' => 50
                ]
            ]);

        return json_decode($response->getBody()->getContents());
    }

    public function getVideos(array $ids, $part = ['id', 'snippet', 'statistics'])
    {
        $response = $this->client->request('GET', self::VIDEOS_URL, [
                'query' => [
                    'key' =>  env('YOUTUBE_API_KEY'),
                    'id' => implode(', ', $ids),
                    'part' => implode(', ', $part),
                ]
            ]);

        return json_decode($response->getBody()->getContents());
    }
}
