<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Setting :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="pinChange">
        <div class="container-fluid">
            <h1 class="text-center">Change Pin</h1>
            <hr>
            <?php
                if(isSessionVariableSet($this->isUserSession) === true)
                {
                   ?>
                    <form action="<?php echo base_url();?>login/changePin/json" id="pinForm" method="post" class="form-horizontal" role="form">
                        <input type="hidden" name="userId" value="<?php echo $userId;?>"/>
                        <div class="form-group">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-10">
                                <div class="password-status"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="password">New:</label>
                            <div class="col-sm-10">
                                <ul class="list-inline loginpin-list">
                                    <li>
                                        <input class="form-control loginPin1" oninput="maxLengthCheck(this)" type="number" maxlength="1" placeholder="0" />
                                    </li>
                                    <li>
                                        <input class="form-control loginPin2" oninput="maxLengthCheck(this)" type="number" maxlength="1" placeholder="0" />
                                    </li>
                                    <li>
                                        <input class="form-control loginPin3" oninput="maxLengthCheck(this)" type="number" maxlength="1" placeholder="0" />
                                    </li>
                                    <li>
                                        <input class="form-control loginPin4" oninput="maxLengthCheck(this)" type="number" maxlength="1" placeholder="0" />
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="pwd">Confirm:</label>
                            <div class="col-sm-10">
                                <ul class="list-inline loginpin-list">
                                    <li>
                                        <input class="form-control loginPin5" oninput="maxLengthCheck(this)" type="number" maxlength="1" placeholder="0" />
                                    </li>
                                    <li>
                                        <input class="form-control loginPin6" oninput="maxLengthCheck(this)" type="number" maxlength="1" placeholder="0" />
                                    </li>
                                    <li>
                                        <input class="form-control loginPin7" oninput="maxLengthCheck(this)" type="number" maxlength="1" placeholder="0" />
                                    </li>
                                    <li>
                                        <input class="form-control loginPin8" oninput="maxLengthCheck(this)" type="number" maxlength="1" placeholder="0" />
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <input type="hidden" name="LoginPin" />
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                    <?php
                }
            else
            {
                redirect(base_url());
            }
            ?>

        </div>
    </main>
    <?php echo $footerView; ?>
</body>
<?php echo $globalJs; ?>
<script>
    $(document).ready(function(){
       $('form button[type="submit"]').attr('disabled','true');
    });

    var passGoodToGo = 0;

    $(document).on('keyup', '.loginPin1', function(e){
        if($(this).val() != '')
        {
            $('.loginPin2').focus();
        }
    });
    $(document).on('keyup', '.loginPin2', function(e){
        if($(this).val() != '')
        {
            $('.loginPin3').focus();
        }
        else if(e.keyCode == 8)
        {
            $('.loginPin1').val('').focus();
        }
    });
    $(document).on('keyup', '.loginPin3', function(e){
        if($(this).val() != '')
        {
            $('.loginPin4').focus();
        }
        else if(e.keyCode == 8)
        {
            $('.loginPin2').val('').focus();
        }
    });
    $(document).on('keyup', '.loginPin4', function(e){
        if(e.keyCode == 8)
        {
            $('.loginPin3').val('').focus();
        }
    });


    $(document).on('keyup', '.loginPin5', function(e){
        if($(this).val() != '')
        {
            $('.loginPin6').focus();
        }
    });
    $(document).on('keyup', '.loginPin6', function(e){
        if($(this).val() != '')
        {
            $('.loginPin7').focus();
        }
        else if(e.keyCode == 8)
        {
            $('.loginPin5').val('').focus();
        }
    });
    $(document).on('keyup', '.loginPin7', function(e){
        if($(this).val() != '')
        {
            $('.loginPin8').focus();
        }
        else if(e.keyCode == 8)
        {
            $('.loginPin6').val('').focus();
        }
    });
    $(document).on('keyup', '.loginPin8', function(e){
        if(e.keyCode == 8)
        {
            $('.loginPin7').val('').focus();
        }
        if($(this).val() != '')
        {
            var newPin = $('.loginPin1').val()+$('.loginPin2').val()+$('.loginPin3').val()+$('.loginPin4').val();
            var confirmPin = $('.loginPin5').val()+$('.loginPin6').val()+$('.loginPin7').val()+$('.loginPin8').val();
            if(newPin != confirmPin)
            {
                passGoodToGo = 0;
                $('.password-status').removeClass('my-success-text').addClass('my-danger-text').html("Pin Doesn't Match!");
                $('form button[type="submit"]').attr('disabled','true');
            }
            else
            {
                $('input[name="LoginPin"]').val(newPin);
                passGoodToGo = 1;
                $('.password-status').removeClass('my-danger-text').addClass('my-success-text').html("Pin Matched!");
                $('form button[type="submit"]').removeAttr('disabled');
            }
        }
    });

    $(document).on('submit','#pinForm',function(e){
        e.preventDefault();
        showCustomLoader();
        $.ajax({
            type:"POST",
            url:$(this).attr('action'),
            dataType:"json",
            data:$(this).serialize(),
            success: function(data){
                hideCustomLoader();
                if(data.status === true)
                {
                    window.location.href= data.pageUrl;
                }
                else
                {
                    $('.password-status').removeClass('my-success-text').addClass('my-danger-text').html(data.errorMsg);
                }
            },
            error: function(){
                hideCustomLoader();
                $('.password-status').removeClass('my-success-text').addClass('my-danger-text').html('Some Error Occurred, Try Later');
            }
        });
    });
</script>
</html>