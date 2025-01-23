<?php

namespace App\Livewire\Post;

use App\Models\Post;
use App\Models\Media;
use Livewire\WithFileUploads;
use LivewireUI\Modal\ModalComponent;
use Illuminate\Support\Facades\Storage;

class Create extends ModalComponent
{
    use WithFileUploads;

    public $media = [];
    public $description;
    public $location;
    public $hide_like_view = false;
    public $allow_commenting = false;

    /**
     * Supported: 'sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl'
     */
    public static function modalMaxWidth(): string
    {
        return '4xl';
    }

    public function submit()
    {
        $this->validate([
            'media.*'=>'required|file|mimes:jpg,jpeg,png,mp4,mov|max:100000',
            'hide_like_view'=>'boolean',
            'allow_commenting'=>'boolean',
        ]);

        #determine if real or post
        $type= $this->getPostType($this->media);

        #create post
        $post = Post::create([
            'user_id' => auth()->user()->id,
            'description' => $this->description,
            'location' => $this->location,
            'allow_commenting' => $this->allow_commenting,
            'hide_like_view' => $this->hide_like_view,
            'type' => $type
        ]);

        #add media
        foreach ($this->media as $key => $media) {
            #get mime type
            $mime = $this->getMime($media);

            #save to storage
            $path = $media->store('media','public');
            $url = url(Storage::url($path));

            #create media 
            Media::create([
                'url' => $url,
                'mime' => $mime,
                'mediable_id' => $post->id,
                'mediable_type' => Post::class
            ]);

            $this->reset();
            $this->dispatch('close');
        }
    }

    function getMime($media): string
    {
        if (str()->contains($media->getMimeType(), 'video')) {
            return 'video';
        } else {
            return 'image';
        }
    }

    function getPostType($media): string
    {
        if (count($media)===1 && str()->contains($media[0]->getMimeType(), 'video')) {
            return 'reel';
        } else {
            return 'post';
        }
    }

    public function render()
    {
        return view('livewire.post.create');
    }
}
