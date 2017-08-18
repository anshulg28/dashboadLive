<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php echo $title; ?></title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="offerPage">
        <?php
            if(isSessionVariableSet($this->isUserSession) === true && $this->userType != SERVER_USER )
            {
                ?>
                <div class="container-fluid">
                    <div class="row">
                        <h2 class="text-center">Welcome <?php echo ucfirst($this->userName); ?></h2>
                        <br>
                        <div class="col-sm-12 text-center">
                            <ul class="list-inline my-mainMenuList">
                                <?php
                                    if($this->userType != OFFERS_USER)
                                    {
                                        ?>
                                        <li>
                                            <a href="<?php echo base_url().'offers/check';?>">
                                                <div class="menuWrap">
                                                    <i class="fa fa-trophy fa-2x"></i>
                                                    <br>
                                                    <span>Offer Check</span>
                                                </div>
                                            </a>
                                        </li>
                                        <?php
                                    }
                                ?>
                                <li>
                                    <a href="<?php echo base_url().'offers/generate';?>">
                                        <div class="menuWrap">
                                            <i class="fa fa-cogs fa-2x"></i>
                                            <br>
                                            <span>Generate Codes</span>
                                        </div>
                                    </a>
                                </li>
                                <?php
                                    if($this->userType != OFFERS_USER)
                                    {
                                        ?>
                                        <li>
                                            <a href="<?php echo base_url().'offers/stats'; ?>">
                                                <div class="menuWrap">
                                                    <i class="glyphicon glyphicon-stats fa-2x"></i>
                                                    <br>
                                                    <span>View Stats</span>
                                                </div>
                                            </a>
                                        </li>
                                        <?php
                                    }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php
            }
            elseif(isSessionVariableSet($this->isUserSession) === false)
            {
                ?>
                <div class="container-fluid">
                    <h2 class="text-center">Login</h2>
                    <hr>
                    <form action="<?php echo base_url();?>login/checkUser/json" id="mainOfferForm" method="post" class="form-horizontal" role="form">
                        <div class="login-error-block text-center"></div>
                        <br>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="email">Username:</label>
                            <div class="col-sm-10">
                                <input type="text" name="userName" class="form-control" id="email" placeholder="Enter Username">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="pwd">Password:</label>
                            <div class="col-sm-10">
                                <input type="password" name="password" class="form-control" id="pwd" placeholder="Enter password">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
                <?php
            }
            else
            {
                echo "You Don't have Permission To Access This Page!";
            }
        ?>

    </main>
</body>
<?php echo $globalJs; ?>

<script>
    $(document).on('submit','#mainOfferForm', function(e){

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
                    window.location.href = data.pageUrl;
                }
                else
                {
                    $('#mainLoginForm .login-error-block').html(data.errorMsg);
                }
            },
            error:function(xhr, status, error)
            {
                $('#mainLoginForm button[type="submit"]').removeAttr("disabled");
                $('#mainLoginForm .login-error-block').html('Some Error Occurred, Try Again!');
                var err = '<pre>'+xhr.responseText+'</pre>';
                saveErrorLog(err);
            }
        });
        e.preventDefault();
    });
</script>
</html>