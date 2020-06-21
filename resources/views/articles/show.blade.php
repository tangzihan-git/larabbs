@extends('layouts.app')

@section('content')

<div class="container">
  <div class="col-md-10 offset-md-1">
    <div class="card ">
      <div class="card-header">
        <h1>Article / Show #{{ $article->id }}</h1>
      </div>

      <div class="card-body">
        <div class="card-block bg-light">
          <div class="row">
            <div class="col-md-6">
              <a class="btn btn-link" href="{{ route('articles.index') }}"><- Back</a>
            </div>
            <div class="col-md-6">
              <a class="btn btn-sm btn-warning float-right mt-1" href="{{ route('articles.edit', $article->id) }}">
                Edit
              </a>
            </div>
          </div>
        </div>
        <br>

        <label>Title</label>
<p>
	{{ $article->title }}
</p> <label>Desc</label>
<p>
	{{ $article->desc }}
</p> <label>Content</label>
<p>
	{{ $article->content }}
</p> <label>User_id</label>
<p>
	{{ $article->user_id }}
</p>
      </div>
    </div>
  </div>
</div>

@endsection
