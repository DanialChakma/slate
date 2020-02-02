@extends('layouts.app')

@section('title',$department->name)

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
                        <td>{{ $department->description ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
            <br />
            <a href="{{ route('departments.edit', ['id' => $department->id]) }}" class="big-btn yellowbtn">Edit</a>
            <a href="{{ route('departments.delete', ['id' => $department->id]) }}" class="big-btn redbtn">Delete</a>

            <a href="{{ route('departments.index') }}" class="big-btn">Back</a>
        </div>
    </div>
@endsection