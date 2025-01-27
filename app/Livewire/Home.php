<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;
use Livewire\Attributes\On;

class Home extends Component
{
    public $posts;

    #[On('closeModal')]
    public function reverseUrl()
    {
        $this->js("history.replaceState({}, '', '/')");
    }

    #[On('post-created')]
    public function postCreated($id)
    {
        $post = Post::find($id);
        $this->posts = $this->posts->prepend($post);
    }

    public function mount()
    {
        $this->posts = Post::with('comments')->latest()->get();
    }

    public function render()
    {
        return view('livewire.home')->layout('layouts.app');
    }
}
