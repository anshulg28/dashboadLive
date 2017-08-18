<!DOCTYPE html>
<html lang="en" itemscope itemtype="http://schema.org/Article">
<head>
    <!-- Place this data between the <head> tags of your website -->
    <title><?php echo $eventDetails[0]['eventData']['eventName'];?></title>
    <?php
    $truncated_RestaurantName = (strlen($eventDetails[0]['eventData']['eventDescription']) > 140) ? substr($eventDetails[0]['eventData']['eventDescription'], 0, 140) . '..' : $eventDetails[0]['eventData']['eventDescription'];
    ?>
    <meta name="description" content="<?php echo $truncated_RestaurantName;?>" />

    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="<?php echo $eventDetails[0]['eventData']['eventName'];?>">
    <meta itemprop="description" content="<?php echo $truncated_RestaurantName;?>">
    <meta itemprop="image" content="<?php echo base_url().EVENT_PATH_THUMB.$eventDetails[0]['eventAtt'][0]['filename'];?>">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@godoolally">
    <meta name="twitter:title" content="<?php echo $eventDetails[0]['eventData']['eventName'];?>">
    <meta name="twitter:description" content="<?php echo $truncated_RestaurantName;?>">
    <meta name="twitter:creator" content="@godoolally">
    <!-- Twitter summary card with large image must be at least 280x150px -->
    <meta name="twitter:image:src" content="<?php echo base_url().EVENT_PATH_THUMB.$eventDetails[0]['eventAtt'][0]['filename'];?>">

    <!-- Open Graph data -->
    <meta property="og:title" content="<?php echo $eventDetails[0]['eventData']['eventName'];?>" />
    <meta property="og:type" content="article" />
    <meta property="og:url" content="http://doolally.io/" />
    <meta property="og:image" content="<?php echo base_url().EVENT_PATH_THUMB.$eventDetails[0]['eventAtt'][0]['filename'];?>" />
    <meta property="og:description" content="<?php echo $truncated_RestaurantName;?>" />
    <!--<meta property="og:site_name" content="Site Name, i.e. Moz" />-->
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="eventViewer">
        <div class="container">
            <div class="row">
                <div class="col-sm-2 col-xs-0"></div>
                <div class="col-sm-8 col-xs-12">
                <?php
                    if(isset($eventDetails) && myIsArray($eventDetails))
                    {
                        foreach($eventDetails as $key => $row)
                        {
                            if(isset($row['eventData']['eventId']))
                            {
                                ?>
                                <h2><i class="fa fa-calendar fa-1x"></i> <?php echo $row['eventData']['eventName'];?></h2>
                                <hr>
                                <div class="content-block event-wrapper">
                                    <?php
                                    if(isset($row['eventAtt']) && myIsMultiArray($row['eventAtt']))
                                    {
                                        ?>
                                        <img src="<?php echo base_url().EVENT_PATH_THUMB.$row['eventAtt'][0]['filename'];?>" class="img-responsive event-img"/>
                                        <?php
                                    }
                                    ?>
                                    <br>
                                    <!--<div class="event-header-name"><?php /*echo $row['eventData']['eventName'];*/?></div>-->
                                    <blockquote><?php echo strip_tags($row['eventData']['eventDescription'],'<br>');?></blockquote>
                                    <!--<hr class="card-ptag">-->
                                    <!-- Where section -->
                                    <div class="event-descrip-wrapper">
                                        <i class="fa fa-map-marker my-right-event-icon fa-15x"></i>
                                        <div class="event-header-name">Where</div>
                                        <div class="clear"></div>
                                        <p class="event-sub-text">
                                            Doolally Taproom, <?php echo $row['eventData']['locData'][0]['locName'];?>
                                        </p>
                                    </div>

                                    <!-- When Section -->
                                    <div class="event-descrip-wrapper">
                                        <span class="fa-15x ic_events_icon my-right-event-icon"></span>
                                        <div class="event-header-name">When</div>
                                        <div class="clear"></div>
                                        <p class="event-sub-text">
                                            <?php $d = date_create($row['eventData']['eventDate']);
                                            echo date('h:i a',strtotime($row['eventData']['startTime'])).'-'.date('h:i a',strtotime($row['eventData']['endTime'])).' ,'. date_format($d,EVENT_INSIDE_DATE_FORMAT);?>
                                        </p>
                                    </div>

                                    <!-- Entry Section -->
                                    <div class="event-descrip-wrapper">
                                        <div class="event-header-name">Entry</div>
                                        <p class="event-sub-text">
                                            <?php
                                            switch($row['eventData']['costType'])
                                            {
                                                case "1":
                                                    echo "Free";
                                                    break;
                                                case "2":
                                                    echo 'Rs. '.$row['eventData']['eventPrice'];
                                                    if(isset($row['eventData']['priceFreeStuff']) && isStringSet($row['eventData']['priceFreeStuff']))
                                                    {
                                                        echo ' + '.$row['eventData']['priceFreeStuff'];
                                                    }
                                                    break;
                                            }
                                            ?>
                                        </p>
                                    </div>
                                <?php
                            }
                        }
                    }
                    else
                    {
                        echo '<h2 class="my-danger-text text-center>Event Not Found!</h2>"';
                    }
                ?>
                </div>
                <div class="col-sm-2 col-xs-0"></div>
            </div>
        </div>
    </main>
</body>
<?php echo $globalJs; ?>

</html>