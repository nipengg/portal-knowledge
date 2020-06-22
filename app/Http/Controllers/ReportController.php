<?php

namespace app\Http\Controllers;

use app\Report;
use app\Question;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DateTime;
use Excel;
use FastExcel;
use PDF;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        if(Session::get('is_admin') === 0 || Session::has('username') === false) {
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'danger');
            $request->session()->flash('notification_msg', 'You cant enter that area!');
            return redirect()->to('/');
        }
        $data = [];

        $questions = DB::select("
                    SELECT * FROM questions
            ");
        $data['questions'] = $questions;

        foreach ($data['questions'] as $question){

            $users = DB::select("
                    SELECT 
                        u.username
                    FROM 
                        questions q, users u
                    WHERE
                        q.id = ? AND
                        u.id = q.user_id
                    ", [$question->id]);
    
            $question->users= $users[0]->username;

            $created = DB::select("
                            SELECT 
                                created_at
                            FROM 
                                questions q
                            WHERE
                                q.id = ?
                            ", [$question->id]);
            $question->created= $created[0]->created_at;

            $closed = DB::select("
                            SELECT 
                                closed_at
                            FROM 
                                questions q
                            WHERE
                                q.id = ?
                            ", [$question->id]);
            $question->closed = $closed[0]->closed_at;

            $departments = DB::select("
                    SELECT 
                      d.department_name
                    FROM 
                      departments d, users u , questions q
                    WHERE
                        q.id = ? AND
                        u.id = q.user_id AND
                        d.department_id = u.department_id
                ", [$question->id]);

            $question->department= $departments[0]->department_name;

            $files = DB::select("
                    SELECT 
                      qhf.filename 
                    FROM 
                       question_has_files qhf, questions q , posts p
                    WHERE
                      q.id = ? AND
                      p.question_id = q.id AND
                      qhf.post_id = p.id 
                ", [$question->id]);

            $result_files = [];
            foreach($files as $file){
                array_push($result_files, $file->filename);
            }
            $question->files = $result_files;

            $refrences = DB::select("
                    SELECT 
                        p.refrence
                    FROM 
                        questions q , posts p
                    WHERE
                      q.id = ? AND
                      p.question_id = q.id
                ", [$question->id]);

            $result_refrences = [];
            foreach($refrences as $refrence){
                array_push($result_refrences, $refrence->refrence);
            }
            $question->refrences = $result_refrences;

            if($question->closed === null){
                $question->days = 'Not Finished';
            }
            else{
            $to = \Carbon\Carbon::parse($question->closed);
            $from = \Carbon\Carbon::parse($question->created);
            $diff_in_days = $to->diffInDays($from);
            $question->days = $diff_in_days;
            }
        }

        return view('report.index', $data);
    }

    public function pdf()
    {
        $data = [];

        $questions = DB::select("
                    SELECT * FROM questions
            ");
        $data['questions'] = $questions;

        $done = DB::select("
                    SELECT * FROM questions
                    WHERE
                    accepted_answer_id = 1
            ");
        $data['done'] = $done;

        foreach ($data['questions'] as $question){
            $users = DB::select("
                    SELECT 
                        u.username
                    FROM 
                        questions q, users u
                    WHERE
                        q.id = ? AND
                        u.id = q.user_id
                    ", [$question->id]);
    
            $question->users= $users[0]->username;

            $created = DB::select("
                            SELECT 
                                created_at
                            FROM 
                                questions q
                            WHERE
                                q.id = ?
                            ", [$question->id]);
            $question->created= $created[0]->created_at;

            $closed = DB::select("
                            SELECT 
                                closed_at
                            FROM 
                                questions q
                            WHERE
                                q.id = ?
                            ", [$question->id]);
            $question->closed = $closed[0]->closed_at;

            $departments = DB::select("
                    SELECT 
                      d.department_name
                    FROM 
                      departments d, users u , questions q
                    WHERE
                        q.id = ? AND
                        u.id = q.user_id AND
                        d.department_id = u.department_id
                ", [$question->id]);

            $question->department= $departments[0]->department_name;

            $files = DB::select("
                    SELECT 
                      qhf.filename 
                    FROM 
                       question_has_files qhf, questions q , posts p
                    WHERE
                      q.id = ? AND
                      p.question_id = q.id AND
                      qhf.post_id = p.id 
                ", [$question->id]);

            $result_files = [];
            foreach($files as $file){
                array_push($result_files, $file->filename);
            }
            $question->files = $result_files;

            $refrences = DB::select("
                    SELECT 
                        p.refrence
                    FROM 
                        questions q , posts p
                    WHERE
                      q.id = ? AND
                      p.question_id = q.id
                ", [$question->id]);

            $result_refrences = [];
            foreach($refrences as $refrence){
                array_push($result_refrences, $refrence->refrence);
            }
            $question->refrences = $result_refrences;

            if($question->closed === null){
                $question->days = 'Not Finished';
            }
            else{
            $to = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $question->closed);
            $from = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $question->created);
            $diff_in_days = $to->diffInDays($from);
            $question->days = $diff_in_days;
            }
        }
        $done = Question::get();
        $pdf = PDF::loadView('report.a_pdf',compact('done') ,$data);
        return $pdf->download('laporan_request_'.date('Y-m-d_H-i-s').'.pdf', $data);
    }

    public function excel(Request $request){
        $nama = 'laporan_'.date('Y-m-d_H-i-s');
        Excel::create($nama, function ($excel) use ($request) {
        $excel->sheet('Laporan Data Request', function ($sheet) use ($request) {
        
        $sheet->mergeCells('A1:L1');
        $sheet->setBorder('A1:L10', 'thin');

       // $sheet->setAllBorders('thin');
        $sheet->row(1, function ($row) {
            $row->setFontFamily('Calibri');
            $row->setFontSize(11);
            $row->setAlignment('center');
            $row->setFontWeight('bold');
        });

        $sheet->row(1, array('LAPORAN DATA REQUEST'));

        $sheet->row(2, function ($row) {
            $row->setFontFamily('Calibri');
            $row->setFontSize(11);
            $row->setFontWeight('bold');
        });

        $questions = DB::select("
                    SELECT * FROM questions
            ");
        $data['questions'] = $questions;

        foreach ($data['questions'] as $question){

            $users = DB::select("
                    SELECT 
                        u.username
                    FROM 
                        questions q, users u
                    WHERE
                        q.id = ? AND
                        u.id = q.user_id
                    ", [$question->id]);
    
            $question->users= $users[0]->username;

            $created = DB::select("
                            SELECT 
                                created_at
                            FROM 
                                questions q
                            WHERE
                                q.id = ?
                            ", [$question->id]);
            $question->created= $created[0]->created_at;

            $closed = DB::select("
                            SELECT 
                                closed_at
                            FROM 
                                questions q
                            WHERE
                                q.id = ?
                            ", [$question->id]);
            $question->closed = $closed[0]->closed_at;

            $departments = DB::select("
                    SELECT 
                      d.department_name
                    FROM 
                      departments d, users u , questions q
                    WHERE
                        q.id = ? AND
                        u.id = q.user_id AND
                        d.department_id = u.department_id
                ", [$question->id]);

            $question->department= $departments[0]->department_name;

            $files = DB::select("
                    SELECT 
                      qhf.filename 
                    FROM 
                       question_has_files qhf, questions q , posts p
                    WHERE
                      q.id = ? AND
                      p.question_id = q.id AND
                      qhf.post_id = p.id 
                ", [$question->id]);

            $result_files = [];
            foreach($files as $file){
                array_push($result_files, $file->filename);
            }
            $question->files = $result_files;

            $refrences = DB::select("
                    SELECT 
                        p.refrence
                    FROM 
                        questions q , posts p
                    WHERE
                      q.id = ? AND
                      p.question_id = q.id
                ", [$question->id]);

            $result_refrences = [];
            foreach($refrences as $refrence){
                array_push($result_refrences, $refrence->refrence);
            }
            $question->refrences = $result_refrences;

            if($question->closed === null){
                $question->days = 'Not Finished';
            }
            else{
            $to = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $question->closed);
            $from = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $question->created);
            $diff_in_days = $to->diffInDays($from);
            $question->days = $diff_in_days;
            }
        }

       // $sheet->appendRow(array_keys($datas[0]));
        $sheet->row($sheet->getHighestRow(), function ($row) {
            $row->setFontWeight('bold');
        });

         $datasheet = array();
         $datasheet[0]  =   array("NO", "CATEGORY", "TITLE", "TOPIC", "REQUESTOR DATE", "REQUESTOR NAME", "DEPARTMENT", "CLOSING DATE", "TOTAL WORKOUTS DAYS", "RATING", "ADDITIONAL INFORMATION USER", "ADDITIONAL INFORMATION ADMIN");
         $i=1;

        foreach ($questions as $data) {

           // $sheet->appendrow($data);
          $datasheet[$i] = array($i,
                        $data->category_name,
                        $data->question_title,
                        '-',
                        $data->created_at,
                        $data->users,
                        $data->department,
                        $data->closed_at,
                        $data->days,
                        $data->post_rating,
                        $data->additional_information,
                        $data->additional_information_admin
                    );
          
          $i++;
        }

        $sheet->fromArray($datasheet);
    });

    })->export('xlsx');
    }

    public function chart($filter)
    {
        $result = DB::table('questions')
                    ->select('accepted_answer_ids.id as status_id','accepted_answer_ids.status')
		            ->addSelect(DB::raw('COUNT(accepted_answer_id) as total'))
		            ->rightjoin('accepted_answer_ids', function($join) {
			            $join->on('accepted_answer_ids.id', '=', 'questions.accepted_answer_id');
                        })
                    ->whereYEAR('questions.created_at', '=', $filter)
                    ->groupBy('accepted_answer_ids.id')
                    ->get();
                    
        $total = DB::table('questions')
		            ->addSelect(DB::raw('COUNT(id)'))
		            ->from('questions')
                    ->get();
                    
        return response()->json($result);
    }

    public function indexChart(Request $request){
        $data = [];
        $data['filter'] = $request->filter ?: '2020';
        $filter = $data['filter'];
        $data['page'] = $request->page ?: 1;
        $page = $data['page'];
        $data['limit'] = $request->limit ?: 10;
   
        if($data['filter'] == '2020'){
            $questions = Question::orderBy('created_at', 'desc')
                            ->whereYear('created_at', 2020);
        } else{
            $questions = Question::orderBy('created_at', 'desc')
                            ->whereYear('created_at', 2019);
        }
        $questions = $questions->paginate(5);
        $questions->setPath(url("/report/chart/?filter=$filter"));
        $data['filter'] = $filter;
        $data['questions'] = $questions;
        $selected_year = $request->input('year');
        $data['chart_data'] = $this->chart($selected_year);

        if(Session::get('is_admin') === 0 || Session::has('username') === false) {
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'danger');
            $request->session()->flash('notification_msg', 'You cant enter that area!');
            return redirect()->to('/');
        }

        return view('report/report', $data);
    }
}