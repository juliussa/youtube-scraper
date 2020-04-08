<?php
namespace App\Contracts;

use App\Channel;

interface ClientInterface
{
    public function getChannel(string $id);

    public function getChannelVideos(Channel $channel, $part, $nextToken);

    public function getVideos(array $ids, $part = ['id', 'snippet', 'statistics']);
}
