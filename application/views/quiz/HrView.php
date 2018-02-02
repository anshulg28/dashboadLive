<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Quiz :: Doolally</title>
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
                <div class="col-sm-8 col-xs-12 text-center">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#quizPage">Quiz</a></li>
                        <li><a data-toggle="tab" href="#qHistory">History</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="quizPage" class="tab-pane fade in active">
                            <?php


                            {
                                if(isset($lastDrawn) && myIsArray($lastDrawn))
                                {
                                    if($lastDrawn['drawnStatus'] == '0')
                                    {
                                        ?>
                                        <br>
                                        <button type="button" class="btn btn-warning" disabled>Generating quiz list..</button>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                        <br>
                                        <button type="button" class="btn btn-warning" disabled>Already drawn for this month!</button>
                                        <br>
                                        <?php
                                    }
                                }
                                else
                                {
                                    ?>
                                    <br>
                                    <div>
                                        <span>Upload Excel: </span>
                                        <input type="file" id="emp_attachment" class="form-control" onchange="uploadChange(this)"/><br>
                                        <button type="button" class="btn btn-success" id="quiz-create-excel" disabled>Draw Names using Excel Sheet</button>
                                    </div>
                                    <br>
                                    <span class="my-danger-text">Please Convert the excel file in (97-2003 .xls) format before uploading.</span>
                                    <!--<button type="button" class="btn btn-warning" id="quiz-create" disabled>Draw Names</button>-->
                                    <br>
                                    <div class="progress hide">
                                        <div class="progress-bar progress-bar-striped active" role="progressbar"
                                             aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            ?>

                            <?php
                            if(isset($quizRecord) && myIsArray($quizRecord))
                            {
                                ?>
                                <br>
                                <table id="postponeTab" class="table table-hover table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Department</th>
                                        <th>Location</th>
                                        <th>Attempts</th>
                                        <th>Test Level</th>
                                        <th>Test Status</th>
                                        <th>Drawn Date/Time</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach($quizRecord as $key => $row)
                                    {
                                        ?>
                                        <tr>
                                            <td><?php echo $row['quizId'];?></td>
                                            <td><?php echo $row['empName'];?></td>
                                            <td><?php echo $row['empDepart'];?></td>
                                            <td><?php echo $row['locName'];?></td>
                                            <td><?php echo $row['attemptNum'];?></td>
                                            <td><?php echo $row['testLevel'];?></td>
                                            <td>
                                                <?php
                                                if($row['ifTestFinished'] == '1')
                                                {
                                                    echo 'Test Finished, Score: '.$row['marksScored'];
                                                }
                                                elseif($row['ifTestFinished'] == '2')
                                                {
                                                    echo 'User blocked for multiple attempts.';
                                                }
                                                elseif($row['ifTestStarted'] == '0')
                                                {
                                                    echo 'Not Started Yet';
                                                }
                                                elseif($row['ifTestStarted'] == '1')
                                                {
                                                    echo 'Test Started';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $d = date_create($row['insertedDT']);
                                                echo date_format($d,DATE_TIME_FORMAT_UI);
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
                            /*else
                            {
                                echo '<br>Names Not Drawn Yet!';
                            }*/
                            ?>
                        </div>
                        <div id="qHistory" class="tab-pane fade">
                            <?php
                            if(isset($staffRecords) && myIsArray($staffRecords))
                            {
                                ?>
                                <br>
                                <table id="staffTab" class="table table-hover table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Department</th>
                                        <th>Location</th>
                                        <th>Attempts</th>
                                        <th>Total Marks</th>
                                        <th>Repeated Date(s)</th>
                                        <th>Drawn Date/Time</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach($staffRecords as $key => $row)
                                    {
                                        ?>
                                        <tr>
                                            <td><?php echo $row['empId'];?></td>
                                            <td><?php echo $row['empName'];?></td>
                                            <td><?php echo $row['empDepart'];?></td>
                                            <td><?php echo $row['locName'];?></td>
                                            <td><?php echo $row['noOfAttempts'];?></td>
                                            <td><?php echo $row['totalMarks'];?></td>
                                            <td>
                                                <?php
                                                echo $row['repeatedDates'];
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $d = date_create($row['insertedDT']);
                                                echo date_format($d,DATE_TIME_FORMAT_UI);
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
                            else
                            {
                                echo '<br>No Record Found!!';
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2 col-xs-0"></div>
            </div>
        </div>
    </main>
    <?php echo $footerView;?>
</body>
<?php echo $globalJs; ?>
<script>
    $(document).on('click','#quiz-create', function(){
        $(this).attr('disabled','disabled');
        showCustomLoader();
        $.ajax({
            type:'GET',
            dataType:'json',
            url:base_url+'quiz/createQuiz',
            success: function(data)
            {

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

    $(document).on('click','#quiz-create-excel', function(){
        triggerQuizExcel();
    });
    var excelFilename = '';
    function uploadChange(ele)
    {
        $('.progress').removeClass('hide');

        $('.progress-bar').css('width','0%').attr('aria-valuenow', 0).html('0%');
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
                verifyQuiz();
            });
            xhr[i].open('post', '<?php echo base_url();?>quiz/uploadFile', true);

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
                        excelFilename = e.srcElement.responseText;
                    }
                }
            }
        }
    }

    function verifyQuiz()
    {
        showCustomLoader();
        $.ajax({
            type:'POST',
            dataType:'json',
            url:base_url+'quiz/verfiyExcel',
            data:{filename: excelFilename},
            success: function(data)
            {
                hideCustomLoader();
                if(data.status === true)
                {
                    $('#quiz-create-excel').removeAttr('disabled');
                    bootbox.alert('Excel Verified! Please proceed with draw button');
                }
                else
                {
                    var errors = data.errorMsg.split(',');
                    var errTxt = '';
                    for(var i=0;i<errors.length;i++)
                    {
                        errTxt += errors[i]+'<br>';
                    }
                    bootbox.alert(errTxt);
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

    function triggerQuizExcel()
    {
        showCustomLoader();
        $.ajax({
            type:'POST',
            dataType:'json',
            url:base_url+'quiz/createQuizExcel',
            data:{filename: excelFilename},
            success: function(data)
            {
                hideCustomLoader();
                if(data.status === true)
                {
                    window.location.reload();
                   /* bootbox.alert('Quiz Creation requires time, system will send mail once done!',function(){
                        window.location.reload();
                    });*/
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
    var hrTab = $('#postponeTab').DataTable();
    var sTab = $('#staffTab').DataTable();
</script>
</html>