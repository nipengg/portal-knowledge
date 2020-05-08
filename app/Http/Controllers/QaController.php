<?php

namespace app\Http\Controllers;

use app\Qa;
use app\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;


class QaController extends Controller
{
    public function index(Request $request)
    {
        $data = [];
        $data['filter'] = $request->filter ?: 'recent';
        $filter = $data['filter'];
        $data['page'] = $request->page ?: 1;
        $page = $data['page'];
        $data['limit'] = $request->limit ?: 10;

        if($data['filter'] == 'recent'){
            $qas = Qa::orderBy('created_at', 'desc')
                  ->where(function($query){
                  $query->where('user_id', Session::get('id'))
                        ->orWhere('user_request_id', Session::get('id'));
                  })
                  ->orderBy('id', 'desc');
        } else if($data['filter'] == 'trending'){

        } else {
            $qas = Qa::orderBy('created_at', 'desc')
                ->where(function($query){
                $query->where('user_id', Session::get('id'))
                    ->orWhere('user_request_id', Session::get('id'));
                })
                ->orderBy('id', 'desc');
        }
        $qas = $qas->paginate(5);
        $qas->setPath(url("/?filter=$filter"));

        $data['qas'] = $qas;

        $department = DB::select("
                    SELECT * FROM departments
                    ORDER BY department_name
            ");
        $data['department'] = $department;

        foreach($data['qas'] as $qa){
        $users = DB::select("
                    SELECT 
                      u.username
                    FROM 
                      users u, qas q
                    WHERE
                      q.id = ? AND
                      q.user_id = u.id
                ", [$qa->id]);

            $qa->user = $users[0]->username;

        $admins = DB::select("
                    SELECT 
                      u.username
                    FROM 
                      users u, qas q
                    WHERE
                      q.id = ? AND
                      q.user_request_id = u.id
                ", [$qa->id]);

            $qa->admin = $admins[0]->username;

         $tags = DB::select("
                    SELECT 
                      t.tag 
                    FROM 
                      tags t, qa_has_tags qht
                    WHERE
                      qht.qa_id = ? AND
                      qht.tag_id = t.id
                ", [$qa->id]);

            $result_tags = [];
            foreach($tags as $tag){
                array_push($result_tags, $tag->tag);
            }
            $qa->tags = $result_tags;
        }

        return view('qa.index', $data);
    }

    public function share(Request $request)
    {
        $data = [];
        $data['filter'] = $request->filter ?: 'recent';
        $filter = $data['filter'];
        $data['page'] = $request->page ?: 1;
        $page = $data['page'];
        $data['limit'] = $request->limit ?: 10;

        if($data['filter'] == 'recent'){
            $qa = Qa::orderBy('created_at', 'desc')
                            ->where('accepted_qa_id', 1)
                            ->orderBy('id', 'desc');
        } else {
            $qa = Qa::orderBy('created_at', 'desc')
                ->where('accepted_qa_id', 1)
                ->orderBy('id', 'desc');
        }
        $qa = $qa->paginate($data['limit']);
        $qa->setPath(url("/?filter=$filter"));

        $data['qa'] = $qa;


        $department = DB::select("
                    SELECT * FROM departments
                    ORDER BY department_name
            ");
        $data['department'] = $department;

        $qas = DB::select("
        SELECT * FROM qas
        WHERE is_active = 1 AND accepted_qa_id = 1 AND security = 'sharing'
        ");
        $data['qas'] = $qas;

        foreach($data['qas'] as $qa){
        $users = DB::select("
                    SELECT 
                      u.username
                    FROM 
                      users u, qas q
                    WHERE
                      q.id = ? AND
                      q.user_id = u.id
                ", [$qa->id]);

            $qa->user = $users[0]->username;

        $admins = DB::select("
                    SELECT 
                      u.username
                    FROM 
                      users u, qas q
                    WHERE
                      q.id = ? AND
                      q.user_request_id = u.id
                ", [$qa->id]);

            $qa->admin = $admins[0]->username;

         $tags = DB::select("
                    SELECT 
                      t.tag 
                    FROM 
                      tags t, qa_has_tags qht
                    WHERE
                      qht.qa_id = ? AND
                      qht.tag_id = t.id
                ", [$qa->id]);

            $result_tags = [];
            foreach($tags as $tag){
                array_push($result_tags, $tag->tag);
            }
            $qa->tags = $result_tags;
        }

        return view('qa.share', $data);
    }

    public function create()
    {
      if(Session::get('is_admin') === 1 || Session::has('username') === false) {
        $request->session()->flash('notification', TRUE);
        $request->session()->flash('notification_type', 'danger');
        $request->session()->flash('notification_msg', 'You cant enter that area!');
        return redirect()->to('/');
      }
        $data = [];
        $tags = DB::select("
                    SELECT * FROM tags
                    ORDER BY tag
            ");
        $data['allowed_tags'] = $tags;

        $admins = DB::select("
                    SELECT * FROM users
                    WHERE is_approved = 'active' AND is_admin = 1
            ");
        $data['admins'] = $admins;

        return view('qa.ask' , $data);
    }

    public function store(Request $request)
    {
     try {
        $data = [];
        DB::beginTransaction();
        $qa = new Qa();
        $qa->qa_title = $request->qa_title;
        $qa->user_request_id = $request->admin;
        $qa->security = $request->security;
        $qa->category_name = 'QA';
        $qa->user_id = Session::get('id');
        $qa->accepted_qa_id  = 0;
        $qa->is_active = 1;
        $qa->save();
        $qa_id = DB::getPdo()->lastInsertId();

        $message = new Message();
        $message->post_content = 'Hey There!';
        $message->user_id = Session::get('id');
        $message->qa_id = $qa_id;
        $message->save();

        $tags = $request->input('tags');
        foreach($tags as $tag){
            DB::insert("
                INSERT INTO qa_has_tags 
                (tag_id, qa_id) 
                VALUES (?, ?)
            ", [$tag, $qa_id]);
        }

        if($request->hasfile('filename'))
                {
                    foreach($request->file('filename') as $file)
                    {
                        $name=$file->getClientOriginalName();
                        $file->move(public_path().'/files/', $name);  
                        $data[] = $name;  
                    }
                }
                foreach($data as $data){
                    DB::insert("
                        INSERT INTO qa_has_files 
                        (filename, qa_id) 
                        VALUES (?, ?)
                    ", [$data, $qa_id]);
                }

        DB::commit();
        $request->session()->flash('notification', TRUE);
        $request->session()->flash('notification_type', 'success');
        $request->session()->flash('notification_msg', 'Success!');

    } catch (\Exception $e) {
        DB::rollback();
        // something went wrong
        $request->session()->flash('notification', TRUE);
        $request->session()->flash('notification_type', 'danger');
        $request->session()->flash('notification_msg', 'Uh oh, something went wrong while trying to take your answer.');
    }

    return redirect()->to('/qa/index');
    }

    
    public function qa($qa_id){
        $data = [];
       
        $data['qa'] = DB::select("
            SELECT 
              q.*, 
              u.username as 'username', 
              u.id as 'user_id'
            FROM qas q, users u
            WHERE
              q.id = ? AND 
              q.user_id = u.id 
            ", [$qa_id])[0];
        //
            
        $data['admins'] = DB::select("
            SELECT 
              u.username
            FROM qas q, users u
            WHERE
              q.id = ? AND 
              q.user_request_id = u.id 
            ", [$qa_id])[0];
        
        $tags = DB::select("
                    SELECT 
                      t.tag
                    FROM 
                      tags t, qa_has_tags qht 
                    WHERE
                      qht.qa_id = ? AND
                      qht.tag_id = t.id
                ", [$qa_id]);

        $result_tags = [];
        foreach($tags as $tag){
            array_push($result_tags, $tag->tag);
        }
        $data['qa_tags'] = $result_tags;

        $files = DB::select("
                    SELECT 
                      qhf.filename
                    FROM 
                      qa_has_files qhf 
                    WHERE
                      qhf.qa_id = ?
                ", [$qa_id]);

        $result_files = [];
        foreach($files as $file){
            array_push($result_files, $file->filename);
        }
        $data['qa_files'] = $result_files;
        
        //
        
        $data['message_post'] = DB::select("
            SELECT 
                m.*, 
                u.username as 'username', 
                u.id as 'user_id'
            FROM messages m, users u
            WHERE 
                m.qa_id = ? AND 
                m.qa_id = u.id 
            ", [$qa_id]);

        $data['messages'] = Message::where('qa_id', $qa_id)->offset(1)->paginate(1000);
        foreach($data['messages'] as $message){
            $user = DB::select("
                    SELECT
                      u.id as 'user_id', u.username as 'username'
                    FROM
                      messages m, users u
                    WHERE
                      m.id = ? AND 
                      u.id = m.user_id
                ", [$message['id']]);

            $message['user_id'] = $user[0]->user_id;
            $message['username'] = $user[0]->username;
        }
        return view('qa.view', $data);
    }

    public function message(Request $request){
        try {
            $message = new Message();
            $message->post_content = Input::get('post_content');
            $message->user_id = Session::get('id');
            $message->qa_id = Input::get('qa_id');
            $message->save();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'danger');
            $request->session()->flash('notification_msg', 'Uh oh, something went wrong while trying to take your answer.');
        }

        return redirect()->to(url()->previous());
    }
    public function acceptAnswer(Request $request){
      try {

          $qa = Qa::find(Input::get('qa_id'));
          $qa->accepted_qa_id = 1;
          $qa->save();

          $request->session()->flash('notification', TRUE);
          $request->session()->flash('notification_type', 'success');
          $request->session()->flash('notification_msg', 'Thank you for your part of the community!');

      } catch (\Exception $e) {
          DB::rollback();
          // something went wrong
          $request->session()->flash('notification', TRUE);
          $request->session()->flash('notification_type', 'danger');
          $request->session()->flash('notification_msg', 'Uh oh, something went wrong while trying to accept an answer.');
      }

      return redirect()->to(url()->previous());
  }
}
