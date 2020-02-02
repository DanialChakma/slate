@extends('layouts.app')
@section('title','Meeting Status Change')
@section('content')

                <form action="#">

                    <input type="hidden" value="{{$meeting->id}}" name="meeting_id" id="meeting_id"/>
                    <div class="input-area">

                            <label for="select">Meeting Title</label>
                            <input  id="title" type="text"  name="title" value="{{ (isset($meeting) && !empty($meeting->title))? $meeting->title:"" }}">

                    </div>
                    <div class="input-area">

                            <label for="Client Company">Client Company</label>
                            <input  id="company_name" type="text"  name="company_name" value="{{ (isset($meeting) && !empty($meeting->clientCompany->company_name))? $meeting->clientCompany->company_name:"" }}">

                    </div>
                    <div class="input-area">

                            <label for="select">Department</label>
                            <input id="title" type="text"  name="title" value="{{ (isset($meeting) && !empty($meeting->project->id))? $meeting->project->department->name:"" }}">

                    </div>
                    <div class="input-area">

                            <label for="select">Project</label>
                            <input id="project_id" type="text"  name="project_id" value="{{ (isset($meeting) && !empty($meeting->project_id))? $meeting->project->name:"" }}">

                    </div>
                    <div class="input-area {{ $errors->has('TimeAndDate') ? ' has-error' : '' }}">

                            <div class="five columns">
                                <label for="date">Time &amp; Date</label>
                            </div>
                            <div class="three columns">
                                <input value="{{ date("d-m-Y",strtotime($meeting->start_time)) }}" name="date" data-range="true" data-multiple-dates-separator=" - " data-language="en" class="datepicker-here input-icon icon-calender" type="text" readonly>
                            </div>
                            <div class="two columns">
                                <input value="{{ date("h:i",strtotime($meeting->start_time)) }}" name="start_time" id="start_time" class="only-time input-icon icon-time" type="text">
                            </div>
                            <div class="two columns">
                                <input value="{{ date("h:i",strtotime($meeting->end_time)) }}" name="end_time" id="end_time" class="only-time input-icon icon-time" type="text">
                            </div>

                    </div>
                </form>

                <div class="section big-btn-area">
                    <a href="{{ route('meetings.meetingsOfFieldStuffs')}}" class="big-btn">Go back</a>
                    <button id="complete" name="complete"  class="big-btn" id="btn-com">Complete</button>
                    <button id="reschedule" name="reschedule" class="big-btn yellowbtn">reschedule</button>
                    <button id="cancel" name="cancel" class="big-btn redbtn">cancel</button>
                </div>


@endsection

@section('FooterAdditionalCodes')
    <style type="text/css">
        .only-time { z-index: 999999990 !important;  }
        .datepicker-here{ z-index: 999999990 !important;  }
    </style>
    <script type="text/javascript" src="{{asset("js/jquery-confirm.min.js")}}"></script>
    <script type="text/javascript" src="{{asset("js/JconfirmFunctions.js")}}"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            var IS_FIRST_TYPED_FINISHED = true;
            var TYPING_TIMER;
            var DONE_TYPING_INTERVAL = 3000; // in ms unit

            function finished_typing(eventObject){
                var remarks = $(eventObject).val().trim();
                if(remarks && remarks !="" ){
                    $("#info").find(".alert-info").text("")
                              .end()
                              .hide();
                }

                var is = $('input[type="radio"]:checked').val();
                if( is && is == "notsend" ){
                        if( !remarks || typeof remarks == 'undefined' || remarks == '' ){
                            $("#info").show().find(".alert-info").text("Please,type the reason of not sending survey.");
                        }
                }

            }
            $(document).on('keydown','textarea#remarks',function(event) {
                if (TYPING_TIMER) clearInterval(TYPING_TIMER);

            });
            $(document).on('keyup','textarea#remarks',function(event) {
                if (TYPING_TIMER) clearInterval(TYPING_TIMER);
                TYPING_TIMER = setInterval(finished_typing, DONE_TYPING_INTERVAL, this);
            });

            $(document).on('change','input[type="radio"]',function(event) {

                var value = $(this).val();
                if( value == "notsend" ){
                    $("#remarks_box").show();
                }else{
                    $("#remarks_box").hide();
                }

                $("#info").hide();
            });

            $(document).on('click','input.datepicker-here,input.only-time',function(){
               $(document).find(".jconfirm").css({'z-index':0});
            });

            function onOpenReshceduleConfirm(){
                var dp = $('.datepicker-here').datepicker().data('datepicker');
                var dpt = $('.only-time').datepicker({
                                onlyTimepicker: true,
                                timepicker: true
                            }).data('datepicker');

                $(document).find(".jconfirm button").removeClass('btn');
            }

            function reschedule_yes(){
                var meeting_id = $("#meeting_id").val();
                var date = $('#date').val().trim();
                var starttime = $('#starttime').val().trim();
                var endtime = $('#endtime').val().trim();
                var remarks = $('#remarks').val().trim();
                var validation_messages = [];
                if( !date || typeof date == 'undefined' || date == '' ){
                    validation_messages.push("Re-shedule date must not be empty.");
                }

                if( !starttime || typeof starttime == 'undefined' || starttime == '' ){
                    validation_messages.push("Start time must not be empty.");
                }

                if( !endtime || typeof endtime == 'undefined' || endtime == '' ){
                    validation_messages.push("End time must not be empty.");
                }
                if( !remarks || typeof remarks == 'undefined' || remarks == '' ){
                    validation_messages.push("Remarks must not be empty.");
                }

                if( starttime && endtime ){
                    var $startDate = Date.parse(date+" "+starttime);
                    var $endDate = Date.parse(date+" "+endtime);
                    if( isNaN($startDate) || isNaN($endDate) ){
                        validation_messages.push("Incorrect Date time.");
                    }else{
                        if( $startDate > $endDate ){
                            validation_messages.push("Start time must precede end time.");
                        }
                    }
                }

                if( validation_messages.length > 0 ){
                    for(var i=0;i<validation_messages.length;i++)validation_messages[i]=(i+1)+'. '+validation_messages[i];
                    var msg  = validation_messages.join("<br/>");
                    $("#info").show().find(".alert-info").html(msg);
                    return false;
                }

                $.ajax({
                    type: "GET",
                    url: "/meetings/meetingRescheduleAction",
                    data:"meeting="+meeting_id+"&date="+date+"&starttime="+starttime+"&endtime="+endtime+'&remarks='+remarks,
                    async: false,
                    dataType: 'JSON',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        //console.log(response);
                        JconfirmAlert('Meeting Re-schedule Status',response.msg);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        JconfirmAlert('Meeting Re-schedule Status','Failed to update status.');
                    },
                    processData: false,
                });

            }

            function cancel_confirm_yes(){
                var meeting_id = $("#meeting_id").val().trim();
                var remarks = $("#remarks").val().trim();
                if(!remarks || typeof remarks == 'undefined' || remarks == '' ){
                    $("#info").show().find(".alert-info").html('Please,Enter reason of cancelling.');
                    return false;
                }

                $.ajax({
                    type: "GET",
                    url: "/meetings/meetingCancelAction",
                    data:"meeting="+meeting_id+"&remarks="+remarks,
                    async: false,
                    dataType: 'JSON',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        //console.log(response);
                        JconfirmAlert('Meeting Cancel Status',response.msg);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        JconfirmAlert('Meeting Cancel Status','Failed to update status.');
                    },
                    processData: false,
                });
            }

            function complete_yes(){

                var meeting_id = $("#meeting_id").val();
                var is = $('input[type="radio"]:checked').val();
                if( !is || typeof is == 'undefined' || is == '' ){
                    $("#info").show().find(".alert-info").text("Please,Select any one from above list.");
                    return false;
                }

                var remarks = $("#remarks").val().trim();

                if( is && is == "notsend" ){
                    if( !remarks || typeof remarks == 'undefined' || remarks == '' ){
                        $("#info").show().find(".alert-info").text("Please,type the reason of not sending survey.");
                        return false;
                    }
                }
                console.log(remarks);
               // return true;
                $.ajax({
                    type: "GET",
                    url: "/meetings/meetingCompleteAction",
                    data:"meeting="+meeting_id+"&survey="+is+( is == "notsend" ? "&remarks="+remarks:""),
                    async: false,
                    dataType: 'JSON',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        //console.log(response);
                        JconfirmAlert('Meeting Completion Status',response.msg);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        JconfirmAlert('Meeting Completion Status','Failed to update status.');
                    },
                    processData: false,
                });
            }

            $("#complete").on('click',function(){
                var content =   '<div class="row">'+
                                    '<div class="col-md-3"></div>'+
                                    '<div class="col-md-6">' +
                                        '<input type="radio" value="send" name="servey_send" id="servey_send_1" style="opacity:0;"/>'
                                    +'<label for="servey_send_1">Complete & Send Survey</label>'
                                    +'</div>'
                                    +'<div class="col-md-3"></div>'
                                +'</div>'
                                + '<div class="row">'+
                                        '<div class="col-md-3"></div>'+
                                        '<div class="col-md-6">' +
                                            '<input type="radio" value="notsend" name="servey_send" id="servey_send_2" style="opacity:0;" />'
                                            +'<label for="servey_send_2">Complete & Do Not Send Survey</label>'
                                        +'</div>'
                                        +'<div class="col-md-3"></div>'
                                +'</div>'
                                + '<br/><div class="row" id="remarks_box" style="display: none">'+
                                        '<div class="col-md-2"></div>'+
                                        '<div class="col-md-8">' +
                                            '<textarea class="form-control" placeholder="Reason for not sending survey"  value="" name="remarks" id="remarks" />'
                                        +'</div>'
                                        +'<div class="col-md-2"></div>'
                                +'</div>'
                                + '<br/><div class="row" id="info" style="display: none">'+
                                        '<div class="col-md-2"></div>'+
                                        '<div class="col-md-8 alert-info">' +

                                        +'</div>'
                                        +'<div class="col-md-2"></div>'
                                +'</div>';
                JconfirmComplete('Confirmation',content,complete_yes,onOpenReshceduleConfirm);
            })

            $("#reschedule").on('click',function(){

                var content =   '<div class="row">'+
                                    '<div class="col-md-3"></div>'+
                                    '<div class="col-md-6">' +
                                        '<input placeholder="Reschedule date"  id="date" name="date" data-range="false" data-multiple-dates-separator=" - " data-language="en" class="datepicker-here input-icon icon-calender" type="text"/>'

                                    +'</div>'
                                    +'<div class="col-md-3"></div>'
                                +'</div>'
                                +'<div class="row">'+
                                        '<div class="col-md-3"></div>'+
                                        '<div class="col-md-6">' +
                                            '<input placeholder="Start time" class="only-time input-icon icon-time"  type="text" value="" name="starttime" id="starttime" />'

                                        +'</div>'
                                        +'<div class="col-md-3"></div>'
                                +'</div>'
                                +'<div class="row">'+
                                            '<div class="col-md-3"></div>'+
                                            '<div class="col-md-6">' +
                                                '<input placeholder="End time" class="only-time input-icon icon-time" type="text"  name="endtime" id="endtime" />'
                                            +'</div>'
                                            +'<div class="col-md-3"></div>'
                                +'</div>'
                                +'<div class="row" id="remarks_box">'+
                                                '<div class="col-md-2"></div>'+
                                                '<div class="col-md-8">' +
                                                '<textarea placeholder="Reason for Re-schedule"  name="remarks" id="remarks" />'
                                                +'</div>'
                                                +'<div class="col-md-2"></div>'
                                +'</div>'
                                +'<br/><div class="row" id="info" style="display: none">'+
                                                    '<div class="col-md-2"></div>'+
                                                    '<div class="col-md-8 alert-info">' +

                                                    +'</div>'
                                                    +'<div class="col-md-2"></div>'
                                +'</div>';

                JconfirmReschedule('Confirmation',content,reschedule_yes,onOpenReshceduleConfirm);
            })
            $("#cancel").on('click',function(){

                var content = '<div class="row" id="remarks_box">'+
                                        '<div class="col-xs-1"></div>'+
                                        '<div class="col-xs-10 text-center">' +
                                                '<textarea placeholder="Reason for Cancelling" name="remarks" id="remarks" />'
                                        +'</div>'
                                        +'<div class="col-xs-1"></div>'
                                +'</div>'
                                +'<div class="row" id="info" style="display: none">'+
                                        '<div class="col-md-2"></div>'+
                                        '<div class="col-md-8 alert-info"></div>'
                                        +'<div class="col-md-2"></div>'
                                +'</div>';
                JconfirmCancel('Confirmation',content,cancel_confirm_yes,onOpenReshceduleConfirm);
            })
        });
    </script>
@endsection