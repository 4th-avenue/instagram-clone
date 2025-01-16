@props([
    'source'=>'https://cdn.devdojo.com/pines/videos/coast.mp4'
])

<div x-data="{playing:false, muted:false}">
    <video controls class="h-full max-h-[500px] w-full">
        <source src="{{$source}}" type="video/mp4">
        your browser does not support html5 video 
    </video>
</div>