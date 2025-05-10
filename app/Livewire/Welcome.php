<?php

namespace App\Livewire;

use Livewire\Component;

class Welcome extends Component
{
    public function render()
    {
        return view('livewire.welcome')->title('Your Laravel Livewire Starter Kit - ' . config('app.name'));
    }
}
