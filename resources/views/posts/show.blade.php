@extends('layouts.app')

@section('title', $post->title)

@section('content')
    <div class="header">
        <h1>{{ $post->title }}</h1>
        <a href="{{ route('posts.index') }}" class="btn btn-gray">Back</a>
    </div>
    <p class="muted">
        Created {{ $post->created_at->format('Y-m-d H:i') }} &middot;
        Updated {{ $post->updated_at->format('Y-m-d H:i') }}
    </p>
    <div style="white-space: pre-wrap; line-height: 1.6;">{{ $post->content }}</div>
    <div class="actions" style="margin-top: 20px;">
        <a href="{{ route('posts.edit', $post) }}" class="btn btn-yellow">Edit</a>
        <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Delete this post?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-red">Delete</button>
        </form>
    </div>
@endsection
