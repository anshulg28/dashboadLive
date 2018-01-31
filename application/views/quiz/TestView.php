<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Test :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="homePage">
        <div class="container-fluid">
            <div class="row">
                <input type="hidden" id="qId" value="<?php echo $qId;?>"/>
                <h2 class="text-center">Test for <?php echo ucfirst($this->userName); ?></h2>
                <br>
                <div class="col-sm-1 col-xs-0"></div>
                <div class="col-sm-10 col-xs-12 main-quiz-panel">

                </div>
                <div class="col-sm-1 col-xs-0"></div>
            </div>
        </div>
    </main>
    <?php echo $footerView;?>
</body>
<?php echo $globalJs; ?>
<script>
    var questions = {};
    var i = 0;
    <?php
        foreach($quizQts as $key => $row)
        {
            ?>
            questions[i] = {
                'questionText': '<?php echo addslashes($row['questionText']);?>',
                'categoryName': '<?php echo addslashes($row['categoryName']);?>',
                'optionText': '<?php echo addslashes($row['optionText']);?>',
                'isCorrectOption': '<?php echo addslashes($row['isCorrectOption']);?>'
            };
            i++;
            <?php
        }
    ?>

    $(window).load(function(){
        if(localStorageUtil.getLocal('isPlayerLost') != null && localStorageUtil.getLocal('isPlayerLost') == '1')
        {
            var attempNum = 0;
            if(localStorageUtil.getLocal('attempNum') != null)
            {
                attempNum = localStorageUtil.getLocal('attempNum');
                localStorageUtil.delLocal('attempNum');
            }
            localStorageUtil.delLocal('isPlayerLost');
            if(attempNum != 0)
            {
                bootbox.alert('Seems like Test got restarted! Attempt Number: '+attempNum, function(){
                    startQuiz();
                });
            }
            else
            {
                bootbox.alert('Seems like Test got restarted!', function(){
                    startQuiz();
                });
            }
        }
        else
        {
            //start Quiz normally
            startQuiz();
        }
    });

    $(window).on('beforeunload', function(e){
        //return "Are you sure?";
        if(!isQuizEnd)
        {
            localStorageUtil.setLocal('isPlayerLost','1');
        }
    });

    var maxTime = 60;
    var questionNum = 0;

    function startQuiz()
    {
        var Quest = questions[questionNum]['questionText'];
        var opts = questions[questionNum]['optionText'].split(';');
        var qCat = questions[questionNum]['categoryName'];
        var correctOpts = questions[questionNum]['isCorrectOption'].split(';');

        var quizHtml = '<div class="row">';
        quizHtml += '<span class="pull-left"><b>Time Remaining: </b><span class="time-passed">0</span> seconds</span>';
        quizHtml += '<span class="pull-right"><b>Question #: </b>'+(questionNum+1)+' of '+Object.keys(questions).length+'</span>';
        quizHtml += '<br><span class="pull-left"><b>Category: </b>'+qCat+'</span>';
        quizHtml += '<br><br><p><b>Question: </b> '+Quest+'</p><br>';

        var optsCount = 0;
        for(var j=0;j<correctOpts.length;j++)
        {
            if(correctOpts[j] == '1')
            {
                optsCount++;
            }
        }
        if(optsCount>1)
        {
            for(var j=0;j<opts.length;j++)
            {
                quizHtml += '<div class="checkbox"><label><input type="checkbox" name="optsRadio" value="'+j+'">'+opts[j]+'</label></div>';
            }
        }
        else
        {
            for(var j=0;j<opts.length;j++)
            {
                quizHtml += '<div class="radio"><label><input type="radio" value="'+j+'" name="optsRadio">'+opts[j]+'</label></div>';
            }
        }

        quizHtml += '<br><br><button type="button" class="btn btn-warning submit-quest">Submit</button>';

        $('.main-quiz-panel').html(quizHtml);
        maxTime = 60;
        startTime();
    }

    var timeOut;
    function startTime()
    {
        timeOut = setInterval(function(){
            if(maxTime <= 0)
            {
                clearInterval(timeOut);
                if(questionNum>=Object.keys(questions).length-1)
                {
                    //End Quiz
                    checkAnswer();
                    endQuiz();
                }
                else
                {
                    checkAnswer();
                    questionNum++;
                    startQuiz();
                }
            }
            maxTime-=1;
            if(maxTime <= 10)
            {
                $('.time-passed').css('color','red').html(maxTime);
            }
            else
            {
                $('.time-passed').css('color','#000').html(maxTime);
            }
        },1000);
    }

    var totalScore = 0;
    $(document).on('click','.submit-quest', function(){
        checkAnswer();
        if(questionNum>=Object.keys(questions).length-1)
        {
            //End Quiz
            clearInterval(timeOut);
            endQuiz();
        }
        else
        {
            questionNum++;
            clearInterval(timeOut);
            startQuiz();
        }
    });
    function checkAnswer()
    {
        var correctOpts = questions[questionNum]['isCorrectOption'].split(';');

        var optsCount = 0;
        for(var j=0;j<correctOpts.length;j++)
        {
            if(correctOpts[j] == '1')
            {
                optsCount++;
            }
        }


        var userChoice = 0;
        $('.main-quiz-panel input[name="optsRadio"]').each(function(i,val){
            if($(val).is(':checked'))
            {
                if(correctOpts[Number($(val).val())] == '1')
                {
                    userChoice++;
                }
            }
        });
        if(optsCount == userChoice)
        {
            totalScore++;
        }
    }
    var isQuizEnd = false;
    function endQuiz()
    {
        var qId = $('#qId').val();

        var errUrl = base_url+'quiz/endQuiz';
        showCustomLoader();
        $.ajax({
            type:'POST',
            dataType:'json',
            url: base_url+'quiz/endQuiz',
            data:{qid: qId, marks: totalScore},
            success: function(data){
                hideCustomLoader();
                isQuizEnd = true;
                var backBtn = '<a href="'+base_url+'quiz'+'" class="btn btn-success">Go Back</a>'
                if(data.status === true)
                {
                    $('.main-quiz-panel').html('Thank you for test<br>Your score is: '+totalScore+'<br><br>'+backBtn);
                }
                else
                {
                    $('.main-quiz-panel').html('Error Occured in saving score: '+data.errorMsg+'<br><br>'+backBtn);
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
</script>
</html>