<?php

namespace app\Http\Controllers;

use app\ArticleInisiative;
use app\Post;
use app\Question;
use app\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
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
                    ORDER BY department_name
            ");
        $data['department'] = $department;

        $themes = DB::select("
                    SELECT * FROM themes
                    WHERE is_active = 1
            ");
        $data['themes'] = $themes;

        $users = DB::select("
                    SELECT * FROM users WHERE is_approved = 'active'
        ");
        $data['users'] = $users;

        $sumbers = DB::select("
                    SELECT * FROM sumbers
                    WHERE is_active = 1
            ");
        $data['sumbers'] = $sumbers;
        
        $tags = DB::select("
                    SELECT * FROM tags
                    ORDER BY tag
            ");
        $data['tags'] = $tags;

        $user = DB::select("
                    SELECT * FROM users
                    ORDER BY id
            ");
        $data['user'] = $user;
        $articles = ArticleInisiative::latest()->paginate(5);
        $data['articles'] = $articles;
        return view('article.create', $data)
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function articleform(Request $request)
    {   
        $data = [];
        $data['filter'] = $request->filter ?: 'recent';
        $filter = $data['filter'];
        $data['page'] = $request->page ?: 1;
        $page = $data['page'];
        $data['limit'] = $request->limit ?: 10;

        if($data['filter'] == 'recent'){
            $articles = ArticleInisiative::orderBy('created_at', 'desc')
                            ->where('security', 'sharing')
                            ->where('is_active', 1)
                            ->orderBy('id', 'desc');
        } elseif($data['filter'] == 'all'){
          $articles = ArticleInisiative::orderBy('created_at', 'desc')
                          ->where('is_active', 1)
                          ->orderBy('id', 'desc');
        } else {
            $articles = ArticleInisiative::orderBy('created_at', 'desc')
                ->where('security', 'sharing')
                ->where('is_active', 1)
                ->orderBy('id', 'desc');
        }
        $articles = $articles->paginate(5);
        $articles->setPath(url("/articles/?filter=$filter"));

        $data['articles'] = $articles;

        // $articles = DB::select("
        // SELECT * FROM article_inisiatives
        // WHERE is_active = 1 and security = 'sharing'
        // ");
        $department = DB::select("
                    SELECT * FROM departments
                    ORDER BY department_name
            ");
        $data['department'] = $department;

        foreach($data['articles'] as $article){
        $users = DB::select("
                    SELECT 
                      u.username
                    FROM 
                      users u, article_inisiatives a 
                    WHERE
                      a.id = ? AND
                      a.user_id = u.id
                ", [$article->id]);

            $article->user = $users[0]->username;

        $themes = DB::select("
                    SELECT 
                      t.theme
                    FROM 
                      themes t, article_inisiatives a 
                    WHERE
                      a.id = ? AND
                      a.theme_id = t.id
                ", [$article->id]);

            $article->theme = $themes[0]->theme;

       $sumbers = DB::select("
                    SELECT 
                      s.sumber_name 
                    FROM 
                      sumbers s, article_has_sumbers phs 
                    WHERE
                      phs.article_id = ? AND
                      phs.sumber_id = s.id
                ", [$article->id]);

            $result_sumbers = [];
            foreach($sumbers as $sumber){
                array_push($result_sumbers, $sumber->sumber_name);
            }
            $article->sumbers = $result_sumbers;

         $tags = DB::select("
                    SELECT 
                      t.tag 
                    FROM 
                      tags t, article_has_tags phs 
                    WHERE
                      phs.article_id = ? AND
                      phs.tag_id = t.id
                ", [$article->id]);

            $result_tags = [];
            foreach($tags as $tag){
                array_push($result_tags, $tag->tag);
            }
            $article->tags = $result_tags;
        }

        return view('article.index', $data);
    }

    public function approve(Request $request)
    {   
      if(Session::get('is_admin') === 0 || Session::has('username') === false) {
        $request->session()->flash('notification', TRUE);
        $request->session()->flash('notification_type', 'danger');
        $request->session()->flash('notification_msg', 'You cant enter that area!');
        return redirect()->to('/');
      }
        $data = [];
        $data['filter'] = $request->filter ?: 'recent';
        $filter = $data['filter'];
        $data['page'] = $request->page ?: 1;
        $page = $data['page'];
        $data['limit'] = $request->limit ?: 10;

        if($data['filter'] == 'recent'){
            $articles = ArticleInisiative::orderBy('created_at', 'desc')
                            ->where('is_active', 2)
                            ->orderBy('id', 'desc');
        } else {
            $articles = ArticleInisiative::orderBy('created_at', 'desc')
                ->where('is_active', 2)
                ->orderBy('id', 'desc');
        }
        $articles = $articles->paginate(5);
        $articles->setPath(url("/articles/?filter=$filter"));

        $data['articles'] = $articles;

        // $articles = DB::select("
        // SELECT * FROM article_inisiatives
        // WHERE is_active = 1 and security = 'sharing'
        // ");
        $department = DB::select("
                    SELECT * FROM departments
                    ORDER BY department_name
            ");
        $data['department'] = $department;

        foreach($data['articles'] as $article){
        $users = DB::select("
                    SELECT 
                      u.username
                    FROM 
                      users u, article_inisiatives a 
                    WHERE
                      a.id = ? AND
                      a.user_id = u.id
                ", [$article->id]);

            $article->user = $users[0]->username;

        $themes = DB::select("
                    SELECT 
                      t.theme
                    FROM 
                      themes t, article_inisiatives a 
                    WHERE
                      a.id = ? AND
                      a.theme_id = t.id
                ", [$article->id]);

            $article->theme = $themes[0]->theme;

       $sumbers = DB::select("
                    SELECT 
                      s.sumber_name 
                    FROM 
                      sumbers s, article_has_sumbers phs 
                    WHERE
                      phs.article_id = ? AND
                      phs.sumber_id = s.id
                ", [$article->id]);

            $result_sumbers = [];
            foreach($sumbers as $sumber){
                array_push($result_sumbers, $sumber->sumber_name);
            }
            $article->sumbers = $result_sumbers;

         $tags = DB::select("
                    SELECT 
                      t.tag 
                    FROM 
                      tags t, article_has_tags phs 
                    WHERE
                      phs.article_id = ? AND
                      phs.tag_id = t.id
                ", [$article->id]);

            $result_tags = [];
            foreach($tags as $tag){
                array_push($result_tags, $tag->tag);
            }
            $article->tags = $result_tags;
        }

        return view('article.approve', $data);
    }

    public function approveview($id)
    {   
        $data = [];

        $department = DB::select("
                    SELECT * FROM departments
                    ORDER BY department_name
            ");
        $data['department'] = $department;

        $articles = DB::select("
        SELECT * FROM article_inisiatives
        ORDER BY id, title, content, user_id
        ");

        $data['articles'] = DB::select("
            SELECT 
              a.*, 
              u.username as 'username', 
              u.id as 'user_id'
            FROM article_inisiatives a, users u
            WHERE
              a.id = ? AND 
              a.user_id = u.id 
            ", [$id])[0];

        $data['themes'] = DB::select("
            SELECT 
              t.*
            FROM article_inisiatives a, themes t
            WHERE
              a.id = ? AND 
              a.theme_id = t.id 
            ", [$id])[0];   

        $tags = DB::select("
                    SELECT 
                      t.tag
                    FROM 
                      tags t, article_has_tags aht 
                    WHERE
                      aht.article_id = ? AND
                      aht.tag_id = t.id
                ", [$id]);

        $result_tags = [];
        foreach($tags as $tag){
            array_push($result_tags, $tag->tag);
        }
        $data['article_tags'] = $result_tags;

        $sumbers = DB::select("
                    SELECT 
                      s.sumber_name
                    FROM 
                      sumbers s, article_has_sumbers ahs
                    WHERE
                      ahs.article_id = ? AND
                      ahs.sumber_id = s.id
                ", [$id]);

        $result_sumbers = [];
        foreach($sumbers as $sumber){
            array_push($result_sumbers, $sumber->sumber_name);
        }
        $data['article_sumbers'] = $result_sumbers;

        $files = DB::select("
                    SELECT 
                      ahf.filename_article
                    FROM 
                      article_has_files ahf 
                    WHERE
                      ahf.article_id = ?
                ", [$id]);

        $result_files = [];
        foreach($files as $file){
            array_push($result_files, $file->filename_article);
        }
        $data['article_files'] = $result_files;

        return view('article.approveview', $data);
    }

    public function personal($id, Request $request)
    {   
        $data = [];
        $data['filter'] = $request->filter ?: 'recent';
        $filter = $data['filter'];
        $data['page'] = $request->page ?: 1;
        $page = $data['page'];
        $data['limit'] = $request->limit ?: 10;

        if($data['filter'] == 'recent'){
            $articles = ArticleInisiative::orderBy('created_at', 'desc')
                            ->where('is_active', 1)
                            ->where('user_id', $id)
                            ->orderBy('id', 'desc');
        } else {
            $articles = ArticleInisiative::orderBy('created_at', 'desc')
                ->where('is_active', 1)
                ->where('user_id', $id)
                ->orderBy('id', 'desc');;
        }
        $articles = $articles->paginate(5);
        $articles->setPath(url("/articles/$id/?filter=$filter"));

        $data['articles'] = $articles;

        $department = DB::select("
                    SELECT * FROM departments
                    ORDER BY department_name
            ");
        $data['department'] = $department;

        // $articles = DB::select("
        // SELECT * FROM article_inisiatives
        // WHERE is_active = 1 and user_id = ?
        // ",[$id]);

        foreach($data['articles'] as $article){
        $users = DB::select("
                    SELECT 
                      u.username
                    FROM 
                      users u, article_inisiatives a 
                    WHERE
                      a.id = ? AND
                      a.user_id = u.id
                ", [$article->id]);

            $article->user = $users[0]->username;

        $themes = DB::select("
                    SELECT 
                      t.theme
                    FROM 
                      themes t, article_inisiatives a 
                    WHERE
                      a.id = ? AND
                      a.theme_id = t.id
                ", [$article->id]);

            $article->theme = $themes[0]->theme;

       $sumbers = DB::select("
                    SELECT 
                      s.sumber_name 
                    FROM 
                      sumbers s, article_has_sumbers phs 
                    WHERE
                      phs.article_id = ? AND
                      phs.sumber_id = s.id
                ", [$article->id]);

            $result_sumbers = [];
            foreach($sumbers as $sumber){
                array_push($result_sumbers, $sumber->sumber_name);
            }
            $article->sumbers = $result_sumbers;

         $tags = DB::select("
                    SELECT 
                      t.tag 
                    FROM 
                      tags t, article_has_tags phs 
                    WHERE
                      phs.article_id = ? AND
                      phs.tag_id = t.id
                ", [$article->id]);

            $result_tags = [];
            foreach($tags as $tag){
                array_push($result_tags, $tag->tag);
            }
            $article->tags = $result_tags;
        }

        return view('article.personal', $data);
    }

    public function articleformtag($id ,Request $request)
    {   
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

      // $data['tag'] = $tag;
      $data['id'] = $id;

      // $tag_id = DB::select("
      //         SELECT * FROM tags WHERE tag = ?
      //     ", [$tag])[0]->id;

      $articles = DB::select("
              SELECT article_id FROM tagged_user_articles WHERE user_id = ?
          ", [$id]);

      $article_ids = array();
      foreach($articles as $article){
          array_push($article_ids, $article->article_id);
      }
      if($data['filter'] == 'recent'){
          $articles = ArticleInisiative::orderBy('created_at', 'desc')
              ->where('security', 'konfidensial')
              ->where('is_active', 1)
              ->orderBy('id', 'desc');
      } else if($data['filter'] == 'trending'){
          
      } else {
          // fallback if user entered random gibberish in the url
          $articles = ArticleInisiative::orderBy('created_at', 'desc')
              ->orderBy('id', 'desc');;
      }
      $articles = $articles->whereIn('id', $article_ids)->paginate(5);

      $articles->setPath(url("/$id/?filter=$filter"));
      $data['articles'] = $articles;

      // $articles = DB::select("
      // SELECT * FROM article_inisiatives
      // ");
      
      foreach($data['articles'] as $article){
      $users = DB::select("
                  SELECT 
                    u.username
                  FROM 
                    users u, article_inisiatives a 
                  WHERE
                    a.id = ? AND
                    a.user_id = u.id
              ", [$article->id]);

          $article->user = $users[0]->username;

           $themes = DB::select("
                  SELECT 
                    t.theme
                  FROM 
                    themes t, article_inisiatives a
                  WHERE
                    a.id = ? AND
                    a.theme_id = t.id
              ", [$article->id]);

          $article->theme = $themes[0]->theme;

           $sumbers = DB::select("
                  SELECT 
                    s.sumber_name 
                  FROM 
                    sumbers s, article_has_sumbers phs 
                  WHERE
                    phs.article_id = ? AND
                    phs.sumber_id = s.id
              ", [$article->id]);

          $result_sumbers = [];
          foreach($sumbers as $sumber){
              array_push($result_sumbers, $sumber->sumber_name);
          }
          $article->sumbers = $result_sumbers;

       $tags = DB::select("
                  SELECT 
                    t.tag 
                  FROM 
                    tags t, article_has_tags phs 
                  WHERE
                    phs.article_id = ? AND
                    phs.tag_id = t.id
              ", [$article->id]);

          $result_tags = [];
          foreach($tags as $tag){
              array_push($result_tags, $tag->tag);
          }
          $article->tags = $result_tags;
      }
        return view('article.tag', $data);
    }

    public function department($id ,Request $request)
    {   
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

      // $data['tag'] = $tag;
      $data['id'] = $id;

      // $tag_id = DB::select("
      //         SELECT * FROM tags WHERE tag = ?
      //     ", [$tag])[0]->id;

      $articles = DB::select("
              SELECT article_id FROM tagged_department_article WHERE department_id = ?
          ", [$id]);

      $article_ids = array();
      foreach($articles as $article){
          array_push($article_ids, $article->article_id);
      }
      if($data['filter'] == 'recent'){
          $articles = ArticleInisiative::orderBy('created_at', 'desc')
              ->where('security', 'konfidensial')
              ->where('is_active', 1)
              ->orderBy('id', 'desc');
      } else if($data['filter'] == 'trending'){
          
      } else {
          // fallback if user entered random gibberish in the url
          $articles = ArticleInisiative::orderBy('created_at', 'desc')
              ->orderBy('id', 'desc');;
      }
      $articles = $articles->whereIn('id', $article_ids)->paginate(5);

      $articles->setPath(url("/$id/?filter=$filter"));
      $data['articles'] = $articles;

      // $articles = DB::select("
      // SELECT * FROM article_inisiatives
      // ");
      
      foreach($data['articles'] as $article){
      $users = DB::select("
                  SELECT 
                    u.username
                  FROM 
                    users u, article_inisiatives a 
                  WHERE
                    a.id = ? AND
                    a.user_id = u.id
              ", [$article->id]);

          $article->user = $users[0]->username;

           $themes = DB::select("
                  SELECT 
                    t.theme
                  FROM 
                    themes t, article_inisiatives a
                  WHERE
                    a.id = ? AND
                    a.theme_id = t.id
              ", [$article->id]);

          $article->theme = $themes[0]->theme;

           $sumbers = DB::select("
                  SELECT 
                    s.sumber_name 
                  FROM 
                    sumbers s, article_has_sumbers phs 
                  WHERE
                    phs.article_id = ? AND
                    phs.sumber_id = s.id
              ", [$article->id]);

          $result_sumbers = [];
          foreach($sumbers as $sumber){
              array_push($result_sumbers, $sumber->sumber_name);
          }
          $article->sumbers = $result_sumbers;

       $tags = DB::select("
                  SELECT 
                    t.tag 
                  FROM 
                    tags t, article_has_tags phs 
                  WHERE
                    phs.article_id = ? AND
                    phs.tag_id = t.id
              ", [$article->id]);

          $result_tags = [];
          foreach($tags as $tag){
              array_push($result_tags, $tag->tag);
          }
          $article->tags = $result_tags;
      }
        return view('article.tagd', $data);
    }

    public function search(Request $request)
    {   
        $data = [];
        $data['filter'] = $request->filter ?: 'recent';
        $filter = $data['filter'];
        $data['page'] = $request->page ?: 1;
        $page = $data['page'];
        $data['limit'] = $request->limit ?: 10;

        $search = $request->search;
        
        if($data['filter'] == 'recent'){
            $articles = ArticleInisiative::orderBy('created_at', 'desc')
                            ->where('title','like',"%".$search."%")
                            ->where('security', 'sharing')
                            ->where('is_active', 1)
                            ->orderBy('id', 'desc');
        } else {
            $articles = ArticleInisiative::orderBy('created_at', 'desc')
                            ->where('title','like',"%".$search."%")
                            ->where('security', 'sharing')
                            ->where('is_active', 1)
                            ->orderBy('id', 'desc');
        }
        $articles = $articles->paginate(5);
        $articles->setPath(url("/articles/?filter=$filter"));

        $data['articles'] = $articles;

        $department = DB::select("
                    SELECT * FROM departments
                    ORDER BY department_name
            ");
        $data['department'] = $department;

        foreach($data['articles'] as $article){
        $users = DB::select("
                    SELECT 
                      u.username
                    FROM 
                      users u, article_inisiatives a 
                    WHERE
                      a.id = ? AND
                      a.user_id = u.id
                ", [$article->id]);

            $article->user = $users[0]->username;

        $themes = DB::select("
                    SELECT 
                      t.theme
                    FROM 
                      themes t, article_inisiatives a 
                    WHERE
                      a.id = ? AND
                      a.theme_id = t.id
                ", [$article->id]);

            $article->theme = $themes[0]->theme;

       $sumbers = DB::select("
                    SELECT 
                      s.sumber_name 
                    FROM 
                      sumbers s, article_has_sumbers phs 
                    WHERE
                      phs.article_id = ? AND
                      phs.sumber_id = s.id
                ", [$article->id]);

            $result_sumbers = [];
            foreach($sumbers as $sumber){
                array_push($result_sumbers, $sumber->sumber_name);
            }
            $article->sumbers = $result_sumbers;

         $tags = DB::select("
                    SELECT 
                      t.tag 
                    FROM 
                      tags t, article_has_tags phs 
                    WHERE
                      phs.article_id = ? AND
                      phs.tag_id = t.id
                ", [$article->id]);

            $result_tags = [];
            foreach($tags as $tag){
                array_push($result_tags, $tag->tag);
            }
            $article->tags = $result_tags;
        }

        return view('article.index', $data);
    }

    public function articleEditForm($id ,Request $request){
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

      $sumbers = DB::select("
                  SELECT * FROM sumbers
                  WHERE is_active = 1
          ");
      $data['sumbers'] = $sumbers;
      
      $themes = DB::select("
                  SELECT * FROM themes
                  WHERE is_active = 1
          ");
      $data['themes'] = $themes;

      $articles = DB::select("
      SELECT * FROM article_inisiatives
      ");

      $data['articles'] = DB::select("
          SELECT 
            a.*, 
            u.username as 'username', 
            u.id as 'user_id'
          FROM article_inisiatives a, users u
          WHERE
            a.id = ? AND 
            a.user_id = u.id 
          ", [$id])[0];

      $tags = DB::select("
                  SELECT * FROM tags
                  ORDER BY tag
          ");
      $data['tags'] = $tags;

      $tags = DB::select("
                  SELECT 
                    t.tag
                  FROM 
                    tags t, article_has_tags aht
                  WHERE
                    aht.article_id = ? AND
                    aht.tag_id = t.id
              ", [$id]);

      $result_tags = [];
      foreach($tags as $tag){
          array_push($result_tags, $tag->tag);
      }
      $data['article_tags'] = $result_tags;

      $sumbers = DB::select("
                  SELECT 
                    s.sumber_name
                  FROM 
                    sumbers s, article_has_sumbers ahs
                  WHERE
                    ahs.article_id = ? AND
                    ahs.sumber_id = s.id
              ", [$id]);

      $result_sumbers = [];
      foreach($sumbers as $sumber){
          array_push($result_sumbers, $sumber->sumber_name);
      }
      $data['article_sumbers'] = $result_sumbers;

      return view('article.edit', $data);
  }

  public function articleEdit($id, Request $request){
    DB::beginTransaction();

    try {
        DB::delete("
            DELETE FROM article_has_sumbers 
            WHERE 
            article_id = ? 
        ", [$id]);

         $sumbers = $request->input('sumbers');
         $article = DB::getPdo()->lastInsertId();
         foreach($sumbers as $sumber){
             DB::insert("
                 INSERT INTO article_has_sumbers 
                 (sumber_id, article_id) 
                 VALUES (?, ?)  
             ", [$sumber, $id]);
         }

         DB::delete("
            DELETE FROM article_has_tags 
            WHERE 
            article_id = ? 
        ", [$id]);

         $tags = $request->input('tags');
         $article = DB::getPdo()->lastInsertId();
         foreach($tags as $tag){
             DB::insert("
                 INSERT INTO article_has_tags 
                 (tag_id, article_id) 
                 VALUES (?, ?)  
             ", [$tag, $id]);
         }
       
        DB::table('article_inisiatives')->where('id',$request->id)->update([
            'content' => $request->content,
            'theme_id' => $request->theme,
            'summary' => $request->summary
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

    return redirect()->to('/articles');
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

        $articles = DB::select("
                SELECT article_id FROM article_has_tags WHERE tag_id = ?
            ", [$tag_id]);

        $article_ids = array();
        foreach($articles as $article){
            array_push($article_ids, $article->article_id);
        }
        if($data['filter'] == 'recent'){
            $articles = ArticleInisiative::orderBy('created_at', 'desc')
                ->where('is_active', 1)
                ->where('security', 'sharing')
                ->orderBy('id', 'desc');
        } else if($data['filter'] == 'trending'){
            
        } else {
            // fallback if user entered random gibberish in the url
            $articles = ArticleInisiative::orderBy('created_at', 'desc')
                ->orderBy('id', 'desc');;
        }
        $articles = $articles->whereIn('id', $article_ids)->paginate($data['limit']);

        $articles->setPath(url("/$tag/?filter=$filter"));
        $data['articles'] = $articles;

        foreach($data['articles'] as $article){
        $users = DB::select("
                    SELECT 
                      u.username
                    FROM 
                      users u, article_inisiatives a 
                    WHERE
                      a.id = ? AND
                      a.user_id = u.id
                ", [$article->id]);

            $article->user = $users[0]->username;

             $themes = DB::select("
                    SELECT 
                      t.theme
                    FROM 
                      themes t, article_inisiatives a
                    WHERE
                      a.id = ? AND
                      a.theme_id = t.id
                ", [$article->id]);

            $article->theme = $themes[0]->theme;

             $sumbers = DB::select("
                    SELECT 
                      s.sumber_name 
                    FROM 
                      sumbers s, article_has_sumbers phs 
                    WHERE
                      phs.article_id = ? AND
                      phs.sumber_id = s.id
                ", [$article->id]);

            $result_sumbers = [];
            foreach($sumbers as $sumber){
                array_push($result_sumbers, $sumber->sumber_name);
            }
            $article->sumbers = $result_sumbers;

         $tags = DB::select("
                    SELECT 
                      t.tag 
                    FROM 
                      tags t, article_has_tags phs 
                    WHERE
                      phs.article_id = ? AND
                      phs.tag_id = t.id
                ", [$article->id]);

            $result_tags = [];
            foreach($tags as $tag){
                array_push($result_tags, $tag->tag);
            }
            $article->tags = $result_tags;
        }


        return view('tags.article', $data);
    }

    public function sumber($sumber, Request $request){
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

        $data['sumber'] = $sumber;

        $sumber_id = DB::select("
                SELECT * FROM sumbers WHERE sumber_name = ?
            ", [$sumber])[0]->id;

        $articles = DB::select("
                SELECT article_id FROM article_has_sumbers WHERE sumber_id = ?
            ", [$sumber_id]);

        $article_ids = array();
        foreach($articles as $article){
            array_push($article_ids, $article->article_id);
        }

        if($data['filter'] == 'recent'){
            $articles = ArticleInisiative::orderBy('created_at', 'desc')
                ->where('is_active', 1)
                ->where('security', 'sharing')
                ->orderBy('id', 'desc');
        } else if($data['filter'] == 'trending'){
            
        } else {
            // fallback if user entered random gibberish in the url
            $articles = ArticleInisiative::orderBy('created_at', 'desc')
                ->orderBy('id', 'desc');;
        }
        $articles = $articles->whereIn('id', $article_ids)->paginate(5);

        $articles->setPath(url("/$sumber/?filter=$filter"));
        $data['articles'] = $articles;

        foreach($data['articles'] as $article){
        $users = DB::select("
                    SELECT 
                      u.username
                    FROM 
                      users u, article_inisiatives a 
                    WHERE
                      a.id = ? AND
                      a.user_id = u.id
                ", [$article->id]);

            $article->user = $users[0]->username;

         $themes = DB::select("
                    SELECT 
                      t.theme
                    FROM 
                      themes t, article_inisiatives a
                    WHERE
                      a.id = ? AND
                      a.theme_id = t.id
                ", [$article->id]);

            $article->theme = $themes[0]->theme;

         $sumbers = DB::select("
                    SELECT 
                      s.sumber_name 
                    FROM 
                      sumbers s, article_has_sumbers phs 
                    WHERE
                      phs.article_id = ? AND
                      phs.sumber_id = s.id
                ", [$article->id]);

            $result_sumbers = [];
            foreach($sumbers as $sumber){
                array_push($result_sumbers, $sumber->sumber_name);
            }
            $article->sumbers = $result_sumbers;

         $tags = DB::select("
                    SELECT 
                      t.tag 
                    FROM 
                      tags t, article_has_tags phs 
                    WHERE
                      phs.article_id = ? AND
                      phs.tag_id = t.id
                ", [$article->id]);

            $result_tags = [];
            foreach($tags as $tag){
                array_push($result_tags, $tag->tag);
            }
            $article->tags = $result_tags;
        }


        return view('tags.sumber', $data);
    }

    public function articleview($id)
    {   
        $data = [];

        $department = DB::select("
                    SELECT * FROM departments
                    ORDER BY department_name
            ");
        $data['department'] = $department;

        $articles = DB::select("
        SELECT * FROM article_inisiatives
        ORDER BY id, title, content, user_id
        ");

        $data['articles'] = DB::select("
            SELECT 
              a.*, 
              u.username as 'username', 
              u.id as 'user_id'
            FROM article_inisiatives a, users u
            WHERE
              a.id = ? AND 
              a.user_id = u.id 
            ", [$id])[0];

        $data['themes'] = DB::select("
            SELECT 
              t.*
            FROM article_inisiatives a, themes t
            WHERE
              a.id = ? AND 
              a.theme_id = t.id 
            ", [$id])[0];   

        $tags = DB::select("
                    SELECT 
                      t.tag
                    FROM 
                      tags t, article_has_tags aht 
                    WHERE
                      aht.article_id = ? AND
                      aht.tag_id = t.id
                ", [$id]);

        $result_tags = [];
        foreach($tags as $tag){
            array_push($result_tags, $tag->tag);
        }
        $data['article_tags'] = $result_tags;

        $sumbers = DB::select("
                    SELECT 
                      s.sumber_name
                    FROM 
                      sumbers s, article_has_sumbers ahs
                    WHERE
                      ahs.article_id = ? AND
                      ahs.sumber_id = s.id
                ", [$id]);

        $result_sumbers = [];
        foreach($sumbers as $sumber){
            array_push($result_sumbers, $sumber->sumber_name);
        }
        $data['article_sumbers'] = $result_sumbers;

        $files = DB::select("
                    SELECT 
                      ahf.filename_article
                    FROM 
                      article_has_files ahf 
                    WHERE
                      ahf.article_id = ?
                ", [$id]);

        $result_files = [];
        foreach($files as $file){
            array_push($result_files, $file->filename_article);
        }
        $data['article_files'] = $result_files;

        $refrences = DB::select("
                    SELECT 
                      ahr.refrence
                    FROM 
                      article_has_refrences ahr 
                    WHERE
                      ahr.article_id = ?
                ", [$id]);

        $result_refrences = [];
        foreach($refrences as $refrence){
            array_push($result_refrences, $refrence->refrence);
        }
        $data['article_refrences'] = $result_refrences;

        return view('article.view', $data);
    }
   
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('articles.create');
    }
  
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
            $article = new ArticleInisiative();
            $article->title = Input::get('title');
            $article->content = Input::get('content');
            $article->summary = Input::get('summary');
            $article->security = Input::get('security');
            $article->user_id = Session::get('id');
            $article->theme_id = Input::get('theme');
            if(Session::get('is_admin') === 1){
            $article->is_active = 2;
            }else{
            $article->is_active = 1;
            }
            $article->save();

            // associate the related sumber
            $sumbers = $request->input('sumbers');
            $article_id = DB::getPdo()->lastInsertId();
            foreach($sumbers as $sumber){
                DB::insert("
                    INSERT INTO article_has_sumbers
                    (sumber_id, article_id) 
                    VALUES (?, ?)
                ", [$sumber, $article_id]);
            }
            
            $tags = $request->input('tags');
            foreach($tags as $tag){
                DB::insert("
                    INSERT INTO article_has_tags 
                    (tag_id, article_id) 
                    VALUES (?, ?)
                ", [$tag, $article_id]);
            }

            if($request->hasfile('file'))
                {
                    foreach($request->file('file') as $file)
                    {
                        $name = $file->getClientOriginalName();
                        $file->move(public_path().'/files/', $name);  
                        $data[] = $name;  
                    }
                }
                if($request->file('file') === null){

                }
                else{
                foreach($data as $data){
                    DB::insert("
                        INSERT INTO article_has_files
                        (filename_article, article_id) 
                        VALUES (?, ?)
                    ", [$data, $article_id]);
                  }
                }

            if($request->input('refrence') === null){

            }
            else{
                $refrences = $request->input('refrence');
                foreach($refrences as $refrence){
                    DB::insert("
                        INSERT INTO article_has_refrences
                        (article_id, refrence) 
                        VALUES (?, ?)
                    ", [$article_id, $refrence]);
              }
            }

            $security = $request->input('security');
            $users = $request->input('users');
            $departments = $request->input('departments');
            if($security === 'konfidensial'){
              if($users === null){
                foreach($departments as $department){
                  DB::insert("
                      INSERT INTO tagged_department_article
                      (article_id, department_id)
                      VALUES (?, ?)
                  ", [$article_id, $department]);
                }
              }
              else if($departments === null){
                foreach($users as $user){
                  DB::insert("
                      INSERT INTO tagged_user_articles
                      (article_id, user_id)
                      VALUES (?, ?)
                  ", [$article_id, $user]);
                }
              }
              else{
                foreach($users as $user){
                    DB::insert("
                        INSERT INTO tagged_user_articles
                        (article_id, user_id)
                        VALUES (?, ?)
                    ", [$article_id, $user]);
                }

                foreach($departments as $department){
                  DB::insert("
                      INSERT INTO tagged_department_article
                      (article_id, department_id)
                      VALUES (?, ?)
                  ", [$article_id, $department]);
              }
            }
          }
            else{
              
            }

        DB::commit();

            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'success');
            $request->session()->flash('notification_msg', 'Your article is posted!');

        return redirect()->to('articles');
    }
   
    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(ArticleInisiative $article)
    {
        return view('article.create',compact('article'));
    }
   
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(ArticleInisiative $article)
    {
        return view('article.create',compact('article'));
    }
  
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ArticleInisiative $article)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);
  
        $article->update($request->all());
  
        return redirect()->route('article.create')
                        ->with('success','Product updated successfully');
    }
  
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */

    public function delete($id, Request $request)
    {
        DB::beginTransaction();

        try {  
            DB::table('article_inisiatives')->where('id',$request->id)->update([
                'is_active' => 0
            ]);

            DB::commit();
            
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'success');
            $request->session()->flash('notification_msg', 'Deleted!');

        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'danger');
            $request->session()->flash('notification_msg', 'Uh oh, something went wrong.');
        }

        return redirect()->to('/articles');
    }

    public function approved($id, Request $request)
    {
        DB::beginTransaction();

        try {  
            DB::table('article_inisiatives')->where('id',$request->id)->update([
                'is_active' => 1
            ]);

            DB::commit();
            
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'success');
            $request->session()->flash('notification_msg', 'Approved!');

        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'danger');
            $request->session()->flash('notification_msg', 'Uh oh, something went wrong.');
        }

        return redirect()->to('/articles/approve');
    }

    public function unapproved($id, Request $request)
    {
        DB::beginTransaction();

        try {  
            DB::table('article_inisiatives')->where('id',$request->id)->update([
                'is_active' => 0
            ]);

            DB::commit();
            
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'danger');
            $request->session()->flash('notification_msg', 'Unapproved!');

        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'danger');
            $request->session()->flash('notification_msg', 'Uh oh, something went wrong.');
        }

        return redirect()->to('/articles/approve');
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

      $articles = DB::select("
              SELECT * FROM article_inisiatives WHERE theme_id = ?
          ", [$theme_id]);

      

      $data['articles'] = $articles;

      foreach($data['articles'] as $article){
      $users = DB::select("
                  SELECT 
                    u.username
                  FROM 
                    users u, article_inisiatives a 
                  WHERE
                    a.id = ? AND
                    a.user_id = u.id
              ", [$article->id]);

          $article->user = $users[0]->username;


      $themes = DB::select("
                    SELECT 
                      t.theme
                    FROM 
                      themes t, article_inisiatives a 
                    WHERE
                      a.id = ? AND
                      a.theme_id = t.id
                ", [$article->id]);

            $article->theme = $themes[0]->theme;

     $sumbers = DB::select("
                  SELECT 
                    s.sumber_name 
                  FROM 
                    sumbers s, article_has_sumbers phs 
                  WHERE
                    phs.article_id = ? AND
                    phs.sumber_id = s.id
              ", [$article->id]);

          $result_sumbers = [];
          foreach($sumbers as $sumber){
              array_push($result_sumbers, $sumber->sumber_name);
          }
          $article->sumbers = $result_sumbers;

       $tags = DB::select("
                  SELECT 
                    t.tag 
                  FROM 
                    tags t, article_has_tags phs 
                  WHERE
                    phs.article_id = ? AND
                    phs.tag_id = t.id
              ", [$article->id]);

          $result_tags = [];
          foreach($tags as $tag){
              array_push($result_tags, $tag->tag);
          }
          $article->tags = $result_tags;
      }


      return view('tags.themeArticle', $data);
     }
}
