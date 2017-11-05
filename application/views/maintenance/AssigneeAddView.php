<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Assignee Add :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="workType">
        <div class="container">
            <div class="row">
                <h2> Add New Assignee</h2>
                <hr>
                <br>
                <form action="<?php echo base_url();?>maintenance/saveAssignee" id="assignee-form" method="post" class="form-horizontal" role="form">
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="userName">Assignee Name :</label>
                        <div class="col-sm-10">
                            <input type="text" name="userName" class="form-control" id="userName" placeholder="Abc" required>
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