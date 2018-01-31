<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Questions :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="homePage">
        <div class="container-fluid">
            <div class="row">
                <h2 class="text-center">Welcome <?php echo ucfirst($this->userName); ?></h2>
                <br>
                <div class="col-sm-12 text-center">
                    <span>Upload Excel: </span>
                    <input type="file" id="emp_attachment" class="form-control" onchange="uploadChange(this)"/><br>
                    <button type="button" class="btn btn-success" id="quiz-create-excel">Parse Questions</button>
                    <br>
                    <div class="progress hide">
                        <div class="progress-bar progress-bar-striped active" role="progressbar"
                             aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php echo $footerView;?>
</body>
<?php echo $globalJs; ?>
<script>
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
                //verifyQuiz();
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

    function triggerQuizExcel()
    {
        showCustomLoader();
        $.ajax({
            type:'POST',
            dataType:'json',
            url:base_url+'question/parseQuestions',
            data:{filename: excelFilename},
            success: function(data)
            {
                hideCustomLoader();
                if(data.status === true)
                {
                    bootbox.alert('done');
                    //window.location.reload();
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
</script>
</html>