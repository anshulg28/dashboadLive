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
        <div class="center sliding">Contact</div>
        <!--<div class="right">
            <i class="ic_me_refresh_icon point-item page-refresh-btn"></i>
        </div>-->
    </div>
</div>
<div class="pages">
    <div data-page="contactPage" class="page contact-page">
        <div class="page-content">
            <div class="content-block event-wrapper">
                <?php
                if(isset($locData) && myIsMultiArray($locData))
                {
                    foreach($locData as $key => $row)
                    {
                        if(isset($row['viewFrame']))
                        {
                            ?>
                            <div class="card">
                                <div class="row no-gutter">
                                    <div class="col-100">
                                        <?php echo $row['viewFrame'];?>
                                    </div>
                                </div>
                                <div class="card-content">
                                    <div class="card-content-inner">
                                        <div class="comment clear">
                                            <p>
                                                <?php echo $row['locName'];?> Taproom
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <a href="tel:<?php echo $row['phoneNumber']; ?>" class="external link">
                                        <span class="ic_event_contact_icon point-item"></span> Call
                                    </a>
                                    <a href="<?php echo $row['mapLink'];?>" class="external link" target="_blank">
                                        <i class="ic_me_location_icon point-item"></i>
                                        Get Directions
                                    </a>
                                </div>
                            </div>
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