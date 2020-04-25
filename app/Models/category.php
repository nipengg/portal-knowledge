<?php

namespace app;

use Illuminate\Database\Eloquent\Model;

class category extends Model
{
    public function questions(){
        return $this->belongsTo('Question');
    }
}
