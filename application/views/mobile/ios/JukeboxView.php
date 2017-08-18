<!DOCTYPE html>
<html lang="en">

<body>
<!-- Status bar overlay for full screen mode (PhoneGap) -->
<!-- Top Navbar-->
<div class="navbar">
    <div class="navbar-inner">
        <div class="left">
            <a href="#" class="back link" data-ignore-cache="true">
                <i class="ic_back_icon point-item"></i>
            </a>
        </div>
        <div class="center sliding">Jukebox</div>
        <!--<div class="right">
            <i class="ic_me_refresh_icon point-item page-refresh-btn"></i>
        </div>-->
    </div>
</div>
<div class="pages">
    <div data-page="jukeboxPage" class="page jukebox-page">
        <div class="page-content">
            <div class="content-block">
                <?php
                if(isset($taprooms) && myIsMultiArray($taprooms))
                {
                    foreach($taprooms as $key => $row)
                    {
                        if(isset($row['id']) && $row['is_online'] === true)
                        {
                            ?>
                            <a href="taproom/<?php echo $row['id'];?>" class="taproom-btn">
                                <div class="card">
                                    <div class="row no-gutter">
                                        <div class="col-100">
                                            <img src="<?php echo $row['app_image_thumb'];?>" alt="<?php echo $row['name'];?>"
                                                 class="taproom-img"/>
                                        </div>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-content-inner">
                                            <div class="comment clear">
                                                <p>
                                                    <?php echo $row['name'];?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <?php
                        }
                    }
                }
                else
                {
                    ?>
                    <div class="content-block">
                        Not Available!
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>