<?php

namespace app\Http\Controllers;

use app\Search;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;


class SearchController extends Controller
{
    public function index()
    {
        if(Session::get('is_admin') === 0 || Session::has('username') === false) {
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'danger');
            $request->session()->flash('notification_msg', 'You cant enter that area!');
            return redirect()->to('/');
        }
        $data = [];

        $department = DB::select("
        SELECT * FROM departments
        ORDER BY department_id, department_name, description, is_active
        ");
        $data['department'] = $department;

        $tags = DB::select("
                    SELECT * FROM tags
            ");
        $data['tags'] = $tags;

        return view('search.tag', $data);
    }
}
