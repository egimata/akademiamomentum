@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Post</h1>
    {{-- Form Field --}}
    {!! Form::open(['action'=>['App\Http\Controllers\PostsController@update', $post->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        <div class="form-group">
            {{ Form::label('title', 'Title') }}
            {{ Form::text('title', $post->title, ['class' => 'form-control', 'placeholder' => 'Title']) }}
        </div>
        <div class="form-group">
            {{ Form::label('description', 'Description') }}
            {{ Form::textarea('description', $post->description, ['id' => 'article-ckeditor', 'class' => 'form-control', 'placeholder' => 'Description']) }}
        </div>
        <div class="form-group">
            {{ Form::label('short_description', 'Short Description') }}
            {{ Form::text('short_description', $post->short_description, ['class' => 'form-control', 'placeholder' => 'Short Description']) }}
        </div>
        <div class="form-group">
            {{ Form::file('image') }}
        </div>
        <div class="form-group">
            {{ Form::file('png') }}
        </div>
        {{ Form::hidden('_method', 'PUT') }}
        {!! Form::submit('Submit', ['class'=>'btn btn-primary']) !!}
    {!! Form::close() !!}



</div>
@endsection
