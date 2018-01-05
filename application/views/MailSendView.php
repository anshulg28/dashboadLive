<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Send Mail :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="mailPage">
        <div class="container-fluid">
            <div class="row">
                <h2 class="text-center">Send Mail</h2>
                <br>
                <div class="col-sm-1 col-xs-0"></div>
                <div class="col-sm-10 col-xs-12 mail-content text-center">
                    <div class="row">
                        <?php
                            if($mailType == EXPIRED_MAIL || $mailType == EXPIRING_MAIL || $mailType == BIRTHDAY_MAIL)
                            {
                                ?>
                                <form action="<?php echo base_url();?>mailers/sendAllMails/json" id="mainMailerForm" method="post" role="form">
                                    <input type="hidden" name="mailType" value="<?php echo $mailType;?>"/>
                                    <input type="hidden" name="senderEmail" id="senderEmail" value="<?php echo $loggedEmail;?>"/>
                                    <?php
                                        if($this->userEmail == DEFAULT_COMM_EMAIL)
                                        {
                                            ?>
                                            <input type="hidden" name="senderPass" id="senderPass" value="<?php echo DEFAULT_COMM_PASS;?>"/>
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                            <input type="hidden" name="senderPass" id="senderPass" value=""/>
                                            <?php
                                        }
                                    ?>
                                    <nav class="col-sm-2 custom-mugs-list">
                                        <ul class="nav nav-pills nav-stacked text-left">
                                            <?php
                                                if(isset($mailMugs) && myIsArray($mailMugs))
                                                {
                                                    if($mailMugs['status'] === false)
                                                    {
                                                        echo '<li>No List Found</li>';
                                                    }
                                                    else
                                                    {
                                                        foreach($mailMugs['expiryMugList'] as $key => $row)
                                                        {
                                                            ?>
                                                            <li>
                                                                <label for="mug_<?php echo $row['mugId'];?>" class="my-pointer-item">
                                                                    <input type="checkbox" id="mug_<?php echo $row['mugId'];?>" name="mugNums[]"
                                                                           value="<?php echo $row['mugId'];?>" class="mugCheckList" onchange="changeToList()"
                                                                           <?php
                                                                                if(isset($row['emailId']) && $row['emailId'] != '')
                                                                                {
                                                                                    echo 'checked';
                                                                                }
                                                                               else
                                                                               {
                                                                                   echo 'disabled';
                                                                               }
                                                                           ?>>
                                                                    <?php
                                                                        echo $row['mugId'].': '.ucfirst($row['firstName']);
                                                                        if(isset($row['birthDate']))
                                                                        {
                                                                            $bDate = date_create($row['birthDate']);
                                                                            echo ' - '.date_format($bDate,'jS M');
                                                                        }
                                                                    ?>
                                                                </label>
                                                            </li>
                                                            <?php
                                                        }
                                                    }
                                                }
                                            ?>
                                        </ul>
                                    </nav>
                                    <div class="col-sm-10">
                                        <div class="login-error-block text-center"></div>
                                        <br>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="toExpiryList">To:</label>
                                            <div class="col-sm-10 my-marginDown">
                                                <textarea class="form-control" id="toExpiryList"
                                                    <?php
                                                        if($mailMugs['status'] === true)
                                                        {
                                                            echo 'disabled';
                                                        }
                                                    ?>
                                                ><?php
                                                    if($mailMugs['status'] === true)
                                                    {
                                                        echo 'ALL';
                                                    }
                                                    ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php
                                                if($mailType == BIRTHDAY_MAIL)
                                                {
                                                    ?>
                                                    <button type="button" class="btn btn-danger col-sm-2 my-marginDown" data-toggle="modal" data-target="#subjectModal" disabled>Select Subject</button>
                                                    <?php
                                                }
                                                else
                                                {
                                                    ?>
                                                    <button type="button" class="btn btn-danger col-sm-2 my-marginDown" data-toggle="modal" data-target="#subjectModal" >Select Subject</button>
                                                    <?php
                                                }
                                            ?>
                                            <div class="col-sm-10 my-marginDown">
                                                <input type="text" name="mailSubject" class="form-control" id="mailSubject" placeholder="Subject"
                                                       <?php
                                                            if($mailList['status'] === true)
                                                            {
                                                                echo 'readonly';
                                                            }
                                                           else
                                                           {
                                                               ?>
                                                               onfocus="whichHasFocus = 1"
                                                                <?php
                                                           }
                                                       ?>
                                                       />
                                                <?php
                                                    if($mailList['status'] === false)
                                                    {
                                                        ?>
                                                        <input type="hidden" name="isSimpleMail" value="1"/>
                                                        <?php
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php
                                            if($mailType == BIRTHDAY_MAIL)
                                            {
                                                ?>
                                                <button type="button" class="btn btn-danger col-sm-2 my-marginDown" data-toggle="modal" data-target="#bodyModal" disabled>Select Body</button>
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                <button type="button" class="btn btn-danger col-sm-2 my-marginDown" data-toggle="modal" data-target="#bodyModal" >Select Body</button>
                                                <?php
                                            }
                                            ?>
                                            <div class="col-sm-10 my-marginDown">
                                                <textarea name="mailBody" class="form-control" id="mailBody" placeholder="Body" <?php
                                                if($mailList['status'] === true)
                                                {
                                                    echo 'readonly';
                                                }
                                                else
                                                {
                                                    ?>
                                                    onfocus="whichHasFocus = 2"
                                                    <?php
                                                }
                                                ?>></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <?php
                                                if($mailType == BIRTHDAY_MAIL)
                                                {
                                                    ?>
                                                    <button type="submit" class="btn btn-primary" disabled>Send Mail(s)</button>
                                                    <?php
                                                }
                                                else
                                                {
                                                    ?>
                                                    <button type="submit" class="btn btn-primary">Send Mail(s)</button>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <br>
                                        <?php
                                        if($mailList['status'] === false)
                                        {
                                            ?>
                                            <div>
                                                <p>Available Tags:</p>
                                                <div class="col-sm-2"></div>
                                                <ul class="col-sm-10 list-inline mugtags-list">
                                                    <li class="my-pointer-item"><span class="label label-success">[mugno]</span></li>
                                                    <li class="my-pointer-item"><span class="label label-success">[firstname]</span></li>
                                                    <li class="my-pointer-item"><span class="label label-success">[lastname]</span></li>
                                                    <li class="my-pointer-item"><span class="label label-success">[birthdate]</span></li>
                                                    <li class="my-pointer-item"><span class="label label-success">[mobno]</span></li>
                                                    <li class="my-pointer-item"><span class="label label-success">[expirydate]</span></li>
                                                    <li class="my-pointer-item"><span class="label label-success">[sendername]</span></li>
                                                </ul>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </form>
                                <?php
                            }
                            else
                            {
                                ?>
                                <div class="col-sm-1 col-xs-0"></div>
                                <div class="col-sm-10 col-xs-12">
                                    <form action="<?php echo base_url();?>mailers/sendAllMails/json" id="mainMailerForm" method="post" class="form-horizontal" role="form">
                                        <input type="hidden" name="mailType" value="<?php echo $mailType;?>"/>
                                        <input type="hidden" name="isSimpleMail" value="1"/>
                                        <input type="hidden" name="senderEmail" id="senderEmail" value="<?php echo $loggedEmail;?>"/>
                                        <input type="hidden" name="senderPass" id="senderPass" value=""/>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="toList">To:</label>
                                            <div class="col-sm-10">
                                                <div class="row">
                                                    <div class="col-xs-11">
                                                        <textarea class="form-control" name="mugNums" id="toList" placeholder="Mug Numbers (comma separated)"></textarea>
                                                    </div>
                                                    <div class="col-xs-1">
                                                        <i class="fa fa-search fa-2x my-pointer-item" data-toggle="modal" data-target="#searchModal"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button type="button" class="btn btn-danger col-sm-2 my-marginDown" data-toggle="modal" data-target="#subjectModal" >Select Subject</button>
                                            <div class="col-sm-10">
                                                <input type="text" name="mailSubject" class="form-control" onfocus="whichHasFocus = 1" id="mailSubject" placeholder="Subject">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button type="button" class="btn btn-danger col-sm-2 my-marginDown" data-toggle="modal" data-target="#bodyModal" >Select Body</button>
                                            <div class="col-sm-10">
                                                <textarea name="mailBody" rows="10" class="form-control" onfocus="whichHasFocus= 2" id="mailBody" placeholder="Body"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                        <br>
                                        <div>
                                            <p>Available Tags:</p>
                                            <div class="col-sm-2"></div>
                                            <ul class="col-sm-10 list-inline mugtags-list">
                                                <li class="my-pointer-item"><span class="label label-success">[mugno]</span></li>
                                                <li class="my-pointer-item"><span class="label label-success">[firstname]</span></li>
                                                <li class="my-pointer-item"><span class="label label-success">[lastname]</span></li>
                                                <li class="my-pointer-item"><span class="label label-success">[birthdate]</span></li>
                                                <li class="my-pointer-item"><span class="label label-success">[mobno]</span></li>
                                                <li class="my-pointer-item"><span class="label label-success">[expirydate]</span></li>
                                                <li class="my-pointer-item"><span class="label label-success">[sendername]</span></li>
                                                <li class="my-pointer-item"><span class="label label-success">[docode]</span></li>
                                                <li class="my-pointer-item"><span class="label label-success">[brcode]</span></li>
                                            </ul>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-sm-1 col-xs-0"></div>
                                <?php
                            }
                        ?>
                    </div>
                </div>
                <div class="col-sm-1 col-xs-0"></div>
            </div>
        </div>

        <div id="subjectModal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Subject Templates</h4>
                    </div>
                    <div class="modal-body">
                        <?php
                        if($mailList['status'] === true)
                        {
                            ?>
                            <table class="table table-responsive table-bordered table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach($mailList['mailData'] as $key => $row)
                                {
                                    ?>
                                    <tr>
                                        <td class="subject-preview">
                                            <?php echo $row['mailSubject'];?>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-success subject-use-btn">Use</button>
                                        </td>
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
                            echo 'No Subject Templates Found!';
                        }
                        ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="bodyModal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Body Templates</h4>
                    </div>
                    <div class="modal-body row">
                        <?php
                        if($mailList['status'] === true)
                        {
                            ?>
                            <nav class="col-sm-4">
                                <ul class="nav nav-pills nav-stacked text-center body-half-txt">
                                    <?php
                                    foreach($mailList['mailData'] as $key => $row)
                                    {
                                        $truncated_BodyName = (strlen($row['mailBody']) > 24) ? substr($row['mailBody'], 0, 25) . '..' : $row['mailBody'];
                                        ?>
                                        <li class="my-pointer-item">
                                            <?php echo $truncated_BodyName;?>
                                            <textarea class="body-txt hide"><?php echo $row['mailBody'];?></textarea>
                                        </li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                            </nav>
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <div class="col-sm-12 my-marginDown">
                                        <div class="hdden-body hide"></div>
                                        <textarea class="form-control" id="body-preview" disabled>Nothing To Show</textarea>
                                    </div>
                                </div>
                                <br>
                                <div class="form-group text-center">
                                    <button type="button" class="btn btn-primary body-use-btn">Use</button>
                                </div>
                            </div>
                            <?php
                        }
                        else
                        {
                            echo 'No Body Templates Found!';
                        }
                        ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="searchModal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-md">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Search List</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12 modal-table table-responsive">
                                <table id="main-mugclub-table" class="table table-hover table-bordered table-striped my-mail-search-table">
                                    <thead>
                                    <tr>
                                        <th>Mug #</th>
                                        <th>Name</th>
                                        <th>Location</th>
                                        <th>Email</th>
                                    </tr>
                                    </thead>
                                    <?php
                                    if(isset($mugData) && myIsArray($mugData))
                                    {
                                        if($mugData['status'] === false)
                                        {
                                            ?>
                                            <tbody>
                                            <tr class="my-danger-text text-center">
                                                <td colspan="10">No Data Found!</td>
                                            </tr>
                                            </tbody>
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                            <tbody>
                                            <?php
                                            foreach($mugData['mugList'] as $key => $row)
                                            {
                                                ?>
                                                <tr class="my-pointer-item">
                                                    <td class="mugNumber-info">
                                                        <?php echo $row['mugId'];?>
                                                    </td>
                                                    <td>
                                                        <?php echo ucfirst($row['firstName']) .' '.ucfirst($row['lastName']);?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['locName'];?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['emailId'];?>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                            </tbody>
                                            <?php
                                        }
                                    }
                                    ?>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>

    </main>
    <?php echo $footerView; ?>
</body>
<?php echo $globalJs; ?>

<script>
    var whichHasFocus = 0;
    $(document).on('click','#bodyModal .body-half-txt li', function(){
        $('#bodyModal .body-half-txt li').removeClass('my-primary-highlighter');
        $(this).addClass('my-primary-highlighter');
        $('#bodyModal #body-preview').html($(this).find('textarea').html());

    });
    $(document).on('click','#bodyModal .body-use-btn', function(){
        var content = $('#bodyModal #body-preview').html();
        if(content == 'Nothing To Show')
        {
            bootbox.alert('Please Select a Template');
        }
        else
        {
            $('input[name="isSimpleMail"]').val('0');
            $('.mailPage #mailBody').html(content);
            $('#bodyModal').modal('hide');
        }
    });
    $(document).on('click','#subjectModal .subject-use-btn', function(){
        var content = $(this).parent().parent().find('.subject-preview').html().trim();
        $('.mailPage #mailSubject').val(content);
        $('#subjectModal').modal('hide');
    });
    $(window).load(function(){
        $('#main-mugclub-table').DataTable({ordering:  false});
    });
    $("#searchModal").on('show.bs.modal', function () {
        $('.my-mail-search-table td').each(function(i,val){
            if($(val).parent().hasClass('hide'))
            {
                $(val).parent().removeClass('hide');
            }
        });
    });
    $(document).on('click','.my-mail-search-table td', function() {
        var selectedRow = $(this).parent()[0];
        var mugNum = $(selectedRow).find('.mugNumber-info')[0].innerText;
        var previousContent = $('.mailPage #toList').val().trim();

        if(previousContent == '')
        {
            $('.mailPage #toList').val(previousContent+mugNum+',');
        }
        else
        {
            if(previousContent[previousContent.length -1] == ',')
            {
                $('.mailPage #toList').val(previousContent+mugNum+',');
            }
            else
            {
                $('.mailPage #toList').val(previousContent+','+mugNum+',');
            }
        }

        $(this).parent().addClass('hide');

        //$('#searchModal').modal('hide');
    });

    function changeToList()
    {
        var allChecked = 1;
        var newMugList = [];
        $('.mailPage .mugCheckList').each(function(i,val){
            if(!$(val).is(':checked')) {
                allChecked = 0;
            }
            else
            {
                newMugList.push($(val).val());
            }
        });
        if(newMugList.length == 0)
        {
            $('.mailPage #toExpiryList').html('Nothing Selected!');
        }
        $('.mailPage #toExpiryList').html(newMugList.join());
        /*if(allChecked == 0)
        {
            $('.mailPage #toExpiryList').html('MANY');
        }
        else
        {
            $('.mailPage #toExpiryList').html('ALL');
        }*/
    }

    $(document).on('submit','#mainMailerForm',function(e){
        e.preventDefault();
        var formVar = $(this);
        if($('textarea[name="mugNums"]').val() == '')
        {
            bootbox.alert('Mug Numbers Are Required!',function(){
                $('textarea[name="mugNums"]').focus();
            });
            return false;
        }

        if($('input[name="mailSubject"]').val() == '')
        {
            bootbox.alert('Subject is Required!',function(){
                $('input[name="mailSubject"]').focus();
            });
            return false;
        }
        if($('textarea[name="mailBody"]').val() == '')
        {
            bootbox.alert('Body is Required!',function(){
                $('textarea[name="mailBody"]').focus();
            });
            return false;
        }

        var senderEmail = $('#senderEmail').val();

        if($('#senderPass').val() == '')
        {
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
                                }
                                else
                                {
                                    $('#senderPass').val(senderPass);
                                    SubmitMailForm(formVar);
                                }
                            },
                            error: function(xhr,status,error){
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
        else
        {
            SubmitMailForm(formVar);
        }

    });

    function SubmitMailForm(form)
    {
        var errUrl = $(form).attr('action');
        showCustomLoader();
        $.ajax({
            type:"POST",
            url:$(form).attr('action'),
            dataType:"json",
            data:$(form).serialize(),
            success: function(data){
                hideCustomLoader();
                if(data.status === true)
                {
                    bootbox.alert('Mail Send Successfully', function(){
                        window.location.href=base_url+'mailers';
                    });
                    removeNotifications();
                }
            },
            error: function(xhr, status, error){
                hideCustomLoader();
                bootbox.alert('Some Error occurred');
                var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                saveErrorLog(err);
            }
        });
    }

    $(document).on('click','.mugtags-list li', function(){
        var mugTag = $(this).find('span').html();

        if(whichHasFocus == 1)
        {
            $('input[name="mailSubject"]').val($('input[name="mailSubject"]').val()+mugTag);
        }
        else if(whichHasFocus == 2)
        {
            $('textarea[name="mailBody"]').val($('textarea[name="mailBody"]').val()+mugTag);
            //$('textarea[name="mailBody"]').append(mugTag);
        }

    });
</script>

</html>