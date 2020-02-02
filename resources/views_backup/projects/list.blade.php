@extends('layouts.app')

@section('title','List of projects')

@section('content')
    <div class="row">
        <div class="col-sm-8">
        </div>
        <div class="col-sm-4">
            <a class="big-btn fr" href="{{ route('projects.create') }}">Add New Project</a>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-xs-12">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($projects as $project)
                        <tr>
                            <td>
                                <a href="{{ route('projects.show', ['id' => $project->id]) }}">{{ $project->name }}</a>
                            </td>
                            <td>
                                <a class="action-btn" href="{{ route('projects.edit', ['id' => $project->id]) }}">Edit</a>
                                <a class="action-btn delete" id="{{$project->id}}" href="#">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
        </div>
        <div class="col-sm-6">
            @if(isset($projects))
                @if(isset($q))
                    {{ $projects->appends(['q'=>$q])->links() }}
                @else
                    {{ $projects->links() }}
                @endif
            @endif
        </div>
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
                var url = "{{ route('projects.confirmDeleteAjax','') }}"+'/'+row_id;
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

