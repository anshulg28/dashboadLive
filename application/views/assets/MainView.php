<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Assets :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="homePage">
        <div class="container-fluid">
            <div class="row">
                <h2 class="text-center">Welcome <?php echo ucfirst($this->userName); ?></h2>
                <br>
                <div class="col-sm-2 col-xs-0"></div>
                <div class="col-sm-8 col-xs-12">
                    <?php
                    if(myInArray('assets_add',$userModules))
                    {
                        ?>
                        <a href="<?php echo base_url();?>assets/addAsset" class="btn btn-warning">Add New Asset</a>
                        <br>
                        <?php
                    }
                    ?>
                    <?php
                        if(isset($assetsRecord) && myIsArray($assetsRecord))
                        {
                            ?>
                            <table id="assetsTab" class="table table-bordered table-responsive">
                                <thead>
                                    <tr>
                                        <th>Item Type</th>
                                        <th>Item Brand</th>
                                        <th>Location</th>
                                        <th>Item Quantity</th>
                                        <th>Serial Number</th>
                                        <th>Model Number</th>
                                        <th>Purchase Date</th>
                                        <th>Warranty Till</th>
                                        <th>Price</th>
                                        <th>Vendor Name</th>
                                        <th>POC</th>
                                        <th>Assigned To</th>
                                        <th>Status</th>
                                        <th>Comments</th>
                                        <?php
                                        if(myInArray('assets_crud',$userModules))
                                        {
                                            ?>
                                            <th>Actions</th>
                                            <?php
                                        }
                                        ?>
                                    </tr>
                                </thead>
                                <tbody>
                            <?php
                            foreach($assetsRecord as $key => $row)
                            {
                                ?>
                                <tr>
                                    <td><?php echo $row['itemType'];?></td>
                                    <td><?php echo $row['itemBrand'];?></td>
                                    <td>
                                        <?php
                                            if(isset($row['locName']))
                                            {
                                                echo $row['locName'];
                                            }
                                            else
                                            {
                                                echo $row['otherLoc'];
                                            }

                                        ?>
                                    </td>
                                    <td><?php echo $row['itemQty'];?></td>
                                    <td><?php echo $row['serialNum'];?></td>
                                    <td><?php echo $row['modelNum'];?></td>
                                    <td><?php $d = date_create($row['purchaseDate']); echo date_format($d, DATE_FORMAT_UI);?></td>
                                    <td><?php $d = date_create($row['warrantyTill']); echo date_format($d, DATE_FORMAT_UI);?></td>
                                    <td><?php echo 'Rs. '.$row['itemPrice'];?></td>
                                    <td><?php echo $row['vendorName'];?></td>
                                    <td><?php echo $row['pocDoolally'];?></td>
                                    <td><?php echo $row['assignedTo'];?></td>
                                    <td><?php echo $this->config->item('assetsStatus')[$row['currentStatus']];?></td>
                                    <td><?php echo $row['comments'];?></td>
                                    <?php
                                        if(myInArray('assets_crud',$userModules))
                                        {
                                            ?>
                                            <td>
                                                <a data-toggle="tooltip" title="Edit" href="<?php echo base_url().'assets/editItem/'.$row['aId'];?>">
                                                    <i class="glyphicon glyphicon-edit"></i></a>&nbsp;
                                                <a data-toggle="tooltip" class="assetsDelete-icon" title="Delete" data-mugId = "<?php echo $row['aId'];?>">
                                                    <i class="fa fa-trash-o"></i></a>
                                            </td>
                                            <?php
                                        }
                                    ?>
                                </tr>
                                <?php
                            }
                            ?>
                                </tbody>
                            </table>
                            <?php
                        }
                        else
                        {
                            echo 'Nothing Here!';
                        }
                    ?>
                    <br>
                    <form method="POST" class="form hide" id="startQuiz-form">
                        <input type="hidden" name="qid" id="qid"/>
                    </form>
                </div>
                <div class="col-sm-2 col-xs-0"></div>
            </div>
        </div>
    </main>
    <?php echo $footerView;?>
</body>
<?php echo $globalJs; ?>
<script>
    $('#quizServerTab').dataTable();
    $(document).on('click','.start-quiz', function(){
        $(this).attr('disabled','disabled');
        var quizId = $(this).attr('data-quizId');
        showCustomLoader();
        $.ajax({
            type:'GET',
            dataType:'json',
            url:base_url+'quiz/startServerQuiz/'+quizId,
            success: function(data)
            {
                hideCustomLoader();
                if(data.status === true)
                {
                    var popupHtml = '<ul><li>You have 10 minutes for 10 questions</li>' +
                        '<li>You have click on any one option which you think is correct, and click Submit to go to the next question</li>' +
                        '<li>If you do not click on any option within the time limit, you will be taken to the next question automatically</li>' +
                        '<li>There is no negative marking for wrong answers</li>' +
                        '<li>Do NOT close the window before the quiz is completed</li>';
                    bootbox.alert(popupHtml, function(){
                        $('#startQuiz-form #qid').val(quizId);
                        $('#startQuiz-form').attr('action',base_url+'quiz/quizPage/'+data.quizLvl).submit();
                    });
                    /*window.location.reload();*/
                }
                else
                {
                    bootbox.alert(data.errorTxt);
                }
            },
            error:function(xhr, status, error)
            {
                hideCustomLoader();
                bootbox.alert('Some Error Occurred, Try Again!');
                var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                saveErrorLog(err);
            }
        });
    });

    $(document).on('click','.restart-test', function(e){
        e.preventDefault();
        $(this).removeClass('.restart-test');
        var quizId = $(this).attr('data-quizId');
        showCustomLoader();
        $.ajax({
            type:'GET',
            dataType:'json',
            url:base_url+'quiz/restartServerQuiz/'+quizId,
            success: function(data)
            {
                hideCustomLoader();
                if(data.status === true)
                {
                    if(typeof data.isBlocked !== 'undefined')
                    {
                        bootbox.alert('Max Attempts Exceeded! User is blocked!', function(){
                            window.location.reload();
                        });
                    }
                    else
                    {
                        var popupHtml = '<ul><li>You have 10 minutes for 10 questions</li>' +
                            '<li>You have click on any one option which you think is correct, and click Submit to go to the next question</li>' +
                            '<li>If you do not click on any option within the time limit, you will be taken to the next question automatically</li>' +
                            '<li>There is no negative marking for wrong answers</li>' +
                            '<li>Do NOT close the window before the quiz is completed</li>';
                        bootbox.alert(popupHtml, function(){
                            $('#startQuiz-form #qid').val(quizId);
                            localStorageUtil.setLocal('attempNum',data.attempNum);
                            $('#startQuiz-form').attr('action',base_url+'quiz/quizPage/'+data.quizLvl).submit();
                        });
                    }
                    /*window.location.reload();*/
                }
                else
                {
                    bootbox.alert(data.errorTxt);
                }
            },
            error:function(xhr, status, error)
            {
                hideCustomLoader();
                bootbox.alert('Some Error Occurred, Try Again!');
                var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                saveErrorLog(err);
            }
        });
    });
</script>
</html>