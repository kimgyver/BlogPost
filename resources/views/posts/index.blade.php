@extends('layout')

@section('content')
<div class="row">
   <div class="col-8"> 
    @forelse ($posts as $post)
        <p>
            <h3> 
                @if ($post->trashed())
                    <del>
                @endif
                <a class="{{ $post->trashed() ? 'text-muted' : '' }}"
                    href="{{ route('posts.show', ['post'=>$post->id])}}">{{ $post->title }}</a>
                @if ($post->trashed())
                    </del>
                @endif
            </h3>
            {{-- <p class="text-muted">
                Added {{ $post->created_at->diffForHumans() }}
                by {{ $post->user->name }}
            </p> --}}

            @updated(['date' => $post->created_at, 'name' => $post->user->name])
            @endupdated

            @if ($post->comments_count)
                <p>{{ $post->comments_count }} comments</p>
            @else
                <p>No comments yet!</p>
            @endif
            

            @auth
                @can('update', $post)
                <a href="{{ route('posts.edit', ['post'=>$post->id])}}" class="btn btn-primary">
                    Edit
                </a>
                @endcan
            @endauth
            
            {{-- @cannot('delete', $post)
                <p>You cannot delete this post!</p>
            @endcannot --}}

            @auth
                @if (!$post->trashed())
                    @can('delete', $post)
                    <form method="post" class="fm-inline"
                        action="{{ route('posts.destroy', ['post' => $post->id])}}">
                        @csrf
                        @method('delete')
                        <input type="submit" value="Delete!" class="btn btn-primary"/>
                    </form>
                    @endcan
                @endif
            @endauth
        </p>
    @empty
        <p>No blog posts yet!</p>
    @endforelse
   </div>
   <div class="col-4">
       <div class="container">
           <div class="row">
                @card(['title' => 'Most Commented'])
                    @slot('subtitle')
                        What people are currently talking about
                    @endslot
                    @slot('items')
                        @foreach ($mostCommentedPosts as $post)
                            <li class="list-group-item">
                                <a href="{{ route('posts.show', ['post' => $post->id]) }}">
                                    {{ $post->title }}
                                </a>
                            </li>    
                        @endforeach
                    @endslot
                @endcard
            </div>

            <div class="row mt-4">
                @card(['title' => 'Most Active'])
                    @slot('subtitle')
                        Users with most posts written
                    @endslot
                    @slot('items', collect($mostActive)->pluck('name'))
                @endcard
            </div>

            <div class="row mt-4">
                @card(['title' => 'Most Active Last Month'])
                    @slot('subtitle')
                        Users with most posts written in the last month
                    @endslot
                    @slot('items', collect($mostActiveLastMonth)->pluck('name'))
                @endcard
            </div>
       </div>
   </div>
</div>
@endsection('content')