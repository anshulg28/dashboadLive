<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>User Edit :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="userEdit">
        <div class="container">
            <div class="row">
                <?php
                    if(isset($userInfo) && myIsArray($userInfo))
                    {
                        if($userInfo['status'] === true)
                        {
                            foreach($userInfo['userData'] as $key => $row)
                            {
                                if(isset($row['userId']))
                                {
                                    ?>
                                    <h2><i class="fa fa-user"></i> Edit User #<?php echo $row['userId'];?></h2>
                                    <hr>
                                    <br>
                                    <form id="userEdit-form" action="<?php echo base_url();?>users/save" method="post" class="form-horizontal" role="form">
                                        <input type="hidden" name="userId" value="<?php echo $row['userId'];?>"/>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="userName">Username :</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="userName" class="form-control"
                                                       value="<?php echo $row['userName'];?>" id="userName" placeholder="Eg. abc"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="firstName">First Name:</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="firstName" value="<?php echo $row['firstName'];?>"
                                                       class="form-control" id="firstName" placeholder="Eg. John"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="lastName">Last Name:</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="lastName" value="<?php echo $row['lastName'];?>"
                                                       class="form-control" id="lastName" placeholder="Eg. Doe"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-2"></div>
                                            <div class="col-sm-10">
                                                <div class="password-status"></div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="pass1">Password :</label>
                                            <div class="col-sm-10">
                                                <input type="password" name="pass1"
                                                       class="form-control" id="pass1"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="pass2">Confirm:</label>
                                            <div class="col-sm-10">
                                                <input type="password"
                                                       class="form-control" id="pass2" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-sm-2">Roles:</label>
                                            <div class="col-sm-10">
                                                <label for="is_admin">
                                                    <input type="radio" id="is_admin" name="userLevel" value="1"
                                                        <?php
                                                            if($row['userType'] == ADMIN_USER)
                                                            {
                                                                echo 'checked';
                                                            }
                                                        ?>>
                                                    Administrator (Full Access)
                                                </label>
                                                <br>
                                                <label for="is_executive">
                                                    <input type="radio" id="is_executive" name="userLevel" value="2"
                                                        <?php
                                                        if($row['userType'] == EXECUTIVE_USER)
                                                        {
                                                            echo 'checked';
                                                        }
                                                        ?>>
                                                    Executive (Back Office access only, no check-ins)
                                                </label>
                                                <br>
                                                <label for="is_server">
                                                    <input type="radio" id="is_server" name="userLevel" value="3"
                                                        <?php
                                                        if($row['userType'] == SERVER_USER)
                                                        {
                                                            echo 'checked';
                                                        }
                                                        ?>>
                                                    Server (new mugs, check-ins)
                                                </label>
                                                <br>
                                                <?php
                                                    if($row['userType'] != SERVER_USER)
                                                    {
                                                        ?>
                                                        <input type="email" name="email" value="<?php echo $row['emailId'];?>"
                                                               class="form-control hide" id="emailid" placeholder="Email (abc@doolally.in)" required/>
                                                        <?php
                                                    }
                                                ?>

                                            </div>
                                        </div>
                                        <input type="hidden" name="ifActive" value="<?php echo $row['ifActive'];?>"/>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                    <?php
                                }
                            }
                        }
                        else
                        {
                            echo '<h2 class="my-danger-text text-center>User Not Found!</h2>"';
                        }
                    }
                ?>
            </div>
        </div>
    </main>
</body>
<?php echo $globalJs; ?>

<script>
    var passGoodToGo = 0;
    $(document).on('keyup','#pass2',function(){
        if($(this).val() != '')
        {
            if($('#pass1').val() != $(this).val())
            {
                passGoodToGo = 0;
                $('.password-status').removeClass('my-success-text').addClass('my-danger-text').html("Password Doesn't Match!");
                $('form button[type="submit"]').attr('disabled','true');
            }
            else
            {
                passGoodToGo = 1;
                $('.password-status').removeClass('my-danger-text').addClass('my-success-text').html("Password Matched!");
                $('form button[type="submit"]').removeAttr('disabled');
            }
        }
    });

</script>
</html>