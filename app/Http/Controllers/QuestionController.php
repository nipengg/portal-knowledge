<?php

namespace app\Http\Controllers;

use app\Post;
use app\Question;
use app\User;
use app\category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class QuestionController extends Controller
{
    public function __construct(){
        $this->middleware('MyAuth');
    }

    public function newQuestionForm(Request $request){
        $data = [];
        $department = DB::select("
        SELECT * FROM departments
        ");
        $data['department'] = $department;

        if($request->has('question')){
            $data['question'] = $request->question;
        }
        $tags = DB::select("
                    SELECT * FROM tags
                    ORDER BY tag
            ");
        $data['allowed_tags'] = $tags;

        $themes = DB::select("
                    SELECT * FROM themes
                    WHERE is_active = 1
            ");
        $data['themes'] = $themes;

        $topics = DB::select("
                    SELECT * FROM topics
            ");
        $data['topics'] = $topics;

        $admins = DB::select("
                    SELECT * FROM users
                    WHERE is_approved = 'active' AND is_admin = 1 OR is_admin = 2 OR is_admin = 3
            ");
        $data['admins'] = $admins;

        $users = DB::select("
                    SELECT * FROM users WHERE is_approved = 'active'
        ");
        $data['users'] = $users;
        
        $category = DB::select("
                    SELECT * FROM categories
                    WHERE is_active = 1
            ");
        $data['category'] = $category;

        return view('questions.ask', $data);
    }

   public function questionEditForm($question_id){
        $data = [];
        //
        // $id = Post::findOrFail($id);
        // $data['data'] = $id;
        //  
        $department = DB::select("
        SELECT * FROM departments
        ORDER BY department_name
        ");
        $data['department'] = $department;
        //
        $tags = DB::select("
                  SELECT * FROM tags
                  ORDER BY tag
          ");
        $data['tags'] = $tags;
        //
        $themes = DB::select("
                  SELECT * FROM themes
                  WHERE is_active = 1
        ");
        $data['themes'] = $themes;
        //
        $data['question'] = DB::select("
            SELECT 
              q.*, 
              u.username as 'username', 
              u.id as 'user_id'
            FROM questions q, users u
            WHERE
              q.id = ? AND 
              q.user_id = u.id 
            ", [$question_id])[0];
        //
        $data['date'] = DB::select("
            SELECT 
              q.meeting, 
              u.username as 'username', 
              u.id as 'user_id'
            FROM questions q, users u
            WHERE
              q.id = ? AND 
              q.user_id = u.id 
            ", [$question_id])[0];
        //
        
        $tags = DB::select("
                    SELECT 
                      t.tag
                    FROM 
                      tags t, question_has_tags qht 
                    WHERE
                      qht.question_id = ? AND
                      qht.tag_id = t.id
                ", [$question_id]);

        $result_tags = [];
        foreach($tags as $tag){
            array_push($result_tags, $tag->tag);
        }
        $data['question_tags'] = $result_tags;
        
        //
        
        $data['first_post'] = DB::select("
            SELECT 
                p.*, 
                u.username as 'username', 
                u.id as 'user_id'
            FROM posts p, users u
            WHERE 
                p.question_id = ? AND 
                p.user_id = u.id 
            ORDER BY
                p.id ASC
            LIMIT 1
            ", [$question_id])[0];

            $category = DB::select("
            SELECT * FROM categories
            ORDER BY category_name
            ");
            $data['category'] = $category;
       
        return view('questions.edit', $data);
    }

    public function questionEdit($id, $first_post, Request $request){
        DB::beginTransaction();
    
        try {
             DB::delete("
                DELETE FROM question_has_tags 
                WHERE 
                question_id = ? 
            ", [$id]);
    
             $tags = $request->input('tags');
             $category_names = $request->input('category_name');
             $question = DB::getPdo()->lastInsertId();
             foreach($tags as $tag){
                 DB::insert("
                     INSERT INTO question_has_tags 
                     (tag_id, question_id, category_name) 
                     VALUES (?, ?, ?)  
                 ", [$tag, $id, $category_names]);
             }
           
            DB::table('questions')->where('id',$request->id)->update([
                'question_title' => $request->question_title,
                'category_name' => $request->category_name,
                'summary_question' => $request->summary,
                'theme_id' => $request->theme
            ]);

            DB::table('posts')->where('id',$request->first_post)->update([
                'post_content' => $request->content
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
    
        return redirect()->to(url('/'));
    }


    public function rating($id, Request $request){
        DB::beginTransaction();
    
        try {
            DB::table('questions')->where('id',$request->id)->update([
                'post_rating' => $request->rating,
            ]);
    
            DB::commit();
            
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'success');
            $request->session()->flash('notification_msg', 'Rating Send!');
    
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'danger');
            $request->session()->flash('notification_msg', 'Uh oh, something went wrong.');
        }
    
        return redirect()->to(url()->previous());
    }


    public function dateEdit($id, Request $request){
        DB::beginTransaction();
    
        try {
            
            DB::table('questions')->where('id',$request->id)->update([
                'estimated_time' => $request->estimated
            ]);
            DB::commit();
            
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'success');
            $request->session()->flash('notification_msg', 'Success!');
    
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'danger');
            $request->session()->flash('notification_msg', 'Uh oh, something went wrong.');
        }
        return redirect()->to(url()->previous());
    }

    public function dateMeetingEdit($id, Request $request){
        DB::beginTransaction();
    
        try {
            
            DB::table('questions')->where('id',$request->id)->update([
                'meeting' => $request->meeting
            ]);
            DB::commit();
            
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'success');
            $request->session()->flash('notification_msg', 'Success!');
    
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'danger');
            $request->session()->flash('notification_msg', 'Uh oh, something went wrong.');
        }
        return redirect()->to(url()->previous());
    }

    public function newQuestionSubmit(Request $request){
        $data = [];
        if($request->has('question')){
            $data['question'] = $request->question;
        } else {
            $data['question'] = '';
        }
        $question_title = $request->question_title;
        DB::beginTransaction();

            // create a new question
            $question = new Question();
            $question->question_title = $request->question_title;
            $question->user_id = Session::get('id');
            $question->user_request_id = $request->admin;
            $question->topic = $request->topic;
            $question->category_name = $request->category_name;
            $question->theme_id = Input::get('theme');
            $question->estimated_time = $request->estimated;
            $question->estimated_time_updated = $request->estimated;
            $question->summary_question = $request->summary;
            $question->is_give = 0;
            $question->accepted_answer_id = 0;
            $question->security = $request->security;
            $question->save();
            $question_id = DB::getPdo()->lastInsertId();

            // create the first post
            $post = new Post();
            $post->post_content = $request->first_post;
            $post->votes = 0;
            $post->user_id = Session::get('id');
            $post->question_id = $question_id;
            $post->save();

            DB::update("
                UPDATE reports
                SET total = total + 1
                WHERE id = 1
            ");

            DB::update("
                UPDATE reports
                SET total = total + 1
                WHERE id = 4
            ");

            $tags = $request->input('tags');
            $category = $request->input('category_name');
            foreach($tags as $tag){
                DB::insert("
                    INSERT INTO question_has_tags 
                    (tag_id, question_id, category_name) 
                    VALUES (?, ?, ?)
                ", [$tag, $question_id, $category]);
            }

            DB::insert("
                INSERT INTO post_issues
                (issue, question_id) 
                VALUES (?, ?)
            ", ['-', $question_id]);
           
            $security = $request->input('security');
            $users = $request->input('users');
            $departments = $request->input('departments');
            if($security === 'konfidensial'){
                if($users === null){
                    foreach($departments as $department){
                      DB::insert("
                          INSERT INTO tagged_department_questions
                          (question_id, department_id)
                          VALUES (?, ?)
                      ", [$question_id, $department]);
                    }
                  }
                  else if($departments === null){
                    foreach($users as $user){
                      DB::insert("
                          INSERT INTO tagged_user_questions
                          (question_id, user_id)
                          VALUES (?, ?)
                      ", [$question_id, $user]);
                    }
                  }
                  else{
                    foreach($users as $user){
                        DB::insert("
                            INSERT INTO tagged_user_questions
                            (question_id, user_id)
                            VALUES (?, ?)
                        ", [$question_id, $user]);
                    }
    
                    foreach($departments as $department){
                      DB::insert("
                          INSERT INTO tagged_department_questions
                          (question_id, department_id)
                          VALUES (?, ?)
                      ", [$question_id, $department]);
                  }
                }
            }
            else{
                
            }
 
        DB::commit();
        $request->session()->flash('notification', TRUE);
        $request->session()->flash('notification_type', 'success');
        $request->session()->flash('notification_msg', 'Question asked! Check again later to see if people have given answers to your question!');

        return redirect()->action('MainController@question', ['question' => $question_id]);
    }

    public function votePost($post_id, Request $request){
        DB::beginTransaction();

        try {
            DB::insert("
                INSERT INTO user_voted_posts 
                (post_id, user_id)
                VALUES
                (?, ?)
            ", [$post_id, $request->session()->get('id')]);

            DB::update("
                UPDATE posts
                SET votes = votes + 1
                WHERE id = ?
            ", [$post_id]);
            DB::commit();
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'success');
            $request->session()->flash('notification_msg', 'Your vote has been counted! Thank you for your input for the community!');

        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'danger');
            $request->session()->flash('notification_msg', 'Uh oh, something went wrong while trying to register your vote.');
        }

        return redirect()->to(url()->previous().'#'. $post_id);
    }

    public function unvotePost($post_id, Request $request){
        DB::beginTransaction();

        try {
            DB::delete("
                DELETE FROM user_voted_posts 
                WHERE 
                post_id = ? AND
                user_id = ?
            ", [$post_id, $request->session()->get('id')]);

            DB::update("
                UPDATE posts
                SET votes = votes - 1
                WHERE id = ?
            ", [$post_id]);
            DB::commit();
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'success');
            $request->session()->flash('notification_msg', 'Your vote has been canceled!');

        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'danger');
            $request->session()->flash('notification_msg', 'Uh oh, something went wrong while trying to unregister your vote.');
        }

        return redirect()->to(url()->previous().'#'. $post_id);
    }

    public function answer(Request $request){
        try {
            $post = new Post();
            $post->post_content = Input::get('content');
            $post->user_id = Session::get('id');
            $post->votes = 0;
            $post->question_id = Input::get('question_id');
            if($request->input('refrence') === null){

            }
            else{
                $refrences = $request->input('refrence');
                $post->refrence = $refrences;
            }
            $post->save();

             // associate the related tags
             $sumbers = $request->input('sumbers');
             $post = DB::getPdo()->lastInsertId();
             foreach($sumbers as $sumber){
                 DB::insert("
                     INSERT INTO post_has_sumbers 
                     (sumber_id, post_id) 
                     VALUES (?, ?)  
                 ", [$sumber, $post]);
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
             if($request->file('filename') === null){

             }
             else{
             foreach($data as $data){
                 DB::insert("
                     INSERT INTO question_has_files 
                     (filename, post_id) 
                     VALUES (?, ?)
                 ", [$data, $post]);
             }
            }
        //      DB::delete("
        //      DELETE FROM question_has_files 
        //      WHERE 
        //      question_id = ? AND filename = ''
        //  ", [$question_id]);

            DB::commit();

            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'success');
            $request->session()->flash('notification_msg', 'Your answer is posted! Thank you for your input for the community!');

        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'danger');
            $request->session()->flash('notification_msg', 'Uh oh, something went wrong while trying to take your answer.');
        }

        return redirect()->to(url()->previous());
    }

    public function newEditForm($post_id ,Request $request){
        if(Session::get('is_admin') === 0 || Session::has('username') === false) {
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'danger');
            $request->session()->flash('notification_msg', 'You cant enter that area!');
            return redirect()->to('/');
        }
        $data = [];
        $department = DB::select("
        SELECT * FROM departments
        ORDER BY department_name
        ");
        $data['department'] = $department;

        $sumber = DB::select("
                    SELECT * FROM sumbers
                    WHERE is_active = 1
            ");
        $data['sumber'] = $sumber;
        

        $posts = DB::select("
        SELECT * FROM posts

        ");

        $data['posts'] = DB::select("
            SELECT 
              p.*, 
              u.username as 'username', 
              u.id as 'user_id'
            FROM posts p, users u
            WHERE
              p.id = ? AND 
              p.user_id = u.id 
            ", [$post_id])[0];

        $tags = DB::select("
                    SELECT * FROM tags
                    ORDER BY tag
            ");
        $data['allowed_tags'] = $tags;

        $sumbers = DB::select("
                    SELECT 
                      s.sumber_name
                    FROM 
                      sumbers s, post_has_sumbers phs
                    WHERE
                      phs.post_id = ? AND
                      phs.sumber_id = s.id
                ", [$post_id]);

        $result_sumbers = [];
        foreach($sumbers as $sumber){
            array_push($result_sumbers, $sumber->sumber_name);
        }
        $data['post_sumbers'] = $result_sumbers;

        return view('post.edit', $data);
    }

    public function postEdit($post_id, Request $request){
        DB::beginTransaction();

        try {
            DB::delete("
                DELETE FROM post_has_sumbers 
                WHERE 
                post_id = ? 
            ", [$post_id]);

             $sumbers = $request->input('sumbers');
             $post = DB::getPdo()->lastInsertId();
             foreach($sumbers as $sumber){
                 DB::insert("
                     INSERT INTO post_has_sumbers 
                     (sumber_id, post_id) 
                     VALUES (?, ?)  
                 ", [$sumber, $post_id]);
             }
           
            DB::table('posts')->where('id',$request->id)->update([
                'post_content' => $request->content,
                'updated_at' => \Carbon\Carbon::now()
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

        return redirect()->to('/');
    }

    public function acceptAnswer(Request $request){
        try {
            $today = \Carbon\Carbon::now();
            $estimated = $request->estimated_time;
            $estimated_updated = $request->estimated_time_updated;

            $question = Question::find(Input::get('question_id'));
            $question->closed_at = \Carbon\Carbon::now();
            if($today > $estimated_updated || $today > $estimated_updated){
                $question->accepted_answer_id = 5;
            }
            else{
                $question->accepted_answer_id = 1;
            }
            $question->save();

            if($today > $estimated_updated || $today > $estimated_updated){
                DB::update("
                    UPDATE reports
                    SET total = total + 1
                    WHERE id = 3
                ");
            }
            else{
                DB::update("
                    UPDATE reports
                    SET total = total + 1
                    WHERE id = 2
                ");
            }

            DB::update("
                UPDATE reports
                SET total = total - 1
                WHERE id = 4
            ");
            
            DB::commit();
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'success');
            $request->session()->flash('notification_msg', 'You have accepted an answer! Thank you for your input for the community!');

        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'danger');
            $request->session()->flash('notification_msg', 'Uh oh, something went wrong while trying to accept an answer.');
        }

        return redirect()->to(url()->previous(). '#' . Input::get('post_id'));
    }

    public function declineAnswer(Request $request){
        try {
            $question = Question::find(Input::get('question_id'));
            $question->accepted_answer_id = 2;
            $question->save();

            $issues = $request->input('issue');
            $question_id = $request->input('question_id');
                DB::insert("
                    INSERT INTO post_issues
                    (issue, question_id) 
                    VALUES (?, ?)
                ", [$issues, $question_id]);

            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'success');
            $request->session()->flash('notification_msg', 'Success');

        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'danger');
            $request->session()->flash('notification_msg', 'Uh oh, something went wrong while trying to accept an answer.');
        }

        return redirect()->to(url()->previous(). '#' . Input::get('post_id'));
    }

    public function stopRequest(Request $request){
        try {
            $question = Question::find(Input::get('question_id'));
            if(Session::get('is_admin') === 0){
                $question->accepted_answer_id = 3;
                $question->additional_information = $request->input('additional');
            }
            else{
                $question->accepted_answer_id = 4;
                $question->additional_information_admin = $request->input('additional');
                DB::update("
                    UPDATE questions
                    SET is_give = is_give + 1
                    WHERE id = ?
                ",[Input::get('question_id')]);
            }
            $question->save();

            DB::update("
                UPDATE reports
                SET total = total - 1
                WHERE id = 4
            ");

            DB::update("
                UPDATE reports
                SET total = total + 1
                WHERE id = 5
            ");

            DB::commit();
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'success');
            $request->session()->flash('notification_msg', 'Success');

        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'danger');
            $request->session()->flash('notification_msg', 'Uh oh, something went wrong while trying to accept an answer.');
        }

        return redirect()->to(url()->previous(). '#' . Input::get('post_id'));
    }

    public function cancelStop(Request $request){
        try {
            $question = Question::find(Input::get('question_id'));
            $question->additional_information = $request->input('additional');
            if(Input::get('issues') === '-'){
                $question->accepted_answer_id = 0;
            }
            else{
                $question->accepted_answer_id = 2;
            }
            $question->save();

            DB::update("
                UPDATE reports
                SET total = total + 1
                WHERE id = 4
            ");

            DB::update("
                UPDATE reports
                SET total = total - 1
                WHERE id = 5
            ");

            DB::commit();

            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'success');
            $request->session()->flash('notification_msg', 'Success');

        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'danger');
            $request->session()->flash('notification_msg', 'Uh oh, something went wrong while trying to accept an answer.');
        }

        return redirect()->to(url()->previous(). '#' . Input::get('post_id'));
    }

    public function info(Request $request){
        try {
            $question = Question::find(Input::get('question_id'));
            if(Session::get('is_admin') === 0){
                $question->additional_information = $request->input('additional');
            }
            else{
                $question->additional_information_admin = $request->input('additional');
            }
            $question->save();

            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'success');
            $request->session()->flash('notification_msg', 'Success');

        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'danger');
            $request->session()->flash('notification_msg', 'Uh oh, something went wrong while trying to accept an answer.');
        }

        return redirect()->to(url()->previous(). '#' . Input::get('post_id'));
    }

    public function approveStop(Request $request){
        try {
            $question = Question::find(Input::get('question_id'));
            $question->accepted_answer_id = 3;
            $question->save();

            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'success');
            $request->session()->flash('notification_msg', 'Success');

        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'danger');
            $request->session()->flash('notification_msg', 'Uh oh, something went wrong while trying to accept an answer.');
        }

        return redirect()->to(url()->previous(). '#' . Input::get('post_id'));
    }
}
