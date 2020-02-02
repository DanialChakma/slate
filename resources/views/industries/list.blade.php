@extends('layouts.app')

@php
$title = "List of Industry";
@endphp

@section('title',$title)

@section('content')
   <h1>{{ $title }}</h1>
            <a class="big-btn fr nmr" href="{{ route('industries.create') }}">Add New Industry</a>
     
    <div class="row">
      
            @if( isset($industries) && count($industries) > 0 )
            <div class="table-content">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($industries as $row)
                        <tr>
                            <td>
                                <a href="{{ route('industries.show', ['id' => $row->id]) }}">{{ $row->name }}</a></td>
                            <td>
                                <a class="action-btn" href="{{ route('industries.edit', ['id' => $row->id]) }}">Edit</a>
                                <a class="action-btn delete" id="{{$row->id}}" href="#">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @elseif( isset($msg) )
                <div class="text-info">{{$msg}}</div>
            @endif
       
    </div>

    <div class="row">
      
        <div class="col-sm-6">
            @if(isset($industries))
                @if(isset($q))
                    {{ $industries->appends(['q'=>$q])->links() }}
                @else
                    {{ $industries->links() }}
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
            var url = "{{ route('industries.confirmDeleteAjax','') }}"+'/'+row_id;
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

            console.log("Delete operation."+row_id);
        }

        $(document).on('click','a.delete',function(event){
            event.preventDefault();
            var deleteId = $(this).attr('id');
            var ModalContent = "Are you sure to delete?"+'<input type="hidden" id="row_id" value="'+deleteId+'"/>';
            JconfirmDefault("Delete Confirmation",ModalContent,delete_yes_function);
            console.log(deleteId);
        });


    });
</script>

@endsection