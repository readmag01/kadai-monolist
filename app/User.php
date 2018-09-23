<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function items() {
        
        /*
         $this(Userクラス・モデル)は多くのアイテムのwant、haveに関連している
         ユーザーがwant、haveしたアイテムを取得
        */
        return $this->belongsToMany(Item::class)->withPivot('type')->withTimestamps();
    }
    
    
//want


    public function want_items() {
        
        return $this->items()->where('type', 'want');
    }
    
    public function want($itemId) {
        
        $exist = $this->is_wanting($itemId);
        
        if($exist){
            return false;
        } else {
            $this->items()->attach($itemId, ['type' => 'want']);
            return true;
        }
        
    }
    
    public function dont_want($itemId) {
        
        $exist = $this->is_wanting($itemId);
        
        if($exist) {
            
            /*
            ->detach()ではtypeを絞り込んで削除できないので、直接SQL文で削除している
            */
            \DB::delete("DELETE FROM item_user WHERE user_id = ? AND item_id = ? AND type = 'want'",[$this->id, $itemId]);
        } else {
            return false;
        }
    }
    
    public function is_wanting($itemIdOrCode) {
        
        /*
        is_numeric()は'1234'という数字の文字列であってもは整数だと判断する
        $itemIdとAPIから受け取るitemCode両方を判定しなければならないので$ItemIdOrCodeとなる
        */
        if(is_numeric($itemIdOrCode)) {
            
            /*
            中間テーブルのitem_idカラムの$itemIdOrCodeをチェックし、取得する
            */
            $item_id_exists = $this->want_items()->where('item_id', $itemIdOrCode)->exists();
            return $item_id_exists;
        } else {
             /*
            itemsテーブルのcodeカラムを$itemIdOrCodeとする場合
            */
            $item_code_exists = $this->want_items()->where('code', $itemIdOrCode)->exists();
            return $item_code_exists;
        }

        
    }
    
  
    
//have   
  
    
    public function have_items() 
    {
        
        return $this->items()->where('type', 'have');
    }
    
    
    public function have($itemId) 
    {
        
        $exist = $this->is_having($itemId);
        
        if($exist) {
            return false;
        } else {
            $this->items()->attach($itemId, ['type' => 'have']);
            return true;
        }
    }
    
    
    public function dont_have($itemId)
    {
        
        $exist = $this->is_having($itemId);
        
        if($exist){
            \DB::delete("DELETE FROM item_user WHERE user_id = ? AND item_id = ? AND type = 'have'", [$this->id, $itemId]);
        } else {
            return false;
        }
        
    }
    
    
    public function is_having($itemIdOrCode)
    {
            /*
            中間テーブルのitem_idカラム=$itemIdOrCode
            itemsテーブルのcodeカラム=$itemIdOrCode
            where(A, B)ではA=Bと考える
            */
        if(is_numeric($itemIdOrCode)){
            $item_id_exists = $this->have_items()->where('item_id', $itemIdOrCode)->exists();
            return $item_id_exists;
        } else {
            $item_code_exists = $this->have_items()->where('code', $itemIdOrCode)->exists();
            return $item_code_exists;
        }
    }
    
    
}
