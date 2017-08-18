<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Doolally Staff Billing</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
<main class="walletPage">
    <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--2-col"></div>
        <div class="mdl-cell mdl-cell--8-col">
            <div class="demo-card-wide mdl-shadow--2dp text-center wallet-check-panel">
                <div class="mdl-card__title">
                    <h2 class="">Complete Billing</h2>
                </div>
                <div class="mdl-card__supporting-text tbl-responsive">
                   <!-- --><?php
/*                        if(isset($billDetails) && myIsMultiArray($billDetails))
                        {
                            */?>
                            <form id="staffBillForm" action="<?php echo base_url();?>getCoupon" method="post">
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label add-wallet">
                                    <input class="mdl-textfield__input" type="text" id="empId" name="empId">
                                    <label class="mdl-textfield__label" for="empId">Employee Id Or Mobile No.</label>
                                </div>
                                <br>
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label add-wallet">
                                    <input class="mdl-textfield__input" type="text" id="billNum" name="billNum">
                                    <label class="mdl-textfield__label" for="billNum">Bill Number</label>
                                </div>
                                <br>
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label add-wallet">
                                    <input class="mdl-textfield__input" type="number" id="billAmount" name="billAmount">
                                    <label class="mdl-textfield__label" for="billAmount">Amount</label>
                                </div>
                                <br>
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label add-wallet">
                                    <input class="mdl-textfield__input" type="number" id="userOtp" name="userOtp">
                                    <label class="mdl-textfield__label" for="userOtp">Enter OTP</label>
                                </div>
                                <div class="timer fa-15x"></div>
                                <br>
                                <button type="button" id="gen-bill-otp" class="mdl-button mdl-js-button mdl-button--primary mdl-js-ripple-effect">
                                    Generate OTP
                                </button>
                                <button type="submit" class="mdl-button mdl-js-button mdl-button--primary mdl-js-ripple-effect hide">
                                    Clear Bill
                                </button>
                            </form>
                            <!--<button type="button" id="viewCoupon" class="mdl-button mdl-js-button mdl-button--primary mdl-js-ripple-effect hide">
                                Didn't Got SMS? View Coupon
                            </button>
                            <h3 class="Coupon-view hide"></h3>-->
                            <?php
/*                        }*/
                        /*else
                        {
                            */?><!--
                            <h3>No Bill To Settle</h3>
                            --><?php
/*                        }*/
                    ?>
                </div>
            </div>
        </div>
        <div class="mdl-cell mdl-cell--2-col"></div>
    </div>
</main>
<?php echo $footerView;?>
</body>
<?php echo $globalJs; ?>
<script>
    var empDetails = {};

    $(document).on('submit','#staffBillForm', function(e){
        e.preventDefault();
        if($('#billNum').val() != '' && $('#billAmount').val() != '' && $('#userOtp').val() != '')
        {
            showCustomLoader();
            $.ajax({
                url: $(this).attr('action'),
                dataType: 'json',
                method: 'POST',
                data: $(this).serialize(),
                success: function(data)
                {
                    hideCustomLoader();
                    if(data.status == true)
                    {
                        //$('.Coupon-view').empty().html('Coupon Code: '+data.couponCode);
                        if(typeof data.smsError != 'undefined')
                        {
                            bootbox.alert(data.smsError);
                            //$('.Coupon-view').removeClass('hide');
                        }
                        else
                        {
                            bootbox.alert('Bill Settled!', function(){
                                window.location.href = base_url+'wallet';
                            });
                            /*setTimeout(function(){
                                $('#viewCoupon').removeClass('hide');
                            },10000);*/
                        }
                    }
                    else
                    {
                        bootbox.alert(data.errorMsg);
                    }
                },
                error: function(xhr, status, error){
                    hideCustomLoader();
                    bootbox.alert('Some Error Occurred!');
                    $('#Coupon-view').addClass('hide');
                    var err = '<pre>'+xhr.responseText+'</pre>';
                    saveErrorLog(err);
                }
            });
        }
        else
        {
            bootbox.alert('All Fields Are Required!');
        }
    });

    $(document).on('click','#viewCoupon', function(){
        $('.Coupon-view').removeClass('hide');
    });

    $(document).on('click','#gen-bill-otp', function(){
        var empId = $('#empId').val();

        if(empId == '')
        {
            bootbox.alert('Please Provide Employee Id or Mobile Number');
            return false;
        }
        showCustomLoader();
        $.ajax({
            type:'POST',
            dataType:'json',
            url: base_url+'home/requestWalletOtp',
            data:{empId: empId},
            success: function(data){
                hideCustomLoader();

                if(data.status == true)
                {
                    bootbox.alert('OTP Send Successfully!');
                    $('#gen-bill-otp').addClass('hide');
                    $('button[type="submit"]').removeClass('hide');
                    var min = 0;
                    var sec = 0;
                    timer = setInterval(function(){
                        sec +=1;
                        if(sec == 60)
                        {
                            min += 1;
                            sec = 0;
                        }
                        $('.walletPage .timer').html('Wait(5 mins): '+min+' : '+sec);
                        if(min >= 5)
                        {
                            clearInterval(timer);
                        }
                    },1000);
                    setTimeout(function(){
                        $('.walletPage .timer').html('');
                        $('.walletPage #gen-bill-otp').removeClass('hide');
                        //$('button[type="submit"]').addClass('hide');
                        clearInterval(timer);
                    },(5*60*1000));
                }
                else
                {
                    bootbox.alert(data.errorMsg);
                }

            },
            error: function(xhr, status ,error){
                hideCustomLoader();
                bootbox.alert('Some Error Occurred!');
                var err = '<pre>'+xhr.responseText+'</pre>';
                saveErrorLog(err);
            }
        });

    });

</script>
</html>