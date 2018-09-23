<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Item;

class RankingController extends Controller
{
    
    public function want()
    {
        /*
        \DB::table('item_user')　中間であるitem_userテーブルを指す
        join('items', 'item_user.item_id', '=', 'items.id')　itemsテーブルのidとitem_userテーブルのitem_idを結合
        select('items.*', \DB::raw('COUNT(*) as count'))　取得するカラムを選択し、COUNT(*) AS countでそのカラムの集計をする
        where('type', 'want')　タイプがwantのものだけに限定
        groupBy('items.id')　itemsテーブルにあるカラムを全て使ってグループ化して
                            ここで全ユーザが Want, Have した商品の重複をグループ化してまとめている
        orderBy('count', 'DESC')　COUNT(*) AS count としたので、一時的にできるカラム名はcountにしている
        take(10) ランキングを10個まで取得
        */
        $items = \DB::table('item_user')->join('items', 'item_user.item_id', '=', 'items.id')->select('items.*', \DB::raw('COUNT(*) as count'))->where('type', 'want')->groupBy('items.id', 'items.code', 'items.name', 'items.url', 'items.image_url', 'items.created_at', 'items.updated_at')->orderBy('count', 'DESC')->take(10)->get();
        
        return view('ranking.want', [
            'items' => $items,
            ]);
    }
}
