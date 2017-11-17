<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Spot Awards :: Doolally</title>
	<?php echo $globalStyle; ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>asset/css/ui.jqgrid.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>asset/css/ui.jqgrid-bootstrap.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>asset/css/ui.jqgrid-bootstrap-ui.css">
</head>
<body>
    <?php echo $headerView; ?>
    <main class="logComplaint">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-1 col-xs-0"></div>
                <div class="col-sm-10 col-xs-12">
                    <div class="row">
                        <?php
                        if(myInArray('spot_award_add',$userModules))
                        {
                            ?>
                            <a class="btn btn-primary" href="<?php echo base_url().'spotaward/addNewAwards';?>">
                                <i class="fa fa-plus"></i>
                                Add New Award List</a>
                            <?php
                        }
                        ?>
                    </div>
                    <br>

                </div>
                <div class="col-sm-1 col-xs-0"></div>
            </div>
        </div>
    </main>
    <?php echo $footerView;?>
</body>
<?php echo $globalJs; ?>
<script type="text/javascript" src="<?php echo base_url(); ?>asset/js/jquery.jqGrid.min.js"></script>

<script>

</script>
</html>