<?php

namespace App\Livewire\Profile;

use App\Models\Post;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;

class Saved extends Component
{
    public $user;

    #[On('closeModal')]
    public function reverseUrl()
    {
        $this->js("history.replaceState({}, '', '/')");
    }

    function toggleFollow()
    {
        abort_unless(auth()->check(), 401);
        auth()->user()->toggleFollow($this->user);
    }

    public function mount($user)
    {
        $this->user = User::whereUsername($user)->withCount(['followers', 'followings', 'posts'])->firstOrFail();
    }

    public function render()
    {
        $this->user = User::whereUsername($this->user->username)->withCount(['followers', 'followings', 'posts'])->firstOrFail();

        $posts = $this->user->getFavoriteItems(Post::class)->get();

        return view('livewire.profile.saved', [
            'posts' => $posts
        ])->layout('layouts.app');
    }
}
