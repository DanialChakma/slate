@extends('layouts.app')

@php
$title = "Calendar";
@endphp

@section('title',$title)

@section('HeaderAdditionalCodes')
    <link href="{{ asset('css/calendarStyle.css') }}" rel="stylesheet">
@endsection
@section('content')
<h1>{{ $title }}</h1>
<div class="top-btn"><a href="{{ route('meetings.create') }}" class="big-btn fr">Add Schedule</a></div>

    <div class="section">
        <!--<div class="header">
            <h1>August 2017</h1>
            <div class="right"></div>
            <div class="left"></div>
        </div>-->
        <div id="calendar"></div>
        <!--<div id="notification"></div>-->
    </div>

@endsection
@section('FooterAdditionalCodes')
<script type="text/javascript" src="{{asset('js/moment-with-locales.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/moment-timezone-with-data.min.js')}}"></script>
<script type="text/javascript">
    !function () {



        /*var today = moment().tz("Asia/Dhaka").format();
         *
         * Format : 2017-08-12T03:52:22+06:00
         * */
        var today = moment().tz("Asia/Dhaka");

        /*var today = moment().local();*/
        /*today = today.local();*/


        /*  console.log(today.month()); // return 7 for August

         return false;*/

        var counter = 0;
        var day_counter = 0;
        var first_day = '';
        var last_day = '';
        var response_data;


        function Calendar(selector, events) {
            this.el = document.querySelector(selector);
            this.events = events;

            this.current = moment().tz("Asia/Dhaka").date(1);

            /*console.log(this.current);
             return false;*/

            this.draw();
            var current = document.querySelector('.today');
            if (current) {
                var self = this;
                window.setTimeout(function () {
                    self.openDay(current);
                }, 500);
            }
        }

        Calendar.prototype.draw = function () {
            //Create Header
            this.drawHeader();
            this.drawMonth();
            if($("#notification").length == 0) {
                $('#calendar').append('<div id="notification"></div>');
            }


            //this.drawNotificationDiv();

            //Draw Month


            /*this.drawLegend();*/
        }

        Calendar.prototype.drawHeader = function () {
            var self = this;
            if (!this.header) {
                //Create the header elements
                this.header = createElement('div', 'header');
                this.header.className = 'header';

                /*g.id = 'notification';*/


                this.title = createElement('h1');

                var right = createElement('div', 'right');
                right.addEventListener('click', function () {
                    self.nextMonth();
                });

                var left = createElement('div', 'left');
                left.addEventListener('click', function () {
                    self.prevMonth();
                });

                //Append the Elements
                this.header.appendChild(this.title);
                this.header.appendChild(right);
                this.header.appendChild(left);
                this.el.appendChild(this.header);
            }

            this.title.innerHTML = this.current.format('MMMM YYYY');
        }

        Calendar.prototype.drawMonth = function () {

            var self = this;

            if (this.month) {
                this.oldMonth = this.month;
                this.oldMonth.className = 'month out ' + (self.next ? 'next' : 'prev');
                this.oldMonth.addEventListener('webkitAnimationEnd', function () {
                    self.oldMonth.parentNode.removeChild(self.oldMonth);
                    self.month = createElement('div', 'month');
                    self.backFill();
                    self.currentMonth();
                    self.fowardFill();
                    self.el.appendChild(self.month);
                    window.setTimeout(function () {
                        self.month.className = 'month in ' + (self.next ? 'next' : 'prev');
                    }, 16);
                });
            } else {
                this.month = createElement('div', 'month');
                this.el.appendChild(this.month);
                this.backFill();
                this.currentMonth();
                this.fowardFill();
                this.month.className = 'month new';
            }

        };

        Calendar.prototype.drawNotificationDiv = function () {
            var self = this;
            this.notification = createElement('div');
            this.notification.setAttribute("id", "notification");
        };




        Calendar.prototype.backFill = function () {
            var clone = this.current.clone();
            var dayOfWeek = clone.day();

            if (!dayOfWeek) {
                return;
            }

            clone.subtract('days', dayOfWeek + 1);

            for (var i = dayOfWeek; i > 0; i--) {
                this.drawDay(clone.add('days', 1));
            }
        }

        Calendar.prototype.fowardFill = function () {
            var clone = this.current.clone().add('months', 1).subtract('days', 1);
            var dayOfWeek = clone.day();

            if (dayOfWeek === 6) {
                return;
            }

            for (var i = dayOfWeek; i < 6; i++) {
                this.drawDay(clone.add('days', 1));
            }
        }

        Calendar.prototype.currentMonth = function () {
            var clone = this.current.clone();

            while (clone.month() === this.current.month()) {
                this.drawDay(clone);
                clone.add('days', 1);
            }
        }

        Calendar.prototype.getWeek = function (day) {
            if (!this.week || day.day() === 0) {
                this.week = createElement('div', 'week');
                this.month.appendChild(this.week);
            }
        }

        Calendar.prototype.drawDay = function (day) {


            //console.log(this.current.month());
            var first_day_moment = {};
            var self = this;
            this.getWeek(day);

            var events = createElement('div', 'day-events');

            var current_date = day.format("YYYY-MM-DD");
            if (day_counter === 0 && day.month() === this.current.month()) {
                /*first_day = day.format("YYYY-MM-DD");
                 first_day_moment = moment(first_day);
                 last_day = first_day_moment.add('days', 34);
                 last_day = last_day.format("YYYY-MM-DD");*/

                var begin = day.format("YYYY-MM-01");
                var end = day.format("YYYY-MM-") + day.daysInMonth();


                var meeting_numbers = [];
                $.ajax({
                    type: "GET",
                    url: "meetings/getMeetingsPerDay?start_date=" + begin + "&end_date=" + end,
                    async: false,
                    /*dataType: 'JSON',*/
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    // data: {"department_id":department_id},
                    success: function (response) {

                        response_data = JSON.parse(response);

                    }
                });


                var dataSet = [[]];

                $.ajax({
                    type: "GET",
                    url: "meetings/getMeetings?start_date=" + begin + "&end_date=" + end,
                    async: false,
                    /*dataType: 'JSON',*/
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    // data: {"department_id":department_id},
                    success: function (response) {

                        dataSet = JSON.parse(response);


                        $.each(dataSet, function (i, val) {
                            val.date = moment(val.date);

                        });

			self.events = [];
                        $.each(dataSet, function (i, val) {
                            self.events.push(val);
                        });
			
			if( self.next == true || self.next== false  ){
                            self.FirstOpenDay();
                        }

                    }
                });

            }

            if(response_data !== undefined) {

                if (response_data[day_counter].hasOwnProperty("meeting_count")) {
                    if (response_data[day_counter].date === current_date) {
                        var span_no_of_meetings = createElement('span', '', response_data[day_counter].meeting_count);
                        events.appendChild(span_no_of_meetings);
                    }
                }

            }




            //Outer Day
            var outer = createElement('div', this.getDayClass(day));
            //console.log(outer);

            outer.addEventListener('click', function () {
                self.openDay(this);
            });

            // $('[class="day other"]').prop('onclick',null).off('click');

            /*if (outer.indexOf("day other") === -1) {

             }*/

            //Day Name
            var name = createElement('div', 'day-name', day.format('ddd'));


            //Day Number
            var number = createElement('div', 'day-number', day.format('DD'));


            if (counter < 7) {
                outer.appendChild(name);
            }


            if (day.month() === this.current.month()) {

                if(response_data !== undefined) {
                    if (response_data[day_counter].date === current_date) {
                        if (response_data[day_counter].meeting_count) {
                            var has_event = createElement('div', 'has-event');
                            outer.appendChild(has_event);
                        } else {
                            var has_no_event = createElement('div', 'has-no-event');
                            outer.appendChild(has_no_event);
                        }
                    }
                }

            } else {
                var has_no_event = createElement('div', 'has-no-event');
                outer.appendChild(has_no_event);


            }


            if (day.month() === this.current.month()) {
                outer.appendChild(number);
                outer.appendChild(events);
            }


            this.week.appendChild(outer);

            if (day.month() === this.current.month() && day.date() !== day.daysInMonth()) {
                day_counter++;
            }

            counter++;



        }


        Calendar.prototype.getDayClass = function (day) {
            classes = ['day'];
            if (day.month() !== this.current.month()) {
                classes.push('other');
            } else if (today.isSame(day, 'day')) {
                classes.push('today');
            }
            return classes.join(' ');
        }

	 Calendar.prototype.FirstOpenDay = function(){
            var details, arrow;

            var currentOpened = document.querySelector('.details');


                //Close the open events on differnt week row
                //currentOpened && currentOpened.parentNode.removeChild(currentOpened);
                if (currentOpened) {
                    currentOpened.addEventListener('webkitAnimationEnd', function () {
                        currentOpened.parentNode.removeChild(currentOpened);
                    });
                    currentOpened.addEventListener('oanimationend', function () {
                        currentOpened.parentNode.removeChild(currentOpened);
                    });
                    currentOpened.addEventListener('msAnimationEnd', function () {
                        currentOpened.parentNode.removeChild(currentOpened);
                    });
                    currentOpened.addEventListener('animationend', function () {
                        currentOpened.parentNode.removeChild(currentOpened);
                    });
                    currentOpened.className = 'details out';
                }

                //Create the Details Container
                details = createElement('div', 'details in');


                //Create the arrow
                var arrow = createElement('div', 'arrow');

                document.getElementById("notification").appendChild(arrow);
                document.getElementById("notification").appendChild(details);

                this.renderMonthEvents(this.events, details);

            /*arrow.style.left = el.offsetLeft - el.parentNode.offsetLeft - 27 + 'px';*/
            arrow.style.left = '-8px';
        }

        Calendar.prototype.openDay = function (el) {
            var details, arrow;
            var dayNumber = +el.querySelectorAll('.day-number')[0].innerText || +el.querySelectorAll('.day-number')[0].textContent;
            var day = this.current.clone().date(dayNumber);

            var currentOpened = document.querySelector('.details');

            //Check to see if there is an open detais box on the current row
            if (currentOpened && currentOpened.parentNode === el.parentNode) {
                details = currentOpened;
                arrow = document.querySelector('.arrow');
            } else {
                //Close the open events on differnt week row
                //currentOpened && currentOpened.parentNode.removeChild(currentOpened);
                if (currentOpened) {
                    currentOpened.addEventListener('webkitAnimationEnd', function () {
                        currentOpened.parentNode.removeChild(currentOpened);
                    });
                    currentOpened.addEventListener('oanimationend', function () {
                        currentOpened.parentNode.removeChild(currentOpened);
                    });
                    currentOpened.addEventListener('msAnimationEnd', function () {
                        currentOpened.parentNode.removeChild(currentOpened);
                    });
                    currentOpened.addEventListener('animationend', function () {
                        currentOpened.parentNode.removeChild(currentOpened);
                    });
                    currentOpened.className = 'details out';
                }

                //Create the Details Container
                details = createElement('div', 'details in');


                //Create the arrow
                var arrow = createElement('div', 'arrow');

                document.getElementById("notification").appendChild(arrow);
                document.getElementById("notification").appendChild(details);

            }

            var todaysEvents = this.events.reduce(function (memo, ev) {
                if (ev.date.isSame(day, 'day')) {
                    memo.push(ev);
                }
                return memo;
            }, []);

            this.renderEvents(todaysEvents, details, day);

            /*arrow.style.left = el.offsetLeft - el.parentNode.offsetLeft - 27 + 'px';*/
            arrow.style.left = '-8px';
        }

         Calendar.prototype.renderMonthEvents = function(MonthEvents,ele){
            //Remove any events in the current details element
            var currentWrapper = ele.querySelector('.events');
            var wrapper = createElement('div', 'events in ' + (currentWrapper ? ' new' : '')+' mCustomScrollbar');
            // wrapper.setAttribute('data-mcs-theme','rounded-dark');
            /*console.log(current_day);
             current_day.format("Do MMMM, YYYY"); // "Sunday, February 14th 2010, 3:25:50 pm"*/
            var date_now = createElement('div', 'current-date', 'Selected Month');
            wrapper.appendChild(date_now);

            MonthEvents.forEach(function (ev) {
                var div = createElement('div', 'event');
                var anchorDiv = createElement('a', '');
                var square = createElement('div', 'event-category ' + ev.color); // notification er color
                var eventAnchor = createElement('h5', 'name-span', ' ' + ev.eventName);
                // var date = createElement('span','desc-date',ev.date);
                var desc = createElement('span', 'desc-span', ev.desc);
                var time = createElement('span', 'desc-time', ev.time);
                var location = createElement('span', 'desc-location glyphicon glyphicon-map-marker', ev.location);
                var icon_span = document.createElement('span');
                icon_span.className = 'icon-span';
                /*
                var anchor = document.createElement('a');
                anchor.className = 'anchor-image';
                anchor.href = "{{ route('meetings.changeStatus',['id'=>''])}}"+'/'+ev.id;
                icon_span.appendChild(anchor);
                eventAnchor.href = anchor.href;
                var icon_img = document.createElement('img');
                icon_img.className = 'arrow-icon';
                icon_img.src = "images/right-arrow.png";
                anchor.appendChild(icon_img); */

                anchorDiv.href = "{{ route('meetings.changeStatus',['id'=>''])}}"+'/'+ev.id;
                anchorDiv.appendChild(square);
                anchorDiv.appendChild(eventAnchor);
                anchorDiv.appendChild(desc);
                anchorDiv.appendChild(time);
                anchorDiv.appendChild(location);
               // anchorDiv.appendChild(icon_span);
                /*
                div.appendChild(square);
                div.appendChild(eventAnchor);
                div.appendChild(desc);
                div.appendChild(time);
                div.appendChild(location);
                div.appendChild(icon_span);
                div.appendChild(anchorDiv);
                */
                div.appendChild(anchorDiv);
                wrapper.appendChild(div);
            });

            if (!MonthEvents.length) {
                var div = createElement('div', 'event empty');
                var span = createElement('span', '', 'No Schedules');

                div.appendChild(span);
                wrapper.appendChild(div);
            }

            if (currentWrapper) {
                currentWrapper.className = 'events out';
                currentWrapper.addEventListener('webkitAnimationEnd', function () {
                    currentWrapper.parentNode.removeChild(currentWrapper);
                    ele.appendChild(wrapper);
                });
                currentWrapper.addEventListener('oanimationend', function () {
                    currentWrapper.parentNode.removeChild(currentWrapper);
                    ele.appendChild(wrapper);
                });
                currentWrapper.addEventListener('msAnimationEnd', function () {
                    currentWrapper.parentNode.removeChild(currentWrapper);
                    ele.appendChild(wrapper);
                });
                currentWrapper.addEventListener('animationend', function () {
                    currentWrapper.parentNode.removeChild(currentWrapper);
                    ele.appendChild(wrapper);
                });
            } else {
                ele.appendChild(wrapper);
            }

            if($(".mCustomScrollbar").length){
                $.mCustomScrollbar.defaults.scrollButtons.enable=true;
                $(".mCustomScrollbar").mCustomScrollbar({theme:'rounded-dark'});
                console.log("MScroll Found in Render");
            }else{
                setTimeout(function(){
                    $.mCustomScrollbar.defaults.scrollButtons.enable=true;
                    $(".mCustomScrollbar").mCustomScrollbar({theme:'rounded-dark'});
                    if($(".mCustomScrollbar").length){
                        console.log("Found Here after one second delay in Renders");
                    }
                },1000)
            }
        }

        Calendar.prototype.renderEvents = function (events, ele, current_day) {

            //Remove any events in the current details element
            var currentWrapper = ele.querySelector('.events');
            var wrapper = createElement('div', 'events in ' + (currentWrapper ? ' new' : '')+' mCustomScrollbar');
           // wrapper.setAttribute('data-mcs-theme','rounded-dark');
            /*console.log(current_day);
             current_day.format("Do MMMM, YYYY"); // "Sunday, February 14th 2010, 3:25:50 pm"*/
            var date_now = createElement('div', 'current-date', current_day.format("Do MMMM, YYYY"));
            wrapper.appendChild(date_now);

           events.forEach(function (ev) {
                var div = createElement('div', 'event');
                var anchorDiv = createElement('a', '');
                var square = createElement('div', 'event-category ' + ev.color); // notification er color
                var eventAnchor = createElement('h5', 'name-span', ' ' + ev.eventName);
		       // var date = createElement('span','desc-date',ev.date);
                var desc = createElement('span', 'desc-span', ev.desc);
                var time = createElement('span', 'desc-time', ev.time);
                var location = createElement('span', 'desc-location', ev.location);
                var icon_span = document.createElement('span');
                icon_span.className = 'icon-span';

                {{--var anchor = document.createElement('a');--}}
                {{--anchor.className = 'anchor-image';--}}
                {{--//anchor.href = "https://google.com";--}}
                {{--anchor.href = "{{ route('meetings.changeStatus',['id'=>''])}}"+'/'+ev.id;--}}
                {{--icon_span.appendChild(anchor);--}}
                {{--eventAnchor.href = anchor.href;--}}
                {{--var icon_img = document.createElement('img');--}}
                {{--icon_img.className = 'arrow-icon';--}}
                {{--icon_img.src = "/images/right-arrow.png";--}}
                {{--anchor.appendChild(icon_img);--}}
               anchorDiv.href = "{{ route('meetings.changeStatus',['id'=>''])}}"+'/'+ev.id;
               anchorDiv.appendChild(square);
               anchorDiv.appendChild(eventAnchor);
               anchorDiv.appendChild(desc);
               //anchorDiv.appendChild(date);
               anchorDiv.appendChild(time);
               anchorDiv.appendChild(location);
//                div.appendChild(square);
//                div.appendChild(eventAnchor);
//
//                div.appendChild(desc);
//		//div.appendChild(date);
//                div.appendChild(time);
//                div.appendChild(location);
//                div.appendChild(icon_span);
               div.appendChild(anchorDiv);
                wrapper.appendChild(div);
            });

            if (!events.length) {
                var div = createElement('div', 'event empty');
                var span = createElement('span', '', 'No Schedules');

                div.appendChild(span);
                wrapper.appendChild(div);
            }

            if (currentWrapper) {
                currentWrapper.className = 'events out';
                currentWrapper.addEventListener('webkitAnimationEnd', function () {
                    currentWrapper.parentNode.removeChild(currentWrapper);
                    ele.appendChild(wrapper);
                });
                currentWrapper.addEventListener('oanimationend', function () {
                    currentWrapper.parentNode.removeChild(currentWrapper);
                    ele.appendChild(wrapper);
                });
                currentWrapper.addEventListener('msAnimationEnd', function () {
                    currentWrapper.parentNode.removeChild(currentWrapper);
                    ele.appendChild(wrapper);
                });
                currentWrapper.addEventListener('animationend', function () {
                    currentWrapper.parentNode.removeChild(currentWrapper);
                    ele.appendChild(wrapper);
                });
            } else {
                ele.appendChild(wrapper);
            }

	        if($(".mCustomScrollbar").length){
            	$.mCustomScrollbar.defaults.scrollButtons.enable=true;
            	$(".mCustomScrollbar").mCustomScrollbar({theme:'rounded-dark'});
            	            	        }else{
            setTimeout(function(){
                $.mCustomScrollbar.defaults.scrollButtons.enable=true;
                $(".mCustomScrollbar").mCustomScrollbar({theme:'rounded-dark'});
                if($(".mCustomScrollbar").length){
                    console.log("Found Here after one second delay in Render Events");
                }
            },1000)
        }
        }

        Calendar.prototype.drawLegend = function () {
            var legend = createElement('div', 'legend');
            var calendars = this.events.map(function (e) {
                return e.calendar + '|' + e.color;
            }).reduce(function (memo, e) {
                if (memo.indexOf(e) === -1) {
                    memo.push(e);
                }
                return memo;
            }, []).forEach(function (e) {
                var parts = e.split('|');
                var entry = createElement('span', 'entry ' + parts[1], parts[0]);
                legend.appendChild(entry);
            });
            this.el.appendChild(legend);
        }


        Calendar.prototype.nextMonth = function () {
            counter = 0;
            day_counter = 0;
            this.current.add('months', 1);
            /*   var hgh = this.current;
             console.log(hgh.month()); // month from 0-11
             console.log(hgh.day()); // returns the current day*/
            this.next = true;
            this.draw();
        }

        Calendar.prototype.prevMonth = function () {
            counter = 0;
            day_counter = 0;
            this.current.subtract('months', 1);
            this.next = false;
            this.draw();
        }

        window.Calendar = Calendar;

        function createElement(tagName, className, innerText) {
            var ele = document.createElement(tagName);
            if (className) {
                ele.className = className;
            }
            if (innerText) {
                ele.innderText = ele.textContent = innerText;
            }
            return ele;
        }
    }();

    !function () {

        var data = [];

        var calendar = new Calendar('#calendar', data);

        /*var elm = document.getElementsByClassName('other');
         for(i=0; i<elm.length; i++) {
         elm[i].onclick = null;
         elm[i].removeAttribute('onclick');
         //elm[i].addEventListener('change', alert('test!'), false);
         }*/


    }();


    $(document).ready(function(){

       if($(".events").length){
            $.mCustomScrollbar.defaults.scrollButtons.enable=true;
            $(".mCustomScrollbar").mCustomScrollbar({theme:'rounded-dark'});
            console.log("MScroll Found");
        }else{
            setTimeout(function(){
                $.mCustomScrollbar.defaults.scrollButtons.enable=true;
                $(".mCustomScrollbar").mCustomScrollbar({theme:'rounded-dark'});
                if($(".mCustomScrollbar").length){
                    console.log("Found Here after one second delay");
                }
            },1000)
        } 
    });

</script>

@endsection