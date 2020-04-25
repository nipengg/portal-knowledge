<?php

namespace app\Http\Controllers;

use app\Theme;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;

class ThemeController extends Controller
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

        $themes = DB::select("
                    SELECT * FROM themes
            ");
        $data['themes'] = $themes;

        return view('theme.index', $data);
    }

    public function active($id, Request $request){
        DB::beginTransaction();

        try {
            DB::update("
                UPDATE themes
                SET is_active = 1
                WHERE id = ?
            ", [$id]);
            DB::commit();
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'success');
            $request->session()->flash('notification_msg', 'You have Active an Theme!');

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
                UPDATE themes
                SET is_active = 0
                WHERE id = ?
            ", [$id]);
            DB::commit();
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'danger');
            $request->session()->flash('notification_msg', 'You have inactive an Theme!');

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

        return view('theme.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // try {
            $theme = new Theme();
            $theme->theme = Input::get('theme');
            $theme->is_active = 0;
            $theme->save();

        DB::commit();

            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'success');
            $request->session()->flash('notification_msg', 'Success!');

        // } catch (\Exception $e) {
        //     DB::rollback();
        //     // something went wrong
        //     $request->session()->flash('notification', TRUE);
        //     $request->session()->flash('notification_type', 'danger');
        //     $request->session()->flash('notification_msg', 'Uh oh, something went wrong...');
        // }

        // return redirect()->to(url()->previous());
        return redirect()->to('themes');
    }

    /**
     * Display the specified resource.
     *
     * @param  \app\Theme  $theme
     * @return \Illuminate\Http\Response
     */
    public function show(Theme $theme)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \app\Theme  $theme
     * @return \Illuminate\Http\Response
     */
    public function edit(Theme $theme)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \app\Theme  $theme
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Theme $theme)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \app\Theme  $theme
     * @return \Illuminate\Http\Response
     */
    public function destroy(Theme $theme)
    {
        //
    }
}
