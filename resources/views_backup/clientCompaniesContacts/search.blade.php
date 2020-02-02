@extends('layouts.app')

@section('title','List of Contact Persons')

@section('content')
    <div class="row">
        <div class="col-xs-6">
            @if( isset($clientCompanyContactPersons) && count($clientCompanyContactPersons)> 0)
                <a class="btn btn-md btn-primary" href="{{ route('clientCompaniesContacts.create',['id' => $clientCompanyContactPersons[0]->client_company_id]) }}">Add New Contact Person.</a>
            @endif
        </div>
        <div class="col-xs-6">
            <form action="{{ route('clientCompaniesContacts.search') }}" method="GET" role="search">
                {{ csrf_field() }}
                <div class="input-group">
                    @if(isset($company_id) && !empty($company_id) )
                        <input type="hidden" class="form-control" name="company_id" value="{{$company_id}}">
                    @endif
                    <input type="text" class="form-control" name="q"
                           placeholder="Search Contacts Person.">
                        <span class="input-group-btn">
					        <button type="submit" class="btn btn-default">
                                <span class="glyphicon glyphicon-search"></span>
                            </button>
				        </span>
                </div>
            </form>
        </div>
    </div>
    <div class="row">

        <div class="col-xs-8">
            @if(isset($clientCompanyContactPersons) )
                @if(isset($q))
                    {{ $clientCompanyContactPersons->appends(['q'=>$q,'company_id'=>$company_id])->links() }}
                @else
                    {{ $clientCompanyContactPersons->links() }}
                @endif
            @endif
        </div>
        <div class="col-xs-4">

            @if(isset($clientCompanyContactPersons) && count($clientCompanyContactPersons) >10 )
                <span class="pagination">
                    {{ "Total Contact Persons found:".$clientCompanyContactPersons->total() }}
                </span>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            @if( isset($clientCompanyContactPersons) && count($clientCompanyContactPersons) > 0 )
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <td>Sl.</td>
                            <th>Name</th>
                            <th>Designation</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Remarks</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($clientCompanyContactPersons as $row)
                            <tr>
                                <td>{{ $row->id }}</td>
                                <td>{{ $row->name }}</td>
                                <td>{{ $row->designation }}</td>
                                <td>{{ $row->email }}</td>
                                <td>{{ $row->phone }}</td>

                                <td>{{ str_limit($row->remarks,30,'...')  }}</td>
                                <td>
                                    <a class="btn btn-primary btn-sm" href="{{ route('clientCompaniesContacts.show', ['id' => $row->id]) }}">
                                        Details
                                    </a>
                                    <a class="btn btn-primary btn-sm" href="{{ route('clientCompaniesContacts.edit', ['id' => $row->id]) }}">
                                        <span class="glyphicon glyphicon-edit">Edit</span>
                                    </a>
                                    <a class="btn btn-primary btn-sm" href="{{ route('clientCompaniesContacts.delete', ['id' => $row->id]) }}">
                                        <span class="glyphicon glyphicon-trash">Delete</span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @elseif( isset($msg) )
                <div class="text-danger text-center">{{$msg}}</div>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            @if(isset($clientCompanyContactPersons) && count($clientCompanyContactPersons)> 0 )
                @if(isset($q))
                    {{ $clientCompanyContactPersons->appends(['q'=>$q,'company_id'=>$company_id])->links() }}
                @else
                    {{ $clientCompanyContactPersons->appends(['company_id'=>$company_id])->links() }}
                @endif
            @endif
        </div>
    </div>
@endsection