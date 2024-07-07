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
class ProjectController extends Controller
{
    public function index(Request $request)
    {  

        if ($request->has('search')){

            $all_projects = Project::where('name', 'LIKE', '%' . $request->search . '%')->paginate(10);
        }else{

            $all_projects= Project::orderBy('id','desc')->paginate(10);
        } 
        return view('projects.index',compact('all_projects'));

    }
    public function create()
    {
        return view('projects.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:projects,name',
           
        ]);
       
        if($request->is_active){
            $active='1';
        }else{
            $active='0';
        }
       
        $project = Project::create(['name' => $request->name,'is_active'=> $active]);

        

        return redirect()->route('projects')
            ->with('success', 'Project created successfully.');
    }
    public function edit($id)
    {
       $project=Project::find($id);

        return view('projects.edit', compact('project'));
    }

    public function update(Request $request,Project $project)
    {
        $request->validate([
            'name' => 'required|unique:projects,name,'.$project->id,
           
        ]);
       
        if($request->is_active){
            $active='1';
        }else{
            $active='0';
        }
       
        $project->update(['name' => $request->name,'is_active'=> $active]);

        

        return redirect()->route('sections')
            ->with('success', 'Project updated successfully.');
    }
    public function delete(Project $project)
    {
        $project->delete();

        return redirect()->route('projects')
            ->with('success', 'Project deleted successfully.');
    }
}