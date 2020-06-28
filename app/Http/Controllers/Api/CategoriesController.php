<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;

class CategoriesController extends Controller
{
    public function index()
    {
        //返回多个资源集合--栏目分类
        CategoryResource::wrap('data');//资源数据格式统一指定所有资源被data包裹
        return CategoryResource::collection(Category::all());
    }
}