<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Log Complaint :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="logComplaint">
        <form method="POST" id="complaintForm" action="<?php echo base_url().'maintenance/saveComplaint';?>">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-1"></div>
                    <div class="col-xs-10">
                        <div class="form-group">
                            <label>Complaint ID: <?php echo $logId;?></label>
                        </div>
                        <div class="form-group">
                            <label for="workAreaId">Work Area:</label>
                            <select id="workAreaId" name="workAreaId" class="form-control" required>
                                <?php
                                foreach($workAreas as $key => $row)
                                {
                                    ?>
                                    <option value="<?php echo $row['areaId'];?>"><?php echo $row['areaName'];?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="workTypeId">Work Type:</label>
                            <select id="workTypeId" name="workTypeId" class="form-control" required>
                                <option value="">Select</option>
                                <?php
                                foreach($workTypes as $key => $row)
                                {
                                    ?>
                                    <option value="<?php echo $row['typeId'];?>"><?php echo $row['typeName'];?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group subWorkType-panel hide">
                            <label for="subTypeId">Sub Work Type:</label>
                            <select id="subTypeId" name="subTypeId" class="form-control">

                            </select>
                        </div>
                        <div class="form-group">
                            <label for="problemDescription">Problem Description:</label>
                            <textarea id="problemDescription" name="problemDescription" rows="10" cols="20" class="form-control" required></textarea>
                        </div>
                        <?php
                            if(isset($taprooms) && myIsArray($taprooms))
                            {
                                ?>
                                <div class="form-group">
                                    <label for="locId">Location:</label>
                                    <select id="locId" name="locId" class="form-control" required>
                                        <option value="">Select</option>
                                        <?php
                                        foreach($taprooms as $key => $row)
                                        {
                                            ?>
                                            <option value="<?php echo $row['id'];?>"><?php echo $row['locName'];?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <?php
                            }
                        ?>
                        <div class="form-group">
                            <label for="loggedUser">Logging User:</label>
                            <input type="text" id="loggedUser" name="loggedUser" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <label for="problemMedia">Photo/Video Upload</label>
                            <input type="file" class="form-control" id="problemMedia" onchange="uploadMediaChange(this)" />
                            <input type="hidden" name="problemMedia"/>
                        </div>
                        <div class="form-group pri-group">
                            <ul class="list-inline">
                                <li>
                                    <label class="radio-inline job-high-pri"><input type="radio" name="jobPriority" value="1">High</label>
                                </li>
                                <li>
                                    <label class="radio-inline job-medium-pri"><input type="radio" name="jobPriority" value="2">Medium</label>
                                </li>
                                <li>
                                    <label class="radio-inline job-low-pri"><input type="radio" name="jobPriority" value="3">Low</label>
                                </li>
                            </ul>
                        </div>
                        <br>
                        <div class="progress hide">
                            <div class="progress-bar progress-bar-striped active" role="progressbar"
                                 aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <br>
                    </div>
                    <div class="col-xs-1"></div>
                </div>
            </div>
        </form>
    </main>
    <?php echo $footerView; ?>
</body>
<?php echo $globalJs; ?>

<script>
    $(document).on('change','#workTypeId', function(){
        var workId = $(this).val();

        if(workId != '')
        {
            showCustomLoader();
            $.ajax({
                type:'GET',
                dataType:'json',
                url:base_url+'maintenance/getSubType/'+workId,
                success: function(data){
                    hideCustomLoader();
                    if(data.status == true)
                    {
                        var optHtml = '';
                        for(var i=0;i<data.subTypes.length;i++)
                        {
                            optHtml += '<option value="'+data.subTypes[i].subTypeId+'">'+data.subTypes[i].subTypeName+'</option>';
                        }
                        $('#subTypeId').html(optHtml);
                        $('.subWorkType-panel').removeClass('hide');
                    }
                    else
                    {
                        if(!$('.subWorkType-panel').hasClass('hide'))
                        {
                            $('.subWorkType-panel').addClass('hide');
                        }
                        $('#subTypeId').html('');
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
        }
    });

    $(document).on('submit','#complaintForm', function(e){
        e.preventDefault();
        
        if($('#loggedUser').val() == '')
        {
            bootbox.alert('Need Logged User Name!');
            return false;
        }

        /*if($('input[name="problemMedia"]').val() == '')
        {
            bootbox.alert('Photo or Video is required!');
            return false;
        }*/

        if(typeof $('#locId') !== 'undefined' && $('#locId').val() == '')
        {
            bootbox.alert('Location is Required!');
            return false;
        }

        var errUrl = $(this).attr('action');
        $('button[type="submit"]').attr('disabled','disabled');
        showCustomLoader();
        $.ajax({
            type: 'POST',
            dataType:'json',
            url:$(this).attr('action'),
            data:$(this).serialize(),
            success: function(data)
            {
                hideCustomLoader();
                if(data.status === true)
                {
                    window.location.href=base_url+'maintenance';
                }
                else
                {
                    if(typeof data.pageUrl !== 'undefined')
                    {
                        bootbox.alert(data.errorMsg,function(){
                            window.location.href=data.pageUrl;
                        });
                    }
                    else
                    {
                        bootbox.alert(data.errorMsg);
                    }
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

    var filesArr = [];
    function uploadMediaChange(ele)
    {
        $('button[type="submit"]').attr('disabled','true');
        $('.progress').removeClass('hide');
        var xhr = [];
        var totalFiles = ele.files.length;
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
            xhr[i].open('post', '<?php echo base_url();?>maintenance/uploadJobFiles', true);

            var data = new FormData;
            data.append('attachment', ele.files[i]);
            xhr[i].send(data);
            xhr[i].onreadystatechange = function(e) {
                if (e.srcElement.readyState == 4 && e.srcElement.status == 200) {
                    if(e.srcElement.responseText == 'Some Error Occurred!')
                    {
                        bootbox.alert('File size Limit 30MB');
                        return false;
                    }
                    try
                    {
                        var obj = $.parseJSON(e.srcElement.responseText);
                        if(obj.status == false)
                        {
                            bootbox.alert('<label class="my-danger-text">Error: '+obj.errorMsg+'</label>');
                            return false;
                        }
                    }
                    catch(excep)
                    {
                        filesArr = [];
                        filesArr.push(e.srcElement.responseText);
                        fillImgs();
                    }
                }
            }
        }
    }
    function fillImgs()
    {
        $('input[name="problemMedia"]').val(filesArr.join());
    }
</script>

</html>