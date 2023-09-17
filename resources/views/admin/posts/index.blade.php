@extends('layouts.app')

@section('title', 'Posts')

@section('content')
    <h2 class="text-center py-3">Posts</h2>
    <div class="indexContent">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Title</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($posts as $post)
                    <tr>
                        <td>{{ $post->id }}</td>
                        <td>{{ $post->title }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
