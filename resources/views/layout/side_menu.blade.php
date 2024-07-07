<div class="sidebar pe-4 pb-3">
    <nav class="navbar bg-secondary navbar-dark">
        <a href="{{url('/')}}" class="navbar-brand mx-4 mb-3">
            <h3 class="text-primary"><i class="fa fa-user-edit"></i> N G</h3>
        </a>
        <div class="d-flex align-items-center ms-4 mb-4">
            <div class="position-relative">
                <img class="rounded-circle" src="{{asset('logos/user_logo.png')}}" alt="" style="width: 40px; height: 40px;">
                <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
            </div>
            <div class="ms-3">
                <h6 class="mb-0">{{auth()->user()->name}}</h6>
                <span>{{ucwords(auth()->user()->roles()->pluck('name')->first())}}</span>
            </div>
        </div>
        
        <div class="navbar-nav w-100">
            <a href="{{url('/home')}}" class="nav-item nav-link {{ Request::is('home') ? 'active' : '' }}"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
           
            @can('show users')
            <a href="{{url('/users')}}" class="nav-item nav-link {{ Request::is('users') ? 'active' : '' }}"><i class="fa fa-user me-2"></i>Users</a>
            @endcan
            @can('show projects')
            <a href="{{url('/projects')}}" class="nav-item nav-link {{ Request::is('projects') ? 'active' : '' }}"><i class="fa fa-project-diagram me-2"></i>Projects</a>
            @endcan
            @can('show tasks')
            <a href="{{url('/tasks')}}" class="nav-item nav-link {{ Request::is('tasks') ? 'active' : '' }}"><i class="fa fa-project-diagram me-2"></i>Tasks</a>
            @endcan
           
        </div>
    </nav>
</div>