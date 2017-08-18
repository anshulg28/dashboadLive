<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Template Add/Edit :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="templates">
        <div class="container">
            <div class="row">

                <?php
                    if(isset($tempEdit) && myIsArray($tempEdit))
                    {
                        ?>
                        <h2><i class="fa fa-book fa-1x"></i> Edit Template</h2>
                        <hr>
                        <br>
                        <div class="row">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-8">
                                <a href="<?php echo base_url().'mailers/templates';?>" class="btn btn-warning"><i class="fa fa-arrow-circle-o-left"></i> Go Back</a>
                            </div>
                            <div class="col-sm-4"></div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-2">Available Tags:</div>
                            <ul class="col-sm-10 list-inline mugtags-list">
                                <li class="my-pointer-item"><span class="label label-success">[mugno]</span></li>
                                <li class="my-pointer-item"><span class="label label-success">[firstname]</span></li>
                                <li class="my-pointer-item"><span class="label label-success">[lastname]</span></li>
                                <li class="my-pointer-item"><span class="label label-success">[birthdate]</span></li>
                                <li class="my-pointer-item"><span class="label label-success">[mobno]</span></li>
                                <li class="my-pointer-item"><span class="label label-success">[expirydate]</span></li>
                                <li class="my-pointer-item"><span class="label label-success">[sendername]</span></li>
                            </ul>
                        </div>
                        <br>
                        <form action="<?php echo base_url();?>mailers/tempUpdate" method="post" class="form-horizontal" role="form">
                            <input type="hidden" name="id" value="<?php echo $tempEdit['id'];?>"/>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="mailSubject">Mail Subject :</label>
                                <div class="col-sm-10">
                                    <input onfocus="whichHasFocus=1" type="text" name="mailSubject" class="form-control" id="mailSubject" required
                                    value="<?php echo $tempEdit['mailSubject'];?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="mailBody">Mail Body:</label>
                                <div class="col-sm-10">
                                    <textarea name="mailBody" rows="15" class="form-control" id="mailBody" required><?php echo $tempEdit['mailBody'];?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="mailType">Mail Type:</label>
                                <div class="col-sm-10">
                                    <select name="mailType" id="mailType" class="form-control">
                                        <option value="0"
                                            <?php if($tempEdit['mailType'] == CUSTOM_MAIL){echo 'selected';}?>>Custom</option>
                                        <option value="1"
                                            <?php if($tempEdit['mailType'] == EXPIRED_MAIL){echo 'selected';}?>>Expired</option>
                                        <option value="2"
                                            <?php if($tempEdit['mailType'] == EXPIRING_MAIL){echo 'selected';}?>>Expiring</option>
                                        <option value="3"
                                            <?php if($tempEdit['mailType'] == BIRTHDAY_MAIL){echo 'selected';}?>>Birthday</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <button type="reset" class="btn btn-success">Reset</button>
                                </div>
                            </div>
                        </form>
                        <?php
                    }
                    else
                    {
                        ?>
                        <h2><i class="fa fa-book fa-1x"></i> Add New Template</h2>
                        <hr>
                        <br>
                        <div class="row">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-8">
                                <a href="<?php echo base_url().'mailers/templates';?>" class="btn btn-warning"><i class="fa fa-arrow-circle-o-left"></i> Go Back</a>
                            </div>
                            <div class="col-sm-4"></div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-2">Available Tags:</div>
                            <ul class="col-sm-10 list-inline mugtags-list">
                                <li class="my-pointer-item"><span class="label label-success">[mugno]</span></li>
                                <li class="my-pointer-item"><span class="label label-success">[firstname]</span></li>
                                <li class="my-pointer-item"><span class="label label-success">[lastname]</span></li>
                                <li class="my-pointer-item"><span class="label label-success">[birthdate]</span></li>
                                <li class="my-pointer-item"><span class="label label-success">[mobno]</span></li>
                                <li class="my-pointer-item"><span class="label label-success">[expirydate]</span></li>
                                <li class="my-pointer-item"><span class="label label-success">[sendername]</span></li>
                            </ul>
                        </div>
                        <br>
                        <form action="<?php echo base_url();?>mailers/tempSave" method="post" class="form-horizontal" role="form">
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="mailSubject">Mail Subject :</label>
                                <div class="col-sm-10">
                                    <input onfocus="whichHasFocus=1" type="text" name="mailSubject" class="form-control" id="mailSubject" required/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="mailBody">Mail Body:</label>
                                <div class="col-sm-10">
                                    <textarea name="mailBody" rows="15" class="form-control" id="mailBody" required></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="mailType">Mail Type:</label>
                                <div class="col-sm-10">
                                    <select name="mailType" id="mailType" class="form-control">
                                        <option value="0">Custom</option>
                                        <option value="1">Expired</option>
                                        <option value="2">Expiring</option>
                                        <option value="3">Birthday</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <button type="reset" class="btn btn-success">Reset</button>
                                </div>
                            </div>
                        </form>
                        <?php
                    }
                ?>
            </div>
        </div>
    </main>
</body>
<?php echo $globalJs; ?>

<script>
    var whichHasFocus = 1;
    CKEDITOR.replace('mailBody');
    CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
    CKEDITOR.config.shiftEnterMode = CKEDITOR.ENTER_P;
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
</script>
</html>