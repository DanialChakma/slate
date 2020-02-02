@extends('layouts.app')
@section('title','Meeting Details')
@section('HeaderAdditionalCodes')
    <script>
        var parentClasses = ["a_meetings"];
    </script>
@endsection
@section('content')

    <form role="form" action = "#" >
        {{ csrf_field() }}
        <div class="input-area {{ $errors->has('title') ? ' has-error' : '' }}">
            <label for="select">Meeting Title</label>
            <input readonly id="title" type="text"  name="title" value="{{ (isset($meeting) && !empty($meeting->title))? $meeting->title:"" }}">
            @if($errors->has('title'))
                <span class="help-block">
                        <strong>{{ $errors->first('title') }}</strong>
                </span>
            @endif
        </div>
        <div class="input-area {{ $errors->has('remarks') ? ' has-error' : '' }}">
            <label for="select">Remarks</label>
            <textarea readonly id="remarks" name="remarks">{{ (isset($meeting) && !empty($meeting->remarks)) ? $meeting->remarks:"" }}</textarea>
            @if ($errors->has('remarks'))
                <span class="help-block">
                        <strong>{{ $errors->first('remarks') }}</strong>
                </span>
            @endif
        </div>

        <div class="input-area {{ $errors->has('department') ? ' has-error' : '' }}">
            <label for="select">Department</label>
            <select readonly name="department" id="department" >
                @foreach(\App\Department::all() as $department)
                    @if( $meeting->project->department->id  == $department->id )
                        <option selected value="{{$department->id}}">{{$department->name}}</option>
                    @else
                        <option value="{{$department->id}}">{{$department->name}}</option>
                    @endif
                @endforeach
            </select>
            @if($errors->has('department'))
                <span class="help-block">
                    <strong>{{ $errors->first('department') }}</strong>
                </span>
            @endif
        </div>

        <div class="input-area {{ $errors->has('project_id') ? ' has-error' : '' }}">
            <label for="select">Project</label>
            <select readonly name="project_id" id="project_id" required>
                <option value="">--Select Project--</option>
                @foreach($meeting->project->department->projects as $project)
                    @if( $project->id == $meeting->project_id )
                        <option selected value="{{$project->id}}">{{$project->name}}</option>
                    @else
                        <option value="{{$project->id}}">{{$project->name}}</option>
                    @endif
                @endforeach
            </select>
            @if($errors->has('project_id'))
                <span class="help-block">
                    <strong>{{ $errors->first('project_id') }}</strong>
                </span>
            @endif
        </div>

        <div class="input-area {{ $errors->has('date') || $errors->has('start_time') || $errors->has('end_time') ? ' has-error' : '' }}">
            <div class="five columns">
                <label for="date">Time &amp; Date</label>
            </div>
            <div class="three columns">
                <input value="{{ date("m/d/Y",strtotime($meeting->start_time)) }}" name="date" data-range="false" data-multiple-dates-separator=" - " data-language="en" class="datepicker-here input-icon icon-calender" type="text">
                @if($errors->has('date'))
                    <span class="help-block">
                        <strong>{{ $errors->first('date') }}</strong>
                    </span>
                @endif
            </div>
            <div class="two columns">
                <input value="{{ date("h:i",strtotime($meeting->start_time)) }}" name="start_time" id="start_time" class="only-time input-icon icon-time" type="text">
                @if ($errors->has('start_time'))
                    <span class="help-block">
                        <strong>{{ $errors->first('start_time') }}</strong>
                    </span>
                @endif
            </div>
            <div class="two columns">
                <input value="{{ date("h:i",strtotime($meeting->end_time)) }}" name="end_time" id="end_time" class="only-time input-icon icon-time" type="text">
                @if ($errors->has('end_time'))
                    <span class="help-block">
                        <strong>{{ $errors->first('end_time') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="input-area {{ $errors->has('location') ? ' has-error' : '' }}">

            <label for="Location">Location</label>
            <input  value="{{ isset($meeting->location)? $meeting->location: ""}}" name="location" id="location" type="text" placeholder="Search Meeting Place.">
            @if ($errors->has('location'))
                <span class="help-block">
                    <strong>{{ $errors->first('location') }}</strong>
                </span>
            @endif
        </div>
        <div class="input-area">
            <div class="google-map" id="map_canvas" style="position: relative; overflow: hidden;">
            </div>
        </div>
        <div class="input-area {{ $errors->has('client_company_id') ? ' has-error' : '' }}">
            <label for="Client Company">Client Company</label>
            <input readonly type="text" id="client_company_id" name="client_company_id" value="{{$meeting->clientCompany->company_name}}"/>
            @if($errors->has('client_company_id'))
                <span class="help-block">
                        <strong>{{ $errors->first('client_company_id') }}</strong>
                </span>
            @endif
        </div>


        <div id="contacts" class="input-area contacts">
            @foreach( $meeting->clientCompanyContactPersons as $index=>$contact )
                <div class="input-area contact">
                    {{--<div class="fr"><a href="javascript:void(0)"><span class="glyphicon glyphicon-remove-sign remove"></span> </a> </div>--}}
                    <div class="input-area">
                        <input type="hidden" name="contacts[]" value="{{$contact->id}}" autofocus>
                        <label for="person_name" class="person_name">Client Contact Person</label><input id="name[]" type="text" class="name" name="name[]" value="{{$contact->name}}" autofocus>
                    </div>
                    <div class="input-area">
                        <label for="designation" class="designation">Client Designation</label><input id="designation[]" type="text" class="designation" name="designation[]" value="{{$contact->designation}}" autofocus>
                    </div>
                    <div class="input-area">
                        <div class="four columns"><label for="date">Client Contact Details</label></div>
                        <div class="five columns"><input placeholder="Email" type="email" class="input-icon icon-email" id="email[]" name="email[]" value="{{$contact->email}}" autofocus></div>
                        <div class="three columns"><input placeholder="Cell Number" type="text" class="input-icon icon-phone" id="phone[]" name="phone[]" value="{{$contact->phone}}" autofocus/></div>
                    </div>
                </div>
            @endforeach
        </div>
        {{--<div class="section"><a href="#" class="add_contact_person fr"> + Add New Contact Person</a></div>--}}
        <div id="quann_staffs" class="quann_staffs input-area">
	   @foreach($meeting->staffs as $index=>$staff )
            <div class="quann_staff input-area">
                <label for="Quann Staff Name">Quann Staff Name <span class="required">*</span></label>
                <input type="text" name="user_id" value="{{ \App\User::find($staff->pivot->user_id)->name }}"/>
            </div>
            @endforeach
        </div>
        <div class="button-area text-center">
                <a href="{{ route('meetings.edit', ['id' => $meeting->id]) }}" class="big-btn yellowbtn">Edit</a>
                <a href="{{ route('meetings.delete', ['id' => $meeting->id]) }}" class="big-btn redbtn">Delete</a>
            <a href="{{ route('meetings') }}" class="big-btn">Back</a>
        </div>
    </form>

    <script>
        var geocoder;
        var map;
        var marker;
        var infowindow;

        function initialize() {
            geocoder = new google.maps.Geocoder();
            var latlng = new google.maps.LatLng(-34.397, 150.644);
            var mapOptions = {
                zoom: 8,
                center: latlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            }
            map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
            infowindow = new google.maps.InfoWindow({
                size: new google.maps.Size(150, 50)
            });
            google.maps.event.addListener(map, 'click', function() {
                infowindow.close();
            });

            codeAddress();
        }

        function geocodePosition(pos) {
            geocoder.geocode({
                latLng: pos
            }, function(responses) {
                if (responses && responses.length > 0) {
                    marker.formatted_address = responses[0].formatted_address;
                    $("#location").val(marker.formatted_address);
                } else {
                    marker.formatted_address = 'Cannot determine address at this location.';
                }
                infowindow.setContent(marker.formatted_address + "<br>coordinates: " + marker.getPosition().toUrlValue(6));
                infowindow.open(map, marker);

            });
        }

        function codeAddress() {
            var address = document.getElementById('location').value;
            geocoder.geocode({
                'address': address
            }, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    map.setCenter(results[0].geometry.location);
                    map.setZoom(16);
                    if (marker) {
                        marker.setMap(null);
                        if (infowindow) infowindow.close();
                    }
                    marker = new google.maps.Marker({
                        map: map,
                        draggable: true,
                        position: results[0].geometry.location
                    });
                    google.maps.event.addListener(marker, 'dragend', function() {
                        geocodePosition(marker.getPosition());
                    });
                    google.maps.event.addListener(marker, 'click', function() {
                        if (marker.formatted_address) {
                            $("#location").val(marker.formatted_address);
                            infowindow.setContent(marker.formatted_address + "<br>coordinates: " + marker.getPosition().toUrlValue(6));
                        } else {
                            infowindow.setContent(address + "<br>coordinates: " + marker.getPosition().toUrlValue(6));
                        }
                        infowindow.open(map, marker);
                    });
                    google.maps.event.trigger(marker, 'click');
                } else {
                    // alert('Geocode was not successful for the following reason: ' + status);
                }
            });
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAxbgv9LR7QeRR-k2H0nrNlltUhNBvpjnw&callback=initialize" async defer></script>
@endsection

@section('FooterAdditionalCodes')
    <script type="text/javascript">
        $(document).ready(function(){

            $('#location').on("keypress", function (event) {
                if (event.which === 13) {
                    codeAddress();
                    e.preventDefault();
                }
            });

            $("#client_company_contact_person_id").on('change', function () {
                var contact_id = $(this).val();

            });

            $("#client_company_id").on('change', function () {
                $("#contacts").html("");
                var company_id = $(this).val();
                $.ajax({
                    type: "GET",
                    url: "/meetings/getContactsUnderCompany?company_id=" + company_id,
                    async: false,
                    dataType: 'JSON',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (rows) {
                        var contact_details = "";
                        var options = '<option value="NA">' + "--Select Person--" + '</option>';
                        for (var row in rows) {
                            options += '<option value="' + rows[row].id + '">' + rows[row].name + '</option>';
                            contact_details += '<input  type="hidden" name="contact_name['+rows[row].id+']" value="'+rows[row].name+'"/>';
                            contact_details += '<input  type="hidden" name="contact_designation['+rows[row].id+']" value="'+rows[row].designation+'"/>';
                            contact_details += '<input  type="hidden" name="contact_email['+rows[row].id+']" value="'+rows[row].email+'"/>';
                            contact_details += '<input  type="hidden" name="contact_phone['+rows[row].id+']" value="'+rows[row].phone+'"/>';
                        }
                        $("#client_company_contact_person_id").html(options);
                        $('.contact_details').html(contact_details);
                    },
                    error: function (jqXHR, textStatus, errorThrown) { },
                    processData: false,
                });
            });

            $("#department").on('change', function () {
                var department_id = $(this).val();
                $.ajax({
                    type: "GET",
                    url: "/meetings/getProjectUnderDepartment?department_id=" + department_id,
                    async: false,
                    dataType: 'JSON',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (rows) {
                        var options = '<option value="NA">' + "--Select Project--" + '</option>';
                        for (var row in rows) {
                            options += '<option value="' + rows[row].id + '">' + rows[row].name + '</option>';
                        }
                        $("#project_id").html(options);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {

                    },
                    processData: false,
                });
            });


            $(document).on('change','#client_company_contact_person_id',function(event){


            });

            $(document).on('click','.remove',function(event){
                var contact_id = $(this).closest('.contact').find('input[type="hidden"][name="contacts[]"]').first().val();
                $(this).closest('.contact').remove();
                $('#client_company_contact_person_id option[value="'+contact_id+'"]').show();
            });

            var counter = 0;

            $('.add_contact_person').on('click',function(event){
                event.preventDefault();

                var contact_id = $('#client_company_contact_person_id').val();
                console.log(contact_id);
                if( !contact_id || contact_id == 'NA' || contact_id == '' || typeof contact_id == 'undefined' ){
                    return;
                }

                var name = $('input[name="contact_name['+contact_id+']"]').first().val();
                var designatiion =  $('input[name="contact_designation['+contact_id+']"]').first().val();
                var email =  $('input[name="contact_email['+contact_id+']"]').first().val();
                var phone =  $('input[name="contact_phone['+contact_id+']"]').first().val();

                // console.log("Name:"+name+',Designation:'+designatiion+',Email:'+email+',Phone:'+phone);

                var client_div ='<div class="contact">'+
                        '<div class="fr"><a href="javascript:void(0)"><span class="glyphicon glyphicon-remove-sign remove"></span> </a> </div>'+
                        '<div class="input-area">'+
                        '<input type="hidden" name="contacts[]" value="'+contact_id+'" autofocus>'+
                        '<label for="person_name" class="person_name">Client Contact Person</label><input id="name[]" type="text" class="name" name="name[]" value="'+name+'" autofocus>'+
                        '</div>'+
                        '<div class="input-area">'+
                        '<label for="designation" class="designation">Client Designation</label><input id="designation[]" type="text" class="designation" name="designation[]" value="'+designatiion+'" autofocus>'+
                        '</div>'+
                        '<div class="input-area">'+
                        '<div class="four columns"><label for="date">Client Contact Details</label></div>'+
                        '<div class="five columns"><input placeholder="Email" type="email" class="input-icon icon-email" id="email[]" name="email[]" value="'+email+'" autofocus></div>'+
                        '<div class="three columns"><input placeholder="Cell Number" type="text" class="input-icon icon-phone" id="phone[]" name="phone[]" value="'+phone+'" autofocus/></div>'+
                        '</div>'+
                        '</div>';
                $("#contacts").append(client_div);
                $('#client_company_contact_person_id option[value="'+contact_id+'"]').hide();
                $('#client_company_contact_person_id option[value="'+contact_id+'"]').attr('selected',false);
                counter++;
            });

        });
    </script>

@endsection