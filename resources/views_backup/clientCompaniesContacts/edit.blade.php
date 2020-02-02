@extends('layouts.app')

@section('title','Edit Client Company Contact Person')

@section('content')

            <form  method="POST" action="{{ route('clientCompaniesContacts.update', ['id' => $clientCompanyContactPerson->id]) }}">
                {{ csrf_field() }}

                <input name="id" type="hidden" value="{{ $clientCompanyContactPerson->id }}" />
                <br/>
                <div class="input-area {{ $errors->has('company_name') ? ' has-error' : '' }}">
                    <div class="input-area text-center">
                        <input id="client_company_id" type="hidden"  name="client_company_id" value="{{ $clientCompanyContactPerson->client_company_id }}">
                        <h2>
                            Update Contact Information for <strong> {{ $clientCompanyContactPerson->clientCompany->company_name }}</strong> company.
                        </h2>
                    </div>
                </div>
                <div class="input-area {{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="person_name" class="person_name">Person Name</label>
                    <input id="name" type="text" name="name" value="{{$clientCompanyContactPerson->name}}" required autofocus>
                </div>
                <div class="input-area {{ $errors->has('designation') ? ' has-error' : '' }}">
                    <label for="person_name" class="person_name">Designation</label>
                    <input id="designation" type="text"  name="designation" value="{{$clientCompanyContactPerson->designation}}" required autofocus>
                </div>
                <div class="input-area {{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email" class="email">Email</label>
                    <input id="email" type="text"  name="email" value="{{$clientCompanyContactPerson->email}}" required autofocus>
                </div>
                <div class="input-area {{ $errors->has('phone') ? ' has-error' : '' }}">
                    <label for="email" class="email">Phone</label>
                    <input id="phone" type="text"  name="phone" value="{{$clientCompanyContactPerson->phone}}" required autofocus>

                </div>
                <div class="input-area {{ $errors->has('remarks') ? ' has-error' : '' }}">
                    <label for="details" class="details">Remarks</label>
                    <textarea id="remarks"  name="remarks" required>{{$clientCompanyContactPerson->remarks}}</textarea>
                    @if ($errors->has('remarks'))
                        <span class="help-block">
                            <strong>{{ $errors->first('remarks') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <div class="row text-center">
                        <a href="{{ route('clientCompaniesContacts.index',['id'=>$clientCompanyContactPerson->client_company_id]) }}" class="big-btn">Go back</a>
                        <button type="submit" class="big-btn">
                                Update Contact
                        </button>
                    </div>
                </div>
            </form>

@endsection