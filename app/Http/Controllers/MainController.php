<?php

namespace app\Http\Controllers;

use app\Post;
use app\Question;
use app\User;
use app\PostIssue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class MainController extends Controller
{
    public function index(Request $request){
        $data = [];
        $data['filter'] = $request->filter ?: 'recent';
        $filter = $data['filter'];
        $data['page'] = $request->page ?: 1;
        $page = $data['page'];
        $data['limit'] = $request->limit ?: 10;
   
        $department = DB::select("
                    SELECT * FROM departments
                    where is_active = 1
            ");
        $data['department'] = $department;

        if($data['filter'] == 'recent'){
            $questions = Question::orderBy('created_at', 'desc')
                            ->orderBy('id', 'desc');
            $answered = Question::where('accepted_answer_id', 1)
                ->where('security', 'sharing')
                ->orderBy('id', 'desc');
        } else if($data['filter'] == 'trending'){
            // implement some kind of algorithm to fetch based on trending questions
        } else if($data['filter'] == 'open'){
            $questions = Question::where('accepted_answer_id', 0)
                ->orderBy('created_at', 'desc');
            $answered = Question::where('accepted_answer_id', 1)
                ->where('security', 'sharing')
                ->orderBy('id', 'desc');
        } else if($data['filter'] == 'answered'){
            $questions = Question::where('accepted_answer_id', 1)
                ->orderBy('created_at', 'desc');
            $answered = Question::where('accepted_answer_id', 1)
                ->where('security', 'sharing')
                ->orderBy('id', 'desc');
        } else {
            $questions = Question::orderBy('created_at', 'desc')
                ->orderBy('id', 'desc');
            $answered = Question::where('accepted_answer_id', 1)
                ->where('security', 'sharing')
                ->orderBy('id', 'desc');
        }
        $questions = $questions->paginate(5);
        $answered = $answered->paginate(5);
        $questions->setPath(url("/?filter=$filter"));

        $data['answered'] = $answered;
        $data['questions'] = $questions;

//        $data['questions'] = Question::limit(5)->offset(0)->get();
//        $data['questions'] = $questions->results;
//        $data['questions_links'] = $questions->links();

    foreach ($data['answered'] as $question){
      $tags = DB::select("
              SELECT 
                t.tag 
              FROM 
                tags t, question_has_tags qht 
              WHERE
                qht.question_id = ? AND
                qht.tag_id = t.id
          ", [$question['id']]);

      $result_tags = [];
      foreach($tags as $tag){
          array_push($result_tags, $tag->tag);
      }
      $question['tags'] = $result_tags;

      $themes = DB::select("
              SELECT 
                t.theme
              FROM 
                themes t, questions q
              WHERE
                q.id = ? AND
                q.theme_id = t.id
          ", [$question->id]);

      $question['theme'] = $themes[0]->theme;

      $first_post = DB::select("
              SELECT
                p.votes, p.user_id, u.username, q.category_name
              FROM
                posts p, users u, questions q
              WHERE
                q.id = ? AND 
                p.question_id = q.id AND 
                u.id = p.user_id
              ORDER BY
                p.id ASC
              LIMIT 1
          ", [$question['id']]);

      $question['votes'] = $first_post[0]->votes;
      $question['asker'] = $first_post[0]->username;
      $question['category'] = $first_post[0]->category_name;
  

      $answers_count = DB::select("
              SELECT
                p.*
              FROM
                posts p
              WHERE
                p.question_id = ?
              ORDER BY
                p.id ASC
          ", [$question['id']]);

      $question['answers'] = sizeof($answers_count)-1;
    }

        foreach ($data['questions'] as $question){
            $tags = DB::select("
                    SELECT 
                      t.tag 
                    FROM 
                      tags t, question_has_tags qht 
                    WHERE
                      qht.question_id = ? AND
                      qht.tag_id = t.id
                ", [$question['id']]);

            $result_tags = [];
            foreach($tags as $tag){
                array_push($result_tags, $tag->tag);
            }
            $question['tags'] = $result_tags;

            $themes = DB::select("
                    SELECT 
                      t.theme
                    FROM 
                      themes t, questions q
                    WHERE
                      q.id = ? AND
                      q.theme_id = t.id
                ", [$question->id]);

            $question['theme'] = $themes[0]->theme;

            $first_post = DB::select("
                    SELECT
                      p.votes, p.user_id, u.username, q.category_name
                    FROM
                      posts p, users u, questions q
                    WHERE
                      q.id = ? AND 
                      p.question_id = q.id AND 
                      u.id = p.user_id
                    ORDER BY
                      p.id ASC
                    LIMIT 1
                ", [$question['id']]);

            $question['votes'] = $first_post[0]->votes;
            $question['asker'] = $first_post[0]->username;
            $question['category'] = $first_post[0]->category_name;
            

            $answers_count = DB::select("
                    SELECT
                      p.*
                    FROM
                      posts p
                    WHERE
                      p.question_id = ?
                    ORDER BY
                      p.id ASC
                ", [$question['id']]);

            $question['answers'] = sizeof($answers_count)-1;

        }

        return view('index', $data);
    }


    public function personal($id, Request $request){
      $data = [];
      $data['filter'] = $request->filter ?: 'recent';
      $filter = $data['filter'];
      $data['page'] = $request->page ?: 1;
      $page = $data['page'];
      $data['limit'] = $request->limit ?: 10;
 
      $department = DB::select("
                  SELECT * FROM departments
                  where is_active = 1
          ");
      $data['department'] = $department;

      $data['id'] = $id;

      $questions = DB::select("
              SELECT question_id FROM tagged_user_questions WHERE user_id = ?
      ",[$id]);

      $question_ids = array();
      foreach($questions as $question){
          array_push($question_ids, $question->question_id);
      }

      if($data['filter'] == 'recent'){
          $questions = Question::orderBy('created_at', 'desc')
              ->where('accepted_answer_id', 1)
              ->where('security', 'konfidensial')
              ->orderBy('id', 'desc');
      } else if($data['filter'] == 'trending'){
          // implement some kind of algorithm to fetch based on trending questions
      } else if($data['filter'] == 'open'){
          $questions = Question::where('accepted_answer_id', 0)
              ->orderBy('created_at', 'desc');
      } else if($data['filter'] == 'answered'){
          $questions = Question::where('accepted_answer_id', 1)
              ->orderBy('created_at', 'desc');
      } else {
          $questions = Question::orderBy('created_at', 'desc')
              ->orderBy('id', 'desc');
      }

      $questions = $questions->whereIn('id', $question_ids)->paginate($data['limit']);
      $questions->setPath(url("/?filter=$filter"));

      $data['questions'] = $questions;

//        $data['questions'] = Question::limit(5)->offset(0)->get();
//        $data['questions'] = $questions->results;
//        $data['questions_links'] = $questions->links();

      foreach ($data['questions'] as $question){
          $tags = DB::select("
                  SELECT 
                    t.tag 
                  FROM 
                    tags t, question_has_tags qht 
                  WHERE
                    qht.question_id = ? AND
                    qht.tag_id = t.id
              ", [$question['id']]);

          $result_tags = [];
          foreach($tags as $tag){
              array_push($result_tags, $tag->tag);
          }
          $question['tags'] = $result_tags;

          $themes = DB::select("
                  SELECT 
                    t.theme
                  FROM 
                    themes t, questions q
                  WHERE
                    q.id = ? AND
                    q.theme_id = t.id
              ", [$question->id]);

          $question['theme'] = $themes[0]->theme;

          $first_post = DB::select("
                  SELECT
                    p.votes, p.user_id, u.username, q.category_name
                  FROM
                    posts p, users u, questions q
                  WHERE
                    q.id = ? AND 
                    p.question_id = q.id AND 
                    u.id = p.user_id
                  ORDER BY
                    p.id ASC
                  LIMIT 1
              ", [$question['id']]);

          $question['votes'] = $first_post[0]->votes;
          $question['asker'] = $first_post[0]->username;
          $question['category'] = $first_post[0]->category_name;
          

          $answers_count = DB::select("
                  SELECT
                    p.*
                  FROM
                    posts p
                  WHERE
                    p.question_id = ?
                  ORDER BY
                    p.id ASC
              ", [$question['id']]);

          $question['answers'] = sizeof($answers_count)-1;

      }

      return view('questions.tag', $data);
  }

    public function search(Request $request){
      $data = [];
      $data['filter'] = $request->filter ?: 'recent';
      $filter = $data['filter'];
      $data['page'] = $request->page ?: 1;
      $page = $data['page'];
      $data['limit'] = $request->limit ?: 10;
 
      $department = DB::select("
                  SELECT * FROM departments
                  where is_active = 1
          ");
      $data['department'] = $department;

      $search = $request->search;

      if($data['filter'] == 'recent'){
          $questions = Question::orderBy('created_at', 'desc')
                          ->where('question_title','like',"%".$search."%")
                          ->orderBy('id', 'desc');
          $answered = Question::where('accepted_answer_id', 1)
                ->where('question_title','like',"%".$search."%")
                ->orderBy('id', 'desc');
      } else if($data['filter'] == 'open'){
          $questions = Question::where('accepted_answer_id', 0)
              ->where('question_title','like',"%".$search."%")
              ->orderBy('created_at', 'desc');
          $answered = Question::where('accepted_answer_id', 1)
                ->where('question_title','like',"%".$search."%")
                ->orderBy('id', 'desc');
      } else if($data['filter'] == 'answered'){
          $questions = Question::where('accepted_answer_id', 1)
              ->where('question_title','like',"%".$search."%")
              ->orderBy('created_at', 'desc');
          $answered = Question::where('accepted_answer_id', 1)
                ->where('question_title','like',"%".$search."%")
                ->orderBy('id', 'desc');
      } else {
          // fallback if user entered random gibberish in the url
          $questions = Question::orderBy('created_at', 'desc')
              ->where('question_title','like',"%".$search."%")
              ->orderBy('id', 'desc');
          $answered = Question::where('accepted_answer_id', 1)
                ->where('question_title','like',"%".$search."%")
                ->orderBy('id', 'desc');
      }
      $questions = $questions->paginate($data['limit']);
      $questions->setPath(url("/?filter=$filter"));

      $data['answered'] = $answered;
      $data['questions'] = $questions;

//        $data['questions'] = Question::limit(5)->offset(0)->get();
//        $data['questions'] = $questions->results;
//        $data['questions_links'] = $questions->links();

foreach ($data['answered'] as $question){
  $tags = DB::select("
          SELECT 
            t.tag 
          FROM 
            tags t, question_has_tags qht 
          WHERE
            qht.question_id = ? AND
            qht.tag_id = t.id
      ", [$question['id']]);

  $result_tags = [];
  foreach($tags as $tag){
      array_push($result_tags, $tag->tag);
  }
  $question['tags'] = $result_tags;

  $themes = DB::select("
          SELECT 
            t.theme
          FROM 
            themes t, questions q
          WHERE
            q.id = ? AND
            q.theme_id = t.id
      ", [$question->id]);

  $question['theme'] = $themes[0]->theme;

  $first_post = DB::select("
          SELECT
            p.votes, p.user_id, u.username, q.category_name
          FROM
            posts p, users u, questions q
          WHERE
            q.id = ? AND 
            p.question_id = q.id AND 
            u.id = p.user_id
          ORDER BY
            p.id ASC
          LIMIT 1
      ", [$question['id']]);

  $question['votes'] = $first_post[0]->votes;
  $question['asker'] = $first_post[0]->username;
  $question['category'] = $first_post[0]->category_name;


  $answers_count = DB::select("
          SELECT
            p.*
          FROM
            posts p
          WHERE
            p.question_id = ?
          ORDER BY
            p.id ASC
      ", [$question['id']]);

  $question['answers'] = sizeof($answers_count)-1;

}

      foreach ($data['questions'] as $question){
          $tags = DB::select("
                  SELECT 
                    t.tag 
                  FROM 
                    tags t, question_has_tags qht 
                  WHERE
                    qht.question_id = ? AND
                    qht.tag_id = t.id
              ", [$question['id']]);

          $result_tags = [];
          foreach($tags as $tag){
              array_push($result_tags, $tag->tag);
          }
          $question['tags'] = $result_tags;

          $themes = DB::select("
                  SELECT 
                    t.theme
                  FROM 
                    themes t, questions q
                  WHERE
                    q.id = ? AND
                    q.theme_id = t.id
              ", [$question->id]);

          $question['theme'] = $themes[0]->theme;

          $first_post = DB::select("
                  SELECT
                    p.votes, p.user_id, u.username, q.category_name
                  FROM
                    posts p, users u, questions q
                  WHERE
                    q.id = ? AND 
                    p.question_id = q.id AND 
                    u.id = p.user_id
                  ORDER BY
                    p.id ASC
                  LIMIT 1
              ", [$question['id']]);

          $question['votes'] = $first_post[0]->votes;
          $question['asker'] = $first_post[0]->username;
          $question['category'] = $first_post[0]->category_name;
          

          $answers_count = DB::select("
                  SELECT
                    p.*
                  FROM
                    posts p
                  WHERE
                    p.question_id = ?
                  ORDER BY
                    p.id ASC
              ", [$question['id']]);

          $question['answers'] = sizeof($answers_count)-1;

      }

      return view('index', $data);
  }

    public function tagCategory($category ,Request $request){
      $data = [];
      $data['filter'] = $request->filter ?: 'recent';
      $filter = $data['filter'];
      $data['page'] = $request->page ?: 1;
      $page = $data['page'];
      $data['limit'] = $request->limit ?: 10;
 
      $department = DB::select("
                  SELECT * FROM departments
                  where is_active = 1
          ");
      $data['department'] = $department;

      if($data['filter'] == 'recent'){
          $questions = Question::orderBy('created_at', 'desc')
          ->where('category_name', $category)
          ->orderBy('id', 'desc');
          $answered = Question::orderBy('created_at', 'desc')
          ->where('category_name', $category)
          ->where('accepted_answer_id', 1)
          ->orderBy('id', 'desc');
      } else if($data['filter'] == 'open'){
          $questions = Question::where('accepted_answer_id', 0)
              ->where('category_name', $category)
              ->orderBy('created_at', 'desc');
      } else if($data['filter'] == 'answered'){
          $questions = Question::where('accepted_answer_id', 1)
              ->where('category_name', $category)
              ->orderBy('created_at', 'desc');
      } else {
          // fallback if user entered random gibberish in the url
          $questions = Question::orderBy('created_at', 'desc')
              ->where('category_name', $category)
              ->orderBy('id', 'desc');
      }
      $questions = $questions->paginate($data['limit']);
      $answered = $answered->paginate($data['limit']);
      $questions->setPath(url("/$category/?filter=$filter"));

      $data['category'] = $category;
      $data['answered'] = $answered;
      $data['questions'] = $questions;

//        $data['questions'] = Question::limit(5)->offset(0)->get();
//        $data['questions'] = $questions->results;
//        $data['questions_links'] = $questions->links();

      foreach ($data['questions'] as $question){
          $tags = DB::select("
                  SELECT 
                    t.tag 
                  FROM 
                    tags t, question_has_tags qht 
                  WHERE
                    qht.question_id = ? AND
                    qht.tag_id = t.id
              ", [$question['id']]);

          $result_tags = [];
          foreach($tags as $tag){
              array_push($result_tags, $tag->tag);
          }
          $question['tags'] = $result_tags;

          $themes = DB::select("
                    SELECT 
                      t.theme
                    FROM 
                      themes t, questions q
                    WHERE
                      q.id = ? AND
                      q.theme_id = t.id
                ", [$question->id]);

            $question->theme = $themes[0]->theme;

          $first_post = DB::select("
                  SELECT
                    p.votes, p.user_id, u.username, q.category_name, q.user_id, q.user_request_id
                  FROM
                    posts p, users u, questions q
                  WHERE
                    q.id = ? AND 
                    p.question_id = q.id AND 
                    u.id = p.user_id
                  ORDER BY
                    p.id ASC
                  LIMIT 1
              ", [$question['id']]);

          $question['votes'] = $first_post[0]->votes;
          $question['asker'] = $first_post[0]->username;
          $question['category'] = $first_post[0]->category_name;
          

          $answers_count = DB::select("
                  SELECT
                    p.*
                  FROM
                    posts p
                  WHERE
                    p.question_id = ?
                  ORDER BY
                    p.id ASC
              ", [$question['id']]);

          $question['answers'] = sizeof($answers_count)-1;

      }

      foreach ($data['answered'] as $question){
        $tags = DB::select("
                SELECT 
                  t.tag 
                FROM 
                  tags t, question_has_tags qht 
                WHERE
                  qht.question_id = ? AND
                  qht.tag_id = t.id
            ", [$question['id']]);

        $result_tags = [];
        foreach($tags as $tag){
            array_push($result_tags, $tag->tag);
        }
        $question['tags'] = $result_tags;

        $themes = DB::select("
                  SELECT 
                    t.theme
                  FROM 
                    themes t, questions q
                  WHERE
                    q.id = ? AND
                    q.theme_id = t.id
              ", [$question->id]);

          $question->theme = $themes[0]->theme;

        $first_post = DB::select("
                SELECT
                  p.votes, p.user_id, u.username, q.category_name, q.user_id, q.user_request_id
                FROM
                  posts p, users u, questions q
                WHERE
                  q.id = ? AND 
                  p.question_id = q.id AND 
                  u.id = p.user_id
                ORDER BY
                  p.id ASC
                LIMIT 1
            ", [$question['id']]);

        $question['votes'] = $first_post[0]->votes;
        $question['asker'] = $first_post[0]->username;
        $question['category'] = $first_post[0]->category_name;
        

        $answers_count = DB::select("
                SELECT
                  p.*
                FROM
                  posts p
                WHERE
                  p.question_id = ?
                ORDER BY
                  p.id ASC
            ", [$question['id']]);

        $question['answers'] = sizeof($answers_count)-1;

    }

      return view('tags.category', $data);
  }

    public function question($question_id){
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
        $sumbers = DB::select("
                    SELECT * FROM sumbers
                    where is_active = 1
            ");
        $data['sumbers'] = $sumbers;
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

        $data['themes'] = DB::select("
            SELECT 
              t.theme
            FROM questions q, themes t
            WHERE
              q.id = ? AND 
              q.theme_id = t.id 
            ", [$question_id])[0];
            
        $data['admins'] = DB::select("
            SELECT 
              u.username
            FROM questions q, users u
            WHERE
              q.id = ? AND 
              q.user_request_id = u.id 
            ", [$question_id])[0];
        
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

        $data['issues'] = DB::select("
            SELECT 
              p.* 
            FROM questions q, post_issues p
            WHERE 
                q.id = ? AND 
                q.id = p.question_id 
            ORDER BY
                p.issue_id DESC
            LIMIT 1
            ", [$question_id])[0];

        $data['last_post'] = DB::select("
            SELECT 
                p.*, 
                u.username as 'username', 
                u.id as 'user_id'
            FROM posts p, users u
            WHERE 
                p.question_id = ? AND 
                p.user_id = u.id 
            ORDER BY
                p.id DESC
            LIMIT 1
            ", [$question_id])[0];

          $sumber = DB::select("
                    SELECT * FROM sumbers
                    ORDER BY sumber_name
            ");
          $data['sumber'] = $sumber;

          // $category = DB::select("
          //           SELECT * FROM `question_has_tags` WHERE question_id = 3 ORDER BY category_name
          //   ");
          // $data['category'] = $category;
        $data['category'] = Question::where('id', $question_id)->offset(1)->paginate(10);
        $data['answers'] = Post::where('question_id', $question_id)->offset(1)->paginate(10);
        foreach($data['answers'] as $answer){
            $user = DB::select("
                    SELECT
                      u.id as 'user_id', u.username as 'username'
                    FROM
                      posts p, users u  
                    WHERE 
                      p.id = ? AND  
                      u.id = p.user_id
                    ORDER BY
                      p.id ASC
                    LIMIT 1
                ", [$answer['id']]);

            $answer['user_id'] = $user[0]->user_id;
            $answer['username'] = $user[0]->username;

             $sumbers = DB::select("
                    SELECT 
                      s.sumber_name 
                    FROM 
                      sumbers s, post_has_sumbers phs 
                    WHERE
                      phs.post_id = ? AND
                      phs.sumber_id = s.id
                ", [$answer['id']]);

            $result_sumbers = [];
            foreach($sumbers as $sumber){
                array_push($result_sumbers, $sumber->sumber_name);
            }
            $answer['sumbers'] = $result_sumbers;

        $files = DB::select("
                    SELECT 
                      qhf.filename 
                    FROM 
                       question_has_files qhf 
                    WHERE
                      qhf.post_id = ?
                ", [$answer['id']]);

            $result_files = [];
            foreach($files as $file){
                array_push($result_files, $file->filename);
            }
            $answer['files'] = $result_files;
          }
        
        foreach($data['answers'] as $answer){
            $voted = DB::select("
                    SELECT *
                    FROM user_voted_posts 
                    WHERE 
                      user_id = ? AND 
                      post_id = ?
                    LIMIT 1
                ", [Session::get('id'), $answer['id']]);

            $voted = sizeof($voted) === 1;
            $answer['voted'] = $voted;
        }
        // if($data['question']->accepted_answer_id !== 0){
        //     $data['accepted_answer'] = DB::select("
        //     SELECT 
        //         p.*, 
        //         u.username as 'username', 
        //         u.id as 'user_id'
        //     FROM posts p, users u
        //     WHERE 
        //         p.question_id = ? AND 
        //         p.user_id = u.id AND 
        //         p.id = ?
        //     ORDER BY
        //         p.id DESC
        //     LIMIT 1
        //     ", [$question_id, $data['question']->accepted_answer_id])[0];

        //     $voted = DB::select("
        //             SELECT *
        //             FROM user_voted_posts 
        //             WHERE 
        //               user_id = ? AND 
        //               post_id = ?
        //             LIMIT 1
        //         ", [Session::get('id'), $data['question']->accepted_answer_id]);

        //     $voted = sizeof($voted) === 1;
        //     $data['accepted_answer']->voted = $voted;
        // }

        return view('questions.view', $data);
    }

    public function tag($tag, Request $request){
        $data = [];
        //
        $department = DB::select("
        SELECT * FROM departments
        ORDER BY department_name
        ");
        $data['department'] = $department;
        //
        $data['filter'] = $request->filter ?: 'recent';
        $filter = $data['filter'];
        $data['page'] = $request->page ?: 1;
        $page = $data['page'];
        $data['limit'] = $request->limit ?: 10;

        $data['tag'] = $tag;

        $tag_id = DB::select("
                SELECT * FROM tags WHERE tag = ?
            ", [$tag])[0]->id;

        $questions = DB::select("
                SELECT question_id FROM question_has_tags WHERE tag_id = ?
            ", [$tag_id]);

        $question_ids = array();
        foreach($questions as $question){
            array_push($question_ids, $question->question_id);
        }

        $answered = DB::select("
              SELECT question_id FROM question_has_tags WHERE tag_id = ?
          ", [$tag_id]);

          $answereds = array();
          foreach($answered as $answered){
              array_push($answereds, $answered->question_id);
          }

        if($data['filter'] == 'recent'){
            $questions = Question::orderBy('created_at', 'desc')
                ->where('security', 'sharing')
                ->orderBy('id', 'desc');
            $answered = Question::orderBy('created_at', 'desc')
                ->where('accepted_answer_id', 1)
                ->where('security', 'sharing')
                ->orderBy('id', 'desc');
        } else if($data['filter'] == 'trending'){
            // implement some kind of algorithm to fetch based on trending questions
        } else if($data['filter'] == 'open'){
            $questions = Question::where('accepted_answer_id', 0)
                ->where('security', 'sharing')
                ->orderBy('created_at', 'desc');
            $answered = Question::orderBy('created_at', 'desc')
                ->where('accepted_answer_id', 1)
                ->where('security', 'sharing')
                ->orderBy('id', 'desc');
        } else if($data['filter'] == 'answered'){
            $questions = Question::where('accepted_answer_id', '<>', 0)
                ->where('security', 'sharing')
                ->orderBy('created_at', 'desc');
            $answered = Question::orderBy('created_at', 'desc')
                ->where('accepted_answer_id', 1)
                ->where('security', 'sharing')
                ->orderBy('id', 'desc');
        } else {
            // fallback if user entered random gibberish in the url
            $questions = Question::orderBy('created_at', 'desc')
                ->where('security', 'sharing')
                ->orderBy('id', 'desc');;
        }
        $questions = $questions->whereIn('id', $question_ids)->paginate($data['limit']);
        $answered = $answered->whereIn('id', $answereds)->paginate($data['limit']);

        $questions->setPath(url("/$tag/?filter=$filter"));

        $data['answered'] = $answered;
        $data['questions'] = $questions;

//        $data['questions'] = Question::limit(5)->offset(0)->get();
//        $data['questions'] = $questions->results;
//        $data['questions_links'] = $questions->links();

        foreach ($data['questions'] as $question){
            $tags = DB::select("
                    SELECT 
                      t.tag
                    FROM 
                      tags t, question_has_tags qht 
                    WHERE
                      qht.question_id = ? AND
                      qht.tag_id = t.id
                ", [$question['id']]);

            $result_tags = [];
            foreach($tags as $tag){
                array_push($result_tags, $tag->tag);
            }
            $question['tags'] = $result_tags;

            $themes = DB::select("
                    SELECT 
                      t.theme
                    FROM 
                      themes t, questions q
                    WHERE
                      q.id = ? AND
                      q.theme_id = t.id
                ", [$question->id]);

            $question->theme = $themes[0]->theme;

            $first_post = DB::select("
                    SELECT
                      p.votes, p.user_id, u.username
                    FROM
                      posts p, users u, questions q
                    WHERE
                      q.id = ? AND 
                      p.question_id = q.id AND 
                      u.id = p.user_id
                    ORDER BY
                      p.id ASC
                    LIMIT 1
                ", [$question['id']]);

            $question['votes'] = $first_post[0]->votes;
            $question['asker'] = $first_post[0]->username;

            $answers_count = DB::select("
                    SELECT
                      p.*
                    FROM
                      posts p
                    WHERE
                      p.question_id = ?
                    ORDER BY
                      p.id ASC
                ", [$question['id']]);

            $question['answers'] = sizeof($answers_count)-1;
        }

        foreach ($data['answered'] as $question){
          $tags = DB::select("
                  SELECT 
                    t.tag
                  FROM 
                    tags t, question_has_tags qht 
                  WHERE
                    qht.question_id = ? AND
                    qht.tag_id = t.id
              ", [$question['id']]);

          $result_tags = [];
          foreach($tags as $tag){
              array_push($result_tags, $tag->tag);
          }
          $question['tags'] = $result_tags;

          $themes = DB::select("
                  SELECT 
                    t.theme
                  FROM 
                    themes t, questions q
                  WHERE
                    q.id = ? AND
                    q.theme_id = t.id
              ", [$question->id]);

          $question->theme = $themes[0]->theme;

          $first_post = DB::select("
                  SELECT
                    p.votes, p.user_id, u.username
                  FROM
                    posts p, users u, questions q
                  WHERE
                    q.id = ? AND 
                    p.question_id = q.id AND 
                    u.id = p.user_id
                  ORDER BY
                    p.id ASC
                  LIMIT 1
              ", [$question['id']]);

          $question['votes'] = $first_post[0]->votes;
          $question['asker'] = $first_post[0]->username;

          $answers_count = DB::select("
                  SELECT
                    p.*
                  FROM
                    posts p
                  WHERE
                    p.question_id = ?
                  ORDER BY
                    p.id ASC
              ", [$question['id']]);

          $question['answers'] = sizeof($answers_count)-1;

      }

        return view('tags.view', $data);
    }

    public function theme($theme, Request $request){
      $data = [];
      //
      $department = DB::select("
      SELECT * FROM departments
      ORDER BY department_name
      ");
      $data['department'] = $department;
      //
      $data['filter'] = $request->filter ?: 'recent';
      $filter = $data['filter'];
      $data['page'] = $request->page ?: 1;
      $page = $data['page'];
      $data['limit'] = $request->limit ?: 10;

      $data['theme'] = $theme;

      $theme_id = DB::select("
              SELECT * FROM themes WHERE theme = ?
          ", [$theme])[0]->id;

      $questions = DB::select("
              SELECT * FROM questions WHERE theme_id = ?
          ", [$theme_id]);

          $question_ids = array();
          foreach($questions as $question){
              array_push($question_ids, $question->id);
          }

      $answered = DB::select("
              SELECT * FROM questions WHERE accepted_answer_id = 1 AND theme_id = ?
          ", [$theme_id]);

          $answereds = array();
          foreach($answered as $answered){
              array_push($answereds, $answered->id);
          }

          if($data['filter'] == 'recent'){
              $questions = Question::orderBy('created_at', 'desc')
                  ->orderBy('id', 'desc');
              $answered = Question::orderBy('created_at', 'desc')
                  ->orderBy('id', 'desc');
          } else if($data['filter'] == 'trending'){
              // implement some kind of algorithm to fetch based on trending questions
          } else if($data['filter'] == 'open'){
              $questions = Question::where('accepted_answer_id', 0)
                  ->orderBy('created_at', 'desc');
          } else if($data['filter'] == 'answered'){
              $questions = Question::where('accepted_answer_id', '<>', 0)
                  ->orderBy('created_at', 'desc');
          } else {
              // fallback if user entered random gibberish in the url
              $questions = Question::orderBy('created_at', 'desc')
                  ->orderBy('id', 'desc');;
          }
          $questions = $questions->whereIn('id', $question_ids)->paginate($data['limit']);
          $answered = $answered->whereIn('id', $answereds)->paginate($data['limit']);
  
          $questions->setPath(url("/$theme/?filter=$filter"));
          
      $data['questions'] = $questions;
      $data['answered'] = $answered;

//        $data['questions'] = Question::limit(5)->offset(0)->get();
//        $data['questions'] = $questions->results;
//        $data['questions_links'] = $questions->links();

      foreach ($data['questions'] as $question){
          $tags = DB::select("
                  SELECT 
                    t.tag
                  FROM 
                    tags t, question_has_tags qht 
                  WHERE
                    qht.question_id = ? AND
                    qht.tag_id = t.id
              ", [$question->id]);

          $result_tags = [];
          foreach($tags as $tag){
              array_push($result_tags, $tag->tag);
          }
          $question->tags = $result_tags;

          $themes = DB::select("
                    SELECT 
                      t.theme
                    FROM 
                      themes t, questions q
                    WHERE
                      q.id = ? AND
                      q.theme_id = t.id
                ", [$question->id]);

            $question->theme = $themes[0]->theme;

          $first_post = DB::select("
                  SELECT
                    p.votes, p.user_id, u.username
                  FROM
                    posts p, users u, questions q
                  WHERE
                    q.id = ? AND 
                    p.question_id = q.id AND 
                    u.id = p.user_id
                  ORDER BY
                    p.id ASC
                  LIMIT 1
              ", [$question->id]);

          $question->votes = $first_post[0]->votes;
          $question->asker = $first_post[0]->username;

          $answers_count = DB::select("
                  SELECT
                    p.*
                  FROM
                    posts p
                  WHERE
                    p.question_id = ?
                  ORDER BY
                    p.id ASC
              ", [$question->id]);

          $question->answers = sizeof($answers_count)-1;

      }

      foreach ($data['answered'] as $question){
        $tags = DB::select("
                SELECT 
                  t.tag
                FROM 
                  tags t, question_has_tags qht 
                WHERE
                  qht.question_id = ? AND
                  qht.tag_id = t.id
            ", [$question->id]);

        $result_tags = [];
        foreach($tags as $tag){
            array_push($result_tags, $tag->tag);
        }
        $question->tags = $result_tags;

        $themes = DB::select("
                  SELECT 
                    t.theme
                  FROM 
                    themes t, questions q
                  WHERE
                    q.id = ? AND
                    q.theme_id = t.id
              ", [$question->id]);

          $question->theme = $themes[0]->theme;

        $first_post = DB::select("
                SELECT
                  p.votes, p.user_id, u.username
                FROM
                  posts p, users u, questions q
                WHERE
                  q.id = ? AND 
                  p.question_id = q.id AND 
                  u.id = p.user_id
                ORDER BY
                  p.id ASC
                LIMIT 1
            ", [$question->id]);

        $question->votes = $first_post[0]->votes;
        $question->asker = $first_post[0]->username;

        $answers_count = DB::select("
                SELECT
                  p.*
                FROM
                  posts p
                WHERE
                  p.question_id = ?
                ORDER BY
                  p.id ASC
            ", [$question->id]);

        $question->answers = sizeof($answers_count)-1;

    }

      return view('tags.themeQuestion', $data);
  }

    public function tagIndex()
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

        return view('tags.index', $data);
    }

    public function profile($id){
        $data = [];

        $data['user'] = DB::select("
            SELECT 
             u.*
            FROM users u
            WHERE
              u.id = ? 
            ", [$id])[0];

        $data['departments'] = DB::select("
            SELECT 
              d.*
            FROM departments d, users u
            WHERE
              u.id = ? AND 
              u.department_id = d.department_id 
            ", [$id])[0];

        return view('profiles.view', $data);
    }

    // public function seed(){
    //    while(true){
    //        $question_id = random_int(1,23);
    //        $tag_id = random_int(1,5);
    //        DB::insert("
    //            INSERT INTO question_has_tags (question_id, tag_id)
    //            VALUES (?, ?)
    //        ", [$question_id, $tag_id]);
    //    }

    //     $tags = ['life', 'love', 'software-engineering', 'software-development', 'web-development', 'food', 'culinary',
    //             'react-native', 'react', 'codeigniter3', 'codeigniter2', 'codeigniter', 'relationship', 'language',
    //             'social-convention', 'social-network', 'bootstrap', 'bootstrap-css', 'foundation-css', 'express.js',
    //             'mongodb', 'linux', 'windows', 'windows-7', 'windows-10', 'linux-ubuntu', 'writing', 'literature',
    //             'vacation', 'world-problem', 'ios', '.net', 'mac-OS', 'ASP', 'C', 'C++', 'C#', 'python', 'python3',
    //             'ruby', 'ruby-on-rails', 'django', 'flask', 'javascript', 'es6', 'es7', 'es5', 'wordpress',
    //             'drupal', 'node.js', 'angular', 'angular2'];

    //     foreach($tags as $tag){
    //         DB::insert("
    //             INSERT INTO tags (tag)
    //             VALUES (?)
    //         ", [$tag]);
    //     }
    // }

}
