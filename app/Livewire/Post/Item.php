<?php

namespace App\Livewire\Post;

use App\Models\Post;
use App\Models\Comment;
use Livewire\Component;
use App\Events\TestNotification;
use App\Notifications\PostLikedNotification;
use App\Notifications\NewCommentNotification;

class Item extends Component
{
    public Post $post;

    public $body;

    public function togglePostLike()
    {
        abort_unless(auth()->check(), 401);
        auth()->user()->toggleLike($this->post);

        // send notification is post is liked
        if ($this->post->isLikedBy(auth()->user())) {
            if ($this->post->user_id != auth()->id()) {
                $this->post->user->notify(new PostLikedNotification(auth()->user(), $this->post));

                event(new TestNotification([
                    'user_id' => auth()->id(),
                    'post_id' => $this->post->id,
                    // 'id' => ,
                    // 'type' => ,
                ]));
            }
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
        $comment = Comment::create([
            'body' => $this->body,
            'commentable_id' => $this->post->id,
            'commentable_type' => Post::class,
            'user_id' => auth()->id(),
        ]);

        $this->reset('body');

        // notify user 
        if ($this->post->user_id != auth()->id()) {
            $this->post->user->notify(new NewCommentNotification(auth()->user(), $comment));
        }
    }

    public function render()
    {
        return view('livewire.post.item');
    }
}
