@if(Auth::user()->is_wanting($item->code))

    <!--
    'route' => 'item_user.dont_want'や'user_item.want'に送られる内容は$requestだけなので
    wantやdont_wantする商品のitemCodeもItemUserControlleに渡す必要がある
    するとコントローラ側でrequest()->itemCodeとして特定商品のデータを取得できる
    -->

    {!! Form::open(['route' => 'item_user.dont_want', 'method' => 'delete']) !!}
        {!! Form::hidden('itemCode', $item->code) !!}
        {!! Form::submit('Want', ['class' => 'btn btn-success']) !!}
    {!! Form::close() !!}
@else
    {!! Form::open(['route' => 'item_user.want']) !!}
        {!! Form::hidden('itemCode', $item->code) !!}
        {!! Form::submit('Want it', ['class' => 'btn btn-primary']) !!}
    {!! Form::close() !!}
@endif