@extends('layouts.app')
@section('title','Add Client Company')
@section('HeaderAdditionalCodes')
    <style>
        .required{
            color:orangered;
        }
    </style>
@endsection
@section('content')
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
                <div class="fr"><a href="javascript:void(0)"><span class="glyphicon glyphicon-remove-sign remove"></span> </a> </div>
                <div class="input-area"><label for="person_name" class="person_name">Client Contact Person <span class="required">*</span></label><input id="name_0" type="text" class="name" name="name_0" autofocus=""></div>
                <div class="input-area"><label for="person_name" class="person_name">Client Designation <span class="required">*</span></label><input id="designation_0" type="text" class="designation" name="designation_0" required="" autofocus=""></div>
                <div class="input-area"><div class="four columns"><label for="date">Client Contact Details <span class="required">*</span></label></div>
                <div class="five columns"><input placeholder="Email" type="email" class="input-icon icon-email" id="email_0" name="email_0" required="" autofocus=""></div>
                    <div class="three columns"><input placeholder="Cell Number" type="text" class="input-icon icon-phone" id="phone_0" name="phone_0" required="" autofocus=""></div>
                </div>
            </div>
        </div>
        <div class="input-area">
            <div class="section"><a href="javascript:void(0);" class="fr clientContact">+ Add New Contact Person</a></div>
        </div>
        <div class="input-area {{ $errors->has('remarks') ? ' has-error' : '' }}">
            <label for="details" >Remarks</label>
            <textarea id="remarks"  name="remarks" >{{ old('remarks') }}</textarea>
            @if ($errors->has('description'))
                <span class="help-block">
                            <strong>{{ $errors->first('remarks') }}</strong>
                        </span>
            @endif
        </div>
        <div class="row text-center input-area">
            <button type="submit">
                Create Company
            </button>
            <a href="{{ route('clientCompanies.index') }}" class="big-btn">Back</a>
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
                        '<div class="fr"><a href="javascript:void(0)"><span class="glyphicon glyphicon-remove-sign remove"></span> </a> </div>'+
                        '<div class="input-area">'+
                        '<label for="person_name" class="person_name">Client Contact Person <span class="required">*</span></label><input id="name_'+counter+'" type="text" class="name" name="name_'+counter+'" autofocus>'+
                        '</div>'+
                        '<div class="input-area">'+
                        '<label for="person_name" class="person_name">Client Designation <span class="required">*</span></label><input id="designation_'+counter+'" type="text" class="designation" name="designation_'+counter+'" required autofocus>'+
                        '</div>'+
                        '<div class="input-area">'+
                        '<div class="four columns"><label for="date">Client Contact Details <span class="required">*</span></label></div>'+
                        '<div class="five columns"><input placeholder="Email" type="email" class="input-icon icon-email" id="email_'+counter+'" name="email_'+counter+'" required autofocus></div>'+
                        '<div class="three columns"><input placeholder="Cell Number" type="text" class="input-icon icon-phone" id="phone_'+counter+'" name="phone_'+counter+'" required autofocus/></div>'+
                        '</div>'+
                        '</div>';
                $("#clients").append(client_div);
                counter++;
            });

        });
    </script>
@endsection