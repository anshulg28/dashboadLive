<!DOCTYPE html>
<html lang="en">

<body>
<!-- Status bar overlay for full screen mode (PhoneGap) -->
<!-- Top Navbar-->
<div class="navbar mycustomNav">
    <div class="navbar-inner">
        <div class="left">
            <a href="#" class="link icon-only open-panel main-menu-icon">
                <!--<i class="fa fa-bars color-black"></i>-->
                <span class="d-logo"></span>
                <span class="bottom-bar-line"></span>
                <!--<span class="d-logo"></span>-->
                <!--<span class="bottom-bar-line"></span>-->
                <!--<i class="fa fa-minus"></i>
                <i class="fa fa-minus"></i>-->
            </a>
        </div>
        <div class="center sliding">
            <!--My Events-->
            <?php
                if(isSessionVariableSet($this->userMobFirstName))
                {
                    echo 'Welcome '.$this->userMobFirstName;
                }
                else
                {
                    echo 'My Events';
                }
            ?>
        </div>
        <div class="right">
            <?php
                if(isset($status) && $status === true)
                {
                    ?>
                    <a href="#" id="logout-btn">
                        <i class="ic_logout_icon point-item"></i>
                    </a>
                    <?php
                }
            ?>
        </div>
        <?php
            if(isset($status) && $status === true)
            {
                ?>
                <div class="subnavbar myCustomSubNav">
                    <!-- Buttons row as tabs controller in Subnavbar-->
                    <div class="buttons-row">
                        <!-- Link to 1st tab, active -->
                        <a href="#attending" class="button active tab-link">Attending</a>
                        <!-- Link to 2nd tab -->
                        <a href="#hosting" class="button tab-link">Hosting</a>
                    </div>
                </div>
                <?php
            }
        ?>
    </div>
</div>

<div class="pages">
    <div data-page="eventsDash" class="page even-dashboard">
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
                <input type="hidden" id="isLoggedIn" value="1"/>
                <!-- Tabs swipeable wrapper, required to switch tabs with swipes -->
                <div class="tabs-swipeable-wrap">
                    <!-- Tabs, tabs wrapper -->
                    <div class="tabs">
                        <!-- Tab 1, active by default -->
                        <div id="attending" class="page-content tab active">
                            <div class="content-block">
                                <?php
                                    if(isset($registeredEvents) && myIsMultiArray($registeredEvents))
                                    {
                                        $postImg = 0;
                                        foreach($registeredEvents as $key => $row)
                                        {
                                            $img_collection = array();
                                            ?>
                                            <div class="card demo-card-header-pic">
                                                <div class="row no-gutter">
                                                    <div class="col-100"> <!--more-photos-wrapper-->
                                                        <?php
                                                        if($postImg >=10)
                                                        {
                                                            ?>
                                                            <img src="<?php echo base_url().EVENT_PATH_THUMB.$row['filename'];?>" class="mainFeed-img"/>
                                                            <?php
                                                        }
                                                        else
                                                        {
                                                            ?>
                                                            <img data-src="<?php echo base_url().EVENT_PATH_THUMB.$row['filename'];?>" class="mainFeed-img lazy lazy-fadein"/>
                                                            <?php
                                                        }
                                                        $postImg++;
                                                        ?>
                                                    </div>
                                                </div>
                                                <!--<div style="background-image:url()" valign="bottom" class="card-header color-white no-border">Journey To Mountains</div>-->
                                                <div class="card-content">
                                                    <div class="card-content-inner">
                                                        <div class="event-info-wrapper">
                                                            <p class="pull-left card-ptag event-date-tag">
                                                                <?php
                                                                $eventName = (strlen($row['eventName']) > 25) ? substr($row['eventName'], 0, 25) . '..' : $row['eventName'];
                                                                echo $eventName;?>
                                                            </p>
                                                            <input type="hidden" data-name="<?php echo $row['eventName'];?>" value="<?php echo $row['eventShareLink'];?>"/>
                                                            <i class="ic_me_share_icon pull-right event-share-icn event-card-share-btn"></i>
                                                        </div>

                                                        <div class="comment my-event-status">
                                                            <?php echo $row['eventDescription'];?>
                                                            <p>
                                                                <i class="ic_me_location_icon main-loc-icon"></i>&nbsp;<?php echo $row['locName']; ?>
                                                                &nbsp;&nbsp;<span class="ic_events_icon event-date-main"></span>&nbsp;
                                                                <?php $d = date_create($row['eventDate']);
                                                                echo date_format($d,EVENT_DATE_FORMAT); ?>
                                                                &nbsp;&nbsp;<i class="ic_me_rupee_icon main-rupee-icon"></i>
                                                                <?php
                                                                switch($row['costType'])
                                                                {
                                                                    case "1":
                                                                        echo "Free";
                                                                        break;
                                                                    case "2":
                                                                        echo 'Rs '.$row['eventPrice'];
                                                                        break;
                                                                }
                                                                ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer event-card-footer">
                                                    <a href="#" class="link color-black">View&nbsp;Details</a>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                        <!-- Tab 2 -->
                        <div id="hosting" class="page-content tab">
                            <div class="content-block">
                                <?php
                                    if(isset($userEvents) && myIsMultiArray($userEvents))
                                    {
                                        $postImg = 0;
                                        foreach($userEvents as $key => $row)
                                        {
                                            $img_collection = array();
                                            ?>
                                            <div class="card demo-card-header-pic">
                                                <div class="row no-gutter">
                                                    <div class="col-100"> <!--more-photos-wrapper-->
                                                        <?php
                                                        if($postImg >=10)
                                                        {
                                                            ?>
                                                            <img src="<?php echo base_url().EVENT_PATH_THUMB.$row['filename'];?>" class="mainFeed-img"/>
                                                            <?php
                                                        }
                                                        else
                                                        {
                                                            ?>
                                                            <img data-src="<?php echo base_url().EVENT_PATH_THUMB.$row['filename'];?>" class="mainFeed-img lazy lazy-fadein"/>
                                                            <?php
                                                        }
                                                        $postImg++;
                                                        ?>
                                                    </div>
                                                </div>
                                                <!--<div style="background-image:url()" valign="bottom" class="card-header color-white no-border">Journey To Mountains</div>-->
                                                <div class="card-content">
                                                    <div class="card-content-inner">
                                                        <div class="event-info-wrapper">
                                                            <p class="pull-left card-ptag event-date-tag">
                                                                <?php
                                                                $eventName = (strlen($row['eventName']) > 25) ? substr($row['eventName'], 0, 25) . '..' : $row['eventName'];
                                                                echo $eventName;?>
                                                            </p>
                                                            <input type="hidden" data-name="<?php echo $row['eventName'];?>" value="<?php echo $row['eventShareLink'];?>"/>
                                                            <?php
                                                            if($row['ifApproved'] == EVENT_APPROVED && $row['ifActive'] == ACTIVE)
                                                            {
                                                                ?>
                                                                <i class="ic_me_share_icon pull-right event-share-icn event-card-share-btn"></i>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>

                                                        <div class="comment my-event-status">
                                                            <span>
                                                            <?php
                                                            $isApprov = false;
                                                                if($row['ifApproved'] == EVENT_DECLINED)
                                                                {
                                                                    ?>
                                                                    <i class="ic_me_info_icon info-icon"></i>&nbsp;&nbsp;Event Declined!<?php
                                                                }
                                                                elseif($row['ifApproved'] == EVENT_WAITING)
                                                                {
                                                                    ?>
                                                                    <i class="ic_me_info_icon info-icon"></i>&nbsp;&nbsp;Review In Progress...<?php
                                                                }
                                                                elseif($row['ifApproved'] == EVENT_APPROVED && $row['ifActive'] == ACTIVE)
                                                                {
                                                                    $isApprov = true;
                                                                    ?>
                                                                    <i class="ic_me_info_icon info-icon"></i>&nbsp;&nbsp;Event Approved!<?php
                                                                }
                                                                    elseif($row['ifApproved'] == EVENT_APPROVED && $row['ifActive'] == NOT_ACTIVE)
                                                                {
                                                                    $isApprov = true;
                                                                    ?>
                                                                    <i class="ic_me_info_icon info-icon"></i>&nbsp;&nbsp;Event Approved But Not Active<?php
                                                                }
                                                            ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer event-card-footer">
                                                    <?php
                                                        if($isApprov === true)
                                                        {
                                                            ?>
                                                            <a href="<?php echo 'event_details/EV-'.$row['eventId'].'/'.encrypt_data('EV-'.$row['eventId']);?>" data-ignore-cache="true" class="link color-black event-bookNow">View&nbsp;Details</a>
                                                            <?php
                                                        }
                                                        else
                                                        {
                                                            ?>
                                                            <a href="<?php echo 'event_details/EV-'.$row['eventId'].'/'.encrypt_data('EV-'.$row['eventId']);?>" data-ignore-cache="true" class="link color-black event-bookNow" disabled>View&nbsp;Details</a>
                                                            <?php
                                                        }
                                                    ?>

                                                </div>
                                            </div>
                                            <?php
                                        }
                                    }
                                    else
                                    {
                                        echo 'No Event Created Yet!';
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
            <!--<div class="content-block">

            </div>-->
            <div class="page-content login-screen">
                <div class="login-screen-title">Doolally Login</div>
                <form action="<?php echo base_url().'mobile/main/checkUser';?>" id="user-app-login" method="post" class="ajax-submit">
                    <div class="list-block">
                        <ul>
                            <li class="item-content">
                                <div class="item-inner">
                                    <div class="item-title label">Username</div>
                                    <div class="item-input">
                                        <input type="text" name="username" placeholder="Username/Email">
                                    </div>
                                </div>
                            </li>
                            <li class="item-content">
                                <div class="item-inner">
                                    <div class="item-title label">Password</div>
                                    <div class="item-input">
                                        <input type="password" name="password" placeholder="Your password">
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="list-block">
                        <ul>
                            <li><input type="submit" class="button button-big button-fill" value="Sign In"/></li>
                        </ul>
                    </div>
                </form>
            </div>
        </div>
    </div>
<!--    <div class="login-screen">
        <div class="view">
            <div class="page">

            </div>
        </div>
    </div>-->
</div>

</body>
</html>