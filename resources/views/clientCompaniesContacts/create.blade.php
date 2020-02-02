@extends('layouts.app')

@section('title','Add Client Company Contact Person')

@section('content')

            <form  method="POST" action="{{ route('clientCompaniesContacts.store') }}">
                {{ csrf_field() }}

                <div class="input-area {{ $errors->has('company_name') ? ' has-error' : '' }}">
                    <div class="text-center">
                        <input id="client_company_id" type="hidden" class="client_company_id" name="client_company_id" value="{{ $clientCompany->id }}">
                        <h2>
                           Create Contact Information for <strong>{{ $clientCompany->company_name }}</strong> Company.
                       </h2>
                        <hr/>
                        {{--<input id="company_name" type="text" class="company_name" name="company_name" value="{{ $clientCompany->company_name }}" required autofocus>--}}
                    </div>
                </div>
                <br/>
                <div class="input-area {{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="person_name" class="person_name">Person Name</label>
                    <input id="name" type="text" class="name" name="name" required autofocus>
                </div>
                <div class="input-area {{ $errors->has('designation') ? ' has-error' : '' }}">
                    <label for="person_name" class="person_name">Designation</label>
                    <input id="designation" type="text" class="designation" name="designation" required autofocus>
                </div>
                <div class="input-area {{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email" class="email">Email</label>
                    <input id="email" type="text" class="email" name="email" required autofocus>
                </div>
                <div class="input-area {{ $errors->has('phone') ? ' has-error' : '' }}">

                        <label for="email" class="email">Phone</label>
                        <input id="phone" type="text" class="phone" name="phone" required autofocus>
                </div>
                <div class="input-area {{ $errors->has('remarks') ? ' has-error' : '' }}">
                    <label for="details" class="details">Remarks</label>
                    <textarea id="remarks" class="remarks" name="remarks" required>{{ old('remarks') }}</textarea>
                    @if ($errors->has('remarks'))
                        <span class="help-block">
                            <strong>{{ $errors->first('remarks') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <div class="row text-center">
                        <a href="{{ route('clientCompaniesContacts.index',['id'=>$clientCompany->id]) }}" class="big-btn">Go back to list page</a>
                        <button type="submit" class="big-btn">
                                Create Contact
                        </button>
                    </div>
                </div>
            </form>

@endsection