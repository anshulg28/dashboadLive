<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Setting :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main>
        <div class="container-fluid">
            <h1 class="text-center">Change Password</h1>
            <hr>
            <?php
                if(isset($userData) && myIsArray($userData))
                {
                    if($userData['status'] === true)
                    {
                        foreach($userData['userData'] as $key => $row)
                        {
                            ?>
                            <form action="<?php echo base_url();?>login/changePassword" method="post" class="form-horizontal" role="form">
                                <input type="hidden" name="userId" value="<?php echo $row['userId'];?>"/>
                                <div class="form-group">
                                    <div class="col-sm-2"></div>
                                    <div class="col-sm-10">
                                        <div class="password-status"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="password">New:</label>
                                    <div class="col-sm-10">
                                        <input type="password" name="password" class="form-control" id="password" placeholder="Enter Password">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="pwd">Confirm:</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" id="pwd" placeholder="Confirm password">
                                    </div>
                                </div>
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
            ?>

        </div>
    </main>
</body>
<?php echo $globalJs; ?>
<script>
    $(document).ready(function(){
       $('form button[type="submit"]').attr('disabled','true');
    });

    var passGoodToGo = 0;
    $(document).on('keyup','#pwd',function(){
        if($(this).val() != '')
        {
            if($('#password').val() != $(this).val())
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