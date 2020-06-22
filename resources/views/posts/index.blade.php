@extends('layouts.app')

@section('content')
<form action="" method="POST" id="delete-form">
    @csrf
    @method('DELETE')
</form>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Posts</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('errors'))
                        <div class="alert alert-danger" role="alert">
                            @foreach ($errors->all() as $message)
                                <li>{{ $message }}</li>
                            @endforeach

                        </div>
                    @endif

                    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#createPost">
                        Create Post
                    </button>
                    @if(! auth()->user()->pages()->where('active', true)->first())
                    <p class="alert alert-warning">
                        No page selected, do it <a href="{{ route('connect.index')}}" class="btn-link">here</a>
                    </p>
                    @endif

                    <table class="table">
                        <thead>
                            <th scope="col">Post</th>
                            <th scope="col">Page</th>
                            <th scope="col">Status</th>
                            <th scope="col">Date</th>
                            <th scope="col">Actions</th>
                        </thead>
                        <tbody>
                            @forelse ($posts as $post)
                                <tr>
                                    <td>{{ $post->name }}</td>
                                    <td>{{ $post->page->name }}</td>
                                    <td>{{ $post->status ? 'Scheduled' : 'Posted' }}</td>
                                    <td>{{ $post->date }}</td>
                                    <td>
                                        <button
                                            href="#"
                                            class="btn {{ $post->status ? 'btn-warning' : 'btn-danger' }}"
                                            onclick="
                                            if(confirm('Are you sure to {{ $post->status ? 'cancel' : 'delete' }} this post?')){
                                                document.getElementById('delete-form').action = '{{ route('posts.destroy', ['post' => $post->id ]) }}';
                                                document.getElementById('delete-form').submit();
                                            }

                                            "
                                            >
                                            {{ $post->status ? 'Cancel' : 'Delete' }}
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">No post yet</td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
<x-create-modal />
@endsection
