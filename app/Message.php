<?php

namespace app;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    public function getCreatedAtAttribute()
    {
        return \Carbon\Carbon::parse($this->attributes['created_at'])
            ->format('l, M Y H H:i');
    }
}
