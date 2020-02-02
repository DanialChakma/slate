@extends('layouts.app')

@section('title','Contact Details of Client Company')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading text-center">
                   <h2>
                       Contact Details of <strong>{{ $clientCompanyContactPerson->clientCompany->company_name}}</strong> Company.
                   </h2>
                </div>
                <div class="panel-body">
                    <div class="input-area">
                        <label for="person_name" class="person_name">Person Name</label>
                        <input readonly="readonly" id="name" type="text" name="name" value="{{$clientCompanyContactPerson->name}}">
                    </div>
                    <div class="input-area">
                        <label for="person_name" class="person_name">Designation</label>
                        <input readonly="readonly" id="designation" type="text" class="designation" name="designation" value="{{$clientCompanyContactPerson->designation}}">
                    </div>
                    <div class="input-area">
                        <label for="email" class="email">Email</label>
                        <input readonly="readonly" id="email" type="text" class="email" name="email" value="{{$clientCompanyContactPerson->email}}">
                    </div>
                    <div class="input-area">
                        <label for="email" class="email">Phone</label>
                        <input readonly="readonly" id="phone" type="text" class="phone" name="phone" value="{{$clientCompanyContactPerson->phone}}">
                    </div>
                    <div class="input-area">
                        <label for="details" class="details">Remarks</label>
                        <textarea readonly="readonly" id="remarks" class="remarks" name="remarks" required>{{$clientCompanyContactPerson->remarks}}</textarea>
                    </div>

                    <br />
                    <hr>
                    <div class="input-area text-center">
                        <a href="{{ route('clientCompaniesContacts.index',['id'=> $clientCompanyContactPerson->client_company_id]) }}" class="big-btn">Go back</a>
                        <a href="{{ route('clientCompaniesContacts.edit', ['id' => $clientCompanyContactPerson->id]) }}" class="big-btn yellowbtn">Edit</a>
                        <a href="{{ route('clientCompaniesContacts.delete', ['id' => $clientCompanyContactPerson->id]) }}" class="big-btn redbtn">Delete</a>
                        <a href="{{ route('clientCompaniesContacts.create', ['id' => $clientCompanyContactPerson->client_company_id]) }}" class="big-btn">Add Contact</a>
                    </div>
              </div>
            </div>
        </div>
    </div>
@endsection