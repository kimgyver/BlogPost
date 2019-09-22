@extends('layout')

@section('content')
    <form method="post" action="{{ route('posts.update', ['post' => $post->id])}}" enctype="multipart/form-data">
        @csrf
        @method('put')

        @include('posts._form')

        <button type="submit" class="btn btn-primary btn-block">Update</button>
    </form>
@endsection