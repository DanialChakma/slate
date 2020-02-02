@extends('layouts.app')

@section('title','List of Client Company')
<style>
    .table td {
        overflow: hidden; /* this is what fixes the expansion */
        text-overflow: ellipsis; /* not supported in all browsers, but I accepted the tradeoff */
        white-space: nowrap;
    }
</style>
@section('content')
    <div class="row">
        <div class="col-sm-8">
        </div> 
        <div class="col-sm-4">
            <a class="big-btn" href="{{ route('clientCompanies.create') }}">Add New Company</a>
        </div>
   
    </div>
    <br/>
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
                    @foreach ($clientCompanies as $row)
                        <tr>
                            <td>
                                <a href="{{ route('clientCompanies.show', ['id' => $row->id]) }}">
                                    {{ $row->company_name }}
                                </a>
                            </td>
                            <td>
                                <a  class="action-btn" href="{{ route('clientCompanies.edit', ['id' => $row->id]) }}">
                                    Edit
                                </a>
                                <a  class="action-btn delete" id="{{$row->id}}" href="#">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
         <div class="col-xs-6"></div>
        <div class="col-xs-6">
            @if(isset($clientCompanies))
                @if(isset($q))
                    {{ $clientCompanies->appends(['q'=>$q])->links() }}
                @else
                    {{ $clientCompanies->links() }}
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
                var url = "{{ route('clientCompanies.confirmDeleteAjax','') }}"+'/'+row_id;
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
