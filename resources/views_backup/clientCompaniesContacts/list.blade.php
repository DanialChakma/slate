@extends('layouts.app')

@section('title','List of Contact Persons')

@section('content')
    <div class="row">
        <div class="col-xs-6">
            @if(isset($company_id))
            <a class="big-btn" href="{{ route('clientCompaniesContacts.create',['id' => $company_id ]) }}">Add New Contact Person</a>
            @endif
        </div>
        <div class="col-xs-6">
            @if(isset($clientCompanyContactPersons) && count($clientCompanyContactPersons) >0 )
                <form action="{{ route('clientCompaniesContacts.search') }}" method="GET">
                    {{ csrf_field() }}
                    <div class="input-group">
                        @if(isset($company_id) && !empty($company_id) )
                            <input type="hidden" class="form-control" name="company_id" value="{{$company_id}}">
                        @endif
                        <input type="text" class="form-control" name="q" placeholder="Search Contacts Person...">
                        <span class="input-group-btn">
					        <button type="submit" class="btn btn-secondary">
                               Search
                            </button>
				        </span>
                    </div>
                </form>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-xs-8">
            @if(isset($clientCompanyContactPersons))
                @if(isset($q))
                {{ $clientCompanyContactPersons->appends(['q'=>$q,'company_id'=>$company_id])->links() }}
                @else
                    {{ $clientCompanyContactPersons->appends(['company_id'=>$company_id])->links() }}
                @endif
            @endif
        </div>
        <div class="col-xs-4">
            @if(isset($clientCompanyContactPersons) && count($clientCompanyContactPersons)> 0 )
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
                            <td>{{ str_limit($row->name,10,'...') }}</td>
                            <td>{{ $row->designation }}</td>
                            <td>{{ $row->email }}</td>
                            <td>{{ $row->phone }}</td>

                            <td>{{ str_limit($row->remarks,10,'...') }}</td>
                            <td>
                                <a class="action-btn" href="{{ route('clientCompaniesContacts.show', ['id' => $row->id]) }}">
                                    Details
                                </a>
                                <a class="action-btn" href="{{ route('clientCompaniesContacts.edit', ['id' => $row->id]) }}">
                                      Edit
                                </a>
                                <a class="action-btn" href="{{ route('clientCompaniesContacts.delete', ['id' => $row->id]) }}">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @elseif( isset($msg) )
                <div class="text-info">{{$msg}}</div>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            @if(isset($clientCompanyContactPersons))
                @if(isset($q))
                    {{ $clientCompanyContactPersons->appends(['q'=>$q,'company_id'=>$company_id])->links() }}
                @else
                    {{ $clientCompanyContactPersons->appends(['company_id'=>$company_id])->links() }}
                @endif
            @endif
        </div>
    </div>
@endsection