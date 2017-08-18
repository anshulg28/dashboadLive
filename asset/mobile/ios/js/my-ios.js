
// Init App
var tabToShow = '#tab1';
var myApp = new Framework7({
    // Enable Template7 pages
    pushState: true,
    pushStateSeparator: '?page/',
    //template7Pages: true,
    smartSelectOpenIn:'picker',
    //hideNavbarOnPageScroll: true,
    /*swipePanel: 'left',*/
    preprocess: function (content, url, next) {
        if(typeof url != 'undefined')
        {
            if(url.indexOf('events') != -1 || url.indexOf('create_event') != -1 || url.indexOf('event_dash') != -1
                || url.indexOf('eventEdit') != -1 || url.indexOf('event_details') != -1 || url.indexOf('contact_us') != -1
                || url.indexOf('jukebox') != -1 || url.indexOf('taproom') != -1)
            {
                tabToShow = '#tab2';
            }
        }
        return content;
    }
});

var welcomescreen = myApp.welcomescreen(welcomescreen_slides, options);
// Init View
var mainView = myApp.addView('.view-main', {
    // Material doesn't support it but don't worry about it
    // F7 will ignore it for Material theme
    dynamicNavbar: true,
    uniqueHistory: true
});

/*var commingUp = myApp.addView('.view-commingUp', {
    dynamicNavbar: true,
    uniqueHistory: true
});
var menus = myApp.addView('.view-menus', {
    dynamicNavbar: true,
    uniqueHistory: true
});*/
myApp.onPageInit('event', function (page) {
    // Do something here for "about" page
    myApp.hideIndicator();
    $('.event-fab-btn').addClass('hide');
    if(typeof $('#meta_title').val() != 'undefined')
    {
        $('title').html($('#meta_title').val());
    }

    $("#share").jsSocials({
        url: $('#shareLink').val(),
        text:$('title').html(),
        shares: ["whatsapp", "twitter", "facebook"]
    });
    $('.jssocials-share').find('a').addClass('external');

    $$(document).on('click','.custom-addToCal', function(){
        var evTitle = $(this).attr('data-ev-title');
        var evDescription = $(this).attr('data-ev-description');
        var evStart =  new Date(($(this).attr('data-ev-start')).replace(/-/g, "/"));
        var evEnd = new Date(($(this).attr('data-ev-end')).replace(/-/g, "/"));
        var evLoc = $(this).attr('data-ev-location');
        var data= {
            title: evTitle,
            start: evStart,
            end: evEnd,
            address: evLoc,
            description: evDescription
        };
       var cal = generateCalendars(data);
        var clickedLink = this;
        var popoverHTML = '<div class="popover">'+
            '<div class="popover-inner">'+
            '<div class="list-block">'+
            '<ul>'+
            '<li>'+cal.google+'</li>'+
            '<li>'+cal.ical+'</li>'+
            '<li>'+cal.yahoo+'</li>'+
            '<li>'+cal.outlook+'</li>'+
            '</ul>'+
            '</div>'+
            '</div>'+
            '</div>'
        myApp.popover(popoverHTML, clickedLink);
        //$(this).html(myCalendar);

    });
});
myApp.onPageInit('eventSingle', function (page) {
    // Do something here for "about" page
    myApp.hideIndicator();
    $('.event-fab-btn').addClass('hide');

    if($('#isLoggedIn').val() == '0')
    {
        myApp.loginScreen();
    }

    if(typeof $('#meta_title').val() != 'undefined')
    {
        $('title').html($('#meta_title').val());
    }

    $("#share").jsSocials({
        url: $('#shareLink').val(),
        text:$('title').html(),
        shares: ["whatsapp", "twitter", "facebook"]
    });
    $('.jssocials-share').find('a').addClass('external');

    $$(document).on('click','.event-cancel-btn', function(){
        var isConfirm = false;
        vex.dialog.buttons.YES.text = 'Cancel Event';
        vex.dialog.buttons.NO.text = 'Close';
        vex.dialog.confirm({
            unsafeMessage: '<label class="head-title">Cancel Event?</label><br><br>'+"Once you tap 'Cancel Event', your event will be cancelled within 48 hours. All" +
            " the fees collected will be refunded to the attendees.",
            showCloseButton: true,
            afterClose: function(){
                vex.closeAll();
                if(isConfirm)
                {
                    cancelEvent($$('.event-details #eventId').val());
                }
                /*var f = Object.keys(vex.getAll());
                if(f.length != 0)
                {
                    console.log(f.length);
                    for(var i=0;i<f.length;i++)
                    {
                        vex.close(''+f[i]+'');
                    }
                }*/
            },
            callback: function (value) {
                if (value) {
                    isConfirm = true;
                }
            }
        });
        //confirmDialog('Cancel Event?', "Once you tap 'Cancel Event', your event will be cancelled within 48 hours. All" +
            //" the fees collected will be refunded to the attendees.",'Cancel Event', $$('.event-details #eventId').val(), false);
    });

    $$(document).on('click','.page-refresh-btn', function(){
        myApp.showIndicator();
        setTimeout(function(){
            mainView.router.refreshPage();
            myApp.hideIndicator();
        },1000);
    });

    $$(document).on('click','.numbr-signup', function(e){
        var parent, ink, d, x, y;
        parent = $(this);
        //create .ink element if it doesn't exist
        if(parent.find(".ink").length == 0)
            parent.append("<span class='ink'></span>");

        ink = parent.find(".ink");
        //incase of quick double clicks stop the previous animation
        ink.removeClass("animate");

        //set size of .ink
        if(!ink.height() && !ink.width())
        {
            //use parent's width or height whichever is larger for the diameter to make a circle which can cover the entire element.
            d = Math.max(parent.outerWidth(), parent.outerHeight());
            ink.css({height: d, width: d});
        }

        //get click coordinates
        //logic = click coordinates relative to page - parent's position relative to page - half of self height/width to make it controllable from the center;
        x = e.pageX - parent.offset().left - ink.width()/2;
        y = e.pageY - parent.offset().top - ink.height()/2;

        //set the position and add class .animate
        ink.css({top: y+'px', left: x+'px'}).addClass("animate");
    });

});
myApp.onPageInit('signUpListPage', function (page) {
    // Do something here for "about" page
    myApp.hideIndicator();
    $('.event-fab-btn').addClass('hide');

    if($('#isLoggedIn').val() == '0')
    {
        myApp.loginScreen();
    }

    if(typeof $('#meta_title').val() != 'undefined')
    {
        $('title').html($('#meta_title').val());
    }

    $$(document).on('click','.page-refresh-btn', function(){
        myApp.showIndicator();
        setTimeout(function(){
            mainView.router.refreshPage();
            myApp.hideIndicator();
        },1000);
    });

});
myApp.onPageInit('eventsDash', function(page){
    myApp.hideIndicator();
    $('.event-fab-btn').addClass('hide');
    if($('#isLoggedIn').val() == '0')
    {
        myApp.loginScreen();
    }
    $$('.even-dashboard #user-app-login').on('beforeSubmit', function (e) {
        e.preventDefault();
        var xhr = e.detail.xhr;
        if($$('input[name="username"]').val() == '')
        {
            myApp.addNotification({
                title: 'Error!',
                message: 'Username Required!'
            });
            xhr.abort();
        }
        if($$('input[name="password"]').val() == '')
        {
            myApp.addNotification({
                title: 'Error!',
                message: 'Password Required!'
            });
            xhr.abort();
        }
    });
    $$('.even-dashboard #user-app-login').on('submitted', function(e){
        var data = e.detail.data;
        var objJSON = JSON.parse(data);
        if(objJSON.status === true)
        {
            $('.iosHome .user-settings').removeClass('hide');
            mainView.router.refreshPage();
        }
        else
        {
            vex.dialog.buttons.YES.text = 'Close';
            vex.dialog.alert({
                unsafeMessage: '<label class="head-title">Error!</label><br><br>'+objJSON.errorMsg
            });
            //alertDialog('Error!',objJSON.errorMsg, false);
        }
    });
});
var eventAddStatus = false;
myApp.onPageInit('eventAdd', function (page) {
    // Do something here for "about" page

    myApp.hideIndicator();
    $('.event-fab-btn').addClass('hide');
    $$(document).on('click','.book-event-btn', function(){
        myApp.modal({
            title:  'Event Guidelines <i class="pull-right fa fa-times" id="guide-close"></i>',
            text: $('#event-guide').html()
        });
    });
    $$(document).on('click','#guide-close', function(){
        myApp.closeModal();
    });
    componentHandler.upgradeDom();
    $('#startTime').timepicker({
        minuteStep: 1
    });
    $('#endTime').timepicker({
        minuteStep: 1
    });
    /*$('#startTime').clockpicker({
        default: 'now',
        placement: 'top',
        autoclose: true,
        vibrate: true,
        twelvehour: true,
        afterDone: function() {
            timeCheck();
        }
    });*/
    /*$('#endTime').clockpicker({
        default: 'now',
        placement: 'top',
        align:'right',
        autoclose: true,
        vibrate: true,
        twelvehour: true,
        afterDone: function() {
            timeCheck();
        }
    });*/
    var calendarDefault = myApp.calendar({
        input: '#eventDate',
        minDate: new Date(),
        closeOnSelect: true
    });
    $$('.event-img-add-btn').touchend(function(){
        $('#event-img-upload').click();
    });
    $$(document).on('click','.event-img-remove', function(){
        filesArr = [];
        $$('.event-add .event-img-after .progressbar').removeClass('hide');
        $$('.event-add .event-img-after .event-img-remove').addClass('hide');
        $('.event-add .event-img-after').addClass('hide');
        $('.event-add .event-img-before').removeClass('hide');
        $$('.event-add #event-img-upload').val('');
        $$('.event-add .event-img-space').css({
           'background': '#EDEDEC'
        });
    });
    $$(document).on('keyup','#eventPrice', function(){
        var basic = 300;
        var inputVal = Number($(this).val());
        var total = basic+inputVal;
        $$('.event-add .total-event-price').html(total);
        $$('.event-add input[name="eventPrice"]').val(total);
    });
    $$(document).on('change','.event-add input[name="costType"]', function(){
        if($(this).val() == '1')
        {
            $('.event-add #eventPrice').attr('disabled', 'disabled');
        }
        else
        {
            $('.event-add #eventPrice').removeAttr('disabled');
        }
    });

    $$(document).on('click','.event-add .my-mainMenuList .micDiv', function(){

        if($$(this).find('li').attr('disabled'))
        {
            myApp.addNotification({
                title: 'Error!',
                message: 'Mic timings are 7 am to 11 am',
                hold:10*1000
            });
        }
    });
    $$(document).on('click','.event-add .my-mainMenuList .projDiv', function(){

        if($$(this).find('li').attr('disabled'))
        {
            myApp.addNotification({
                title: 'Error!',
                message: 'Projector timings are 7 am to 4 pm',
                hold:10*1000
            });
        }
    });

    $$(document).on('change','.event-add select[name="eventPlace"]', function(){
        if($$(this).val() != '' && $$('.event-add #eventDate').val() != '' && $$('.event-add input[name="startTime"]').val() != ''
         && $$('.event-add input[name="endTime"]').val() != '')
        {
            var postData = {
                'startTime' : $$('.event-add input[name="startTime"]').val(),
                'endTime' : $$('.event-add input[name="endTime"]').val(),
                'eventPlace' : $$(this).val(),
                'eventDate' : $$('.event-add #eventDate').val()
            };

            $.ajax({
                type:"POST",
                dataType: 'json',
                url: base_url+'checkEventSpace',
                data: postData,
                success: function(data){
                    if(data.status === false)
                    {
                        vex.dialog.buttons.YES.text = 'Close';
                        vex.dialog.alert({
                            unsafeMessage: '<label class="head-title">Error!</label><br><br>'+data.errorMsg
                        });
                    }
                },
                error: function(){
                    vex.dialog.buttons.YES.text = 'Close';
                    vex.dialog.alert({
                        unsafeMessage: '<label class="head-title">Error!</label><br><br>Some Error Occurred!'
                    });
                }
            });
        }
    });

    $$('.event-add form.ajax-submit').on('beforeSubmit', function (e) {
        e.preventDefault();
        var xhr = e.detail.xhr; // actual XHR object
        /*if($('.event-add input[name="attachment"]').val() == '')
        {
            myApp.addNotification({
                title: 'Error!',
                message: 'Cover Image Required!',
                hold:10*1000
            });
            xhr.abort();
            return false;
        }*/
        if($$('.event-add #eventName').val() == '')
        {
            myApp.addNotification({
                title: 'Error!',
                message: 'Event Name Required!',
                hold:10*1000
            });
            xhr.abort();
            return false;
        }
        if($$('.event-add #eventPlace').val() == '')
        {
            myApp.addNotification({
                title: 'Error!',
                message: 'Event Place Required!',
                hold:10*1000
            });
            xhr.abort();
            return false;
        }
        if($$('.event-add #eventDate').val() == '')
        {
            myApp.addNotification({
                title: 'Error!',
                message: 'Event Date Required!',
                hold:10*1000
            });
            xhr.abort();
            return false;
        }
        if($$('.event-add #creatorName').val() == '')
        {
            myApp.addNotification({
                title: 'Error!',
                message: 'Organiser Name Required!',
                hold:10*1000
            });
            xhr.abort();
            return false;
        }
        if($$('.event-add #creatorPhone').val() == '')
        {
            myApp.addNotification({
                title: 'Error!',
                message: 'Organiser Phone Required!',
                hold:10*1000
            });
            xhr.abort();
            return false;
        }
        if($$('.event-add #creatorEmail').val() == '')
        {
            myApp.addNotification({
                title: 'Error!',
                message: 'Organiser Email Required!',
                hold:10*1000
            });
            xhr.abort();
            return false;
        }
        if(!$$('.event-add #tnc').is(':checked'))
        {
            myApp.addNotification({
                title: 'Error!',
                message: 'Please Agree To T&C!',
                hold:10*1000
            });
            xhr.abort();
            return false;
        }
        if($$('.event-add input[name="startTime"]').val() == '' || $$('.event-add input[name="endTime"]').val() == '')
        {
            myApp.addNotification({
                title: 'Error!',
                message: 'Start and End Time Required!',
                hold:10*1000
            });
            xhr.abort();
            return false;
        }

        var d = new Date($$('.event-add #eventDate').val());
        var startT = ConvertTimeformat('24',$$('.event-add input[name="startTime"]').val());
        var endT = ConvertTimeformat('24',$$('.event-add input[name="endTime"]').val());
        if(d.getDay() == 6 || d.getDay() == 0)
        {
            if(startT < "07:00")
            {
                myApp.addNotification({
                    title: 'Error!',
                    message: 'On weekends, events can be organised from 7 am to 2 pm!',
                    hold:10*1000
                });
                xhr.abort();
                return false;
            }
            if(endT > "14:00")
            {
                myApp.addNotification({
                    title: 'Error!',
                    message: 'On weekends, events can be organised from 7 am to 2 pm!',
                    hold:10*1000
                });
                xhr.abort();
                return false;
            }
        }
        else
        {
            if(startT < "07:00")
            {
                myApp.addNotification({
                    title: 'Error!',
                    message: 'On weekdays, events can be organised from 7 am to 6 pm!',
                    hold:10*1000
                });
                xhr.abort();
                return false;
            }
            if(endT > "18:00")
            {
                myApp.addNotification({
                    title: 'Error!',
                    message: 'On weekdays, events can be organised from 7 am to 6 pm!',
                    hold:10*1000
                });
                xhr.abort();
                return false;
            }
        }

        if(startT > endT)
        {
            myApp.addNotification({
                title: 'Error!',
                message: 'Event Time is not proper!',
                hold:10*1000
            });
            xhr.abort();
            return false;
        }
        if(typeof cropData['imgUrl'] != 'undefined' && eventAddStatus === false)
        {
            xhr.abort();
            myApp.showIndicator();
            cropData['imgData'] = $('#img-container').cropper('getCroppedCanvas').toDataURL();
            $.ajax({
                type:'POST',
                dataType:'json',
                url:base_url+'dashboard/cropEventImage',
                data:{data: cropData},
                success: function(data)
                {
                    myApp.hideIndicator();
                    if(data.status == 'error')
                    {
                        myApp.addNotification({
                            title: 'Error!',
                            message: data.message,
                            hold:10*1000
                        });
                        xhr.abort();
                        return false;
                    }
                    else
                    {
                        filesArr = [];
                        var uri = data.url.split('/');
                        filesArr.push(uri[uri.length-1]);
                        fillEventImgs();
                        eventAddStatus = true;
                        $$('.event-add .submit-event-btn').click();
                        //xhr.open();
                        //xhr.send();
                    }
                },
                error: function()
                {
                    myApp.hideIndicator();
                    myApp.addNotification({
                        title: 'Error!',
                        message: 'Some Error Occurred!',
                        hold:10*1000
                    });
                    xhr.abort();
                    return false;
                }
            });
        }
        else if($('.event-add input[name="attachment"]').val() == '')
        {
            myApp.addNotification({
                title: 'Error!',
                message: 'Cover Image Required!',
                hold:10*1000
            });
            xhr.abort();
            return false;
        }
        else if(eventAddStatus === false)
        {
            fillEventImgs();
        }
        myApp.showIndicator();
    });
    $$('.event-add form.ajax-submit').on('submitted', function(e){
        myApp.hideIndicator();
        var data = e.detail.data;
        var objJSON = JSON.parse(data);
        if(objJSON.status === true)
        {
            vex.dialog.buttons.YES.text = 'Close';
            vex.dialog.alert({
                unsafeMessage: '<label class="head-title">Success</label><br><br>'+'Thank you for creating an event, ' +
                'We have sent you an confirmation email, please check for event status in My Events section.',
                callback: function(){
                    setTimeout(function(){
                        mainView.router.back({
                            ignoreCache: true
                        });
                    },500);
                }
            });
            //alertDialog('Success','Thank you for creating an event, ' +
                //'We have send you an confirmation email, please check for event status in My Dashboard section.',true);
            //mainView.router.back();
        }
        else
        {
            vex.dialog.buttons.YES.text = 'Close';
            vex.dialog.alert({
                unsafeMessage: '<label class="head-title">Error!</label><br><br>'+objJSON.errorMsg
            });
            //alertDialog('Error!',objJSON.errorMsg, false);
        }
    });
});
var eventEditStatus = false;
myApp.onPageInit('eventEdit', function (page) {
    // Do something here for "about" page
    myApp.hideIndicator();
    $('.event-fab-btn').addClass('hide');
    if($('#isLoggedIn').val() == '0')
    {
        myApp.loginScreen();
    }
    $$(document).on('click','.book-event-btn', function(){
        myApp.modal({
            title:  'Event Guidelines <i class="pull-right fa fa-times" id="guide-close"></i>',
            text: $('#event-guide').html()
        });
    });
    $$(document).on('click','#guide-close', function(){
        myApp.closeModal();
    });
    componentHandler.upgradeDom();
    $('#startTime').timepicker({
        minuteStep: 1
    });
    $('#endTime').timepicker({
        minuteStep: 1
    });
    var calendarDefault = myApp.calendar({
        input: '#eventDate',
        minDate: new Date(),
        closeOnSelect: true
    });
    $$('.event-img-add-btn').touchend(function(){
        $('#event-img-upload').click();
    });
    $$(document).on('click','.event-img-remove', function(){
        filesArr = [];
        $$('.event-add .event-img-after .progressbar').removeClass('hide');
        $$('.event-add .event-img-after .event-img-remove').addClass('hide');
        $('.event-add .event-img-after').addClass('hide');
        $('.event-add .event-img-before').removeClass('hide');
        $$('.event-add #event-img-upload').val('');
        $$('.event-add .event-img-space').css({
            'background': '#EDEDEC'
        });
    });
    $$(document).on('keyup','#eventPrice', function(){
        var basic = 300;
        var inputVal = Number($(this).val());
        var total = basic+inputVal;
        $$('.event-add .total-event-price').html(total);
        $$('.event-add input[name="eventPrice"]').val(total);
    });
    $$(document).on('change','.event-add input[name="costType"]', function(){
        if($(this).val() == '1')
        {
            $('.event-add #eventPrice').attr('disabled', 'disabled');
        }
        else
        {
            $('.event-add #eventPrice').removeAttr('disabled');
        }
    });

    $$('.event-add form.ajax-submit').on('beforeSubmit', function (e) {
        e.preventDefault();
        var xhr = e.detail.xhr; // actual XHR object
        /*if($('.event-add input[name="attachment"]').val() == '')
        {
            myApp.addNotification({
                title: 'Error!',
                message: 'Cover Image Required!'
            });
            xhr.abort();
            return false;
        }*/
        if($$('.event-add #eventName').val() == '')
        {
            myApp.addNotification({
                title: 'Error!',
                message: 'Event Name Required!',
                hold:10*1000
            });
            xhr.abort();
            return false;
        }
        if($$('.event-add #eventPlace').val() == '')
        {
            myApp.addNotification({
                title: 'Error!',
                message: 'Event Place Required!',
                hold:10*1000
            });
            xhr.abort();
            return false;
        }
        if($$('.event-add #eventDate').val() == '')
        {
            myApp.addNotification({
                title: 'Error!',
                message: 'Event Date Required!',
                hold:10*1000
            });
            xhr.abort();
            return false;
        }
        if($$('.event-add #creatorName').val() == '')
        {
            myApp.addNotification({
                title: 'Error!',
                message: 'Organiser Name Required!',
                hold:10*1000
            });
            xhr.abort();
            return false;
        }
        if($$('.event-add #creatorPhone').val() == '')
        {
            myApp.addNotification({
                title: 'Error!',
                message: 'Organiser Phone Required!',
                hold:10*1000
            });
            xhr.abort();
            return false;
        }
        if($$('.event-add #creatorEmail').val() == '')
        {
            myApp.addNotification({
                title: 'Error!',
                message: 'Organiser Email Required!',
                hold:10*1000
            });
            xhr.abort();
            return false;
        }
        if(!$$('.event-add #tnc').is(':checked'))
        {
            myApp.addNotification({
                title: 'Error!',
                message: 'Please Agree To T&C!',
                hold:10*1000
            });
            xhr.abort();
            return false;
        }
        if($$('.event-add input[name="startTime"]').val() == '' || $$('.event-add input[name="endTime"]').val() == '')
        {
            myApp.addNotification({
                title: 'Error!',
                message: 'Start and End Time Required!',
                hold:10*1000
            });
            xhr.abort();
            return false;
        }

        var d = new Date($$('.event-add #eventDate').val());
        var startT = ConvertTimeformat('24',$$('.event-add input[name="startTime"]').val());
        var endT = ConvertTimeformat('24',$$('.event-add input[name="endTime"]').val());
        if(d.getDay() == 6 || d.getDay() == 0)
        {
            if(startT < "07:00")
            {
                myApp.addNotification({
                    title: 'Error!',
                    message: 'On weekends, events can be organised from 7 am to 2 pm!',
                    hold:10*1000
                });
                xhr.abort();
                return false;
            }
            if(endT > "14:00")
            {
                myApp.addNotification({
                    title: 'Error!',
                    message: 'On weekends, events can be organised from 7 am to 2 pm!',
                    hold:10*1000
                });
                xhr.abort();
                return false;
            }
        }
        else
        {
            if(startT < "07:00")
            {
                myApp.addNotification({
                    title: 'Error!',
                    message: 'On weekdays, events can be organised from 7 am to 6 pm!',
                    hold:10*1000
                });
                xhr.abort();
                return false;
            }
            if(endT > "18:00")
            {
                myApp.addNotification({
                    title: 'Error!',
                    message: 'On weekdays, events can be organised from 7 am to 6 pm!',
                    hold:10*1000
                });
                xhr.abort();
                return false;
            }
        }

        if(startT > endT)
        {
            myApp.addNotification({
                title: 'Error!',
                message: 'Event Time is not proper!',
                hold:10*1000
            });
            xhr.abort();
            return false;
        }
        if(typeof cropData['imgUrl'] != 'undefined' && eventEditStatus === false)
        {
            xhr.abort();
            myApp.showIndicator();
            cropData['imgData'] = $('#img-container').cropper('getCroppedCanvas').toDataURL();
            $.ajax({
                type:'POST',
                dataType:'json',
                url:base_url+'dashboard/cropEventImage',
                data:{data: cropData},
                success: function(data)
                {
                    myApp.hideIndicator();
                    if(data.status == 'error')
                    {
                        myApp.addNotification({
                            title: 'Error!',
                            message: data.message,
                            hold:10*1000
                        });
                        xhr.abort();
                        return false;
                    }
                    else
                    {
                        filesArr = [];
                        var uri = data.url.split('/');
                        filesArr.push(uri[uri.length-1]);
                        fillEventImgs();
                        eventEditStatus = true;
                        $$('.event-add .submit-event-btn').click();
                        //xhr.open();
                        //xhr.send();
                    }
                },
                error: function()
                {
                    myApp.hideIndicator();
                    myApp.addNotification({
                        title: 'Error!',
                        message: 'Some Error Occurred!',
                        hold:10*1000
                    });
                    xhr.abort();
                    return false;
                }
            });
        }
        else if($('.event-add input[name="attachment"]').val() == '')
        {
            myApp.addNotification({
                title: 'Error!',
                message: 'Cover Image Required!',
                hold:10*1000
            });
            xhr.abort();
            return false;
        }
        else if(eventEditStatus === false)
        {
            fillEventImgs();
        }
        myApp.showIndicator();
    });
    $$('.event-add form.ajax-submit').on('submitted', function(e){
        myApp.hideIndicator();
        var data = e.detail.data;
        var objJSON = JSON.parse(data);
        if(objJSON.status === true)
        {
            mainView.router.refreshPreviousPage();
            vex.dialog.buttons.YES.text = 'Close';
            vex.dialog.alert({
                unsafeMessage: '<label class="head-title">Success</label><br><br>'+'Your event is now in review state, ' +
                'We will sent you mail once review is done, you can check for event status in My Events section.',
                callback: function(){
                    setTimeout(function(){
                        mainView.router.back({
                            ignoreCache: true
                        });
                    },500);
                }
            });
            //alertDialog('Success!', 'Your event is now in review state, ' +
                //'We will send you mail once review is done, you can check for event status in My Events section.',true);

        }
        else
        {
            vex.dialog.buttons.YES.text = 'Close';
            vex.dialog.alert({
                unsafeMessage: '<label class="head-title">Error!</label><br><br>'+objJSON.errorMsg
            });
            //alertDialog('Error!',objJSON.errorMsg, false);
        }
    });
});
myApp.onPageInit('contactPage', function(page){
    myApp.hideIndicator();
    $('.event-fab-btn').addClass('hide');
});
myApp.onPageInit('jukeboxPage', function(page){
    myApp.hideIndicator();
    $('.event-fab-btn').addClass('hide');

    $$(document).on('click','.taproom-btn', function(){
        myApp.showIndicator();
    });
    /*$.geolocation.get({
        win: function(position)
        {
            console.log(position);
            $$('.juke_status').html(position.coords.latitude + ", " + position.coords.longitude);
        },
        fail: function (error) {
            console.log(error);
            $$('.juke_status').html(getGeoError(error.code)+'<br>Message: '+error.message);
        }
    });*/

});
myApp.onPageInit('taproomPage', function(page){
    myApp.hideIndicator();
    $('.event-fab-btn').addClass('hide');
});
myApp.onPageBeforeAnimation('comming-up', function (page) {
    if (page.from === 'left') {
        $('.event-fab-btn').removeClass('hide');
    }
});

$$(window).on('load', function (e) {
    setTimeout(function(){
        //myApp.hideIndicator();
        myApp.showTab(tabToShow);
        welcomescreen.close();
        if(tabToShow == '#tab1')
        {
            $$('.my-fab-btn').removeClass('hide');
        }
        /*else if(tabToShow == '#tab2')
        {
            $$('.event-fab-btn').removeClass('hide');
        }*/
        if($$('#MojoStatus').val() == '1')
        {
            mainView.router.refreshPreviousPage();
            vex.dialog.buttons.YES.text = 'Close';
            vex.dialog.alert({
                unsafeMessage: '<label class="head-title">Success</label><br><br>'+'Congrats! You have successfully registered for the event, please find the details in My Events section'
            });
            //alertDialog('Success!','Congrats! You have successfully registered for the event, please find the details in My Events section',false);
            myApp.showIndicator();
            mainView.router.load({
                url:'event_dash',
                ignoreCache: true
            });
            myApp.showTab('#tab2');
        }
        else if($$('#MojoStatus').val() == '2')
        {
            vex.dialog.buttons.YES.text = 'Close';
            vex.dialog.alert({
                unsafeMessage: '<label class="head-title">Failed!</label><br><br>'+'Some Error occurred! Please try again!'
            });
            //alertDialog('Failed!','Some Error occurred! Please try again!',false);
        }
        /*var mySwiper2 = myApp.swiper('#tab2 .my-cal-glance', {
            pagination:'#tab2 .my-cal-glance .swiper-pagination',
            spaceBetween: 0,
            slidesPerView: 2
        });*/
        if(typeof $('#tab2 .even-cal-list') === 'undefined')
        {
            var d = new Date();
            $('#calendar-glance').fullCalendar({
                defaultView: 'basicWeek',
                header: false,
                height:'auto',
                firstDay: d.getDay(),
                defaultDate: d
            });
        }
        else
        {
            var events = [];
            var d = new Date();
            $('#tab2 .even-cal-list li').each(function(j,val){
                var tempData = {};
                var eveName = $(val).attr('data-evenNames');
                if(eveName.indexOf(',') != -1)
                {
                    var res = eveName.split(',');
                    console.log(res);
                    var places = $(val).attr('data-evenPlaces').split(',');
                    console.log(places);
                    for(var i=0;i<res.length;i++)
                    {
                         tempData = {};
                         tempData['title'] = res[i];
                         tempData['allDay'] = true;
                         tempData['start'] = $(val).attr('data-evenDate');
                         tempData['className'] = 'evenPlace_'+places[i];
                         events.push(tempData);
                    }
                }
                else
                {
                    tempData = {};
                    tempData['title'] = eveName;
                    tempData['allDay'] = true;
                    tempData['start'] = $(val).attr('data-evenDate');
                    tempData['className'] = 'evenPlace_'+$(val).attr('data-evenPlaces');
                    events.push(tempData);
                }
            });
            $('#calendar-glance').fullCalendar({
                defaultView: 'basicWeek',
                header: false,
                height:'auto',
                firstDay:d.getDay(),
                defaultDate: d,
                events: events
            });
        }
        $('#calendar-glance').fullCalendar('render');
    },1000);
    /*setTimeout(function(){
        $('#calendar-glance').fullCalendar('render');
    },1000);*/
    document.querySelector('.my-fb-label').MaterialCheckbox.check();
    document.querySelector('.my-tw-label').MaterialCheckbox.check();
    document.querySelector('.my-insta-label').MaterialCheckbox.check();
});
$(document).on('click','#tab2 #calendar-glance .fc-day-grid-event.fc-event', function(){
    var srcTitle = $(this).find('span').html();
    $('#tab2 .event-section .demo-card-header-pic').each(function(i,val){
        if($(val).attr('data-eveTitle') == srcTitle)
        {
            var pos = $(val).position();
            console.log(pos);
            $('#tab2 .page-content').animate({
                scrollTop: pos.top - 50
            });
            return false;
        }
    });
});
/*var myElement1 = document.getElementById('my-page1');
var myElement2 = document.getElementById('my-page2');
var myElement3 = document.getElementById('my-page3');
var mc1 = new Hammer(myElement1);
var mc2 = new Hammer(myElement2);
var mc3 = new Hammer(myElement3);

mc1.on("swipeleft", function(ev) {
    if(ev.target != myElement1 && ev.isFirst === false)
    {
        ev.preventDefault();
    }
    else
    {
        myApp.showTab('#tab2');
    }
});

mc2.on("swipeleft swiperight", function(ev) {

    if(ev.type == "swipeleft")
    {
        myApp.showTab('#tab3');
    }
    else
    {
        myApp.showTab('#tab1');
    }
});

mc3.on("swiperight", function(ev) {
    if(ev.target != myElement3 && ev.isFirst === false)
    {
        ev.preventDefault();
    }
    else
    {
        myApp.showTab('#tab2');
    }
});*/

var lastIndex = 10;
var maxItems = $$('.custom-accordion .my-card-items').length;
var itemsPerLoad = 10;
var loading = false;

// Attach 'infinite' event handler
$$('.infinite-scroll').on('infinite', function () {

    // Exit, if loading in progress
    if (loading) return;
    loading = true;

    // Emulate 1s loading
    setTimeout(function () {
        // Reset loading flag
        loading = false;

        // Generate new items HTML
        var html = '';
        var totalToLoad = lastIndex + itemsPerLoad;
        if(totalToLoad > maxItems)
        {
            totalToLoad = maxItems;
        }
        for (var i = lastIndex; i < totalToLoad; i++) {
            $$($$('.custom-accordion .my-card-items')[i]).removeClass('hide');
            $('.my-card-items').each(function(i,val){
                if(!$(this).hasClass('hide'))
                {
                    if(typeof $(this).find('.my-link-url').val() !== 'undefined')
                    renderLinks($(this).find('.my-link-url'));
                }
                //renderLinks(val);
            });
        }

        // Update last loaded index
        lastIndex = totalToLoad;
        if (lastIndex >= maxItems) {
            // Nothing more to load, detach infinite scroll events to prevent unnecessary loadings
            myApp.detachInfiniteScroll($$('.infinite-scroll'));
            // Remove preloader
            $$('.infinite-scroll-preloader').remove();
        }
    }, 1000);
});

/* FAB Button click*/
$(document).on('click','.my-fab-btn',function(){
    if($(this).hasClass('open-popover'))
    {
        $('.popover-toggle-on').fadeOut(100,function(){
            $('.popover-toggle-off').fadeIn();
        });
        $(this).removeClass('open-popover').addClass('close-popover');
    }
    else if($(this).hasClass('close-popover'))
    {
        myApp.closeModal('.popover-links');
        $('.popover-toggle-off').fadeOut(100,function(){
            $('.popover-toggle-on').fadeIn();
        });
        $(this).removeClass('close-popover').addClass('open-popover');
    }
});

$$('.popover-links').on('close', function(){
    $('.popover-toggle-off').fadeOut(100,function(){
        $('.popover-toggle-on').fadeIn();
    });
    $('.my-fab-btn').removeClass('close-popover').addClass('open-popover');
});

$(document).ready(function(e){
    $('.my-card-items').each(function(i,val){
        if(!$(val).hasClass('hide'))
        {
            if(typeof $(val).find('.my-link-url').val() !== 'undefined')
            {
                renderLinks($(val).find('.my-link-url'));
            }
        }
        //renderLinks(val);
    });
    $("time.timeago").timeago();
    //checkNewEvents();
});

//renderLinks($('.my-link-url'));
function renderLinks(ele)
{
    if($(ele).val() != '')
    {
        $.ajax({
            type:'POST',
            dataType:'json',
            crossDomain:true,
            url:base_url+'mobile/renderLink',
            data:{url:$(ele).val()},
            success: function(data)
            {
                if(typeof data.image === 'undefined')
                {
                    $(ele).parent().find('.liveurl').find('img').attr('src',base_url+'asset/images/placeholder.png');
                }
                else
                {
                    $(ele).parent().find('.liveurl').find('img').attr('src',base_url+'asset/images/placeholder.png');
                    $(ele).parent().find('.liveurl').find('img').attr('data-src',data.image);
                }
                var mainTitle = data.title;
                if(data.title.length>35)
                {
                    mainTitle = data.title.substr(0,35)+'...';
                }
                $(ele).parent().find('.liveurl').find('.title').html(mainTitle);
                $(ele).parent().find('.liveurl').find('.description').html(data.site_name);
                $(ele).parent().find('.liveurl').removeClass('hide');
                $(ele).removeClass('my-link-url');
            },
            error: function()
            {

            }
        });
    }
}
/* Scroll Part
 ------------------ */

/*$('#tab3 .page-content').scroll(function(){
    didScroll = true;
    headerScroll = $(this).scrollTop();
    showHideScrollUp(headerScroll);
});
$('#tab2 .page-content').scroll(function(){
    didScroll = true;
    headerScroll = $(this).scrollTop();
    showHideScrollUp(headerScroll);
});*/


// Hide Header on on scroll down
/*
var didScroll;
var headerScroll = 0;
var lastScrollTop = 0;
var delta = 5;
var navbarHeight = $('.myMainBottomBar').outerHeight();*/

$$(document).on('change','input[name=social-filter]',function(){
    var notSelectedFilters = [];
    var selectedFilters = [];
    $("input[name=social-filter]:not(:checked)").each(function(){
        notSelectedFilters.push($(this).val());
        switch($(this).val())
        {
            case "1":
                $('#tab1 .facebook-wrapper').fadeOut();
                $$('#tab1 .page-content').scrollTop($(document).height(),500);
                break;
            case "2":
                $('#tab1 .twitter-wrapper').fadeOut();
                $$('#tab1 .page-content').scrollTop($(document).height(),500);
                break;
            case "3":
                $('#tab1 .instagram-wrapper').fadeOut();
                $$('#tab1 .page-content').scrollTop($(document).height(),500);
                break;
        }
    });
    if(notSelectedFilters.length == 3)
    {
        $$('.infinite-scroll-preloader').hide();
    }
    $("input[name=social-filter]:checked").each(function(){
        selectedFilters.push($(this).val());
        switch($(this).val())
        {
            case "1":
                $('.facebook-wrapper').fadeIn();
                break;
            case "2":
                $('.twitter-wrapper').fadeIn();
                break;
            case "3":
                $('.instagram-wrapper').fadeIn();
                break;
        }
    });
    if(selectedFilters.length >= 1)
    {
        $$('.infinite-scroll-preloader').show();
    }
});
var currentPos = 0;
$('#tab1 .page-content').scroll(function(){
    //didScroll = true;
    currentPos = $(this).scrollTop();
    //headerScroll = $(this).scrollTop();
    if(currentPos <=20)
    {
        if(!$('.feed-notifier').hasClass('hide'))
        {
            $('.feed-notifier').addClass('hide');
        }
    }
    //showHideScrollUp(currentPos);
});
/*setInterval(function() {
    if (didScroll) {
        hasScrolled();
        didScroll = false;
    }
}, 250);*/
/*
function hasScrolled() {
    var st = headerScroll;

    // Make sure they scroll more than delta
    if(Math.abs(lastScrollTop - st) <= delta)
        return;

    // If they scrolled down and are past the navbar, add class .nav-up.
    // This is necessary so you never see what is "behind" the navbar.
    if (st > lastScrollTop && st > navbarHeight){
        console.log('down');
        // Scroll Down
        $('.myMainBottomBar').removeClass('nav-down').addClass('nav-up');
    } else {
        console.log('up');
        // Scroll Up
        //if(st + $('#tab1 .page-content').height() < $('#tab1 .page-content').height()) {
            $('.myMainBottomBar').removeClass('nav-up').addClass('nav-down');
        //}
    }

    lastScrollTop = st;
}
function showHideScrollUp(currentScroll)
{
    if(currentScroll > 200)
    {
        $('.custom-scroll-up').fadeIn();
    }
    else
    {
        $('.custom-scroll-up').fadeOut();
    }
}
$$(document).on('click','.custom-scroll-up',function(){

    $('.page-content').animate({
        scrollTop:0
    },200);
});*/
function preventAct(ele)
{
    ele.preventDefault();
}

var pickerDevice = myApp.picker({
    input: '#picker-device',
    rotateEffect: true,
    cols: [
        {
            textAlign: 'center',
            values: ['Andheri Taproom', 'Bandra Taproom', 'Kemps Taproom']
        }
    ]
});

// Adding lazy load later
function doLazyWork()
{
    $$('.my-card-items').each(function(i,val){
        if($$(this).hasClass('hide'))
        {
            if(!$$(this).find('.myAvtar-list').hasClass('lazy'))
            {
                $$(this).find('.myAvtar-list').addClass('lazy');
            }
            if(!$$(this).find('.mainFeed-img').hasClass('lazy'))
            {
                $$(this).find('.mainFeed-img').addClass('lazy').addClass('lazy-fadein');
            }
        }
    });
    myApp.initImagesLazyLoad('.my-card-items');
}

function makeTxtShort()
{
    var showChar = 100;
    var ellipsestext = "...";
    var moretext = "more";
    var lesstext = "less";
    $('.more').each(function() {
        var content = $(this).html();

        if(content.length > showChar) {

            var c = content.substr(0, showChar);
            var h = content.substr(showChar-1, content.length - showChar);

            var html = c + '<span class="moreelipses">'+ellipsestext+'</span>&nbsp;<span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">'+moretext+'</a></span>';

            $(this).html(html);
        }

    });

    $(".morelink").click(function(){
        if($(this).hasClass("less")) {
            $(this).removeClass("less");
            $(this).html(moretext);
        } else {
            $(this).addClass("less");
            $(this).html(lesstext);
        }
        $(this).parent().prev().toggle();
        $(this).prev().toggle();
        return false;
    });
}
//makeTxtShort();

$$(document).on('click','.more-photos-wrapper', function(){
    var pics = $$(this).find('.imgs_collection').val().split(',');
    if(typeof pics != 'undefined')
    {
        var newPics = [];
        for(var i=0;i<pics.length;i++)
        {
            var temp = {href:pics[i],title:''};
            newPics[i] = temp;
        }
        $.swipebox( newPics );
        /* var myPhotoBrowserDark = myApp.photoBrowser({
             photos : pics,
             type: 'page',
             backLinkText: 'Back'
         });
         myPhotoBrowserDark.open();*/
    }
});
$$('#tab2').on('show',function(){ //Events Section
    $('#calendar-glance').fullCalendar('render');
    if(typeof $('.welcomescreen-container').val() === 'undefined' && $$('#tab2').attr('data-page') == 'comming-up')
    {
        $$('.event-fab-btn').removeClass('hide');
    }
    $$('.my-fab-btn').addClass('hide');
    $$('.ic_events_icon').addClass('on');
    $$('.ic_fnb_icon').removeClass('on');
});
$$('#tab3').on('show',function(){ //Fnb Section
    $$('.my-fab-btn').addClass('hide');
    $$('.event-fab-btn').addClass('hide');
    $$('.ic_events_icon').removeClass('on');
    $$('.ic_fnb_icon').addClass('on');
});
$$('#tab1').on('show',function(){ //Timeline Section
    if(typeof $('.welcomescreen-container').val() === 'undefined')
    {
        $$('.my-fab-btn').removeClass('hide');
    }
    $$('.event-fab-btn').addClass('hide');
    $$('.ic_events_icon').removeClass('on');
    $$('.ic_fnb_icon').removeClass('on');
});

var contentTime = setInterval(function(){
        if(typeof $('.welcomescreen-container').val() === 'undefined')
        {
            clearInterval(contentTime);
            doLazyWork();
        }
},2000);
$$(document).on('click','.event-bookNow',function(){
   myApp.showIndicator();
});
function checkNewEvents()
{
    //event-new-notify
    $('#tab2 .demo-card-header-pic .card-footer .event-new-notify').each(function(i,val){
        if(typeof $(val).attr('data-created') != 'undefined')
        {
            var created = new Date($(val).attr('data-created').replace(/-/g,"/"));
            var today = new Date();
            var timeDiff = Math.abs(today.getTime() - created.getTime());
            var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));

            if(diffDays <= 3)
            {
                $(val).removeClass('my-vanish');
            }
        }
    });
}

$$(document).on('click','.event-fab-btn', function(){
    if(mainView.activePage.url != 'create_event')
    {
        myApp.showIndicator();
        mainView.router.load({
            url:'create_event',
            ignoreCache: true
        });
    }
    else
    {
        myApp.closePanel();
        mainView.router.refreshPage();
    }
});
$$(document).on('click','#my-events-tab', function(){
    if(mainView.activePage.url != 'event_dash')
    {
        myApp.closePanel();
        myApp.showIndicator();
        mainView.router.load({
            url:'event_dash',
            ignoreCache: true
        });
        myApp.showTab('#tab2');
    }
    else
    {
        myApp.closePanel();
        mainView.router.refreshPage();
    }
});
$$(document).on('click','#contact-tab', function(){
    if(mainView.activePage.url != 'contact_us')
    {
        myApp.closePanel();
        myApp.showIndicator();
        mainView.router.load({
            url:'contact_us',
            ignoreCache: true
        });
        myApp.showTab('#tab2');
    }
    else
    {
        myApp.closePanel();
        mainView.router.refreshPage();
    }
});
$$(document).on('click','#my-jukebox-tab', function(){
    if(mainView.activePage.url != 'jukebox')
    {
        myApp.closePanel();
        myApp.showIndicator();
        mainView.router.load({
            url:'jukebox',
            ignoreCache: true
        });
        myApp.showTab('#tab2');
    }
    else
    {
        myApp.closePanel();
        mainView.router.refreshPage();
    }
});

$$(document).on('click','#logout-btn', function(){
    myApp.showIndicator();
    $$.ajax({
        method:"GET",
        url: base_url+'mobile/main/appLogout',
        cache: false,
        dataType: 'json',
        success: function(data){
            myApp.hideIndicator();
            if(data.status === true)
            {
                $('.iosHome .user-settings').addClass('hide');
                mainView.router.refreshPage();
            }
        },
        error: function(){
            myApp.hideIndicator();
            myApp.addNotification({
                title: 'Error!',
                message: 'Some Error Occurred!'
            });
        }
    });
});

$$(document).on('click','.event-card-share-btn', function(){

    $$('.popover-share').find('p').html('Share "'+$(this).parent().find('input[type="hidden"]').attr('data-name')+'"');
    $('#main-share').jsSocials({
        showLabel: true,
        text:$(this).parent().find('input[type="hidden"]').attr('data-name'),
        url: $(this).parent().find('input[type="hidden"]').val(),
        shares: [
            { share: "whatsapp", label: "WhatsApp" },
            { share: "twitter", label: "Twitter" },
            { share: "facebook", label: "Facebook" }
        ]
    });
    $('.jssocials-share').find('a').addClass('external');
    myApp.popover('.popover-share',$(this));
});
var filesArr = [];
var cropData = {};
var hasCropped = false;
function uploadChange(ele)
{

    //$('.event-add button[type="submit"]').attr('disabled','true');
    $('.event-add .event-img-after').removeClass('hide');
    $('.event-add .event-img-before').addClass('hide');
    var xhr = [];
    var totalFiles = ele.files.length;
    for(var i=0;i<totalFiles;i++)
    {
        xhr[i] = new XMLHttpRequest();
        (xhr[i].upload || xhr[i]).addEventListener('progress', function(e) {
            var done = e.position || e.loaded;
            var total = e.totalSize || e.total;
            myApp.setProgressbar($$('.event-add .event-img-after .progressbar'),Math.round(done/total*100));
            //$('.event-add .event-img-after .progressbar').css('width', Math.round(done/total*100)+'%').attr('aria-valuenow', Math.round(done/total*100)).html(parseInt(Math.round(done/total*100))+'%');
        });
        xhr[i].addEventListener('load', function(e) {
            //$('.event-add button[type="submit"]').removeAttr('disabled');
        });
        xhr[i].open('post', base_url+'dashboard/uploadEventFiles', true);

        var data = new FormData;
        data.append('attachment', ele.files[i]);
        xhr[i].send(data);
        xhr[i].onreadystatechange = function(e) {
            if (e.srcElement.readyState == 4 && e.srcElement.status == 200) {
                if(e.srcElement.responseText == 'Some Error Occurred!')
                {
                    vex.dialog.buttons.YES.text = 'Close';
                    vex.dialog.alert({
                        unsafeMessage: '<label class="head-title">Error!</label><br><br>'+'File size Limit 30MB'
                    });
                    //alertDialog('Error!','File size Limit 30MB',false);
                    return false;
                }
                filesArr.push(e.srcElement.responseText);
                $$('.event-add .event-img-after .progressbar').addClass('hide');
                var eventImg = base_url+'uploads/events/thumb/'+e.srcElement.responseText;
                $$('.event-add .event-img-space').addClass('hide');
                $('.event-add #cropContainerModal').removeClass('hide').find('#img-container').attr('src',eventImg);
                cropData['imgUrl'] = eventImg;
                $('#img-container').cropper({
                    viewMode:3,
                    minContainerHeight: 250,
                    dragMode:'move',
                    aspectRatio: 16 / 9
                    /*crop: function(e) {
                        // Output the result data for cropping image.
                        cropData['imgUrl'] = eventImg;
                        cropData['x'] = e.x;
                        cropData['y'] = e.y;
                        cropData['width'] = e.width;
                        cropData['height'] = e.height;
                        cropData['rotate'] = e.rotate;
                        cropData['scaleX'] = e.scaleX;
                        cropData['scaleY'] = e.scaleY;
                        /!*cropData['cWidth'] = $('.cropper-crop-box').width();
                        cropData['cHeight'] = $('.cropper-crop-box').height();*!/
                        /!*console.log(e.x);
                        console.log(e.y);
                        console.log(e.width);
                        console.log(e.height);
                        console.log(e.rotate);
                        console.log(e.scaleX);
                        console.log(e.scaleY);*!/
                    }*/
                });
                /*var cropperOptions = {
                    cropUrl:base_url+'dashboard/cropEventImage',
                    loadPicture:eventImg,
                    imgEyecandy:false,
                    loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> ',
                    onAfterImgCrop:function(data){
                        filesArr = [];
                        var uri = data.url.split('/');
                        filesArr.push(uri[uri.length-1]);
                        hasCropped = true;
                    },
                    onReset:function(){
                        if(hasCropped === true)
                        {
                            hasCropped = false;
                            filesArr = [];
                            $$('.event-add .event-img-after .progressbar').removeClass('hide');
                            $('.event-add .event-img-after').addClass('hide');
                            $('.event-add .event-img-before').removeClass('hide');
                            $$('.event-add #event-img-upload').val('');
                            $$('.event-add .event-img-space').removeClass('hide');
                            $('.event-add #cropContainerModal').addClass('hide');

                        }
                    },
                    onError:function(errormessage){ console.log('onError:'+errormessage) }
                };

                var cropperHeader = new Croppic('cropContainerModal', cropperOptions);
                $('.event-add #cropContainerModal').removeClass('hide');
                cropperHeader.reset();*/
                //$('.event-add #cropContainerModal').css('height','auto');
                /*$$('.event-add .event-img-space').css({
                   'background' : 'linear-gradient(rgba(255, 255, 255, 0.5), rgba(255, 255, 255, 0.5)), 0 / cover url('+eventImg+') no-repeat'
                });
                $$('.event-add .event-img-after .event-img-remove').removeClass('hide');*/

            }
        }
    }
}
$$(document).on('click','.upload-img-close', function(){
    filesArr = [];
    $$('.event-add .event-img-after .progressbar').removeClass('hide');
    $('.event-add .event-img-after').addClass('hide');
    $('.event-add .event-img-before').removeClass('hide');
    $$('.event-add #event-img-upload').val('');
    $$('.event-add .event-img-space').removeClass('hide');
    $('.event-add #cropContainerModal').addClass('hide');
    $('#img-container').cropper('destroy');
});

$$(document).on('click', '.upload-done-icon', function(){
    $$('.event-add #cropContainerModal .done-overlay').removeClass('hide');
    $$(this).addClass('hide');
    $('.event-add #eventName').focus();
});
$$(document).on('click', '.event-overlay-remove', function(){
    $$('.event-add #cropContainerModal .done-overlay').addClass('hide');
    $$('.event-add .upload-done-icon').removeClass('hide');
});
var fnb_initial_state = '';
var event_initial_state = '';
$$(document).on('change', 'input[name="beer-locations"]', function(){
    if(typeof $$(this).val() != 'undefined')
    {
        if(fnb_initial_state == '')
        {
            fnb_initial_state = $$('.fnb-section').html();
        }
        $$('.clear-beer-filter').removeClass('hide');
        $$('#tab3 .beer-filter-toggler').addClass('on');
        var filterVal = $$(this).val();
        var catArray = $$('#tab3 .category-'+filterVal);
        $(catArray).hide();
        $$('#tab3 .category-'+filterVal).remove();
        $$('.fnb-section').prepend(catArray);
        $(catArray).slideToggle();
        myApp.closeModal('.popover-filters');
    }
});
$$(document).on('click','.clear-beer-filter', function(){
    $$('#tab3 .beer-filter-toggler').removeClass('on');
    $$('.popover-filters ul li').each(function(i,val){
        var inp = '#'+$$(val).find('input').attr('id');
        document.querySelector(inp).parentNode.MaterialRadio.uncheck();
    });
    $$('.clear-beer-filter').addClass('hide');
    if(fnb_initial_state != '')
    {
        $('.fnb-section').empty().html(fnb_initial_state);
    }
    myApp.closeModal('.popover-filters');
    //document.querySelector('input[name="beer-locations"]').parentNode.MaterialRadio.uncheck();
});
//For events sorting
$$(document).on('change', 'input[name="event-locations"]', function(){
    if(typeof $$(this).val() != 'undefined')
    {
        if(event_initial_state == '')
        {
            event_initial_state = $$('.event-section').html();
        }
        $$('.clear-event-filter').removeClass('hide');
        $$('#tab2 .event-filter-toggler').addClass('on');
        var filterVal = $$(this).val();
        var catArray = $$('#tab2 .eve-'+filterVal);
        $(catArray).hide();
        $$('#tab2 .eve-'+filterVal).remove();
        $$('.event-section').prepend(catArray);
        $(catArray).slideToggle();
        myApp.closeModal('.popover-event-filter');
    }
});
$$(document).on('click','.clear-event-filter', function(){
    $$('#tab2 .event-filter-toggler').removeClass('on');
    $$('.popover-event-filter ul li').each(function(i,val){
        var inp = '#'+$$(val).find('input').attr('id');
        document.querySelector(inp).parentNode.MaterialRadio.uncheck();
    });
    $$('.clear-event-filter').addClass('hide');
    if(event_initial_state != '')
    {
        $('.event-section').empty().html(event_initial_state);
    }
    myApp.closeModal('.popover-event-filter');
    //document.querySelector('input[name="beer-locations"]').parentNode.MaterialRadio.uncheck();
});
function fillEventImgs()
{
    if(typeof filesArr[0] != 'undefined')
    $('.event-add input[name="attachment"]').val(filesArr.join());
}

function toggleAccess(ele)
{
    if($(ele).is(':checked'))
    {
        $(ele).parent().addClass('isChecked').find('i,span').addClass('on');
    }
    else
    {
        $(ele).parent().removeClass('isChecked').find('i,span').removeClass('on');
    }
}

function timeCheck()
{
    var startTime  = ConvertTimeformat('24', $('.event-add input[name="startTime"]').val());
    var endTime  = ConvertTimeformat('24', $('.event-add input[name="endTime"]').val());

    if(startTime != '' && endTime != '')
    {
        var sArray = startTime.split(':');
        var eArray = endTime.split(':');
        if($('.event-add input[name="eventPlace"]').val() != '4')
        {
            if(Number(sArray[0]) >= 7 && Number(eArray[0]) <= 11)
            {
                $('.event-add #micWrapper').removeAttr('disabled');
            }
            else
            {
                $('.event-add #micWrapper').attr('disabled','disabled');
            }
        }
        else
        {
            if(Number(sArray[0]) >= 7 && Number(eArray[0]) <= 16)
            {
                $('.event-add #micWrapper').removeAttr('disabled');
            }
            else
            {
                $('.event-add #micWrapper').attr('disabled','disabled');
            }
        }
        if(Number(sArray[0]) >= 7 && Number(eArray[0]) <= 16)
        {
            $('.event-add #projWrapper').removeAttr('disabled');
        }
        else
        {
            $('.event-add #projWrapper').attr('disabled','disabled');
        }
    }
}
function maxLengthCheck(object)
{
    if (object.value.length > object.maxLength)
        object.value = object.value.slice(0, object.maxLength)
}

$$(document).on('click', '#global-home-btn', function(){
    if(mainView.router.back() === false)
    {
        window.location.href= base_url+'mobile';
    }
    else
    {
        mainView.router.back();
    }
    myApp.closePanel();
});
var shortUri = '';
function urlshort(url)
{
    $.urlShortener({
        longUrl: url,
        success: function (shortUrl) {
            //shortUrl ->  Shortened URL
            shortUri = shortUrl;
        },
        error: function(err)
        {
            shortUri = 'error';
            //alert(JSON.stringify(err));
        }
    });
}

function cancelEvent(eventId)
{
    myApp.showIndicator();
    $$.ajax({
        method:"GET",
        url: base_url+'dashboard/cancelEvent/'+eventId,
        cache: false,
        dataType: 'json',
        success: function(data){
            myApp.hideIndicator();
            if(data.status === true)
            {
                mainView.router.back({
                    ignoreCache: true
                });
            }
        },
        error: function(){
            myApp.hideIndicator();
            myApp.addNotification({
                title: 'Error!',
                message: 'Some Error Occurred!'
            });
            vex.closeTop();
        }
    });
}
