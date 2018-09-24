@if ($items)
    <div class="row">
        
        <!--$itemsを$key as $itemの形で取り出
        例：$preflist = ['Tokyo' => '東京', 'Osaka' => '大阪'];
            foreach ($preflist as $key => $value){
            print $key.'=>'.$value.;　 TOKYO=>東京、Osaka=>大阪-->
        @foreach ($items as $key => $item)
            <div class="item">
                <div class="col-md-3 col-sm-4 col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading text-center">
                            <img src="{{ $item->image_url }}" alt="">
                        </div>
                        <div class="panel-body">
                            @if($item->id)
                                <p class="item-title"><a href="{{ route('items.show', $item->id) }}">{{ $item->name }}</a></p>
                                @else
                                <p class="item-title">{{ $item->name }}</p>
                                @endif
                                <div class="buttons text-center">
                                @if(Auth::check())
                                    @include('items.want_button', ['item' => $item])
                                    @include('items.have_button', ['item' => $item])
                                @endif
                            </div>
                        </div>
                        @if(isset($item->count))
                            <div class="panel-footer">
                                
                                <!--
                                ランキングを取得するSQ で下記のようにcountを取得するように指定していたので
                                RankingController からの$itemの中にはcountの値が入っている
                                それを利用して$item->countの値が存在した場合にはランキング表示させている
                                select('items.*', \DB::raw('COUNT(*) AS count'))
                                -->
                                <p class="text-center">{{ $key+1 }}位： {{ $item->count }} points</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif