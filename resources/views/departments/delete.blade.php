@extends('layouts.app')

@section('title', 'Delete Department')
@section('HeaderAdditionalCodes')
    <script>
        var parentClasses = ["a_department"];
    </script>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <td>Department Name: </td>
                        <td>{{ $department->name }}</td>
                    </tr>
                    <tr>
                        <td>Remarks: </td>
                        <td>{{ $department->description }}</td>
                    </tr>
                </table>
            </div>
            <br />
            <form class="form-horizontal" role="form" method="POST" action="{{ route('departments.confirmDelete', ['id' => $department->id]) }}">
                {{ csrf_field() }}

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-1">
                            <button type="submit" class="sbtn">
                                Confirm Delete
                            </button>
                            <a href="{{ route('departments') }}" class="big-btn">Back</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection