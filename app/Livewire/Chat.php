<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Chat extends Component
{
    public $users;
    public $selectedUser;
    
    public function mount()
    {
        $this->users = User::whereNot("id", Auth::id())->get();
        $this->selectedUser = $this->users->first();
    }
    
    public function render()
    {
        return view('livewire.chat');
    }
}
