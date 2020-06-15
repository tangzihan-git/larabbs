<?php

namespace App\Observers;

use App\Models\Topic;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
    //自动生成摘要
    public function saving(Topic $topic)
    {
       $topic->body = clean($topic->body,'user_topic_body');
       $topic->excerpt = make_excerpt($topic->body);
    }
}