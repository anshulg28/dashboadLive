<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Doolally Staff</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
<main class="walletPage">
    <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--2-col"></div>
        <div class="mdl-cell mdl-cell--8-col text-center">
            <a href="<?php echo base_url().'add';?>" class="add-staff-btn">
                <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored mdl-js-ripple-effect ">
                    Add New Staff Member
                </button>
            </a>

            <div class="mdl-grid tbl-responsive">
                <table id="staffTable" class="mdl-data-table mdl-shadow--2dp" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>Employee Id</th>
                        <th>Name</th>
                        <th>Mobile Number</th>
                        <th>Wallet Balance</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if(isset($staffList) && myIsMultiArray($staffList))
                    {
                        foreach($staffList as $key => $row)
                        {
                            ?>
                            <tr class="<?php if($row['walletBalance'] < 0){echo 'my-danger-text';}?>">
                                <td><?php echo $row['empId'];?></td>
                                <td><?php echo $row['firstName'].' '.$row['middleName'].' '.$row['lastName'];?></td>
                                <td><?php echo $row['mobNum'];?></td>
                                <td><?php echo 'Rs. '.$row['walletBalance'].'/-';?></td>
                                <td>
                                    <?php
                                    if($row['ifActive'] == ACTIVE)
                                    {
                                        ?>
                                        <div for="bulb<?php echo $row['id'];?>" class="mdl-tooltip">Active</div>
                                        <a class="pageTracker" id="bulb<?php echo $row['id'];?>" href="<?php echo base_url().'blockStaff/'.$row['id'];?>">
                                            <i class="fa fa-lightbulb-o fa-15x my-success-text"></i></a>&nbsp;
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                        <div for="bulb<?php echo $row['id'];?>" class="mdl-tooltip">Blocked</div>
                                        <a class="pageTracker blocked-bulb" data-mob="<?php echo $row['mobNum'];?>" id="bulb<?php echo $row['id'];?>" href="#" data-href="<?php echo base_url().'freeStaff/'.$row['id'];?>">
                                            <i class="fa fa-lightbulb-o fa-15x my-danger-text"></i></a>&nbsp;
                                        <?php
                                    }
                                    ?>
                                    <div for="edit<?php echo $row['id'];?>" class="mdl-tooltip">Edit</div>
                                    <?php
                                    if($row['ifActive'] == ACTIVE)
                                    {
                                      ?>
                                        <a class="pageTracker" id="edit<?php echo $row['id'];?>" href="<?php echo base_url().'edit/'.$row['id'];?>">
                                            <i class="fa fa-edit fa-15x"></i></a>&nbsp;
                                      <?php
                                    }
                                    else
                                    {
                                        ?>
                                        <a class="pageTracker" id="edit<?php echo $row['id'];?>" href="#">
                                            <i class="fa fa-edit fa-15x"></i></a>&nbsp;
                                        <?php
                                    }
                                    ?>
                                    <div for="wallet<?php echo $row['id'];?>" class="mdl-tooltip">Manage Wallet</div>
                                    <a class="pageTracker" id="wallet<?php echo $row['id'];?>" href="<?php echo base_url().'walletManage/'.$row['id'];?>">
                                        <i class="fa fa-money fa-15x"></i></a>&nbsp;
                                    <?php
                                        if($this->userType == ADMIN_USER || $this->userType == ROOT_USER)
                                        {
                                            ?>
                                            <div for="delWallet<?php echo $row['id'];?>" class="mdl-tooltip">Delete Wallet</div>
                                            <a class="pageTracker" id="delWallet<?php echo $row['id'];?>" href="<?php echo base_url().'home/delStaffRecord/'.$row['id'];?>">
                                                <i class="fa fa-trash fa-15x"></i></a>&nbsp;
                                            <?php
                                        }
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    else
                    {
                        ?>
                        <tr class="my-danger-text">
                            <td class="text-center" colspan="9">No Data Found!</td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mdl-cell mdl-cell--2-col"></div>
    </div>
</main>
</body>
<?php echo $globalJs; ?>
<script>
    var staffTable = null;

    /*if(typeof $('#staffTable') !== 'undefined')
     {
     staffTable = $('#staffTable').DataTable({
     "ordering": false
     });
     }*/

    $(document).on('click','.pageTracker', function(){
        localStorageUtil.setLocal('tabWalletPage',staffTable.page());
    });

    if(localStorageUtil.getLocal('tabWalletPage') != null)
    {
        staffTable =  $('#staffTable').DataTable({
            "displayStart": localStorageUtil.getLocal('tabWalletPage') * 10,
            "ordering": false
        });
        localStorageUtil.delLocal('tabWalletPage');
    }
    else
    {
        staffTable =  $('#staffTable').DataTable({
            "ordering": false
        });
    }

    $(document).on('click','.blocked-bulb' , function(e) {
        e.preventDefault();

        var mob = $(this).attr('data-mob');
        var staffLink = $(this).attr('data-href');

        if (mob == '' || mob == '<?php echo DEFAULT_STAFF_MOB;?>')
        {
            bootbox.prompt({
                title: "Please provide the staff mobile number",
                inputType: 'number',
                callback: function (result) {
                    if(result != null && result != '')
                    {
                        if(result == '<?php echo DEFAULT_STAFF_MOB;?>')
                        {
                            bootbox.alert('Invalid Mobile Number!');
                            return false;
                        }
                        showCustomLoader();
                        var senderPass = result;

                        var errUrl = staffLink;
                        $.ajax({
                            type:'POST',
                            dataType:'json',
                            url: staffLink,
                            data:{mobNum: senderPass},
                            success: function(data)
                            {
                                hideCustomLoader();
                                if(data.status === true)
                                {
                                    window.location.reload();
                                }
                                else
                                {
                                    bootbox.alert('Mobile Number Already Used!');
                                }
                            },
                            error: function(xhr,status, error){
                                hideCustomLoader();
                                bootbox.alert('Some Error Occurred!');
                                var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                                saveErrorLog(err);
                            }
                        });
                    }
                }
            });
        }
    });
</script>
</html>