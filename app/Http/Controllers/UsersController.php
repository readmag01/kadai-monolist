<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\User;
use App\Item;

class UsersController extends Controller
{
    
    public function show($id) {
        
        /*
        $user->itemsではwantもhaveもしたアイテムが取得されてしまう。
        そのためSQL文でwantした商品のみを取り出している
        
        DB::table('items')　結合したいテーブル
        join('item_user', 'items.id', '=', 'item_user.item_id')　結合させたいテーブル
        1:テーブル名　2:結合したいテーブルのカラム名　4:結合させたいテーブルのカラム名
        ->select('items.*')　itemsテーブルの全カラムを取得
        ->where('item_user.user_id', $user->id)　item_user.user_idに$user->id(uesrsテーブル)を含む場合
        ->distinct() 重複をまとめてからデータを取得
        */
        $user = User::find($id);
        $count_want = $user->want_items()->count();
        $count_have = $user->have_items()->count();
        $items = \DB::table('items')->join('item_user', 'items.id', '=', 'item_user.item_id')->select('items.*')->where('item_user.user_id', $user->id)->distinct()->paginate(20);
        
        return view('users.show', [
            'user' => $user,
            'items' => $items,
            'count_want' => $count_want,
            'count_have' => $count_have,
            
            ]);
    } 
    
}
