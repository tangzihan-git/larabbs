<?php

namespace App\Http\Controllers\Api;

use App\Models\Topic;
use App\Models\Reply;
use Illuminate\Http\Request;
use App\Http\Resources\ReplyResource;
use App\Http\Requests\Api\ReplyRequest;
use App\Http\Queries\ReplyQuery;
class RepliesController extends Controller
{
    public function store(ReplyRequest $request, Topic $topic, Reply $reply)
    {
        $reply->content = $request->content;
        $reply->topic()->associate($topic);//传入关联模型的id值，把当前要回复的话题 id 赋值给 Reply 模型的 topic_id 字段
        $reply->user()->associate($request->user());
        $reply->save();

        return new ReplyResource($reply);
    }
    public function index($topicId, ReplyQuery $query)
    {
        $replies = $query->where('topic_id',$topicId)->paginate();

        return ReplyResource::collection($replies);
    }
    public function userIndex($userId, ReplyQuery $query)
    {
        $replies = $query->where('user_id', $userId)->paginate();

        return ReplyResource::collection($replies);
    }
}