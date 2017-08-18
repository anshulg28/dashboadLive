<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Doolally Staff Edit</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
<main class="editStaff">
    <?php
        if(isset($staffDetails) && myIsMultiArray($staffDetails))
        {
            foreach($staffDetails as $key => $row)
            {
                ?>
                <div class="mdl-grid">
                    <div class="mdl-cell mdl-cell--2-col"></div>
                    <div class="mdl-cell mdl-cell--8-col text-center">
                        <a href="<?php echo base_url().'empDetails';?>" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
                            <i class="fa fa-chevron-left"></i> Go Back
                        </a>
                        <h3>Edit Employee <?php echo $row['empId'];?></h3>
                        <form action="<?php echo base_url();?>updateStaff" method="post">
                            <input type="hidden" name="id" value="<?php echo $row['id'];?>"/>
                            <div class="mdl-grid">
                                <div class="mdl-cell mdl-cell--6-col">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label my-fullWidth">
                                        <input class="mdl-textfield__input" type="text" name="empId" id="empId"
                                               value="<?php echo $row['empId'];?>">
                                        <label class="mdl-textfield__label" for="empId">Employee Id</label>
                                    </div>
                                </div>
                                <div class="mdl-cell mdl-cell--6-col">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label my-fullWidth">
                                        <input class="mdl-textfield__input" type="text" name="firstName" id="firstName"
                                               value="<?php echo $row['firstName'];?>">
                                        <label class="mdl-textfield__label" for="firstName">First Name</label>
                                    </div>
                                </div>
                                <div class="mdl-cell mdl-cell--6-col">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label my-fullWidth">
                                        <input class="mdl-textfield__input" type="text" name="middleName" id="middleName"
                                               value="<?php echo $row['middleName'];?>">
                                        <label class="mdl-textfield__label" for="middleName">Middle Name</label>
                                    </div>
                                </div>
                                <div class="mdl-cell mdl-cell--6-col">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label my-fullWidth">
                                        <input class="mdl-textfield__input" type="text" name="lastName" id="lastName"
                                               value="<?php echo $row['lastName'];?>">
                                        <label class="mdl-textfield__label" for="lastName">Last Name</label>
                                    </div>
                                </div>
                                <div class="mdl-cell mdl-cell--6-col">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label my-fullWidth">
                                        <input class="mdl-textfield__input" type="number" name="walletBalance" id="walletBalance"
                                               value="<?php echo $row['walletBalance'];?>" readonly>
                                        <label class="mdl-textfield__label" for="walletBalance">Wallet Balance</label>
                                    </div>
                                </div>
                                <div class="mdl-cell mdl-cell--6-col">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label my-fullWidth">
                                        <input class="mdl-textfield__input" type="number" name="mobNum" id="mobNum"
                                               value="<?php echo $row['mobNum'];?>">
                                        <label class="mdl-textfield__label" for="mobNum">Mobile Number</label>
                                    </div>
                                </div>
                                <div class="mdl-cell mdl-cell--12-col">
                                    <label for="recurringFreq">Recurring Frequency</label>
                                    <select id="recurringFreq" class="form-control" name="recurringFrequency">
                                        <option value="monthly" <?php if($row['recurringFrequency'] == 'monthly'){echo 'selected';}?>>Monthly</option>
                                        <option value="quarterly" <?php if($row['recurringFrequency'] == 'quarterly'){echo 'selected';}?>>Quarterly</option>
                                        <option value="yearly" <?php if($row['recurringFrequency'] == 'yearly'){echo 'selected';}?>>Yearly</option>
                                    </select>
                                </div>

                                <div class="mdl-cell mdl-cell--6-col">
                                    <label>Recurring?</label>
                                    <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="isRecYes">
                                        <input type="radio" id="isRecYes" class="mdl-radio__button" name="isRecurring" value="1"
                                            <?php if($row['isRecurring'] == '1'){echo 'checked';}?>>
                                        <span class="mdl-radio__label">Yes</span>
                                    </label>
                                    <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="isRecYes">
                                        <input type="radio" id="isRecNo" class="mdl-radio__button" name="isRecurring" value="2"
                                            <?php if($row['isRecurring'] == '2'){echo 'checked';}?>>
                                        <span class="mdl-radio__label">No</span>
                                    </label>
                                </div>
                                <div class="mdl-cell mdl-cell--6-col">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label my-fullWidth">
                                        <input class="mdl-textfield__input" type="number" name="recurringAmt" id="recurringAmt"
                                               value="<?php echo $row['recurringAmt'];?>">
                                        <label class="mdl-textfield__label" for="recurringAmt">Recurring Amount</label>
                                    </div>
                                </div>
                                <div class="mdl-cell mdl-cell--6-col">
                                    <label>Capping?</label>
                                    <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="isCapYes">
                                        <input type="radio" id="isCapYes" class="mdl-radio__button" name="isCapping" value="1"
                                            <?php if($row['isCapping'] == '1'){echo 'checked';}?>>
                                        <span class="mdl-radio__label">Yes</span>
                                    </label>
                                    <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="isCapYes">
                                        <input type="radio" id="isCapNo" class="mdl-radio__button" name="isCapping" value="2"
                                            <?php if($row['isCapping'] == '2'){echo 'checked';}?>>
                                        <span class="mdl-radio__label">No</span>
                                    </label>
                                </div>
                                <div class="mdl-cell mdl-cell--6-col">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label my-fullWidth">
                                        <input class="mdl-textfield__input" type="number" name="cappingAmt" id="cappingAmt"
                                               value="<?php echo $row['cappingAmt'];?>">
                                        <label class="mdl-textfield__label" for="cappingAmt">Capping Amount</label>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">Submit</button>
                        </form>
                    </div>
                    <div class="mdl-cell mdl-cell--2-col"></div>
                </div>
                <?php
            }
        }
    ?>
</main>
</body>
<?php echo $globalJs; ?>

</html>