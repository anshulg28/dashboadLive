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
    </main>
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
</script>
</html>