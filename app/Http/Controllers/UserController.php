<?php

namespace app\Http\Controllers;

use app\User;
use app\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;

class UserController extends Controller
{
	public function __construct(){

    }

    public function approve($id, Request $request){
        DB::beginTransaction();

        try {
            DB::update("
                UPDATE users
                SET is_approved = 'active'
                WHERE id = ?
            ", [$id]);
            DB::commit();
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'success');
            $request->session()->flash('notification_msg', 'You have Approved an user!');

        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'danger');
            $request->session()->flash('notification_msg', 'Uh oh, something went wrong while declining answer.');
        }

        return redirect()->to(url()->previous().'#'. $id);
    }

    public function reject($id, Request $request){
        DB::beginTransaction();

        try {
            DB::update("
                UPDATE users
                SET is_approved = 'reject'
                WHERE id = ?
            ", [$id]);
            DB::commit();
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'danger');
            $request->session()->flash('notification_msg', 'You have reject an user!');

        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'danger');
            $request->session()->flash('notification_msg', 'Uh oh, something went wrong while declining answer.');
        }

        return redirect()->to(url()->previous().'#'. $id);
    }

    public function inactive($id, Request $request){
        DB::beginTransaction();

        try {
            DB::update("
                UPDATE users
                SET is_approved = 'inactive'
                WHERE id = ?
            ", [$id]);
            DB::commit();
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'danger');
            $request->session()->flash('notification_msg', 'You have Inactive an user!');

        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'danger');
            $request->session()->flash('notification_msg', 'Uh oh, something went wrong while declining answer.');
        }

        return redirect()->to(url()->previous().'#'. $id);
    }


    public function userform(Request $request)
    {   
        if(Session::get('is_admin') === 0 || Session::has('username') === false) {
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'danger');
            $request->session()->flash('notification_msg', 'You cant enter that area!');
            return redirect()->to('/');
        }
        $data = [];
        $users = DB::select("
        SELECT * FROM users
        ");
        $data['users'] = $users;

        foreach ($data['users'] as $user){
        $departments = DB::select("
                    SELECT 
                      d.department_name
                    FROM 
                      departments d, users u
                    WHERE
                      u.id = ? AND
                      d.department_id = u.department_id
                ", [$user->id]);

            $user->department= $departments[0]->department_name;
        }

        return view('admin.users', $data);
    }

  

	public function validateSignup(Request $request){
        $this->validate($request, [
            'username' => 'required|min:5|alpha_dash|unique:users',
            'password' => 'required|min:8|same:password2',
            'email' => 'required|email|unique:users',
        ]);

        $user = new User();

        $user->username = $request->username;
        $user->password = sha1($request->password);
        $user->email = $request->email;
        $user->department_id = $request->department;
        $user->is_approved = 'sending';

        $user->save();

        $request->session()->flash('notification', TRUE);
        $request->session()->flash('notification_type', 'info');
        $request->session()->flash('notification_msg', 'Successfully signed up! wait until your account to be appoved by admin');

        return redirect()->back();
    }

    public function authLogin(Request $request){
	    $users = DB::select('SELECT * FROM users WHERE username=? AND password=?', [$request->username, sha1($request->password)]);
	    if(count($users) === 1){
	        $request->session()->regenerateToken();
	        $request->session()->put('username', $users[0]->username);
            $request->session()->put('email', $users[0]->email);
            $request->session()->put('is_approved', $users[0]->is_approved);
	        $request->session()->put('is_admin', $users[0]->is_admin);
	        $request->session()->put('id', $users[0]->id);
//
//        $user = array(
//            'username' => Input::get('username'),
//            'password' => sha1(Input::get('password')),
//        );
//        if(Auth::attempt($user)){
//	        $request->session()->regenerateToken();
//	        $request->session()->put('username', $user->username);
	    } else {
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'danger');
            $request->session()->flash('notification_msg', 'Invalid username/password');
        }

        return redirect()->back();
    }
    public function logout(Request $request){
        $request->session()->flush();

        return redirect()->to('/');
    }

    public function changePassword(Request $request){
        $user = User::where('password', sha1($request->old_password))
                    ->where('id', $request->user_id)
                    ->first();
        if($user === null){
            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'danger');
            $request->session()->flash('notification_msg', 'The password you provided is incorrect.');
        } else {
            $user->password = sha1($request->new_password);
            $user->save();

            $request->session()->flash('notification', TRUE);
            $request->session()->flash('notification_type', 'success');
            $request->session()->flash('notification_msg', 'Your password is successfully changed! Try not to forget it!');
        }

        return redirect()->back();
    }

    public function showPath(Request $request){
		$uri = $request->path();
		echo '<br> URI: '. $uri;

		$url = $request->url();
		echo '<br>';
		echo 'URL: '. $url;

		$method = $request->method();
		echo '<br>';
		echo 'Method: '. $method;

    }
    
    public function userEditForm($id ,Request $request){
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

        $users = DB::select("
        SELECT * FROM users
        ");
  
        $data['users'] = DB::select("
            SELECT 
              u.*
            FROM users u
            WHERE
              u.id = ?
            ", [$id])[0];
  
        return view('admin.edit', $data);
    }
    public function userEdit($id, Request $request){
        DB::beginTransaction();
    
        try {
            DB::table('users')->where('id',$request->id)->update([
                'username' => $request->username,
                'email' => $request->email,
                'department_id' => $request->department
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
    
        return redirect()->to('/users');
    }

    public function destroy($id, Request $request)
    {
        DB::beginTransaction();

        try {  
            DB::table('users')->where('id',$request->id)->update([
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

        return redirect()->to('/');
    }
}
