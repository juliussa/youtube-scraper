<?php

namespace App\Http\Livewire;

use App\Channel as ChannelModel;
use App\GuzzleClient;
use App\Queries\VideoQuery;
use App\Scraper;
use App\Tag;
use Livewire\Component;

class Channel extends Component
{
    public $channel;
    public $videos= [];
    public $tags = [];
    public $search = '';
    public $performanceFrom = '';
    public $performanceTo = '';

    protected $listeners = ['selectTag' => 'addTag'];


    public function mount(ChannelModel $channel)
    {
        $this->channel = $channel;
        $this->fetchVideos($channel->id);
    }

    public function updatedSearch($value)
    {
        $this->emit('updateAutocomplete', $value);
    }

    public function updateVideos()
    {
        try {
            $scraper = new Scraper(new GuzzleClient);
            $scraper->scrapeChannelVideos($this->channel);
        } catch (\Exception $e) {
            $this->addError('updateChannel', $e->getMessage());
        }
    }

    public function addTag($id)
    {
        $tag = Tag::find($id);
        $this->tags[] = $tag;
        $this->fetchVideos();
        $this->search = '';
    }

    public function fetchVideos()
    {
        $this->videos = json_decode(json_encode((array)VideoQuery::indexByTags($this->channel->id, $this->tags, ['from' => $this->performanceFrom, 'to' => $this->performanceTo])), true);
    }

    public function removeTag($id)
    {
        $index = -1;
        foreach ($this->tags as $key => $item) {
            if ($item['id'] === $id) {
                $index = $key;
                break;
            }
        }


        if ($index>= 0) {
            unset($this->tags[$index]);
        }

        $this->fetchVideos();
    }

    public function render()
    {
        return view('livewire.channel');
    }
}
