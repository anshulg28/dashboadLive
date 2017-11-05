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
                        <?php
                        if(myInArray('maintenance_workarea',$userModules))
                        {
                            ?>
                            <li>
                                <a href="<?php echo base_url() . 'maintenance/workArea'; ?>">
                                    <div class="menuWrap">
                                        <i class="fa fa-area-chart fa-2x"></i>
                                        <br>
                                        <span>Work Area</span>
                                    </div>
                                </a>
                            </li>
                            <?php
                        }

                        if(myInArray('maintenance_worktype',$userModules))
                        {
                            ?>
                            <li>
                                <a href="<?php echo base_url() . 'maintenance/workType'; ?>">
                                    <div class="menuWrap">
                                        <i class="fa fa-clone fa-2x"></i>
                                        <br>
                                        <span>Work Type</span>
                                    </div>
                                </a>
                            </li>
                            <?php
                        }

                        if(myInArray('maintenance_useradd',$userModules))
                        {
                            ?>
                            <li>
                                <a href="<?php echo base_url() . 'maintenance/assignees'; ?>">
                                    <div class="menuWrap">
                                        <i class="fa fa-users fa-2x"></i>
                                        <br>
                                        <span>Show Assignees</span>
                                    </div>
                                </a>
                            </li>
                            <?php
                        }

                        if(myInArray('maintenance_complaint',$userModules))
                        {
                            ?>
                            <li>
                                <a href="<?php echo base_url() . 'maintenance/logbook'; ?>">
                                    <div class="menuWrap">
                                        <i class="fa fa-file-text-o fa-2x"></i>
                                        <br>
                                        <span>File Complaint</span>
                                    </div>
                                </a>
                            </li>
                            <?php
                        }

                        if(myInArray('maintenance_logview',$userModules))
                        {
                            ?>
                            <li>
                                <a href="<?php echo base_url() . 'maintenance/actionLog'; ?>">
                                    <div class="menuWrap">
                                        <i class="fa fa-tasks fa-2x"></i>
                                        <br>
                                        <span>Log View</span>
                                    </div>
                                </a>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </main>
</body>
<?php echo $globalJs; ?>
</html>