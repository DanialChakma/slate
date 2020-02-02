@extends('layouts.app')
@section('title','Add Meeting')
@section('HeaderAdditionalCodes')
    <style>
        .required{
            color:orangered;
        }
    </style>
    <link href="{{ asset('css/anypicker-all.min.css') }}" rel="stylesheet">
    <script>
        var parentClasses = ["a_schedule"];
    </script>
@endsection
@section('content')

                <form  method="POST" action="{{ route('meetings.store') }}">
                    {{ csrf_field() }}

                    <div class="input-area {{ $errors->has('department') ? ' has-error' : '' }}">
                        <label for="select">Department <span class="required">*</span></label>
                        <select name="department" id="department" >
                            @foreach($departments as $department)
                                <option value="{{$department->id}}">{{$department->name}}</option>
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
                        <select name="project_id" id="project_id" required>
                            <option value="">--Select Project--</option>
                            @foreach($projects as $project)
                                <option value="{{$project->id}}">{{$project->name}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('project_id'))
                            <span class="help-block">
                                    <strong>{{ $errors->first('project_id') }}</strong>
                                </span>
                        @endif
                    </div>

                    <div class="input-area {{ $errors->has('title') ? ' has-error' : '' }}">
                            <label for="select">Meeting Title <span class="required">*</span></label>
                            <input id="title" type="text"  name="title" value="{{ old('title') }}" required>

                            @if ($errors->has('title'))
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
                            <input id="date" name="date" class="input-icon icon-calender" type="text" placeholder="Date" required>
                            @if ($errors->has('date'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('date') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="two columns">
                            <input name="start_time" id="start_time" class="input-icon icon-time" type="text" placeholder="Start Time" required>
                            @if ($errors->has('start_time'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('start_time') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="two columns">
                            <input name="end_time" id="end_time" class="input-icon icon-time" type="text" placeholder="End Time" required>
                            @if ($errors->has('end_time'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('end_time') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="input-area {{ $errors->has('location') ? ' has-error' : '' }}">
                        <label for="Location">Location <span class="required">*</span></label>
                        <input  name="location" id="location" type="text" placeholder="Search Meeting Place.">
                    </div>
                    <div class="input-area">
                        <div class="google-map" id="map_canvas" style="position: relative; overflow: hidden;">
                        </div>
                    </div>




                    <div class="input-area {{ $errors->has('client_company_id') ? ' has-error' : '' }}">

                            <label for="Client Company">Client Company <span class="required">*</span></label>
                            <select  id="client_company_id" name="client_company_id" required>
                                <option value="">--Select Company--</option>
                                @if( isset($clientCompanies) && count($clientCompanies) > 0)
                                    @foreach($clientCompanies as $company)
                                        <option value="{{$company->id}}">{{$company->company_name}}</option>
                                    @endforeach
                                @endif
                            </select>
                            @if ($errors->has('client_company_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('client_company_id') }}</strong>
                                </span>
                            @endif
                        <div class="contact_details"></div>
                    </div>
                    <div id="contacts" class="contacts input-area">
                        <div id="contact" class="contact input-area">
                            <div class="fr"><a href="javascript:void(0);"><span class="glyphicon glyphicon-remove-sign remove"></span> </a> </div>
                            <div class="input-area">
                                <label for="Client Contact Person">Client Contact Person <span class="required">*</span></label>
                                <select class="contact_persons"  name="contact_persons[]" required>
                                    <option>--Select Person--</option>
                                </select>
                            </div>
                            <div class="input-area">
                                <label for="designation" class="designation">Client Designation <span class="required">*</span></label><input id="designation[]" type="text" class="designation" name="designation[]" value="" autofocus>
                            </div>
                            <div class="input-area">
                                <div class="four columns"><label for="date">Client Contact Details <span class="required">*</span></label></div>
                                <div ><input placeholder="Email" type="email" class="input-icon icon-email" id="email[]" name="email[]" value="" autofocus></div>
                                <div ><input placeholder="Cell Number" type="text" class="input-icon icon-phone" id="phone[]" name="phone[]" value="" autofocus/></div>
                            </div>
                        </div>
                    </div>
                    <div class="section"><a href="#" class="add_contact_person fr"> + Add New Contact Person</a></div>
        <div id="quann_staffs_hidden" style="display:none">
                        @if(isset($quannStaffs) && count($quannStaffs)> 0 )
                            @foreach($quannStaffs as $staff)
                                <input type="hidden" value="{{$staff->id}}" name="{{$staff->name}}"/>
                            @endforeach
                        @endif
                    </div>              
<div id="quann_staffs" class="quann_staffs input-area">

                        <div class="quann_staff input-area {{ $errors->has('user_id') ? ' has-error' : '' }}">
                            <div class="fr"><a href="javascript:void(0);"><span class="glyphicon glyphicon-remove-sign remove"></span> </a> </div>
			    <div class="input-area">	
                            <label for="Quann Staff Name">Quann Staff Name <span class="required">*</span></label>
                            <select  name="user_id[]" required="">
                                <option value="">--Select Staff Name--</option>
                                @if(isset($quannStaffs) && count($quannStaffs)> 0 )
                                    @foreach($quannStaffs as $staff)
                                        <option value="{{$staff->id}}">{{$staff->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                            @if ($errors->has('user_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('user_id') }}</strong>
                                </span>
                            @endif
                            </div>
                        </div>
                    </div>

                    <div class="section"><a href="#" class="add_quann_staff fr"> + Add New Quann Staff</a></div>

                    <div class="input-area {{ $errors->has('remarks') ? ' has-error' : '' }}">
                       <label for="select">Remarks</label>
                        <textarea id="remarks"  name="remarks">{{ old('remarks') }}</textarea>
                        @if ($errors->has('title'))
                            <span class="help-block">
                                    <strong>{{ $errors->first('title') }}</strong>
                                </span>
                        @endif
                    </div>
 <div class="input-area text-right">
				<a href="{{ route('meetings') }}" class="big-btn">Back</a>
                                <button type="submit" onclick="return check_front_end_validation();">
                                    Create Meeting
                                </button>
                         
</div>
                </form>


    <script type="text/javascript">
        var geocoder;
        var map;
        var marker;
        var infowindow;
        var clientPosition;

        function failureCallback(location){
            geocoder = !geocoder ? new google.maps.Geocoder():geocoder;
            if(map){
                map.setCenter(location);
                map.setZoom(16);
                marker = new google.maps.Marker({
                    map: map,
                    draggable: true,
                    position: location
                });

                infowindow = !infowindow ? new google.maps.InfoWindow({
                    size: new google.maps.Size(150, 50)
                }): infowindow;

                google.maps.event.addListener(marker, 'dragend', function() {
                    geocodePosition(marker.getPosition());
                });

                geocoder.geocode({
                    latLng: location
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

                google.maps.event.addListener(marker, 'click', function() {
                    if (marker.formatted_address) {
                        $("#location").val(marker.formatted_address);
                        infowindow.setContent(marker.formatted_address + "<br>coordinates: " + marker.getPosition().toUrlValue(6));
                    }
                    infowindow.open(map, marker);
                });
            }
        }

        function initialize() {
            geocoder = new google.maps.Geocoder();
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    clientPosition = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                },function(failure) {
                    $.getJSON('https://ipinfo.io/geo', function(response) {
                        var loc = response.loc.split(',');
                        clientPosition = new google.maps.LatLng(loc[0], loc[1]);
                        failureCallback(clientPosition);
                        //console.log(loc);
                    });

                });
            }

            var latlng = clientPosition && typeof clientPosition !== 'undefined' ? clientPosition : new google.maps.LatLng(-34.397, 150.644);
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

        // google.maps.event.addDomListener(window, "load", initialize);
    </script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAxbgv9LR7QeRR-k2H0nrNlltUhNBvpjnw&callback=initialize" async defer></script>
@endsection

@section('FooterAdditionalCodes')
    <script type="text/javascript" src="{{asset('js/JconfirmFunctions.js')}}"></script>
    <script src="{{ asset('js/anypicker.min.js') }}" type="text/javascript"></script>
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
                var title = '<i class="fa fa-warning" style="font-size:30px;color:red"></i>'+' Notification';
                JconfirmAlert(title,"Please,Select at least one contact person.");
                return false;
            }
        }

        $(document).ready(function(){


            $('#date').AnyPicker(
                    {
                        mode: "datetime",
                        dateTimeFormat: "dd MMM, yyyy"
                    });

            var StartTime,EndTime;
            function OnInitStart(){
                StartTime = this;
            }
            function OnInitEnd(){
                EndTime = this;
            }
            function EndBeforeShow(){
                var start_time = $("#start_time").val();
                if( start_time && start_time != "" ){
                   var date =  new Date();
                    var date_string = date.getDate()+'/'+date.getMonth()+'/'+date.getYear()+' '+start_time;
                    var stratTimeObject = new Date(date_string);
                    var miniutes = stratTimeObject.getMinutes();
                    stratTimeObject.setMinutes(miniutes+30);
                    EndTime.setSelectedDate(stratTimeObject);
                    return true;
                }
            }
            $("#start_time").AnyPicker(
                    {
                        mode: "datetime",
                        dateTimeFormat: "hh:mm AA",
                        showComponentLabel: true,
                        onInit:OnInitStart,
                        /*
                        inputChangeEvent: "onChange",
                        onChange: function(iRow, iComp, oSelectedValues)
                        {
                            var stratTimeObject = new Date(oSelectedValues.date);
                            var miniutes = stratTimeObject.getMinutes();
                            stratTimeObject.setMinutes(miniutes+30);
                            EndTime.setSelectedDate(stratTimeObject);
                        } */
                    });

                $("#end_time").AnyPicker(
                        {
                            mode: "datetime",
                            dateTimeFormat: "hh:mm AA",
                            showComponentLabel: true,
                            onInit:OnInitEnd,
                            onBeforeShowPicker:EndBeforeShow
                        });



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
				'<div class="input-area">'+
                                '<label for="Quann Staff Name">Quann Staff Name <span class="required">*</span></label>'+
                                '<select  name="user_id[]" required="">'+
                                        option_string+
                                '</select>'+
                                '</div>'+
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
                                '<div ><input placeholder="Email" type="email" class="input-icon icon-email email" id="email[]" name="email[]" value="" autofocus></div>'+
                                '<div ><input placeholder="Cell Number" type="text" class="input-icon icon-phone phone" id="phone[]" name="phone[]" value="" autofocus/></div>'+
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
                 JconfirmAlert("Notification","No contact person for the selected company.");
                    return;
                }
                if( SelectedValues.length === ContactPersonIDs.length ){
                    console.log("Equeals.");
                    JconfirmAlert("Notification",'There is no contact person left to add');
                    return;
                }


                var client_div ='<div class="contact input-area">'+
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