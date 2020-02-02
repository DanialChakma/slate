@extends('layouts.app')
@section('HeaderAdditionalCodes')

@php
$title = "Add Client Company";
@endphp

@section('title',$title)

@section('HeaderAdditionalCodes')
    <script>
        var parentClasses = ["a_client_management"];
    </script>
@endsection
@endsection
@section('content')
<h1>{{ $title }}</h1>
<form method="POST" action="{{ route('clientCompanies.store') }}">
        {{ csrf_field() }}
        <div class="input-area {{ $errors->has('company_name') ? ' has-error' : '' }}">
            <label for="company_name" >Company Name <span class="required">*</span></label>
            <input id="company_name" type="text"  name="company_name" value="{{ old('company_name') }}"  autofocus>
            @if ($errors->has('company_name'))
                <span class="help-block">
                            <strong>{{ $errors->first('company_name') }}</strong>
                        </span>
            @endif
        </div>
        <div class="input-area {{ $errors->has('industry_id') ? ' has-error' : '' }}">
            <label for="industry_id">Industry <span class="required">*</span></label>
            <select  name="industry_id" id="industry_id">
                @foreach($industries as $industry)
                    <option value="{{$industry->id}}">{{$industry->name}}</option>
                @endforeach
            </select>
            @if ($errors->has('industry_id'))
                <span class="help-block">
                <strong>{{ $errors->first('industry_id') }}</strong>
            </span>
            @endif
        </div>
        <div id="clients" class="clients input-area">
            <div id="client" class="client input-area">
                <div class="fr"><a href="javascript:void(0)" class="removebtn"><span class="remove">remove</span> </a> </div>
                <div class="input-area"><label for="person_name" class="person_name">Client Contact Person <span class="required">*</span></label><input id="name_0" type="text" class="name" name="name[]" autofocus=""></div>
                <div class="input-area"><label for="person_name" class="person_name">Client Designation <span class="required">*</span></label><input id="designation_0" type="text" class="designation" name="designation[]" required="" autofocus=""></div>
                <div class="input-area"><div class="four columns"><label for="date">Client Contact Details <span class="required">*</span></label></div>
                <div ><input placeholder="Email" type="email" class="input-icon icon-email" id="email_0" name="email[]" required="" autofocus=""></div>
                <div ><input placeholder="Cell Number" type="text" class="input-icon icon-phone" id="phone_0" name="phone[]" required="" autofocus=""></div>
                </div>
            </div>
        </div>
      
            <div class="section"><a href="javascript:void(0);" class="fr clientContact add-btn">+ Add New Contact Person</a></div>
      
        <div class="input-area {{ $errors->has('remarks') ? ' has-error' : '' }}">
            <label for="details" >Remarks</label>
            <textarea id="remarks"  name="remarks" >{{ old('remarks') }}</textarea>
            @if ($errors->has('description'))
                <span class="help-block">
                            <strong>{{ $errors->first('remarks') }}</strong>
                        </span>
            @endif
        </div>
      <div class="button-area">
	    <a href="{{ route('clientCompanies') }}" class="big-btn">Back</a>
            <button type="submit">
                Create Company
            </button>
            
        </div>
</form>
@endsection
@section('FooterAdditionalCodes')
    <script type="text/javascript">
        $(document).ready(function(){

            $(document).on('click','.remove',function(event){
                $(this).closest('.client').remove();
            });

            var counter = 0;

            $('.clientContact').on('click',function(){
                var client_div ='<div id="client" class="client input-area">'+
                        '<div class="fr"><a href="javascript:void(0)"  class="removebtn"><span class="remove">remove</span> </a> </div>'+
                        '<div class="input-area">'+
                        '<label for="person_name" class="person_name">Client Contact Person <span class="required">*</span></label><input id="name_'+counter+'" type="text" class="name" name="name[]" >'+
                        '</div>'+
                        '<div class="input-area">'+
                        '<label for="person_name" class="person_name">Client Designation <span class="required">*</span></label><input id="designation_'+counter+'" type="text" class="designation" name="designation[]" required >'+
                        '</div>'+
                        '<div class="input-area">'+
                        '<div class="four columns"><label for="date">Client Contact Details <span class="required">*</span></label></div>'+
                        '<div ><input placeholder="Email" type="email" class="input-icon icon-email" id="email_'+counter+'" name="email[]" required autofocus></div>'+
                        '<div ><input placeholder="Cell Number" type="text" class="input-icon icon-phone" id="phone_'+counter+'" name="phone[]" required autofocus/></div>'+
                        '</div>'+
                        '</div>';
                $("#clients").append(client_div);
                counter++;
            });

        });
    </script>
@endsection