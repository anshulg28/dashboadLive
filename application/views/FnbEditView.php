<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Fnb Edit :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="fnbEdit">
        <div class="container">
            <div class="row">
                <a href="<?php echo base_url().'dashboard';?>" class="btn btn-warning"><i class="fa fa-arrow-circle-o-left"></i> Go Back</a>
                <?php
                    if(isset($fnbInfo) && myIsArray($fnbInfo))
                    {
                        foreach($fnbInfo as $key => $row)
                        {
                            if(isset($row['fnbData']['fnbId']))
                            {
                                ?>
                                <h2><i class="fa fa-calendar fa-1x"></i> Edit FnB: <?php echo $row['fnbData']['itemName'];?></h2>
                                <hr>
                                <br>
                                <form action="<?php echo base_url();?>dashboard/updatefnb" method="post" class="form-horizontal" role="form">
                                    <input type="hidden" name="fnbId" value="<?php echo $row['fnbData']['fnbId'];?>"/>
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label my-fullWidth">
                                        <input class="mdl-textfield__input" type="text" name="itemName"
                                               id="itemName" value="<?php echo $row['fnbData']['itemName'];?>">
                                        <label class="mdl-textfield__label" for="itemName">Item Name</label>
                                    </div>
                                    <br>
                                    <div class="text-left">
                                        <label>Event Cost :</label>
                                        <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="foodType">
                                            <input type="radio" id="foodType" class="mdl-radio__button" name="itemType"
                                                   value="1" <?php if($row['fnbData']['itemType'] == "1"){echo 'checked';}?>>
                                            <span class="mdl-radio__label">Food</span>
                                        </label>
                                        <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="beverageType">
                                            <input type="radio" id="beverageType" class="mdl-radio__button" name="itemType"
                                                   value="2" <?php if($row['fnbData']['itemType'] == "2"){echo 'checked';}?>>
                                            <span class="mdl-radio__label">Beverage</span>
                                        </label>
                                    </div>
                                    <br>
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label my-fullWidth text-left">
                                        <label for="itemHeadline">Item Headline: </label>
                                        <textarea class="mdl-textfield__input my-singleBorder" type="text" name="itemHeadline" rows="5"
                                                  id="itemHeadline"><?php echo strip_tags($row['fnbData']['itemHeadline']);?></textarea>
                                    </div>
                                    <br>
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label my-fullWidth text-left">
                                        <label for="itemDescription">Item Description: </label>
                                        <textarea class="mdl-textfield__input my-singleBorder" type="text" name="itemDescription" rows="5"
                                                  id="itemDescription"><?php echo strip_tags($row['fnbData']['itemDescription']);?></textarea>
                                    </div>
                                    <ul class="list-inline text-left">
                                        <li>
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                <input class="mdl-textfield__input" type="text" name="priceFull"
                                                       id="priceFull" placeholder="" value="<?php echo $row['fnbData']['priceFull'];?>">
                                                <label class="mdl-textfield__label" for="priceFull">Price Full</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                <input class="mdl-textfield__input" type="text" name="priceHalf"
                                                       id="priceHalf" placeholder="" value="<?php echo $row['fnbData']['priceHalf'];?>">
                                                <label class="mdl-textfield__label" for="priceHalf">Price Half</label>
                                            </div>
                                        </li>
                                    </ul>
                                    <br>
                                    <?php
                                        if(isset($row['fnbAtt']) && myIsMultiArray($row['fnbAtt']))
                                        {
                                            ?>
                                            <div class="text-left">
                                                <?php
                                                    foreach($row['fnbAtt'] as $imgkey => $imgrow)
                                                    {
                                                        switch($imgrow['attachmentType'])
                                                        {
                                                            case '1':
                                                                ?>
                                                                <div class="pics-preview-panel col-sm-2 col-xs-5">
                                                                    <img src="<?php echo MOBILE_URL.FOOD_PATH_THUMB.$imgrow['filename'];?>"
                                                                         class="img-thumbnail"/>
                                                                    <i class="fa fa-times img-remove-icon" data-picId="<?php echo $imgrow['id'];?>"></i>
                                                                </div>
                                                                <?php
                                                                break;
                                                            case '2':
                                                                ?>
                                                                <div class="pics-preview-panel col-sm-2 col-xs-5">
                                                                    <img src="<?php echo MOBILE_URL.BEVERAGE_PATH_THUMB.$imgrow['filename'];?>"
                                                                         class="img-thumbnail"/>
                                                                    <i class="fa fa-times img-remove-icon" data-picId="<?php echo $imgrow['id'];?>"></i>
                                                                </div>
                                                                <?php
                                                                break;
                                                            default:
                                                                ?>
                                                                <div class="pics-preview-panel col-sm-2 col-xs-5">
                                                                    <img src="<?php echo MOBILE_URL.BEVERAGE_PATH_THUMB.$imgrow['filename'];?>"
                                                                         class="img-thumbnail"/>
                                                                    <i class="fa fa-times img-remove-icon" data-picId="<?php echo $imgrow['id'];?>"></i>
                                                                </div>
                                                                <?php
                                                                break;
                                                        }
                                                    }
                                                ?>
                                            </div>
                                            <?php
                                        }
                                    ?>
                                    <div class="myUploadPanel text-left clear">
                                        <label>Attachment Type :</label>
                                        <!--<label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="attFood">
                                            <input type="radio" id="attFood" class="mdl-radio__button" name="attType[0]" value="1" checked>
                                            <span class="mdl-radio__label">Food</span>
                                        </label>
                                        <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="attBeer">
                                            <input type="radio" id="attBeer" class="mdl-radio__button" name="attType[0]" value="2">
                                            <span class="mdl-radio__label">Beer Digital</span>
                                        </label>
                                        <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="attBeerW">
                                            <input type="radio" id="attBeerW" class="mdl-radio__button" name="attType[0]" value="3">
                                            <span class="mdl-radio__label">Beer Woodcut</span>
                                        </label>-->
                                        <input type="file" multiple class="form-control" onchange="uploadChange(this)" />
                                        <br>
                                        <button onclick="addUploadPanel()" type="button" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">Add More?</button>
                                        <input type="hidden" name="attachment" />
                                    </div>
                                    <br>
                                    <button onclick="fillImgs()" type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">Submit</button>
                                    <br><br>
                                    <div class="progress hide">
                                        <div class="progress-bar progress-bar-striped active" role="progressbar"
                                             aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                </form>
                                <?php
                            }
                        }
                    }
                    else
                    {
                        echo '<h2 class="my-danger-text text-center>Item Number Not Found!</h2>"';
                    }
                ?>
            </div>
        </div>
    </main>
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
            xhr[i].open('post', '<?php echo base_url();?>dashboard/uploadFiles', true);

            var data = new FormData;
            data.append('attachment', ele.files[i]);
            data.append('itemType',$('input[name="itemType"]:checked').val());
            xhr[i].send(data);
            xhr[i].onreadystatechange = function(e) {
                if (e.srcElement.readyState == 4 && e.srcElement.status == 200) {
                    if(e.srcElement.responseText == 'Some Error Occurred!')
                    {
                        bootbox.alert('File size Limit 30MB');
                        return false;
                    }
                    filesArr.push(e.srcElement.responseText);
                }
            }
        }
    }
    var upPanel = 1;
    function addUploadPanel()
    {
        /*'<br><br><label>Attachment Type :</label>'+
        '<label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="attFood'+upPanel+'">'+
        '<input type="radio" id="attFood'+upPanel+'" class="mdl-radio__button" name="attType['+upPanel+']" value="1" checked>'+
        '<span class="mdl-radio__label">Food</span>'+
        '</label>'+
        '<label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="attBeer'+upPanel+'">'+
        '<input type="radio" id="attBeer'+upPanel+'" class="mdl-radio__button" name="attType['+upPanel+']" value="2">'+
        '<span class="mdl-radio__label">Beer Digital</span>'+
        '</label>'+
        '<label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="attBeerW'+upPanel+'">'+
        '<input type="radio" id="attBeerW'+upPanel+'" class="mdl-radio__button" name="attType['+upPanel+']" value="3">'+
        '<span class="mdl-radio__label">Beer Woodcut</span>'+
        '</label>'+*/
        var html = '';
        html += '<br><br><input type="file" multiple class="form-control" onchange="uploadChange(this)" /><br>'+
                '<button onclick="addUploadPanel()" type="button" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">Another?</button>';
        upPanel++;
        $('.myUploadPanel').append(html);
    }

</script>

</html>