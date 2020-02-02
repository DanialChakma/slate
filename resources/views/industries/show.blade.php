@extends('layouts.app')

@section('title',$industry->name)
@section('HeaderAdditionalCodes')
    <script>
        var parentClasses = ["a_industry"];
    </script>
@endsection
@section('content')

                <h1>Industry Information</h1>
                <div class="section company-details list-items">
                   <ul> <li><strong>Name</strong>{{ $industry->name }}</li></ul>
                </div>
                 <div class="button-area">
                    <a href="{{ route('industries.edit', ['id' => $industry->id]) }}" class="big-btn yellowbtn">Edit</a>
                    <a href="{{ route('industries.delete', ['id' => $industry->id]) }}" class="big-btn redbtn">Delete</a>
                    <a href="{{ route('industries') }}" class="big-btn">Back</a>
					</div>
           
@endsection