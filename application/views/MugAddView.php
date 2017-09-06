<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Mug Club :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="mugClub">
        <div class="container">
            <div class="row">
                <h2><i class="fa fa-beer fa-1x"></i> Add New Mug</h2>
                <hr>
                <br>
                <form action="<?php echo base_url();?>mugclub/save" id="mugNumSave-form" method="post" class="form-horizontal" role="form">
                    <input type="hidden" name="senderEmail" id="senderEmail" value="<?php echo $this->userEmail;?>" />
                    <input type="hidden" name="senderPass" id="senderPass" value="" />
                    <div class="mugNumber-status text-center"></div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="mugNum">Mug No. :</label>
                        <div class="col-sm-10">
                            <input type="number" name="mugNum" class="form-control" id="mugNum" placeholder="Eg. 100" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="mugTag">Mug Tag:</label>
                        <div class="col-sm-10">
                            <input type="text" name="mugTag" class="form-control" id="mugTag" placeholder="Eg. ABC">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="homeBase">HomeBase:</label>
                        <div class="col-sm-10">
                            <?php
                                if(isset($baseLocations))
                                {
                                    ?>
                                    <select class="form-control" name="baseLocation">
                                        <?php
                                        foreach($baseLocations as $key => $row)
                                        {
                                            ?>
                                            <option value="<?php echo $row['id'];?>"><?php echo $row['locName'];?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                        <?php
                                }
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="firstName">First Name:</label>
                        <div class="col-sm-10">
                            <input type="text" name="firstName" class="form-control" id="firstName" placeholder="Eg. John" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="lastName">Last Name:</label>
                        <div class="col-sm-10">
                            <input type="text" name="lastName" class="form-control" id="lastName" placeholder="Eg. Doe">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="mobNum">Mobile No. :</label>
                        <div class="col-sm-10">
                            <input type="number" name="mobNum" maxlength="10" oninput="maxLengthCheck(this)" class="form-control" id="mobNum" placeholder="If not provided use default (9999999999)" required>
                            <div class="mobile-verification-holder"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="email">Email:</label>
                        <div class="col-sm-10">
                            <input type="email" name="emailId" class="form-control" id="email" placeholder="Eg. abc@gmail.com (default: Community Manager's Email)" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="bdate">BirthDate:</label>
                        <div class="col-sm-10">
                            <input type="text" name="birthdate" class="form-control" id="bdate" placeholder="Eg. 12 June 1990 (default: 1 jan 1970)">
                            <div class="birthday-verification-holder"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="invoiceDate">Invoice Date:</label>
                        <div class="col-sm-10">
                            <input type="text" name="invoiceDate" class="form-control" id="invoiceDate" placeholder="Eg. 12 June 2016">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="invoiceNo">Invoice No. :</label>
                        <div class="col-sm-10">
                            <input type="text" name="invoiceNo"
                                   class="form-control" id="invoiceNo" placeholder="Eg. L-50" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="invoiceAmt">Invoice Amount:</label>
                        <div class="col-sm-10">
                            <input type="number" name="invoiceAmt"
                                   class="form-control" id="invoiceAmt" placeholder="Eg. 3000" min="1" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="memberS">Membership Start Date:</label>
                        <div class="col-sm-10">
                            <input type="text" name="memberS"
                                   class="form-control" id="memberS" placeholder="Eg. 12 June 2016">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="memberE">Membership End Date:</label>
                        <div class="col-sm-10">
                            <input type="text" name="memberE"
                                   class="form-control" id="memberE" placeholder="Eg. 12 June 2016">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="mugNotes">Notes:</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="mugNotes" id="mugNotes" rows="5" placeholder="Eg. Anshul's Friend"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-10">
                            <label class="control-label" for="mugMail">
                                <input type="checkbox" name="ifMail" id="mugMail" value="1" />
                                Send Confirmation E-Mail?
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="reset" class="btn btn-success">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
<?php echo $footerView;?>
</body>
<?php echo $globalJs; ?>

<script>
    var mugVerified = 0;
    var mobileVerified = 0;
    var birthVerified = 0;
    $(document).on('focusout','#mugNum', function(){
        if($(this).val() != '')
        {
            var mugNo = $(this).val();
            $('.mugNumber-status').empty();

            var errUrl = '<?php echo base_url();?>mugclub/MugAvailability/json/1/'+mugNo;
            $.ajax({
                type:'get',
                dataType:'json',
                url:'<?php echo base_url();?>mugclub/MugAvailability/json/1/'+mugNo,
                success: function(data){
                    if(data.status === true)
                    {
                        $('.mugNumber-status').css('color','green').html('Mug Number is Available');
                        mugVerified = 1;
                        if(mobileVerified == 1)
                        {
                            if(birthVerified == 1)
                            {
                                $('form button[type="submit"]').removeAttr('disabled');
                            }
                        }
                    }
                    else
                    {
                        $('.mugNumber-status').css('color','red').html(data.errorMsg);
                        mugVerified = 0;
                        $('form button[type="submit"]').attr('disabled','true');
                    }
                },
                error: function(xhr, status, error){
                    $('.mugNumber-status').css('color','red').html('Some Error Occurred');
                    var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                    saveErrorLog(err);
                }
            });
        }
        else
        {
            $('.mugNumber-status').empty();
        }
    });

    $(document).on('focusout','#mobNum', function(){
        if($(this).val() != '')
        {
            var mobNo = $(this).val();
            $('.mobile-verification-holder').empty();

            var errUrl = '<?php echo base_url();?>mugclub/CheckMobileNumber/json/'+mobNo;
            $.ajax({
                type:'get',
                dataType:'json',
                url:'<?php echo base_url();?>mugclub/CheckMobileNumber/json/'+mobNo,
                success: function(data){
                    if(data.status === true)
                    {
                        $('.mobile-verification-holder').css('color','green').html('Mobile Number is Available');
                        mobileVerified = 1;
                        if(mugVerified == 1)
                        {
                            if(birthVerified == 1)
                            {
                                $('form button[type="submit"]').removeAttr('disabled');
                            }
                        }
                    }
                    else
                    {
                        $('.mobile-verification-holder').css('color','red').html(data.errorMsg);
                        mobileVerified = 0;
                        $('form button[type="submit"]').attr('disabled','true');
                    }
                },
                error: function(xhr, status, error){
                    $('.mobile-verification-holder  ').css('color','red').html('Some Error Occurred');
                    var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                    saveErrorLog(err);
                }
            });
        }
        else
        {
            $('.mobile-verification-holder').empty();
        }
    });

    $(document).ready(function(){
        $('form button[type="submit"]').attr('disabled','true');
    });

    $(document).on('focusout','input[name="birthdate"]', function(){
        if($(this).val() != '')
        {
            if(getAge($(this).val()) < 21)
            {
                birthVerified = 0;
                $('.birthday-verification-holder').css('color','red').html('This Member is Under Age (below 21)');
            }
            else
            {
                $('.birthday-verification-holder').empty();
                birthVerified = 1;
                if(mugVerified == 1)
                {
                    if(mobileVerified == 1)
                    {
                        $('form button[type="submit"]').removeAttr('disabled');
                    }
                }
            }
        }
    });

    $(document).on('change','#mugMail',function(){
        var mailCheck = $(this);
        if($(this).is(":checked"))
        {
            $('form button[type="submit"]').attr('disabled','true');
            var senderEmail = $('#senderEmail').val();
            bootbox.prompt({
                title: "Please provide your Gmail("+senderEmail+") password",
                inputType: 'password',
                callback: function (result) {
                    if(result != null && result != '')
                    {
                        var errUrl = base_url+'mailers/checkGmailLogin';
                        showCustomLoader();
                        var senderPass = result;
                        $.ajax({
                            type:'POST',
                            dataType:'json',
                            url: base_url+'mailers/checkGmailLogin',
                            data:{from:senderEmail,fromPass:senderPass},
                            success: function(data)
                            {
                                hideCustomLoader();
                                if(data.status === false)
                                {
                                    bootbox.alert('Invalid Gmail Credentials!');
                                    $(mailCheck).prop('checked',false);
                                }
                                else
                                {
                                    $('#senderPass').val(senderPass);
                                    $('form button[type="submit"]').removeAttr('disabled');
                                }
                            },
                            error: function(xhr, status, error){
                                hideCustomLoader();
                                bootbox.alert('Some Error Occurred!');
                                var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                                saveErrorLog(err);
                            }
                        });
                    }
                    else
                    {
                        $(mailCheck).prop('checked',false);
                    }
                }
            });
        }
    });

    function getAge(dateString)
    {
        var today = new Date();
        var birthDate = new Date(dateString);
        var age = today.getFullYear() - birthDate.getFullYear();
        var m = today.getMonth() - birthDate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate()))
        {
            age--;
        }
        return age;
    }
    $('#bdate, #invoiceDate, #memberS, #memberE').datetimepicker({
        format: 'YYYY-MM-DD'
    });
    $(document).on('submit','#mugNumSave-form',function(){
        $('form button[type="submit"]').attr('disabled','disabled');
    });
</script>
</html>