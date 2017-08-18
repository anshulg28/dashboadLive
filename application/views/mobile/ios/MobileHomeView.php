<!DOCTYPE html>
<html lang="en">
<head>
    <?php
        if(isset($meta) && myIsArray($meta))
        {
            ?>
            <title><?php echo $meta['title'];?></title>
            <meta name="description" content="<?php echo $meta['description'];?>" />

            <!-- Schema.org markup for Google+ -->
            <meta itemprop="name" content="<?php echo $meta['title'];?>">
            <meta itemprop="description" content="<?php echo $meta['description'];?>">
            <meta itemprop="image" content="<?php echo base_url().EVENT_PATH_THUMB.$meta['img'];?>">

            <!-- Twitter Card data -->
            <meta name="twitter:card" content="summary_large_image">
            <meta name="twitter:site" content="@godoolally">
            <meta name="twitter:title" content="<?php echo $meta['title'];?>">
            <meta name="twitter:description" content="<?php echo $meta['description'];?>">
            <meta name="twitter:creator" content="@godoolally">
            <!-- Twitter summary card with large image must be at least 280x150px -->
            <meta name="twitter:image:src" content="<?php echo base_url().EVENT_PATH_THUMB.$meta['img'];?>">

            <!-- Open Graph data -->
            <meta property="og:title" content="<?php echo $meta['title'];?>" />
            <meta property="og:type" content="website" />
            <meta property="og:url" content="<?php echo $meta['link'];?>" />
            <meta property="og:image" content="<?php echo base_url().EVENT_PATH_THUMB.$meta['img'];?>" />
            <meta property="og:description" content="<?php echo $meta['description'];?>" />
            <?php
        }
        else
        {
            ?>
            <title>Doolally</title>
            <?php
        }
    ?>
	<?php echo $mobileStyle; ?>
    <?php echo $iosStyle; ?>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans|Averia+Serif+Libre' rel='stylesheet' type='text/css'>
</head>
<body class="iosHome">
    <!-- Status bar overlay for full screen mode (PhoneGap) -->
    <div class="statusbar-overlay"></div>
    <!-- Panels overlay-->
    <div class="panel-overlay"></div>
    <!-- Left panel with reveal effect-->
    <div class="panel panel-left panel-cover">
        <div class="content-block">
            <ul class="demo-list-icon mdl-list">
                <li class="mdl-list__item">
                    <a href="#" id="global-home-btn" class="my-fullWidth">
                        <span class="mdl-list__item-primary-content">
                            <i class="fa fa-home fa-15x mdl-list__item-icon"></i>
                            <!--<i class="material-icons ">location_on</i>-->
                            <!--<input type="text" class="my-select" placeholder="Select Taproom" readonly id="picker-device">-->
                            <!--<select class="my-select">
                                <option value="andheri" selected>Andheri Taproom</option>
                                <option value="bandra">Bandra Taproom</option>
                                <option value="kemps">Kemps Temproom</option>
                            </select>-->
                            Home
                        </span>
                    </a>
                </li>
                <!--<li class="mdl-list__item">
                    <a href="#">
                        <span class="mdl-list__item-primary-content">
                            <i class="ic_mug_icon mdl-list__item-icon"></i>
                            Mug Club
                        </span>
                    </a>
                </li>-->
                <li class="mdl-list__item">
                    <a href="#" id="my-jukebox-tab" class="my-fullWidth">
                        <span class="mdl-list__item-primary-content">
                            <i class="fa fa-music mdl-list__item-icon"></i>
                            <!--<i class="material-icons mdl-list__item-icon">music_note</i>-->
                            Jukebox
                        </span>
                    </a>
                </li>
                <li class="mdl-list__item">
                    <a href="#" id="my-events-tab" class="my-fullWidth">
                        <span class="mdl-list__item-primary-content">
                            <i class="fa fa-calendar mdl-list__item-icon"></i>
                            <!--<i class="material-icons mdl-list__item-icon">insert_invitation</i>-->
                            My Events
                        </span>
                    </a>
                </li>
                <li class="user-settings mdl-list__item <?php if(isSessionVariableSet($this->isMobUserSession) === false){echo 'hide';}?>">
                    <a href="#" class="my-fullWidth">
                    <span class="mdl-list__item-primary-content">
                        <i class="fa fa-cog mdl-list__item-icon"></i>
                        <!--<i class="material-icons mdl-list__item-icon">settings</i>-->
                        Settings
                    </span>
                    </a>
                </li>
                <li class="mdl-list__item">
                    <a href="#" id="contact-tab" class="my-fullWidth">
                        <span class="mdl-list__item-primary-content">
                            <i class="fa fa-life-ring mdl-list__item-icon"></i>
                            <!--<i class="material-icons mdl-list__item-icon">insert_invitation</i>-->
                            Contact Us
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <!-- Views -->
    <div class="views tabs toolbar-through">
        <!--<div class="tabs">-->
        <!-- Your main view, should have "view-main" class -->
        <div id="tab1" class="view view-commingUp tab">
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
                    <div class="right">
                        <i class="ic_filter_icon open-popover event-filter-toggler" data-popover=".popover-links"></i>
                    </div>
                    <!--<div class="center sliding">events</div>-->
                </div>
            </div>

            <!-- Pages container, because we use fixed-through navbar and toolbar, it has additional appropriate classes-->
            <div class="pages navbar-through toolbar-through">
                <!-- Page, "data-page" contains page name -->
                <div data-page="main-feeds" class="page">
                    <!-- Scrollable page content -->
                    <div class="page-content infinite-scroll pull-to-refresh-content" data-ptr-distance="55" id="my-page2">
                        <div class="pull-to-refresh-layer">
                            <div class="preloader"></div>
                            <div class="pull-to-refresh-arrow"></div>
                        </div>
                        <div class="content-block custom-accordion">
                            <?php
                            if(isset($myFeeds) && myIsArray($myFeeds))
                            {
                                $postlimit = 0;
                                foreach($myFeeds as $key => $row)
                                {
                                    if(isset($row['socialType']))
                                    {
                                        switch($row['socialType'])
                                        {
                                            case 't':
                                                $row['text'] = preg_replace('!(http|ftp|scp)(s)?:\/\/[a-zA-Z0-9.?%=&_/]+!', "", $row['text']);
                                                $row['text'] = highlight('/#\w+/',$row['text']);
                                                $row['text'] = highlight('/@\w+/',$row['text']);
                                                //$truncated_RestaurantName = (strlen($row['text']) > 140) ? substr($row['text'], 0, 140) . '..' : $row['text'];
                                                ?>
                                                <!--twitter://status?status_id=756765768470130689-->
                                                <a href="https://twitter.com/<?php echo $row['user']['screen_name'];?>/status/<?php echo $row['id_str'];?>" target="_blank" class="external twitter-wrapper">
                                                    <div class="my-card-items <?php if($postlimit >= 5){echo 'hide';} $postlimit++; ?>">
                                                        <div class="card demo-card-header-pic">
                                                            <div class="card-content">
                                                                <div class="card-content-inner">
                                                                    <div class="list-block media-list">
                                                                        <ul>
                                                                            <li>
                                                                                <div class="item-content">
                                                                                    <div class="item-media">
                                                                                        <?php
                                                                                        if($postlimit > 5)
                                                                                        {
                                                                                            ?>
                                                                                            <img class="myAvtar-list" data-src="<?php echo $row['user']['profile_image_url'];?>" width="44"/>
                                                                                            <?php
                                                                                        }
                                                                                        else
                                                                                        {
                                                                                            ?>
                                                                                            <img class="myAvtar-list" src="<?php echo $row['user']['profile_image_url'];?>" width="44"/>
                                                                                            <?php
                                                                                        }
                                                                                        ?>
                                                                                    </div>
                                                                                    <div class="item-inner">
                                                                                        <div class="item-title-row">
                                                                                            <div class="item-title"><?php echo ucfirst($row['user']['name']);?></div>
                                                                                            <i class="fa fa-twitter social-icon-gap"></i>
                                                                                        </div>
                                                                                        <div class="item-subtitle">@<?php echo $row['user']['screen_name'];?>
                                                                                            <time class="timeago time-stamp" datetime="<?php echo $row['created_at'];?>"></time>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                    <?php
                                                                    if(isset($row['extended_entities']))
                                                                    {
                                                                        ?>
                                                                        <div class="row no-gutter feed-image-container">
                                                                            <?php
                                                                            $imageLimit = 0;
                                                                            foreach($row['extended_entities']['media'] as $mediaKey => $mediaRow)
                                                                            {
                                                                                if($imageLimit >= 1)
                                                                                {
                                                                                    $isImageDone = true;
                                                                                    break;
                                                                                }
                                                                                $imageLimit++;
                                                                                if(isset($mediaRow['video_info']['variants']) && myIsArray($mediaRow['video_info']['variants']))
                                                                                {
                                                                                    $videoUrl= '';
                                                                                    $videoType = '';
                                                                                    foreach($mediaRow['video_info']['variants'] as $videoKey => $videoRow)
                                                                                    {
                                                                                        if(isset($videoRow['bitrate']))
                                                                                        {
                                                                                            $videoUrl = $videoRow['url'];
                                                                                            $videoType = $videoRow['content_type'];
                                                                                        }
                                                                                    }
                                                                                    if(strpos($videoUrl,"youtube") !== false || strpos($videoUrl,"youtu.be"))
                                                                                    {
                                                                                        ?>
                                                                                        <div class="col-100">
                                                                                            <iframe width="100%" src="<?php echo $videoUrl;?>" frameborder="0" allowfullscreen>
                                                                                            </iframe>
                                                                                        </div>
                                                                                        <?php
                                                                                    }
                                                                                    else
                                                                                    {
                                                                                        ?>
                                                                                        <div class="col-100">
                                                                                            <video width="100%" controls>
                                                                                                <source src="<?php echo $videoUrl;?>" type="<?php echo $videoType;?>">
                                                                                                No Video Found!
                                                                                            </video>
                                                                                        </div>
                                                                                        <?php
                                                                                    }
                                                                                }
                                                                                else
                                                                                {
                                                                                    ?>
                                                                                    <div class="col-100">
                                                                                        <?php
                                                                                        if($postlimit > 5)
                                                                                        {
                                                                                            ?>
                                                                                            <img data-src="<?php echo $mediaRow['media_url'];?>" class="mainFeed-img"/>
                                                                                            <?php
                                                                                        }
                                                                                        else
                                                                                        {
                                                                                            ?>
                                                                                            <img src="<?php echo $mediaRow['media_url'];?>" class="mainFeed-img"/>
                                                                                            <?php
                                                                                        }
                                                                                        ?>
                                                                                    </div>
                                                                                    <?php
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </div>
                                                                        <?php
                                                                    }
                                                                    elseif(isset($row['entities']['urls']) && myIsMultiArray($row['entities']['urls']))
                                                                    {
                                                                        ?>
                                                                        <div class="link-card-wrapper">
                                                                            <input type="hidden" class="my-link-url" value="<?php echo $row['entities']['urls'][0]['expanded_url'];?>"/>
                                                                            <div class="liveurl feed-image-container hide">
                                                                                <img src="" class="link-image mainFeed-img lazy lazy-fadeIn" />
                                                                                <div class="details">
                                                                                    <div class="title"></div>
                                                                                    <div class="description"></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                    <p class="final-card-text"><?php echo $row['text'];?></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                                <?php
                                                break;
                                            case 'f':
                                                preg_match_all('!(http|ftp|scp)(s)?:\/\/[a-zA-Z0-9.?%=&_/]+!', $row['message'], $backupLink);
                                                $row['message'] = preg_replace('!(http|ftp|scp)(s)?:\/\/[a-zA-Z0-9.?%=&_/]+!', "", $row['message']);
                                                $row['message'] = highlight('/#\w+/',$row['message']);
                                                $row['message'] = highlight('/@\w+/',$row['message']);
                                                $truncated_RestaurantName = (strlen($row['message']) > 140) ? substr($row['message'], 0, 140) . '..' : $row['message'];
                                                ?>
                                                <!--twitter://status?status_id=756765768470130689-->
                                                <a href="<?php echo $row['permalink_url'];?>" target="_blank" class="external facebook-wrapper">
                                                    <div class="my-card-items <?php if($postlimit >= 5){echo 'hide';} $postlimit++; ?>">
                                                        <div class="card demo-card-header-pic">
                                                            <div class="card-content">
                                                                <div class="card-content-inner">
                                                                    <div class="list-block media-list">
                                                                        <ul>
                                                                            <li>
                                                                                <div class="item-content">
                                                                                    <div class="item-media">
                                                                                        <?php
                                                                                        if($postlimit > 5)
                                                                                        {
                                                                                            ?>
                                                                                            <img class="myAvtar-list" data-src="https://graph.facebook.com/v2.7/<?php echo $row['from']['id'];?>/picture" width="44"/>
                                                                                            <?php
                                                                                        }
                                                                                        else
                                                                                        {
                                                                                            ?>
                                                                                            <img class="myAvtar-list" src="https://graph.facebook.com/v2.7/<?php echo $row['from']['id'];?>/picture" width="44"/>
                                                                                            <?php
                                                                                        }
                                                                                        ?>
                                                                                    </div>
                                                                                    <div class="item-inner">
                                                                                        <div class="item-title-row">
                                                                                            <div class="item-title"><?php echo ucfirst($row['from']['name']);?></div>
                                                                                            <i class="fa fa-facebook-official social-icon-gap"></i>
                                                                                        </div>
                                                                                        <div class="item-subtitle">
                                                                                            <time class="timeago time-stamp" datetime="<?php echo $row['created_at'];?>"></time>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                    <?php
                                                                    if(isset($row['source']))
                                                                    {
                                                                        if(strpos($row['source'],"youtube") !== false || strpos($row['source'],"youtu.be") !== false)
                                                                        {
                                                                            ?>
                                                                            <div class="row no-gutter feed-image-container" onclick="preventAct(event)">
                                                                                <div class="col-100">
                                                                                    <iframe width="100%" src="<?php echo $row['source'];?>" frameborder="0" allowfullscreen>
                                                                                    </iframe>
                                                                                </div>
                                                                            </div>
                                                                            <?php
                                                                        }
                                                                        else
                                                                        {
                                                                            ?>
                                                                            <div class="row no-gutter feed-image-container" onclick="preventAct(event)">
                                                                                <div class="col-100">
                                                                                    <video width="100%" controls>
                                                                                        <source src="<?php echo $row['source'];?>" >
                                                                                        No Video Found!
                                                                                    </video>
                                                                                </div>
                                                                            </div>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    elseif(isset($row['picture']))
                                                                    {
                                                                        ?>
                                                                        <div class="row no-gutter feed-image-container">
                                                                            <div class="col-100">
                                                                                <?php
                                                                                if($postlimit > 5)
                                                                                {
                                                                                    ?>
                                                                                    <img data-src="<?php echo $row['picture'];?>" class="mainFeed-img"/>
                                                                                    <?php
                                                                                }
                                                                                else
                                                                                {
                                                                                    ?>
                                                                                    <img src="<?php echo $row['picture'];?>" class="mainFeed-img"/>
                                                                                    <?php
                                                                                }
                                                                                ?>
                                                                            </div>
                                                                        </div>
                                                                        <?php
                                                                    }
                                                                    elseif(isset($backupLink) && myIsMultiArray($backupLink))
                                                                    {
                                                                        ?>
                                                                        <div class="link-card-wrapper">
                                                                            <input type="hidden" class="my-link-url" value="<?php echo $backupLink[0][0];?>"/>
                                                                            <div class="liveurl feed-image-container hide">
                                                                                <img src="" class="link-image mainFeed-img lazy lazy-fadeIn" />
                                                                                <div class="details">
                                                                                    <div class="title"></div>
                                                                                    <div class="description"></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                    <p class="final-card-text"><?php echo $truncated_RestaurantName;?></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                                <?php
                                                break;
                                            case 'i':
                                                preg_match_all('!(http|ftp|scp)(s)?:\/\/[a-zA-Z0-9.?%=&_/]+!', $row['unformatted_message'], $backupLink);
                                                $row['unformatted_message'] = preg_replace('!(http|ftp|scp)(s)?:\/\/[a-zA-Z0-9.?%=&_/]+!', "", $row['unformatted_message']);
                                                $row['unformatted_message'] = highlight('/#\w+/',$row['unformatted_message']);
                                                $row['unformatted_message'] = highlight('/@\w+/',$row['unformatted_message']);
                                                $truncated_RestaurantName = (strlen($row['unformatted_message']) > 140) ? substr($row['unformatted_message'], 0, 140) . '..' : $row['unformatted_message'];
                                                ?>
                                                <!--twitter://status?status_id=756765768470130689-->
                                                <a href="<?php echo $row['full_url'];?>" target="_blank" class="external instagram-wrapper">
                                                    <div class="my-card-items <?php if($postlimit >= 5){echo 'hide';} $postlimit++; ?>">
                                                        <div class="card demo-card-header-pic">
                                                            <div class="card-content">
                                                                <div class="card-content-inner">
                                                                    <div class="list-block media-list">
                                                                        <ul>
                                                                            <li>
                                                                                <div class="item-content">
                                                                                    <div class="item-media">
                                                                                        <?php
                                                                                        if($postlimit > 5)
                                                                                        {
                                                                                            ?>
                                                                                            <img class="myAvtar-list" data-src="<?php echo $row['poster_image'];?>" width="44"/>
                                                                                            <?php
                                                                                        }
                                                                                        else
                                                                                        {
                                                                                            ?>
                                                                                            <img class="myAvtar-list" src="<?php echo $row['poster_image'];?>" width="44"/>
                                                                                            <?php
                                                                                        }
                                                                                        ?>
                                                                                    </div>
                                                                                    <div class="item-inner">
                                                                                        <div class="item-title-row">
                                                                                            <div class="item-title"><?php echo ucfirst($row['poster_name']);?></div>
                                                                                            <i class="fa fa-instagram social-icon-gap"></i>
                                                                                        </div>
                                                                                        <?php
                                                                                        if(isset($row['source']))
                                                                                        {
                                                                                            if($row['source']['term_type'] == 'hashtag')
                                                                                            {
                                                                                                ?>
                                                                                                <div class="item-subtitle">#<?php echo $row['source']['term'];?>
                                                                                                    <time class="timeago time-stamp" datetime="<?php echo $row['created_at'];?>"></time>
                                                                                                </div>
                                                                                                <?php
                                                                                            }
                                                                                            elseif($row['source']['term_type'] == 'location')
                                                                                            {
                                                                                                $locs = $this->config->item('insta_locationMap');
                                                                                                ?>
                                                                                                <div class="item-subtitle"><?php echo $locs[$row['source']['term']];?>
                                                                                                    <time class="timeago time-stamp" datetime="<?php echo $row['created_at'];?>"></time>
                                                                                                </div>
                                                                                                <?php
                                                                                            }
                                                                                            else
                                                                                            {
                                                                                                ?>
                                                                                                <div class="item-subtitle">@<?php echo $row['source']['term'];?>
                                                                                                    <time class="timeago time-stamp" datetime="<?php echo $row['created_at'];?>"></time>
                                                                                                </div>
                                                                                                <?php
                                                                                            }
                                                                                        }
                                                                                        ?>
                                                                                    </div>
                                                                                </div>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                    <?php
                                                                    if(isset($row['video']))
                                                                    {
                                                                        if(strpos($row['video'],"youtube") !== false || strpos($row['video'],"youtu.be") !== false)
                                                                        {
                                                                            ?>
                                                                            <div class="row no-gutter feed-image-container">
                                                                                <div class="col-100">
                                                                                    <iframe width="100%" src="<?php echo $row['video'];?>" frameborder="0" allowfullscreen>
                                                                                    </iframe>
                                                                                </div>
                                                                            </div>
                                                                            <?php
                                                                        }
                                                                        else
                                                                        {
                                                                            ?>
                                                                            <div class="row no-gutter feed-image-container">
                                                                                <div class="col-100">
                                                                                    <video width="100%" controls>
                                                                                        <source src="<?php echo $row['video'];?>" >
                                                                                        No Video Found!
                                                                                    </video>
                                                                                </div>
                                                                            </div>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    elseif(isset($row['image']))
                                                                    {
                                                                        ?>
                                                                        <div class="row no-gutter feed-image-container">
                                                                            <div class="col-100">
                                                                                <?php
                                                                                if($postlimit > 5)
                                                                                {
                                                                                    ?>
                                                                                    <img data-src="<?php echo $row['image'];?>" class="mainFeed-img"/>
                                                                                    <?php
                                                                                }
                                                                                else
                                                                                {
                                                                                    ?>
                                                                                    <img src="<?php echo $row['image'];?>" class="mainFeed-img"/>
                                                                                    <?php
                                                                                }
                                                                                ?>
                                                                            </div>
                                                                        </div>
                                                                        <?php
                                                                    }
                                                                    elseif(isset($backupLink) && myIsArray($backupLink))
                                                                    {
                                                                        ?>
                                                                        <div class="link-card-wrapper">
                                                                            <input type="hidden" class="my-link-url" value="<?php echo $backupLink[0][0];?>"/>
                                                                            <div class="liveurl feed-image-container hide">
                                                                                <img src="" class="link-image mainFeed-img lazy lazy-fadeIn" />
                                                                                <div class="details">
                                                                                    <div class="title"></div>
                                                                                    <div class="description"></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                    <p class="final-card-text"><?php echo $truncated_RestaurantName;?></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                                <?php
                                                break;
                                        }
                                    }
                                }
                            }
                            else
                            {
                                echo 'No Feeds Found!';
                            }
                            ?>
                        </div>
                        <?php
                        if(isset($myFeeds) && myIsArray($myFeeds))
                        {
                            ?>
                            <div class="infinite-scroll-preloader">
                                <div class="preloader"></div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>

            </div>
        </div>
        <div id="tab2" class="view view-main tab">
            <!-- Top Navbar-->
            <div class="navbar mycustomNav">
                <div class="navbar-inner">
                    <div class="left">
                        <!--<a href="#" class="link icon-only open-panel"><i class="icon fa fa-bars color-black"></i></a>-->
                        <a href="#" class="link icon-only open-panel ripple main-menu-icon">
                            <!--<i class="fa fa-bars color-black"></i>-->
                            <span class="d-logo"></span>
                            <span class="bottom-bar-line"></span>
                            <!--<br>
                            <i class="fa fa-minus color-black"></i>
                            <i class="fa fa-minus color-black"></i>-->
                        </a>
                    </div>
                    <div class="right">
                        <i class="ic_filter_icon open-popover event-filter-toggler" data-popover=".popover-event-filter"></i>
                    </div>
                    <!--<div class="center sliding">Doolally</div>-->
                </div>
            </div>
            <!-- Pages container, because we use fixed-through navbar and toolbar, it has additional appropriate classes-->
            <div class="pages navbar-through toolbar-through">
                <!-- Page, "data-page" contains page name -->
                <div class="page" data-page="comming-up">
                    <!-- Scrollable page content -->
                    <div class="page-content" id="my-page1">
                        <input type="hidden" id="MojoStatus" value="<?php echo $MojoStatus;?>"/>
                        <div class="content-block">
                            <?php
                                if(isset($weekEvents) && myIsMultiArray($weekEvents))
                                {
                                    ?>
                                    <ul class="hide even-cal-list">
                                        <?php
                                        foreach($weekEvents as $key => $row)
                                        {
                                            ?>
                                                <li data-evenDate="<?php echo $row['eventDate'];?>"
                                                    data-evenNames="<?php echo $row['eventNames'];?>"
                                                    data-evenPlaces="<?php echo $row['eventPlaces'];?>">
                                                </li>
                                            <?php
                                        }
                                        ?>
                                    </ul>
                                    <?php
                                }
                            ?>
                            <div id='calendar-glance'></div>
                            <div class="event-section">
                                <?php
                                if(isset($eventDetails) && myIsMultiArray($eventDetails))
                                {
                                    $postImg = 0;
                                    foreach($eventDetails as $key => $row)
                                    {
                                        $img_collection = array();
                                        ?>
                                        <div class="card demo-card-header-pic <?php
                                            if(isset($row['eventData']['eventPlace']))
                                            {
                                                echo 'eve-'.$row['eventData']['eventPlace'];
                                            }
                                            ?>" data-eveTitle="<?php echo $row['eventData']['eventName'];?>">
                                            <div class="row no-gutter">
                                                <div class="col-100"> <!--more-photos-wrapper-->
                                                    <?php
                                                    if($postImg >=5)
                                                    {
                                                        $img_collection[] = base_url().EVENT_PATH_THUMB.$row['eventData']['filename'];
                                                        ?>
                                                        <a href="<?php echo 'events/EV-'.$row['eventData']['eventId'].'/'.encrypt_data('EV-'.$row['eventData']['eventId']);?>">
                                                            <img src="<?php echo base_url().EVENT_PATH_THUMB.$row['eventData']['filename'];?>" class="mainFeed-img"/>
                                                        </a>
                                                        <?php
                                                    }
                                                    else
                                                    {
                                                        $img_collection[] = base_url().EVENT_PATH_THUMB.$row['eventData']['filename'];
                                                        ?>
                                                        <a href="<?php echo 'events/EV-'.$row['eventData']['eventId'].'/'.encrypt_data('EV-'.$row['eventData']['eventId']);?>">
                                                            <img data-src="<?php echo base_url().EVENT_PATH_THUMB.$row['eventData']['filename'];?>" class="mainFeed-img lazy lazy-fadein"/>
                                                        </a>
                                                        <?php
                                                    }
                                                    $postImg++;
                                                    if(myIsArray($img_collection))
                                                    {
                                                        ?>
                                                        <input type="hidden" class="imgs_collection"
                                                               value="<?php echo implode(',',$img_collection); ?>"/>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <!--<div style="background-image:url()" valign="bottom" class="card-header color-white no-border">Journey To Mountains</div>-->
                                            <div class="card-content">
                                                <div class="card-content-inner">
                                                    <div class="event-info-wrapper">
                                                        <p class="pull-left card-ptag event-date-tag">
                                                            <?php
                                                            $eventName = (strlen($row['eventData']['eventName']) > 25) ? substr($row['eventData']['eventName'], 0, 25) . '..' : $row['eventData']['eventName'];
                                                            echo $eventName;
                                                            ?>
                                                        </p>
                                                        <input type="hidden" data-name="<?php echo $row['eventData']['eventName'];?>" value="<?php echo $row['eventData']['eventShareLink'];?>"/>
                                                        <i class="ic_me_share_icon pull-right event-share-icn event-card-share-btn"></i>
                                                    </div>

                                                    <div class="comment clear">
                                                        <?php echo $row['eventData']['eventDescription'];?>
                                                        <p>
                                                            <i class="ic_me_location_icon main-loc-icon"></i>&nbsp;<?php echo $row['eventData']['locName']; ?>
                                                            &nbsp;&nbsp;<span class="ic_events_icon event-date-main"></span>&nbsp;
                                                            <?php $d = date_create($row['eventData']['eventDate']);
                                                            echo date_format($d,EVENT_DATE_FORMAT); ?>
                                                            &nbsp;&nbsp;<i class="ic_me_rupee_icon main-rupee-icon"></i>
                                                            <?php
                                                            switch($row['eventData']['costType'])
                                                            {
                                                                case "1":
                                                                    echo "Free";
                                                                    break;
                                                                case "2":
                                                                    echo 'Rs '.$row['eventData']['eventPrice'];
                                                                    break;
                                                            }
                                                            ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer event-card-footer">
                                                <!--<span class="event-new-notify">
                                                <?php
                                                /*                                            switch($row['eventData']['costType'])
                                                                                            {
                                                                                                case "1":
                                                                                                    echo "Free";
                                                                                                    break;
                                                                                                case "2":
                                                                                                    echo 'Rs '.$row['eventData']['eventPrice'];
                                                                                                    break;
                                                                                            }
                                                                                        */?>
                                            </span>-->
                                                <!--<a href="#" class="link color-black event-card-share-btn">Share</a>-->
                                                <a href="<?php echo 'events/EV-'.$row['eventData']['eventId'].'/'.encrypt_data('EV-'.$row['eventData']['eventId']);?>" data-ignore-cache="true" class="link color-black event-bookNow">Book&nbsp;Event</a>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                                else
                                {
                                    echo 'No Items Found!';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="tab3" class="view view-menus tab">
            <!-- Top Navbar-->
            <div class="navbar mycustomNav">
                <div class="navbar-inner">
                    <div class="left">
                        <!--<a href="#" class="link icon-only open-panel"><i class="icon fa fa-bars color-black"></i></a>-->
                        <a href="#" class="link icon-only open-panel main-menu-icon">
                            <!--<i class="fa fa-bars color-black"></i>-->
                            <span class="d-logo"></span>
                            <span class="bottom-bar-line"></span>
                            <!--<span class="d-logo"></span>-->
                            <!--<i class="fa fa-minus"></i>
                            <i class="fa fa-minus"></i>-->
                        </a>
                    </div>
                    <!--<div class="center sliding">F & B</div>-->
                    <div class="right">
                        <i class="ic_filter_icon open-popover beer-filter-toggler" data-popover=".popover-filters"></i>
                    </div>
                </div>
            </div>
            <!-- Pages container, because we use fixed-through navbar and toolbar, it has additional appropriate classes-->
            <div class="pages navbar-through toolbar-through">
                <!-- Page, "data-page" contains page name -->
                <div class="page" data-page="menusPage">
                    <!-- Scrollable page content -->
                    <div class="page-content" id="my-page3">
                        <div class="content-block fnb-section">
                            <?php
                                if(isset($fnbItems) && myIsMultiArray($fnbItems))
                                {
                                    $postImg = 0;
                                    foreach($fnbItems as $key => $row)
                                    {
                                        $freecard = true;
                                        $locClass = array();
                                        if($row['itemType'] == ITEM_BEVERAGE && isset($row['taggedLoc']))
                                        {
                                            $freecard = false;
                                            $locClass = explode(',',$row['taggedLoc']);
                                        }
                                        ?>
                                        <div class="card demo-card-header-pic <?php
                                            if($freecard === false)
                                            {
                                                if(myIsArray($locClass))
                                                {
                                                    foreach($locClass as $key)
                                                    {
                                                        $cat = $key;
                                                        echo ' category-'.$key;
                                                    }
                                                }
                                            }
                                        ?>">
                                            <div class="row no-gutter">
                                                <div class="col-100 more-photos-wrapper">
                                                    <?php
                                                    $img_collection = array();
                                                        if($postImg >=5)
                                                        {
                                                            if($row['itemType'] == ITEM_FOOD)
                                                            {
                                                                $img_collection[] = base_url().FOOD_PATH_THUMB.$row['filename'];
                                                                ?>
                                                                <img src="<?php echo base_url().FOOD_PATH_THUMB.$row['filename'];?>" class="mainFeed-img"/>
                                                                <?php
                                                            }
                                                            elseif($row['itemType'] == ITEM_BEVERAGE)
                                                            {
                                                                $img_collection[] = base_url().BEVERAGE_PATH_THUMB.$row['filename'];
                                                                ?>
                                                                <img src="<?php echo base_url().BEVERAGE_PATH_THUMB.$row['filename'];?>" class="mainFeed-img"/>
                                                                <?php
                                                            }
                                                        }
                                                        else
                                                        {
                                                            if($row['itemType'] == ITEM_FOOD)
                                                            {
                                                                $img_collection[] = base_url().FOOD_PATH_THUMB.$row['filename'];
                                                                ?>
                                                                <img data-src="<?php echo base_url().FOOD_PATH_THUMB.$row['filename'];?>" class="mainFeed-img lazy lazy-fadein"/>
                                                                <?php
                                                            }
                                                            elseif($row['itemType'] == ITEM_BEVERAGE)
                                                            {
                                                                $img_collection[] = base_url().BEVERAGE_PATH_THUMB.$row['filename'];
                                                                ?>
                                                                <img data-src="<?php echo base_url().BEVERAGE_PATH_THUMB.$row['filename'];?>" class="mainFeed-img lazy lazy-fadein"/>
                                                                <?php
                                                            }
                                                        }
                                                        $postImg++;
                                                        if(myIsArray($img_collection))
                                                        {
                                                            ?>
                                                                <input type="hidden" class="imgs_collection"
                                                                       value="<?php echo implode(',',$img_collection); ?>"/>
                                                            <?php
                                                        }
                                                    ?>
                                                </div>
                                            </div>
                                            <!--<div style="background-image:url()" valign="bottom" class="card-header color-white no-border">Journey To Mountains</div>-->
                                            <div class="card-content">
                                                <div class="card-content-inner">
                                                    <p class="pull-left card-ptag"><?php echo $row['itemName'];?></p>
                                                    <span class="pull-right">Rs. <?php echo $row['priceFull'];?>
                                                        <?php
                                                            if(isset($row['priceHalf']) && $row['priceHalf'] != '0')
                                                            {
                                                                echo '/'.$row['priceHalf'];
                                                            }
                                                        ?>
                                                    </span>
                                                    <div class="comment more content-block clear">
                                                        <?php echo strip_tags($row['itemDescription'],'<br>');?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                                else
                                {
                                    echo 'No Items Found!';
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Bottom Toolbar-->
        <!--</div>-->
        <div class="toolbar tabbar tabbar-labels myMainBottomBar">
            <div class="toolbar-inner">
                <a href="#tab1" class="tab-link">
                    <i class="icon fa fa-hashtag">
                        <span class="badge feed-notifier hide"></span>
                    </i>
                    <span class="tabbar-label">/doolally</span>
                </a>
                <a href="#tab2" class="tab-link">
                    <!--<i class="fa fa-hashtag"></i>-->
                    <!--<i class="fa fa-calendar"></i>-->
                    <span class="ic_events_icon"></span>
                    <span class="tabbar-label">/events</span>
                </a>
                <a href="#tab3" class="tab-link">
                    <!--<i class="fa fa-cutlery"></i>-->
                    <span class="ic_fnb_icon"></span>
                    <span class="tabbar-label">/fnb</span>
                </a>
            </div>
        </div>
    </div>
    <!-- Colored FAB button with ripple -->
<!--    <button data-popover=".popover-links"
            class="open-popover mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored my-fab-btn hide">
        <i class="popover-toggle-on fa fa-filter"></i>
        <i class="popover-toggle-off fa fa-times"></i>
    </button>-->
    <button class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored event-fab-btn hide">
        <i class="ic_add"></i>
    </button>
    <div class="popover popover-links">
        <div class="popover-angle"></div>
        <div class="popover-inner">
            <p>Filter Posts</p>
            <div class="list-block inset">
                <ul>
                    <li>
                        <div class="item-inner">
                            <div class="item-title">Facebook</div>
                            <div class="item-after">
                                <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect my-fb-label" for="fb-checked">
                                    <input type="checkbox" name="social-filter" value="1" id="fb-checked" class="mdl-checkbox__input">
                                    <span class="mdl-checkbox__label"></span>
                                </label>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="item-inner">
                            <div class="item-title">Twitter</div>
                            <div class="item-after">
                                <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect my-tw-label" for="tw-checked">
                                    <input type="checkbox" name="social-filter" value="2" id="tw-checked" class="mdl-checkbox__input">
                                    <span class="mdl-checkbox__label"></span>
                                </label>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="item-inner">
                            <div class="item-title">Instagram</div>
                            <div class="item-after">
                                <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect my-insta-label" for="insta-checked">
                                    <input type="checkbox" name="social-filter" value="3" id="insta-checked" class="mdl-checkbox__input">
                                    <span class="mdl-checkbox__label"></span>
                                </label>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="popover popover-filters">
        <div class="popover-angle"></div>
        <div class="popover-inner">
            <p>What's on tap in..
            <i class="fa fa-times pull-right clear-beer-filter hide"></i></p>
            <div class="list-block inset">
                <ul>
                    <?php
                        if(isset($mainLocs) && myIsMultiArray($mainLocs) && $mainLocs['status'] == true)
                        {
                            foreach($mainLocs as $key => $row)
                            {
                                if(isset($row['id']))
                                {
                                    ?>
                                    <li>
                                        <div class="item-inner">
                                            <div class="item-title"><?php echo $row['locName'];?></div>
                                            <div class="item-after">
                                                <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="option-<?php echo $row['id'];?>">
                                                    <input type="radio" id="option-<?php echo $row['id'];?>" class="mdl-radio__button" name="beer-locations" value="<?php echo $row['id'];?>">
                                                    <span class="mdl-radio__label"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </li>
                                    <?php
                                }
                            }
                        }
                    ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="popover popover-event-filter">
        <div class="popover-angle"></div>
        <div class="popover-inner">
            <p>Show events in..
                <i class="fa fa-times pull-right clear-event-filter hide"></i></p>
            <div class="list-block inset">
                <ul>
                    <?php
                    if(isset($mainLocs) && myIsMultiArray($mainLocs) && $mainLocs['status'] == true)
                    {
                        foreach($mainLocs as $key => $row)
                        {
                            if(isset($row['id']))
                            {
                                ?>
                                <li>
                                    <div class="item-inner">
                                        <div class="item-title"><?php echo $row['locName'];?></div>
                                        <div class="item-after">
                                            <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="even-<?php echo $row['id'];?>">
                                                <input type="radio" id="even-<?php echo $row['id'];?>" class="mdl-radio__button" name="event-locations" value="<?php echo $row['id'];?>">
                                                <span class="mdl-radio__label"></span>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                                <?php
                            }
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="popover popover-share">
        <!-- Popover's angle arrow -->
        <!--<div class="popover-angle"></div>-->

        <!-- Popover content -->
        <div class="popover-inner my-social-share">
            <p class="color-black" style="margin-bottom:0"></p>
            <div id="main-share">
            </div>
        </div>
    </div>
    <span class="custom-scroll-up">
        ↑ Scroll Up
    </span>
    <dialog class="mdl-dialog" id="alertDialog">
        <h4 class="mdl-dialog__title"></h4>
        <div class="mdl-dialog__content">
            <p class="msg">
            </p>
        </div>
        <div class="mdl-dialog__actions">
            <button type="button" class="mdl-button mdl-js-button mdl-js-ripple-effect close">Close</button>
        </div>
    </dialog>
    <dialog class="mdl-dialog" id="confirmDialog">
    <h4 class="mdl-dialog__title"></h4>
    <div class="mdl-dialog__content">
        <p class="msg">
        </p>
    </div>
    <div class="mdl-dialog__actions">
        <input type="hidden" id="imp-stuff" value=""/>
        <button type="button" class="mdl-button mdl-js-button mdl-js-ripple-effect close">Close</button>
        <button type="button" class="mdl-button mdl-js-button mdl-js-ripple-effect confirm-option"></button>
    </div>
</dialog>
</body>
<?php echo $mobileJs; ?>
<?php echo $iosJs; ?>

<script>
    var mainFeeds = '';
    //previous data
    <?php
        if(isset($myFeeds) && myIsArray($myFeeds))
        {
            switch($myFeeds[0]['socialType'])
            {
                case 'i':
                    ?>
                    mainFeeds = '<?php echo $myFeeds[0]['id'];?>';
                    <?php
                    break;
                case 'f':
                    ?>
                    mainFeeds = '<?php echo $myFeeds[0]['id'];?>';
                    <?php
                    break;
                case 't':
                    ?>
                    mainFeeds = '<?php echo $myFeeds[0]['id_str'];?>';
                    <?php
                    break;
            }
        }
    ?>

    function highlight(regex,text)
    {
        var m = regex.exec(text);
        if(!m)
            return text;
        for(var i=0;i<m.length;i++)
        {
           descrip[i] = '<label>'+m[i]+'</label>';
        }
        if( typeof descrip !== 'undefined')
        {
            return text.replace(descrip,m);
        }
        else
        {
            return text;
        }
    }
    function fetchNewFeeds()
    {
        $.ajax({
            type:"GET",
            dataType:"json",
            url: '<?php echo  base_url();?>mobile/main/returnAllFeeds/json',
            success: function(data)
            {
                if(mainFeeds == '' && typeof mainFeeds == 'undefined')
                {
                    return false;
                }
                var newFeeds = [];
                for(var i=0;i<data.length;i++)
                {
                    var currentId = '';
                    switch(data[i]['socialType'])
                    {
                        case 't':
                            currentId = data[i]['id_str'];
                            break;
                        case 'i':
                            currentId = data[i]['id'];
                            break;
                        case 'f':
                            currentId = data[i]['id'];
                            break;
                    }
                    if(currentId == mainFeeds)
                    {
                        break;
                    }
                    else
                    {
                        newFeeds[i] = data[i];
                    }
                }
                if(newFeeds.length > 0)
                {
                    addCards(newFeeds);
                }
            },
            error: function(xhr) {
                console.log('error');
                /*
                var err = eval("(" + xhr.responseText + ")");
                alert(err.Message);*/
            }
        });
    }

    String.prototype.capitalize = function() {
        return this.charAt(0).toUpperCase() + this.slice(1);
    }
    function addCards(data)
    {
        var ifUpdate = false;
        var totalNew = 0;
        var oldHeight = $('.custom-accordion').height();
        for(var i=data.length-1;i>=0;i--)
        {
            switch(data[i]['socialType'])
            {
                case 'i':
                    if(ifUpdate == false)
                    {
                        ifUpdate = true;
                        mainFeeds = data[0]['id'];
                    }
                    var urlRegex =/(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
                    var backupLink = urlRegex.exec(data[i]['unformatted_message']);
                    var truncated_RestaurantName ='';
                    data[i]['unformatted_message'] = data[i]['unformatted_message'].replace(urlRegex,'');

                    data[i]['unformatted_message'] = data[i]['unformatted_message'].replace(/(#[a-z\d-]+)/ig,"<label>$1</label>");
                    data[i]['unformatted_message'] = data[i]['unformatted_message'].replace(/(@[a-z\d-]+)/ig,"<label>$1</label>");
                    if(data[i]['unformatted_message'].length > 140)
                    {
                        truncated_RestaurantName = data[i]['unformatted_message'].substr(0,140)+'..';
                    }
                    else
                    {
                        truncated_RestaurantName = data[i]['unformatted_message'];
                    }
                    var bigCardHtml = '';
                    bigCardHtml += '<a href="'+data[i]['full_url']+'" target="_blank" class="external instagram-wrapper new-post-wrapper hide">';
                    bigCardHtml += '<div class="my-card-items"><div class="card demo-card-header-pic">';
                    bigCardHtml += '<div class="card-content"><div class="card-content-inner">';
                    bigCardHtml += '<div class="list-block media-list"><ul><li><div class="item-content">';
                    bigCardHtml += '<div class="item-media"><img class="myAvtar-list lazy" data-src="'+data[i]['poster_image']+'" width="44"/></div>';
                    bigCardHtml += '<div class="item-inner"><div class="item-title-row">';
                    bigCardHtml += '<div class="item-title">'+data[i]['poster_name'].capitalize()+'</div>';
                    bigCardHtml += '<i class="fa fa-instagram social-icon-gap"></i></div>';
                    if(data[i].hasOwnProperty('source'))
                    {
                        if(data[i]['source']['term_type'] == 'hashtag')
                        {
                            bigCardHtml += '<div class="item-subtitle">#'+data[i]['source']['term'];
                            bigCardHtml += '<time class="timeago time-stamp" datetime="'+data[i]['created_at']+'"></time></div>';
                            //bigCardHtml += '<span class="time-stamp"></span></div>'
                        }
                        else
                        {
                            bigCardHtml += '<div class="item-subtitle">@'+data[i]['source']['term'];
                            bigCardHtml += '<time class="timeago time-stamp" datetime="'+data[i]['created_at']+'"></time></div>';
                        }
                    }
                    bigCardHtml += '</div></div></li></ul></div>';
                    if(data[i].hasOwnProperty('video'))
                    {
                        if(data[i]['video'].indexOf('youtube') != -1 || data[i]['video'].indexOf('youtu.be') != -1)
                        {
                            bigCardHtml += '<div class="row no-gutter feed-image-container">';
                            bigCardHtml += '<div class="col-100">';
                            bigCardHtml += '<iframe width="100%" src="'+data[i]['video']+'" frameborder="0" allowfullscreen>';
                            bigCardHtml += '</iframe></div></div>';
                        }
                        else
                        {
                            bigCardHtml += '<div class="row no-gutter feed-image-container">';
                            bigCardHtml += '<div class="col-100">';
                            bigCardHtml += '<video width="100%" controls>';
                            bigCardHtml += '<source src="'+data[i]['video']+'">No Video found!';
                            bigCardHtml += '</video></div></div>';
                        }
                    }
                    else if(data[i].hasOwnProperty('image'))
                    {
                        bigCardHtml += '<div class="row no-gutter feed-image-container">';
                        bigCardHtml += '<div class="col-100">';
                        bigCardHtml += '<img data-src="'+data[i]['image']+'" class="mainFeed-img lazy lazy-fadein"/>';
                        bigCardHtml += '</div></div>';
                    }
                    else if(typeof backupLink !== 'undefined')
                    {
                        bigCardHtml += '<div class="link-card-wrapper">';
                        bigCardHtml += '<input type="hidden" class="my-link-url" value="'+backupLink[0]+'"/>';
                        bigCardHtml += '<div class="liveurl feed-image-container hide">';
                        bigCardHtml += '<img src="" class="link-image mainFeed-img lazy lazy-fadein"/>';
                        bigCardHtml += '<div class="details"><div class="title"></div><div class="description"></div>';
                        bigCardHtml += '</div></div></div>';
                    }

                    bigCardHtml += '<p class="final-card-text">'+truncated_RestaurantName+'</p>';
                    bigCardHtml += '</div></div></div></a>';

                    $('.custom-accordion').prepend(bigCardHtml);

                    totalNew++;
                    break;
                case 'f':
                    if(ifUpdate == false)
                    {
                        ifUpdate = true;
                        mainFeeds = data[0]['id'];
                    }
                    var urlRegex =/(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
                    var backupLink = urlRegex.exec(data[i]['message']);
                    var truncated_RestaurantName ='';
                    data[i]['message'] = data[i]['message'].replace(urlRegex,'');

                    data[i]['message'] = data[i]['message'].replace(/(#[a-z\d-]+)/ig,"<label>$1</label>");
                    data[i]['message'] = data[i]['message'].replace(/(@[a-z\d-]+)/ig,"<label>$1</label>");
                    if(data[i]['message'].length > 140)
                    {
                        truncated_RestaurantName = data[i]['message'].substr(0,140)+'..';
                    }
                    else
                    {
                        truncated_RestaurantName = data[i]['message'];
                    }
                    var bigCardHtml = '';
                    bigCardHtml += '<a href="'+data[i]['permalink_url']+'" target="_blank" class="external facebook-wrapper new-post-wrapper hide">';
                    bigCardHtml += '<div class="my-card-items"><div class="card demo-card-header-pic">';
                    bigCardHtml += '<div class="card-content"><div class="card-content-inner">';
                    bigCardHtml += '<div class="list-block media-list"><ul><li><div class="item-content">';
                    bigCardHtml += '<div class="item-media"><img class="myAvtar-list lazy" data-src="https://graph.facebook.com/v2.7/'+data[i]['from']['id']+'/picture" width="44"/></div>';
                    bigCardHtml += '<div class="item-inner"><div class="item-title-row">';
                    bigCardHtml += '<div class="item-title">'+data[i]['from']['name'].capitalize()+'</div>';
                    bigCardHtml += '<i class="fa fa-facebook social-icon-gap"></i></div>';
                    bigCardHtml += '<div class="item-subtitle">';
                    bigCardHtml += '<time class="timeago time-stamp" datetime="'+data[i]['created_at']+'"></time></div>';
                    /*if(data[i].hasOwnProperty('source'))
                    {
                        if(data[i]['source']['term_type'] == 'hashtag')
                        {
                            bigCardHtml += '<div class="item-subtitle">#'+data[i]['source']['term']+'</div>';
                        }
                        else
                        {
                            bigCardHtml += '<div class="item-subtitle">@'+data[i]['source']['term']+'</div>';
                        }
                    }*/
                    bigCardHtml += '</div></div></li></ul></div>';
                    if(data[i].hasOwnProperty('source'))
                    {
                        if(data[i]['source'].indexOf('youtube') != -1 || data[i]['source'].indexOf('youtu.be') != -1)
                        {
                            bigCardHtml += '<div class="row no-gutter feed-image-container">';
                            bigCardHtml += '<div class="col-100">';
                            bigCardHtml += '<iframe width="100%" src="'+data[i]['source']+'" frameborder="0" allowfullscreen>';
                            bigCardHtml += '</iframe></div></div>';
                        }
                        else
                        {
                            bigCardHtml += '<div class="row no-gutter feed-image-container">';
                            bigCardHtml += '<div class="col-100">';
                            bigCardHtml += '<video width="100%" controls>';
                            bigCardHtml += '<source src="'+data[i]['source']+'">No Video found!';
                            bigCardHtml += '</video></div></div>';
                        }
                    }
                    else if(data[i].hasOwnProperty('picture'))
                    {
                        bigCardHtml += '<div class="row no-gutter feed-image-container">';
                        bigCardHtml += '<div class="col-100">';
                        bigCardHtml += '<img data-src="'+data[i]['picture']+'" class="mainFeed-img lazy lazy-fadein"/>';
                        bigCardHtml += '</div></div>';
                    }
                    else if(typeof backupLink !== 'undefined')
                    {
                        bigCardHtml += '<div class="link-card-wrapper">';
                        bigCardHtml += '<input type="hidden" class="my-link-url" value="'+backupLink[0]+'"/>';
                        bigCardHtml += '<div class="liveurl feed-image-container hide">';
                        bigCardHtml += '<img src="" class="link-image mainFeed-img lazy lazy-fadein"/>';
                        bigCardHtml += '<div class="details"><div class="title"></div><div class="description"></div>';
                        bigCardHtml += '</div></div></div>';
                    }

                    bigCardHtml += '<p class="final-card-text">'+truncated_RestaurantName+'</p>';
                    bigCardHtml += '</div></div></div></a>';
                    /*var oldHeight = $('.custom-accordion').height();
                    $('.custom-accordion').prepend(bigCardHtml);
                    var total = currentPos+($('.custom-accordion').height()- oldHeight);
                    $('.page-content').scrollTop(total);*/
                    $('.custom-accordion').prepend(bigCardHtml);
                    totalNew++;
                    break;
                case 't':
                    if(ifUpdate == false)
                    {
                        ifUpdate = true;
                        mainFeeds = data[0]['id_str'];
                    }
                    var urlRegex =/(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
                    //var httpPattern = "!(http|ftp|scp)(s)?:\/\/[a-zA-Z0-9.?%=&_/]+!";
                    var truncated_RestaurantName ='';
                    data[i]['text'] = data[i]['text'].replace(urlRegex,'');

                    data[i]['text'] = data[i]['text'].replace(/(#[a-z\d-]+)/ig,"<label>$1</label>");
                    data[i]['text'] = data[i]['text'].replace(/(@[a-z\d-]+)/ig,"<label>$1</label>");
                    if(data[i]['text'].length > 140)
                    {
                        truncated_RestaurantName = data[i]['text'].substr(0,140)+'..';
                    }
                    else
                    {
                        truncated_RestaurantName = data[i]['text'];
                    }
                    var bigCardHtml = '';
                    bigCardHtml += '<a href="https://twitter.com/'+data[i]['user']['screen_name']+'/status/'+data[i]['id_str']+'" target="_blank" class="external twitter-wrapper new-post-wrapper hide">';
                    bigCardHtml += '<div class="my-card-items"><div class="card demo-card-header-pic">';
                    bigCardHtml += '<div class="card-content"><div class="card-content-inner">';
                    bigCardHtml += '<div class="list-block media-list"><ul><li><div class="item-content">';
                    bigCardHtml += '<div class="item-media"><img class="myAvtar-list lazy" data-src="'+data[i]['user']['profile_image_url']+'" width="44"/></div>';
                    bigCardHtml += '<div class="item-inner"><div class="item-title-row">';
                    bigCardHtml += '<div class="item-title">'+data[i]['user']['name'].capitalize()+'</div>';
                    bigCardHtml += '<i class="fa fa-twitter social-icon-gap"></i></div>';
                    bigCardHtml += '<div class="item-subtitle">@'+data[i]['user']['screen_name'];
                    bigCardHtml += '<time class="timeago time-stamp" datetime="'+data[i]['created_at']+'"></time></div>';

                    bigCardHtml += '</div></div></li></ul></div>';

                    if(data[i].hasOwnProperty('extended_entities'))
                    {
                        bigCardHtml += '<div class="row no-gutter feed-image-container">';
                        var imageLimit = 0;
                        for(var j=0;j<data[i]['extended_entities']['media'].length;j++)
                        {
                            if(imageLimit >= 1)
                            {
                                return false;
                            }
                            imageLimit++;
                            if(data[i]['extended_entities']['media'][j].hasOwnProperty('video_info') && data[i]['extended_entities']['media'][j]['video_info']['variants'] != null)
                            {
                                var videoUrl = '';
                                var videoType = '';
                                for(var k=0;k<data[i]['extended_entities']['media'][j]['video_info']['variants'].length;k++)
                                {
                                    if(data[i]['extended_entities']['media'][j]['video_info']['variants'][k].hasOwnProperty('bitrate'))
                                    {
                                        videoUrl = data[i]['extended_entities']['media'][j]['video_info']['variants'][k]['url'];
                                        videoType = data[i]['extended_entities']['media'][j]['video_info']['variants'][k]['content_type'];
                                    }
                                }
                                if(videoUrl.indexOf('youtube') != -1 || videoUrl.indexOf('youtu.be') != -1)
                                {
                                    bigCardHtml += '<div class="col-100">';
                                    bigCardHtml += '<iframe width="100%" src="'+videoUrl+'" frameborder="0" allowfullscreen>';
                                    bigCardHtml += '</iframe></div></div>';
                                }
                                else
                                {
                                    bigCardHtml += '<div class="col-100">';
                                    bigCardHtml += '<video width="100%" controls>';
                                    bigCardHtml += '<source src="'+videoUrl+'" type="'+videoType+'">No Video found!';
                                    bigCardHtml += '</video></div></div>';
                                }
                            }
                            else
                            {
                                bigCardHtml += '<div class="col-100">';
                                bigCardHtml += '<img data-src="'+data[i]['extended_entities']['media'][j]['media_url']+'" class="mainFeed-img lazy lazy-fadein"/>';
                                bigCardHtml += '</div></div>';
                            }
                        }
                    }
                    else if(data[i]['entities']['urls'] != null && data[i]['entities']['urls'].length > 0)
                    {
                        bigCardHtml += '<div class="link-card-wrapper">';
                        bigCardHtml += '<input type="hidden" class="my-link-url" value="'+data[i]['entities']['urls'][0]['expanded_url']+'"/>';
                        bigCardHtml += '<div class="liveurl feed-image-container hide">';
                        bigCardHtml += '<img src="" class="link-image mainFeed-img lazy lazy-fadein"/>';
                        bigCardHtml += '<div class="details"><div class="title"></div><div class="description"></div>';
                        bigCardHtml += '</div></div></div>';
                    }

                    bigCardHtml += '<p class="final-card-text">'+truncated_RestaurantName+'</p>';
                    bigCardHtml += '</div></div></div></a>';
                    $('.custom-accordion').prepend(bigCardHtml);
                    /*var oldHeight = $('.custom-accordion').height();
                    $('.custom-accordion').prepend(bigCardHtml);
                    var total = currentPos+($('.custom-accordion').height()- oldHeight);
                    $('.page-content').scrollTop(total);*/
                    totalNew++;
                    break;
            }
        }

        $('.new-post-wrapper').removeClass('hide').removeClass('new-post-wrapper');
        var total = currentPos+($('.custom-accordion').height()- oldHeight);
        $('.page-content').animate({
            scrollTop: total
        },100);

        $('.feed-notifier').removeClass('hide');
        if(currentPos <=20)
        {
            if(!$('.feed-notifier').hasClass('hide'))
            {
                $('.feed-notifier').addClass('hide');
            }
        }
        $("time.timeago").timeago();
    }
    $$(window).on('load', function(e){
        setInterval(fetchNewFeeds,5*60*1000);
    });

    var ptrContent = $$('.pull-to-refresh-content');
    ptrContent.on('refresh', function (e) {
        fetchNewFeeds();
        // Emulate 2s loading
        setTimeout(function () {
            // When loading done, we need to reset it
            myApp.pullToRefreshDone();
        }, 2000);
    });
</script>

</html>