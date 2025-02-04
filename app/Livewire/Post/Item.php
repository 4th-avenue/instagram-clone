<?php

namespace App\Livewire\Post;

use App\Models\Post;
use App\Models\Comment;
use Livewire\Component;

class Item extends Component
{
    public Post $post;

    public $body;

    function togglePostLike()
    {
        abort_unless(auth()->check(), 401);

        auth()->user()->toggleLike($this->post);
    }

    public function addComment()
    {
        $this->validate([
            'body' => 'required'
        ]);

        // create comment
        Comment::create([
            'body' => $this->body,
            'commentable_id' => $this->post->id,
            'commentable_type' => Post::class,
            'user_id' => auth()->id(),
        ]);

        $this->reset('body');
    }

    public function render()
    {
        return view('livewire.post.item');
    }
}
