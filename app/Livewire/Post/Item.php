<?php

namespace App\Livewire\Post;

use App\Models\Post;
use App\Models\Comment;
use Livewire\Component;
use App\Notifications\PostLikedNotification;

class Item extends Component
{
    public Post $post;

    public $body;

    public function togglePostLike()
    {
        abort_unless(auth()->check(), 401);
        auth()->user()->toggleLike($this->post);

        #send notification is post is liked
        if ($this->post->isLikedBy(auth()->user())) {
            $this->post->user->notify(new PostLikedNotification(auth()->user(), $this->post));
        }
    }

    public function toggleFavorite()
    {
        abort_unless(auth()->check(), 401);

        auth()->user()->toggleFavorite($this->post);        
    }

    public function toggleCommentLike(Comment $comment)
    {
        abort_unless(auth()->check(), 401);

        auth()->user()->toggleLike($comment);        
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
