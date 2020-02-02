@extends('layouts.app')
@section('HeaderAdditionalCodes')

@php
$title = "Edit Client Company";
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
<form  method="POST" action="{{ route('clientCompanies.update', ['id' => $clientCompany->id]) }}">
                {{ csrf_field() }}
                <input name="id" type="hidden" value="{{ $clientCompany->id }}" />
                <div class="input-area {{ $errors->has('industry_id') ? ' has-error' : '' }}">
                    <label for="industry_id" >Select Industry <span class="required">*</span></label>
                    <select  name="industry_id" id="industry_id">
                        @foreach($industries as $industry)
                            @if( $industry->id == $clientCompany->industry_id )
                               <option selected value="{{$industry->id}}">{{$industry->name}}</option>
                            @else
                                <option value="{{$industry->id}}">{{$industry->name}}</option>
                            @endif
                        @endforeach
                    </select>
                    @if ($errors->has('industry_id'))
                        <span class="help-block">
                            <strong>{{ $errors->first('industry_id') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="input-area {{ $errors->has('company_name') ? ' has-error' : '' }}">
                    <label for="name" >Company Name <span class="required">*</span></label>
                    <input id="company_name" type="text" name="company_name" value="{{ empty(old('company_name')) ? $clientCompany->company_name : old('company_name') }}" required autofocus>
                    @if ($errors->has('company_name'))
                        <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>
                <div id="clients" class="clients input-area">
                    @foreach($clientCompany->clientCompanyContactPersons as $index=>$contact)
                        <input type="hidden" name="contact_persons_old[]" value="{{$contact->id}}">
                        <div id="client" class="client input-area">
                            <div class="fr">
                                @if($contact->meetings->count() > 0)
                                    <small style="color: #adadad; display: block; float: left; padding-right: 10px;">cannot delete, has {{ $contact->meetings->count() }} meetings</small>
                                @endif
                                    <a href="javascript:void(0)" class="removebtn"><span class="{{ $contact->meetings->count() > 0 ? "" : "remove" }}" style="{{ $contact->meetings->count() > 0 ? "color: #adadad;" : "" }}">remove</span></a> </div>
                            <div class="input-area">
                            <input type="hidden" name="contact_persons[]" value="{{$contact->id}}">
                            <label for="person_name" class="person_name">Client Contact Person <span class="required">*</span></label><input id="name_{{$index}}" type="text" class="name" name="name[{{$contact->id}}]" value="{{$contact->name}}" autofocus=""></div>
                            <div class="input-area"><label for="person_name" class="person_name">Client Designation <span class="required">*</span></label><input id="designation_{{$index}}" type="text" class="designation" name="designation[{{$contact->id}}]" value="{{$contact->designation}}" required="" autofocus=""></div>
                            <div class="input-area">
                                <div class="four columns"><label for="date">Client Contact Details <span class="required">*</span></label></div>
                                <div ><input placeholder="Email" type="email" class="input-icon icon-email" id="email_{{$index}}" name="email[{{$contact->id}}]" value="{{$contact->email}}" required="" autofocus=""></div>
                                <div ><input placeholder="Cell Number" type="text" class="input-icon icon-phone" id="phone_{{$index}}" name="phone[{{$contact->id}}]" value="{{$contact->phone}}" required="" autofocus=""></div>
                            </div>
                        </div>
                    @endforeach
                </div>
               
                    <div class="section"><a href="javascript:void(0);" class="fr clientContact add-btn">+ Add New Contact Person</a></div>
              
                <div class="input-area {{ $errors->has('remarks') ? ' has-error' : '' }}">
                    <label for="remarks" >Remarks</label>
                    <textarea id="remarks" name="remarks">{!! empty(old('remarks')) ? $clientCompany->remarks : old('remarks') !!}</textarea>

                    @if ($errors->has('remarks'))
                        <span class="help-block">
                                <strong>{{ $errors->first('remarks') }}</strong>
                        </span>
                    @endif
                </div>
              <div class="button-area">
			    <a href="{{ route('clientCompanies') }}" class="big-btn">Back</a>
                            <button type="submit" class="big-btn">
                                Update
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

            var counter = "{{$clientCompany->clientCompanyContactPersons()->count()}}";

            $('.clientContact').on('click',function(){
                var client_div ='<div id="client" class="client input-area">'+
                        '<div class="fr"><a href="javascript:void(0)" class="removebtn"><span class="remove">remove</span> </a> </div>'+
                        '<div class="input-area">'+
                        '<label for="person_name" class="person_name">Client Contact Person <span class="required">*</span></label><input id="name_'+counter+'" type="text" class="name" name="name_new[]" autofocus>'+
                        '</div>'+
                        '<div class="input-area">'+
                        '<label for="person_name" class="person_name">Client Designation <span class="required">*</span></label><input id="designation_'+counter+'" type="text" class="designation" name="designation_new[]" required autofocus>'+
                        '</div>'+
                        '<div class="input-area">'+
                        '<div class="four columns"><label for="date">Client Contact Details <span class="required">*</span></label></div>'+
                        '<div ><input placeholder="Email" type="email" class="input-icon icon-email" id="email_'+counter+'" name="email_new[]" required autofocus></div>'+
                        '<div ><input placeholder="Cell Number" type="text" class="input-icon icon-phone" id="phone_'+counter+'" name="phone_new[]" required autofocus/></div>'+
                        '</div>'+
                        '</div>';
                $("#clients").append(client_div);
                counter++;
            });
        });
</script>
@endsection