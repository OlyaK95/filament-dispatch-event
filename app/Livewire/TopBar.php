<?php

namespace App\Livewire;

use Livewire\Component;

class TopBar extends Component
{
    protected $listeners = [
        "user-updated" => '$refresh',
    ];

    /*#[On("user-updated")]
    public function test()
    {
        dd("Test!");
    }*/

    public function render()
    {
        return view('livewire.top-bar');
    }
}
