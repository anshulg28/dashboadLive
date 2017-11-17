<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>New Spot Awards :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="logComplaint">
        <form method="POST" id="spotForm" action="<?php echo base_url().'spotaward/saveSpotData';?>">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-1"></div>
                    <div class="col-xs-10">
                        <div class="form-group">
                            <label for="awardDate">Select Award Date</label>
                            <input type="text" name="awardDate" id="awardDate"/>
                        </div>
                        <div class="form-group">
                            <label for="problemMedia">Excel File Upload</label>
                            <input type="file" class="form-control" id="problemMedia" onchange="uploadMediaChange(this)" />
                            <input type="hidden" name="excelFile"/>
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
    $('#awardDate').datetimepicker({
        format: 'YYYY-MM-DD'
    });

    $(document).on('submit','#spotForm', function(e){
        e.preventDefault();

        if($('input[name="excelFile"]').val() == '')
        {
            bootbox.alert('Excel File is required!');
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
                    window.location.href=base_url+'spotaward';
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
            xhr[i].open('post', '<?php echo base_url();?>spotaward/uploadExcelFiles', true);

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
        $('input[name="excelFile"]').val(filesArr.join());
    }
</script>

</html>