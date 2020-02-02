@extends('layouts.app')

@section('content')
	<h1>User List</h1>

<a class="big-btn fr nmr" href="{{ route('users.create') }}">Create User</a>

            <div class="table-content">
                <table>
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>Line Manager</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach ($users as $user)
                        <tr>
                            <td data-toggle="tooltip" title="{{$user->name}}">
                                <a href="{{ route('users.show', ['id' => $user->id]) }}">{{ $user->name }}</a>
                            </td>
                            <td data-toggle="tooltip" title="{{ $user->email}}">{{ $user->email}}</td>
                            <td data-toggle="tooltip" title="{{ $user->department->name}}">{{$user->department->name }}</td>
                            <td data-toggle="tooltip" title="{{ empty($user->supervisor) ? "" : $user->supervisor->name . ' (' .  $user->supervisor->role->name . ')' }}">
                                {{ empty($user->supervisor) ? "N/A" : $user->supervisor->name . ' (' .  $user->supervisor->role->name . ')' }}
                                {{--{{ $user->employeesUnderHim->count() }}--}}
                            </td>
                            <td class="action">
                                <a class="action-btn" href="{{ route('users.edit', ['id' => $user->id]) }}">Edit</a>
                                <a class="action-btn delete" id="{{$user->id}}" href="#">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
       
    <div class="input-area">
            {{ $users->links() }}
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
                var url = "{{ route('users.confirmDeleteAjax','') }}"+'/'+row_id;
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