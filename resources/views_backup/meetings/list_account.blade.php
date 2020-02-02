@extends('layouts.app')
@section('title','Staff List Page')
@section('content')

    <div class="section content-area">
        <form action="{{route('meetings.listAccount')}}" method="GET">
            <div class="input-area">
                <div class="four columns"><input placeholder="Select Year" type="text" name="year" data-min-view="years" data-view="years" data-date-format="yyyy" data-language="en" class="datepicker-here input-icon icon-calender"></div>
                <div class="four columns"><input placeholder="Select Month" type="text" name="month" data-min-view="months" data-view="months" data-date-format="MM" data-language="en" class="datepicker-here input-icon icon-calender"></div>
                <div class="four columns"> <select name="criteria" required>
                        <option value="ALL">All</option>
                        <option value="Companies">Companies</option>
                        <option value="Projects">Projects</option>
                        <option value="upcomingMeetings">Upcoming meetings</option>
                        <option value="CompleteMeetings">Complete meetings</option>
                        </select></div>
                </div>
            <input type="submit" class="fr sbtn" value="View List">
            <div class="clearfix"></div>
            <div class="input-area">
                <input type="hidden" name="prev_criteria" id="prev_criteria" value="{{ isset($criteria) ? $criteria :"" }}" />
                <input type="hidden" name="prev_year" id="prev_year" value="{{ isset($year) ? $year:"" }}"/>
                <input type="hidden" name="prev_month" id="prev_month" value="{{ isset($month) ? $month:""}}"/>
            </div>
            <div class="row">
                <div class="six columns box"><h3>Clients Companies</h3>

                    <div class="big-number">{{ isset($userCompanyCount) ? $userCompanyCount:0 }}</div> <div class="list-btn"><a href="#" id="btn-company">list</a></div>
                </div>
                <div class="six columns box"><h3>Projects</h3>
                    <div class="big-number">{{ isset($userProjectCount) ? $userCompanyCount:0 }}</div> <div class="list-btn"><a href="#" id="btn-project" >list</a></div>
                </div>
            </div>
            <div class="row">
                <div class="six columns box"><h3>Upcoming Meetings</h3>
                    <div class="big-number">{{ isset($upComingMeeting) ? $upComingMeeting:0 }}</div> <div class="list-btn"><a href="#" id="btn-upcoming" >list</a></div>
                </div>
                <div class="six columns box"><h3>Completed Meetings</h3>
                    <div class="big-number">{{ isset($completedMeetingCount) ? $completedMeetingCount:0 }}</div> <div class="list-btn"><a href="#" id="btn-completed" >list</a></div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('FooterAdditionalCodes')
    <script type="text/javascript" src="{{asset("js/jquery-confirm.min.js")}}"></script>
    <script type="text/javascript" src="{{asset("js/JconfirmFunctions.js")}}"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            function export_company_list(){
                var criteria = $("#prev_criteria").val();
                var year = $("#prev_year").val();
                var month = $("#prev_month").val();
                window.location.href = "/meetings/exportCompanyList?criteria="+criteria+"&year="+year+"&month="+month;
            }

            function export_project_list(){
                var criteria = $("#prev_criteria").val();
                var year = $("#prev_year").val();
                var month = $("#prev_month").val();
                window.location.href = "/meetings/exportProjectList?criteria="+criteria+"&year="+year+"&month="+month;
            }

            function export_upcoming_meeting_list(){
                var criteria = $("#prev_criteria").val();
                var year = $("#prev_year").val();
                var month = $("#prev_month").val();
                window.location.href = "/meetings/exportUpcomingMeetingList?criteria="+criteria+"&year="+year+"&month="+month;
            }

            function export_completed_meeting_list(){
                var criteria = $("#prev_criteria").val();
                var year = $("#prev_year").val();
                var month = $("#prev_month").val();
                window.location.href = "/meetings/exportCompletedMeetingList?criteria="+criteria+"&year="+year+"&month="+month;
            }

            function onContentReady(){

            }
            $(document).on('click','#btn-upcoming',function(){

                $.ajax({
                    type: "GET",
                    url: "/meetings/listUpcomingMeetingByUser",
                    async: false,
                    dataType: 'JSON',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {

                        if( response.status == "OK" ){
                            var upcoming_eeting_table_html = "";
                            var table_header_html = "";
                            for(var index in response.Header){
                                table_header_html += '<th>'+response.Header[index]+'</th>';
                            }

                            if(table_header_html != ""){
                                table_header_html = '<thead><tr>'+table_header_html+'</tr></thead>';
                            }

                            for(var index in response.data){
                                upcoming_eeting_table_html += '<tr><td>'+response.data[index].Project+'</td><td>'+response.data[index].Staff+'</td><td>'+response.data[index].CompanyName+'</td><td>'+response.data[index].Date+'</td><td>'+response.data[index].Time+'</td></tr>';
                            }

                            if( upcoming_eeting_table_html != "" ){
                                upcoming_eeting_table_html = '<table class="table table-bordered table-responsive">'+table_header_html+'<tbody>'+upcoming_eeting_table_html+'</tbody></table>';
                            }
                            JconfirmCustomized('Upcoming Meeting List',upcoming_eeting_table_html,export_upcoming_meeting_list,null);
                        }else{
                            JconfirmAlert('Operation Status.',response.message);
                        }

                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        JconfirmAlert('Operation Status.','Failed to Load Upcoming Meeting List due to '+textStatus);
                    },
                    processData: false
                });
            });


            $(document).on('click','#btn-completed',function(){

                $.ajax({
                    type: "GET",
                    url: "/meetings/listCompletedMeetingByUser",
                    async: false,
                    dataType: 'JSON',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {

                        if( response.status == "OK" ){
                            var completed_meeting_table_html = "";
                            var table_header_html = "";
                            for(var index in response.Header){
                                table_header_html += '<th>'+response.Header[index]+'</th>';
                            }

                            if(table_header_html != ""){
                                table_header_html = '<thead><tr>'+table_header_html+'</tr></thead>';
                            }

                            for(var index in response.data){
                                completed_meeting_table_html += '<tr><td>'+response.data[index].Project+'</td><td>'+response.data[index].Staff+'</td><td>'+response.data[index].CompanyName+'</td><td>'+response.data[index].Date+'</td><td>'+response.data[index].Time+'</td></tr>';
                            }

                            if( completed_meeting_table_html != "" ){
                                completed_meeting_table_html = '<table class="table table-bordered table-responsive">'+table_header_html+'<tbody>'+completed_meeting_table_html+'</tbody></table>';
                            }
                            JconfirmCustomized('Completed Meeting List',completed_meeting_table_html,export_completed_meeting_list,null);
                        }else{
                            JconfirmAlert('Operation Status.',response.message);
                        }

                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        JconfirmAlert('Operation Status.','Failed to Load Completed Meeting List due to '+textStatus);
                    },
                    processData: false
                });
            });

            $(document).on('click','#btn-company',function(){
                var criteria = $("#prev_criteria").val();
                var year = $("#prev_year").val();
                var month = $("#prev_month").val();
                $.ajax({
                    type: "GET",
                    url: "/meetings/listCompanyByUser",
                    data:{criteria:criteria,year:year,month:month},
                    async: false,
                    dataType: 'JSON',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if( response.status == "OK" ){
                            console.log(response.data.length);
                            var company_table_html = "";
                            for(var index in response.data){
                                //console.log(response[index].company_name);
                                company_table_html += '<tr><td>'+response.data[index].company_name+'</td><td>'+response.data[index].user+'</td></tr>';
                            }

                            if(company_table_html != ""){
                                company_table_html = '<table class="table table-bordered table-responsive"><tbody>'+company_table_html+'</tbody></table>';
                            }
                            JconfirmCustomized('Client Company List',company_table_html,export_company_list,null);
                        }else{
                            JconfirmAlert('Operation Status.',response.message);
                        }

                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        JconfirmAlert('Operation Status.','Failed to load Company List due to '+ textStatus);
                    },
                    processData: true
                });
            });


            $(document).on('click','#btn-project',function(){
                var criteria = $("#prev_criteria").val();
                var year = $("#prev_year").val();
                var month = $("#prev_month").val();

                $.ajax({
                    type: "GET",
                    url: "/meetings/listProjectByUser",
                    async: false,
                    data:{criteria:criteria,year:year,month:month},
                    dataType: 'JSON',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if( response.status == "OK" ){
                            var project_table_html = "";
                            var table_header_html = "";
                            for(var index in response.Header){
                                table_header_html += '<th>'+response.Header[index]+'</th>';
                            }
                            if(table_header_html != ""){
                                table_header_html = '<thead><tr>'+table_header_html+'</tr></thead>';
                            }
                            for(var index in response.data){
                                project_table_html += '<tr><td>'+response.data[index].Project+'</td><td>'+response.data[index].Staff+'</td><td>'+response.data[index].CompanyName+'</td><td>'+response.data[index].Status+'</td></tr>';
                            }

                            if(project_table_html != ""){
                               // console.log(table_header_html);
                                project_table_html = '<table class="table table-bordered table-responsive">'+table_header_html+'<tbody>'+project_table_html+'</tbody></table>';
                            }
                            JconfirmCustomized('Project List',project_table_html,export_project_list,null);
                        }else{
                            JconfirmAlert('Operation Status.',response.message);
                        }

                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        JconfirmAlert('Operation Status.','Failed to Load Company List due to '+textStatus);
                    },
                    processData: true
                });
            });





        });
    </script>

@endsection