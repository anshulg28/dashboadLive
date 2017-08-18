<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Maintenance :: Doolally</title>
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
                    <ul class="list-inline my-mainMenuList">
                        <li>
                            <a href="<?php echo base_url() . 'maintenance/logbook'; ?>">
                                <div class="menuWrap">
                                    <i class="fa fa-file-text-o fa-2x"></i>
                                    <br>
                                    <span>File Complaint</span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo base_url() . 'maintenance/actionLog'; ?>">
                                <div class="menuWrap">
                                    <i class="fa fa-tasks fa-2x"></i>
                                    <br>
                                    <span>Log View</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </main>
</body>
<?php echo $globalJs; ?>
</html>