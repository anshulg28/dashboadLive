<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Career Edit :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="career">
        <div class="container">
            <div class="row">
                <?php
                    if(isset($jobData) && myIsArray($jobData))
                    {
                        ?>
                        <h2><i class="fa fa-suitcase fa-1x"></i> Edit Job</h2>
                        <hr>
                        <br>
                        <form action="<?php echo base_url();?>career/updateJob/<?php echo $jobData['id'];?>" id="mugNumSave-form" method="post" class="form-horizontal" role="form">
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="jobTitle">Job Title :</label>
                                <div class="col-sm-10">
                                    <input type="number" name="jobTitle" value="<?php echo $jobData['jobTitle'];?>"
                                           class="form-control" id="jobTitle" placeholder="Community Manager" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="mugTag">Job Description:</label>
                                <div class="col-sm-10">
                                    <textarea name="jobDescription" class="form-control" id="jobDescription" required><?php echo $jobData['jobDescription'];?></textarea>
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
                                            <option value="">Select</option>
                                            <?php
                                            foreach($locs as $key => $row)
                                            {
                                                ?>
                                                <option value="<?php echo $row['id'];?>" <?php if($row['id'] == $jobData['locId']){echo 'selected';} ?>><?php echo $row['locName'];?></option>
                                                <?php
                                            }
                                            ?>
                                            <option value="other" <?php if(!isset($jobData['locId'])){echo 'selected';} ?>>Other</option>
                                        </select>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="form-group <?php if(isset($jobData['otherLocation']) && isStringSet($jobData['otherLocation'])){echo 'show';}else{echo 'hide';} ?>" id="otherPanel">
                                <label class="control-label col-sm-2" for="otherLocation">Other Location:</label>
                                <div class="col-sm-10">
                                    <input type="text" name="otherLocation" class="form-control" id="otherLocation" value="<?php echo $jobData['otherLocation'];?>" placeholder="Eg. Santacruz" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="jobSkills">Job Skills (optional):</label>
                                <div class="col-sm-10">
                                    <input type="text" name="jobSkills" class="form-control" value="<?php echo $jobData['jobSkills'];?>" id="jobSkills" placeholder="php,photoshop (comma separated)">
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
                        echo 'Invalid Job!';
                    }
                ?>
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
</script>
</html>