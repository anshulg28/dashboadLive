<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Career Add :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="career">
        <div class="container">
            <div class="row">
                <h2><i class="fa fa-suitcase fa-1x"></i> Add New Job</h2>
                <hr>
                <a href="<?php echo base_url().'career';?>" class="btn btn-default">GO Back</a>
                <br>
                <form action="<?php echo base_url();?>career/saveJob" id="mugNumSave-form" method="post" class="form-horizontal" role="form">
                    <div class="mugNumber-status text-center"></div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="jobTitle">Job Title :</label>
                        <div class="col-sm-10">
                            <input type="text" name="jobTitle" class="form-control" id="jobTitle" placeholder="Community Manager" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="jobDepartment">Department: </label>
                        <div class="col-sm-10">
                            <input type="text" name="jobDepartment" class="form-control" id="jobDepartment" placeholder="Eg. Marketing">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="mugTag">Job Description:</label>
                        <div class="col-sm-10">
                            <textarea name="jobDescription" class="form-control" id="jobDescription" required></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="locId">Location:</label>
                        <div class="col-sm-10">
                            <?php
                                if(isset($locs))
                                {
                                    ?>
                                    <select class="form-control" name="locId" required>
                                        <option value="" selected>Select</option>
                                        <?php
                                        foreach($locs as $key => $row)
                                        {
                                            ?>
                                            <option value="<?php echo $row['id'];?>"><?php echo $row['locName'];?></option>
                                            <?php
                                        }
                                        ?>
                                        <option value="other">Other</option>
                                    </select>
                                        <?php
                                }
                            ?>
                        </div>
                    </div>
                    <div class="form-group hide" id="otherPanel">
                        <label class="control-label col-sm-2" for="otherLocation">Other Location:</label>
                        <div class="col-sm-10">
                            <input type="text" name="otherLocation" class="form-control" id="otherLocation" placeholder="Eg. Santacruz">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="jobSkills">Job Skills (optional):</label>
                        <div class="col-sm-10">
                            <input type="text" name="jobSkills" class="form-control" id="jobSkills" placeholder="php,photoshop (comma separated)">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="reset" class="btn btn-success">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
<?php echo $footerView;?>
</body>
<?php echo $globalJs; ?>

<script>
    CKEDITOR.replace( 'jobDescription' );
    CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
    CKEDITOR.config.shiftEnterMode = CKEDITOR.ENTER_P;
    $(document).on('change','select[name="locId"]',function(){
        if($(this).val() == 'other')
        {
            $('#otherPanel').removeClass('hide');
            $('#otherPanel #otherLocation').attr('required','required');
        }
        else
        {
            $('#otherPanel').addClass('hide').val('');
            $('#otherPanel #otherLocation').removeAttr('required');
        }
    });
</script>
</html>