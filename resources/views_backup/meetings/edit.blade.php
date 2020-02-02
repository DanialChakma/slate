@extends('layouts.app')
@section('title','Edit Meeting')
@section('HeaderAdditionalCodes')
    <style>
        .required{
            color:orangered;
        }
    </style>
@endsection
@section('content')

                <form class="form-horizontal" method="POST" role="form" action = "{{route( 'meetings.update',[ 'id'=>$meeting->id ] )}}" >
                    {{ csrf_field() }}

                    <div class="input-area {{ $errors->has('department') ? ' has-error' : '' }}">
                        <label for="select">Department <span class="required">*</span></label>
                        <select name="department" id="department" >
                            @foreach(\App\Department::all() as $department)
                                @if( $meeting->project->department->id  == $department->id )
                                    <option selected value="{{$department->id}}">{{$department->name}}</option>
                                @else
                                    <option value="{{$department->id}}">{{$department->name}}</option>
                                @endif
                            @endforeach
                        </select>
                        @if ($errors->has('department'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('department') }}</strong>
                                    </span>
                        @endif
                    </div>

                    <div class="input-area {{ $errors->has('project_id') ? ' has-error' : '' }}">
                        <label for="select">Project Type <span class="required">*</span></label>
                        <select  name="project_id" id="project_id" required>
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

                    <div class="input-area {{ $errors->has('title') ? ' has-error' : '' }}">
                            <label for="select">Meeting Title <span class="required">*</span></label>
                            <input id="title" type="text"  name="title" value="{{ (isset($meeting) && !empty($meeting->title))? $meeting->title:"" }}">
                            @if($errors->has('title'))
                                <span class="help-block">
                                                <strong>{{ $errors->first('title') }}</strong>
                                </span>
                            @endif
                    </div>

                    <div class="input-area {{ $errors->has('date') || $errors->has('start_time') || $errors->has('end_time') ? ' has-error' : '' }}">
                        <div class="five columns">
                            <label for="date">Time &amp; Date <span class="required">*</span></label>
                        </div>
                        <div class="three columns">
                            <input value="{{ date("m/d/Y",strtotime($meeting->start_time)) }}" name="date" data-range="false" data-multiple-dates-separator=" - " data-language="en" class="datepicker-here input-icon icon-calender" type="text">
                            @if ($errors->has('date'))
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
                            <label for="Location">Location <span class="required">*</span></label>
                            <input  value="{{ isset($meeting->location)? $meeting->location: ""}}" name="location" id="location" type="text" placeholder="Search Meeting Place.">
                            @if($errors->has('location'))
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
                            <label for="Client Company">Client Company <span class="required">*</span></label>
                            <select id="client_company_id" name="client_company_id" required>
                                <option value="">--Select Company--</option>
                                @foreach(\App\ClientCompany::all() as $company)
                                    @if($company->id == $meeting->clientCompany->id)
                                        <option selected value="{{$company->id}}">{{$company->company_name}}</option>
                                    @else
                                        <option value="{{$company->id}}">{{$company->company_name}}</option>
                                    @endif
                                @endforeach
                            </select>
                            @if($errors->has('client_company_id'))
                                <span class="help-block">
                                            <strong>{{ $errors->first('client_company_id') }}</strong>
                                </span>
                            @endif
                            <div class="contact_details">
                                @foreach($meeting->clientCompany->clientCompanyContactPersons as $person)
                                    <input type="hidden" name="ID" value="{{$person->id}}" data-name="{{$person->name}}" />
                                    <input type="hidden" name="contact_designation[{{$person->id}}]" value="{{$person->designation}}" />
                                    <input type="hidden" name="contact_email[{{$person->id}}]" value="{{$person->email}}" />
                                    <input type="hidden" name="contact_phone[{{$person->id}}]" value="{{$person->phone}}" />
                                @endforeach
                            </div>
                    </div>


                    <?php
                    $SelectedPersons = array();
                    ?>
                    @foreach($meeting->clientCompanyContactPersons as $person)
                        <?php array_push($SelectedPersons,$person->pivot->client_company_contact_person_id); ?>
                    @endforeach
                    <div id="contacts" class="contacts input-area">
                        @foreach( $meeting->clientCompanyContactPersons as $index=>$contact )
                        <div class="contact input-area">
                            <div class="fr"><a href="javascript:void(0)"><span class="glyphicon glyphicon-remove-sign remove"></span> </a> </div>
                            <div class="input-area">
                                <label for="person_name" class="person_name">Client Contact Person <span class="required">*</span></label>
                                <select class="contact_persons"  name="contact_persons[]" required>
                                    <option value="">--Select Person--</option>
                                    @foreach($meeting->clientCompany->clientCompanyContactPersons as $companyPerson)
                                         @if( in_array($companyPerson->id,$SelectedPersons) )
                                              @if($contact->id == $companyPerson->id)
                                                  <option selected value="{{$companyPerson->id}}">{{$companyPerson->name}}</option>
                                              @else
                                                  <option style="display: none" value="{{$companyPerson->id}}">{{$companyPerson->name}}</option>
                                              @endif
                                        @else
                                            <option value="{{$companyPerson->id}}">{{$companyPerson->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-area">
                                <label for="designation" class="designation">Client Designation <span class="required">*</span></label><input id="designation[]" type="text" class="designation" name="designation[]" value="{{$contact->designation}}" autofocus>
                            </div>
                            <div class="input-area">
                                <div class="four columns"><label for="date">Client Contact Details <span class="required">*</span></label></div>
                                <div class="five columns"><input placeholder="Email" type="email" class="input-icon icon-email" id="email[]" name="email[]" value="{{$contact->email}}" autofocus></div>
                                <div class="three columns"><input placeholder="Cell Number" type="text" class="input-icon icon-phone" id="phone[]" name="phone[]" value="{{$contact->phone}}" autofocus/></div>
                            </div>

                        </div>
                        @endforeach
                    </div>

                    <div class="section"><a href="#" class="add_contact_person fr"> + Add New Contact Person</a></div>
                    <div id="quann_staffs_hidden" style="display:none">
                        @if(isset($staffs) && count($staffs)> 0 )
                            @foreach($staffs as $staff)
                                <input type="hidden" value="{{$staff->id}}" name="{{$staff->name}}"/>
                            @endforeach
                        @endif
                    </div>
                    <div id="quann_staffs" class="quann_staffs input-area">
                        @foreach($meeting->staffs as $selectedStaff )
                        <div class="quann_staff input-area">
                            <div class="fr"><a href="javascript:void(0);"><span class="glyphicon glyphicon-remove-sign remove"></span> </a> </div>
                            <label for="Quann Staff Name">Quann Staff Name <span class="required">*</span></label>
                            <select  name="user_id[]" required="">
                                <option value="">--Select Staff--</option>
                                @if(isset($staffs) && count($staffs)> 0 )
                                    @foreach($staffs as $staff)
                                        @if( $staff->id == $selectedStaff->pivot->user_id )
                                            <option selected value="{{$staff->id}}">{{$staff->name}}</option>
                                        @else
                                            <option value="{{$staff->id}}">{{$staff->name}}</option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        @endforeach
                    </div>
		    <div class="section"><a href="#" class="add_quann_staff fr"> + Add New Quann Staff</a></div>
                    
		    <div class="input-area {{ $errors->has('remarks') ? ' has-error' : '' }}">
                        <label for="select">Remarks</label>
                        <textarea id="remarks" name="remarks">{{ (isset($meeting) && !empty($meeting->remarks)) ? $meeting->remarks:"" }}</textarea>
                        @if ($errors->has('remarks'))
                            <span class="help-block">
                                            <strong>{{ $errors->first('remarks') }}</strong>
                                </span>
                        @endif
                    </div>
                    <div class="input-area text-right">
			<a href="{{ route('meetings') }}" class="big-btn">Back</a>
                        <button type="submit" class="big-btn">
                            Update Meeting
                        </button>
                       
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

    <script type="text/javascript" src="{{asset('js/JconfirmFunctions.js')}}"></script>
    <script type="text/javascript">
        function check_front_end_validation(){
            var is_one_contact_person_selected = false;
            $('#contacts').find('select.contact_persons').each(function(index,elem){
                var selOption = $(elem).find('option:selected').val();
                if( selOption && selOption !== "" ){
                    is_one_contact_person_selected = true;
                    return false;
                }
            });

            if( !is_one_contact_person_selected ){

                JconfirmAlert("Notification","Please,Select at least one contact person.");
                return false;
            }
        }

        $(document).ready(function(){

            $('#location').on("keypress", function (event) {
                if (event.which === 13) {
                    codeAddress();
                    e.preventDefault();
                }
            });

	
	   $(document).on('click','.add_quann_staff',function(event){
                event.preventDefault();

                var option_string = '<option value="">'+'--Select Staff--'+'</option>';
                $('#quann_staffs_hidden').find('input[type="hidden"]').each(function(index,elem){
                    var staff_id = $(this).val();
                    var staff_name = $(this).attr('name');
                    option_string += '<option value="'+staff_id+'" >'+staff_name+'</option>';
                });

                var html = '<div class="quann_staff input-area">'+
                        '<div class="fr"><a href="javascript:void(0);"><span class="glyphicon glyphicon-remove-sign remove"></span> </a> </div>'+
                        '<label for="Quann Staff Name">Quann Staff Name <span class="required">*</span></label>'+
                        '<select  name="user_id[]" required="">'+
                        option_string+
                        '</select>'+
                        '</div>';
                $('#quann_staffs').append(html);
                var SelectedValues = [];
                $("#quann_staffs").find("select")
                        .each(function(index, elem) {
                            var selVal = $(elem).find('option:selected').val();
                            if (selVal != "") SelectedValues.push(selVal);
                        })
                        .end()
                        .each(function(index, elem) {
                            $(elem).find('option[value!=""]')
                                    .filter(function(index, option) {
                                        return SelectedValues.indexOf(option.value) !== -1;
                                    })
                                    .hide()
                                    .end()
                                    .filter(function(index, option) {
                                        return SelectedValues.indexOf(option.value) == -1;
                                    }).show()
                                    .end()

                        })
                        .end();
            });

            $(document).on('change','.quann_staff select',function(event){
                event.preventDefault();
                var SelectedValues = [];
                $("#quann_staffs").find("select")
                        .each(function(index, elem) {
                            var selVal = $(elem).find('option:selected').val();
                            if (selVal != "") SelectedValues.push(selVal);
                        })
                        .end()
                        .each(function(index, elem) {
                            $(elem).find('option[value!=""]')
                                    .filter(function(index, option) {
                                        return SelectedValues.indexOf(option.value) !== -1;
                                    })
                                    .hide()
                                    .end()
                                    .filter(function(index, option) {
                                        return SelectedValues.indexOf(option.value) == -1;
                                    }).show()
                                    .end()

                        })
                        .end();
            });

            $(document).on('click','div.quann_staff .remove',function(event){
                event.preventDefault();
                $(this).closest('div.quann_staff').remove();

                var SelectedValues = [];
                $("#quann_staffs").find("select")
                        .each(function(index, elem) {
                            var selVal = $(elem).find('option:selected').val();
                            if (selVal != "") SelectedValues.push(selVal);
                        })
                        .end()
                        .each(function(index, elem) {
                            $(elem).find('option[value!=""]')
                                    .filter(function(index, option) {
                                        return SelectedValues.indexOf(option.value) !== -1;
                                    })
                                    .hide()
                                    .end()
                                    .filter(function(index, option) {
                                        return SelectedValues.indexOf(option.value) == -1;
                                    }).show()
                                    .end()

                        })
                        .end();

            });


            var client_div ='<div class="contact input-area">'+
                    '<div class="fr"><a href="javascript:void(0)"><span class="glyphicon glyphicon-remove-sign remove"></span> </a> </div>'+
                    '<div class="input-area">'+
                    '<label for="Client Contact Person">Client Contact Person <span class="required">*</span></label>'+
                    '<select class="contact_persons"  name="contact_persons[]" required>'+

                    '</select>'+
                    '</div>'+
                    '<div class="input-area">'+
                    '<label for="designation" class="designation">Client Designation <span class="required">*</span></label><input id="designation[]" type="text" class="designation" name="designation[]" value="" autofocus>'+
                    '</div>'+
                    '<div class="input-area">'+
                    '<div class="four columns"><label for="date">Client Contact Details <span class="required">*</span></label></div>'+
                    '<div class="five columns"><input placeholder="Email" type="email" class="input-icon icon-email email" id="email[]" name="email[]" value="" autofocus></div>'+
                    '<div class="three columns"><input placeholder="Cell Number" type="text" class="input-icon icon-phone phone" id="phone[]" name="phone[]" value="" autofocus/></div>'+
                    '</div>'+
                    '</div>';



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
                        var options = '<option value="">' + "--Select Person--" + '</option>';
                        for (var row in rows) {
                            options += '<option value="' + rows[row].id + '">' + rows[row].name + '</option>';
                            contact_details += '<input type="hidden" name="ID" value="'+rows[row].id+'" data-name="'+ rows[row].name +'" />';
                            contact_details += '<input  type="hidden"  name="contact_name['+rows[row].id+']" value="'+rows[row].name+'"/>';
                            contact_details += '<input  type="hidden" name="contact_designation['+rows[row].id+']" value="'+rows[row].designation+'"/>';
                            contact_details += '<input  type="hidden" name="contact_email['+rows[row].id+']" value="'+rows[row].email+'"/>';
                            contact_details += '<input  type="hidden" name="contact_phone['+rows[row].id+']" value="'+rows[row].phone+'"/>';
                        }
                        $('.contact_details').html(contact_details);
                        $("#contacts").html(client_div);
                        $(".contact_persons").html(options);
                    },
                    error: function (jqXHR, textStatus, errorThrown) { },
                    processData: false,
                });
            });


            $(document).on('change','.contact_persons',function(event){
                var SelectedValues=[];
                $("#contacts").find("select")
                        .each(function(index, elem) {
                            var selVal = $(elem).find('option:selected').val();
                            if (selVal != "") SelectedValues.push(selVal);
                        })
                        .end();
                var contact_id = $(this).val();
                if( !contact_id || contact_id == 'NA' || contact_id == '' || typeof contact_id == 'undefined' ){
                    $(this).closest('.contact').find('input.designation[type="text"]').val('');
                    $(this).closest('.contact').find('input.email[type="email"]').val('');
                    $(this).closest('.contact').find('input.phone[type="text"]').val('');

                    $("#contacts").find("select")
                            .each(function(index, elem) {
                                $(elem).find('option[value!=""]')
                                        .filter(function(index, option) {
                                            return SelectedValues.indexOf(option.value) !== -1;
                                        })
                                        .hide()
                                        .end()
                                        .filter(function(index, option) {
                                            return SelectedValues.indexOf(option.value) == -1;
                                        }).show()
                                        .end()

                            })
                            .end();
                    return;
                }

                var designation =  $('input[name="contact_designation['+contact_id+']"]').first().val();
                var email =  $('input[name="contact_email['+contact_id+']"]').first().val();
                var phone =  $('input[name="contact_phone['+contact_id+']"]').first().val();
                console.log('Designation:'+designation+',Email:'+email+',Phone:'+phone);
                //$(this).closest('.contact').find('input.name[type="text"]').val(name);
                $(this).closest('.contact').find('input.designation[type="text"]').val(designation);
                $(this).closest('.contact').find('input.email[type="email"]').val(email);
                $(this).closest('.contact').find('input.phone[type="text"]').val(phone);

                $("#contacts").find("select")
                        .each(function(index, elem) {
                            $(elem).find('option[value!=""]')
                                    .filter(function(index, option) {
                                        return SelectedValues.indexOf(option.value) !== -1;
                                    }).hide()
                                    .end()
                                    .filter(function(index, option) {
                                        return SelectedValues.indexOf(option.value) == -1;
                                    }).show()
                                    .end()

                        })
                        .end();
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




            $(document).on('click','.remove',function(event){
                $(this).closest('.contact').remove();
                var SelectedValues = [];
                $("#contacts").find("select")
                        .each(function(index, elem) {
                            var selVal = $(elem).find('option:selected').val();
                            if (selVal != "") SelectedValues.push(selVal);
                        })
                        .end()
                        .each(function(index, elem) {
                            $(elem).find('option[value!=""]')
                                    .filter(function(index, option) {
                                        return SelectedValues.indexOf(option.value) !== -1;
                                    })
                                    .hide()
                                    .end()
                                    .filter(function(index, option) {
                                        return SelectedValues.indexOf(option.value) == -1;
                                    }).show()
                                    .end()

                        })
                        .end();
            });

            var counter = 0;

            $('.add_contact_person').on('click',function(event){
                event.preventDefault();
                var SelectedValues = [];
                $("#contacts").find("select")
                        .each(function(index, elem) {
                            var selVal = $(elem).find('option:selected').val();
                            if (selVal != "") SelectedValues.push(selVal);
                        })
                        .end();
                var ContactPersonIDs = [];
                var $optionString = '<option value="">'+'--Select Person--'+'</option>';
                $('.contact_details').find('input[name="ID"]').each(function(i,v){
                    var PersonName = $(v).attr('data-name');
                    $optionString += '<option value="'+ v.value+'">'+ PersonName +'</option>';
                    ContactPersonIDs.push(v.value);
                });

                if( ContactPersonIDs.length === 0 ){
                    console.log("Zero.");
                    JconfirmAlert("Notification","No contact person for the selected company.");
                    return;
                }
                if( SelectedValues.length === ContactPersonIDs.length ){
                    console.log("Equeals.");
                    JconfirmAlert("Notification",'There is no contact person left to add');
                    return;
                }


                var client_div = '<div class="contact input-area">'+
                        '<div class="fr"><a href="javascript:void(0)"><span class="glyphicon glyphicon-remove-sign remove"></span> </a> </div>'+
                        '<div class="input-area">'+
                        '<label for="Client Contact Person">Client Contact Person <span class="required">*</span></label>'+
                        '<select class="contact_persons"  name="contact_persons[]" required>'+
                        $optionString+
                        '</select>'+
                        '</div>'+
                        '<div class="input-area">'+
                        '<label for="designation" class="designation">Client Designation <span class="required">*</span></label><input id="designation[]" type="text" class="designation" name="designation[]" value="" autofocus>'+
                        '</div>'+
                        '<div class="input-area">'+
                        '<div class="four columns"><label for="date">Client Contact Details <span class="required">*</span></label></div>'+
                        '<div class="five columns"><input placeholder="Email" type="email" class="input-icon icon-email email" id="email[]" name="email[]" value="" autofocus></div>'+
                        '<div class="three columns"><input placeholder="Cell Number" type="text" class="input-icon icon-phone phone" id="phone[]" name="phone[]" value="" autofocus/></div>'+
                        '</div>'+
                        '</div>';
                $("#contacts").append(client_div);

                $("#contacts").find("select")
                        .each(function(index, elem) {
                            $(elem).find('option[value!=""]')
                                    .filter(function(index, option) {
                                        return SelectedValues.indexOf(option.value) !== -1;
                                    })
                                    .hide()
                                    .end()
                                    .filter(function(index, option) {
                                        return SelectedValues.indexOf(option.value) == -1;
                                    }).show()
                                    .end()

                        })
                        .end();


                counter++;
            });

        });
</script>
@endsection