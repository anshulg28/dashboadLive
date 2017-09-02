<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Mug Renew :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="locationsAdd">
        <div class="container">
            <div class="row">
                <h2><i class="fa fa-beer"></i> Renew Mug #<?php echo $mugId; ?></h2>
                <hr>
                <br>
                <form id="mug-renew-form" action="<?php echo base_url();?>mugclub/mugRenew/return" method="post" class="form-horizontal" role="form">
                    <input type="hidden" name="senderEmail" id="senderEmail" value="<?php echo $this->userEmail;?>"/>
                    <input type="hidden" name="senderPass" id="senderPass" />
                    <input type="hidden" value="<?php echo $mugId;?>" name="mugId"/>
                    <!--<div class="form-group">
                        <label class="control-label col-sm-2" for="memEnd">Membership End Date :</label>
                        <div class="col-sm-10">
                            <input type="date" name="membershipEnd" class="form-control"
                                    id="memEnd" value="<?php /*echo $mugInfo['mugList'][0]['membershipEnd'];*/?>"/>
                        </div>
                    </div>-->
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="invoNum">Invoice Number :</label>
                        <div class="col-sm-10">
                            <input type="text" name="invoiceNo" class="form-control"
                                   id="invoNum" placeholder="Eg: 0000" required/>
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
<?php echo $footerView;?>
</body>
<?php echo $globalJs; ?>

<script>
    $(document).on('submit','#mug-renew-form', function(e){
        e.preventDefault();
        var renewForm = $(this);
        var senderEmail = $(this).find('#senderEmail').val();
        bootbox.prompt({
            title: "Please provide your Gmail("+senderEmail+") password",
            inputType: 'password',
            callback: function (result) {
                if(result != null && result != '')
                {
                    showCustomLoader();
                    var senderPass = result;

                    var errUrl = base_url+'mailers/checkGmailLogin';
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
                            }
                            else
                            {
                                $(renewForm).find('#senderPass').val(senderPass);
                                renewThisMug(renewForm);
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
            }
        });

    });

    function renewThisMug(postData)
    {
        var errUrl = base_url+'mugclub/mugRenew/json';
        showCustomLoader();
        $.ajax({
            type:"POST",
            dataType:"json",
            url:"<?php echo base_url();?>mugclub/mugRenew/json",
            data:$(postData).serialize(),
            success: function(data)
            {
                hideCustomLoader();
                if(data.status === true)
                {
                    window.location.href=base_url+'mugclub';
                }
                else
                {
                    bootbox.alert('Try again later!');
                }
            },
            error: function(xhr, status, error)
            {
                hideCustomLoader();
                bootbox.alert('Some Error Occurred!');
                var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                saveErrorLog(err);
            }
        });
    }
</script>

</html>