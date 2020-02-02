@extends('layouts.app')
@section('title','Edit Client Company')
@section('HeaderAdditionalCodes')
    <style>
        .required{
            color:orangered;
        }
    </style>
@endsection
@section('content')
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
                        <div id="client" class="client input-area">
                            <div class="fr">
                                @if($contact->meetings->count() > 0)
                                    <small style="color: #adadad; display: block; float: left; padding-right: 10px;">cannot delete, has {{ $contact->meetings->count() }} meetings</small>
                                @endif
                                    <a href="javascript:void(0)"><span title class="glyphicon glyphicon-remove-sign {{ $contact->meetings->count() > 0 ? "" : "remove" }}" style="{{ $contact->meetings->count() > 0 ? "color: #adadad;" : "" }}"></span></a> </div>
                            <div class="input-area">
                            <label for="person_name" class="person_name">Client Contact Person <span class="required">*</span></label><input id="name_{{$index}}" type="text" class="name" name="name_{{$index}}" value="{{$contact->name}}" autofocus=""></div>
                            <div class="input-area"><label for="person_name" class="person_name">Client Designation <span class="required">*</span></label><input id="designation_{{$index}}" type="text" class="designation" name="designation_{{$index}}" value="{{$contact->designation}}" required="" autofocus=""></div>
                            <div class="input-area">
                                <div class="four columns"><label for="date">Client Contact Details <span class="required">*</span></label></div>
                                <div class="five columns"><input placeholder="Email" type="email" class="input-icon icon-email" id="email_{{$index}}" name="email_{{$index}}" value="{{$contact->email}}" required="" autofocus=""></div>
                                <div class="three columns"><input placeholder="Cell Number" type="text" class="input-icon icon-phone" id="phone_{{$index}}" name="phone_{{$index}}" value="{{$contact->phone}}" required="" autofocus=""></div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="input-area">
                    <div class="section"><a href="javascript:void(0);" class="fr clientContact">+ Add New Contact Person</a></div>
                </div>
                <br/><br/>
                <div class="input-area {{ $errors->has('remarks') ? ' has-error' : '' }}">
                    <label for="remarks" >Remarks</label>
                    <textarea id="remarks" name="remarks">{!! empty(old('remarks')) ? $clientCompany->remarks : old('remarks') !!}</textarea>

                    @if ($errors->has('remarks'))
                        <span class="help-block">
                                <strong>{{ $errors->first('remarks') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="input-area">
                    <div class="row text-center">
                            <button type="submit" class="big-btn">
                                Update
                            </button>
                            <a href="{{ route('clientCompanies.index') }}" class="big-btn">Back</a>
                    </div>
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