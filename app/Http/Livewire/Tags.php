<?php

namespace App\Http\Livewire;

use App\Queries\TagQuery;
use Livewire\Component;

class Tags extends Component
{
    public $tags = [];

    public function mount()
    {
        $this->tags = [];
    }

    protected $listeners = ['updateAutocomplete' => 'updateSuggestions'];



    public function updateSuggestions($value)
    {
        if ($value == '') {
            $this->tags === '';
        } else {
            $this->tags = TagQuery::index($value);
        }
    }

    public function addTag($id)
    {
        $this->emit('selectTag', $id);
    }


    public function render()
    {
        return view('livewire.tags');
    }
}
