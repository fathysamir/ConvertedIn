@extends('layout.app')
@section('title', 'Edit Task')
@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-secondary rounded h-100 p-4">
                <h6 class="mb-4">Edit Task</h6>
                <form action="{{ route('update.task',$task) }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <label for="title" class="col-sm-2 col-form-label">Title</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title',$task->title) }}">
                            @if ($errors->has('title'))
                                <p class="text-error more-info-err" style="color: red;">
                                    {{ $errors->first('title') }}
                                </p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="description" class="col-sm-2 col-form-label">Description</label>
                        <div class="col-sm-10">
                            <textarea class="form-control textarea" id="description" name="description">{{ old('description',$task->description) }}</textarea>
                            @if ($errors->has('description'))
                                <p class="text-error more-info-err" style="color: red;">
                                    {{ $errors->first('description') }}
                                </p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="project" class="col-sm-2 col-form-label">Project</label>
                        <div class="col-sm-10">
                            <select class="form-select" id="project" name="project">
                                <option value="">Select Project</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}" {{ old('project') == $project->id || $task->project_id==$project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('project'))
                                <p class="text-error more-info-err" style="color: red;">
                                    {{ $errors->first('project') }}
                                </p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="status" class="col-sm-2 col-form-label">Status</label>
                        <div class="col-sm-10">
                            <select class="form-select" id="status" name="status">
                                <option value="">Select Status</option>
                                <option value="not started" {{ old('status') == 'not started' || $task->status=='not started' ? 'selected' : '' }}>Not Started</option>
                                <option value="working on it" {{ old('status') == 'working on it' || $task->status=='working on it' ? 'selected' : '' }}>Working On It</option>
                                <option value="stuck" {{ old('status') == 'stuck' || $task->status=='stuck' ? 'selected' : '' }}>Stuck</option>
                                <option value="on hold" {{ old('status') == 'on hold' || $task->status=='on hold' ? 'selected' : '' }}>On Hold</option>
                                <option value="under review" {{ old('status') == 'under review' || $task->status=='under review' ? 'selected' : '' }}>Under Review</option>
                                <option value="rejected" {{ old('status') == 'rejected' || $task->status=='rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="done" {{ old('status') == 'done' || $task->status=='done' ? 'selected' : '' }}>Done</option>
                            </select>
                            @if ($errors->has('status'))
                                <p class="text-error more-info-err" style="color: red;">
                                    {{ $errors->first('status') }}
                                </p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="admin" class="col-sm-2 col-form-label">Admin</label>
                        <div class="col-sm-10">
                            <select class="form-select" id="admin" name="admin">
                                <option value="">Select Admin</option>
                                @foreach ($admins as $admin)
                                    <option value="{{ $admin->id }}" {{ old('admin') == $admin->id || $task->assigned_by_id==$admin->id ? 'selected' : '' }}>
                                        {{ $admin->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('admin'))
                                <p class="text-error more-info-err" style="color: red;">
                                    {{ $errors->first('admin') }}
                                </p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="users" class="col-sm-2 col-form-label">Users</label>
                        <div class="col-sm-10">
                            <select class="form-select" id="users" name="users[]" multiple>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ in_array($user->id, old('users', [])) || in_array($user->id, $task->users->pluck('id')->toArray())  ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('users'))
                                <p class="text-error more-info-err" style="color: red;">
                                    {{ $errors->first('users') }}
                                </p>
                            @endif
                        </div>
                    </div>
                    
                   
                    <div class="row mb-3">
                        <legend class="col-form-label col-sm-2 pt-0">Activation</legend>
                        <div class="col-sm-10">
                            
                                <div class="form-check form-switch">
                                    <input class="form-check-input" name="is_active"  type="checkbox" role="switch"
                                        id="flexSwitchCheckDefault" value="1" @if(old('is_active',$task->is_active)) 
                                        checked 
                                        @endif >
                                    <label class="form-check-label" for="flexSwitchCheckDefault">active</label>
                                </div>
                               
                            
                            
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#users').select2({
                placeholder: "Select users",
                
                allowClear: true
            });
        });
    </script>
@endpush