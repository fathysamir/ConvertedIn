<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

use Illuminate\Validation\Rule;
use Image;
use Str;
use Google\Cloud\Firestore\FirestoreClient;
use Google\Cloud\Core\Timestamp;
use Google\Cloud\Firestore\FieldValue;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Exception\Auth\EmailExists;
use Kreait\Firebase\Exception\Auth\AuthError;
use GuzzleHttp\Client;
use File;
use App\Models\Section;

class UserController extends Controller
{
    public function index(Request $request){  

        if ($request->has('search')){

            $all_users = user::where('name', 'LIKE', '%' . $request->search . '%')->orWhere('email', 'LIKE', '%' . $request->search . '%')->paginate(10);
        }else{

            $all_users= user::orderBy('id','desc')->paginate(10);
        } 
        return view('users.index',compact('all_users'));

    }

    public function create(){
  
        $roles=Role::all();
       
        return view('users.create',compact('roles'));
    }
     
    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|confirmed',
            'role' => ['required', Rule::in(Role::pluck('id'))],
            
        ]);
        
        // Find the role
        $role = Role::findOrFail($request->role);
    
        // Prepare data for the User model
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password
        ];
   
        $user = User::create($userData);
    
        // Assign role to the user
        $user->assignRole($role->id);

        // Redirect with success message
        return redirect()->route('users')
            ->with('success', 'User created successfully.');
    }
    
    public function edit($id){
        $user = User::findOrFail($id);
        $user->roles;
       
        $roles=Role::all();
        
        
        return view('users.edit',compact('roles','user'));
    }

    public function update(Request $request, User $user){
        
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,'.$user->id,
           
            'role' => ['required',Rule::in(Role::pluck('id'))],
        ]);
        $role = Role::find($request->role);
        $data['name']=$request->name;
        $data['email']=$request->email;
       
        
       
        $user->update($data);
        $user->syncRoles([$role->id]);
        return redirect()->route('users')
        ->with('success', 'User updated successfully.');
    }

    public function delete($id){
        $user = User::findOrFail($id);
        
        $user->tokens->each(function ($token) {
            $token->delete();
        });
        User::where('id', $id)->delete();
        return redirect('/users');
    }
}