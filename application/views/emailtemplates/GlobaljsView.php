<!--not tho change js-->
<script type="text/javascript" src="<?php echo base_url(); ?>asset/js/jquery-2.2.4.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>asset/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>asset/js/moment.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>asset/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>asset/js/bootbox.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>asset/js/material.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>asset/js/getmdl-select.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>asset/js/Chart.min.js"></script>
<script src="http://cdn.ckeditor.com/4.5.10/basic/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>asset/js/progressbar.min.js"></script>
<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBu7Do2fKtcQYdGyoC5glTzRLxs6FKxy4Y&libraries=places"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>asset/mobile/js/jquery.swipebox.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>asset/js/jquery.geocomplete.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>asset/js/doolally-local-session.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>asset/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>asset/js/dataTables.bootstrap.min.js"></script>

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
    $(document).on('submit','#mainLoginForm', function(e){

        $(this).find('.login-error-block').empty();
        $(this).find('button[type="submit"]').attr('disabled','disabled');
        $.ajax({
            type:"POST",
            dataType:"json",
            url:$(this).attr('action'),
            data:$(this).serialize(),
            success: function(data)
            {
                $('#mainLoginForm button[type="submit"]').removeAttr("disabled");
                if(data.status == true)
                {
                    window.location.reload();
                }
                else
                {
                    $('.login-error-block').css('color','red').html(data.errorMsg);
                }
            },
            error:function()
            {
                $('#mainLoginForm button[type="submit"]').removeAttr("disabled");
                $('.login-error-block').css('color','red').html('Some Error Occurred, Try Again!');
            }
        });
        e.preventDefault();
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
        && ($this->userType == ADMIN_USER || $this->userType == EXECUTIVE_USER) )
    {
        ?>
            function checkExpiredMugs()
            {
                $.ajax({
                    type:"GET",
                    dataType:"json",
                    async: true,
                    url:base_url+'mugclub/getAllExpiredMugs/json',
                    success: function(data){
                        if(data.status === true)
                        {
                            localStorageUtil.setLocal('foundM1','1',(23 * 60 * 60 * 1000));
                            $('.notification-indicator').addClass('notification-animate-cls');
                            $('.notification-indicator-mobile').addClass('notification-animate-cls');
                            $('.notification-indicator-big').addClass('notification-animate-cls');
                        }
                        else
                        {
                            localStorageUtil.setLocal('foundM1','0',(23 * 60 * 60 * 1000));
                        }
                    },
                    error: function(){

                    }
                });
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
                    },
                    error: function(){

                    }
                });
            }

            /*checkExpiredMugs();
            checkExpiringMugs();
            checkBirthdayMugs();*/
            if(localStorageUtil.getLocal('mailCheckDone') == null)
            {
                localStorageUtil.setLocal('foundM2','0',(23 * 60 * 60 * 1000));
                localStorageUtil.setLocal('mailCheckDone','1',(23 * 60 * 60 * 1000));
                checkExpiredMugs();
                //checkExpiringMugs();
                checkBirthdayMugs();
                //write for recurring expired mugs
            }
            else if(localStorageUtil.getLocal('mailCheckDone') == '0') {
                localStorageUtil.setLocal('foundM2','0',(23 * 60 * 60 * 1000));
                localStorageUtil.setLocal('mailCheckDone','1',(23 * 60 * 60 * 1000));
                checkExpiredMugs();
                //checkExpiringMugs();
                checkBirthdayMugs();
            }
            else if(localStorageUtil.getLocal('foundM1') == '1' ||
                    localStorageUtil.getLocal('foundM3') == '1')
            {
                localStorageUtil.setLocal('foundM2','0',(23 * 60 * 60 * 1000));
                $('.notification-indicator').addClass('notification-animate-cls');
                $('.notification-indicator-mobile').addClass('notification-animate-cls');
                $('.notification-indicator-big').addClass('notification-animate-cls');
            }
            else
            {
                localStorageUtil.setLocal('foundM2','0',(23 * 60 * 60 * 1000));
                $('.notification-indicator').removeClass('notification-animate-cls');
                $('.notification-indicator-mobile').removeClass('notification-animate-cls');
                $('.notification-indicator-big').removeClass('notification-animate-cls');
            }

            function removeNotifications()
            {
                if(localStorageUtil.getLocal('foundMails') != null)
                {
                    localStorageUtil.delLocal('foundMails');
                }
                if(localStorageUtil.getLocal('mailCheckDone') != null)
                {
                    localStorageUtil.delLocal('mailCheckDone');
                }
            }

        <?php
    }
?>

$(document).on('click','.homePage .request-otp', function(){
    $.ajax({
        type: 'GET',
        dataType: 'json',
        url:base_url+'generateOtp',
        success: function(data){
            if(data.status == true)
            {
                $('.homePage .my-timer').removeClass('hide');
                $('.homePage #mainLoginForm').find('input[name="mobNum"]').val(data.mobNum);
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
                    $('.homePage .request-otp').removeClass('hide');
                    $('.homePage .my-timer').addClass('hide');
                    //$('#mainLoginForm').addClass('hide');
                    clearInterval(timer);
                },(2*60*1000));
            }
            else
            {
                $('.homePage .login-error-block').css('color','red').html(data.errorMsg);
            }
        },
        error: function(){
            bootbox.alert('Some Error Occurred!');
        }
    });
});

    $(document).on('click','.loginPage .request-otp', function(){
        if($('.loginPage input[name="mobEmail"]').val() == '')
        {
            bootbox.alert('Field Required!');
            return false;
        }
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url:base_url+'getOtp',
            data: {mobEmail: $('.loginPage input[name="mobEmail"]').val()},
            success: function(data){
                if(data.status == true)
                {
                    $('.loginPage .my-timer').removeClass('hide');
                    $('.loginPage #mainLoginForm').find('input[name="mobNum"]').val(data.mobNum);
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
                        $('.loginPage .my-timer').html('Wait: '+min+' : '+sec);
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
            error: function(){
                bootbox.alert('Some Error Occurred!');
            }
        });
    });
</script>