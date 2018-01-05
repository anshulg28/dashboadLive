<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Log View :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="logComplaint">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-1 col-xs-0"></div>
                <div class="col-sm-10 col-xs-12">
                    <div class="row">
                        <a class="btn btn-primary" href="<?php echo base_url().'maintenance/logbook';?>">
                            <i class="fa fa-plus"></i>
                            Add New Job</a>
                    </div>
                    <br>
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#open">Open Issues</a></li>
                        <li><a data-toggle="tab" href="#inProgress">In Progress Issues</a></li>
                        <li><a data-toggle="tab" href="#closed">Closed Issues</a></li>
                        <li><a data-toggle="tab" href="#postpone">Postponed Issues</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="open" class="tab-pane fade in active">
                            <table id="openTab" class="table table-hover table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Problem At</th>
                                    <th>Work Type</th>
                                    <th>Problem</th>
                                    <th>Location</th>
                                    <th>Log By</th>
                                    <th>Logged Date/Time</th>
                                    <th>Logged Since</th>
                                    <th>Media</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                    if(!isset($openComplaints) && !myIsArray($openComplaints))
                                    {
                                        ?>
                                        <tr class="my-danger-text text-center">
                                            <td colspan="8">No Records Found!</td>
                                        </tr>
                                        <?php
                                    }
                                    else
                                    {
                                        foreach($openComplaints as $key => $row)
                                        {
                                            ?>
                                            <tr>
                                                <td scope="row">
                                                    <?php
                                                    switch($row['jobPriority'])
                                                    {
                                                        case JOB_PRIORITY_HIGH:
                                                            ?>
                                                            <div class="job-with-high"><?php echo $row['complaintId'];?></div>
                                                            <?php
                                                            break;
                                                        case JOB_PRIORITY_MEDIUM:
                                                            ?>
                                                            <div class="job-with-medium"><?php echo $row['complaintId'];?></div>
                                                            <?php
                                                            break;
                                                        case JOB_PRIORITY_LOW:
                                                            ?>
                                                            <div class="job-with-low"><?php echo $row['complaintId'];?></div>
                                                            <?php
                                                            break;
                                                    }
                                                    ?>
                                                </td>
                                                <td><?php echo $row['areaName'];?></td>
                                                <td><?php
                                                    echo $row['typeName'];
                                                    if(isset($row['subTypeName']) && !empty($row['subTypeName']))
                                                    {
                                                        echo ' ('.$row['subTypeName'].')';
                                                    }
                                                    ?></td>
                                                <td><?php echo $row['problemDescription'];?></td>
                                                <td><?php echo $row['locName'];?></td>
                                                <td><?php echo $row['loggedUser'];?></td>
                                                <!--<td><?php /*echo $row['userName'];*/?></td>-->
                                                <td><?php $d = date_create($row['loggedDT']); echo date_format($d,DATE_TIME_FORMAT_UI);?></td>
                                                <td>
                                                    <time class="timeago time-stamp" datetime="<?php echo $row['loggedDT'];?>"></time>
                                                </td>
                                                <td>
                                                    <?php
                                                    if(isset($row['problemMedia']))
                                                    {
                                                        if($row['pMediaType'] == '1')
                                                        {
                                                            ?>
                                                            <a href="#" class="show-img-media" data-media="<?php echo base_url().JOB_MEDIA_PATH.$row['problemMedia'];?>">View Media</a>
                                                            <?php
                                                        }
                                                        else
                                                        {
                                                            ?>
                                                            <a href="#" class="show-vid-media" data-media="<?php echo base_url().JOB_MEDIA_PATH.$row['problemMedia'];?>">View Media</a>
                                                            <?php
                                                        }
                                                    }
                                                    else
                                                    {
                                                        echo 'No Media!';
                                                    }
                                                    ?>
                                                </td>
                                                <?php
                                                if($row['status'] == LOG_STATUS_DECLINED)
                                                {
                                                    ?>
                                                    <td>Declined (<a href="#" class="view-reason" data-reason="<?php echo htmlspecialchars($row['declineReason']);?>">View Reason</a>)</td>
                                                    <?php
                                                }
                                                ?>

                                            </tr>
                                            <?php
                                        }
                                    }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <div id="inProgress" class="tab-pane fade">
                            <table id="progressTab" class="table table-hover table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Problem At</th>
                                    <th>Work Type</th>
                                    <th>Problem</th>
                                    <th>Location</th>
                                    <th>Update</th>
                                    <th>Approx Cost</th>
                                    <th>Assigned To</th>
                                    <th>Logged Date/Time</th>
                                    <th>Aged Since</th>
                                    <th>Media</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if(!isset($progressComplaints) && !myIsArray($progressComplaints))
                                {
                                    ?>
                                    <tr class="my-danger-text text-center">
                                        <td colspan="9">No Records Found!</td>
                                    </tr>
                                    <?php
                                }
                                else
                                {
                                    foreach($progressComplaints as $key => $row)
                                    {
                                        ?>
                                        <tr>
                                            <td scope="row">
                                                <?php
                                                switch($row['jobPriority'])
                                                {
                                                    case JOB_PRIORITY_HIGH:
                                                        ?>
                                                        <div class="job-with-high"><?php echo $row['complaintId'];?></div>
                                                        <?php
                                                        break;
                                                    case JOB_PRIORITY_MEDIUM:
                                                        ?>
                                                        <div class="job-with-medium"><?php echo $row['complaintId'];?></div>
                                                        <?php
                                                        break;
                                                    case JOB_PRIORITY_LOW:
                                                        ?>
                                                        <div class="job-with-low"><?php echo $row['complaintId'];?></div>
                                                        <?php
                                                        break;
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $row['areaName'];?></td>
                                            <td><?php
                                                echo $row['typeName'];
                                                if(isset($row['subTypeName']) && !empty($row['subTypeName']))
                                                {
                                                    echo ' ('.$row['subTypeName'].')';
                                                }
                                                ?></td>
                                            <td><?php echo $row['problemDescription'];?></td>
                                            <td><?php echo $row['locName'];?></td>
                                            <td><?php echo $row['updateOnComplaint'];?></td>
                                            <td><?php echo $row['approxCost'];?></td>
                                            <td><?php
                                                if(isset($row['assignee']) && $row['assignee'] != '')
                                                {
                                                    echo $row['assignee'];
                                                }
                                                else
                                                {
                                                    echo $row['workAssignedTo'];
                                                }
                                                ?></td>
                                            <td><?php $d = date_create($row['loggedDT']); echo date_format($d,DATE_TIME_FORMAT_UI);?></td>
                                            <td>
                                                <time class="timeago time-stamp" datetime="<?php echo $row['loggedDT'];?>"></time>
                                            </td>
                                            <td>
                                                <?php
                                                if(isset($row['problemMedia']))
                                                {
                                                    if($row['pMediaType'] == '1')
                                                    {
                                                        ?>
                                                        <a href="#" class="show-img-media" data-media="<?php echo base_url().JOB_MEDIA_PATH.$row['problemMedia'];?>">View Media</a>
                                                        <?php
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                        <a href="#" class="show-vid-media" data-media="<?php echo base_url().JOB_MEDIA_PATH.$row['problemMedia'];?>">View Media</a>
                                                        <?php
                                                    }
                                                }
                                                else
                                                {
                                                    echo 'No Media!';
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <div id="closed" class="tab-pane fade">
                            <table id="closeTab" class="table table-hover table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Problem At</th>
                                    <th>Work Type</th>
                                    <th>Problem</th>
                                    <th>Location</th>
                                    <th>Update Date/Time</th>
                                    <th>Approx Cost</th>
                                    <th>Media</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $closeCount = 0;
                                if(!isset($closeComplaints) && !myIsArray($closeComplaints))
                                {
                                    ?>
                                    <tr class="my-danger-text text-center">
                                        <td colspan="8">No Records Found!</td>
                                    </tr>
                                    <?php
                                }
                                else
                                {
                                    foreach($closeComplaints as $key => $row)
                                    {
                                        $ifMedia = true;
                                        ?>
                                        <tr>
                                            <td scope="row">
                                                <?php
                                                switch($row['jobPriority'])
                                                {
                                                    case JOB_PRIORITY_HIGH:
                                                        ?>
                                                        <div class="job-with-high"><?php echo $row['complaintId'];?></div>
                                                        <?php
                                                        break;
                                                    case JOB_PRIORITY_MEDIUM:
                                                        ?>
                                                        <div class="job-with-medium"><?php echo $row['complaintId'];?></div>
                                                        <?php
                                                        break;
                                                    case JOB_PRIORITY_LOW:
                                                        ?>
                                                        <div class="job-with-low"><?php echo $row['complaintId'];?></div>
                                                        <?php
                                                        break;
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $row['areaName'];?></td>
                                            <td><?php
                                                echo $row['typeName'];
                                                if(isset($row['subTypeName']) && !empty($row['subTypeName']))
                                                {
                                                    echo ' ('.$row['subTypeName'].')';
                                                }
                                                ?></td>
                                            <td><?php echo $row['problemDescription'];?></td>
                                            <td><?php echo $row['locName'];?></td>
                                            <td><?php $d = date_create($row['lastUpdateDT']); echo date_format($d,DATE_TIME_FORMAT_UI);?></td>
                                            <td><?php echo $row['approxCost'];?></td>
                                            <td>
                                                <?php
                                                if(isset($row['solutionMedia']))
                                                {
                                                    if($row['sMediaType'] == '1')
                                                    {
                                                        ?>
                                                        <a href="#" class="show-img-media" data-media="<?php echo base_url().JOB_MEDIA_PATH.$row['solutionMedia'];?>">View Media</a>
                                                        <?php
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                        <a href="#" class="show-vid-media" data-media="<?php echo base_url().JOB_MEDIA_PATH.$row['solutionMedia'];?>">View Media</a>
                                                        <?php
                                                    }
                                                }
                                                else
                                                {
                                                    $ifMedia = false;
                                                    echo 'No Media!';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                    if(!$ifMedia)
                                                    {
                                                        ?>
                                                        <a href="#" class="upload-sol-media track-me" data-jobId="<?php echo $row['complaintId'];?>">Upload Media </a>
                                                        <?php
                                                    }
                                                    if($row['status'] == LOG_STATUS_CLOSED)
                                                    {
                                                        ?>
                                                        <a href="#" data-complaintId="<?php echo $row['complaintId'];?>" class="details-complaint">Details</a>
                                                        <?php
                                                    }
                                                    else
                                                    {
                                                        $closeCount++;
                                                        ?>
                                                        <input type="checkbox" data-openMedia="<?php if(isset($row['problemMedia'])){echo '1';}else{echo '0';}?>" name="jobs" data-media="<?php if($ifMedia){echo '1';}else{echo '0';} ?>" value="<?php echo $row['complaintId'];?>"/>
                                                        <?php
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                                </tbody>
                            </table>
                            <br>
                            <?php
                                if($closeCount>0)
                                {
                                    ?>
                                    <button type="button" class="btn btn-warning" id="final-close-jobs">Close Job(s)</button>
                                    <?php
                                }
                            ?>
                        </div>
                        <div id="postpone" class="tab-pane fade">
                            <table id="postponeTab" class="table table-hover table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Problem At</th>
                                    <th>Work Type</th>
                                    <th>Problem</th>
                                    <th>Location</th>
                                    <th>Logged Date/Time</th>
                                    <th>Logged Since</th>
                                    <th>Postponed Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if(!isset($postponeComplaints) && !myIsArray($postponeComplaints))
                                {
                                    ?>
                                    <tr class="my-danger-text text-center">
                                        <td colspan="8">No Records Found!</td>
                                    </tr>
                                    <?php
                                }
                                else
                                {
                                    foreach($postponeComplaints as $key => $row)
                                    {
                                        ?>
                                        <tr>
                                            <td scope="row">
                                                <?php
                                                switch($row['jobPriority'])
                                                {
                                                    case JOB_PRIORITY_HIGH:
                                                        ?>
                                                        <div class="job-with-high"><?php echo $row['complaintId'];?></div>
                                                        <?php
                                                        break;
                                                    case JOB_PRIORITY_MEDIUM:
                                                        ?>
                                                        <div class="job-with-medium"><?php echo $row['complaintId'];?></div>
                                                        <?php
                                                        break;
                                                    case JOB_PRIORITY_LOW:
                                                        ?>
                                                        <div class="job-with-low"><?php echo $row['complaintId'];?></div>
                                                        <?php
                                                        break;
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $row['areaName'];?></td>
                                            <td><?php
                                                echo $row['typeName'];
                                                if(isset($row['subTypeName']) && !empty($row['subTypeName']))
                                                {
                                                    echo ' ('.$row['subTypeName'].')';
                                                }
                                                ?></td>
                                            <td><?php echo $row['problemDescription'];?></td>
                                            <td><?php echo $row['locName'];?></td>
                                            <td><?php $d = date_create($row['loggedDT']); echo date_format($d,DATE_TIME_FORMAT_UI);?></td>
                                            <td>
                                                <time class="timeago time-stamp" datetime="<?php echo $row['loggedDT'];?>"></time>
                                            </td>
                                            <td>
                                                <?php echo $row['postpondDate'];?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-sm-1 col-xs-0"></div>
            </div>
        </div>
    </main>

    <!-- Modal -->
    <div id="mediaModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Media Upload</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="complaintId"/>
                    <div class="form-group">
                        <label for="solutionMedia">Photo/Video Upload</label>
                        <input type="file" class="form-control" id="solutionMedia" onchange="uploadMediaChange(this)" />
                        <input type="hidden" name="solutionMedia" value=""/>
                    </div>
                    <br>
                    <div class="progress hide">
                        <div class="progress-bar progress-bar-striped active" role="progressbar"
                             aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                    <button type="button" class="btn btn-success save-job-media">Submit</button>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal -->
    <div id="mediaViewModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content media-content-wrapper">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body media-body">

                </div>
            </div>

        </div>
    </div>

    <!-- Details Modal -->
    <div id="detailsModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Complaint #<span class="complaint-num"></span> Details</h4>
                </div>
                <div class="modal-body details-body">

                </div>
            </div>
        </div>
    </div>
    <?php echo $footerView;?>
</body>
<?php echo $globalJs; ?>

<script>
    $("time.timeago").timeago();
    var openTab,progressTab,closeTab,postponeTab;

    openTab = $('#openTab').DataTable({
        "ordering": false
    });
    progressTab = $('#progressTab').DataTable({
        "ordering": false
    });
    closeTab = $('#closeTab').DataTable({
        "ordering": false
    });
    postponeTab = $('#postponeTab').DataTable({
        "ordering": false
    });
    $(document).ready(function(){
        if(localStorageUtil.getLocal('currTab') != null)
        {
            var curT = localStorageUtil.getLocal('currTab');
            $('.nav.nav-tabs li a[href="'+curT+'"]').tab('show');
            openTab.page(localStorageUtil.getLocal('openPage')).draw('page');
            progressTab.page(localStorageUtil.getLocal('progressPage')).draw('page');
            closeTab.page(localStorageUtil.getLocal('closePage')).draw('page');
            postponeTab.page(localStorageUtil.getLocal('postponePage')).draw('page');
        }
    });

    $(document).on('click','.view-reason',function(){
        var reason = $(this).attr('data-reason');
        if(reason != '')
        {
            bootbox.alert('Reason: '+reason);
        }
        else
        {
            bootbox.alert('Error: Reason Not Found!');
        }
    });

    $(document).on('click','#final-close-jobs',function(){
        var closeJobs = [];
        var falseMedia = false;
        $('#closeTab input[name="jobs"]').each(function(i,val){
             if($(val).is(':checked'))
             {
                 if($(val).attr('data-openMedia') == '1')
                 {
                     if($(val).attr('data-media') == '0')
                     {
                         falseMedia = true;
                         bootbox.alert('One of the job has missing media proof!');
                         return false;
                     }
                 }
                 closeJobs.push($(val).val());
             }
        });
        if(falseMedia)
        {
            return false;
        }
        if(closeJobs.length>0)
        {
            bootbox.confirm("Sure want to close "+closeJobs.length+" Job(s) now?", function(result) {
                if(result === true)
                {
                    var errUrl = base_url+'maintenance/sendJobOtp';
                    showCustomLoader();
                    $.ajax({
                        type:"POST",
                        dataType:"json",
                        url:base_url+'maintenance/sendJobOtp',
                        data:{compIds: closeJobs.join(',')},
                        success: function(data){
                            hideCustomLoader();
                            if(data.status === true)
                            {
                                if(typeof data.errorMsg !== 'undefined')
                                {
                                    var err = 'Url: '+errUrl+' resp: '+data.errorMsg;
                                    saveErrorLog(err);
                                }
                                checkOtp(closeJobs);
                            }
                            else
                            {
                                bootbox.alert('Error: '+data.errorMsg);
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
        }
        else
        {
            bootbox.alert('Please select at least 1 job!');
        }
    });

    function checkOtp(jobs)
    {
        bootbox.prompt({
            title: "Please provide OTP: ",
            inputType: 'number',
            callback: function (result) {
                if (result != null && result != '') {
                    showCustomLoader();
                    var errUrl = base_url+'maintenance/checkJobOtp';
                    $.ajax({
                        type:'POST',
                        dataType:'json',
                        url: base_url+'maintenance/checkJobOtp',
                        data:{jobs:jobs.join(','),otp:result},
                        success: function(data)
                        {
                            hideCustomLoader();
                            if(data.status === true)
                            {
                                window.location.reload();
                            }
                            else
                            {
                                bootbox.alert(data.errorMsg,function(){
                                    checkOtp(jobs);
                                });
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

    var filesArr = [];
    function uploadMediaChange(ele)
    {
        $('#mediaModal button[type="submit"]').attr('disabled','true');
        $('#mediaModal .progress').removeClass('hide');
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
                $('#mediaModal button[type="submit"]').removeAttr('disabled');
            });
            xhr[i].open('post', '<?php echo base_url();?>maintenance/uploadSolJobFiles', true);

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
        $('input[name="solutionMedia"]').val(filesArr.join());
    }

    $(document).on('click','.upload-sol-media',function(e){
        e.preventDefault();
        var compId = $(this).attr('data-jobId');
        $('#mediaModal input[name="complaintId"]').val(compId);
        $('#mediaModal').modal('show');
    });

    $(document).on('click','.save-job-media', function(){
        var jobId = $('#mediaModal input[name="complaintId"]').val();
        var medias = $('#mediaModal input[name="solutionMedia"]').val();
        if(medias == '')
        {
            bootbox.alert('No Media Uploaded!');
            return false;
        }
        var errUrl = base_url+'maintenance/saveSolMedia';
        showCustomLoader();
        $.ajax({
            type:'POST',
            dataType:'json',
            url:base_url+'maintenance/saveSolMedia',
            data:{jobId:jobId,solutionMedia:medias},
            success: function(data){
                hideCustomLoader();
                if(data.status === true)
                {
                    window.location.reload();
                }
                else
                {
                    bootbox.alert(data.errorMsg);
                }
            },
            error: function(xhr,status, error){
                hideCustomLoader();
                bootbox.alert('Some Error Occurred!');
                var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                saveErrorLog(err);
            }
        });

    });

    $(document).on('click','.show-img-media',function(e){
        var media = $(this).attr('data-media');
        var meHtml = '<img src="'+media+'" alt="Media" class="img-responsive"/>';
        $('#mediaViewModal .media-body').html(meHtml);
        $('#mediaViewModal').modal('show');
    });
    $(document).on('click','.show-vid-media',function(e){
        var media = $(this).attr('data-media');
        var meHtml = '<video width="400" controls>'+
            '<source src="'+media+'">'+
            'Your browser does not support HTML5 video.'+
            '</video>';
        $('#mediaViewModal .media-body').html(meHtml);
        $('#mediaViewModal').modal('show');
    });
    $(document).on('click','.track-me',function(){
        var currentTab = $('.nav.nav-tabs li.active').find('a').attr('href');

        localStorageUtil.setLocal('currTab',currentTab);
        localStorageUtil.setLocal('openPage',openTab.page());
        localStorageUtil.setLocal('progressPage',progressTab.page());
        localStorageUtil.setLocal('closePage',closeTab.page());
        localStorageUtil.setLocal('postponePage',postponeTab.page());
    });
    $(document).on('click','.details-complaint',function(){
        var jobId = $(this).attr('data-complaintId');
        if(jobId != '')
        {
            var errUrl = base_url+'maintenance/getJobDetails/'+jobId;
            showCustomLoader();
            $.ajax({
                type:'GET',
                dataType:'json',
                url:base_url+'maintenance/getJobDetails/'+jobId,
                success: function(data) {
                    hideCustomLoader();
                    if (data.status === true)
                    {
                        $('#detailsModal .complaint-num').html(jobId);
                        var complaintInfo = '<b>Location:</b> '+data.compInfo.locName+'<br><b>Problem At:</b> '+data.compInfo.areaName+
                            '<br><b>Work Type:</b> '+data.compInfo.typeName+'<br><b>Raised By:</b> '+data.compInfo.userName+'<br><b>Logged Date/Time:</b> '+data.compInfo.loggedDT+
                            '<br><b>Problem:</b> '+data.compInfo.problemDescription;
                        if(data.compInfo.updateOnComplaint != null)
                        {
                            complaintInfo += '<br><b>Update on Problem:</b> '+data.compInfo.updateOnComplaint;
                        }
                        if(data.compInfo.approxCost != null)
                        {
                            complaintInfo += '<br><b>Approx Cost:</b> Rs '+data.compInfo.approxCost;
                        }
                        if(data.compInfo.assignee != null)
                        {
                            complaintInfo += '<br><b>Assigned To:</b> '+data.compInfo.assignee;
                        }
                        if(data.compInfo.workAssignedTo != null)
                        {
                            complaintInfo += '<br><b>Assigned To:</b> '+data.compInfo.workAssignedTo;
                        }
                        if(data.compInfo.remarkIfAny != null)
                        {
                            complaintInfo += '<br><b>Final Remark:</b> '+data.compInfo.remarkIfAny;
                        }
                        if(data.compInfo.actualCost != null)
                        {
                            complaintInfo += '<br><b>Actual Cost:</b> Rs '+data.compInfo.actualCost;
                        }

                        $('#detailsModal .details-body').html(complaintInfo);

                        $('#detailsModal').modal('show');
                    }
                    else
                    {
                        bootbox.alert(data.errorMsg);
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
</script>
</html>