<?php

namespace app\Http\Controllers;

use app\Topic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;

class TopicController extends Controller
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

        $topics = DB::select("
                    SELECT * FROM topics
            ");
        $data['topics'] = $topics;

        return view('topic.topic', $data);
    }

    public function create()
    {
        if(Session::get('is_admin') === 0 || Session::has('username') === false) {
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'danger');
            $request->session()->flash('notification_msg', 'You cant enter that area!');
            return redirect()->to('/');
        }

        return view('topic.create');
    }

    public function update($id)
    {
        if(Session::get('is_admin') === 0 || Session::has('username') === false) {
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'danger');
            $request->session()->flash('notification_msg', 'You cant enter that area!');
            return redirect()->to('/');
        }

        $data = [];

        $topics = DB::select("
                SELECT * FROM topics
            ");

        $data['topics'] = DB::select("
            SELECT 
              t.*
            FROM topics t
            WHERE
              t.id = ?
            ", [$id])[0];

        return view('topic.update', $data);
    }

    public function edit($id, Request $request)
    {
        DB::beginTransaction();
    
        try {
            DB::table('topics')->where('id',$request->id)->update([
                'topic' => $request->topic,
            ]);
    
            DB::commit();
            
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'success');
            $request->session()->flash('notification_msg', 'Updated!');
    
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'danger');
            $request->session()->flash('notification_msg', 'Uh oh, something went wrong.');
        }
    
        return redirect()->to('/topics');
    }

    public function store(Request $request)
    {
        try {
            $topic = new Topic();
            $topic->topic = Input::get('topic');
            $topic->save();

        DB::commit();

            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'success');
            $request->session()->flash('notification_msg', 'Success!');

        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'danger');
            $request->session()->flash('notification_msg', 'Uh oh, something went wrong...');
        }

        return redirect()->to('topics');
    }

}
