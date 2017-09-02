<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Doolally Staff Add</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
<main class="editStaff">
    <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--2-col"></div>
        <div class="mdl-cell mdl-cell--8-col text-center">
            <a href="<?php echo base_url().'empDetails';?>" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
                <i class="fa fa-chevron-left"></i> Go Back
            </a>
            <h3>Add Employee</h3>
            <form id="staff-add-form" action="<?php echo base_url();?>saveStaff" method="post">
                <div class="error-dup"></div>
                <div class="error-dup1"></div>
                <div class="mdl-grid">
                    <div class="mdl-cell mdl-cell--6-col">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label my-fullWidth">
                            <input class="mdl-textfield__input" type="text" name="empId" id="empId" required>
                            <label class="mdl-textfield__label" for="empId">Employee Id</label>
                        </div>
                    </div>
                    <div class="mdl-cell mdl-cell--6-col">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label my-fullWidth">
                            <input class="mdl-textfield__input" type="text" name="firstName" id="firstName" required>
                            <label class="mdl-textfield__label" for="firstName">First Name</label>
                        </div>
                    </div>
                    <div class="mdl-cell mdl-cell--6-col">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label my-fullWidth">
                            <input class="mdl-textfield__input" type="text" name="middleName" id="middleName" >
                            <label class="mdl-textfield__label" for="middleName">Middle Name</label>
                        </div>
                    </div>
                    <div class="mdl-cell mdl-cell--6-col">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label my-fullWidth">
                            <input class="mdl-textfield__input" type="text" name="lastName" id="lastName" >
                            <label class="mdl-textfield__label" for="lastName">Last Name</label>
                        </div>
                    </div>
                    <div class="mdl-cell mdl-cell--6-col">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label my-fullWidth">
                            <input class="mdl-textfield__input" type="number" name="walletBalance" id="walletBalance" value="1500" required>
                            <label class="mdl-textfield__label" for="walletBalance">Wallet Balance</label>
                        </div>
                    </div>
                    <div class="mdl-cell mdl-cell--6-col">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label my-fullWidth">
                            <input class="mdl-textfield__input" type="number" name="mobNum" id="mobNum" required>
                            <label class="mdl-textfield__label" for="mobNum">Mobile Number</label>
                        </div>
                        <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect text-left" for="useDefault">
                            <input type="checkbox" id="useDefault" value="1" class="mdl-checkbox__input">
                            <span class="mdl-checkbox__label">No Mobile No. Use Default?</span>
                        </label>
                    </div>
                    <div class="mdl-cell mdl-cell--12-col">
                        <label for="recurringFreq">Recurring Frequency</label>
                        <select id="recurringFreq" class="form-control" name="recurringFrequency">
                            <option value="monthly" selected>Monthly</option>
                            <option value="quarterly">Quarterly</option>
                            <option value="yearly">Yearly</option>
                        </select>
                    </div>

                    <div class="mdl-cell mdl-cell--6-col">
                        <label>Recurring?</label>
                        <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="isRecYes">
                            <input type="radio" id="isRecYes" class="mdl-radio__button" name="isRecurring" value="1" checked>
                            <span class="mdl-radio__label">Yes</span>
                        </label>
                        <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="isRecNo">
                            <input type="radio" id="isRecNo" class="mdl-radio__button" name="isRecurring" value="2">
                            <span class="mdl-radio__label">No</span>
                        </label>
                    </div>
                    <div class="mdl-cell mdl-cell--6-col">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label my-fullWidth">
                            <input class="mdl-textfield__input" type="number" name="recurringAmt" id="recurringAmt"
                            value="1500">
                            <label class="mdl-textfield__label" for="recurringAmt">Recurring Amount</label>
                        </div>
                    </div>
                    <div class="mdl-cell mdl-cell--6-col">
                        <label>Capping?</label>
                        <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="isCapYes">
                            <input type="radio" id="isCapYes" class="mdl-radio__button" name="isCapping" value="1" checked>
                            <span class="mdl-radio__label">Yes</span>
                        </label>
                        <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="isCapNo">
                            <input type="radio" id="isCapNo" class="mdl-radio__button" name="isCapping" value="2">
                            <span class="mdl-radio__label">No</span>
                        </label>
                    </div>
                    <div class="mdl-cell mdl-cell--6-col">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label my-fullWidth">
                            <input class="mdl-textfield__input" type="number" name="cappingAmt" id="cappingAmt"
                                   value="6000">
                            <label class="mdl-textfield__label" for="cappingAmt">Capping Amount</label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">Submit</button>
            </form>
        </div>
        <div class="mdl-cell mdl-cell--2-col"></div>
    </div>
</main>
<?php echo $footerView;?>
</body>
<?php echo $globalJs; ?>

<script>
    var empIdError = false;
    var mobError = false;
    $(document).on('focusout','#empId', function(){
        if($(this).val() != '')
        {
            var empId = $(this).val();

            $.ajax({
                type:'POST',
                dataType:'json',
                url:base_url+'home/checkEmpId',
                data: {empId:empId},
                success: function(data){
                    if(data.status == true)
                    {
                        empIdError = false;
                        $('.error-dup').html('');
                        //$('.error-dup').css('color','green').html('Employee Id Available!');
                        $('button[type="submit"]').removeAttr('disabled');
                    }
                    else
                    {
                        empIdError = true;
                        $('.error-dup').css('color','red').html('Employee Already Exists!');
                        $('button[type="submit"]').attr('disabled','disabled');
                    }
                },
                error: function(){

                }
            });
        }
    });
    $(document).on('focusout', '#mobNum', function(){
        if($(this).val() != '')
        {
            var empId = $(this).val();

            $.ajax({
                type:'POST',
                dataType:'json',
                url:base_url+'home/checkStaffMob',
                data: {mobNum:empId},
                success: function(data){
                    if(data.status == true)
                    {
                        mobError = false;
                        $('.error-dup1').html('');
                        //$('.error-dup').css('color','green').html('Employee Id Available!');
                        $('button[type="submit"]').removeAttr('disabled');
                    }
                    else
                    {
                        mobError = true;
                        $('.error-dup1').css('color','red').html('Mobile Number Already Exists!');
                        $('button[type="submit"]').attr('disabled','disabled');
                    }
                },
                error: function(){

                }
            });
        }
    });
    $(document).on('change','#useDefault', function(){
        if($(this).is(':checked'))
        {
            $('#mobNum').val(<?php echo DEFAULT_STAFF_MOB;?>).attr('readonly','readonly').parent().addClass('is-focused');
            $('#mobNum').focusout();

        }
        else
        {
            $('#mobNum').val('').removeAttr('readonly').parent().removeClass('is-focused');
        }
    });
    $(document).on('keydown', 'input[type=number]', function(e) {
        if ( e.which == 38 || e.which == 40 )
            e.preventDefault();
    });

    $(document).on('submit','#staff-add-form',function(e){
        e.preventDefault();

        if($('#empId').val() == "")
        {
            bootbox.alert('Employee Id Required!');
            return false;
        }
        if($('#firstName').val() == "")
        {
            bootbox.alert('First Name Required!');
            return false;
        }
        if(empIdError)
        {
            bootbox.alert('Employee Already Exists!');
            return false;
        }

        if(mobError)
        {
            bootbox.alert('Mobile Number Already Exists!');
            return false;
        }
        showCustomLoader();
        var errUrl = $(this).attr('action');
        $.ajax({
            type:'POST',
            dataType:'json',
            url:$(this).attr('action'),
            data: $(this).serialize(),
            success: function(data){
                hideCustomLoader();
                if(data.status == true)
                {
                    window.location.href=base_url+'empDetails';
                }
                else
                {
                    $('.error-dup').css('color','red').html(data.errorMsg);
                }
            },
            error: function(xhr, status, error){
                hideCustomLoader();
                bootbox.alert("Some Error Occurred!");
                var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                saveErrorLog(err);
            }
        });
    });
</script>
</html>