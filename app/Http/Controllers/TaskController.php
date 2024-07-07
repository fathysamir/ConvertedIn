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
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Validation\Rule;
use Image;
use Str;
use DateTime;
use DateTimeZone;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Exception\Auth\EmailExists;
use Kreait\Firebase\Exception\Auth\AuthError;
use GuzzleHttp\Client;
use File;

use Google\Cloud\Core\Timestamp;
class TaskController extends Controller
{
    public function index(Request $request)
    {  
        
        if ($request->has('search')){

            $all_tasks = Task::where('title', 'LIKE', '%' . $request->search . '%')->orWhere('description', 'LIKE', '%' . $request->search . '%')->paginate(10);
        }else{

            $all_tasks= Task::orderBy('id','desc')->paginate(10);
        } 
        return view('tasks.index',compact('all_tasks'));

    }
    public function create()
    {
        $projects=Project::where('is_active',1)->get();
        $users=User::whereHas('roles',function($q){
            $q->where('name','user');
        })->get();
        $admins=User::whereHas('roles',function($q){
            $q->where('name','admin');
        })->get();
        return view('tasks.create',compact('projects','users','admins'));
    }
    public function store(Request $request)
    {   
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'status'=>'required',
            'project' => ['required', Rule::exists('projects', 'id')],
            'admin' => ['required', Rule::exists('users', 'id')],
            'users' => ['required', 'array'],
            'users.*' => ['required', Rule::exists('users', 'id')],
        ]);
       
        if($request->is_active){
            $active='1';
        }else{
            $active='0';
        }
       
        $task = Task::create(['title' => $request->title,
                              'is_active'=> $active,
                              'description'=>$request->description,
                              'status'=>$request->status,
                              'project_id'=>$request->project,
                              'assigned_by_id'=>$request->admin]);
        $task->users()->attach($request->users);
        

        return redirect()->route('tasks')
            ->with('success', 'Task created successfully.');
    }
    public function edit($id)
    {
        $task=Task::find($id);
        $projects=Project::where('is_active',1)->get();
        $users=User::whereHas('roles',function($q){
            $q->where('name','user');
        })->get();
        $admins=User::whereHas('roles',function($q){
            $q->where('name','admin');
        })->get();
        return view('tasks.edit', compact('task','projects','users','admins'));
    }

    public function update(Request $request,Task $task)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'status' => 'required',
            'project' => ['required', Rule::exists('projects', 'id')],
            'admin' => ['required', Rule::exists('users', 'id')],
            'users' => ['required', 'array'],
            'users.*' => ['required', Rule::exists('users', 'id')],
        ]);
    
        // Find the task by ID
        
    
        // Determine the is_active status
        $active = $request->is_active ? '1' : '0';
    
        // Update the task with the new data
        $task->update([
            'title' => $request->title,
            'is_active' => $active,
            'description' => $request->description,
            'status' => $request->status,
            'project_id' => $request->project,
            'assigned_by_id' => $request->admin,
        ]);
    
        // Sync the users to the task
        $task->users()->sync($request->users);
    
        // Redirect back with a success message
        return redirect()->route('tasks')
            ->with('success', 'Task updated successfully.');
    }
    public function delete(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks')
            ->with('success', 'Task deleted successfully.');
    }
}