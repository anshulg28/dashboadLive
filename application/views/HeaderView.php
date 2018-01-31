<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <div class="notification-indicator-mobile"></div>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo base_url();?>">Doolally</a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <?php
                if(isset($userActive))
                {
                    ?>
                    <input type="hidden" id="userActive" value="<?php echo $userActive;?>"/>
                    <?php
                }
            ?>
            <ul class="nav navbar-nav">
                <li><a href="<?php echo base_url();?>"><i class="fa fa-home"></i> Home</a></li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-bars"></i> Menu
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <?php
                        if(isSessionVariableSet($this->isUserSession) === true)
                        {
                            if(myInArray('maintenance',$userModules))
                            {
                                ?>
                                <li><a href="<?php echo base_url();?>maintenance"><i class="fa fa-cogs"></i> Maintenance</a></li>
                                <?php
                            }
                            if(myInArray('quiz',$userModules))
                            {
                                if($this->userType == SERVER_USER)
                                {
                                    if(myIsArray($quizNames))
                                    {
                                        ?>
                                        <li><a href="<?php echo base_url();?>quiz"><i class="fa fa-question-circle-o"></i> Quiz</a></li>
                                        <?php
                                    }
                                }
                                else
                                {
                                    ?>
                                    <li><a href="<?php echo base_url();?>quiz"><i class="fa fa-question-circle-o"></i> Quiz</a></li>
                                    <?php
                                }
                            }
                            if(myInArray('quiz_questions',$userModules))
                            {
                                ?>
                                <li><a href="<?php echo base_url();?>quiz/manageQuestions"><i class="fa fa-list-ol"></i> Quiz Questions</a></li>
                                <?php
                            }
                            if(myInArray('career',$userModules))
                            {
                                ?>
                                <li><a href="<?php echo base_url();?>career"><i class="fa fa-suitcase"></i> Careers</a></li>
                                <?php
                            }
                            if(myInArray('mug_club',$userModules))
                            {
                                ?>
                                <li><a href="<?php echo base_url();?>mugclub"><i class="fa fa-beer"></i> Mug Club</a></li>
                                <?php
                            }

                            if(myInArray('wallet_check',$userModules))
                            {
                                ?>
                                <li><a href="<?php echo base_url().'wallet';?>"><i class="fa fa-money"></i> Wallet Check</a></li>
                                <?php
                            }

                            if(myInArray('wallet_users',$userModules))
                            {
                                ?>
                                <li><a href="<?php echo base_url().'empDetails';?>"><i class="fa fa-users"></i> Wallet Users</a></li>
                                <?php
                            }

                            if(myInArray('user_mgt',$userModules))
                            {
                                ?>
                                <li><a href="<?php echo base_url();?>users"><i class="fa fa-user"></i> Users List</a></li>
                                <?php
                            }

                            if(myInArray('location_mgt',$userModules))
                            {
                                ?>
                                <li><a href="<?php echo base_url();?>locations"><i class="fa fa-globe"></i> Locations</a></li>
                                <?php
                            }

                            if(myInArray('mailers',$userModules))
                            {
                                ?>
                                <li><a href="<?php echo base_url();?>mailers"><i class="fa fa-envelope"></i> Mailers</a>
                                    <div class="notification-indicator"></div>
                                </li>
                                <?php
                            }

                            if(myInArray('offers',$userModules))
                            {
                                ?>
                                <li><a href="<?php echo base_url();?>offers"><i class="fa fa-trophy"></i> Offers</a></li>
                                <?php
                            }

                            if(myInArray('offers_check',$userModules) && !myInArray('offers',$userModules))
                            {
                                ?>
                                <li><a href="<?php echo base_url();?>offers/check"><i class="fa fa-trophy"></i> Offers Check</a></li>
                                <?php
                            }

                            if(myInArray('checkins',$userModules))
                            {
                                ?>
                                <li><a href="<?php echo base_url();?>check-ins"><i class="fa fa-calendar-check-o"></i> Check-Ins</a></li>
                                <?php
                            }
                        }
                        ?>
                    </ul>

                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php
                    if(isSessionVariableSet($this->isUserSession) === true)
                    {
                        if(isset($locInfo) && myIsArray($locInfo) && $this->userEmail == DEFAULT_COMM_EMAIL)
                        {
                            ?>
                            <li><a href="<?php echo base_url();?>dashboard/setCommLoc"><?php echo $locInfo[0]['locName'];?> (Change Location)</a></li>
                            <?php
                        }
                        ?>
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-user"></span>
                                <?php echo ucfirst($this->userName); ?>
                                <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <!--<li class="divider"></li>-->
                                <?php
/*                                    if($this->userType != WALLET_USER)
                                    {
                                        */?><!--
                                        <li><a href="<?php /*echo base_url();*/?>login/settings"><i class="fa fa-cog"></i> Settings</a></li>
                                        --><?php
/*                                    }
                                */?>
                                <li><a href="<?php echo base_url(); ?>login/logout"><i class="fa fa-sign-out"></i> Logout</a></li>
                            </ul>

                        </li>
                        <?php
                    }
                    else
                    {
                        ?>
                        <?php
                            /*if(isSessionVariableSet($this->currentLocation) === false)
                            {
                                */?><!--
                                <li><a href="<?php /*echo base_url().'location-select';*/?>">
                                        <i class="glyphicon glyphicon-map-marker"></i> Change Location</a>
                                </li>
                                --><?php
/*                            }*/
                        ?>
                        <li><a href="<?php echo base_url(); ?>login"><span class="glyphicon glyphicon-log-in"></span> Other Login</a></li>
                        <?php
                    }
                ?>

            </ul>
        </div>
    </div>
</nav>