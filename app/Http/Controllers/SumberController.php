<?php

namespace app\Http\Controllers;

use app\Sumber;
use Illuminate\Http\Request;
use app\Department;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;

class SumberController extends Controller
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
        $sumbers = DB::select("
        SELECT * FROM sumbers
        ORDER BY id, sumber_name, is_active
        ");
        $data['sumbers'] = $sumbers;

        $department = DB::select("
                    SELECT * FROM departments
                    ORDER BY department_name
            ");
        $data['department'] = $department;

        return view('sumber.index', $data);
    }


    public function active($id, Request $request){
        DB::beginTransaction();

        try {
            DB::update("
                UPDATE sumbers
                SET is_active = 1
                WHERE id = ?
            ", [$id]);
            DB::commit();
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'success');
            $request->session()->flash('notification_msg', 'You have Active an Sumber!');

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
                UPDATE sumbers
                SET is_active = 0
                WHERE id = ?
            ", [$id]);
            DB::commit();
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'danger');
            $request->session()->flash('notification_msg', 'You have inactive an Sumber!');

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

        return view('sumber.create', $data);
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
            $sumber = new Sumber();
            $sumber->sumber_name = Input::get('title');
            $sumber->save();

        DB::commit();

            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'success');
            $request->session()->flash('notification_msg', 'Your article is posted!');

        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'danger');
            $request->session()->flash('notification_msg', 'Uh oh, something went wrong...');
        }

        // return redirect()->to(url()->previous());
        return redirect()->to('sumber');
    }

    /**
     * Display the specified resource.
     *
     * @param  \app\Sumber  $sumber
     * @return \Illuminate\Http\Response
     */
    public function show(Sumber $sumber)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \app\Sumber  $sumber
     * @return \Illuminate\Http\Response
     */
    public function edit(Sumber $sumber)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \app\Sumber  $sumber
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sumber $sumber)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \app\Sumber  $sumber
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sumber $sumber)
    {
        //
    }
}
