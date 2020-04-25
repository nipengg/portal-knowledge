<?php

namespace app\Http\Controllers;

use app\category;
use Illuminate\Http\Request;
use app\User;
use app\Department;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;

class CategoryController extends Controller
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
        $categories = DB::select("
        SELECT * FROM categories
        ORDER BY id, category_name, is_active
        ");
        $data['categories'] = $categories;

        $department = DB::select("
                    SELECT * FROM departments
                    ORDER BY department_name
            ");
        $data['department'] = $department;

        return view('category.index', $data);
    }


    public function active($id, Request $request){
        DB::beginTransaction();

        try {
            DB::update("
                UPDATE categories
                SET is_active = 1
                WHERE id = ?
            ", [$id]);
            DB::commit();
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'success');
            $request->session()->flash('notification_msg', 'You have Active an category!');

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
                UPDATE categories
                SET is_active = 0
                WHERE id = ?
            ", [$id]);
            DB::commit();
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'danger');
            $request->session()->flash('notification_msg', 'You have inactive an category!');

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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \app\category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \app\category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit($id ,Request $request)
    {
        if(Session::get('is_admin') === 0 || Session::has('username') === false) {
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'danger');
            $request->session()->flash('notification_msg', 'You cant enter that area!');
            return redirect()->to('/');
        }
        $data = [];
        $departments = DB::select("
        SELECT * FROM departments
        WHERE is_active = 1
        ");
        $data['departments'] = $departments;

        $categories = DB::select("
        SELECT * FROM categories
        ");
  
        $data['categories'] = DB::select("
            SELECT 
              c.*
            FROM categories c
            WHERE
              c.id = ?
            ", [$id])[0];
  
        return view('category.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \app\category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \app\category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        DB::beginTransaction();

        try {  
            DB::table('categories')->where('id',$request->id)->update([
                'is_active' => 0
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

}
