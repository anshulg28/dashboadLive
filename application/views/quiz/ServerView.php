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
                <div class="col-sm-8 col-xs-12">
                    <?php
                        if(isset($quizRecord) && myIsArray($quizRecord))
                        {
                            ?>
                            <table id="quizServerTab" class="table table-bordered table-responsive">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                            <?php
                            foreach($quizRecord as $key => $row)
                            {
                                ?>
                                <tr>
                                    <td><?php echo $row['empName'];?></td>
                                    <td>
                                        <?php
                                            if($row['ifTestStarted'] == '0')
                                            {
                                                ?>
                                                <button type="button" data-quizId="<?php echo $row['quizId'];?>" class="btn btn-primary start-quiz">Start Quiz</button>
                                                <?php
                                            }
                                            else
                                            {
                                                if($row['ifTestFinished'] == '1')
                                                {
                                                    echo 'Test Finished.';
                                                }
                                                else if($row['ifTestFinished'] == '2')
                                                {
                                                    echo 'User is Blocked!';
                                                }
                                                else
                                                {
                                                    echo 'Test is Started....<br>';
                                                    echo 'Attempt: '.$row['attemptNum'].' of 3<br>';
                                                    ?>
                                                    <a href="#" data-quizId="<?php echo $row['quizId'];?>" class="my-noUnderline restart-test">Restart Test?</a>
                                                    <?php
                                                }
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