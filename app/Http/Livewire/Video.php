<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Video extends Component
{
    public $video;

    public function mount($video)
    {
        $this->video = (array) $video;
    }


    public function render()
    {
        return view('livewire.video');
    }
}
