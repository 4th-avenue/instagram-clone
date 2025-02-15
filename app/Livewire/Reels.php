<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;
use Livewire\Attributes\On;

class Reels extends Component
{
    #[On('closeModal')]
    public function reverseUrl()
    {
        $this->js("history.replaceState({}, '', '/reels')");
    }

    public function togglePostLike(Post $post)
    {
        abort_unless(auth()->check(), 401);

        auth()->user()->toggleLike($post);
    }

    public function toggleFavorite(Post $post)
    {
        abort_unless(auth()->check(), 401);

        auth()->user()->toggleFavorite($post);        
    }

    public function render()
    {
        $posts = Post::limit(20)->where('type', 'reel')->get();

        return view('livewire.reels', [
            'posts' => $posts
        ])->layout('layouts.app');
    }
}
