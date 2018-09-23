<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Item;

class ItemUserController extends Controller
{
    
//want
    
    public function want() {
        
        $itemCode = request()->itemCode;
        
        /*
        itemCodeは楽天での商品IDのようなもの
         "Items": [
            {
              "Item": {
                "itemName": "クリスタルガイザー ミネラルウォーター 500ml×48本(並行輸入品)【楽天24】[クリスタルガイザー ミネラルウォーター 軟水]【HOF19】",
                "catchcopy": "クリスタルガイザー ミネラルウォーター 500ml×48本(並行輸入品)/クリスタルガイザー/ミネラルウォーター/送料無料",
                "itemCode": "rakuten24:10130129",
                "itemPrice": 1566,
                "itemCaption": "※パッケージデザイン等は予告なく変更されることがあります。...
              }
              
              itemCodeで商品を検索
        */
        $client = new \RakutenRws_Client();
        $client->setApplicationId(env('RAKUTEN_APPLICATION_ID'));
        $rws_response = $client->execute('IchibaItemSearch', [
            'itemCode' => $itemCode,
            ]);
            
            /*
            ['Items'][順番]['Item']
            */
        $rws_item = $rws_response->getData()['Items'][0]['Item'];
        
            /*
            firstOrCreateでは、第一引数に検索で得たい内容と同じ内容のレコード（以前作成されたレコード）が
            存在する場合はそれを取得して、今回初めて検索した内容ならば新規レコードで保存する関数
            データがあるかどうかチェックするためのif文を書く必要がなかったり、最初に変数をnullで初期化する
            必要がなくなる
            
            ここでてitemsテーブルにcode、name、url、image_urlなどを保存するためのデータを取得
            次のwant()で保存を実行
            つまりwant、haveする商品のみitemsテーブルに保存される
            */
        $item = Item::firstOrCreate([
            'code' => $rws_item['itemCode'],
            'name' => $rws_item['itemName'],
            'url' => $rws_item['itemUrl'],
            'image_url' => str_replace('?_ex=128×128', '', $rws_item['mediumImageUrls'][0]['imageUrl']),
            ]);
            
            /*
            ここの->want()はUserモデルのwantメソッド
            最後に中間テーブルでユーザと商品のwantの関係を作る
            */
            \Auth::user()->want($item->id);
            
            return redirect()->back();
    }
    
    
    public function dont_want() {
        
        $itemCode = request()->itemCode;
        
        /*
        is_wanting()やdont_want()の処理はUserクラス内にある
        */
        if(\Auth::User()->is_wanting($itemCode)) {
            $itemId = Item::where('code', $itemCode)->first()->id;
            \Auth::user()->dont_want($itemId);
        }
        
        return redirect()->back();
    }
    
    
    
//have
    
    public function have()
    {
        $itemCode = request()->itemCode;
        
        
            //itemCodeで商品を検索
        $client = new \RakutenRws_Client();
        $client->setApplicationId(env('RAKUTEN_APPLICATION_ID'));
        $rws_response = $client->execute('IchibaItemSearch', [
            'itemCode' => $itemCode,
            ]);
        
        $rws_item = $rws_response->getData()['Items'][0]['Item'];
        
        $item = Item::firstOrCreate([
            'code' => $rws_item['itemCode'],
            'name' => $rws_item['itemName'],
            'url' => $rws_item['itemUrl'],
            'image_url' => str_replace('?_128×128', '', $rws_item['mediumImageUrls'][0]['imageUrl']),
            
            ]);
        
        //ここでhaveされたitemがレコードに保存される
        \Auth::user()->have($item->id);
        
        return redirect()->back();
    }
    
    
    public function dont_have()
    {
        $itemCode = request()->itemCode;
        
        if(\Auth::user()->is_having($itemCode)){
            $itemId = Item::where('code', $itemCode)->first()->id;
            \Auth::user()->dont_have($itemId);
        }
        
        return redirect()->back();
        
    }
    
}
