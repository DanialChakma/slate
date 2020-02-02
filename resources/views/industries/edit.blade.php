@extends( 'layouts.app' )

@php
$title = "Edit Industry";
@endphp

@section( 'title', $title )


@section('HeaderAdditionalCodes')
	<script>
		var parentClasses = ["a_industry"];
	</script>
@endsection

@section( 'content' )
	<h1>{{ $title }}</h1>

	<div class="row">
		<form class="form-horizontal" role="form" method="POST" action="{{ route('industries.update', ['id' => $industry->id]) }}">
			{{ csrf_field() }}

			<input name="id" type="hidden" value="{{ $industry->id }}"/>
			<div class="input-area {{ $errors->has('name') ? ' has-error' : '' }}">
				<label for="name">Industry Name <span class="required">*</span></label>
				<input id="name" type="text" name="name" autocomplete="off" value="{{ empty(old('name')) ? $industry->name : old('name') }}" required autofocus> @if ($errors->has('name'))
				<span class="help-block">
                 <strong>{{ $errors->first('name') }}</strong>
                        </span>
				@endif
			</div>
			<div class="button-area">
				<a href="{{ route('industries') }}" class="big-btn">Back</a>
				<button type="submit" class="sbtn">Update</button>
			</div>
		</form>
	</div>

@endsection