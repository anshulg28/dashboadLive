<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Twitter Bot :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="twitterBot">
        <div class="container">
            <div class="row">
                <div class="col-sm-3 col-xs-0"></div>
                <div class="col-sm-6 col-xs-12">
                    <form id="tweet-form" action="<?php echo base_url();?>dashboard/saveTweet" method="post" class="form-horizontal" role="form">
                        <div class="form-group">
                            <label for="tweetText">Tweet (140 Chars, including links):</label>
                            <textarea maxlength="140" class="form-control" rows="10" cols="10" name="tweetText" id="tweetText"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="tweetImage">Image (If Any):</label>
                            <input id="tweetImage" type="file" class="form-control" onchange="uploadChange(this)" />
                            <input type="hidden" name="attachment" />
                        </div>
                        <div class="form-group">
                            <label for="masterTweetCount">Max Tweet Recurrence:</label>
                            <input type="number" class="form-control" name="masterTweetCount" id="masterTweetCount"/>
                        </div>
                        <button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">Submit</button>
                        <br><br>
                        <div class="progress hide">
                            <div class="progress-bar progress-bar-striped active" role="progressbar"
                                 aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                    </form>
                    <?php
                    if(isset($existingTweets) && myIsArray($existingTweets))
                    {
                        ?>
                        <div class="mdl-grid table-responsive">
                            <table class="table table-hover table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Tweet Text</th>
                                    <th>Tweet Image</th>
                                    <th>Tweet Repetition Count</th>
                                    <th>Logged Date/Time</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach($existingTweets as $key => $row)
                                {
                                    ?>
                                    <tr>
                                        <th scope="row"><?php echo $row['id'];?></th>
                                            <td><?php echo $row['tweetText'];?></td>
                                        <?php
                                        if(isset($row['tweetImage']) && $row['tweetImage'] != '')
                                        {
                                            $imgs = array(MOBILE_URL.TWITTER_BOT_PATH.'thumb/'.$row['tweetImage']);
                                            ?>
                                            <td>
                                                <a class="view-photos" data-toggle="tooltip" title="View Photo" href="#" data-imgs="<?php echo implode(',',$imgs);?>">
                                                    <i class="fa fa-15x fa-file-image-o my-success-text"></i></a>
                                            </td>
                                            <?php

                                        }
                                        else
                                        {
                                            ?>
                                            <td></td>
                                            <?php
                                        }
                                        ?>
                                        <td><?php echo $row['masterTweetCount'];?></td>
                                        <td><?php echo $row['insertedDateTime'];?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <?php

                    }
                    ?>
                </div>
                <div class="col-sm-3 col-xs-0"></div>
            </div>
        </div>
    </main>
    <?php echo $footerView;?>
</body>
<?php echo $globalJs; ?>

<script>
    $(document).on('click','.img-remove-icon', function(){
        var picId = $(this).attr('data-picId');
        var parent = $(this).parent();
        bootbox.confirm("Remove Image?", function(result) {
            if(result === true)
            {
                var errUrl = base_url+'dashboard/deleteFnbAtt';
                $.ajax({
                    type:"POST",
                    dataType:"json",
                    url:"<?php echo base_url();?>dashboard/deleteFnbAtt",
                    data:{picId:picId},
                    success: function(data)
                    {
                        if(data.status === true)
                        {
                            $(parent).fadeOut();
                        }
                    },
                    error: function(xhr, status, error){
                        bootbox.alert('Some Error Occurred!');
                        var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                        saveErrorLog(err);
                    }
                });
            }
        });
    });
    function fillImgs()
    {
        $('input[name="attachment"]').val(filesArr.join());
    }
    var filesArr = [];
    function uploadChange(ele)
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
            xhr[i].open('post', '<?php echo base_url();?>dashboard/uploadTweetFiles', true);

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
                        filesArr.push(e.srcElement.responseText);
                        fillImgs();
                    }
                }
            }
        }
    }
    $(document).on('click','.view-photos', function(){
        var pics = $(this).attr('data-imgs').split(',');
        if(typeof pics != 'undefined')
        {
            var newPics = [];
            for(var i=0;i<pics.length;i++)
            {
                var temp = {href:pics[i],title:''};
                newPics[i] = temp;
            }
            $.swipebox( newPics );
        }
    });
    $(document).on('submit','#tweet-form', function(e){
        e.preventDefault();

        fillImgs();

        if($('#tweetText').val() == '' && filesArr.length == 0)
        {
            bootbox.alert('Tweet Text Or Image is Required!');
            return false;
        }
        if($('#masterTweetCount').val() == '')
        {
            bootbox.alert('Tweet Repetition Count Required!');
            return false;
        }
        showCustomLoader();
        var errUrl = $(this).attr('action');
        $.ajax({
            type:'POST',
            dataType:'json',
            url:$(this).attr('action'),
            data:$(this).serialize(),
            success: function(data){
                hideCustomLoader();
                if(data.status === true)
                {
                    window.location.reload();
                }
            },
            error: function(xhr, status, error){
                hideCustomLoader();
                bootbox.alert('Some Error Occurred!');
                var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                saveErrorLog(err);
            }
        });

    });
</script>

</html>