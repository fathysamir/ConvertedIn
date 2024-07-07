@extends('layout.app')
@section('title', 'Home')
@section('content')
    <div class="container-fluid pt-4 px-4">
        
            <div class="row g-4">
                <div class="col-12">
                    <div class="bg-secondary rounded h-100 p-4">
                        <div style="display: flex;justify-content: space-between;">
                            <h6 class="mb-4">Statistics Table</h6>
                            <div style="display: flex;justify-content: space-between; ">
                               
                            </div>
                           
                        </div>
                        @if(!empty($topUsers) && $topUsers->count())
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Tasks Count</th>
                                            
                                            
                                        </tr>
                                    </thead>
                                    @php
                                        $counter = 1; // Get the starting index of the current page
                                    @endphp
                                    <tbody id="statistics-table-body">
                                        @foreach($topUsers as $key => $topUser)
                                        <tr>
                                            <th scope="row">{{$counter++}}</th>
                                            <td>{{ucwords($topUser->name)}}</td>
                                            <td>{{$topUser->task_count}}</td>
                                            
                                        
                                            
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                
                            </div>
                            
                        @else
                            
                                <div class="row vh-100 bg-secondary rounded align-items-center justify-content-center mx-0">
                                    <div class="col-md-6 text-center">
                                        <h3>This is blank page</h3>
                                    </div>
                                </div>
                            
                        @endif
                    </div>
                </div>
            </div>
       
    </div>
@endsection
@push('scripts')
<script>
    setInterval(function() {
        $.ajax({
            url: '/update_top_users', // Replace with the actual endpoint URL
            type: 'GET',
            success: function(response) {
                console.log(response.data);
                $('#statistics-table-body').empty();
                var counter = 1;
                    response.data.forEach(function(user) {
                        var newRow = '<tr>' +
                                        '<th scope="row">' + (counter++) + '</th>' +
                                        '<td>' + user.name + '</td>' +
                                        '<td>' + user.task_count + '</td>' +
                                     '</tr>';
                        $('#statistics-table-body').append(newRow);
                    });
            
            },
            error: function(xhr, status, error) {
                // Handle any errors that occur during the AJAX request
                console.log(xhr.responseText);
            }
        }); //console.log(element.value);
    }, 3000); // 1000 milliseconds = 1 second
</script>
@endpush