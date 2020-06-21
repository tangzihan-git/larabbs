<?php

namespace App\Observers;

use App\Models\Topic;
use App\Handlers\SlugTranslateHandler;
// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
    //自动生成摘要
    public function saving(Topic $topic)
    {
       $topic->body = clean($topic->body,'user_topic_body');
       $topic->excerpt = make_excerpt($topic->body);//话题摘录
       //如果slug字段没有内容 调用翻译
       if(! $topic->slug){
       	    $topic->slug = app(SlugTranslateHandler::class)->translate($topic->title);
       }
    }
}