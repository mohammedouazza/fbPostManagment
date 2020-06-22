@extends('layouts.app')

@section('content')
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
                            <span class="pull-center"> | {{ count(auth()->user()->pages) }} pages</span>
                            <x-reload />
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
