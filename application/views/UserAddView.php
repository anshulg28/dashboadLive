<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>User Add :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="userAdd">
        <div class="container">
            <div class="row">
                <h2><i class="fa fa-user"></i> Add New User</h2>
                <hr>
                <br>
                <form id="userAdd-form" action="<?php echo base_url();?>users/save" method="post" class="form-horizontal" role="form">
                    <div class="username-status"></div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="userName">Username :</label>
                        <div class="col-sm-10">
                            <input type="text" name="userName" class="form-control"
                                    id="userName" placeholder="Eg. abc"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="firstName">First Name:</label>
                        <div class="col-sm-10">
                            <input type="text" name="firstName"
                                   class="form-control" id="firstName" placeholder="Eg. John"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="lastName">Last Name:</label>
                        <div class="col-sm-10">
                            <input type="text" name="lastName"
                                   class="form-control" id="lastName" placeholder="Eg. Doe"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-2" for="emailid">Email :</label>
                        <div class="col-sm-10">
                            <input type="email" name="email"
                                   class="form-control" id="emailid" placeholder="Email (abc@doolally.in)" required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="mobNum">Mobile Number:</label>
                        <div class="col-sm-10">
                            <input type="number" name="mobNum"
                                   class="form-control" id="mobNum" placeholder="Mobile No. (9999999999)" required/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-2">Roles:</label>
                        <div class="col-sm-10">
                            <label for="is_admin">
                                <input type="radio" onchange="toggleEmailField(this)" id="is_admin" name="userLevel" value="1">
                                Administrator (Full Access)
                            </label>
                            <br>
                            <label for="is_executive">
                                <input type="radio" onchange="toggleEmailField(this)" id="is_executive" name="userLevel" value="2">
                                Community Manager
                            </label>
                            <br>
                            <label for="is_server">
                                <input type="radio" onchange="toggleEmailField(this)" id="is_server" name="userLevel" value="3">
                                Server (new mugs, check-ins)
                            </label>
                            <br>
                            <label for="is_wallet">
                                <input type="radio" onchange="toggleEmailField(this)" id="is_wallet" name="userLevel" value="5">
                                Wallet Management
                            </label>
                            <br>
                            <div class="locClass hide">
                                <label for="assignedLoc">Location To Assign: </label>
                                <select class="form-control" id="assignedLoc" name="assignedLoc">
                                    <?php
                                    foreach($locs as $key => $row)
                                    {
                                        ?>
                                        <option value="<?php echo $row['id'];?>"><?php echo $row['locName'];?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
<?php echo $globalJs; ?>

<script>

    /*$(document).ready(function(){
       $('form button[type="submit"]').attr('disabled','true');
    });*/
    var passGoodToGo = 0;
    var userVerified = 0;
    /*$(document).on('keyup','#pass2',function(){
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
    });*/

    $(document).on('focusout','#userName', function(){
        if($(this).val() != '')
        {
            var userName = $(this).val();
            $('.username-status').empty();

            var errUrl = '<?php echo base_url();?>users/checkUserByUsername/'+userName;
            $.ajax({
                type:'get',
                dataType:'json',
                url:'<?php echo base_url();?>users/checkUserByUsername/'+userName,
                success: function(data){
                    if(data.status === true)
                    {
                        $('.username-status').css('color','green').html('Username is Available');
                        userVerified = 1;
                    }
                    else
                    {
                        $('.username-status').css('color','red').html(data.errorMsg);
                        userVerified = 0;
                    }
                },
                error: function(xhr, status, error){
                    $('.username-status').css('color','red').html('Some Error Occurred');
                    var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                    saveErrorLog(err);
                }
            });
        }
        else
        {
            $('.username-status').empty();
        }
    });

    function toggleEmailField(ele)
    {
        if($(ele).val() == '2')
        {
            $('.locClass').removeClass('hide');
        }
        else
        {
            $('.locClass').addClass('hide');
        }
    }
</script>
</html>