<?php

namespace app\Http\Controllers;

use Illuminate\Http\Request;

class ApprovalMessagesController extends Controller
{
    public function approved() {
		return view('approval.approved');
    }

	public function unapproved() {
		return view('approval.unapproved');
	}
}
