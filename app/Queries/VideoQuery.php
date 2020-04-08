<?php

namespace App\Queries;

class VideoQuery
{
    public static function indexByTags($channelId, $tags = [], $performance = [])
    {
        $sql = '
            SELECT
                videos.id,
                MAX(video_stats.previews) as previews
            FROM videos
            LEFT JOIN video_stats ON videos.id = video_stats.video_id
            WHERE videos.channel_id = '. $channelId .'
                    AND video_stats.created_at BETWEEN videos.created_at AND ADDTIME(videos.created_at, 3600)
            GROUP BY videos.id
        ';

        $latest = \DB::select($sql);
        $median = collect($latest)->median('previews') ?? 1;

        $query = '
                SELECT 
                    videos.id, 
                    videos.title,
                    GROUP_CONCAT(tags.title SEPARATOR \', \') as tag_names,
                    MAX(video_stats.previews)/'. $median .' as performance
                FROM videos
                LEFT JOIN tag_video ON videos.id = tag_video.video_id
                LEFT JOIN video_stats ON videos.id = video_stats.video_id
                JOIN tags ON tag_video.tag_id = tags.id
                WHERE videos.channel_id = '. $channelId .'
                    AND video_stats.created_at BETWEEN videos.created_at AND ADDTIME(videos.created_at, 3600)
            ';

        if (!empty($tags)) {
            foreach ($tags as $tag) {
                $query .= ' AND tags.id ='. $tag['id'];
            }
        }

        $query .= ' GROUP BY videos.id ';


        if (!empty($performance)) {
            $query .= static::applyPerformanceFilter($performance, $median);
        }

        $query .= ' ORDER BY videos.id DESC';
        return \DB::select($query);
    }

    protected static function applyPerformanceFilter(array $filter, $median) : string
    {
        $from = $filter['from'] ?? '';
        $to = $filter['to'] ?? '';
        $query = ' ';

        if ($from) {
            $query .= 'HAVING MAX(video_stats.previews)/'. $median .' >= ' . $from;
            if ($to) {
                $query .= ' AND MAX(video_stats.previews)/'. $median .' <= ' . $to;
            }
        } else {
            if ($to) {
                $query .= ' HAVING MAX(video_stats.previews)/'. $median .' <= ' . $to;
            }
        }

        return $query ;
    }
}
