@extends('layouts.app')

@section('title',$clientCompany->company_name)

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $clientCompany->company_name }}</div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <td class="col-xs-2">Company Name: </td>
                                <td>{{ $clientCompany->company_name }}</td>
                            </tr>
                            <tr>
                                <td class="col-xs-2">Remarks</td>
                                <td>{!! $clientCompany->remarks !!}</td>
                            </tr>
                        </table>
                    </div>
                    <br />
                    <div class="table-responsive">
                        @foreach($clientCompany->clientCompanyContactPersons as $clientCompanyContactPerson)
                            <h3 class="text-center">Contact Person {{ $loop->index+1 }}</h3>
                            <table class="table table-bordered">
                                <tr>
                                    <td class="col-sm-3">
                                        Name:
                                    </td>
                                    <td>
                                        {{ $clientCompanyContactPerson->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Designation:
                                    </td>
                                    <td>
                                        {{ $clientCompanyContactPerson->designation }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Email:
                                    </td>
                                    <td>
                                        {{ $clientCompanyContactPerson->email }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Phone:
                                    </td>
                                    <td>
                                        {{ $clientCompanyContactPerson->phone }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Remarks:
                                    </td>
                                    <td>
                                        {{ $clientCompanyContactPerson->remarks }}
                                    </td>
                                </tr>
                            </table>
                        @endforeach
                    </div>
                </div>

                    <br />
                    <hr>
                    <div class="text-center">
                        <a href="{{ route('clientCompanies.edit', ['id' => $clientCompany->id]) }}" class="big-btn yellowbtn">Edit</a>
                        <a href="{{ route('clientCompanies.delete', ['id' => $clientCompany->id]) }}" class="big-btn redbtn">Delete</a>
                        <a href="{{ route('clientCompanies.index') }}" class="big-btn">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection