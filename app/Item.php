<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['code', 'name', 'url', 'image_url'];
    
    public function users() {
        
        /*
        withPivot('')で中間テーブルの指定カラムの値を取得する
        $this(itemクラス・モデル)は多くのユーザーのwant、haveに属する（関連していると捉えた方が良いかも）
        want、haveしているユーザーを取得する
        */
        return $this->belongsToMany(User::class)->withPivot('type')->withTimestamps();
    }
    
    
        /*
        wantしたユーザーのみを取得
        */
    public function want_users() {
        
        return $this->users()->where('type', 'want');
    }
    

        /*
        haveしたユーザーのみを取得
        */    
    public function have_users() {
        
        return $this->users()->where('type', 'have');
    }
    
}
