@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">New post</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if(auth()->user()->verified == 1)
                        <form method="POST" action="{!! url('posts') !!}">
                            @csrf

                            <div class="form-group row">
                                <label for="category_id" class="col-md-4 col-form-label text-md-right">{{ __('Category') }}</label>

                                <div class="col-md-6">
                                    <select name="category_id" id="category_id" class="form-control">
                                        @foreach($categories as $category)
                                            <option value="{!! $category->id !!}">{!! implode(' - ', $category->name) !!}</option>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('title'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('title') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="title" class="col-md-4 col-form-label text-md-right">{{ __('Title') }}</label>

                                <div class="col-md-6">
                                    <input id="title" type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="title" value="{{ old('title') }}" required>

                                    @if ($errors->has('title'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('title') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="content" class="col-md-4 col-form-label text-md-right">{{ __('Content') }}</label>

                                <div class="col-md-6">
                                    <textarea  id="content" class="form-control{{ $errors->has('content') ? ' is-invalid' : '' }}" name="content" required>{!! old('content') !!}</textarea>

                                    @if ($errors->has('content'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('content') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Post') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    @else
                        Please Verify your email
                    @endif


                </div>
            </div>

            @if(auth()->user()->verified == 1)

                @foreach($posts as $post)
                    <div class="card" style="margin: 20px 0;">
                        <div class="card-header">
                            <h2 style="font-size: 18px">{!! $post->title !!}</h2>
                            @if(auth()->id() == $post->user_id)
                                <button data-toggle="modal" data-target="#editPost-{!! $post->id !!}" class="btn btn-primary">Edit</button>
                                <form style="display: inline-block; margin: 10px 10px;" action="{!! url('posts/'.$post->id) !!}" method="post">
                                    @csrf
                                    {!! method_field('delete') !!}
                                    <input type="submit" value="Delete" class="btn btn-danger">
                                </form>
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-3">
                                    <p class="text-center">By: {!! $post->user->name !!}</p>
                                </div>
                                <div class="col-md-3">
                                    <p class="text-center">{!! implode(' - ',$post->category->name) !!}</p>
                                </div>

                                <div class="col-md-3">
                                    <p class="text-center">{!! \Carbon\Carbon::parse($post->created_at)->diffForHumans() !!}</p>
                                </div>

                                <div class="col-md-3">
                                    <p class="text-center">Comments: {!! $post->comments->count() !!}</p>
                                </div>
                            </div>
                            <div class="row">
                                <img src="{!! $post->user->image !!}" class="img-responsive img-thumbnail" style="display: block; margin: 0 auto; width: 150px;height: 150px;">
                            </div>
                            <div class="row">
                                <div style="padding: 40px;" class="text-center">
                                    {!! $post->content !!}
                                </div>

                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-12">
                                    <form action="{!! url('posts/'.$post->id.'/comments') !!}" method="post">
                                        @csrf
                                        <input type="text" name="comment" value="" class="form-control">
                                    </form>
                                </div>

                            </div>
                            <div class="row">
                                @foreach($post->comments as $comment)
                                <div style="padding: 10px; margin: 10px 0;border: 1px solid #ccc;border-radius: 5px;" class="col-md-12">
                                    <img src="{!! url($comment->user->image) !!}" width="50" height="50" class="img-responsive img-thumbnail">
                                    {!! $comment->content !!}
                                    @if(auth()->id() == $comment->user->id)
                                        <form style="display: inline-block; margin: 10px 10px;" action="{!! url('posts/'.$post->id.'/comments/'.$comment->id) !!}" method="post">
                                            @csrf
                                            {!! method_field('delete') !!}
                                            <input type="submit" value="Delete" class="btn btn-danger">
                                        </form>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Edit post modal-->
                    @if(auth()->id() == $post->user_id)
                        <div id="editPost-{!! $post->id !!}" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Edit post</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{!! url('posts/'.$post->id) !!}" method="post">
                                            {!! csrf_field() !!}
                                            {!! method_field('put') !!}
                                            <div class="form-group row">
                                                <label for="category_id" class="col-md-4 col-form-label text-md-right">{{ __('Category') }}</label>

                                                <div class="col-md-6">
                                                    <select name="category_id" id="category_id" class="form-control">
                                                        @foreach($categories as $category)
                                                            <option @if($category->id == $post->category_id) selected @endif value="{!! $category->id !!}">{!! implode(' - ', $category->name) !!}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="title" class="col-md-4 col-form-label text-md-right">{{ __('Title') }}</label>

                                                <div class="col-md-6">
                                                    <input id="title" type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="title" value="{{ $post->title }}" required autofocus>

                                                    @if ($errors->has('title'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('title') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="content" class="col-md-4 col-form-label text-md-right">{{ __('Content') }}</label>

                                                <div class="col-md-6">
                                                    <textarea  id="content" class="form-control{{ $errors->has('content') ? ' is-invalid' : '' }}" name="content" required>{!! $post->content !!}</textarea>

                                                    @if ($errors->has('content'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('content') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group row mb-0">
                                                <div class="col-md-6 offset-md-4">
                                                    <button type="submit" class="btn btn-primary">
                                                        {{ __('Edit') }}
                                                    </button>
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
                {!! $posts->links() !!}
            @endif
        </div>
    </div>
</div>
@endsection
