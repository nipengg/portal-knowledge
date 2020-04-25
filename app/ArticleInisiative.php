<?php

namespace app;

use Illuminate\Database\Eloquent\Model;

class ArticleInisiative extends Model
{
    protected $fillable = [
        'title', 'content'
    ];

    public function user()
    {
      return $this->belongsTo('App\User','id','user_id');
    }
}
