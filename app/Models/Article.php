<?php

namespace App\Models;

class Article extends Model
{
    protected $fillable = ['title', 'desc', 'content', 'user_id'];
}
