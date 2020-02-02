$(function(){

    $('#location').on("keypress", function (event) {
        if (event.which === 13) {
            codeAddress();
            e.preventDefault();
        }
    });

});