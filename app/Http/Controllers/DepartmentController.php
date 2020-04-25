<?php

namespace app\Http\Controllers;

use app\Department;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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

        return view('department.index', $data);
    }

    public function active($id, Request $request){
        DB::beginTransaction();

        try {
            DB::update("
                UPDATE departments
                SET is_active = 1
                WHERE department_id = ?
            ", [$id]);
            DB::commit();
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'success');
            $request->session()->flash('notification_msg', 'You have Active an department!');

        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'danger');
            $request->session()->flash('notification_msg', 'Uh oh, something went wrong');
        }

        return redirect()->to(url()->previous().'#'. $id);
    }

    public function inactive($id, Request $request){
        DB::beginTransaction();

        try {
            DB::update("
                UPDATE departments
                SET is_active = 0
                WHERE department_id = ?
            ", [$id]);
            DB::commit();
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'danger');
            $request->session()->flash('notification_msg', 'You have inactive an department!');

        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'danger');
            $request->session()->flash('notification_msg', 'Uh oh, something went wrong');
        }

        return redirect()->to(url()->previous().'#'. $id);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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

        return view('department.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $department = new Department();
            $department->department_name = Input::get('department');
            $department->description = Input::get('description');
            $department->is_active = 0;
            $department->save();

        DB::commit();

            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'success');
            $request->session()->flash('notification_msg', 'Your department is added!');

        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'danger');
            $request->session()->flash('notification_msg', 'Uh oh, something went wrong...');
        }

        // return redirect()->to(url()->previous());
        return redirect()->to('departments');
    }

    /**
     * Display the specified resource.
     *
     * @param  \app\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \app\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function edit($id ,Request $request){
        if(Session::get('is_admin') === 0 || Session::has('username') === false) {
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'danger');
            $request->session()->flash('notification_msg', 'You cant enter that area!');
            return redirect()->to('/');
        }
        $data = [];

        $departments = DB::select("
        SELECT * FROM departments
        ");
  
        $data['departments'] = DB::select("
            SELECT 
              d.*
            FROM departments d
            WHERE
              d.department_id = ?
            ", [$id])[0];
  
        return view('department.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \app\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        DB::beginTransaction();
    
        try {
            DB::table('departments')->where('department_id',$request->id)->update([
                'department_name' => $request->name,
                'description' => $request->description,
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
    
        return redirect()->to('/departments');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \app\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy(Department $department)
    {
        //
    }
}
