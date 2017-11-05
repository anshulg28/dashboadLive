<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Work Area Edit :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="workArea">
        <div class="container">
            <div class="row">
                <h2> Edit Work Area</h2>
                <hr>
                <br>
                <form action="<?php echo base_url();?>maintenance/updateWorkArea" id="workArea-form" method="post" class="form-horizontal" role="form">
                    <input type="hidden" name="areaId" value="<?php echo $areaId;?>"/>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="areaName">Area Name :</label>
                        <div class="col-sm-10">
                            <input type="text" name="areaName" class="form-control" id="areaName" placeholder="Outside Taproom"
                                   value="<?php echo $areaData['areaName'];?>" required>
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
</html>