<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Doolally wallet Check</title>
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
                    <h2 class="">Check Wallet Balance</h2>
                </div>
                <div class="mdl-card__supporting-text tbl-responsive">
                    <button type="button" id="check-staff-balance" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
                        Check Balance
                    </button>&nbsp;&nbsp;
                    <a href="<?php echo base_url().'staffBill' ?>" id="settle-bill-btn" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored">
                        Settle Bill
                    </a>
                    <br>
                    <form id="walletCheckForm" class="hide" action="<?php echo base_url();?>getWallet" method="post">
                        <ul class="list-inline">
                            <li>
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label add-wallet">
                                    <input class="mdl-textfield__input" type="text" id="userInput" name="userInput">
                                    <label class="mdl-textfield__label" for="userInput">Enter Employee Id or Mobile No.</label>
                                </div>
                            </li>
                            <li>
                                <button type="submit" class="mdl-button mdl-js-button mdl-button--primary mdl-js-ripple-effect">
                                    Check
                                </button>
                            </li>
                        </ul>
                    </form>
                   <!-- <div class="wallet-otp-view hide">
                        <div class="my-timer"></div>
                        <button type="button" id="ask-staff-otp" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent hide">
                            Request OTP
                        </button><br>
                        <input type="hidden" id="userMob"/>
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label add-wallet">
                            <input class="mdl-textfield__input" type="text" id="userOtp" name="userOtp">
                            <label class="mdl-textfield__label" for="userOtp">Enter OTP</label>
                        </div>
                        <button type="button" id="check-staff-otp" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
                            Show Balance
                        </button>
                    </div>-->
                    <h3 class="walletBalance-view hide"></h3>
                    <!--<button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored hide"
                            id="checkinBtn">
                        Check-Inn
                    </button>-->
                    <br>
                    <?php
                        if(isset($checkins) && myIsMultiArray($checkins))
                        {
                            ?>
                                <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Balance</th>
                                        <th class="mdl-data-table__cell--non-numeric">Employee Id</th>
                                        <th class="mdl-data-table__cell--non-numeric">Updated Date/Time</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                            <?php
                            foreach($checkins as $key => $row)
                            {
                                ?>
                                    <tr>
                                        <td><?php echo $row['staffName'];?></td>
                                        <td><?php echo $row['walletBalance'];?></td>
                                        <td><?php echo $row['empId'];?></td>
                                        <td><?php $d = date_create($row['updateDT']); echo date_format($d,DATE_TIME_FORMAT_UI);?></td>
                                        <td>
                                            <a href="<?php echo  base_url().'staffBill/'.$row['id'];?>" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored">
                                                Settle Bill
                                            </a>
                                            <?php
                                            if(isSessionVariableSet($this->userType) === true && ($this->userType == WALLET_USER || $this->userType == ROOT_USER) )
                                            {
                                                ?>
                                                <a href="<?php echo  base_url().'clearBill/'.$row['id'];?>" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored">
                                                    Clear
                                                </a>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php
                            }
                            ?>
                                    </tbody>
                                </table>
                            <?php
                        }
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
    var timer = null;

    $(document).on('click','#check-staff-balance', function(){
        $('#walletCheckForm').removeClass('hide');
    });
    $(document).on('submit','#walletCheckForm', function(e){
        e.preventDefault();
        if($('#userInput').val() != '')
        {
            //$('.walletPage .wallet-otp-view').addClass('hide');
            $('.walletBalance-view').empty();
            //$('#checkinBtn').addClass('hide');
            var errUrl = $(this).attr('action');
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
                        if(data.balance.ifActive == '0')
                        {
                            $('.walletBalance-view').empty().html('Employee Account Disabled!').removeClass('hide');
                            //$('#checkinBtn').addClass('hide');
                        }
                        else
                        {
                            empDetails['staffName'] = data.balance.firstName+' '+data.balance.middleName+' '+data.balance.lastName;
                            empDetails['walletBalance'] = data.balance.walletBalance;
                            empDetails['empId'] = data.balance.empId;
                            var newHtml = 'Name: '+data.balance.firstName+' '+data.balance.middleName+' '+data.balance.lastName+'<br><br>';
                            if(Number(data.balance.walletBalance) > 0)
                            {
                                newHtml += '<span class="bal-wrap alert-success">Wallet Balance: Rs. '+data.balance.walletBalance+'/-</span>';
                                if(data.balance.mobNum == '')
                                {
                                    newHtml += '<br>No Mobile Number!';
                                }
                            }
                            else
                            {
                                newHtml += '<span class="bal-wrap alert-danger">Wallet Balance: Rs. '+data.balance.walletBalance+'/-</span>';
                                if(data.balance.mobNum == '')
                                {
                                    newHtml += '<br>No Mobile Number!';
                                }
                                /*else
                                {
                                    $('#checkinBtn').removeClass('hide');
                                }*/
                            }
                            $('.walletBalance-view').empty().html(newHtml);

                            /*if(data.balance.mobNum != '')
                            {
                                $('.walletPage #userMob').val(data.balance.mobNum);
                                $('.walletPage .wallet-otp-view').removeClass('hide');
                                var min = 0;
                                var sec = 0;
                                timer = setInterval(function(){
                                    sec +=1;
                                    if(sec == 60)
                                    {
                                        min += 1;
                                        sec = 0;
                                    }
                                    $('.walletPage .my-timer').html('Wait(2 mins): '+min+' : '+sec);
                                    if(min >= 2)
                                    {
                                        clearInterval(timer);
                                    }
                                },1000);
                                setTimeout(function(){
                                    $('.walletPage #ask-staff-otp').removeClass('hide');
                                    $('.walletPage .my-timer').addClass('hide');
                                    //$('#mainLoginForm').addClass('hide');
                                    clearInterval(timer);
                                },(2*60*1000));
                            }
                            else
                            {
                                $('.walletBalance-view').removeClass('hide');
                            }*/
                            setTimeout(function(){
                                window.location.reload();
                            },30000);
                            $('.walletBalance-view').removeClass('hide');
                        }

                        if(typeof data.errorMsg != 'undefined')
                        {
                            bootbox.alert(data.errorMsg);
                            $('.walletBalance-view').removeClass('hide');
                            $('#checkinBtn').removeClass('hide');
                        }

                    }
                    else
                    {
                        $('.walletBalance-view').empty().html(data.errorMsg).removeClass('hide');
                        $('#checkinBtn').addClass('hide');
                    }
                },
                error: function(xhr, status, error){
                    hideCustomLoader();
                    bootbox.alert('Some Error Occurred!');
                    $('#checkinBtn').addClass('hide');
                    var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                    saveErrorLog(err);
                }
            });
        }
        else
        {
            bootbox.alert('Input Required!');
        }
    });

    /*$(document).on('click','#checkinBtn', function(){
        if(empDetails['staffName'] != '')
        {
            showCustomLoader();
            $.ajax({
                dataType: 'json',
                url: base_url+'checkinStaff',
                method: 'POST',
                data: empDetails,
                success: function(data){
                    hideCustomLoader();
                    if(data.status == true)
                    {
                        window.location.reload();
                    }
                    else
                    {
                        bootbox.alert(data.errorMsg);
                    }
                },
                error: function(){
                    hideCustomLoader();
                    bootbox.alert('Some Error Occurred!');
                }
            });
        }
    });

    $(document).on('click', '.walletPage #check-staff-otp', function(){
        if($('.walletPage #userOtp').val() == '')
        {
            bootbox.alert('OTP Required!');
            return false;
        }
        var mob = $('.walletPage #userMob').val();
        var pData = {
            'mob': mob,
            'otp': $('.walletPage #userOtp').val()
        };
        showCustomLoader();
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: base_url+'home/checkOtp',
            data: pData,
            success: function(data){
                hideCustomLoader();
                if(data.status == true)
                {
                    clearInterval(timer);
                    $('.walletPage .wallet-otp-view').addClass('hide');
                    $('.walletBalance-view').removeClass('hide');
                    if(!$('.walletPage .bal-wrap').hasClass('alert-danger'))
                    {
                        $('#checkinBtn').removeClass('hide');
                    }
                }
                else
                {
                    bootbox.alert(data.errorMsg);
                }
            },
            error: function(){
                hideCustomLoader();
                bootbox.alert('Some Error Occurred!');
            }
        });
    });

    $(document).on('click','.walletPage #ask-staff-otp', function(){
        showCustomLoader();
        $(this).addClass('hide');
        $.ajax({
            type:'POST',
            dataType: 'json',
            url:base_url+'home/requestStaffOtp',
            data: {mob:$('.walletPage #userMob').val()},
            success: function(data){
                hideCustomLoader();
                if(data.status == true)
                {
                    clearInterval(timer);
                    var min = 0;
                    var sec = 0;
                    timer = setInterval(function(){
                        sec +=1;
                        if(sec == 60)
                        {
                            min += 1;
                            sec = 0;
                        }
                        $('.walletPage .my-timer').removeClass('hide').html('Wait(2 mins): '+min+' : '+sec);
                        if(min >= 2)
                        {
                            clearInterval(timer);
                        }
                    },1000);
                    setTimeout(function(){
                        $('.walletPage #ask-staff-otp').removeClass('hide');
                        $('.walletPage .my-timer').addClass('hide');
                        //$('#mainLoginForm').addClass('hide');
                        clearInterval(timer);
                    },(2*60*1000));

                }
                else
                {
                    bootbox.alert(data.errorMsg);
                }
            },
            error: function(){
                hideCustomLoader();
                bootbox.alert('Some Error Occurred!');
            }
        });
    });*/
</script>
</html>