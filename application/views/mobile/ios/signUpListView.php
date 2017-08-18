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
        <div class="center sliding">Signups</div>
        <div class="right">
            <i class="ic_me_refresh_icon point-item page-refresh-btn"></i>
        </div>
    </div>
</div>
<div class="pages">
    <div data-page="signUpListPage" class="page event-details">
        <div class="page-content">
            <?php
                if(isset($status) && $status === false)
                {
                    ?>
                    <a href="#" class="open-login-screen" id="login-btn">Open Login Screen</a>
                    <input type="hidden" id="isLoggedIn" value="0"/>
                    <?php
                }
                else
                {
                    ?>
                    <div class="content-block event-wrapper">
                        <div class="row no-gutter">
                            <div class="col-100">
                                <?php
                                    if(isset($eventDetails) && myIsMultiArray($eventDetails))
                                    {
                                        ?>
                                        <div class="list-block">
                                            <ul>
                                        <?php
                                        foreach($eventDetails as $key => $row)
                                        {
                                            $remain = (int)$row['quantity'] - 1;
                                            ?>
                                                <li class="item-content">
                                                    <div class="item-inner">
                                                        <div class="item-title"><?php echo $row['firstName'].' '.$row['lastName'];?></div>
                                                        <?php
                                                            if($remain != 0)
                                                            {
                                                                ?>
                                                                <div class="item-after">
                                                                    <span class="badge color-green">+<?php echo $remain;?></span>
                                                                </div>
                                                                <?php
                                                            }
                                                        ?>
                                                    </div>
                                                </li>
                                            <?php
                                        }
                                        ?>
                                            </ul>
                                        </div>
                                        <?php
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            ?>
        </div>
    </div>
</div>
</body>
</html>