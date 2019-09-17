@extends('layout')

@section('content')
    <h1>{{ $post->title }}</h1>
    <p>{{ $post->content }}</p>

    @if ($post->created_at != null)
        <p>Added {{ $post->created_at->diffForHumans() }}</p>
    @endif

    @if ((new Carbon\Carbon())->diffInMinutes($post->created_at) < 5)
        <strong>New!</strong>
    @endif

    <h4>Comments</h4>
    @forelse($post->comments as $comment)
        <p>
            {{ $comment->content }}
        </p>
        <p class="text_muted">
            added {{ $comment->created_at->diffForHumans() }}
        </p>
    @empty
        <p>No comments yet!</p>
    @endforelse
@endsection('content')