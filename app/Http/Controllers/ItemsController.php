<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \App\Item;

class ItemsController extends Controller
{
    public function create (){
        
            /*
            view側でForm::text('keyword')から送られ、$requestとして受け取り
            その中keywordを取得し、$keywordとして受け取る
            */
        $keyword = request()->keyword;
        
            /*
            createページが開かれた時点ではまだキーワードは入力されておらず
            カラの配列を入れて初期化しておかないと、createページが開かれた時点で
            $items = nullになってしまい、エラーが出る（空白で検索したことになる）
            $itemに値が入れるのは検索ワードが入力されたときのみ
            */
        $items = [];
        
            /*
            requestからkeywordを取り出し、$keywordに値が与えられるとifの実行
            条件指定した内容を$client->executeで実行し$rws_responseプロパティに代入
            */
        
        if($keyword) {
            $client = new \RakutenRws_Client();
            $client->setApplicationId(env('RAKUTEN_APPLICATION_ID'));
            $rws_response = $client->execute('IchibaItemSearch', [
                'keyword' => $keyword,
                'image_Flag' => 1,
                'hits' => 20,
                ]);
            
            
            /*
            条件指定・実行で代入された$rws_responseをforeachで繰り返し処理
            ['Items']や['Item']は以下を指している
             "Items": [
                    {
                      "Item": {
                        "itemName": "クリスタルガイザー 
                        "catchcopy": "クリスタルガイザー ミネラルウォー
                        "itemCode": "rakuten24:10130129",
                        "itemPrice": 1566,
                        "itemCaption": "※パッケージデザイン等は予告なく変更されることがあります。...
                    }
            複数データを$rws_itemで1件ごとに表示し、['Item']の中の['itemCode']などを指して
            $item->codeなどに代入している
            
            最後の$items[] = $itemで初期化していた$itemsにデータを追加している
            追加しただけでデータベース（itemsテーブル）へ保存はしていない点に注意
            */
                foreach($rws_response->getData()['Items'] as $rws_item) {
                    $item = new Item();
                    $item->code = $rws_item['Item']['itemCode'];
                    $item->name = $rws_item['Item']['itemName'];
                    $item->url = $rws_item['Item']['itemUrl'];
                    $item->image_url = str_replace('?_ex=128×128', '', $rws_item['Item']['mediumImageUrls'][0]['imageUrl']);
                    $items[] = $item;
                }
                
        }
        
        return view('items.create', [
            'keyword' => $keyword,
            'items' => $items,
            ]);
        
    }
    
    public function show($id) {
        
        
        //want_usersの処理はItemモデルに記載
        $item = Item::find($id);
        $want_users = $item->want_users;
        $have_users = $item->have_users;
        
        return view('items.show', [
            'item' => $item,
            'want_users' => $want_users,
            'have_users' => $have_users,
            ]);
    }
}
