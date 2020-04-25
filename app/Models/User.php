<?php

namespace app;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Model
{
    //
    public function isApproved() {
    	return $this->is_approved;
    }

	/**
	 * Approve user
	 */
    public function approve() {
    	$this->is_approved = true;
    	$this->save();
    }

	/**
	 * Unapprove user
	 */
	public function unapprove() {
		$this->is_approved = false;
		$this->save();
	}

    public function posts(){
        return $this->hasMany('Post');
    }


    public function comments(){
        return $this->hasMany('Comment');
    }

    public function questions(){
        return $this->hasMany('Question');
    }

    public function isAdmin() {
		return $this->is_admin;
	}

    // each post can have many votes from users
    // define our pivot table also
    public function vote_posts() {
        return $this->belongsToMany('Post', 'user_voted_posts', 'user_id', 'post_id');
    }

    // each comment can have many votes from users
    // define our pivot table also
    public function vote_comments() {
        return $this->belongsToMany('Comment', 'user_voted_comments', 'user_id', 'comment_id');
    }



}
