<?php

namespace App\Livewire;

use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Chat extends Component
{
    public $users;
    public $selectedUser;
    public $newMessage;
    public $messages;

    public function mount()
    {
        $this->users = User::whereNot("id", Auth::id())->get();
        $this->selectedUser = $this->users->first();
        $this->loadMessages();
    }
    public function selectUser($userId)
    {
        $this->selectedUser = User::find($userId);
        $this->loadMessages();
    }

    public function loadMessages()
    {
        $this->messages = ChatMessage::query()
            ->where(function($query) {
                $query->where("sender_id", Auth::id())
                    ->where("receiver_id", $this->selectedUser->id);
            })
            ->orWhere(function($query) {
                $query->where("sender_id", $this->selectedUser->id)
                    ->where("receiver_id", Auth::id());
            })->latest()->get();
    }

    public function submit()
    {
        if(!$this->newMessage) return;

        $message = ChatMessage::create([
            "sender_id" => Auth::id(),
            "receiver_id" => $this->selectedUser->id,
            "message" => $this->newMessage
        ]);

        $this->messages->push($message);
        $this->newMessage = "";
    }

    public function render()
    {
        return view('livewire.chat');
    }
}
