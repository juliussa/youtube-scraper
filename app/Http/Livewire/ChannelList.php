<?php

namespace App\Http\Livewire;

use App\Channel;
use App\GuzzleClient;
use App\Scraper;
use Livewire\Component;

class ChannelList extends Component
{
    public $channels;
    public $newChannel;


    public function mount()
    {
        $this->channels = Channel::all();
    }

    public function addChannel()
    {
        $this->validate([
            'newChannel' => 'required',
        ]);
        
        try {
            $scraper = new Scraper(new GuzzleClient);
            $scraper->scrapeChannel($this->newChannel);
            $this->newChannel = '';
            $this->channels = Channel::all();
        } catch (\Exception $e) {
            $this->addError('newChannel', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.channel-list');
    }
}
