@extends('layouts.app')
@section('title','Add Survey')
@section('HeaderAdditionalCodes')
    <link href="{{ asset('css/anypicker-all.min.css') }}" rel="stylesheet">
@endsection
@section('content')

        {{--@section("h1Text","Survey Report / Project Name: MSS1 Field Visit")--}}

            <div class="row">
                <div class="six columns box"><h3>Clients Companies</h3>
                    <div class="big-number">{{ isset($clientCompanyCount) ? $clientCompanyCount:0 }}</div> <div class="list-btn">
                        {{--<a href="#" id="btn-com">list</a>--}}
                    </div>
                </div>
                <div class="six columns box">
                    <h3>Projects</h3>
                    <div class="big-number">{{ isset($clientProjectCount) ? $clientProjectCount:0 }}</div> <div class="list-btn">
                        {{--<a href="#" id="btn-com">list</a>--}}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="four columns box">
                    <h3>Sent Surveys</h3>
                    <div class="big-number">{{$surveySendCount}}</div>
                </div>
                <div class="four columns box">
                    <h3>Total Responded</h3>
                    <div class="big-number">{{$surveyRespondedCount}}</div>
                </div>
                <div class="four columns box">
                    <h3>Not Responded/ Completed</h3>
                    <div class="big-number">{{$surveyNotRespondedPlusCompletedWithoutSurveyCount}}</div>
                </div>
            </div>
            <div class="input-area">
                <label for="Project Name">Project Name</label>
                <select name="project" id="project">
                    @foreach($projects as $project)
                        <option value="{{$project->id}}">{{$project->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="input-area">
                <label for="Field Staff">Field Staff</label>
                <select name="field_staff" id="field_staff">
                    <option>--Select Staff--</option>
                </select>
            </div>
            <div class="input-area">
                <div class="row">
                    <div class="five columns">
                        <label for="Time Date">Start Date-Time</label>
                    </div>
                    <div class="four columns">
                        <input type="text" id="start_date" name="start_date" class="input-icon icon-calender" >
                        {{--<input type="text" id="date_range" name="date_range" data-range="false" data-multiple-dates-separator=" - " data-language="en" data-time-format="hh:ii" data-position="left top" class="datepicker-here input-icon icon-calender">--}}
                    </div>
                    <div class="three columns">
                        <input name="start_time" id="start_time" class="input-icon icon-time" type="text">
                    </div>
                </div>
            </div>
            <div class="input-area">
                <div class="row">
                    <div class="five columns">
                        <label for="Time Date">End Date-Time</label>
                    </div>
                    <div class="four columns">
                        <input type="text" id="end_date" name="end_date" class="input-icon icon-calender">
                    </div>
                    <div class="three columns">
                        <input type="text" name="end_time" id="end_time" class="input-icon icon-time" >
                    </div>
                </div>
            </div>
            <div class="input-area">
                <button class="button fr" id="show_report">Show Report</button>
            </div>
            <div class="input-area" id="questions_report">

            </div>
            {{--<div class="section each-q-report">--}}
                {{--<div class="eight columns">--}}
                    {{--<div class="q-number">Q.1</div>--}}
                    {{--<div class="q-title">Has Quann given you a good understanding of your infrastructure?<span>Rating</span> </div>--}}
                    {{--<div class="a-review">--}}
                        {{--<ul>--}}
                            {{--<li>5<br><strong>57%</strong></li>--}}
                            {{--<li>4<br><strong>22%</strong></li>--}}
                            {{--<li>3<br><strong>15%</strong></li>--}}
                            {{--<li>2<br><strong>45%</strong></li>--}}
                            {{--<li>1<br><strong>98%</strong></li>--}}
                        {{--</ul>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="four columns"><canvas id="myChart"></canvas></div>--}}
            {{--</div>--}}

            {{--<div class="section each-q-report">--}}
                {{--<div class="eight columns">--}}
                    {{--<div class="q-number">Q.2</div>--}}
                    {{--<div class="q-title">Has Quann given you a good understanding of your infrastructure?<span>Yes/No</span> </div>--}}
                    {{--<div class="a-review">--}}
                        {{--<ul>--}}
                            {{--<li>Yes<br><strong>57%</strong></li>--}}
                            {{--<li>No<br><strong>22%</strong></li>--}}

                        {{--</ul>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="four columns"><canvas id="myChart2"></canvas></div>--}}
            {{--</div>--}}
            {{--<div class="clearfix"></div>--}}
            {{--<input type="submit" class="fr sbtn" value="Export as Excel">--}}


@endsection

@section('FooterAdditionalCodes')
<script src="{{asset("js/Chart.bundle.min.js")}}" type="text/javascript"></script>
<script src="{{asset("js/Chart.PieceLabel.min.js")}}" type="text/javascript"></script>
<script src="{{ asset('js/anypicker.min.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('js/JconfirmFunctions.js')}}"></script>
<script type="text/javascript">
    function hexToRgb(hex) {
        var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        return result ? {
            r: parseInt(result[1], 16),
            g: parseInt(result[2], 16),
            b: parseInt(result[3], 16)
        } : null;
    }
    function getRandomColor () {
        var hex = Math.floor(Math.random() * 0xFFFFFF);
        return "#" + ("000000" + hex.toString(16)).substr(-6);
    }

    $(document).ready(function(){

            $("#start_date").AnyPicker(
                {
                    mode: "datetime",
                    dateTimeFormat: "MMM dd, yyyy"
                });
            $("#start_time").AnyPicker(
                {
                    mode: "datetime",
                    dateTimeFormat: "hh:mm AA"
                });
        $("#end_date").AnyPicker(
                {
                    mode: "datetime",
                    dateTimeFormat: "MMM dd, yyyy"
                });
        $("#end_time").AnyPicker(
                {
                    mode: "datetime",
                    dateTimeFormat: "hh:mm AA"
                });

        $(document).on('change','#project',function(){
            var project_id = $(this).val();
            $.ajax({
                type: "GET",
                url: "/surveys/getProjectStaffs?ProjectID="+project_id,
                async: false,
                dataType: 'JSON',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (rows) {
                    var options = '<option value="">'+"--Select Staff--"+'</option>';
                    for(var row in rows){
                        options += '<option value="'+rows[row].id+'">'+rows[row].name+'</option>';
                    }
                    $("#field_staff").html(options);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    // genericError(jqXHR, textStatus, errorThrown);
                },
                processData: false,
            });
        });


        $(document).on('click','button#show_report',function(){
            var field_staff = $('#field_staff').val();
            var project_id = $('#project').val();
            var start_date = $('#start_date').val();
            var start_time = $('#start_time').val();

            var end_date = $('#end_date').val();
            var end_time = $('#end_time').val();
	    
            if(!project_id || project_id == '' || typeof project_id == 'undefined'){
                JconfirmAlert('Notification','Please,Select One Project.');
                return;
            }

            if(!field_staff || field_staff == '' || typeof field_staff == 'undefined'){
                JconfirmAlert('Notification','Please,Select One Field Staff');
                return;
            }

            if(!start_date || start_date == '' || typeof start_date == 'undefined'){
                JconfirmAlert('Notification','Please,Select Start Date');
                return;
            }

            if(!end_date || end_date == '' || typeof end_date == 'undefined' ){
                JconfirmAlert('Notification','Please,Select End Date');
                return;
            }

            start_date = start_date + ' '+start_time;
            end_date = end_date + ' '+end_time;
           $.ajax({
                type: "GET",
                url: "/surveys/getProjectReport?ProjectID="+project_id+'&FieldStaff='+field_staff+'&start_date='+start_date+'&end_date='+end_date,
                async: false,
                dataType: 'JSON',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (rows) {
                    var q_c = 1;
                    $("#questions_report").html("");
                    if( rows.length > 0 ){
                        for(var index in rows ){

                            var question = rows[index].question;
                            var options = rows[index].options;
                            var backgroundColors = [];
                            var chartLabels = [];
                            var datasets = [];
                            var totalCount = 0;
                            for(var indx in options){
                                totalCount  += parseInt(options[indx].count);
                                chartLabels.push(options[indx].body);
                                datasets.push(options[indx].count);
                                backgroundColors.push(getRandomColor());
                            }
                            var option_html = "";
                            for(var indx in options){
                                var percent = totalCount > 0 ? (100*parseFloat(options[indx].count/totalCount)).toFixed(2) : 0;
                                if( option_html == "" ) option_html = '<li>'+options[indx].key+'.'+options[indx].body+'&nbsp;<strong>'+ percent +'%</strong></li>';
                                else{
                                    option_html += '<br><li>'+options[indx].key+'.'+options[indx].body+'&nbsp;<strong>'+ percent +'%</strong></li>';
                                }
                            }
                            option_html ='<ul>'+option_html+'</ul>';
                            var report_question = '<div class="section each-q-report">'+
                                    '<div class="eight columns">'+
                                    '<div class="q-number">Q.'+(q_c)+'</div>'+
                                    '<div class="q-title">'+question+'</div>'+
                                    '<div class="a-review">'+
                                    option_html+
                                    '</div>'+
                                    '</div>'+
                                    '<div class="four columns"><canvas id="myChart_'+q_c+'"></canvas></div>'+
                                    '</div>';

                            $("#questions_report").append(report_question);

                            var ctx = document.getElementById("myChart_"+q_c);
                            var data = {
                                labels: chartLabels,
                                datasets: [{
                                    backgroundColor:backgroundColors,
                                    data: datasets
                                }]
                            };


                            var myDoughnutChart = new Chart(ctx, {
                                type: 'doughnut',
                                data: data,
                                options: {
                                    pieceLabel: {
                                        overlap: true,
                                        render: 'percentage',
                                        fontColor: function (data) {
                                            var rgb = hexToRgb(data.dataset.backgroundColor[data.index]);
                                            if( !rgb ){
                                                rgb = {
                                                    r:220,
                                                    g:20,
                                                    b:60
                                                };
                                            }
                                            var threshold = 140;
                                            var luminance = 0.299 * rgb.r + 0.587 * rgb.g + 0.114 * rgb.b;
                                            return luminance > threshold ? 'black' : 'white';
                                        },
                                        precision: 2
                                    },
                                    responsive: true,
                                    animation: {
                                        animateScale: true,
                                        animateRotate: true
                                    },
                                    legend: {
                                        position: 'bottom',

                                    },
                                }
                            });



                            q_c++;
                        }
                    }else{
                        var empty_message = '<div class="text-center">No report found.</div>';
                        $("#questions_report").html(empty_message);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    // genericError(jqXHR, textStatus, errorThrown);
                },
                processData: false,
            });

        })
    });

</script>
@endsection