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
            <ul class="nav navbar-nav">
                <?php
                if(isSessionVariableSet($this->isUserSession) === true)
                {
                    ?>
                    <li><a href="<?php echo base_url();?>"><i class="fa fa-home"></i> Home</a></li>
                    <li><a href="<?php echo base_url();?>maintenance/logbook"><i class="fa fa-file-text-o"></i> File Complaint</a></li>
                    <li><a href="<?php echo base_url();?>maintenance/actionLog"><i class="fa fa-tasks"></i> Log View</a></li>
                    <?php
                }
                ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php
                    if(isSessionVariableSet($this->isUserSession) === true)
                    {
                        ?>
                        <li><a href="<?php echo base_url(); ?>login/logout"><i class="fa fa-sign-out"></i> Logout</a></li>
                        <?php
                    }
                    else
                    {
                        ?>
                        <li><a href="<?php echo base_url(); ?>login"><span class="glyphicon glyphicon-log-in"></span> Other Login</a></li>
                        <?php
                    }
                ?>

            </ul>
        </div>
    </div>
</nav>