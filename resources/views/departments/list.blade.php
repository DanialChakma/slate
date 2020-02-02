@extends('layouts.app')

@php
$title = "List of Departments";
@endphp

@section('title',$title)

@section('content')
 <h1>{{ $title }}</h1><div class="top-btn">  <a class="big-btn fr" href="{{ route('departments.create') }}">Add New Department</a></div>
   

    <div class="row">
       
            <div class="table-content">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($departments as $department)
                        <tr>
                            <td>
                                <a href="{{ route('departments.show', ['id' => $department->id]) }}">{{ $department->name }}</a>
                            </td>
                            <td>
                                <a class="action-btn" href="{{ route('departments.edit', ['id' => $department->id]) }}">Edit</a>
                                <a class="action-btn delete" id="{{$department->id}}" href="#">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
       
    </div>
    <div class="row">
     
            {{ $departments->links() }}
   
    </div>
@endsection

@section('FooterAdditionalCodes')
    <script type="text/javascript" src="{{asset('js/JconfirmFunctions.js')}}"></script>
    <script type="text/javascript">

        $(document).ready(function(){

            function close_call_back(){
                window.location.reload(true);
            }

            function delete_yes_function(){
                var row_id = $("#row_id").val();
                var url = "{{ route('departments.confirmDeleteAjax','') }}"+'/'+row_id;
                $.ajax({
                    type: "POST",
                    url: url,
                    async: false,
                    dataType: 'JSON',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if( response.status == "OK" ){
                            JconfirmAlertWithReload('Operation Status.',response.message,close_call_back);
                        }else{
                            JconfirmAlertWithReload('Operation Status.',response.message);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        JconfirmAlertWithReload('Operation Status.','Failed to Delete due to '+textStatus);
                    },
                    processData: false
                });
            }

            $(document).on('click','a.delete',function(event){
                event.preventDefault();
                var deleteId = $(this).attr('id');
                var ModalContent = "Are you sure to delete?"+'<input type="hidden" id="row_id" value="'+deleteId+'"/>';
                JconfirmDefault("Delete Confirmation",ModalContent,delete_yes_function);
            });
        });
    </script>

@endsection