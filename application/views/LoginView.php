<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Login :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="loginPage">
        <div class="container-fluid">
            <h1 class="text-center">Employee Login</h1>
            <hr>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-8 text-center">
                        <div class="my-timer"></div>
                        <br>
                        <div class="login-error-block text-center"></div>
                        <br>
                        <div class="form-group the-email-panel">
                            <label class="control-label col-sm-2" for="mobEmail">Mobile No/Email:</label>
                            <div class="col-sm-10">
                                <input type="text" name="mobEmail" class="form-control" id="mobEmail" placeholder="Mobile No/Email">
                            </div>
                        </div>
                        <br>
                        <button type="button" class="btn btn-primary request-otp">Request OTP</button>
                        <form action="<?php echo base_url();?>login/checkOtp/json" id="mainLoginForm" method="post" class="form-horizontal hide" role="form">
                            <input type="hidden" name="mobNum" />
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="otp">Enter OTP:</label>
                                <div class="col-sm-10">
                                    <input type="number" name="userOtp" class="form-control" id="otp" placeholder="Enter OTP">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-2"></div>
                </div>
            </div>
            <!--<form action="<?php /*echo base_url();*/?>login/checkUser/json" id="mainLoginForm" method="post" class="form-horizontal" role="form">
                <div class="form-group">
                    <label class="control-label col-sm-2" for="userName">Username:</label>
                    <div class="col-sm-10">
                        <input type="text" name="userName" class="form-control" id="userName" placeholder="Enter Username">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="pwd">Password:</label>
                    <div class="col-sm-10">
                        <input type="password" name="password" class="form-control" id="pwd" placeholder="Enter password">
                    </div>
                </div>-->
                <!--<h2 class="text-center">OR</h2>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="pwd">Login Pin:</label>
                    <div class="col-sm-10">
                        <ul class="list-inline loginpin-list">
                            <li>
                                <input class="form-control" oninput="maxLengthCheck(this)" type="number" maxlength="1" name="loginPin1" placeholder="0" />
                            </li>
                            <li>
                                <input class="form-control" oninput="maxLengthCheck(this)" type="number" maxlength="1" name="loginPin2" placeholder="0" />
                            </li>
                            <li>
                                <input class="form-control" oninput="maxLengthCheck(this)" type="number" maxlength="1" name="loginPin3" placeholder="0" />
                            </li>
                            <li>
                                <input class="form-control" oninput="maxLengthCheck(this)" type="number" maxlength="1" name="loginPin4" placeholder="0" />
                            </li>
                        </ul>
                    </div>
                </div>-->
                <!--<div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-default">Submit</button>
                    </div>
                </div>
            </form>-->
        </div>
        <div id="accountModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form action="<?php echo base_url();?>login/choiceOtp" method="POST" id="choiceOtp">

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </main>
    <?php echo $footerView; ?>
</body>
<?php echo $globalJs; ?>
<script>
    $(document).on('keyup', 'input[name="loginPin1"]', function(e){
        if($(this).val() != '')
        {
            $('input[name="loginPin2"]').focus();
        }
    });
    $(document).on('keyup', 'input[name="loginPin2"]', function(e){
        if($(this).val() != '')
        {
            $('input[name="loginPin3"]').focus();
        }
        else if(e.keyCode == 8)
        {
            $('input[name="loginPin1"]').val('').focus();
        }
    });
    $(document).on('keyup', 'input[name="loginPin3"]', function(e){
        if($(this).val() != '')
        {
            $('input[name="loginPin4"]').focus();
        }
        else if(e.keyCode == 8)
        {
            $('input[name="loginPin2"]').val('').focus();
        }
    });
    $(document).on('keyup', 'input[name="loginPin4"]', function(e){
        if($(this).val() != '')
        {
            $('#mainLoginForm').submit();
        }
        else if(e.keyCode == 8)
        {
            $('input[name="loginPin3"]').val('').focus();
        }
    });

    $(document).on('submit','#choiceOtp', function(e){
        e.preventDefault();
        if($('#choiceOtp input[name="roleRadio"]').is(':checked'))
        {
            console.log('in');
            var errUrl = $(this).attr('action');
            $('#accountModal').modal('hide');
            showCustomLoader();
            $.ajax({
                type:'POST',
                dataType:'json',
                url:$(this).attr('action'),
                data: $(this).serialize(),
                success: function(data){
                    hideCustomLoader();
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

                },
                error: function(xhr, status, error){
                    hideCustomLoader();
                    bootbox.alert('Some Error Occurred!');
                    var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                    saveErrorLog(err);
                }
            });

        }
    });
</script>
</html>