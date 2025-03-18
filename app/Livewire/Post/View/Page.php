<?php

namespace App\Livewire\Post\View;

use App\Models\Post;
use Livewire\Component;

class Page extends Component
{
    public $post;

    public function mount() {
        $this->post = Post::findOrFail($this->post);
    }

    public function render()
    {
        return view('livewire.post.view.page')->layout('layouts.app');
    }
}
