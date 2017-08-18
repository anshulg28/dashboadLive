<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Mug Club Portal :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="homePage">
        <?php
        if(isSessionVariableSet($this->isUserSession) === true)
        {
            if($this->userType != OFFERS_USER)
            {
                ?>
                <div class="container-fluid">
                    <div class="row">
                        <h2 class="text-center">Welcome <?php echo ucfirst($this->userName); ?></h2>
                        <br>
                        <div class="col-sm-12 text-center">
                            <ul class="list-inline my-mainMenuList">
                                <li>
                                    <a href="<?php echo base_url().'mugclub';?>">
                                        <div class="menuWrap">
                                            <i class="fa fa-beer fa-2x"></i>
                                            <br>
                                            <span>Mug Club</span>
                                        </div>
                                    </a>
                                </li>
                                <?php
                                if($this->userType != SERVER_USER)
                                {
                                    ?>
                                    <li>
                                        <div class="notification-indicator-big"></div>
                                        <a href="<?php echo base_url().'mailers';?>">
                                            <div class="menuWrap">
                                                <i class="fa fa-envelope fa-2x"></i>
                                                <br>
                                                <span>Mail View</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url().'users';?>">
                                            <div class="menuWrap">
                                                <i class="fa fa-user fa-2x"></i>
                                                <br>
                                                <span>Users List</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url().'locations'; ?>">
                                            <div class="menuWrap">
                                                <i class="fa fa-globe fa-2x"></i>
                                                <br>
                                                <span>Locations</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url() . 'offers'; ?>">
                                            <div class="menuWrap">
                                                <i class="fa fa-trophy fa-2x"></i>
                                                <br>
                                                <span>Offers Page</span>
                                            </div>
                                        </a>
                                    </li>
                                    <?php
                                }
                                if($this->userType == SERVER_USER)
                                {
                                    ?>
                                    <li>
                                        <a href="<?php echo base_url() . 'check-ins/add'; ?>">
                                            <div class="menuWrap">
                                                <i class="fa fa-calendar-check-o fa-2x"></i>
                                                <br>
                                                <span>Check-Ins</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url() . 'offers/check'; ?>">
                                            <div class="menuWrap">
                                                <i class="fa fa-trophy fa-2x"></i>
                                                <br>
                                                <span>Offers Check</span>
                                            </div>
                                        </a>
                                    </li>
                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <li>
                                        <a href="<?php echo base_url() . 'check-ins'; ?>">
                                            <div class="menuWrap">
                                                <i class="fa fa-calendar-check-o fa-2x"></i>
                                                <br>
                                                <span>Check-Ins</span>
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
                ?>
                <div class="container-fluid">
                    <div class="row">
                        <h2 class="text-center">Welcome <?php echo ucfirst($this->userName); ?></h2>
                        <br>
                        <div class="col-sm-12 text-center">
                            <ul class="list-inline my-mainMenuList">
                                <li>
                                    <a href="<?php echo base_url() . 'offers'; ?>">
                                        <div class="menuWrap">
                                            <i class="fa fa-trophy fa-2x"></i>
                                            <br>
                                            <span>Offers Page</span>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
        else
        {
            ?>
            <div class="container-fluid">
                <h2 class="text-center">Login</h2>
                <hr>
                <form action="<?php echo base_url();?>login/checkUser/json" id="mainLoginForm" method="post" class="form-horizontal" role="form">
                    <div class="login-error-block text-center"></div>
                    <br>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="email">Username:</label>
                        <div class="col-sm-10">
                            <input type="text" name="userName" class="form-control" id="email" placeholder="Enter Username">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="pwd">Password:</label>
                        <div class="col-sm-10">
                            <input type="password" name="password" class="form-control" id="pwd" placeholder="Enter password">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
            <?php
        }
        ?>

    </main>
</body>
<?php echo $globalJs; ?>

</html>