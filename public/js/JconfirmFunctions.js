
function JconfirmAlertWithReload(title,content,close_call_back){
    $.alert({
        theme: 'material',
        useBootstrap: false,
        columnClass: 'col-md-6 col-md-offset-3 text-center',
        // boxWidth:'60%',
        containerFluid: true, // this will add 'container-fluid' instead of 'container'
        title: title,
        content: content,
        buttons: {
            'Close': {
                text: 'Close',
                keys: ['enter'],
                btnClass: 'btn-blue',
                action: close_call_back
            }

        }
    });
}


function JconfirmAlert(title, content) {
    $.alert({
        theme: 'material',
        useBootstrap: false,

        closeIcon: true,
        columnClass: 'col-md-6 col-md-offset-3 text-center',
        // boxWidth:'60%',
        containerFluid: true, // this will add 'container-fluid' instead of 'container' 
        title: title,
        content: content,
        buttons: {

            'Close': {
                text: 'Close',
                keys: ['enter'],
                btnClass: 'btn-blue',
                action: function () {

                }
            }

        }

    });
}


function JconfirmXLarge(title, content, yes_function) {
    $.confirm({
        columnClass: 'col-md-12',
        boxWidth: '60%',
        containerFluid: true, // this will add 'container-fluid' instead of 'container' 
        title: title,
        content: content,
        buttons: {

            'Cancel': {
                text: 'Cancel',
                keys: ['shift'],
                btnClass: 'btn-red',
                action: function () {

                }
            },
            'Yes': {
                text: 'Save',
                keys: ['enter'],
                btnClass: 'btn-blue',
                action: yes_function
            }
        }

    });
}

function JconfirmLarge(title, content, yes_function) {
    $.confirm({
        columnClass: 'col-md-8 col-md-offset-2',
        boxWidth: '60%',
        containerFluid: true, // this will add 'container-fluid' instead of 'container' 
        title: title,
        content: content,
        buttons: {

            'Cancel': {
                text: 'Cancel',
                keys: [],
                btnClass: 'btn-red',
                action: function () {

                }
            },
            'Yes': {
                text: 'Save',
                keys: [],
                btnClass: 'btn-blue',
                action: yes_function
            }
        }

    });
}

function JconfirmMine(title, content, yes_function) {
    $.confirm({
        columnClass: 'col-md-4 col-md-offset-4 col-xs-4 col-xs-offset-4',
        boxWidth:'60%',
        containerFluid: true, // this will add 'container-fluid' instead of 'container' 
        title: title,
        content: content,
        buttons: {

            'Cancel': {
                text: 'Cancel',
                keys: ['shift'],
                btnClass: 'btn-red',
                action: function () {

                }
            },
            'Yes': {
                text: 'Save',
                keys: ['enter'],
                btnClass: 'btn-blue',
                action: yes_function
            }
        }

    });
}

function JconfirmDefault(title, content, yes_function,onContentReady) {
    $.confirm({
        theme: 'material',
        useBootstrap: false,
        columnClass: 'col-md-6 col-md-offset-3 text-center',
       // columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1 text-center',
        //boxWidth:'60%',
        containerFluid: true, // this will add 'container-fluid' instead of 'container'
        title: title,
        content: content,
        onContentReady:onContentReady,
       // onOpen: onOpenFn,
       // onClose:onCloseFn,
        buttons: {

            'Cancel': {
                text: 'Cancel',
                keys: [],
                btnClass: 'btn-red',
                action: function () {

                }
            },
            'Yes': {
                text: 'Yes',
                keys: [],
                btnClass: 'btn-blue',
                action: yes_function
            }
        }

    });
}

function JconfirmComplete(title, content, yes_function,onContentReady) {
    $.confirm({
        theme: 'material',
        closeIcon: true,
        useBootstrap: false,
        columnClass: 'col-md-6 col-md-offset-3 text-center',
        //boxWidth:'60%',
        containerFluid: true, // this will add 'container-fluid' instead of 'container'
        title: title,
        content: content,
        onContentReady:onContentReady,
        // onOpen: onOpenFn,
        // onClose:onCloseFn,
        buttons: {

            'Cancel': {
                text: 'Close',
                keys: [],
                btnClass: 'big-btn',
                action: function () {

                }
            },
            'Yes': {
                text: 'Complete',
                keys: [],
                btnClass: 'big-btn',
                action: yes_function
            }
        }

    });
}

function JconfirmReschedule(title, content, yes_function,onContentReady) {
    $.confirm({
         theme: 'material',
        closeIcon: true,
        useBootstrap: false,
        columnClass: 'col-md-6 col-md-offset-3 text-center',
        //boxWidth:'60%',
        //containerFluid: true, // this will add 'container-fluid' instead of 'container'
        title: title,
        content: content,
        onContentReady:onContentReady,
        // onOpen: onOpenFn,
        // onClose:onCloseFn,
        buttons: {

            'Cancel': {
                text: 'Close',
                keys: [],
                btnClass: 'big-btn',
                action: function () {

                }
            },
            'Yes': {
                text: 'Re-schedule',
                keys: [],
                btnClass: 'big-btn',
                action: yes_function
            }
        }

    });
}


function JconfirmCancel(title, content, yes_function,onContentReady) {
    $.confirm({
        theme: 'material',
        closeIcon: true,
        useBootstrap: false,
        columnClass: 'col-md-6 col-md-offset-3 text-center',
        //boxWidth:'60%',
        containerFluid: true, // this will add 'container-fluid' instead of 'container'
        title: title,
        content: content,
        onContentReady:onContentReady,
        // onOpen: onOpenFn,
        // onClose:onCloseFn,
        buttons: {

            'Cancel': {
                text: 'Close',
                keys: [],
                btnClass: 'big-btn',
                action: function () {

                }
            },
            'Yes': {
                text: 'Cancel',
                keys: [],
                btnClass: 'big-btn',
                action: yes_function
            }
        }

    });
}


function JconfirmCustomized(title, content, yes_function,onContentReady) {
    $.confirm({
        theme: 'material',
        closeIcon: true,
        columnClass: 'col-md-6 col-md-offset-3 text-center',
        //boxWidth:'60%',
        containerFluid: true, // this will add 'container-fluid' instead of 'container'
        title: title,
        useBootstrap: false,
        content: content,
        onContentReady:onContentReady,
        // onOpen: onOpenFn,
        // onClose:onCloseFn,
        buttons: {
            'Yes': {
                text: 'Export as Excel',
                keys: [],
                btnClass: 'btn-blue',
                action: yes_function
            }
        }

    });
}