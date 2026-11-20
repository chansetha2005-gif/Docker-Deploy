@extends('layouts.app')

@section('title', 'All Posts')

@section('content')
    <div class="header">
        <h1>Posts</h1>
        <a href="{{ route('posts.create') }}" class="btn">+ New Post</a>
    </div>

    @if ($posts->isEmpty())
        <p class="muted">No posts yet. Create the first one!</p>
    @else
        <table>
            <thead>
                <tr><th>#</th><th>Title</th><th>Created</th><th>Actions</th></tr>
            </thead>
            <tbody>
            @foreach ($posts as $post)
                <tr>
                    <td>{{ $post->id }}</td>
                    <td>{{ $post->title }}</td>
                    <td class="muted">{{ $post->created_at->format('Y-m-d H:i') }}</td>
                    <td class="actions">
                        <a href="{{ route('posts.show', $post) }}" class="btn btn-gray">View</a>
                        <a href="{{ route('posts.edit', $post) }}" class="btn btn-yellow">Edit</a>
                        <form action="{{ route('posts.destroy', $post) }}" method="POST"
                              onsubmit="return confirm('Delete this post?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-red">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @if ($posts->hasPages())
            <div class="pager actions">
                @if ($posts->previousPageUrl())
                    <a href="{{ $posts->previousPageUrl() }}" class="btn btn-gray">&laquo; Prev</a>
                @endif
                <span class="muted" style="align-self: center;">Page {{ $posts->currentPage() }} of {{ $posts->lastPage() }}</span>
                @if ($posts->nextPageUrl())
                    <a href="{{ $posts->nextPageUrl() }}" class="btn btn-gray">Next &raquo;</a>
                @endif
            </div>
        @endif
    @endif
@endsection
