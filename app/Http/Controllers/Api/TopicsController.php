<?php

namespace App\Http\Controllers\Api;

use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\TopicResource;
use App\Http\Requests\Api\TopicRequest;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class TopicsController extends Controller
{
    
    public function store(TopicRequest $request, Topic $topic)
    {
        $topic->fill($request->all());
        $topic->user_id = $request->user()->id;
        $topic->save();

        return new TopicResource($topic);
    }
    public function update(TopicRequest $request,Topic $topic)
    {
        $this->authorize('update',$topic);
        $topic->update($request->all());
        return new TopicResource($topic);
    }
    public function destroy (Topic $topic){
        $this->authorize('destroy', $topic);
        $topic->delete();
        return response(null, 204);
    }
    // public function index(Request $request, Topic $topic)
    // {
    //     $query = $topic->query();
    //     if ($categoryId = $request->category_id) {
    //         $query->where('category_id', $categoryId);
    //     }
    //     $topics = $query
    //     ->with('user', 'category')
    //     ->withOrder($request->order)
    //     ->paginate();

    //     return TopicResource::collection($topics);
    // }

    public function index(Request $request, Topic $topic)
    {

        $topics = QueryBuilder::for($topic->query())//Topic::class
            ->allowedIncludes('user','category')//可以被include的参数
            ->allowedFilters([//允许过滤搜索的字段
                'title',//模糊搜索title
                AllowedFilter::exact('category_id'),//精确搜索category_id字段
                AllowedFilter::scope('withOrder')->default('recentReplied'),//本地作用域，传递默认参数
            ])
            ->paginate();

        return TopicResource::collection($topics);
    }
    public function userIndex(Request $request, User $user)
    {
        $query = $user->topics()->getquery();
        $topics = QueryBuilder::for($query)
            ->allowedIncludes('user', 'category')
            ->allowedFilters([
                'title',
                AllowedFilter::exact('category_id'),
                AllowedFilter::scope('withOrder')->default('recentReplied'),
            ])
            ->paginate();

        return TopicResource::collection($topics);
    }
    public function show($topicId)
    {
        $topic = QueryBuilder::for(Topic::class)
            ->allowedIncludes('user', 'category')
            ->findOrFail($topicId);

        return new TopicResource($topic);
    }
}