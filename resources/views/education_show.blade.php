@extends('layouts.public')

@section('title', $article->title . ' - AnarcyxReptile')

@section('content')

<div style="max-width: 800px; margin: 60px auto; padding: 0 4%; font-family: 'Plus Jakarta Sans', sans-serif;">
    <a href="{{ route('education') }}" style="text-decoration: none; color: #4A5C3A; font-weight: 700; display: inline-flex; align-items: center; gap: 8px; margin-bottom: 30px;">&larr; Kembali ke Edukasi</a>

    <span style="background: #F3F4F0; color: #4A5C3A; padding: 6px 14px; border-radius: 20px; font-size: 0.8rem; font-weight: 800; text-transform: uppercase;">{{ $article->category }}</span>
    <h1 style="font-size: 2.5rem; font-weight: 800; color: #111; margin: 15px 0 25px 0; line-height: 1.3;">{{ $article->title }}</h1>

    <hr style="border: 0; border-top: 1px solid #E5E5E5; margin-bottom: 35px;">

    @if(!empty($article->image))
        <div style="width: 100%; max-height: 400px; overflow: hidden; border-radius: 16px; margin-bottom: 35px; box-shadow: 0 8px 25px rgba(0,0,0,0.05);">
            <img src="{{ asset('images/education/' . $article->image) }}" alt="{{ $article->title }}" style="width: 100%; height: 100%; object-fit: cover; display: block;">
        </div>
    @endif

    <div style="font-size: 1.1rem; color: #333333; line-height: 1.8; font-weight: 500; text-align: left; white-space: pre-line;">
        {!! $article->content !!}
    </div>
</div>

@endsection
