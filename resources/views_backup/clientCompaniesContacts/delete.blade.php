@extends('layouts.app')

@section('title', 'Delete Client Company')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading"></div>
                <div class="panel-body">
                    <div>Are you sure to delete <strong>{{ $clientCompanyContactPerson->name }}</strong> ? </div>
                    <br />
                    <hr />
                    <form  method="POST" action="{{ route('clientCompaniesContacts.confirmDelete', ['id' => $clientCompanyContactPerson->id]) }}">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <div class="row text-center">
                                <a href="{{ route('clientCompaniesContacts.index',['id'=>$clientCompanyContactPerson->client_company_id]) }}" class="big-btn">Go back to list page</a>
                                <button type="submit" class="big-btn">
                                    Confirm Delete
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection