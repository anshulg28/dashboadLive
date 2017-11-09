<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php echo $title; ?></title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="offerPage">
        <?php
            if(isSessionVariableSet($this->isUserSession) === true && $this->userType != SERVER_USER )
            {
                ?>
                <div class="container-fluid">
                    <div class="row">
                        <h2 class="text-center">Welcome <?php echo ucfirst($this->userName); ?></h2>
                        <br>
                        <div class="col-sm-12 text-center">
                            <ul class="list-inline my-mainMenuList">
                                <?php
                                    if(myInArray('offers_check',$userModules))
                                    {
                                        ?>
                                        <li>
                                            <a href="<?php echo base_url().'offers/check';?>">
                                                <div class="menuWrap">
                                                    <i class="fa fa-trophy fa-2x"></i>
                                                    <br>
                                                    <span>Offer Check</span>
                                                </div>
                                            </a>
                                        </li>
                                        <?php
                                    }

                                    if(myInArray('offers_gen',$userModules))
                                    {
                                        ?>
                                        <li>
                                            <a href="<?php echo base_url().'offers/generate';?>">
                                                <div class="menuWrap">
                                                    <i class="fa fa-cogs fa-2x"></i>
                                                    <br>
                                                    <span>Generate Codes</span>
                                                </div>
                                            </a>
                                        </li>
                                        <?php
                                    }

                                    if(myInArray('offers_stats',$userModules))
                                    {
                                        ?>
                                        <li>
                                            <a href="<?php echo base_url().'offers/stats'; ?>">
                                                <div class="menuWrap">
                                                    <i class="glyphicon glyphicon-stats fa-2x"></i>
                                                    <br>
                                                    <span>View Stats</span>
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
                <?php
            }
            else
            {
                echo "You Don't have Permission To Access This Page!";
            }
        ?>

    </main>
</body>
<?php echo $globalJs; ?>

</html>