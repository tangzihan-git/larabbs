<?php

namespace App\Models;

class Topic extends Model
{
    protected $fillable = [
        'title', 'body', 'user_id', 'category_id', 'reply_count',
        'view_count', 'last_reply_user_id', 'order', 'excerpt', 'slug',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    //调用排序函数
    public function scopewithOrder($query,$order)
    {
        switch($order){
            case "recent":
                $query->recent();
            break;
            default :
                $query->RecentReplied();
        }
    }
    //最新发布
    public function scopeRecent($query)
    {
        return $query->orderBy("created_at","desc");

    }
    //最新回复
    public function scopeRecentReplied($query)
    {
        return $query->orderBy("updated_at","desc");
    }
}