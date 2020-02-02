
$(function(){

    $('[data-toggle="tooltip"]').tooltip();

    var dp = $('.only-time').datepicker({
        onlyTimepicker: true,
        timepicker: true
    }).data('datepicker');

    $("#menu").mmenu({
        navbars: [{
            height: 1,
            content: [
	            '<div class="mob-icon"><img src="images/logo-slate.png" /></div>',
            ]
        }, true],
        "extensions": [
	        "pagedim-black", "listview-huge", "fx-panels-slide-100", "fx-listitems-slide", "fx-menu-slide", "border-full"
        ]
    });

    /*
    $('.nav #menu_id>li').hover(function () {
        $(this).addClass('current-menu-item'); // add class when mouseover happen
    }, function () {
        $(this).removeClass('current-menu-item'); // remove class when mouseout happen
    }); */
 });