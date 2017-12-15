<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Press Mail :: Doolally</title>
	<?php echo $globalStyle; ?>
    <style>
        .cke_editable
        {
            line-height:0.8 !important;
        }
    </style>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="mailPage">
        <div class="container-fluid">
            <div class="row">
                <!--<h2 class="text-center">Send Mail</h2>-->
                <!--<br>-->
                <div class="col-sm-1 col-xs-0"></div>
                <div class="col-sm-10 col-xs-12 mail-content text-center">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#sendMail">Send Mail</a></li>
                        <li><a data-toggle="tab" href="#viewIds">Address Book</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="sendMail" class="tab-pane fade in active">
                            <br>
                            <div class="row">
                                <?php
                                $mailTypes = array();
                                if(!isset($pressMails))
                                {
                                    echo 'No Press Mails Found!';
                                }
                                else
                                {
                                    ?>
                                    <nav class="col-sm-4">
                                        <ul class="nav nav-pills nav-stacked text-left custom-mugs-list">
                                            <?php
                                            if(isset($mailCats) && myIsArray($mailCats))
                                            {
                                                foreach($mailCats as $key => $row)
                                                {
                                                    ?>
                                                    <li>
                                                        <label class="my-pointer-item" for="<?php echo $row['catName'];?>_press">
                                                            <input type="checkbox" id="<?php echo $row['catName'];?>_press" class="mugCheckList"
                                                                   data-pressEmail="<?php echo $row['emails'];?>" />
                                                            <?php echo ucfirst($row['catName']);?>
                                                        </label>
                                                    </li>
                                                    <?php
                                                }
                                            }
                                            else
                                            {
                                                echo 'No Mails Found!';
                                            }
                                            ?>
                                        </ul>
                                    </nav>
                                    <div class="col-sm-8">
                                        <form action="<?php echo base_url();?>mailers/sendPressMails/json" id="mainMailerForm" method="post" class="form-horizontal" role="form">
                                            <input type="hidden" name="senderEmail" id="senderEmail" value="<?php echo $loggedEmail;?>"/>
                                            <input type="hidden" name="senderPass" id="senderPass" value=""/>
                                            <div class="form-group">
                                                <label class="control-label col-sm-2" for="toList">To:</label>
                                                <div class="col-sm-10">
                                                    <div class="row">
                                                        <div class="col-xs-11">
                                                            <textarea class="form-control" name="pressEmails" id="toList" placeholder="Email Id(s) (comma separated)" readonly></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <!--<button type="button" class="btn btn-danger col-sm-2 my-marginDown" data-toggle="modal" data-target="#subjectModal" >Select Subject</button>-->
                                                <div class="col-sm-2"></div>
                                                <div class="col-sm-10">
                                                    <input type="text" name="mailSubject" class="form-control" onfocus="whichHasFocus= 1" id="mailSubject" placeholder="Subject">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <!--<button type="button" class="btn btn-danger col-sm-2 my-marginDown" data-toggle="modal" data-target="#bodyModal" >Select Body</button>-->
                                                <div class="col-sm-2"></div>
                                                <div class="col-sm-10">
                                                    <textarea name="mailBody" rows="10" class="form-control" id="mailBody" placeholder="Body"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <!--<button type="button" class="btn btn-danger col-sm-2 my-marginDown" data-toggle="modal" data-target="#bodyModal" >Select Body</button>-->
                                                <label class="control-label col-sm-2" for="attchment">Attachment:</label>
                                                <div class="col-sm-10">
                                                    <label class="radio-inline"><input type="radio" name="attachmentType" value="1" checked>Upload</label>
                                                    <label class="radio-inline"><input type="radio" name="attachmentType" value="2">URL(Comma separated)</label>
                                                    <input type="file" name="attachment" multiple class="form-control" id="attchment" />
                                                    <textarea name="attachmentUrls" class="form-control hide" rows="5"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-10">
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </div>
                                            </div>
                                        </form>

                                        <div class="progress hide">
                                            <div class="progress-bar progress-bar-striped active" role="progressbar"
                                                 aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                        <div>
                                            <p>Available Tags:</p>
                                            <div class="col-sm-2"></div>
                                            <ul class="col-sm-10 list-inline mugtags-list">
                                                <li class="my-pointer-item"><span class="label label-success">[name]</span></li>
                                                <li class="my-pointer-item"><span class="label label-success">[offercode]</span></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <div id="viewIds" class="tab-pane fade text-left">
                            <a class="btn btn-primary press-add-btn" href="<?php echo base_url().'mailers/add';?>">
                                <i class="fa fa-plus"></i>
                                Add New Address
                            </a>
                            <table id="press-emails-table" class="table table-hover table-bordered table-striped paginated my-fullWidth">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Category</th>
                                    <th>Publication</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <?php
                                if(isset($pressMails) && myIsArray($pressMails))
                                {
                                    ?>
                                    <tbody>
                                    <?php
                                    foreach($pressMails as $key => $row)
                                    {
                                        ?>
                                        <tr>
                                            <th scope="row"><?php echo $row['id'];?></th>
                                            <td><?php echo $row['pressName'];?></td>
                                            <td><?php echo $row['pressEmail'];?></td>
                                            <td><?php echo $row['catName'];?></td>
                                            <td><?php echo $row['publication'];?></td>
                                            <td>
                                                <?php
                                                    if($row['ifActive'] == ACTIVE)
                                                    {
                                                        ?>
                                                        <a data-toggle="tooltip" title="Active" data-pressId="<?php echo $row['id']; ?>" href="#" class="make-press-inactive">
                                                            <i class="fa fa-15x fa-lightbulb-o my-success-text"></i></a>
                                                        <?php
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                        <a data-toggle="tooltip" title="Not Active" data-pressId="<?php echo $row['id']; ?>" href="#" class="make-press-active">
                                                            <i class="fa fa-15x fa-lightbulb-o my-error-text"></i></a>
                                                        <?php
                                                    }
                                                ?>
                                                <a data-toggle="tooltip" title="Edit" href="<?php echo base_url().'mailers/edit/'.$row['id'];?>">
                                                    <i class="glyphicon glyphicon-edit"></i></a>&nbsp;
                                                <a data-toggle="tooltip" class="pressDelete-icon" title="Delete" data-pressId = "<?php echo $row['id'];?>">
                                                    <i class="fa fa-trash-o"></i></a>&nbsp;
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    </tbody>
                                    <?php
                                }
                                ?>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-sm-1 col-xs-0"></div>
            </div>
        </div>
    </main>
    <?php echo $footerView; ?>
</body>
<?php echo $globalJs; ?>

<script>
    CKEDITOR.replace( 'mailBody' );
    CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
    CKEDITOR.config.shiftEnterMode = CKEDITOR.ENTER_P;
    var whichHasFocus = 0;
    $(document).on('change','input[name="attachmentType"]',function(){
        if($(this).val() == '1')
        {
            $('input[name="attachment"]').removeClass('hide');
            $('textarea[name="attachmentUrls"]').addClass('hide');
        }
        else
        {
            $('input[name="attachment"]').addClass('hide');
            $('textarea[name="attachmentUrls"]').removeClass('hide');
        }
    });
    //var xhr;
    var filesArr = [];
    $(document).on('change','input[name=attachment]', function(e){

        var totalSize = 0;
        var fileSizeExceed = false;
        for(var f=0;f<this.files.length;f++)
        {
            totalSize += this.files[f].size;
            if(this.files[f].size/(1000*1000) >= 10 )
            {
                fileSizeExceed = true;
                bootbox.alert('<span class="my-danger-text">File: '+this.files[f].name+' size is more than 10mb!</span>');
                return false;
            }
        }
        if(fileSizeExceed === true)
        {
            return false;
        }
        else if((totalSize/(1000*1000)) >= 25)
        {
            bootbox.alert('<span class="my-danger-text">Upload Limit 25Mb Reached!</span>');
            return false;
        }


        $('button[type="submit"]').attr('disabled','true');
        $('.progress').removeClass('hide');
        var xhr = [];
        var totalFiles = this.files.length;
        if(filesArr.length != 0)
        {
            filesArr = [];
        }
        for(var i=0;i<totalFiles;i++)
        {
            xhr[i] = new XMLHttpRequest();
            (xhr[i].upload || xhr[i]).addEventListener('progress', function(e) {
                var done = e.position || e.loaded;
                var total = e.totalSize || e.total;
                $('.progress-bar').css('width', Math.round(done/total*100)+'%').attr('aria-valuenow', Math.round(done/total*100)).html(parseInt(Math.round(done/total*100))+'%');
            });
            xhr[i].addEventListener('load', function(e) {
                $('button[type="submit"]').removeAttr('disabled');
            });
            xhr[i].open('post', '<?php echo base_url();?>mailers/uploadFiles', true);

            var data = new FormData;
            data.append('attachment', this.files[i]);
            xhr[i].send(data);
            xhr[i].onreadystatechange = function(e) {
                if (e.srcElement.readyState == 4 && e.srcElement.status == 200) {
                    filesArr.push(e.srcElement.responseText);
                }
            }
        }
    });

    $(document).on('submit','#mainMailerForm',function(e){
        e.preventDefault();
        var formVar = $(this);
        if($('textarea[name="pressEmails"]').val() == '')
        {
            bootbox.alert('Email(s) Are Required!',function(){
                $('textarea[name="pressEmails"]').focus();
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
        submitPressForm(formVar,'','');
        /*bootbox.prompt({
            title: "Please provide your Gmail("+senderEmail+") password",
            inputType: 'password',
            callback: function (result) {
                if(result != null && result != '')
                {
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
                                submitPressForm(formVar,senderEmail,senderPass);
                            }
                        },
                        error: function(xhr, status, error){
                            hideCustomLoader();
                            bootbox.alert('Some Error Occurred!');
                            var err = '<pre>'+xhr.responseText+'</pre>';
                            saveErrorLog(err);
                        }
                    });
                }
            }
        });*/

        //showCustomLoader();

    });

    function submitPressForm(form,senderEmail,senderPass)
    {
        var m_data = new FormData();
        m_data.append( 'pressEmails', $('textarea[name=pressEmails]').val());
        m_data.append( 'mailSubject', $('input[name=mailSubject]').val());
        m_data.append( 'mailBody', $('textarea[name=mailBody]').val());
        if(filesArr.length != 0)
        {
            m_data.append( 'attachment', filesArr.join());
        }
        else
        {
            m_data.append( 'attachmentUrls', $('textarea[name=attachmentUrls]').val());
        }
        m_data.append('senderEmail',senderEmail);
        m_data.append('senderPass',senderPass);
        var lastMailCount,updateInterval;
        showCustomLoader();
        var errUrl = $(form).attr('action');
        $.ajax({
            type:"POST",
            url:$(form).attr('action'),
            contentType: false,
            processData: false,
            dataType:"json",
            data:m_data,
            success: function(data){
                hideCustomLoader();
                if(data.status === true)
                {
                    //clearInterval(updateInterval);
                    bootbox.alert('Mail Send Successfully', function(){
                        window.location.href=base_url+'mailers';
                    });
                }
                else
                {
                    //clearInterval(updateInterval);
                    if(typeof data.fileName != 'undefined')
                    {
                        bootbox.alert('<span class="my-danger-text">'+data.errorMsg+', File Name: '+data.fileName+'</span>');
                    }
                    else
                    {
                        bootbox.alert('<span class="my-danger-text">'+data.errorMsg+'</span>');
                    }
                }
            },
            error: function(xhr, status, error){
                hideCustomLoader();
                //clearInterval(updateInterval);
                bootbox.alert('<span class="my-danger-text">Some Error occurred</span>');
                var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                saveErrorLog(err);
            }
        });
        /*$.ajax({
            type:'GET',
            url:base_url+'dashboard/getLastMailLog',
            dataType:'json',
            success: function(data){
                lastMailCount = data.id;
                var senderEmail = $(form).find('#senderEmail').val();
                updateInterval = setInterval(function(){getMailsUpdate(senderEmail,lastMailCount);},60000);

            },
            error: function(){
                hideCustomLoader();
                bootbox.alert('Error Connecting To Server!');
            }
        });*/
    }

    /*function getMailsUpdate(senderEmail, lastMailCount)
    {
        var total = $('#toList').val().split(',').length;

        var http = new XMLHttpRequest();
        var url = base_url+'dashboard/getUpdateMailCount';
        var params = "lastId="+lastMailCount+"&senderEmail="+senderEmail;
        http.open("POST", url, true);

        //Send the proper header information along with the request
        http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        http.onreadystatechange = function() {//Call a function when the state changes.
            if(http.readyState == 4 && http.status == 200) {
                var data = $.parseJSON(http.responseText);
                if(typeof data.Count != 'undefined')
                {
                    $('#myCustomBeerLoader .mail-status-head').html('Status: '+data.Count+"/"+total+" Done");
                    $('#myCustomBeerLoader .progress-bar').css('width', Math.round(data.Count/total*100)+'%').attr('aria-valuenow',data.Count).html(parseInt(Math.round(data.Count/total*100))+'%');
                }
            }
        };
        http.send(params);
    }*/

    $(document).on('change', '.mugCheckList', function(){
        var emails= '';
        $('.mugCheckList:checked').each(function(i,val){
            emails += $(val).attr('data-pressEmail').trim();
            if(i != $('.mugCheckList:checked').length-1)
            {
                emails += ',';
            }
        });
        $('.mailPage #toList').empty().append(emails);
    });

    CKEDITOR.on('instanceReady', function(evt) {
        var editor = evt.editor;

        editor.on('focus', function(e) {
            whichHasFocus = 2;
        });
    });

    $(document).on('click','.mugtags-list li', function(){
        var mugTag = $(this).find('span').html();

        if(whichHasFocus == 1)
        {
            $('input[name="mailSubject"]').val($('input[name="mailSubject"]').val()+mugTag);
        }
        else if(whichHasFocus == 2)
        {
            CKEDITOR.instances.mailBody.insertHtml(mugTag);
        }

    });

    $(document).ready(function(){

        $('[data-toggle="tooltip"]').tooltip();
        $('#press-emails-table').DataTable({
            ordering: false
        });
    });

    $(document).on('click','.pressDelete-icon', function(){
        var pressId = $(this).attr('data-pressId');
        bootbox.confirm("Are you sure you want to delete Press Email #"+pressId+" ?", function(result) {
            if(result === true)
            {
                window.location.href='<?php echo base_url();?>mailers/delete/'+pressId;
            }
        });
    });

    $(document).on('click','.make-press-inactive', function(e){
        e.preventDefault();
        var currEl = this;
        var pressId = $(this).attr('data-pressId');
        if(pressId != '')
        {
            showCustomLoader();
            $.ajax({
                type:'GET',
                dataType:'json',
                url:base_url+'mailers/setEmailDeActive/'+pressId,
                success: function(data){
                    hideCustomLoader();
                    if(data.status === true)
                    {
                        $(currEl).removeClass('make-press-inactive').addClass('make-press-active').attr('title','Not Active').attr('data-original-title','Not Active');
                        $(currEl).find('i').removeClass('my-success-text').addClass('my-error-text');
                    }
                    else
                    {
                        bootbox.alert(data.errorMsg);
                    }
                },
                error: function(xhr, status, error){
                    hideCustomLoader();
                    //clearInterval(updateInterval);
                    bootbox.alert('<span class="my-danger-text">Some Error occurred</span>');
                    var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                    saveErrorLog(err);
                }
            });
        }
    });
    $(document).on('click','.make-press-active', function(e){
        e.preventDefault();
        var currEl = this;
        var pressId = $(this).attr('data-pressId');
        if(pressId != '')
        {
            showCustomLoader();
            $.ajax({
                type:'GET',
                dataType:'json',
                url:base_url+'mailers/setEmailActive/'+pressId,
                success: function(data){
                    hideCustomLoader();
                    if(data.status === true)
                    {
                        $(currEl).removeClass('make-press-active').addClass('make-press-inactive').attr('title','Active').attr('data-original-title','Active');
                        $(currEl).find('i').removeClass('my-error-text').addClass('my-success-text');
                    }
                    else
                    {
                        bootbox.alert(data.errorMsg);
                    }
                },
                error: function(xhr, status, error){
                    hideCustomLoader();
                    //clearInterval(updateInterval);
                    bootbox.alert('<span class="my-danger-text">Some Error occurred</span>');
                    var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                    saveErrorLog(err);
                }
            });
        }
    });
</script>

</html>