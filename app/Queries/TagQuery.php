<?php

namespace App\Queries;

use App\Tag;

class TagQuery
{
    public static function index($search = '', $limit = 10, $orderBy = 'title', $order = 'ASC')
    {
        return Tag::where('title', 'like', '%'. $search . '%')
                ->take($limit)
                ->orderBy($orderBy, $order)
                ->get();
    }
}
