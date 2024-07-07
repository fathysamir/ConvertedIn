<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    ///////////////login view//////////////////////
    public function login_view()
    {
        return view('auth.login');
    }
    //////////////////Login/////////////////////////
    public function login(Request $request)
    {   
        $validator  =   Validator::make($request->all(), [
               
                'email' => ['required', 'string', 'email'],
                'password' => ['required', 'string'],
               
        ]);
           
        if ($validator->fails()) {
           
            return Redirect::back()->withErrors($validator)->withInput($request->all());
        }
       
        if (Auth::attempt(['email' => request('email'),'password' => request('password')])){
            
            return redirect('/home');
        }else{

            return back()->withErrors(['msg' => 'There is something wrong']);
        }
       
    }
    /////////////////////Logout//////////////////////
    public function logout(Request $request){
        $user = auth()->user();

        if ($user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
        }
    
        Auth::logout();
       

        return redirect('/login');
    }
    /////////////////Home Page///////////////////////////
    public function home(){
        $topUsers = DB::table('users')
        ->join('tasks_users', 'users.id', '=', 'tasks_users.assigned_to_id')
        ->select('users.id','users.name', DB::raw('count(tasks_users.task_id) as task_count'))
        ->groupBy('users.id')
        ->orderBy('task_count', 'desc')
        ->limit(10)
        ->get();
        
        return view('home',compact('topUsers'));
    }

    public function updateTopUsersInHome(){
        $topUsers = DB::table('users')
        ->join('tasks_users', 'users.id', '=', 'tasks_users.assigned_to_id')
        ->select('users.id','users.name', DB::raw('count(tasks_users.task_id) as task_count'))
        ->groupBy('users.id')
        ->orderBy('task_count', 'desc')
        ->limit(10)
        ->get();
        
        return response()->json([
            'success' => true,
            'data' => $topUsers
        ],200);
    }

}