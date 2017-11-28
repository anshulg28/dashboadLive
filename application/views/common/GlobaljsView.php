<!--not tho change js-->
<script type="text/javascript" src="<?php echo base_url(); ?>asset/js/jquery-2.2.4.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>asset/js/material.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>asset/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>asset/js/moment.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>asset/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>asset/js/bootbox.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>asset/js/getmdl-select.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>asset/js/Chart.min.js"></script>
<script src="http://cdn.ckeditor.com/4.6.2/basic/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>asset/js/progressbar.min.js"></script>
<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBu7Do2fKtcQYdGyoC5glTzRLxs6FKxy4Y&libraries=places"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>asset/mobile/js/jquery.swipebox.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>asset/js/jquery.geocomplete.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>asset/js/doolally-local-session.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>asset/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>asset/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>asset/js/jquery.timeago.js"></script>

<!-- constants -->
<script>
    <?php
        if(isset($this->currentLocation) && isSessionVariableSet($this->currentLocation) === true)
        {
            ?>
                window.currentLocation = <?php echo $this->currentLocation; ?>;
            <?php
        }
    ?>
    window.base_url = '<?php echo base_url(); ?>';
</script>

<script>

    function saveErrorLog(errorTxt)
    {
        $.ajax({
            type:'POST',
            dataType:'json',
            url:base_url+'dashboard/saveErrorLog',
            data:{errorTxt: errorTxt},
            success: function(data){},
            error: function(){}
        });
    }
    $(document).on('submit','#mainLoginForm', function(e){

        e.preventDefault();
        if(typeof $(this).find('input[name="userOtp"]').val() != 'undefined')
        {
            if($(this).find('input[name="userOtp"]').val() == '')
            {
                bootbox.alert('OTP is Required!');
                return false;
            }
        }
        $(this).find('.login-error-block').empty();
        $(this).find('button[type="submit"]').attr('disabled','disabled');
        showCustomLoader();
        var errUrl = $(this).attr('action');
        $.ajax({
            type:"POST",
            dataType:"json",
            url:$(this).attr('action'),
            data:$(this).serialize(),
            success: function(data)
            {
                hideCustomLoader();
                $('#mainLoginForm button[type="submit"]').removeAttr("disabled");
                if(data.status == true)
                {
                    if(typeof data.locError !== 'undefined' && data.locError === true)
                    {
                        window.location.href=base_url+'dashboard/setCommLoc';
                    }
                    else
                    {
                        window.location.reload();
                    }
                }
                else
                {
                    $('.login-error-block').css('color','red').html(data.errorMsg);
                }
            },
            error:function(xhr, status, error)
            {
                hideCustomLoader();
                $('#mainLoginForm button[type="submit"]').removeAttr("disabled");
                $('.login-error-block').css('color','red').html('Some Error Occurred, Try Again!');
                var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                saveErrorLog(err);
            }
        });
    });
</script>
<!-- Loader Show and hide script -->
<script>
    function showCustomLoader()
    {
        $('body').addClass('custom-loader-body');
        $('.custom-loader-overlay').css('top',$(window).scrollTop()).addClass('show');
    }

    function hideCustomLoader()
    {
        $('body').removeClass('custom-loader-body');
        $('.custom-loader-overlay').removeClass('show');
    }

    function checkMembershipValidity(membershipEndDate)
    {
        var endDate = new Date(membershipEndDate);
        var today = new Date();
        return today > endDate;
    }
    function checkMembershipGrace(membershipEndDate)
    {
        var endDate = new Date(membershipEndDate);
        var graceDate = endDate.setMonth(endDate.getMonth()+1);
        var today = new Date();
        return today > graceDate;
    }
    function checkMemberLocation(location)
    {
        return location == currentLocation;
    }

    function maxLengthCheck(object)
    {
        if (object.value.length > object.maxLength)
            object.value = object.value.slice(0, object.maxLength)
    }
    function formatJsDate(gotDate)
    {
        if(gotDate == null)
        {
            return '';
        }
        var monthNames = [
            "Jan", "Feb", "Mar",
            "Apr", "May", "June", "July",
            "Aug", "Sep", "Oct",
            "Nov", "Dec"
        ];

        var date = new Date(gotDate);
        var day = date.getDate();
        var monthIndex = date.getMonth();
        var year = date.getFullYear();

        return day + ' ' + monthNames[monthIndex] + ' ' + year;
    }

<?php
    if(isSessionVariableSet($this->isUserSession) === true && isSessionVariableSet($this->userType)
        && ($this->userType == ROOT_USER || $this->userType == ADMIN_USER || $this->userType == EXECUTIVE_USER) )
    {
        ?>
            var expiredFlag = false;
            var birthFlag = false;
            function checkExpiredMugs()
            {
                if(!expiredFlag)
                {
                    expiredFlag = true;
                    $.ajax({
                        type:"GET",
                        dataType:"json",
                        async: true,
                        url:base_url+'mugclub/getAllExpiredMugs/json',
                        success: function(data){
                            if(data.status === true)
                            {
                                localStorageUtil.setLocal('foundM1','1',(23 * 60 * 60 * 1000));
                                if(!$('.notification-indicator').hasClass('notification-animate-cls'))
                                {
                                    $('.notification-indicator').addClass('notification-animate-cls');
                                    $('.notification-indicator-mobile').addClass('notification-animate-cls');
                                    $('.notification-indicator-big').addClass('notification-animate-cls');
                                }
                            }
                            else
                            {
                                localStorageUtil.setLocal('foundM1','0',(23 * 60 * 60 * 1000));
                            }
                            expiredFlag = false;
                        },
                        error: function(){
                            expiredFlag = false;
                        }
                    });
                }

            }

            function checkExpiringMugs()
            {
                $.ajax({
                    type:"GET",
                    dataType:"json",
                    async: true,
                    url:base_url+'mugclub/getAllExpiringMugs/json/1/week',
                    success: function(data){
                        if(data.status === true)
                        {
                            localStorageUtil.setLocal('foundM2','1',(23 * 60 * 60 * 1000));
                            if(!$('.notification-indicator').hasClass('notification-animate-cls'))
                            {
                                $('.notification-indicator').addClass('notification-animate-cls');
                                $('.notification-indicator-mobile').addClass('notification-animate-cls');
                                $('.notification-indicator-big').addClass('notification-animate-cls');
                            }
                        }
                        else
                        {
                            localStorageUtil.setLocal('foundM2','0',(23 * 60 * 60 * 1000));
                        }
                    },
                    error: function(){

                    }
                });
            }
            function checkBirthdayMugs()
            {
                if(!birthFlag)
                {
                    birthFlag = true;
                    $.ajax({
                        type:"GET",
                        dataType:"json",
                        async: true,
                        url:base_url+'mugclub/getAllBirthdayMugs/json',
                        success: function(data){
                            console.log(data);
                            if(data.status === true)
                            {
                                localStorageUtil.setLocal('foundM3','1',(23 * 60 * 60 * 1000));
                                if(!$('.notification-indicator').hasClass('notification-animate-cls'))
                                {
                                    $('.notification-indicator').addClass('notification-animate-cls');
                                    $('.notification-indicator-mobile').addClass('notification-animate-cls');
                                    $('.notification-indicator-big').addClass('notification-animate-cls');
                                }
                            }
                            else
                            {
                                localStorageUtil.setLocal('foundM3','0',(23 * 60 * 60 * 1000));
                            }
                            birthFlag = false;
                        },
                        error: function(){
                            birthFlag = false;
                        }
                    });
                }

            }

            /*checkExpiredMugs();
             checkExpiringMugs();
             checkBirthdayMugs();*/
            $(document).ready(function(){
                checkExpiredMugs();
                checkBirthdayMugs();
                var mailCheck = setInterval(function(){
                    if(!expiredFlag && !birthFlag)
                    {
                        clearInterval(mailCheck);
                        if(localStorageUtil.getLocal('foundM1') == '1' ||
                            localStorageUtil.getLocal('foundM3') == '1')
                        {
                            $('.notification-indicator').addClass('notification-animate-cls');
                            $('.notification-indicator-mobile').addClass('notification-animate-cls');
                            $('.notification-indicator-big').addClass('notification-animate-cls');
                            localStorageUtil.setLocal('foundM2','0',(23 * 60 * 60 * 1000));
                        }
                        else
                        {
                            localStorageUtil.setLocal('foundM2','0',(23 * 60 * 60 * 1000));
                            $('.notification-indicator').removeClass('notification-animate-cls');
                            $('.notification-indicator-mobile').removeClass('notification-animate-cls');
                            $('.notification-indicator-big').removeClass('notification-animate-cls');
                        }
                    }
                },100);
            });

        <?php
    }
?>

$(document).on('click','.homePage .request-otp', function(){

    var loc = $('#locSelect option:selected').val();
    if(loc == '')
    {
        bootbox.alert('Please Select A Location!');
        return false;
    }
    var errUrl = base_url+'generateOtp';
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url:base_url+'generateOtp',
        data: {loc:loc},
        success: function(data){
            if(data.status == true)
            {
                $('#locSelect').addClass('hide');
                $('.loclabel').html('Selected Location: '+$('#locSelect option:selected').text());
                $('.homePage .my-timer').removeClass('hide');
                if(typeof data.mobNum  !== 'undefined' && data.mobNum != null)
                {
                    $('.homePage #mainLoginForm').find('input[name="mobNum"]').val(data.mobNum);
                }
                else
                {
                    $('.homePage #mainLoginForm').find('input[name="mobNum"]').val(data.email);
                }
                $('.homePage .request-otp').addClass('hide');
                $('.homePage #mainLoginForm').removeClass('hide');
                var min = 0;
                var sec = 0;
                var timer = setInterval(function(){
                    sec +=1;
                    if(sec == 60)
                    {
                        min += 1;
                        sec = 0;
                    }
                    $('.homePage .my-timer').html('Wait: '+min+' : '+sec);
                    if(min >= 2)
                    {
                        clearInterval(timer);
                    }
                },1000);
                setTimeout(function(){
                    $('#locSelect').removeClass('hide');
                    $('.homePage .request-otp').removeClass('hide');
                    $('.homePage .my-timer').addClass('hide');
                    $('.loclabel').html('Select Location: ');
                    //$('#mainLoginForm').addClass('hide');
                    clearInterval(timer);
                },(2*60*1000));
            }
            else
            {
                $('.homePage .login-error-block').css('color','red').html(data.errorMsg);
            }
        },
        error: function(xhr, status, error){
            bootbox.alert('Some Error Occurred!');
            var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
            saveErrorLog(err);
        }
    });
});

    $(document).on('click','.loginPage .request-otp', function(){
        if($('.loginPage input[name="mobEmail"]').val() == '')
        {
            bootbox.alert('Field Required!');
            return false;
        }
        if(!$.isNumeric($('.loginPage input[name="mobEmail"]').val()) && !isEmailValid($('.loginPage input[name="mobEmail"]').val()))
        {
            bootbox.alert('Email Invalid!');
            return false;
        }
        var errUrl = base_url+'getOtp';
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url:base_url+'getOtp',
            data: {mobEmail: $('.loginPage input[name="mobEmail"]').val()},
            success: function(data){
                if(data.status == true)
                {
                    $('.loginPage .login-error-block').html('').addClass('hide');
                    $('.loginPage .my-timer').removeClass('hide');
                    if(typeof data.mobNum  !== 'undefined' && data.mobNum != null)
                    {
                        $('.loginPage #mainLoginForm').find('input[name="mobNum"]').val(data.mobNum);
                    }
                    else
                    {
                        var emailId = data.email;
                        $('.loginPage #mainLoginForm').find('input[name="mobNum"]').val(emailId);
                    }
                    $('.loginPage .request-otp').addClass('hide');
                    $('.loginPage .the-email-panel').addClass('hide');
                    $('.loginPage #mainLoginForm').removeClass('hide');
                    var min = 0;
                    var sec = 0;
                    var timer = setInterval(function(){
                        sec +=1;
                        if(sec == 60)
                        {
                            min += 1;
                            sec = 0;
                        }
                        $('.loginPage .my-timer').html('Wait(2 mins): '+min+' : '+sec);
                        if(min >= 2)
                        {
                            clearInterval(timer);
                        }
                    },1000);
                    setTimeout(function(){
                        $('.loginPage .request-otp').removeClass('hide');
                        $('.loginPage .my-timer').addClass('hide');
                        //$('#mainLoginForm').addClass('hide');
                        clearInterval(timer);
                    },(2*60*1000));
                }
                else
                {
                    $('.loginPage .login-error-block').css('color','red').html(data.errorMsg);
                }
            },
            error: function(xhr, status, error){
                bootbox.alert('Some Error Occurred!');
                var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                saveErrorLog(err);
            }
        });
    });

    function isEmailValid(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }

    function createEventsHigh(eventData)
    {
        var price_data = {};
        price_data["name"] = "Per Person";
        if(eventData.costType == <?php echo EVENT_FREE;?>)
        {
            price_data["value"] = 0;
        }
        else
        {
            price_data["value"] = Number(eventData.eventPrice);
        }

        var postData = {
            'title': eventData.eventName,
            'description' : eventData.eventDescription,
            'venue_name' : eventData.locName+', Doolally Taproom',
            'venue_address' : eventData.locAddress,
            'city' : 'Mumbai',
            'img_url' : '<?php echo MOBILE_URL.EVENT_PATH_THUMB;?>'+eventData.filename,
            'start_date' : eventData.eventDate,
            'start_time' : eventData.startTime+":00",
            'end_date' : eventData.eventDate,
            'end_time' : eventData.endTime+":00",
            'prices_data' : [price_data],
            'organizer_account_name' : '<?php echo EVENT_HIGH_ACCOUNT;?>',
            'organizer_name' : 'Doolally',
            'organizer_email' : 'events@brewcraftsindia.com',
            'organizer_phone' : eventData.creatorPhone
        };
        if(typeof eventData.highId !== 'undefined')
        {
            postData['id'] = eventData.highId;
        }

        var ifError = '';
        showCustomLoader();
        var errUrl = 'https://developer.eventshigh.com/add_or_edit_event?key=D00la11y@ppKeyProd';
        $.ajax({
            type:'POST',
            dataType: 'json',
            contentType: "application/json; charset=utf-8",
            url:'https://developer.eventshigh.com/add_or_edit_event?key=D00la11y@ppKeyProd',
            data: JSON.stringify(postData),
            success: function(data){
                if(data.status == 'error')
                {
                    if(typeof data.message !== 'undefined')
                    {
                        if(typeof data.message_from_server !== 'undefined')
                        {
                            var jPar = $.parseJSON(data.message_from_server);
                            if(typeof jPar.dupes !== 'undefined')
                            {
                                errUrl = base_url+'dashboard/saveEventHighData/'+eventData.eventId;
                                $.ajax({
                                    type:'POST',
                                    dataType:'json',
                                    url:base_url+'dashboard/saveEventHighData/'+eventData.eventId,
                                    data: {id:jPar.dupes[0],status:'success',extraMsg:data.message_from_server},
                                    success: function(subData){
                                        //hideCustomLoader();
                                    },
                                    error: function(xhr, status, error){
                                        //hideCustomLoader();
                                        //bootbox.alert('Some Error Occurred!');
                                        var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                                        saveErrorLog(err);
                                    }
                                });
                                eventData['highId'] = jPar.dupes[0];
                                createEventsHigh(eventData);
                                return false;
                            }
                        }
                        hideCustomLoader();
                        ifError = data.message;
                        bootbox.alert(data.message);
                    }
                }
                if(typeof eventData.highId !== 'undefined')
                {
                    var errUrl = base_url+'dashboard/enableEventHigh';
                    $.ajax({
                        type:'POST',
                        dataType:'json',
                        url:base_url+'dashboard/enableEventHigh',
                        data: {highId: eventData.highId},
                        success: function(subData){
                            hideCustomLoader();
                            if(subData.status === true)
                            {
                                window.location.href = base_url+'dashboard';
                            }
                        },
                        error: function(xhr, status, error){
                            hideCustomLoader();
                            bootbox.alert('Enable/Disable Failed On Eventshigh, Please try again!');
                            var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                            saveErrorLog(err);
                        }
                    });
                }
                else
                {
                    errUrl = base_url+'dashboard/saveEventHighData/'+eventData.eventId;
                    $.ajax({
                        type:'POST',
                        dataType:'json',
                        url:base_url+'dashboard/saveEventHighData/'+eventData.eventId,
                        data: data,
                        success: function(subData){
                            hideCustomLoader();
                            if(subData.status === true)
                            {
                                if(ifError != '')
                                {
                                    bootbox.alert(ifError, function(){
                                       window.location.reload();
                                    });
                                }
                                else
                                {
                                    window.location.reload();
                                }
                            }
                        },
                        error: function(xhr, status, error){
                            hideCustomLoader();
                            bootbox.alert('Failed to save Eventshigh data, Please Try again');
                            var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                            saveErrorLog(err);
                        }
                    });
                }
            },
            error: function(xhr, status, error){
                hideCustomLoader();
                bootbox.alert('Failed to push event data on Eventshigh, Please try again!');
                var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                saveErrorLog(err);
            }
        });
    }

    if(typeof $('#userActive').val() != 'undefined')
    {
        var ifActive = $('#userActive').val();
        if( ifActive == '0')
        {
            window.location.href = base_url+'login/logout';
        }
    }
</script>