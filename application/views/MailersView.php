<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Mailers :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="mailPage">
        <div class="container-fluid">
            <div class="row">
                <h2 class="text-center">Mail View</h2>
                <br>
                <div class="col-sm-2 col-xs-0"></div>
                <div class="col-sm-8 col-xs-12 text-center">
                    <ul class="list-inline my-mainMenuList">
                        <li>
                            <?php
                                if(count($expiredMugs['expiryMugList']) > 0)
                                {
                                    ?>
                                        <span class="badge"><?php echo count($expiredMugs['expiryMugList']); ?></span>
                                        <a class="mail-notify-remove" href="<?php echo base_url().'mailers/send/1';?>">
                                    <?php
                                }
                                else
                                {
                                    ?>
                                            <a class="mail-notify-remove" href="#">
                                    <?php
                                }
                            ?>
                                <div class="menuWrap">
                                    <i class="fa fa-battery-empty fa-2x my-danger-text"></i>
                                    <br>
                                    <span>Mugs Expired</span>
                                </div>
                            </a>
                        </li>
                        <!--<li>
                            <?php
/*                            if(count($expiringMugs['expiryMugList']) > 0)
                            {
                                */?>
                                    <span class="badge"><?php /*echo count($expiringMugs['expiryMugList']); */?></span>
                                    <a class="mail-notify-remove" href="<?php /*echo base_url().'mailers/send/2';*/?>">
                                <?php
/*                            }
                            else
                            {
                                */?>
                                    <a class="mail-notify-remove" href="#">
                                <?php
/*                            }
                            */?>
                                <div class="menuWrap">
                                    <i class="fa fa-battery-quarter fa-2x my-orange-text"></i>
                                    <br>
                                    <span>Mugs Expiring</span>
                                </div>
                            </a>
                        </li>-->
                        <li>
                            <?php
                            if(count($birthdayMugs['expiryMugList']) > 0)
                            {
                            ?>
                            <span class="badge"><?php echo count($birthdayMugs['expiryMugList']); ?></span>
                            <a class="mail-notify-remove" href="<?php echo base_url().'mailers/send/3';?>">
                                <?php
                                }
                                else
                                {
                                ?>
                                <a class="mail-notify-remove" href="#">
                                    <?php
                                    }
                                    ?>
                                    <div class="menuWrap">
                                        <i class="fa fa-birthday-cake fa-2x my-success-text"></i>
                                        <br>
                                        <span>Today Birthday's</span>
                                    </div>
                                </a>
                        </li>
                        <li>
                            <a href="<?php echo base_url().'mailers/send/0'; ?>">
                                <div class="menuWrap">
                                    <i class="fa fa-plus fa-2x"></i>
                                    <br>
                                    <span>Custom Mail</span>
                                </div>
                            </a>
                        </li>
                        <!-- For beer Olympics -->
                        <li>
                            <a href="<?php echo base_url().'mailers/sendBeer'; ?>">
                                <div class="menuWrap">
                                    <i class="fa fa-beer fa-2x"></i>
                                    <br>
                                    <span>Beer Olympics</span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo base_url().'mailers/pressSend'; ?>">
                                <div class="menuWrap">
                                    <i class="fa fa-television fa-2x"></i>
                                    <br>
                                    <span>Workshop Mail</span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo base_url().'mailers/templates';?>">
                                <div class="menuWrap">
                                    <i class="fa fa-book fa-2x"></i>
                                    <br>
                                    <span>Template View</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-sm-2 col-xs-0"></div>
            </div>
        </div>

    </main>
</body>
<?php echo $globalJs; ?>

<script>
    $(document).on('click','.mail-notify-remove', function(){
       var isAllMailDone = 1;
        $('.mail-notify-remove').each(function(i,val){
            if($(val).attr('href') != '#')
            {
                isAllMailDone = 0;
            }
        });
        if(isAllMailDone == 1)
        {
            removeNotifications();
        }
    });
</script>
</html>