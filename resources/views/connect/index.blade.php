@extends('layouts.app')

@section('content')
<form action="{{ route('connect.logout') }}" method="POST" id="disconnect-form">
@csrf
@method('DELETE')
</form>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Connect</div>

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

                    @if( auth()->check() ? ! auth()->user()->facebookUser : false)
                        <a class="btn btn-primary" href="{{ route('facebook.login') }}">
                            Connect your Facebook
                        </a>
                    @else
                        <div class="card-header">
                            Connected as {{ auth()->user()->facebookUser->name }}
                            <span class="badge badge-pill badge-primary"> {{ count(auth()->user()->pages) }} pages</span>

                            <x-reload />
                            <button class="btn btn-danger float-center"
                                onclick="
                                if(confirm('Are you sure you want to disconnect your facebook account')){
                                    document.getElementById('disconnect-form').submit();
                                }
                                "
                            >
                                Disconnect
                            </button>
                        </div>
                        <div class="card-body">
                            @if(auth()->user()->pages)
                            <form action="{{ route('pages.update') }}" method="POST">
                                @csrf
                                @method('PATCH')

                                @foreach (auth()->user()->pages as $page)
                                    <div class="row">
                                        <div class="col-1">
                                            <input type="checkbox" style="cursor: pointer;" {{ $page->active ? 'checked' : '' }} name="{{ $page->id }}">
                                        </div>
                                        <div class="col-9">
                                            <p>{{ $page->name }}</p>
                                        </div>
                                    </div>
                                @endforeach

                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                            @else
                            <div class="row">
                                No pages yet.
                            </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
