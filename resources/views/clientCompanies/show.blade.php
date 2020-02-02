@extends( 'layouts.app' )

@section( 'title', $clientCompany->company_name )
@section('HeaderAdditionalCodes')
	<script>
		var parentClasses = ["a_client_management"];
	</script>
@endsection
@section( 'content' )



<h1>{{ $clientCompany->company_name }}</h1>

	<div class="section company-details list-items ">
	<ul>
				<li><strong>Company Name</strong> {{ $clientCompany->company_name }}</li>
				<li><strong>Remarks</strong>{!! $clientCompany->remarks !!}</li>
		</ul>
	</div>
	
		@foreach($clientCompany->clientCompanyContactPersons as $clientCompanyContactPerson)
		<div class="section person-details list-items ">
		<h2>Contact Person {{ $loop->index+1 }}</h2>
		<ul>
					<li><strong>Name</strong> {{ $clientCompanyContactPerson->name }}</li>
					<li><strong>Designation</strong>{{ $clientCompanyContactPerson->designation }}</li>
					<li><strong>Email</strong>{{ $clientCompanyContactPerson->email }}</li>
					<li><strong>Phone</strong>{{ $clientCompanyContactPerson->phone }}</li>
					<li><strong>Remarks</strong>{{ $clientCompanyContactPerson->remarks }}</li>
				</ul>
				</div>
		@endforeach
	
@if( auth()->user()->isAdmin() )

	<div class="button-area">
		<a href="{{ route('clientCompanies.edit', ['id' => $clientCompany->id]) }}" class="big-btn yellowbtn">Edit</a>
		<a href="{{ route('clientCompanies.delete', ['id' => $clientCompany->id]) }}" class="big-btn redbtn">Delete</a>
		<a href="{{ route('clientCompanies') }}" class="big-btn">Back</a>
	</div>
@endif



</div>
@endsection