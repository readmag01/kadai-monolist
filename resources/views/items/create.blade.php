@extends('layouts.app')

@section('content')
    <div class="search">
        <div class-"row">
            <div class="text-center">
                <!--
                createアクションにルーティングを送るのは、検索をした段階ではまだデータベースに内容を保存しないため
                'method' => 'get'なのでrouteもstoreアクションではなく、createアクションへ送る
                getメソッドでは検索文字がurlの後ろにkeyword=...の形で付き、キャッシュに残る
                -->
                {!! Form::open(['route' => 'items.create', 'method' => 'get', 'class' => 'form-inline']) !!}
                <div class="form-group">
                    <!--
                    $keywordへ値が入りcreateアクションへ送られる
                    -->
                     {!! Form::text('keyword', $keyword, ['class' => 'form-control input-lg', 'placeholder' => 'キーワードを入力', 'size' => 40]) !!}
                </div>
                    {!! Form::submit('商品を検索', ['class' => 'btn btn-success btn-lg']) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    
    @include('items.items', ['items' => $items])
@endsection