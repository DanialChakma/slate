@extends('layouts.app')

@section('title', 'Delete Client Company')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $clientCompany->company_name }}</div>
                <div class="panel-body">
                    {!! $clientCompany->remarks !!}
                    <br />
                    <hr />
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('clientCompanies.confirmDelete', ['id' => $clientCompany->id]) }}">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 col-md-offset-1">
                                    <button type="submit">
                                        Confirm Delete
                                    </button>
                                    <a href="{{ route('clientCompanies.index') }}" class="big-btn">Back</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection