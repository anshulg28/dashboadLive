<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Dashboard :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body class="dashboard">
    <?php echo $headerView; ?>
    <!-- No header, and the drawer stays open on larger screens (fixed drawer). -->
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer">
        <div class="mdl-layout__drawer">
            <?php
                if(isset($secLocs))
                {
                    $tempLocs = explode(',',$secLocs);
                    if(count($tempLocs) > 1)
                    {
                        ?>
                        <a href="<?php echo base_url().'dashboard/getCommLocation/'.base64_encode($secLocs);?>" id="change-sec-loc">(Change Location)</a>
                        <?php
                    }
                }
            ?>
            <span class="mdl-layout-title">Dashboard</span>
            <ul class="nav nav-pills nav-stacked">
                <li class="active"><a data-toggle="pill" class="my-noBorderRadius" href="#mugclub">Mug Club</a></li>
                <li><a class="my-noBorderRadius" data-toggle="pill" href="#instamojo">Instamojo</a></li>
                <?php
                    if(myInArray('dashboard_feedback',$userModules))
                    {
                        ?>
                        <li><a class="my-noBorderRadius" data-toggle="pill" href="#feedback">Feedback</a></li>
                        <?php
                    }

                    if(myInArray('dashboard_fnb',$userModules) || (isset($this->commSecLoc) && $this->commSecLoc != 5))
                    {
                        ?>
                        <li><a class="my-noBorderRadius" data-toggle="pill" href="#fnbpanel">FnB Data</a></li>
                        <?php
                    }

                    if(myInArray('dashboard_events',$userModules) || (isset($this->commSecLoc) && $this->commSecLoc != 5))
                    {
                        ?>
                        <li><a class="my-noBorderRadius" data-toggle="pill" href="#eventpanel">Events</a></li>
                        <?php                    }

                    if(myInArray('dashboard_beerolympics',$userModules) || (isset($this->commSecLoc) && $this->commSecLoc != 5))
                    {
                        ?>
                        <li><a class="my-noBorderRadius" data-toggle="pill" href="#beerpanel">Beer Olympics</a></li>
                        <?php
                    }
                ?>
            </ul>
            <!--<div class="mdl-layout__tab-bar mdl-js-ripple-effect">
                <a href="#mugclub" class="mdl-layout__tab">Mug Club</a><br>
                <a href="#instamojo" class="mdl-layout__tab is-active">Instamojo</a>
                <a class="mdl-navigation__link" href="#">Mug Club</a>
            </div>-->
        </div>
        <main class="mdl-layout__content tab-content">
            <section class="tab-pane fade in active" id="mugclub">
                <div class="page-content">
                    <div class="mdl-grid">
                        <div class="mdl-cell mdl-cell--1-col"></div>
                        <div class="mdl-cell mdl-cell--10-col text-center">
                            <form action="<?php echo base_url();?>dashboard/custom" method="post" id="customDateForm">
                                <ul class="list-inline">
                                    <li>
                                        <label for="location">Location</label>
                                        <?php
                                        if($this->userType == ADMIN_USER || $this->userType == ROOT_USER)
                                        {
                                            ?>
                                            <select id="location" onchange="refreshBars(this)" class="form-control">
                                                <option value="0">Overall</option>
                                                <?php
                                                if(isset($locations))
                                                {
                                                    foreach($locations as $key => $row)
                                                    {
                                                        if(isset($row['id']))
                                                        {
                                                            ?>
                                                            <option value="<?php echo $row['id'];?>"><?php echo $row['locName'];?></option>
                                                            <?php
                                                        }
                                                    }
                                                }
                                                ?>
                                            </select>
                                            <?php
                                        }
                                        elseif($this->userType == EXECUTIVE_USER)
                                        {
                                            if(isset($userInfo))
                                            {
                                                ?>
                                                <select id="location" onchange="refreshBars(this)" class="form-control">
                                                    <?php
                                                    foreach($userInfo as $key => $row)
                                                    {
                                                        ?>
                                                        <option value="<?php echo $row['locData'][0]['id'];?>">
                                                            <?php echo $row['locData'][0]['locName'];?>
                                                        </option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                                <?php
                                            }
                                            ?>

                                            <?php
                                        }
                                        ?>

                                    </li>
                                    <li>
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label custom-filter">
                                            <input class="mdl-textfield__input" type="text" name="startDate" id="startDate" placeholder="">
                                            <label class="mdl-textfield__label" for="startDate">Start Date</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label custom-filter">
                                            <input class="mdl-textfield__input" type="text" name="endDate" id="endDate" placeholder="">
                                            <label class="mdl-textfield__label" for="endDate">End Date</label>
                                        </div>
                                    </li>
                                    <li>
                                        <button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
                                            Apply
                                        </button>
                                    </li>
                                </ul>
                            </form>
                        </div>
                        <div class="mdl-cell mdl-cell--1-col">

                        </div>
                    </div>
                    <div class="col-sm-12 col-xs-12">
                        <div class="mdl-color--white mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-grid">
                            <div id="totalCheckins-container" class="barContainers">
                                <h6 class="text-primary text-center my-marginTopBottom">Total Check-Ins</h6>
                            </div>
                            <div id="avgCheckins-container" class="barContainers">
                                <h6 class="text-primary text-center my-marginTopBottom">Avg Check-Ins</h6>
                            </div>
                            <div id="regulars-container" class="barContainers">
                                <h6 class="text-primary text-center my-marginTopBottom">Regulars</h6>
                            </div>
                            <div id="irregulars-container" class="barContainers">
                                <h6 class="text-primary text-center my-marginTopBottom">Irregulars</h6>
                            </div>
                            <div id="lapsers-container" class="barContainers">
                                <h6 class="text-primary text-center my-marginTopBottom">Lapsers</h6>
                            </div>
                        </div>
                    </div>
                    <div class="mdl-color--white mdl-shadow--2dp">
                        <div class="col-sm-12 col-xs-12">
                            <ul class="list-inline">
                                <li>
                                    <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="avg-radio">
                                        <input type="radio" id="avg-radio" class="mdl-radio__button" name="dashboardStats" value="1" checked>
                                        <span class="mdl-radio__label">Avg Check-Ins</span>
                                    </label>
                                </li>
                                <li>
                                    <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="regular-radio">
                                        <input type="radio" id="regular-radio" class="mdl-radio__button" name="dashboardStats" value="2">
                                        <span class="mdl-radio__label">Regulars</span>
                                    </label>
                                </li>
                                <li>
                                    <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="irregulars-radio">
                                        <input type="radio" id="irregulars-radio" class="mdl-radio__button" name="dashboardStats" value="3">
                                        <span class="mdl-radio__label">Irregulars</span>
                                    </label>
                                </li>
                                <li>
                                    <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="lapsers-radio">
                                        <input type="radio" id="lapsers-radio" class="mdl-radio__button" name="dashboardStats" value="4">
                                        <span class="mdl-radio__label">Lapsers</span>
                                    </label>
                                </li>
                            </ul>
                            <canvas id="avgChecks-canvas" class="mygraphs"></canvas>
                            <canvas id="regulars-canvas" class="mygraphs"></canvas>
                            <canvas id="irregulars-canvas" class="mygraphs"></canvas>
                            <canvas id="lapsers-canvas" class="mygraphs"></canvas>
                        </div>
                        <!--<div class="col-sm-4 col-xs-12">
                            <table class="mdl-data-table mdl-js-data-table">
                                <thead>
                                <tr>
                                    <th class="mdl-data-table__cell--non-numeric">Legend</th>
                                    <th class="mdl-data-table__cell--non-numeric overall-th">OverAll</th>
                                    <th class="mdl-data-table__cell--non-numeric andheri-th">Andheri</th>
                                    <th class="mdl-data-table__cell--non-numeric bandra-th">Bandra</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
/*                                if(isset($avgChecks))
                                {
                                    */?>
                                    <tr>
                                        <td>Avg Check-ins</td>
                                        <td class="overall-td">
                                            <?php
/*                                            $allStores = ((int)$avgChecks['checkInList']['overall']/$totalMugs['overall']);
                                            echo round($allStores,2);
                                            */?>
                                        </td>
                                        <td class="andheri-td">
                                            <?php
/*                                            $andheriStore = ((int)$avgChecks['checkInList']['andheri']/$totalMugs['andheri']);
                                            echo round($andheriStore,2);
                                            */?>
                                        </td>
                                        <td class="bandra-td">
                                            <?php
/*                                            $bandraStore = ((int)$avgChecks['checkInList']['bandra']/$totalMugs['bandra']);
                                            echo round($bandraStore,2);
                                            */?>
                                        </td>
                                    </tr>
                                    <?php
/*                                }
                                if(isset($Regulars))
                                {
                                    */?>
                                    <tr>
                                        <td>Regulars</td>
                                        <td class="overall-td">
                                            <?php
/*                                            $allStores = ((int)$Regulars['regularCheckins']['overall']/$totalMugs['overall'])*100;
                                            echo round($allStores,1);
                                            */?>
                                        </td>
                                        <td class="andheri-td">
                                            <?php
/*                                            $andheriStore = ((int)$Regulars['regularCheckins']['andheri']/$totalMugs['andheri'])*100;
                                            echo round($andheriStore,1);
                                            */?>
                                        </td>
                                        <td class="bandra-td">
                                            <?php
/*                                            $bandraStore = ((int)$Regulars['regularCheckins']['bandra']/$totalMugs['bandra'])*100;
                                            echo round($bandraStore,1);
                                            */?>
                                        </td>
                                    </tr>
                                    <?php
/*                                }
                                if(isset($Irregulars))
                                {
                                    */?>
                                    <tr>
                                        <td>IrRegulars</td>
                                        <td class="overall-td">
                                            <?php
/*                                            $allStores = ((int)$Irregulars['irregularCheckins']['overall']/$totalMugs['overall'])*100;
                                            echo round($allStores,1);
                                            */?>
                                        </td>
                                        <td class="andheri-td">
                                            <?php
/*                                            $andheriStore = ((int)$Irregulars['irregularCheckins']['andheri']/$totalMugs['andheri'])*100;
                                            echo round($andheriStore,1);
                                            */?>
                                        </td>
                                        <td class="bandra-td">
                                            <?php
/*                                            $bandraStore = ((int)$Irregulars['irregularCheckins']['bandra']/$totalMugs['bandra'])*100;
                                            echo round($bandraStore,1);
                                            */?>
                                        </td>
                                    </tr>
                                    <?php
/*                                }
                                if(isset($lapsers))
                                {
                                    */?>
                                    <tr>
                                        <td>Lapsers</td>
                                        <td class="overall-td">
                                            <?php
/*                                            $allStores = ((int)$lapsers['lapsers']['overall']/$totalMugs['overall'])*100;
                                            echo round($allStores,1);
                                            */?>
                                        </td>
                                        <td class="andheri-td">
                                            <?php
/*                                            $andheriStore = ((int)$lapsers['lapsers']['andheri']/$totalMugs['andheri'])*100;
                                            echo round($andheriStore,1);
                                            */?>
                                        </td>
                                        <td class="bandra-td">
                                            <?php
/*                                            $bandraStore = ((int)$lapsers['lapsers']['bandra']/$totalMugs['bandra'])*100;
                                            echo round($bandraStore,1);
                                            */?>
                                        </td>
                                    </tr>
                                    <?php
/*                                }
                                */?>
                                </tbody>
                            </table>
                        </div>-->
                    </div>
                </div>
            </section>
            <section class="tab-pane fade" id="instamojo">
                <div class="mdl-grid">
                    <?php
                        if(isset($instamojo))
                        {
                            if($instamojo['status'] === true)
                            {
                                foreach($instamojo['instaRecords'] as $key => $row)
                                {
                                    ?>
                                    <div class="mdl-cell mdl-cell--4-col my-instaCard" data-id="<?php echo $row['id'];?>">
                                        <div class="demo-card-square mdl-card mdl-shadow--2dp">
                                            <div class="mdl-card__title mdl-card--expand">
                                                <h2 class="mdl-card__title-text">
                                                    <i class="fa fa-beer fa-2x"></i>
                                                    <span class="mugTitle">Mug #<?php echo $row['mugId'];?></span>
                                                </h2>
                                            </div>
                                            <div class="mdl-card__supporting-text">
                                                <h4 class="my-NoMargin"><?php echo $row['buyerName'];?></h4>
                                                <?php echo $row['buyerEmail'];?><br>
                                                Rs. <?php echo $row['price'];?>
                                            </div>
                                            <div class="mdl-card__actions mdl-card--border">
                                                <a class="confirm-btn mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect my-noUnderline"
                                                onclick="changeCurrent(this)" data-id="<?php echo $row['id'];?>"
                                                data-paymentId="<?php echo $row['paymentId'];?>"
                                                data-email="<?php echo $row['buyerEmail'];?>"
                                                data-mugId="<?php echo $row['mugId'];?>">
                                                    Confirm
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            else
                            {
                                ?>
                                    <div class="mdl-cell mdl-cell--12-col">
                                        No Purchases Found!
                                    </div>
                                <?php
                            }
                        }

                    if(isset($instamojoMugs))
                    {
                        if($instamojoMugs['status'] === true)
                        {
                            foreach($instamojoMugs['instaRecords'] as $key => $row)
                            {
                                ?>
                                <div class="mdl-cell mdl-cell--4-col my-instaMugCard" data-id="<?php echo $row['id'];?>">
                                    <div class="demo-card-square mdl-card mdl-shadow--2dp">
                                        <div class="mdl-card__title mdl-card--expand" style="background-color:#A2C760;">
                                            <h2 class="mdl-card__title-text">
                                                <i class="fa fa-beer fa-2x"></i>
                                                <span class="mugTitle">New Mug #<?php echo $row['mugId'];?></span>
                                            </h2>
                                        </div>
                                        <div class="mdl-card__supporting-text">
                                            <h4 class="my-NoMargin"><?php echo $row['firstName'].' '.$row['lastName'];?></h4>
                                            <?php echo $row['emailId'];?><br>
                                            HomeBase: <?php echo $row['locName'];?>
                                        </div>
                                        <div class="mdl-card__actions mdl-card--border">
                                            <a class="mug_confirm-btn mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect my-noUnderline"
                                               onclick="changeMugCurrent(this)" data-id="<?php echo $row['id'];?>">
                                                Confirm
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                    }
                    ?>
                </div>
                <dialog class="mdl-dialog renew-mug">
                    <input type="hidden" id="selectedMug"/>
                    <input type="hidden" id="mugPaymentId"/>
                    <input type="hidden" id="mugNum"/>
                    <input type="hidden" id="mugEmail"/>
                    <h4 class="mdl-dialog__title">Confirm Upgrade?</h4>
                    <div class="mdl-dialog__content">
                        <p>
                            Extending Membership by 12 months
                        </p>
                    </div>
                    <div class="mdl-dialog__actions">
                        <button type="button" class="mdl-button agree_btn">Agree</button>
                        <button type="button" class="mdl-button close">Cancel</button>
                    </div>
                </dialog>
                <dialog class="mdl-dialog newMug-dialog">
                    <input type="hidden" id="selectedId"/>
                    <h4 class="mdl-dialog__title">Confirm Membership?</h4>
                    <div class="mdl-dialog__content">
                        <p>
                            Register New Mug Membership
                        </p>
                        <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="confirmMail">
                            <input type="checkbox" id="confirmMail" name="ifMail" value="1" class="mdl-checkbox__input">
                            <span class="mdl-checkbox__label">Send Confirmation Mail?</span>
                        </label>
                    </div>
                    <div class="mdl-dialog__actions">
                        <button type="button" class="mdl-button mug_agree_btn">Agree</button>
                        <button type="button" class="mdl-button close">Cancel</button>
                    </div>
                </dialog>
            </section>
            <?php
                if(myInArray('dashboard_feedback',$userModules))
                {
                    ?>
                    <section class="tab-pane fade" id="feedback">
                        <div class="mdl-grid">
                            <div class="mdl-cell mdl-cell--1-col"></div>
                            <div class="mdl-cell mdl-cell--10-col text-center">
                                <ul class="list-inline">
                                    <li>
                                        <label for="feedbackLoc">Location</label>
                                        <?php
                                        $commonLoc = array();
                                        if($this->userType == ADMIN_USER || $this->userType == ROOT_USER)
                                        {
                                            $commonLoc[] = 'overall';
                                            ?>
                                            <select id="feedbackLoc" class="form-control">
                                                <?php
                                                if(isset($locations))
                                                {
                                                    foreach($locations as $key => $row)
                                                    {
                                                        if(isset($row['id']))
                                                        {
                                                            $commonLoc[] = $row['locUniqueLink'];
                                                            ?>
                                                            <option value="<?php echo $row['id'];?>"><?php echo $row['locName'];?></option>
                                                            <?php
                                                        }
                                                    }
                                                }
                                                ?>
                                            </select>
                                            <?php
                                        }
                                        elseif($this->userType == EXECUTIVE_USER)
                                        {
                                            if(isset($userInfo))
                                            {
                                                ?>
                                                <select id="feedbackLoc" class="form-control">
                                                    <?php
                                                    foreach($userInfo as $key => $row)
                                                    {
                                                        $commonLoc[] = $row['locData'][0]['locUniqueLink'];
                                                        ?>
                                                        <option value="<?php echo $row['locData'][0]['id'];?>">
                                                            <?php echo $row['locData'][0]['locName'];?>
                                                        </option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                                <?php
                                            }
                                            ?>

                                            <?php
                                        }
                                        ?>

                                    </li>
                                    <li>
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="number" id="feedbackNum">
                                            <label class="mdl-textfield__label" for="feedbackNum">Number (max:50)</label>
                                        </div>
                                    </li>
                                    <li>
                                        <button type="button" id="genBtn" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
                                            Generate
                                        </button>
                                    </li>
                                </ul>
                                <?php
                                if(isset($feedbacks) && myIsArray($feedbacks))
                                {
                                    ?>
                                    <ul class="list-inline">
                                        <?php
                                        foreach($feedbacks as $key => $row)
                                        {
                                            if(myInArray($key,$commonLoc))
                                            {
                                                ?>
                                                <li>
                                                    <div class="panel panel-default">
                                                        <div class="panel-heading"><?php echo ucfirst($key);?> Net Rating</div>
                                                        <div class="panel-body stats-nums">
                                                            <?php if(isset($row)){ echo $row;} else{echo 'None';}?>
                                                        </div>
                                                    </div>
                                                </li>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </ul>
                                    <?php
                                }
                                ?>
                                <button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent"
                                        data-toggle="modal" data-target="#feedback-modal">
                                    Show Graph
                                </button>
                                <button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent"
                                        data-toggle="modal" data-target="#feedData-modal">
                                    Show Feedback Data
                                </button>
                            </div>
                            <div class="mdl-cell mdl-cell--1-col"></div>
                            <!-- Dynamic Form -->
                            <div class="mdl-cell mdl-cell--12-col dynamic-form-wrapper">
                                <form action="<?php echo base_url().'dashboard/saveFeedback/json';?>" id="feedback-form" method="post">
                                    <div class="form-super-container"></div>
                                    <button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent hide">Submit</button>
                                </form>
                            </div>
                        </div>
                    </section>
                    <?php
                }
                if(myInArray('dashboard_fnb',$userModules) || (isset($this->commSecLoc) && $this->commSecLoc != 5))
                {
                    ?>
                    <section class="tab-pane fade" id="fnbpanel">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#beverageView">Beverages</a></li>
                            <li><a data-toggle="tab" href="#foodView">Food</a></li>
                            <li><a data-toggle="tab" href="#fnbAdd">Add Fnb</a></li>
                        </ul>

                        <div class="tab-content">
                            <div id="beverageView" class="tab-pane fade in active">
                                <?php
                                if(isset($fnbData) && myIsMultiArray($fnbData))
                                {
                                    ?>
                                    <div class="mdl-grid table-responsive">
                                        <table id="main-beverage-table" class="table table-hover table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>Beverage Id</th>
                                                <!--<th>Item Type</th>-->
                                                <th>Name</th>
                                                <th>Headline</th>
                                                <th>Description</th>
                                                <th>Price Full</th>
                                                <th>Price Half</th>
                                                <th>Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            foreach($fnbData as $key => $row)
                                            {
                                                if($row['fnb']['itemType'] == "2")
                                                {
                                                    ?>
                                                    <tr>
                                                        <th scope="row"><?php echo $row['fnb']['fnbId'];?></th>
                                                        <!--<td><?php /*echo 'Beverage';*/?></td>-->
                                                        <td><?php echo $row['fnb']['itemName'];?></td>
                                                        <td><?php echo $row['fnb']['itemHeadline'];?></td>
                                                        <td><?php echo strip_tags($row['fnb']['itemDescription']);?></td>
                                                        <td><?php echo $row['fnb']['priceFull'];?></td>
                                                        <td><?php echo $row['fnb']['priceHalf'];?></td>
                                                        <td>
                                                            <a data-toggle="tooltip" class="beer-tags fnb-tracker" title="Tag Location" href="#" data-fnbId="<?php echo $row['fnb']['fnbId'];?>">
                                                                <i class="fa fa-15x fa-tags my-success-text"></i></a>
                                                            <?php
                                                            if($row['fnb']['ifActive'] == ACTIVE)
                                                            {
                                                                ?>
                                                                <a data-toggle="tooltip" class="fnb-tracker" title="Active" href="<?php echo base_url().'dashboard/setFnbDeActive/'.$row['fnb']['fnbId'];?>">
                                                                    <i class="fa fa-15x fa-lightbulb-o my-success-text"></i></a>
                                                                <?php
                                                            }
                                                            else
                                                            {
                                                                ?>
                                                                <a data-toggle="tooltip" class="fnb-tracker" title="Not Active" href="<?php echo base_url().'dashboard/setFnbActive/'.$row['fnb']['fnbId'];?>">
                                                                    <i class="fa fa-15x fa-lightbulb-o my-error-text"></i></a>
                                                                <?php
                                                            }

                                                            if(isset($row['fnbAtt']) && myIsMultiArray($row['fnbAtt']))
                                                            {
                                                                $imgs = array();
                                                                foreach($row['fnbAtt'] as $attkey => $attrow)
                                                                {
                                                                    switch($attrow['attachmentType'])
                                                                    {
                                                                        case "1":
                                                                            $imgs[] = MOBILE_URL.FOOD_PATH_THUMB.$attrow['filename'];
                                                                            break;
                                                                        case "2":
                                                                            $imgs[] = MOBILE_URL.BEVERAGE_PATH_NORMAL.$attrow['filename'];
                                                                            break;
                                                                        default:
                                                                            $imgs[] = MOBILE_URL.BEVERAGE_PATH_NORMAL.$attrow['filename'];
                                                                            break;

                                                                    }
                                                                }
                                                                ?>
                                                                <a class="view-photos" data-toggle="tooltip" title="View Photos" href="#" data-imgs="<?php echo implode(',',$imgs);?>">
                                                                    <i class="fa fa-15x fa-file-image-o my-success-text"></i></a>
                                                                <?php
                                                            }
                                                            ?>
                                                            <a data-toggle="tooltip" title="Edit"
                                                               href="<?php echo base_url().'dashboard/editFnb/'.$row['fnb']['fnbId']?>">
                                                                <i class="fa fa-15x fa-pencil-square-o my-black-text"></i></a>
                                                            <a data-toggle="tooltip" class="fnbDelete-icon" data-fnbId="<?php echo $row['fnb']['fnbId'];?>" title="Delete" href="#">
                                                                <i class="fa fa-trash-o fa-15x"></i></a>&nbsp;
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <?php

                                }
                                else
                                {
                                    echo 'No Records Found!';
                                }
                                ?>
                            </div>
                            <div id="foodView" class="tab-pane fade">
                                <?php
                                if(isset($fnbData) && myIsMultiArray($fnbData))
                                {
                                    ?>
                                    <div class="mdl-grid table-responsive">
                                        <table id="main-food-table" class="table table-hover table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>Food Id</th>
                                                <!--<th>Item Type</th>-->
                                                <th>Name</th>
                                                <th>Headline</th>
                                                <th>Description</th>
                                                <th>Price Full</th>
                                                <th>Price Half</th>
                                                <th>Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            foreach($fnbData as $key => $row)
                                            {
                                                if($row['fnb']['itemType'] == "1")
                                                {
                                                    ?>
                                                    <tr>
                                                        <th scope="row"><?php echo $row['fnb']['fnbId'];?></th>
                                                        <!--<td><?php /*echo 'Beverage';*/?></td>-->
                                                        <td><?php echo $row['fnb']['itemName'];?></td>
                                                        <td><?php echo $row['fnb']['itemHeadline'];?></td>
                                                        <td><?php echo strip_tags($row['fnb']['itemDescription']);?></td>
                                                        <td><?php echo $row['fnb']['priceFull'];?></td>
                                                        <td><?php echo $row['fnb']['priceHalf'];?></td>
                                                        <td>
                                                            <?php
                                                            if($row['fnb']['ifActive'] == ACTIVE)
                                                            {
                                                                ?>
                                                                <a data-toggle="tooltip" class="fnb-tracker" title="Active" href="<?php echo base_url().'dashboard/setFnbDeActive/'.$row['fnb']['fnbId'];?>">
                                                                    <i class="fa fa-15x fa-lightbulb-o my-success-text"></i></a>
                                                                <?php
                                                            }
                                                            else
                                                            {
                                                                ?>
                                                                <a data-toggle="tooltip" class="fnb-tracker" title="Not Active" href="<?php echo base_url().'dashboard/setFnbActive/'.$row['fnb']['fnbId'];?>">
                                                                    <i class="fa fa-15x fa-lightbulb-o my-error-text"></i></a>
                                                                <?php
                                                            }

                                                            if(isset($row['fnbAtt']) && myIsMultiArray($row['fnbAtt']))
                                                            {
                                                                $imgs = array();
                                                                foreach($row['fnbAtt'] as $attkey => $attrow)
                                                                {
                                                                    switch($attrow['attachmentType'])
                                                                    {
                                                                        case "1":
                                                                            $imgs[] = MOBILE_URL.FOOD_PATH_THUMB.$attrow['filename'];
                                                                            break;
                                                                        case "2":
                                                                            $imgs[] = MOBILE_URL.BEVERAGE_PATH_NORMAL.$attrow['filename'];
                                                                            break;
                                                                        default:
                                                                            $imgs[] = MOBILE_URL.BEVERAGE_PATH_NORMAL.$attrow['filename'];
                                                                            break;

                                                                    }
                                                                }
                                                                ?>
                                                                <a class="view-photos" data-toggle="tooltip" title="View Photos" href="#" data-imgs="<?php echo implode(',',$imgs);?>">
                                                                    <i class="fa fa-15x fa-file-image-o my-success-text"></i></a>
                                                                <?php
                                                            }
                                                            ?>
                                                            <a data-toggle="tooltip" title="Edit"
                                                               href="<?php echo base_url().'dashboard/editFnb/'.$row['fnb']['fnbId']?>">
                                                                <i class="fa fa-15x fa-pencil-square-o my-black-text"></i></a>
                                                            <a data-toggle="tooltip" class="fnbDelete-icon" data-fnbId="<?php echo $row['fnb']['fnbId'];?>" title="Delete" href="#">
                                                                <i class="fa fa-trash-o fa-15x"></i></a>&nbsp;
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <?php

                                }
                                else
                                {
                                    echo 'No Records Found!';
                                }
                                ?>
                            </div>
                            <div id="fnbAdd" class="tab-pane fade">
                                <div class="mdl-grid">
                                    <div class="mdl-cell mdl-cell--2-col"></div>
                                    <div class="mdl-cell mdl-cell--8-col text-center">
                                        <form action="<?php echo base_url();?>dashboard/savefnb" method="post" enctype="multipart/form-data">
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label my-fullWidth">
                                                <input class="mdl-textfield__input" type="text" name="itemName" id="itemName">
                                                <label class="mdl-textfield__label" for="itemName">Name</label>
                                            </div>
                                            <br>
                                            <div class="text-left">
                                                <label>Item Type :</label>
                                                <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="itemFood">
                                                    <input type="radio" id="itemFood" class="mdl-radio__button" name="itemType" value="1" checked>
                                                    <span class="mdl-radio__label">Food</span>
                                                </label>
                                                <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="itemBeverage">
                                                    <input type="radio" id="itemBeverage" class="mdl-radio__button" name="itemType" value="2">
                                                    <span class="mdl-radio__label">Beverage</span>
                                                </label>
                                            </div>
                                            <br>
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label my-fullWidth">
                                                <textarea class="mdl-textfield__input" placeholder="Headline" name="itemHeadline" rows="5"></textarea>
                                                <br>
                                                <textarea class="mdl-textfield__input" type="text" name="itemDescription" rows="5" id="itemDesc"></textarea>
                                            </div>
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label my-fullWidth">
                                                <input class="mdl-textfield__input" type="text" name="priceFull" pattern="-?[0-9]*(\.[0-9]+)?" id="itemPriceF">
                                                <label class="mdl-textfield__label" for="itemPriceF">Price (Full)</label>
                                                <span class="mdl-textfield__error">Input is not a number!</span>
                                            </div>
                                            <br>
                                            <div class="text-left">
                                                <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="priceHalf">
                                                    <input type="checkbox" id="priceHalf" class="mdl-checkbox__input" onchange="toggleHalf(this)">
                                                    <span class="mdl-checkbox__label">Half Price?</span>
                                                </label>
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label priceHalfCls hide my-fullWidth">
                                                    <input class="mdl-textfield__input" type="text" name="priceHalf" pattern="-?[0-9]*(\.[0-9]+)?" id="itemPriceH">
                                                    <label class="mdl-textfield__label" for="itemPriceH">Price (Half)</label>
                                                    <span class="mdl-textfield__error">Input is not a number!</span>
                                                </div>
                                            </div>

                                            <div class="myUploadPanel text-left">
                                                <br>
                                                <a href="http://www.photoresizer.com/" target="_blank" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent my-noUnderline">Crop Before Upload?</a>
                                                <br>
                                                <!--<label>Attachment Type :</label>
                                                <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="attFood">
                                                    <input type="radio" id="attFood" class="mdl-radio__button" name="attType[0]" value="1" checked>
                                                    <span class="mdl-radio__label">Food</span>
                                                </label>
                                                <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="attBeer">
                                                    <input type="radio" id="attBeer" class="mdl-radio__button" name="attType[0]" value="2">
                                                    <span class="mdl-radio__label">Beer Digital</span>
                                                </label>
                                                <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="attBeerW">
                                                    <input type="radio" id="attBeerW" class="mdl-radio__button" name="attType[0]" value="3">
                                                    <span class="mdl-radio__label">Beer Woodcut</span>
                                                </label>-->
                                                <input type="file" class="form-control" onchange="uploadChange(this)" />
                                                <br>
                                                <button onclick="addUploadPanel()" type="button" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">Add More?</button>
                                                <input type="hidden" name="attachment" />
                                            </div>
                                            <br>
                                            <button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">Submit</button>
                                        </form>
                                        <br>
                                        <div class="progress hide">
                                            <div class="progress-bar progress-bar-striped active" role="progressbar"
                                                 aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mdl-cell mdl-cell--2-col"></div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <?php
                }

                if(myInArray('dashboard_events',$userModules) || (isset($this->commSecLoc) && $this->commSecLoc != 5))
                {
                    ?>
                    <section class="tab-pane fade" id="eventpanel">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#eventView">Event Records</a></li>
                            <!--<li><a data-toggle="tab" href="#eventAdd">Add Event</a></li>-->
                            <li><a data-toggle="tab" href="#compEvents">Completed</a></li>
                            <li><a data-toggle="tab" href="#canEvents">Cancelled</a></li>
                            <?php
                            if($this->userType != SERVER_USER)
                            {
                                ?>
                                <li><a data-toggle="tab" href="#metaTab">Event Sharing</a></li>
                                <?php
                            }
                            ?>
                        </ul>

                        <div class="tab-content">
                            <div id="eventView" class="tab-pane fade in active">
                                <input type="hidden" id="senderEmail" value="<?php echo $this->userEmail;?>"/>
                                <?php
                                if(isset($eventDetails) && myIsMultiArray($eventDetails))
                                {
                                    ?>
                                    <div class="mdl-grid table-responsive">
                                        <table id="main-event-table" class="table table-hover table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>Event Id</th>
                                                <th>Name</th>
                                                <th>Description</th>
                                                <!--<th>Type</th>-->
                                                <th>Date</th>
                                                <th>Timing</th>
                                                <th>Cost</th>
                                                <th>Place</th>
                                                <th>Organizer Details</th>
                                                <th>Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php

                                            foreach($eventDetails as $key => $row)
                                            {
                                                if(($row['eventData']['isEventCancel'] == '0' || $row['eventData']['isEventCancel'] == '1') &&
                                                    $row['eventData']['ifApproved'] != EVENT_DECLINED)
                                                {
                                                    $eveLoc = $row['eventData']['eventPlace'];
                                                    ?>
                                                    <tr>
                                                        <th scope="row"><?php echo $row['eventData']['eventId'];?></th>
                                                        <td><?php echo $row['eventData']['eventName'];?></td>
                                                        <td><?php echo strip_tags($row['eventData']['eventDescription']);?></td>
                                                        <!--<td><?php /*echo $row['eventData']['eventType'];*/?></td>-->
                                                        <td><?php $d = date_create($row['eventData']['eventDate']); echo date_format($d,DATE_FORMAT_UI);?></td>
                                                        <td><?php echo $row['eventData']['startTime'] .' - '.$row['eventData']['endTime'];?></td>
                                                        <td>
                                                            <?php
                                                            switch($row['eventData']['costType'])
                                                            {
                                                                case 1:
                                                                    echo 'Free';
                                                                    break;
                                                                case 2:
                                                                    echo 'Event Fee + Doolally Fee: Rs '.$row['eventData']['eventPrice'];
                                                                    break;
                                                                case 3:
                                                                    echo 'Event Fee: Rs '.$row['eventData']['eventPrice'];
                                                                    break;
                                                                case 4:
                                                                    echo 'Doolally Fee: Rs '.$row['eventData']['eventPrice'];
                                                                    break;
                                                            }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            if($row['eventData']['isEventEverywhere'] == '1')
                                                            {
                                                                echo 'All Taprooms';
                                                            }
                                                            else
                                                            {
                                                                echo $row['eventData']['locData'][0]['locName'];
                                                            }
                                                            ?>
                                                        </td>
                                                        <td><?php echo $row['eventData']['creatorName'].'<br>'.$row['eventData']['creatorPhone'];?></td>
                                                        <td class="my-keepRelative"><!--<a data-toggle="tooltip" title="Edit" href="<?php /*echo base_url().'mugclub/edit/'.$row['mugId'];*/?>">
                                                        <i class="glyphicon glyphicon-edit"></i></a>&nbsp;-->
                                                            <?php
                                                            if(isset($commLocs) && !in_array($eveLoc,$commLocs))
                                                            {
                                                                ?>
                                                                <div class="my-td-overlay"></div>
                                                                <?php
                                                            }
                                                            ?>
                                                            <?php
                                                            if($row['eventData']['ifApproved'] == EVENT_WAITING)
                                                            {
                                                                ?>
                                                                <a data-toggle="tooltip" title="Approve" data-costType="<?php echo $row['eventData']['costType'];?>"
                                                                   data-costPrice="<?php echo $row['eventData']['eventPrice'];?>"
                                                                   class="even-tracker approveThis-event" href="#" data-url="<?php echo base_url().'dashboard/approve/'.$row['eventData']['eventId'];?>">
                                                                    <i class="fa fa-15x fa-check my-success-text"></i></a>
                                                                <a data-toggle="tooltip" title="Decline" class="declineThis-event" href="#" data-url="<?php echo base_url().'dashboard/decline/'.$row['eventData']['eventId'];?>">
                                                                    <i class="fa fa-15x fa-times my-error-text"></i></a>
                                                                <?php
                                                            }
                                                            elseif($row['eventData']['ifApproved'] == EVENT_APPROVED && $row['eventData']['ifActive'] == ACTIVE)
                                                            {
                                                                ?>
                                                                <a data-toggle="tooltip" title="Active" class="even-tracker" href="<?php echo base_url().'dashboard/setEventDeActive/'.$row['eventData']['eventId'];?>">
                                                                    <i class="fa fa-15x fa-lightbulb-o my-success-text"></i></a>
                                                                <a class="even-tracker cancel-this-event" data-toggle="tooltip" title="Cancel Event" href="#" data-eventId="<?php echo $row['eventData']['eventId'];?>" >
                                                                    <i class="fa fa-15x fa-times"></i></a>
                                                                <?php
                                                            }
                                                            elseif($row['eventData']['ifApproved'] == EVENT_APPROVED && $row['eventData']['ifActive'] == NOT_ACTIVE)
                                                            {
                                                                ?>
                                                                <a data-toggle="tooltip" title="Not Active" class="even-tracker" href="<?php echo base_url().'dashboard/setEventActive/'.$row['eventData']['eventId'];?>">
                                                                    <i class="fa fa-15x fa-lightbulb-o my-error-text"></i></a>
                                                                <?php
                                                            }
                                                            elseif($row['eventData']['ifApproved'] == EVENT_DECLINED)
                                                            {
                                                                ?>
                                                                <a data-toggle="tooltip" title="Declined" data-costType="<?php echo $row['eventData']['costType'];?>"
                                                                   data-costPrice="<?php echo $row['eventData']['eventPrice'];?>"
                                                                   class="even-tracker approveThis-event" href="#" data-url="<?php echo base_url().'dashboard/approve/'.$row['eventData']['eventId'];?>">
                                                                    <i class="fa fa-15x fa-ban my-error-text"></i></a>
                                                                <?php
                                                            }
                                                            if(isset($row['eventAtt']) && myIsMultiArray($row['eventAtt']))
                                                            {
                                                                $imgs = array();
                                                                foreach($row['eventAtt'] as $attkey => $attrow)
                                                                {
                                                                    $imgs[] = MOBILE_URL.EVENT_PATH_THUMB.$attrow['filename'];
                                                                }
                                                                if(isset($row['eventData']['verticalImg']))
                                                                {
                                                                    $imgs[] = MOBILE_URL.EVENT_PATH_THUMB.$row['eventData']['verticalImg'];
                                                                }
                                                                ?>
                                                                <a class="view-photos" data-toggle="tooltip" title="View Photos" href="#" data-imgs="<?php echo implode(',',$imgs);?>">
                                                                    <i class="fa fa-15x fa-file-image-o my-success-text"></i></a>
                                                                <?php
                                                            }
                                                            ?>
                                                            <a data-toggle="tooltip" title="Edit" class="even-tracker"
                                                               href="<?php echo base_url().'dashboard/editEvent/'.$row['eventData']['eventId']?>">
                                                                <i class="fa fa-15x fa-pencil-square-o my-black-text"></i></a>
                                                            <!--<a data-toggle="tooltip" class="eventDelete-icon even-tracker" data-eventId="<?php /*echo $row['eventData']['eventId'];*/?>" title="Delete" href="#">
                                                            <i class="fa fa-trash-o fa-15x"></i></a>&nbsp;-->
                                                            <?php
                                                            if($row['eventData']['isRegFull'] == '1')
                                                            {
                                                                ?>
                                                                <a data-toggle="tooltip" title="Open Registration" class="even-tracker"
                                                                   href="<?php echo base_url().'dashboard/openReg/'.$row['eventData']['eventId'];?>">
                                                                    <i class="fa fa-15x fa-check-circle-o"></i></a>&nbsp;
                                                                <?php
                                                            }
                                                            else
                                                            {
                                                                ?>
                                                                <a data-toggle="tooltip" title="Close Registration" class="even-tracker"
                                                                   href="<?php echo base_url().'dashboard/closeReg/'.$row['eventData']['eventId'];?>">
                                                                    <i class="fa fa-15x fa-times-circle-o"></i></a>&nbsp;
                                                                <?php
                                                            }
                                                            ?>

                                                            <a data-toggle="tooltip" class="eventCostChange-icon even-tracker"
                                                               data-costType="<?php echo $row['eventData']['costType'];?>"
                                                               data-costPrice="<?php echo $row['eventData']['eventPrice'];?>"
                                                               data-url="<?php echo base_url().'dashboard/changeCostType/'.$row['eventData']['eventId'];?>"
                                                               data-doolallyFee="<?php echo $row['eventData']['doolallyFee'];?>"
                                                               title="Change Cost Option" href="#">
                                                                <i class="fa fa-inr fa-15x"></i></a>&nbsp;

                                                            <a data-toggle="tooltip" class="eventSignups-icon even-tracker" data-eventName="<?php echo $row['eventData']['eventName'];?>" data-eventId="<?php echo $row['eventData']['eventId'];?>" title="Signup List" href="#">
                                                                <i class="fa fa-users fa-15x"></i></a>&nbsp;
                                                            <a data-toggle="tooltip" class="eventShareImg-icon even-tracker" data-hasShareImg="<?php echo $row['eventData']['hasShareImg'];?>" data-eventName="<?php echo $row['eventData']['eventName'];?>" data-eventId="<?php echo $row['eventData']['eventId'];?>" title="Event Share Images" href="#">
                                                                <i class="fa fa-share-alt fa-15x"></i></a>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <?php

                                }
                                else
                                {
                                    echo 'No Records Found!';
                                }
                                ?>
                            </div>
                            <div id="eventAdd" class="tab-pane fade">
                                <div class="mdl-grid">
                                    <div class="mdl-cell mdl-cell--2-col"></div>
                                    <div class="mdl-cell mdl-cell--8-col text-center">
                                        <form id="dashboardEventAdd" action="<?php echo base_url();?>dashboard/saveEvent" method="post" enctype="multipart/form-data">
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label my-fullWidth">
                                                <input class="mdl-textfield__input" type="text" name="eventName" id="eventName">
                                                <label class="mdl-textfield__label" for="eventName">Event Name</label>
                                            </div>
                                            <input type="hidden" name="senderEmail" id="senderEmail" value="<?php echo $this->userEmail;?>" />
                                            <input type="hidden" name="senderPass" id="senderPass" />
                                            <br>
                                            <!--<div class="text-left">
                                        <label for="eventType">Event Type :</label>
                                        <select name="eventType" id="eventType" class="form-control">
                                            <?php
                                            /*                                                foreach($this->config->item('eventTypes') as $key => $row)
                                                                                            {
                                                                                                */?>
                                                    <option value="<?php /*echo $row;*/?>"><?php /*echo $row;*/?></option>
                                                    <?php
                                            /*                                                }
                                                                                        */?>
                                        </select>
                                        <div class="mdl-textfield mdl-js-textfield other-event hide">
                                            <input class="mdl-textfield__input" type="text" id="otherType">
                                            <label class="mdl-textfield__label" for="otherType">Other</label>
                                        </div>
                                    </div>
                                    <br>-->
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label my-fullWidth text-left">
                                                <label for="eventDescription">Event Description: </label>
                                                <textarea class="mdl-textfield__input my-singleBorder" type="text" name="eventDescription" rows="5" id="eventDescription"></textarea>
                                            </div>
                                            <ul class="list-inline text-left">
                                                <li>
                                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                        <input class="mdl-textfield__input" type="text" name="eventDate" id="eventDate" placeholder="">
                                                        <label class="mdl-textfield__label" for="eventDate">Event Date</label>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="showEventDate">
                                                        <input type="checkbox" name="showEventDate" value="2" id="showEventDate" class="mdl-checkbox__input">
                                                        <span class="mdl-checkbox__label">Hide Event Date?</span>
                                                    </label>
                                                </li>
                                                <li>
                                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                        <input class="mdl-textfield__input" type="text" name="startTime" id="startTime" placeholder="">
                                                        <label class="mdl-textfield__label" for="startTime">Start Time</label>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                        <input class="mdl-textfield__input" type="text" name="endTime" id="endTime" placeholder="">
                                                        <label class="mdl-textfield__label" for="endTime">End Time</label>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="showEventTime">
                                                        <input type="checkbox" name="showEventTime" value="2" id="showEventTime" class="mdl-checkbox__input">
                                                        <span class="mdl-checkbox__label">Hide Event Time?</span>
                                                    </label>
                                                </li>
                                                <li>
                                                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="showEventPrice">
                                                        <input type="checkbox" name="showEventPrice" value="2" id="showEventPrice" class="mdl-checkbox__input">
                                                        <span class="mdl-checkbox__label">Hide Event Price?</span>
                                                    </label>
                                                </li>
                                            </ul>
                                            <br>
                                            <div class="text-left">
                                                <label>Event Cost (<a href="#" data-toggle="modal" data-target="#costModal">?</a>):</label><br>
                                                <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="freeType">
                                                    <input type="radio" id="freeType" class="mdl-radio__button" name="costType" value="1" checked>
                                                    <span class="mdl-radio__label">Free</span>
                                                </label><br>
                                                <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="paidType">
                                                    <input type="radio" id="paidType" class="mdl-radio__button" name="costType" value="2">
                                                    <span class="mdl-radio__label">Event Fee + Doolally Fee</span>
                                                </label>
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label custom-price">
                                                    <input class="mdl-textfield__input" type="text" name="doolallyFee" value="<?php echo (int)NEW_DOOLALLY_FEE;?>" pattern="-?[0-9]*(\.[0-9]+)?" id="customPrice">
                                                    <label class="mdl-textfield__label" for="customPrice">Custom Price</label>
                                                    <span class="mdl-textfield__error">Input is not a number!</span>
                                                </div><br>
                                                <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="paid2Type">
                                                    <input type="radio" id="paid2Type" class="mdl-radio__button" name="costType" value="3">
                                                    <span class="mdl-radio__label">Event Fee</span>
                                                </label><br>
                                                <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="paid3Type">
                                                    <input type="radio" id="paid3Type" class="mdl-radio__button" name="costType" value="4">
                                                    <span class="mdl-radio__label">Doolally Fee</span>
                                                </label><br>

                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label event-price hide">
                                                    <input class="mdl-textfield__input" type="text" name="eventPrice" pattern="-?[0-9]*(\.[0-9]+)?" id="eventPrice">
                                                    <label class="mdl-textfield__label" for="eventPrice">Price</label>
                                                    <span class="mdl-textfield__error">Input is not a number!</span>
                                                </div>

                                                <input type="hidden" name="priceFreeStuff" value=""/>
                                                <!--<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label special-offer hide">
                                                    <input class="mdl-textfield__input" type="text" name="priceFreeStuff" id="priceFreeStuff" placeholder="">
                                                    <label class="mdl-textfield__label" for="priceFreeStuff">Special Offer With Price?</label>
                                                </div>-->
                                            </div>
                                            <br>
                                            <div class="text-left">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <label>Event Place: </label>
                                                        <select id="eventPlace" name="eventPlace" class="form-control">
                                                            <?php
                                                            if(isset($locations))
                                                            {
                                                                foreach($locations as $key => $row)
                                                                {
                                                                    if(isset($row['id']))
                                                                    {
                                                                        ?>
                                                                        <option value="<?php echo $row['id'];?>"><?php echo $row['locName'];?></option>
                                                                        <?php
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-6 all-loc-block">
                                                        <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="isEventEverywhere">
                                                            <input type="checkbox" name="isEventEverywhere" value="1" id="isEventEverywhere" class="mdl-checkbox__input">
                                                            <span class="mdl-checkbox__label">Available At All Locations?</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="text-left">
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                    <input class="mdl-textfield__input" type="text" name="eventCapacity" id="eventCapacity" placeholder="">
                                                    <label class="mdl-textfield__label" for="eventCapacity">Event Capacity</label>
                                                </div>
                                                <br>
                                                <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="ifMicRequired">
                                                    <input type="checkbox" name="ifMicRequired" value="1" id="ifMicRequired" class="mdl-checkbox__input">
                                                    <span class="mdl-checkbox__label">Do you need a mic?</span>
                                                </label>
                                                <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="ifProjectorRequired">
                                                    <input type="checkbox" id="ifProjectorRequired" name="ifProjectorRequired" value="1" class="mdl-checkbox__input">
                                                    <span class="mdl-checkbox__label">Do you need a projector?</span>
                                                </label>
                                            </div>
                                            <br>
                                            <div class="text-left">
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                    <input class="mdl-textfield__input" type="text" name="creatorName" id="creatorName" placeholder="" required>
                                                    <label class="mdl-textfield__label" for="creatorName">Organizer Name</label>
                                                </div>
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                    <input class="mdl-textfield__input" type="number" name="creatorPhone" id="creatorPhone" placeholder="" required>
                                                    <label class="mdl-textfield__label" for="creatorPhone">Organizer Phone</label>
                                                </div>
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                    <input class="mdl-textfield__input" type="email" name="creatorEmail" id="creatorEmail" placeholder="" required>
                                                    <label class="mdl-textfield__label" for="creatorEmail">Organizer Email</label>
                                                </div>
                                                <br>
                                                <label for="eventDescription">Organizer Description: </label>
                                                <textarea class="mdl-textfield__input my-singleBorder" type="text" name="aboutCreator" rows="5" id="aboutCreator"></textarea>
                                            </div>
                                            <br>
                                            <div class="myUploadPanel text-left">
                                                <input type="file" multiple class="form-control" onchange="eventUploadChange(this)" />
                                                <input type="hidden" name="attachment" />
                                            </div>

                                            <br>
                                            <button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">Submit</button>
                                        </form>
                                        <br>
                                        <div class="progress hide">
                                            <div class="progress-bar progress-bar-striped active" role="progressbar"
                                                 aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mdl-cell mdl-cell--2-col"></div>
                                </div>
                            </div>
                            <div id="compEvents" class="tab-pane fade">
                                <?php
                                if(isset($completedEvents) && myIsMultiArray($completedEvents))
                                {
                                    ?>
                                    <div class="mdl-grid table-responsive">
                                        <table id="main-comp-event-table" class="table table-hover table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>Event Id</th>
                                                <th>Name</th>
                                                <th>Description</th>
                                                <!--<th>Type</th>-->
                                                <th>Date</th>
                                                <th>Timing</th>
                                                <th>Cost</th>
                                                <th>Place</th>
                                                <th>Organizer Details</th>
                                                <th>Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            foreach($completedEvents as $key => $row)
                                            {
                                                ?>
                                                <tr>
                                                    <th scope="row"><?php echo $row['eventId'];?></th>
                                                    <td><?php echo $row['eventName'];?></td>
                                                    <td><?php echo strip_tags($row['eventDescription']);?></td>
                                                    <!--<td><?php /*echo $row['eventType'];*/?></td>-->
                                                    <td><?php $d = date_create($row['eventDate']); echo date_format($d,DATE_FORMAT_UI);?></td>
                                                    <td><?php echo $row['startTime'] .' - '.$row['endTime'];?></td>
                                                    <td>
                                                        <?php
                                                        switch($row['costType'])
                                                        {
                                                            case 1:
                                                                echo 'Free';
                                                                break;
                                                            case 2:
                                                                echo 'Event Fee + Doolally Fee: Rs '.$row['eventPrice'];
                                                                break;
                                                            case 3:
                                                                echo 'Event Fee: Rs '.$row['eventPrice'];
                                                                break;
                                                            case 4:
                                                                echo 'Doolally Fee: Rs '.$row['eventPrice'];
                                                                break;
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?php echo $row['locName'];?></td>
                                                    <td><?php echo $row['creatorName'].'<br>'.$row['creatorPhone'];?></td>
                                                    <td><!--<a data-toggle="tooltip" title="Edit" href="<?php /*echo base_url().'mugclub/edit/'.$row['mugId'];*/?>">
                                                        <i class="glyphicon glyphicon-edit"></i></a>&nbsp;-->
                                                        <?php
                                                        if(isset($row['filename']))
                                                        {
                                                            $imgs = array();
                                                            $imgs[] = MOBILE_URL.EVENT_PATH_THUMB.$row['filename'];
                                                            ?>
                                                            <a class="view-photos" data-toggle="tooltip" title="View Photos" href="#" data-imgs="<?php echo implode(',',$imgs);?>">
                                                                <i class="fa fa-15x fa-file-image-o my-success-text"></i></a>
                                                            <?php
                                                        }
                                                        ?>
                                                        <!--<a data-toggle="tooltip" title="Edit"
                                                   href="<?php /*echo base_url().'dashboard/editEvent/'.$row['eventId']*/?>">
                                                    <i class="fa fa-15x fa-pencil-square-o my-black-text"></i></a>-->
                                                        <!--<a data-toggle="tooltip" class="eventCompletedDelete-icon" data-eventId="<?php /*echo $row['eventId'];*/?>" title="Delete" href="#">
                                                    <i class="fa fa-trash-o fa-15x"></i></a>&nbsp;-->
                                                        <a data-toggle="tooltip" class="eventSignups-icon even-tracker" data-eventName="<?php echo $row['eventName'];?>" data-eventId="<?php echo $row['eventId'];?>" title="Signup List" href="#">
                                                            <i class="fa fa-users fa-15x"></i></a>&nbsp;
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <?php

                                }
                                else
                                {
                                    echo 'No Records Found!';
                                }
                                ?>
                            </div>
                            <div id="canEvents" class="tab-pane fade">
                                <?php
                                if(isset($eventDetails) && myIsMultiArray($eventDetails))
                                {
                                    ?>
                                    <div class="mdl-grid table-responsive">
                                        <table id="main-comp-event-table" class="table table-hover table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>Event Id</th>
                                                <th>Name</th>
                                                <th>Description</th>
                                                <!--<th>Type</th>-->
                                                <th>Date</th>
                                                <th>Timing</th>
                                                <th>Cost</th>
                                                <th>Place</th>
                                                <th>Organizer Details</th>
                                                <th>Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            foreach($eventDetails as $key => $row)
                                            {
                                                if($row['eventData']['isEventCancel'] == '2' || $row['eventData']['ifApproved'] == EVENT_DECLINED)
                                                {
                                                    ?>
                                                    <tr>
                                                        <th scope="row"><?php echo $row['eventData']['eventId'];?></th>
                                                        <td><?php echo $row['eventData']['eventName'];?></td>
                                                        <td><?php echo strip_tags($row['eventData']['eventDescription']);?></td>
                                                        <!--<td><?php /*echo $row['eventType'];*/?></td>-->
                                                        <td><?php $d = date_create($row['eventData']['eventDate']); echo date_format($d,DATE_FORMAT_UI);?></td>
                                                        <td><?php echo $row['eventData']['startTime'] .' - '.$row['eventData']['endTime'];?></td>
                                                        <td>
                                                            <?php
                                                            switch($row['eventData']['costType'])
                                                            {
                                                                case 1:
                                                                    echo 'Free';
                                                                    break;
                                                                case 2:
                                                                    echo 'Event Fee + Doolally Fee: Rs '.$row['eventData']['eventPrice'];
                                                                    break;
                                                                case 3:
                                                                    echo 'Event Fee: Rs '.$row['eventData']['eventPrice'];
                                                                    break;
                                                                case 4:
                                                                    echo 'Doolally Fee: Rs '.$row['eventData']['eventPrice'];
                                                                    break;
                                                            }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            if($row['eventData']['isEventEverywhere'] == '1')
                                                            {
                                                                echo 'All Taprooms';
                                                            }
                                                            else
                                                            {
                                                                echo $row['eventData']['locData'][0]['locName'];
                                                            }
                                                            ?>
                                                        </td>
                                                        <td><?php echo $row['eventData']['creatorName'].'<br>'.$row['eventData']['creatorPhone'];?></td>
                                                        <td><!--<a data-toggle="tooltip" title="Edit" href="<?php /*echo base_url().'mugclub/edit/'.$row['mugId'];*/?>">
                                                        <i class="glyphicon glyphicon-edit"></i></a>&nbsp;-->
                                                            <?php
                                                            if(isset($row['eventAtt']) && myIsMultiArray($row['eventAtt']))
                                                            {
                                                                $imgs = array();
                                                                foreach($row['eventAtt'] as $attkey => $attrow)
                                                                {
                                                                    $imgs[] = MOBILE_URL.EVENT_PATH_THUMB.$attrow['filename'];
                                                                }
                                                                ?>
                                                                <a class="view-photos" data-toggle="tooltip" title="View Photos" href="#" data-imgs="<?php echo implode(',',$imgs);?>">
                                                                    <i class="fa fa-15x fa-file-image-o my-success-text"></i></a>
                                                                <?php
                                                            }
                                                            ?>
                                                            <a data-toggle="tooltip" class="eventSignups-icon even-tracker" data-eventName="<?php echo $row['eventData']['eventName'];?>" data-eventId="<?php echo $row['eventData']['eventId'];?>" title="Signup List" href="#">
                                                                <i class="fa fa-users fa-15x"></i></a>&nbsp;
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <?php

                                }
                                else
                                {
                                    echo 'No Records Found!';
                                }
                                ?>
                            </div>
                            <?php
                            if($this->userType != SERVER_USER)
                            {
                                ?>
                                <div id="metaTab" class="tab-pane fade">
                                    <div class="mdl-grid">
                                        <div class="mdl-cell mdl-cell--2-col"></div>
                                        <div class="mdl-cell mdl-cell--8-col">
                                            <form id="meta-event-form" action="<?php echo base_url();?>dashboard/saveEventMeta" method="post" enctype="multipart/form-data">
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label my-fullWidth">
                                                    <input class="mdl-textfield__input" type="text" name="metaTitle" id="shareTitle">
                                                    <label class="mdl-textfield__label" for="shareTitle">Sharing Title</label>
                                                </div>
                                                <br>
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label my-fullWidth">
                                                    <label class="mdl-textfield__label" for="shareDesc">Sharing Description</label>
                                                    <textarea class="mdl-textfield__input my-singleBorder" rows="5" name="metaDescription" id="shareDesc"></textarea>
                                                </div>
                                                <br>
                                                <div class="myUploadPanel text-left">
                                                    <input type="file" class="form-control" onchange="metaUploadChange(this)" />
                                                    <input type="hidden" name="metaImg" />
                                                </div>
                                                <br>
                                                <button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">Save</button>
                                            </form>
                                            <br>
                                            <div class="progress hide">
                                                <div class="progress-bar progress-bar-striped active" role="progressbar"
                                                     aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                            <br>
                                            <?php
                                            if(isset($shareMeta) && myIsArray($shareMeta))
                                            {
                                                ?>
                                                <div class="mdl-grid table-responsive">
                                                    <table class="table table-hover table-bordered table-striped">
                                                        <thead>
                                                        <tr>
                                                            <th>Id</th>
                                                            <th>Title</th>
                                                            <th>Description</th>
                                                            <th>Image</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                        foreach($shareMeta as $key => $row)
                                                        {
                                                            ?>
                                                            <tr>
                                                                <th scope="row"><?php echo $row['id'];?></th>
                                                                <td><?php echo $row['metaTitle'];?></td>
                                                                <td><?php echo $row['metaDescription'];?></td>
                                                                <?php
                                                                if(isset($row['metaImg']) && $row['metaImg'] != '')
                                                                {
                                                                    $imgs = array(MOBILE_URL.'asset/images/thumb/'.$row['metaImg']);
                                                                    ?>
                                                                    <td>
                                                                        <a class="view-photos" data-toggle="tooltip" title="View Photo" href="#" data-imgs="<?php echo implode(',',$imgs);?>">
                                                                            <i class="fa fa-15x fa-file-image-o my-success-text"></i></a>
                                                                    </td>
                                                                    <?php

                                                                }
                                                                ?>
                                                            </tr>
                                                            <?php
                                                        }
                                                        ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <?php

                                            }
                                            ?>
                                        </div>
                                        <div class="mdl-cell mdl-cell--2-col"></div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </section>
                    <?php
                }

                if(myInArray('dashboard_beerolympics',$userModules) || (isset($this->commSecLoc) && $this->commSecLoc != 5))
                {
                    ?>
                    <section class="tab-pane fade" id="beerpanel">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#beerTab">Olympics Sharing</a></li>
                        </ul>

                        <div class="tab-content">
                            <?php
                            if($this->userType != SERVER_USER)
                            {
                                ?>
                                <div id="beerTab" class="tab-pane fade in active">
                                    <div class="mdl-grid">
                                        <div class="mdl-cell mdl-cell--2-col"></div>
                                        <div class="mdl-cell mdl-cell--8-col">
                                            <form id="beer-olympics-form" action="<?php echo base_url();?>dashboard/saveBeerMeta" method="post" enctype="multipart/form-data">
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label my-fullWidth">
                                                    <input class="mdl-textfield__input" type="text" name="olympicsTitle" id="olympicsTitle">
                                                    <label class="mdl-textfield__label" for="olympicsTitle">Sharing Title</label>
                                                </div>
                                                <br>
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label my-fullWidth">
                                                    <label class="mdl-textfield__label" for="olympicsDesc">Sharing Description</label>
                                                    <textarea class="mdl-textfield__input my-singleBorder" rows="5" name="olympicsDescription" id="olympicsDesc"></textarea>
                                                </div>
                                                <br>
                                                <div class="myUploadPanel text-left">
                                                    <input type="file" class="form-control" onchange="beerUploadChange(this)" />
                                                    <input type="hidden" name="olympicsImg" />
                                                </div>
                                                <br>
                                                <button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">Save</button>
                                            </form>
                                            <br>
                                            <div class="progress hide">
                                                <div class="progress-bar progress-bar-striped active" role="progressbar"
                                                     aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                            <br>
                                            <?php
                                            if(isset($olympicsMeta) && myIsArray($olympicsMeta))
                                            {
                                                ?>
                                                <div class="mdl-grid table-responsive">
                                                    <table class="table table-hover table-bordered table-striped">
                                                        <thead>
                                                        <tr>
                                                            <th>Id</th>
                                                            <th>Title</th>
                                                            <th>Description</th>
                                                            <th>Image</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                        foreach($olympicsMeta as $key => $row)
                                                        {
                                                            ?>
                                                            <tr>
                                                                <th scope="row"><?php echo $row['id'];?></th>
                                                                <td><?php echo $row['metaTitle'];?></td>
                                                                <td><?php echo $row['metaDescription'];?></td>
                                                                <?php
                                                                if(isset($row['metaImg']) && $row['metaImg'] != '')
                                                                {
                                                                    $imgs = array(MOBILE_URL.'asset/images/thumb/'.$row['metaImg']);
                                                                    ?>
                                                                    <td>
                                                                        <a class="view-photos" data-toggle="tooltip" title="View Photo" href="#" data-imgs="<?php echo implode(',',$imgs);?>">
                                                                            <i class="fa fa-15x fa-file-image-o my-success-text"></i></a>
                                                                    </td>
                                                                    <?php

                                                                }
                                                                ?>
                                                            </tr>
                                                            <?php
                                                        }
                                                        ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <?php

                                            }
                                            ?>
                                        </div>
                                        <div class="mdl-cell mdl-cell--2-col"></div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </section>
                    <?php
                }
            ?>
        </main>
    </div>
    <div id="feedback-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Feedback Analysis</h4>
                </div>
                <div class="modal-body">
                    <?php
                    if($this->userType == ADMIN_USER || $this->userType == ROOT_USER)
                    {
                        ?>
                        <select id="location-feed" onchange="refreshFeeds(this)" class="form-control">
                            <option value="0">Overall</option>
                            <?php
                            if(isset($locations))
                            {
                                foreach($locations as $key => $row)
                                {
                                    if(isset($row['id']))
                                    {
                                        ?>
                                        <option value="<?php echo $row['id'];?>"><?php echo $row['locName'];?></option>
                                        <?php
                                    }
                                }
                            }
                            ?>
                        </select>
                        <?php
                    }
                    elseif($this->userType == EXECUTIVE_USER)
                    {
                        if(isset($userInfo))
                        {
                            ?>
                            <select id="location-feed" onchange="refreshFeeds(this)" class="form-control">
                                <?php
                                foreach($userInfo as $key => $row)
                                {
                                    ?>
                                    <option value="<?php echo $row['locData'][0]['id'];?>">
                                        <?php echo $row['locData'][0]['locName'];?>
                                    </option>
                                    <?php
                                }
                                ?>
                            </select>
                            <?php
                        }
                        ?>

                        <?php
                    }
                    ?>
                    <canvas id="feedWeekly-canvas" class="mygraphs"></canvas>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
    <div id="feedData-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Feedback Data</h4>
                </div>
                <div class="modal-body">
                    <?php
                        if(isset($allFeedbacks) && myIsArray($allFeedbacks))
                        {
                            ?>
                    <table id="main-feedback-table" class="table table-hover table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Rating</th>
                            <th>Gender</th>
                            <th>Age</th>
                            <th>Location</th>
                            <th>Inserted Date/Time</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach($allFeedbacks as $key => $row)
                            {
                                ?>
                                    <tr>
                                        <td><?php echo $row['id'];?></td>
                                        <td><?php echo $row['overallRating'];?></td>
                                        <td>
                                            <?php
                                                if($row['userGender'] == 'M')
                                                {
                                                    echo 'Man';
                                                }
                                                else
                                                {
                                                    echo 'Female';
                                                }
                                            ?>
                                        </td>
                                        <td><?php echo $row['userAge'];?></td>
                                        <td><?php echo $row['locName'];?></td>
                                        <td><?php $d = date_create($row['insertedDateTime']); echo date_format($d,DATE_TIME_FORMAT_UI);?></td>
                                    </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                        </table>
                            <?php
                        }
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    <div id="beerLoc-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Tag Beer Location</h4>
                </div>
                <div class="modal-body text-center">
                    <input type="hidden" id="fnbId" value=""/>
                    <label>Beer Available at these Locations</label>
                    <div class="tagged-locations">

                    </div>
                    <hr>

                    <div class="beer-loc-select">
                        <label>Locations</label>
                        <ul class="list-inline">
                            <?php
                            if(isset($locations))
                            {
                                foreach($locations as $key => $row)
                                {
                                    if(isset($row['id']))
                                    {
                                        ?>
                                        <li data-value="<?php echo $row['id'];?>"><?php echo $row['locName'];?></li>
                                        <?php
                                    }
                                }
                            }
                            ?>
                        </ul>
                    </div>
                    <br>
                    <button type="button" class="btn btn-primary save-fnb-tags">Save</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
    <div id="peopleView-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Signup List for <b><span class="eventName"></span></b></h4>
                    <div class="signup-for-download hide"></div>
                </div>
                <div class="modal-body text-center signup-tab" style="overflow:auto">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary exportToExcel hide" onclick="exporttocsv()">Export To Excel</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
    <div id="eventPrice-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Event Cost Type</h4>
                </div>
                <div class="modal-body text-center">
                    <label>Select Event Cost Type (<a href="#" data-toggle="modal" data-target="#costModal">?</a>): </label>
                    <div class="cost-type">
                        <div class="row">
                            <div class="col-sm-12">
                                <ul class="list-inline">
                                    <li>
                                        <div class="radio">
                                            <label><input type="radio" name="costType" value="1">Free</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="radio">
                                            <label><input type="radio" name="costType" value="2">Event Fee + Doolally Fee</label>
                                        </div>
                                        <input class="form-control hide" type="number" name="doolallyFee" value="<?php echo NEW_DOOLALLY_FEE;?>" id="customPrice" placeholder="Custom Price" readonly/>
                                    </li>
                                    <li>
                                        <div class="radio">
                                            <label><input type="radio" name="costType" value="3">Event Fee</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="radio">
                                            <label><input type="radio" name="costType" value="4">Doolally Fee</label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-sm-3"></div>
                            <div class="col-sm-6">
                                <div class="input-group hide">
                                    <span class="input-group-addon">Rs. </span>
                                    <input class="form-control" type="number" name="costPrice" id="costPrice" placeholder="Event Cost"/>
                                </div>
                            </div>
                            <div class="col-sm-3"></div>

                        </div>
                    </div><br>
                    <button type="button" class="btn btn-primary save-event-price">Save</button>
                </div>
            </div>

        </div>
    </div>
    <div id="costModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Cost Information</h4>
                </div>
                <div class="modal-body">
                    <p>Free (NO organiser fee, NO doolally fee, NO coupon code)<br>
                        Event Fee (organiser fee, NO doolally fee, NO coupon code)<br>
                        Event Fee + Doolally Fee (organiser fee, doolally fee, coupon code)<br>
                        Doolally Fee (NO organiser fee, doolally fee, coupon code)</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
    <div id="shareImg-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Alternate Share Images for <b><span class="eventName"></span></b></h4>
                </div>
                <div class="modal-body text-center share-body">
                    <input type="hidden" id="eventId" value=""/>
                    <ul class="list-inline text-left">
                        <li>
                            <span>Use Alternate Image For Sharing?</span>
                        </li>
                        <li>
                            <div class="radio">
                                <label><input type="radio" name="hasShareImg" value="1">Yes</label>
                            </div>
                        </li>
                        <li>
                            <div class="radio">
                                <label><input type="radio" name="hasShareImg" value="0">No</label>
                            </div>
                        </li>
                    </ul>
                    <div class="uploaded-imgs-section">
                        No Images Uploaded Yet!
                    </div>
                    <br>
                    <div class="text-center">
                        <input type="file" multiple class="form-control" onchange="shareUploadChange(this)" />
                        <br>
                        <div class="share-progress hide">
                            <div class="progress-bar progress-bar-striped active" role="progressbar"
                                 aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="event-alt-share-btn">Save</button>
                </div>
            </div>

        </div>
    </div>
    <?php echo $footerView; ?>

</body>
<?php echo $globalJs; ?>
<script>

    $('#startDate').datetimepicker({
        format: 'YYYY-MM-DD'
    });
    $('#endDate').datetimepicker({
        format: 'YYYY-MM-DD'
    });
    var totalCheckins = {};
    var avgCheckins = {};
    var regulars = {};
    var irregulars = {};
    var lapsers = {};
    var graph_avg = {};
    var graph_regulars = {};
    var graph_irregulars = {};
    var graph_lapsers = {};
    var graph_labels = [];
    var feed_labels = [];
    var feed_locs = {};

    var totalChecksBar, avgChecksBar, regularsBar, irregularsBar,lapsersBar;
    //setting all values
    <?php
        if(isset($avgChecks))
        {
            for($i = 0;$i<count($avgChecks['checkInList']); $i++)
            {
                $mugkeys = array_keys($totalMugs);
                if($totalMugs[$mugkeys[$i]] != 0)
                {
                    $checkinKeys = array_keys($avgChecks['checkInList']);
                    $allStores = ((int)$avgChecks['checkInList'][$checkinKeys[$i]]/$totalMugs[$mugkeys[$i]]);
                    ?>
                    totalCheckins[<?php echo $i;?>] = <?php echo (int)$avgChecks['checkInList'][$checkinKeys[$i]];?>;
                    avgCheckins[<?php echo $i;?>] = <?php echo round($allStores,2);?>;
                    <?php
                }
                else
                {
                    ?>
                    totalCheckins[<?php echo $i;?>] = 0;
                    avgCheckins[<?php echo $i;?>] = 0;
                    <?php
                }
            }
        }
        if(isset($Regulars))
        {
            for($i = 0;$i<count($Regulars['regularCheckins']); $i++)
            {
                $mugkeys = array_keys($totalMugs);
                if($totalMugs[$mugkeys[$i]] != 0)
                {
                    $checkinKeys = array_keys($Regulars['regularCheckins']);
                    $allStores = ((int)$Regulars['regularCheckins'][$checkinKeys[$i]]/$totalMugs[$mugkeys[$i]]);
                    ?>
                    regulars[<?php echo $i;?>] = <?php echo round($allStores,2);?>;
                    <?php
                }
                else
                {
                    ?>
                    regulars[<?php echo $i;?>] = 0;
                    <?php
                }
            }
        }
        if(isset($Irregulars))
        {
            for($i = 0;$i<count($Irregulars['irregularCheckins']); $i++)
            {
                $mugkeys = array_keys($totalMugs);
                if($totalMugs[$mugkeys[$i]] != 0)
                {
                    $checkinKeys = array_keys($Irregulars['irregularCheckins']);
                    $allStores = ((int)$Irregulars['irregularCheckins'][$checkinKeys[$i]]/$totalMugs[$mugkeys[$i]]);
                    ?>
                    irregulars[<?php echo $i;?>] = <?php echo round($allStores,2);?>;
                    <?php
                }
                else
                {
                    ?>
                        irregulars[<?php echo $i;?>] = 0;
                    <?php
                }
            }
        }
        if(isset($lapsers))
        {
            for($i = 0;$i<count($lapsers['lapsers']); $i++)
            {
                $mugkeys = array_keys($totalMugs);
                if($totalMugs[$mugkeys[$i]] != 0)
                {
                    $checkinKeys = array_keys($lapsers['lapsers']);
                    $allStores = ((int)$lapsers['lapsers'][$checkinKeys[$i]]/$totalMugs[$mugkeys[$i]]);
                    ?>
                    lapsers[<?php echo $i;?>] = <?php echo round($allStores,2);?>;
                    <?php
                }
                else
                {
                    ?>
                        lapsers[<?php echo $i;?>] = 0;
                    <?php
                }
            }
        }

        for($i=0;$i<count($locations);$i++)
        {
            ?>
            graph_avg[<?php echo $i;?>]= [];
            graph_regulars[<?php echo $i;?>] = [];
            graph_irregulars[<?php echo $i;?>] = [];
            graph_lapsers[<?php echo $i;?>] = [];
            feed_locs[<?php echo $i;?>] = [];
            <?php
        }
        //Graph points
        if(isset($graph['avgChecks']))
        {
            ?>
            /*graph_avg[0] = [];
            graph_avg[1] = [];
            graph_avg[2] = [];
            graph_avg[3] = [];
            graph_avg[4] = [];*/
            <?php
            for($i = 0;$i<count($graph['avgChecks']); $i++)
            {
                $graphVals = explode(',',$graph['avgChecks'][$i]);
                for($j=0;$j<count($graphVals);$j++)
                {
                    if(isset($graphVals[$j]))
                    {
                        ?>
                        graph_avg[<?php echo $j;?>].push(<?php echo $graphVals[$j];?>);
                        <?php
                    }
                }
            }
        }
        if(isset($graph['regulars']))
        {
            ?>
            /*graph_regulars[0] = [];
            graph_regulars[1] = [];
            graph_regulars[2] = [];
            graph_regulars[3] = [];
            graph_regulars[4] = [];*/
            <?php
            for($i = 0;$i<count($graph['regulars']); $i++)
            {
                $graphVals = explode(',',$graph['regulars'][$i]);
                for($j=0;$j<count($graphVals);$j++)
                {
                        if(isset($graphVals[$j]))
                        {
                            ?>
                            graph_regulars[<?php echo $j;?>].push(<?php echo $graphVals[$j];?>);
                            <?php
                        }
                }
            }
        }
        if(isset($graph['irregulars']))
        {
            ?>
            /*graph_irregulars[0] = [];
            graph_irregulars[1] = [];
            graph_irregulars[2] = [];
            graph_irregulars[3] = [];
            graph_irregulars[4] = [];*/
            <?php
            for($i = 0;$i<count($graph['irregulars']); $i++)
            {
                $graphVals = explode(',',$graph['irregulars'][$i]);
                for($j=0;$j<count($graphVals);$j++)
                {
                    if(isset($graphVals[$j]))
                    {
                        ?>
                            graph_irregulars[<?php echo $j;?>].push(<?php echo $graphVals[$j];?>);
                        <?php
                    }
                }
            }
        }
        if(isset($graph['lapsers']))
        {
            ?>
            /*graph_lapsers[0] = [];
            graph_lapsers[1] = [];
            graph_lapsers[2] = [];
            graph_lapsers[3] = [];
            graph_lapsers[4] = [];*/
            <?php
            for($i = 0;$i<count($graph['lapsers']); $i++)
            {
                $graphVals = explode(',',$graph['lapsers'][$i]);
                for($j=0;$j<count($graphVals);$j++)
                {
                    if(isset($graphVals[$j]))
                    {
                        ?>
                            graph_lapsers[<?php echo $j;?>].push(<?php echo $graphVals[$j];?>);
                        <?php
                    }
                }
            }
        }
        if(isset($graph['labelDate']))
        {
            for($i = 0;$i<count($graph['labelDate']); $i++)
            {
                ?>
                graph_labels.push('<?php echo $graph['labelDate'][$i];?>');
                <?php
            }
        }
        if(isset($weeklyFeed) && myIsMultiArray($weeklyFeed))
        {
            ?>
            /*feed_locs[0] = [];
            feed_locs[1] = [];
            feed_locs[2] = [];
            feed_locs[3] = [];
            feed_locs[4] = [];*/
            <?php
            foreach($weeklyFeed as $key => $row)
            {
                $feedLocs = explode(',',$row['feeds']);
                for($j=0;$j<count($feedLocs);$j++)
                {
                    if(isset($feedLocs[$j]))
                    {
                        ?>
                            feed_locs[<?php echo $j;?>].push(<?php echo $feedLocs[$j];?>);
                        <?php
                    }
                }
                ?>
                    feed_labels.push('<?php echo $row['labelDate'];?>');
                <?php
            }
        }
    ?>


    $(window).load(function(){
        var selectedLoc = Number($('#location').val());

        //Total Checkins
        totalChecksBar = new ProgressBar.Circle('#totalCheckins-container', {
            strokeWidth: 6,
            easing: 'easeInOut',
            duration: 1000,
            color: '#ACEC00',
            trailWidth: 6,
            step: function(state, circle) {
                var value = circle.value().toFixed(2);
                if (value === 0) {
                    circle.setText('');
                } else {
                    circle.setText(value);
                }

            }
        });
        totalChecksBar.text.style.fontSize = '2em';
        totalChecksBar.animate(totalCheckins[selectedLoc]);

        //Average Checkins
        avgChecksBar = new ProgressBar.Circle('#avgCheckins-container', {
            strokeWidth: 6,
            easing: 'easeInOut',
            duration: 1000,
            color: '#ACEC00',
            trailWidth: 6,
            step: function(state, circle) {
                var value = circle.value().toFixed(2);
                if (value === 0) {
                    circle.setText('');
                } else {
                    circle.setText(value);
                }

            }
        });
        avgChecksBar.text.style.fontSize = '2em';
        avgChecksBar.animate(avgCheckins[selectedLoc]);  // Value from 0.0 to 1.0

        //Regulars
        regularsBar = new ProgressBar.Circle('#regulars-container', {
            strokeWidth: 6,
            easing: 'easeInOut',
            duration: 1000,
            color: '#00BBD6',
            trailWidth: 6,
            step: function(state, circle) {
                var value = Math.round(circle.value() * 100);
                if (value === 0) {
                    circle.setText('');
                } else {
                    circle.setText(value+'%');
                }

            }
        });
        regularsBar.text.style.fontSize = '2em';
        regularsBar.animate(regulars[selectedLoc]);  // Value from 0.0 to 1.0

        //Irregulars
        irregularsBar = new ProgressBar.Circle('#irregulars-container', {
            strokeWidth: 6,
            easing: 'easeInOut',
            duration: 1000,
            color: '#BA65C9',
            trailWidth: 6,
            step: function(state, circle) {
                var value = Math.round(circle.value() * 100);
                if (value === 0) {
                    circle.setText('');
                } else {
                    circle.setText(value+'%');
                }

            }
        });
        irregularsBar.text.style.fontSize = '2em';
        irregularsBar.animate(irregulars[selectedLoc]);  // Value from 0.0 to 1.0

        //Lapsers
        lapsersBar = new ProgressBar.Circle('#lapsers-container', {
            strokeWidth: 6,
            easing: 'easeInOut',
            duration: 1000,
            color: '#EF3C79',
            trailWidth: 6,
            step: function(state, circle) {
                var value = Math.round(circle.value() * 100);
                if (value === 0) {
                    circle.setText('');
                } else {
                    circle.setText(value+'%');
                }

            }
        });
        lapsersBar.text.style.fontSize = '2em';
        lapsersBar.animate(lapsers[selectedLoc]);  // Value from 0.0 to 1.0

        <?php
            if($this->userType == ADMIN_USER || $this->userType == ROOT_USER)
            {
                ?>
                    //setTimeout(saveDashBoardRecord(),5000);
                <?php
            }
        ?>
        if(window.location.href.indexOf('events') != -1)
        {
            $('.nav-pills a[href="#eventpanel"]').tab('show');
        }
    });
    function joinObjectArray(AssocArray)
    {
        var s = '';
        for (var i in AssocArray) {
            s += AssocArray[i] + ", ";
        }
        return s.substring(0, s.length-2);
    }
    function saveDashBoardRecord()
    {
        var postData = {
            'avgCheckins': joinObjectArray(avgCheckins),
            'regulars': joinObjectArray(regulars),
            'irregulars': joinObjectArray(irregulars),
            'lapsers': joinObjectArray(lapsers),
            'insertedDate': $('#endDate').val()
        };

        $.ajax({
            type:"POST",
            dataType:"json",
            data: postData,
            url:'<?php echo base_url();?>dashboard/save',
            success: function(data)
            {

            },
            error: function(){

            }
        });
    }

    function refreshBars(ele)
    {
        var selectedLoc = Number($(ele).val());
        totalChecksBar.animate(totalCheckins[selectedLoc]);
        avgChecksBar.animate(avgCheckins[selectedLoc]);
        regularsBar.animate(regulars[selectedLoc]);
        irregularsBar.animate(irregulars[selectedLoc]);
        lapsersBar.animate(lapsers[selectedLoc]);
        refreshGraphs();
    }
    var evenTable,fnbTable;
    $(document).ready(function(){
        var thArray = ['overall-th','bandra-th','andheri-th'];
        var tdArray = ['overall-td','bandra-td','andheri-td'];
        for(var i=0;i<thArray.length;i++)
        {
            $('.'+thArray[i]).addClass('hide');
            $('.'+tdArray[i]).addClass('hide');
        }
        $('#location option').each(function(i,val){
            var index = Number($(val).attr('value'));
            $('.'+thArray[index]).removeClass('hide');
            $('.'+tdArray[index]).removeClass('hide');
        });
        drawGraphs();
        drawFeedGraph();
        if(localStorageUtil.getLocal('tabEventPage') != null)
        {
            $('.nav-pills a[href="#eventpanel"]').tab('show');
            evenTable =  $('#main-event-table').DataTable({
                "displayStart": localStorageUtil.getLocal('tabEventPage') * 10,
                "ordering": false
            });
            localStorageUtil.delLocal('tabEventPage');
        }
        else
        {
            evenTable =  $('#main-event-table').DataTable({
                "ordering": false
            });
        }
        if(localStorageUtil.getLocal('tabFnbPage') != null)
        {
            $('.nav-pills a[href="#fnbpanel"]').tab('show');
            fnbTable =  $('#main-beverage-table,#main-food-table').DataTable({
                "displayStart": localStorageUtil.getLocal('tabFnbPage') * 10,
                "ordering": false
            });
            localStorageUtil.delLocal('tabFnbPage');
        }
        else
        {
            fnbTable =  $('#main-beverage-table,#main-food-table').DataTable({
                "ordering": false
            });
        }
    });

    $(document).on('submit', '#customDateForm', function(e){

        e.preventDefault();
        if($('input[name="startDate"]').val() == '')
        {
            $('input[name="startDate"]').focus();
            return false;
        }
        if($('input[name="endDate"]').val() == '')
        {
            $('input[name="endDate"]').focus();
            return false;
        }
        if($(this).find('input[name="startDate"]').val() >= $(this).find('input[name="endDate"]').val())
        {
            bootbox.alert('Start Date cannot be greater or equal to End Date');
            return false;
        }
        var errUrl = $(this).attr('action');
        showCustomLoader();
        $.ajax({
            type:'POST',
            dataType:'json',
            url:$(this).attr('action'),
            data: $(this).serialize(),
            success: function(data)
            {
                hideCustomLoader();
                var checkinKeys,mugKeys,calcValue,i;
                mugKeys = Object.keys(data.totalMugs);
                if(typeof data.avgChecks != 'undefined')
                {
                    checkinKeys = Object.keys(data.avgChecks.checkInList);
                    for(i=0;i<Object.keys(data.avgChecks.checkInList).length;i++)
                    {
                        calcValue = Number(data.avgChecks.checkInList[checkinKeys[i]])/data.totalMugs[mugKeys[i]];
                        totalCheckins[i] = Number(data.avgChecks.checkInList[checkinKeys[i]]);
                        avgCheckins[i] = Number(calcValue.toFixed(2));
                    }
                }
                if(typeof data.Regulars != 'undefined')
                {
                    checkinKeys = Object.keys(data.Regulars.regularCheckins);
                    for(i=0;i<Object.keys(data.Regulars.regularCheckins).length;i++)
                    {
                        calcValue = Number(data.Regulars.regularCheckins[checkinKeys[i]])/data.totalMugs[mugKeys[i]];
                        regulars[i] = Number(calcValue.toFixed(2));
                    }
                }
                if(typeof data.Irregulars != 'undefined')
                {
                    checkinKeys = Object.keys(data.Irregulars.irregularCheckins);
                    for(i=0;i<Object.keys(data.Irregulars.irregularCheckins).length;i++)
                    {
                        calcValue = Number(data.Irregulars.irregularCheckins[checkinKeys[i]])/data.totalMugs[mugKeys[i]];
                        irregulars[i] = Number(calcValue.toFixed(2));
                    }
                }
                if(typeof data.lapsers != 'undefined')
                {
                    checkinKeys = Object.keys(data.lapsers.lapsers);
                    for(i=0;i<Object.keys(data.lapsers.lapsers).length;i++)
                    {
                        calcValue = Number(data.lapsers.lapsers[checkinKeys[i]])/data.totalMugs[mugKeys[i]];
                        lapsers[i] = Number(calcValue.toFixed(2));
                    }
                }
                refreshBars($('#location'));
                $('.overall-td').each(function(i,val){
                    switch(i)
                    {
                        case 0:
                            $(this).html(avgCheckins[0]);
                            break;
                        case 1:
                            $(this).html(regulars[0]);
                            break;
                        case 2:
                            $(this).html(irregulars[0]);
                            break;
                        case 3:
                            $(this).html(lapsers[0]);
                            break;
                    }
                });
                $('.andheri-td').each(function(i,val){
                    switch(i)
                    {
                        case 0:
                            $(this).html(avgCheckins[2]);
                            break;
                        case 1:
                            $(this).html(regulars[2]);
                            break;
                        case 2:
                            $(this).html(irregulars[2]);
                            break;
                        case 3:
                            $(this).html(lapsers[2]);
                            break;
                    }
                });
                $('.bandra-td').each(function(i,val){
                    switch(i)
                    {
                        case 0:
                            $(this).html(avgCheckins[1]);
                            break;
                        case 1:
                            $(this).html(regulars[1]);
                            break;
                        case 2:
                            $(this).html(irregulars[1]);
                            break;
                        case 3:
                            $(this).html(lapsers[1]);
                            break;
                    }
                });
            },
            error: function(xhr, status, error)
            {
                hideCustomLoader();
                bootbox.alert('Some Error Occurred!');
                var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                saveErrorLog(err);
            }
        });

    });

    //Graph Generation
    function getConfig(color,dataSet,title)
    {
        var config = {
            type: 'line',
            data: {
                labels: graph_labels,
                datasets: [
                    {
                        label: title,
                        fill: false,
                        lineTension: 0.1,
                        backgroundColor: color,
                        borderColor: color,
                        borderCapStyle: 'butt',
                        borderDash: [],
                        borderDashOffset: 0.0,
                        borderJoinStyle: 'miter',
                        pointBorderColor: color,
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 1,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: color,
                        pointHoverBorderWidth: 2,
                        pointRadius: 1,
                        pointHitRadius: 10,
                        data: dataSet
                    }
                ]
            },
            options: {
                responsive: true,
                hover: {
                    mode: 'label'
                }
            }
        };
        return config;
    }

    function getConfigFeed(color,dataSet,title)
    {
        var config1 = {
            type: 'line',
            data: {
                labels: feed_labels,
                datasets: [
                    {
                        label: title,
                        fill: false,
                        lineTension: 0.1,
                        backgroundColor: color,
                        borderColor: color,
                        borderCapStyle: 'butt',
                        borderDash: [],
                        borderDashOffset: 0.0,
                        borderJoinStyle: 'miter',
                        pointBorderColor: color,
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 1,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: color,
                        pointHoverBorderWidth: 2,
                        pointRadius: 1,
                        pointHitRadius: 10,
                        data: dataSet
                    }
                ]
            },
            options: {
                responsive: true,
                hover: {
                    mode: 'label'
                }
            }
        };
        return config1;
    }


    function drawGraphs()
    {
        var selectedLoc = Number($('#location').val());
        var avgCanvas = document.getElementById("avgChecks-canvas").getContext("2d");
        window.avgLine = new Chart(avgCanvas, getConfig('#ACEC00',graph_avg[selectedLoc],'Avg Check-Ins'));

        var regularCanvas = document.getElementById("regulars-canvas").getContext("2d");
        window.regLine = new Chart(regularCanvas, getConfig('#00BBD6',graph_regulars[selectedLoc],'Regulars'));

        var irregularCanvas = document.getElementById("irregulars-canvas").getContext("2d");
        window.irregLine = new Chart(irregularCanvas, getConfig('#BA65C9',graph_irregulars[selectedLoc],'Irregulars'));

        var lapsersCanvas = document.getElementById("lapsers-canvas").getContext("2d");
        window.lapLine = new Chart(lapsersCanvas, getConfig('#EF3C79',graph_lapsers[selectedLoc],'Lapsers'));
    }
    function drawFeedGraph()
    {
        var selectedLoc = Number($('#location-feed').val());
        var feedWeekCanvas = document.getElementById("feedWeekly-canvas").getContext("2d");
        window.feedLine = new Chart(feedWeekCanvas, getConfigFeed('#EF3C79',feed_locs[selectedLoc],'Weekly Feedback'));
    }
    function refreshGraphs()
    {
        var selectedLoc = Number($('#location').val());
        window.avgLine.config.data.datasets[0].data = graph_avg[selectedLoc];
        window.avgLine.update();

        window.regLine.config.data.datasets[0].data = graph_regulars[selectedLoc];
        window.regLine.update();

        window.irregLine.config.data.datasets[0].data = graph_irregulars[selectedLoc];
        window.irregLine.update();

        window.lapLine.config.data.datasets[0].data = graph_lapsers[selectedLoc];
        window.lapLine.update();
    }
    function refreshFeeds()
    {
        var selectedLoc = Number($('#location-feed').val());
        window.feedLine.config.data.datasets[0].data = feed_locs[selectedLoc];
        window.feedLine.update();
    }

    $(document).on('change','input[name="dashboardStats"]', function(){
        toggleGraphs($(this));
    });
    function toggleGraphs(ele)
    {
        $('.mygraphs').hide();
        if($(ele).val() == 1)
        {
            $('#avgChecks-canvas').show("slow");
        }
        else if($(ele).val() == 2)
        {
            $('#regulars-canvas').show("slow");
        }
        else if($(ele).val() == 3)
        {
            $('#irregulars-canvas').show("slow");
        }
        else
        {
            $('#lapsers-canvas').show("slow");
        }
    }
    toggleGraphs('input[name="dashboardStats"]');
</script>

<!-- Instamojo scripts -->
<script>
    var dialog = document.querySelector('dialog.renew-mug');
    if (! dialog.showModal) {
        dialogPolyfill.registerDialog(dialog);
    }
    $(document).on('click','.confirm-btn', function(){
        dialog.showModal();
    });
    dialog.querySelector('.close').addEventListener('click', function() {
        dialog.close();
    });


    var mugdialog = document.querySelector('dialog.newMug-dialog');
    if (! mugdialog.showModal) {
        dialogPolyfill.registerDialog(mugdialog);
    }
    $(document).on('click','.mug_confirm-btn', function(){
        mugdialog.showModal();
    });
    mugdialog.querySelector('.close').addEventListener('click', function() {
        mugdialog.close();
    });

    function changeCurrent(ele)
    {
        $('#selectedMug').val($(ele).attr('data-id'));
        $('#mugPaymentId').val($(ele).attr('data-paymentId'));
        $('#mugEmail').val($(ele).attr('data-email'));
        $('#mugNum').val($(ele).attr('data-mugId'));
    }

    function changeMugCurrent(ele)
    {
        $('#selectedId').val($(ele).attr('data-id'));
    }

    $(document).on('click','.mug_agree_btn',function(){
        var mugMemId = $('#selectedId').val();
        var ifMail = '0';
        if($('#confirmMail').is(':checked'))
        {
            ifMail = '1';
        }

        var errUrl = base_url+'mugclub/addInstaMug';
        $.ajax({
            type:'POST',
            dataType:'json',
            url:'<?php echo base_url();?>mugclub/addInstaMug',
            data:{memId: mugMemId,ifMail: ifMail},
            success: function(data)
            {
                $('.my-instaMugCard').each(function(i,val){
                    if($(val).attr('data-id') == mugMemId)
                    {
                        mugdialog.close();
                        $(this).fadeOut("fast");
                        return false;
                    }
                });
            },
            error: function(xhr, status, error)
            {
                bootbox.alert('Some Error Occurred!');
                var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                saveErrorLog(err);
            }
        });
    });
    $(document).on('click','.agree_btn',function () {
        var selectedCard = $('#selectedMug').val();
        var paymentId = $('#mugPaymentId').val();
        var mugEmail = $('#mugEmail').val();
        var mugNum = $('#mugNum').val();

        var postData = {
            "mugId": mugNum,
            "invoiceNo": paymentId,
            "mugEmail":mugEmail
        };
        var senderEmail = '<?php echo $this->userEmail;?>';
        bootbox.prompt({
            title: "Please provide your Gmail("+senderEmail+") password",
            inputType: 'password',
            callback: function (result) {
                if(result != null && result != '')
                {
                    showCustomLoader();
                    var senderPass = result;

                    var errUrl = base_url+'mailers/checkGmaillogin';
                    $.ajax({
                        type:'POST',
                        dataType:'json',
                        url: base_url+'mailers/checkGmailLogin',
                        data:{from:senderEmail,fromPass:senderPass},
                        success: function(data)
                        {
                            hideCustomLoader();
                            if(data.status === false)
                            {
                                bootbox.alert('Invalid Gmail Credentials!');
                            }
                            else
                            {
                                postData['senderPass'] = senderPass;
                                postData['senderEmail'] = senderEmail;
                                renewThisMug(postData,selectedCard);
                            }
                        },
                        error: function(xhr,status, error){
                            hideCustomLoader();
                            bootbox.alert('Some Error Occurred!');
                            var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                            saveErrorLog(err);
                        }
                    });
                }
            }
        });


    });

    function renewThisMug(postData,selectedCard)
    {
        var errUrl = base_url+'mugclub/mugRenew/json';
        $.ajax({
            type:"POST",
            dataType:"json",
            url:"<?php echo base_url();?>mugclub/mugRenew/json",
            data:postData,
            success: function(data)
            {
                if(data.status === true)
                {
                    var errUrl = base_url+'dashboard/instadone/json/'+selectedCard;
                    $.ajax({
                        type:"GET",
                        dataType: "json",
                        url:"<?php echo base_url();?>dashboard/instadone/json/"+selectedCard,
                        success: function(data) {
                            if(data.status === true)
                            {

                            }
                            else
                            {
                                bootbox.alert('Try again later!');
                            }
                        },
                        error: function(xhr, status, error){
                            bootbox.alert('Some Error Occurred!');
                            var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                            saveErrorLog(err);
                        }
                    });
                    $('.my-instaCard').each(function(i,val){
                        if($(val).attr('data-id') == selectedCard)
                        {
                            dialog.close();
                            $(this).fadeOut("fast");
                            return false;
                        }
                    });
                }
                else
                {
                    bootbox.alert('Try again later!');
                }
            },
            error: function(xhr, status, error)
            {
                bootbox.alert('Some Error Occurred!');
                var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                saveErrorLog(err);
            }
        });
    }

</script>

<!-- Feedback javascript-->
<script>
    var lastFormNumber = 0;
    var feedbacks = {};
    var errUrl = $(this).attr('action');
    $(document).on('submit','#feedback-form', function(e){
        e.preventDefault();
        showCustomLoader();
        $.ajax({
            type:'POST',
            dataType:'json',
            url: $(this).attr('action'),
            data: $(this).serialize(),
            success: function(data)
            {
                hideCustomLoader();
                if(data.status == true)
                {
                    bootbox.alert('Feedback Saved', function(){
                        window.location.reload();
                    });
                }
                else
                {
                    window.location.href=data.pageUrl;
                }
            },
            error: function(xhr, status, error){
                hideCustomLoader();
                bootbox.alert('Some Error Occurred!');
                var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                saveErrorLog(err);
            }
        });
    });
    $(document).ready(function(){
        $('#genBtn').attr('disabled', true);
    });

    $(document).on('keyup','#feedbackNum', function(){
        if($(this).val() != '' && $(this).val() != 0 && $(this).val() > 0 && $(this).val() < 51)
        {
            $('#genBtn').removeAttr('disabled');
        }
        else
        {
            $('#genBtn').attr('disabled', true);
        }
    });

    $(document).on('click','#genBtn',function(){
        genFeedForm($('#feedbackNum').val());
        $('#feedbackNum').val('');
        $(this).html('Add More');
    });
    function genFeedForm(formLength)
    {
        for(var i = 0; i<formLength;i++)
        {
            var formHtml = '<div class="mdl-grid myFormWrapper">';
            //formHtml += '<ul class="list-inline">';
            formHtml += '<div class="mdl-cell mdl-cell--6-col"><div class="btn-group btn-group-sm">';
            for(var j=1;j<=10;j++)
            {
                if(j==10)
                {
                    formHtml += '<label class="btn btn-default mdl-radio mdl-js-radio mdl-js-ripple-effect">'+
                        '<input type="radio" class="mdl-radio__button" name="overallRating['+lastFormNumber+']" value="'+j+'"/>'+
                        '<span class="mdl-radio__label">'+j+'</span>'+
                        '</label>';
                }
                else
                {
                    formHtml += '<label class="btn btn-default mdl-radio mdl-js-radio mdl-js-ripple-effect">'+
                        '<input type="radio" class="mdl-radio__button" name="overallRating['+lastFormNumber+']" value="'+j+'"/>'+
                        '<span class="mdl-radio__label">'+j+'</span>'+
                        '</label>';
                }

            }
            formHtml += '</div></div>';
            formHtml += '<div class="mdl-cell mdl-cell--3-col">';
            formHtml += '<label class="mdl-radio mdl-js-radio mdl-js-ripple-effect">'+
                            '<input type="radio" class="mdl-radio__button" name="userGender['+lastFormNumber+']" value="M"/>'+
                            '<span class="mdl-radio__label">Male</span>'+
                        '</label>';
            formHtml += '<label class=" mdl-radio mdl-js-radio mdl-js-ripple-effect">'+
                            '<input type="radio" class="mdl-radio__button" name="userGender['+lastFormNumber+']" value="F"/>'+
                            '<span class="mdl-radio__label">Female</span>'+
                        '</label></div>';
            formHtml += '<div class="mdl-cell mdl-cell--3-col">'+
                        '<select class="form-control" name="userAge['+lastFormNumber+']" id="age">'+
                        '<option value="">Age Select</option>';
            for(var k=0;k<=100;k++)
            {
                formHtml += '<option value="'+k+'">'+k+'</option>';
            }
            formHtml += '</select>'+
                        '</div>';
            formHtml += '<input type="hidden" name="feedbackLoc['+lastFormNumber+']" value="'+$('#feedbackLoc').val().trim()+'"/>';
            formHtml += '<button type="button" onclick="removeThis(this)" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored mdl-js-ripple-effect">Remove</button></div>';

            $('#feedback-form .form-super-container').append(formHtml);
            lastFormNumber++;
        }
        if($('#feedback-form button[type="submit"]').hasClass('hide'))
        {
            $('#feedback-form button[type="submit"]').removeClass('hide');
        }
    }

    function removeThis(ele)
    {
        $(ele).parent().animate({
            opacity:0
        },300, function(){
            $(ele).parent().remove();
        });
    }

</script>

<script>
    CKEDITOR.replace( 'itemDesc' );
    function toggleHalf(ele)
    {
        if($(ele).is(':checked'))
        {
            $('.priceHalfCls').removeClass('hide');
        }
        else
        {
            $('.priceHalfCls').addClass('hide');
        }
    }
    var upPanel = 1;
    function addUploadPanel()
    {
        /*'<br><br><label>Attachment Type :</label>'+
        '<label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="attFood'+upPanel+'">'+
        '<input type="radio" id="attFood'+upPanel+'" class="mdl-radio__button" name="attType['+upPanel+']" value="1" checked>'+
        '<span class="mdl-radio__label">Food</span>'+
        '</label>'+
        '<label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="attBeer'+upPanel+'">'+
        '<input type="radio" id="attBeer'+upPanel+'" class="mdl-radio__button" name="attType['+upPanel+']" value="2">'+
        '<span class="mdl-radio__label">Beer Digital</span>'+
        '</label>'+
        '<label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="attBeerW'+upPanel+'">'+
        '<input type="radio" id="attBeerW'+upPanel+'" class="mdl-radio__button" name="attType['+upPanel+']" value="3">'+
        '<span class="mdl-radio__label">Beer Woodcut</span>'+
        '</label>'+*/
        var html = '';
        html += '<br><br><input type="file" multiple class="form-control" onchange="uploadChange(this)" /><br>'+
                '<button onclick="addUploadPanel()" type="button" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">Another?</button>';
        upPanel++;
        $('.myUploadPanel').append(html);
    }
    var filesArr = [];
    function uploadChange(ele)
    {
        $('button[type="submit"]').attr('disabled','true');
        $('.progress').removeClass('hide');
        var xhr = [];
        var totalFiles = ele.files.length;
        for(var i=0;i<totalFiles;i++)
        {
            xhr[i] = new XMLHttpRequest();
            (xhr[i].upload || xhr[i]).addEventListener('progress', function(e) {
                var done = e.position || e.loaded;
                var total = e.totalSize || e.total;
                $('.progress-bar').css('width', Math.round(done/total*100)+'%').attr('aria-valuenow', Math.round(done/total*100)).html(parseInt(Math.round(done/total*100))+'%');
            });
            xhr[i].addEventListener('load', function(e) {
                $('button[type="submit"]').removeAttr('disabled');
            });
            xhr[i].open('post', '<?php echo base_url();?>dashboard/uploadFiles', true);

            var data = new FormData;
            data.append('attachment', ele.files[i]);
            data.append('itemType',$('input[name="itemType"]:checked').val());
            xhr[i].send(data);
            xhr[i].onreadystatechange = function(e) {
                if (e.srcElement.readyState == 4 && e.srcElement.status == 200) {
                    if(e.srcElement.responseText == 'Some Error Occurred!')
                    {
                        bootbox.alert('File size Limit 30MB');
                        return false;
                    }
                    try
                    {
                        var obj = $.parseJSON(e.srcElement.responseText);
                        if(obj.status == false)
                        {
                            bootbox.alert('<label class="my-danger-text">Error: '+obj.errorMsg+'</label>');
                            return false;
                        }
                    }
                    catch(excep)
                    {
                        filesArr.push(e.srcElement.responseText);
                        fillImgs();
                    }
                }
            }
        }
    }
    function fillImgs()
    {
        $('#fnbAdd input[name="attachment"]').val(filesArr.join());
    }
    $(document).on('click', '.beer-tags', function(){
        $('#beerLoc-modal .modal-body .tagged-locations').empty();
        $('#beerLoc-modal .beer-loc-select ul li').each(function(i,val){
            if($(val).hasClass('hide'))
            {
                $(val).removeClass('hide');
            }
        });
        beerLocs = [];
        var fnbId = $(this).attr('data-fnbId');
        $('#beerLoc-modal #fnbId').val(fnbId);
        var errUrl = base_url+'dashboard/beerLocation/'+fnbId;
        showCustomLoader();
        $.ajax({
            type:'GET',
            dataType:"json",
            url:base_url+'dashboard/beerLocation/'+fnbId,
            success: function(data){
                hideCustomLoader();
                if(data.status === true)
                {
                    var newHtml = '<ul class="list-inline">';
                    for(var i=0;i<data.locData.length;i++)
                    {
                        if(data.locData[i].taggedLoc != '')
                        {
                            beerLocs.push(data.locData[i].id);
                            newHtml += '<li class="loc-info" data-value="'+data.locData[i].id+'"><span>'+data.locData[i].locName+'</span>'+
                                '<i class="fa fa-times"></i></li>';
                            $('#beerLoc-modal .beer-loc-select ul li').each(function(h,val){
                                if($(val).attr('data-value') == data.locData[i].id)
                                {
                                    $(val).addClass('hide');
                                }

                            });
                        }
                    }
                    newHtml += '</ul>';
                    $('#beerLoc-modal .modal-body .tagged-locations').html(newHtml);
                }
                $('#beerLoc-modal').modal('show');
            },
            error: function(xhr, status, error){
                hideCustomLoader();
                bootbox.alert('Some Error Occurred!');
                var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                saveErrorLog(err);
            }
        });
    });

    var beerLocs = [];
    $(document).on('click','#beerLoc-modal .beer-loc-select ul li', function(){
        var locVal = $(this).attr('data-value');
        beerLocs.push(locVal);
        $(this).addClass('hide');
        var tagLocHtml = '';
        if($('#beerLoc-modal .modal-body .tagged-locations ul').length == 0)
        {
            tagLocHtml += '<ul class="list-inline"><li class="loc-info" data-value="'+locVal+'"><span>'+$(this).html()+'&nbsp;</span>'+
                    '<i class="fa fa-times"></i></li></ul>';
            $('#beerLoc-modal .modal-body .tagged-locations').html(tagLocHtml);
        }
        else
        {
            tagLocHtml += '<li class="loc-info" data-value="'+locVal+'"><span>'+$(this).html()+'</span>'+
                '<i class="fa fa-times"></i></li>';
            $('#beerLoc-modal .modal-body .tagged-locations ul').append(tagLocHtml);
        }
    });
    $(document).on('click','#beerLoc-modal .loc-info i', function(){
        var locVal = $(this).parent().attr('data-value');
        beerLocs.splice( $.inArray(locVal,beerLocs) ,1 );
        $(this).parent().addClass('hide');

        $('#beerLoc-modal .beer-loc-select ul li').each(function(i,val){
            console.log(locVal);
            if($(val).attr('data-value') == locVal)
            {
                $(val).removeClass('hide');
            }
        });
    });
    $(document).on('click',".save-fnb-tags", function () {
        var postData = '';
        if(typeof beerLocs[0] == 'undefined')
        {
            postData = {'taggedLoc':null};
        }
        else
        {
            postData = {'taggedLoc':beerLocs.join(',')};
        }
        showCustomLoader();

        var errUrl = base_url+'dashboard/fnbTagSet/'+$('#beerLoc-modal #fnbId').val();
        $.ajax({
            type:'POST',
            dataType:'json',
            url:base_url+'dashboard/fnbTagSet/'+$('#beerLoc-modal #fnbId').val(),
            data:postData,
            success: function(data){
                hideCustomLoader();
                if(data.status === true)
                {
                    $('#beerLoc-modal').modal('hide');
                }
                else
                {
                    bootbox.alert('Some Error Occurred!');
                }

            },
            error: function(xhr, status, error){
                hideCustomLoader();
                bootbox.alert('Some Error Occurred!');
                var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                saveErrorLog(err);
            }
        });
    });
</script>

<script>
    //CKEDITOR.replace('eventDescription');
    var date = new Date();
    $('#eventDate').datetimepicker({
        format: 'YYYY-MM-DD',
        minDate: date
    });
    $('#startTime, #endTime').datetimepicker({
        format: 'HH:mm'
    });
    $(document).on('change','#eventType', function(){
        if($(this).find('option:checked').val() != 'Others')
        {
            $(this).attr('name','eventType');
            $('.other-event').addClass('hide');
            $('.other-event input').removeAttr('name');
        }
        else
        {
            $(this).removeAttr('name');
            $('.other-event').removeClass('hide');
            $('.other-event input').attr('name','eventType');
        }
    });
    
    var filesEventsArr = [];
    function eventUploadChange(ele)
    {

        $('#eventpanel #eventAdd button[type="submit"]').attr('disabled','true');
        $('#eventpanel #eventAdd .progress').removeClass('hide');
        var xhr = [];
        var totalFiles = ele.files.length;
        for(var i=0;i<totalFiles;i++)
        {
            xhr[i] = new XMLHttpRequest();
            (xhr[i].upload || xhr[i]).addEventListener('progress', function(e) {
                var done = e.position || e.loaded;
                var total = e.totalSize || e.total;
                $('#eventAdd .progress-bar').css('width', Math.round(done/total*100)+'%').attr('aria-valuenow', Math.round(done/total*100)).html(parseInt(Math.round(done/total*100))+'%');
            });
            xhr[i].addEventListener('load', function(e) {
                $('#eventpanel #eventAdd button[type="submit"]').removeAttr('disabled');
            });
            xhr[i].open('post', '<?php echo base_url();?>dashboard/uploadEventFiles', true);

            var data = new FormData;
            data.append('attachment', ele.files[i]);
            xhr[i].send(data);
            xhr[i].onreadystatechange = function(e) {
                if (e.srcElement.readyState == 4 && e.srcElement.status == 200) {
                    if(e.srcElement.responseText == 'Some Error Occurred!')
                    {
                        bootbox.alert('File size Limit 30MB');
                        return false;
                    }
                    try
                    {
                        var obj = $.parseJSON(e.srcElement.responseText);
                        if(obj.status == false)
                        {
                            bootbox.alert('<label class="my-danger-text">Error: '+obj.errorMsg+'</label>');
                            return false;
                        }
                    }
                    catch(excep)
                    {
                        filesEventsArr.push(e.srcElement.responseText);
                        fillEventImgs();
                    }
                }
            }
        }
    }

    function fillEventImgs()
    {
        if(filesEventsArr.length > 0)
        {
            $('#eventpanel input[name="attachment"]').val(filesEventsArr.join());
        }
    }
    $('#main-comp-event-table').DataTable({
        "ordering": false
    });

    $('[data-toggle="tooltip"]').tooltip();
    $(document).on('click','.view-photos', function(){
        var pics = $(this).attr('data-imgs').split(',');
        if(typeof pics != 'undefined')
        {
            var newPics = [];
            for(var i=0;i<pics.length;i++)
            {
                var temp = {href:pics[i],title:''};
                newPics[i] = temp;
            }
            $.swipebox( newPics );
        }
    });
    $(document).on('click','.eventDelete-icon',function(){
        var mugId = $(this).attr('data-eventId');
        bootbox.confirm("Are you sure you want to delete event #"+mugId+" ?", function(result) {
            if(result === true)
            {
                window.location.href='<?php echo base_url();?>dashboard/deleteEvent/'+mugId;
            }
        });
    });
    $(document).on('click','.eventCompletedDelete-icon',function(){
        var mugId = $(this).attr('data-eventId');
        bootbox.confirm("Are you sure you want to delete event #"+mugId+" ?", function(result) {
            if(result === true)
            {
                window.location.href='<?php echo base_url();?>dashboard/deleteCompEvent/'+mugId;
            }
        });
    });
    $(document).on('click','.fnbDelete-icon',function(){
        var mugId = $(this).attr('data-fnbId');
        bootbox.confirm("Are you sure you want to delete Item #"+mugId+" ?", function(result) {
            if(result === true)
            {
                window.location.href='<?php echo base_url();?>dashboard/deleteFnb/'+mugId;
            }
        });
    });
</script>
<script>
    $(document).on('click','.even-tracker', function(){
        localStorageUtil.setLocal('tabEventPage',evenTable.page());
    });
    $(document).on('click','.fnb-tracker', function(){
        localStorageUtil.setLocal('tabFnbPage',fnbTable.page());
    });

    var filesMetaArr = [];
    var filesOlympicsArr = [];
    function metaUploadChange(ele)
    {

        $('#metaTab button[type="submit"]').attr('disabled','true');
        $('#metaTab .progress').removeClass('hide');
        var xhr = [];
        var totalFiles = ele.files.length;
        for(var i=0;i<totalFiles;i++)
        {
            xhr[i] = new XMLHttpRequest();
            (xhr[i].upload || xhr[i]).addEventListener('progress', function(e) {
                var done = e.position || e.loaded;
                var total = e.totalSize || e.total;
                $('#metaTab .progress-bar').css('width', Math.round(done/total*100)+'%').attr('aria-valuenow', Math.round(done/total*100)).html(parseInt(Math.round(done/total*100))+'%');
            });
            xhr[i].addEventListener('load', function(e) {
                $('#metaTab button[type="submit"]').removeAttr('disabled');
            });
            xhr[i].open('post', '<?php echo base_url();?>dashboard/uploadMetaFiles', true);

            var data = new FormData;
            data.append('attachment', ele.files[i]);
            xhr[i].send(data);
            xhr[i].onreadystatechange = function(e) {
                if (e.srcElement.readyState == 4 && e.srcElement.status == 200) {
                    if(e.srcElement.responseText == 'Some Error Occurred!')
                    {
                        bootbox.alert('File size Limit 30MB');
                        return false;
                    }
                    try
                    {
                        var obj = $.parseJSON(e.srcElement.responseText);
                        if(obj.status == false)
                        {
                            bootbox.alert('<label class="my-danger-text">Error: '+obj.errorMsg+'</label>');
                            return false;
                        }
                    }
                    catch(excep)
                    {
                        filesMetaArr = [];
                        filesMetaArr.push(e.srcElement.responseText);
                        fillMetaImgs();
                    }
                }
            }
        }
    }

    function fillMetaImgs()
    {
        $('#metaTab input[name="metaImg"]').val(filesMetaArr.join());
    }

    function beerUploadChange(ele)
    {

        $('#beerTab button[type="submit"]').attr('disabled','true');
        $('#beerTab .progress').removeClass('hide');
        var xhr = [];
        var totalFiles = ele.files.length;
        for(var i=0;i<totalFiles;i++)
        {
            xhr[i] = new XMLHttpRequest();
            (xhr[i].upload || xhr[i]).addEventListener('progress', function(e) {
                var done = e.position || e.loaded;
                var total = e.totalSize || e.total;
                $('#beerTab .progress-bar').css('width', Math.round(done/total*100)+'%').attr('aria-valuenow', Math.round(done/total*100)).html(parseInt(Math.round(done/total*100))+'%');
            });
            xhr[i].addEventListener('load', function(e) {
                $('#beerTab button[type="submit"]').removeAttr('disabled');
            });
            xhr[i].open('post', '<?php echo base_url();?>dashboard/uploadMetaFiles', true);

            var data = new FormData;
            data.append('attachment', ele.files[i]);
            xhr[i].send(data);
            xhr[i].onreadystatechange = function(e) {
                if (e.srcElement.readyState == 4 && e.srcElement.status == 200) {
                    if(e.srcElement.responseText == 'Some Error Occurred!')
                    {
                        bootbox.alert('File size Limit 30MB');
                        return false;
                    }
                    try
                    {
                        var obj = $.parseJSON(e.srcElement.responseText);
                        if(obj.status == false)
                        {
                            bootbox.alert('<label class="my-danger-text">Error: '+obj.errorMsg+'</label>');
                            return false;
                        }
                    }
                    catch(excep)
                    {
                        filesOlympicsArr.push(e.srcElement.responseText);
                        fillOlympicsImgs();
                    }
                }
            }
        }
    }

    function fillOlympicsImgs()
    {
        $('#beerTab input[name="olympicsImg"]').val(filesOlympicsArr.join());
    }
</script>

<script>
    $(document).on('submit','#dashboardEventAdd', function(e){
        e.preventDefault();
        var eventVar = $(this);
        if($(this).find('#eventName').val() == '')
        {
            bootbox.alert('Event Name required!');
            return false;
        }
        if($(this).find('#eventDescription').val() == '')
        {
            bootbox.alert('Event Description required!');
            return false;
        }
        /*var d = new Date($(this).find('#eventDate').val());
        var startT = $(this).find('#startTime').val();
        var endT = $(this).find('#endTime').val();

        if(startT > endT)
        {
            bootbox.alert('Event Time is not proper!');
            return false;
        }*/
        if($(this).find('input[name="attachment"]').val() == '')
        {
            bootbox.alert('Event Image Required!');
            return false;
        }
        if($(this).find('#creatorName').val() == '' &&
            $(this).find('#creatorPhone').val() == '' &&
            $(this).find('#creatorEmail').val() == '')
        {
            bootbox.alert('Organizer details required!');
            return false;
        }

        var senderEmail = $(this).find('#senderEmail').val();
        bootbox.prompt({
            title: "Please provide your Gmail("+senderEmail+") password",
            inputType: 'password',
            callback: function (result) {
                if(result != null && result != '')
                {
                    showCustomLoader();
                    var senderPass = result;
                    var errUrl = base_url+'mailers/checkGmailLogin';
                    $.ajax({
                        type:'POST',
                        dataType:'json',
                        url: base_url+'mailers/checkGmailLogin',
                        data:{from:senderEmail,fromPass:senderPass},
                        success: function(data)
                        {
                            hideCustomLoader();
                            if(data.status === false)
                            {
                                bootbox.alert('Invalid Gmail Credentials!');
                            }
                            else
                            {
                                var errUrl = $(eventVar).attr('action');
                                $(eventVar).find('#senderPass').val(senderPass);
                                showCustomLoader();
                                $.ajax({
                                    type:"POST",
                                    dataType:'json',
                                    url: $(eventVar).attr('action'),
                                    data: $(eventVar).serialize(),
                                    success: function(data){
                                        hideCustomLoader();
                                        if(data.status === true)
                                        {
                                            window.location.reload();
                                        }
                                        else
                                        {
                                            bootbox.alert(data.errorMsg);
                                        }
                                    },
                                    error: function(xhr, status, error){
                                        hideCustomLoader();
                                        bootbox.alert('Some Error Occurred!');
                                        var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                                        saveErrorLog(err);
                                    }
                                });
                            }
                        },
                        error: function(xhr, status, error){
                            hideCustomLoader();
                            bootbox.alert('Some Error Occurred!');
                            var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                            saveErrorLog(err);
                        }
                    });
                }
            }
        });

    });

    function exporttocsv() {

        if($('#peopleView-modal .signup-for-download').html() != '')
        {
            //var excelTxt = $('#peopleView-modal .signup-for-download').html();
            //$('#peopleView-modal .signup-tab').find('table').removeClass('table').removeClass('table-striped');
            var a = document.createElement('a');
            with (a) {
                href='data:application/vnd.ms-excel,' + $('#peopleView-modal .signup-for-download').html();
                download="<?php echo date('d/m/Y');?>_signups.xls";
            }
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }
    }
    $(document).on('click','.eventSignups-icon', function(){
        var eventId = $(this).attr('data-eventId');
        var eventName = $(this).attr('data-eventName');
        var errUrl = base_url+'dashboard/getSignupList/'+eventId;
        showCustomLoader();
        $.ajax({
            type:'GET',
            dataType:'json',
            url:base_url+'dashboard/getSignupList/'+eventId,
            success: function(data){
                hideCustomLoader();
                if(data.status === true)
                {
                    $('#peopleView-modal .modal-body').html('');
                    $('#peopleView-modal .eventName').html(eventName);
                    $('#peopleView-modal .modal-body').append('<p class="alert-success">Doolally Signups</p>');
                    if(typeof data.joinData !== 'undefined' && data.joinData != null && data.joinData.length != 0)
                    {
                        var downTbl = '<table>';
                        var tblHtml = '<table class="table table-striped">';
                        downTbl += '<thead><tr><th>Doolally Signups</th></tr><tr><th>Name</th><th>Email</th><th>Mobile Number</th><th>Quantitiy</th><th>Signup Date/time</th>';
                        downTbl += '</tr></thead><tbody>';
                        tblHtml += '<thead><tr><th>Name</th><th>Email</th><th>Mobile Number</th><th>Quantity</th><th>Signup Date/time</th>';
                        tblHtml += '</tr></thead><tbody>';
                        for(var i=0;i<data.joinData.length;i++)
                        {
                            downTbl += '<tr>';
                            if(eventId == '<?php echo SPECIAL_EVENT_DOOLALLYID;?>' && data.joinData[i].regPrice != null)
                            {
                                downTbl += '<td>'+data.joinData[i].firstName+' '+data.joinData[i].lastName+'(Rs '+data.joinData[i].regPrice+')'+'</td>';
                            }
                            else
                            {
                                downTbl += '<td>'+data.joinData[i].firstName+' '+data.joinData[i].lastName+'</td>';
                            }
                            downTbl += '<td>'+data.joinData[i].emailId+'</td>';
                            downTbl += '<td>'+data.joinData[i].mobNum+'</td>';
                            downTbl += '<td>'+data.joinData[i].quantity+'</td>';
                            downTbl += '<td>'+formatJsDate(data.joinData[i].createdDT)+'</td>';
                            downTbl += '</tr>';

                            tblHtml += '<tr>';
                            if(eventId == '<?php echo SPECIAL_EVENT_DOOLALLYID;?>' && data.joinData[i].regPrice != null)
                            {
                                tblHtml += '<td>'+data.joinData[i].firstName+' '+data.joinData[i].lastName+'(Rs '+data.joinData[i].regPrice+')'+'</td>';
                            }
                            else
                            {
                                tblHtml += '<td>'+data.joinData[i].firstName+' '+data.joinData[i].lastName+'</td>';
                            }
                            tblHtml += '<td>'+data.joinData[i].emailId+'</td>';
                            tblHtml += '<td>'+data.joinData[i].mobNum+'</td>';
                            tblHtml += '<td>'+data.joinData[i].quantity+'</td>';
                            tblHtml += '<td>'+formatJsDate(data.joinData[i].createdDT)+'</td>';
                            tblHtml += '</tr>';
                        }
                        if(typeof data.EHData === 'undefined' && typeof data.reminderData === 'undefined')
                        {
                            downTbl += '</tbody></table>';
                            $('#peopleView-modal .signup-for-download').html(downTbl);
                        }
                        tblHtml += '</tbody></table>';
                        $('#peopleView-modal .modal-body').append(tblHtml);
                        $('#peopleView-modal .exportToExcel').removeClass('hide');
                    }
                    else
                    {
                        $('#peopleView-modal .modal-body').append('No Sign ups');
                        $('#peopleView-modal .exportToExcel').addClass('hide');
                    }

                    $('#peopleView-modal .modal-body').append('<p class="alert-info">EventsHigh Signups</p>');
                    if(typeof data.EHData !== 'undefined' && data.EHData != null && data.EHData.length != 0)
                    {
                        var tblHtml1 = '<table class="table table-striped">';
                        downTbl += '<tr><th>EventsHigh Signups</th></tr>';
                        tblHtml1 += '<thead><tr><th>Name</th><th>Email</th><th>Mobile Number</th><th>Quantity</th><th>Signup Date/time</th>';
                        tblHtml1 += '</tr></thead><tbody>';
                        for(var j=0;j<data.EHData.length;j++)
                        {
                            downTbl += '<tr>';
                            if(eventId == '<?php echo SPECIAL_EVENT_DOOLALLYID;?>' && data.EHData[j].regPrice != null)
                            {
                                downTbl += '<td>'+data.EHData[j].firstName+' '+data.EHData[j].lastName+'(Rs '+data.EHData[j].regPrice+')'+'</td>';
                            }
                            else
                            {
                                downTbl += '<td>'+data.EHData[j].firstName+' '+data.EHData[j].lastName+'</td>';
                            }
                            downTbl += '<td>'+data.EHData[j].emailId+'</td>';
                            downTbl += '<td>'+data.EHData[j].mobNum+'</td>';
                            downTbl += '<td>'+data.EHData[j].quantity+'</td>';
                            downTbl += '<td>'+formatJsDate(data.EHData[j].createdDT)+'</td>';
                            downTbl += '</tr>';

                            tblHtml1 += '<tr>';
                            if(eventId == '<?php echo SPECIAL_EVENT_DOOLALLYID;?>' && data.EHData[j].regPrice != null)
                            {
                                tblHtml1 += '<td>'+data.EHData[j].firstName+' '+data.EHData[j].lastName+'(Rs '+data.EHData[j].regPrice+')'+'</td>';
                            }
                            else
                            {
                                tblHtml1 += '<td>'+data.EHData[j].firstName+' '+data.EHData[j].lastName+'</td>';
                            }
                            tblHtml1 += '<td>'+data.EHData[j].emailId+'</td>';
                            tblHtml1 += '<td>'+data.EHData[j].mobNum+'</td>';
                            tblHtml1 += '<td>'+data.EHData[j].quantity+'</td>';
                            tblHtml1 += '<td>'+formatJsDate(data.EHData[j].createdDT)+'</td>';
                            tblHtml1 += '</tr>';
                        }
                        if(typeof data.canData === 'undefined')
                        {
                            downTbl += '</tbody></table>';
                            $('#peopleView-modal .signup-for-download').html(downTbl);
                        }
                        tblHtml1 += '</tbody></table>';
                        $('#peopleView-modal .modal-body').append(tblHtml1);
                    }
                    else
                    {
                        $('#peopleView-modal .modal-body').append('No Sign ups');
                    }

                    $('#peopleView-modal .modal-body').append('<p class="alert-info">Cancel List</p>');
                    if(typeof data.canData !== 'undefined' && data.canData != null && data.canData.length != 0)
                    {
                        var tblHtml3 = '<table class="table table-striped">';
                        downTbl += '<tr><th>Cancel List</th></tr>';
                        tblHtml3 += '<thead><tr><th>Name</th><th>Email</th><th>Mobile Number</th><th>Quantity</th><th>Gateway</th><th>Signup Date/time</th>';
                        tblHtml3 += '</tr></thead><tbody>';
                        for(var l=0;l<data.canData.length;l++)
                        {
                            downTbl += '<tr>';
                            if(eventId == '<?php echo SPECIAL_EVENT_DOOLALLYID;?>' && data.canData[l].regPrice != null)
                            {
                                downTbl += '<td>'+data.canData[l].firstName+' '+data.canData[l].lastName+'(Rs '+data.canData[l].regPrice+')'+'</td>';
                            }
                            else
                            {
                                downTbl += '<td>'+data.canData[l].firstName+' '+data.canData[l].lastName+'</td>';
                            }
                            downTbl += '<td>'+data.canData[l].emailId+'</td>';
                            downTbl += '<td>'+data.canData[l].mobNum+'</td>';
                            downTbl += '<td>'+data.canData[l].quantity+'</td>';
                            if(data.canData[l].isDirectlyRegistered == '1')
                            {
                                downTbl += '<td>Doolally</td>';
                            }
                            else
                            {
                                downTbl += '<td>Eventshigh</td>';
                            }
                            downTbl += '<td>'+formatJsDate(data.canData[l].createdDT)+'</td>';
                            downTbl += '</tr>';

                            tblHtml3 += '<tr>';
                            if(eventId == '<?php echo SPECIAL_EVENT_DOOLALLYID;?>' && data.canData[l].regPrice != null)
                            {
                                tblHtml3 += '<td>'+data.canData[l].firstName+' '+data.canData[l].lastName+'(Rs '+data.canData[l].regPrice+')'+'</td>';
                            }
                            else
                            {
                                tblHtml3 += '<td>'+data.canData[l].firstName+' '+data.canData[l].lastName+'</td>';
                            }
                            tblHtml3 += '<td>'+data.canData[l].emailId+'</td>';
                            tblHtml3 += '<td>'+data.canData[l].mobNum+'</td>';
                            tblHtml3 += '<td>'+data.canData[l].quantity+'</td>';
                            if(data.canData[l].isDirectlyRegistered == '1')
                            {
                                tblHtml3 += '<td>Doolally</td>';
                            }
                            else
                            {
                                tblHtml3 += '<td>Eventshigh</td>';
                            }
                            tblHtml3 += '<td>'+formatJsDate(data.canData[l].createdDT)+'</td>';
                            tblHtml3 += '</tr>';
                        }
                        if(typeof data.reminderData === 'undefined')
                        {
                            downTbl += '</tbody></table>';
                            $('#peopleView-modal .signup-for-download').html(downTbl);
                        }
                        tblHtml3 += '</tbody></table>';
                        $('#peopleView-modal .modal-body').append(tblHtml3);
                    }
                    else
                    {
                        $('#peopleView-modal .modal-body').append('No Cancel List');
                    }

                    $('#peopleView-modal .modal-body').append('<p class="alert-info">Events Reminders</p>');
                    if(typeof data.reminderData !== 'undefined' && data.reminderData != null && data.reminderData.length != 0)
                    {
                        var tblHtml2 = '<table class="table table-striped">';
                        downTbl += '<tr><th>Events Reminders</th></tr>';
                        tblHtml2 += '<thead><tr><th>Email</th><th>Signup Date/time</th>';
                        tblHtml2 += '</tr></thead><tbody>';
                        for(var k=0;k<data.reminderData.length;k++)
                        {
                            downTbl += '<tr>';
                            downTbl += '<td>'+data.reminderData[k].emailId+'</td>';
                            downTbl += '<td>'+formatJsDate(data.reminderData[k].insertedDT)+'</td>';
                            downTbl += '</tr>';

                            tblHtml2 += '<tr>';
                            tblHtml2 += '<td>'+data.reminderData[k].emailId+'</td>';
                            tblHtml2 += '<td>'+formatJsDate(data.reminderData[k].insertedDT)+'</td>';
                            tblHtml2 += '</tr>';
                        }
                        downTbl += '</tbody></table>';
                        $('#peopleView-modal .signup-for-download').html(downTbl);
                        tblHtml2 += '</tbody></table>';
                        $('#peopleView-modal .modal-body').append(tblHtml2);
                    }
                    else
                    {
                        $('#peopleView-modal .modal-body').append('No Sign ups');
                    }

                    $('#peopleView-modal').modal('show');
                }
            },
            error: function(xhr, status, error){
                hideCustomLoader();
                bootbox.alert('Some Error Occurred!');
                var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                saveErrorLog(err);
            }
        });
    });

    $(document).on('submit','#meta-event-form',function(e){
        e.preventDefault();
        if($(this).find('#shareTitle').val() == '')
        {
            bootbox.alert('Meta Title required!');
            return false;
        }
        if($(this).find('#shareDesc').val() == '')
        {
            bootbox.alert('Meta Description required!');
            return false;
        }
        if($(this).find('input[name="metaImg"]').val() == '')
        {
            bootbox.alert('Meta Image required!');
            return false;
        }
        showCustomLoader();
        var errUrl = $(this).attr('action');
        $.ajax({
            type:"POST",
            dataType:'json',
            url: $(this).attr('action'),
            data: $(this).serialize(),
            success: function(data){
                hideCustomLoader();
                if(data.status === true)
                {
                    window.location.reload();
                }
                else
                {
                    bootbox.alert(data.errorMsg);
                }
            },
            error: function(xhr, status, error){
                hideCustomLoader();
                bootbox.alert('Some Error Occurred!');
                var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                saveErrorLog(err);
            }
        });
    });

    $(document).on('submit','#beer-olympics-form',function(e){
        e.preventDefault();
        if($(this).find('#olympicsTitle').val() == '')
        {
            bootbox.alert('Meta Title required!');
            return false;
        }
        if($(this).find('#olympicsDesc').val() == '')
        {
            bootbox.alert('Meta Description required!');
            return false;
        }
        if($(this).find('input[name="olympicsImg"]').val() == '')
        {
            bootbox.alert('Meta Image required!');
            return false;
        }
        showCustomLoader();
        var errUrl = $(this).attr('action');
        $.ajax({
            type:"POST",
            dataType:'json',
            url: $(this).attr('action'),
            data: $(this).serialize(),
            success: function(data){
                hideCustomLoader();
                if(data.status === true)
                {
                    window.location.reload();
                }
                else
                {
                    bootbox.alert(data.errorMsg);
                }
            },
            error: function(xhr, status, error){
                hideCustomLoader();
                bootbox.alert('Some Error Occurred!');
                var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                saveErrorLog(err);
            }
        });
    });
</script>

<script>
    var eveApprovUrl, costPrice, paid1Price, paid2Price, doolallyFee;
    $(document).on('click','#eventView .approveThis-event', function(){
        var eveApprovType = $(this).attr('data-costType');
        eveApprovUrl = $(this).attr('data-url');
        costPrice = $(this).attr('data-costPrice');
        $('#eventPrice-modal input[name="costType"]').each(function(i,val){

            if($(val).val() != eveApprovType)
            {
                $(val).prop('checked', false);
                if(eveApprovType != '1')
                {
                    $('#eventPrice-modal #costPrice').parent().removeClass('hide');
                }
                else
                {
                    $('#eventPrice-modal #costPrice').parent().addClass('hide');
                }
            }
            else
            {
                $(val).prop('checked',true);
            }
        });
        $('#eventPrice-modal #costPrice').val(costPrice);
        if(eveApprovType == '2')
        {
            paid1Price = Number(costPrice);
            paid2Price = Number(costPrice) - <?php echo NEW_DOOLALLY_FEE;?>;
        }
        else if(eveApprovType == '3' || eveApprovType == '4')
        {
            paid1Price = Number(costPrice) + <?php echo NEW_DOOLALLY_FEE;?>;
            paid2Price = Number(costPrice);
        }
        else
        {
            paid1Price = costPrice;
            paid2Price = costPrice;
        }
        $('#eventPrice-modal').modal('show');
    });

    $(document).on('click','#eventView .declineThis-event', function(){
        var declineUrl = $(this).attr('data-url');
        var senderEmail = $('#eventView #senderEmail').val();

        bootbox.prompt({
            title: "Please provide your Gmail("+senderEmail+") password",
            inputType: 'password',
            callback: function (result) {
                if(result != null && result != '')
                {
                    var errUrl = base_url+'mailers/checkGmailLogin';
                    showCustomLoader();
                    var senderPass = result;
                    $.ajax({
                        type:'POST',
                        dataType:'json',
                        url: base_url+'mailers/checkGmailLogin',
                        data:{from:senderEmail,fromPass:senderPass},
                        success: function(data)
                        {
                            hideCustomLoader();
                            if(data.status === false)
                            {
                                bootbox.alert('Invalid Gmail Credentials!');
                            }
                            else
                            {
                                var errUrl = declineUrl;
                                showCustomLoader();
                                $.ajax({
                                    type:'POST',
                                    dataType:'json',
                                    url: declineUrl,
                                    data:{from:senderEmail,fromPass:senderPass},
                                    success: function(data){
                                        hideCustomLoader();
                                        if(data.status == true)
                                        {
                                            window.location.reload();
                                        }
                                        else
                                        {
                                            bootbox.alert(data.errorMsg, function(){
                                                window.location.reload();
                                            });
                                        }
                                    },
                                    error: function(xhr, status, error){
                                        hideCustomLoader();
                                        bootbox.alert('Some Error Occurred!');
                                        var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                                        saveErrorLog(err);
                                    }
                                });
                            }
                        },
                        error: function(xhr, status, error){
                            hideCustomLoader();
                            bootbox.alert('Some Error Occurred!');
                            var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                            saveErrorLog(err);
                        }
                    });
                }
            }
        });
    });

    $(document).on('click','#eventPrice-modal .save-event-price', function(){
        var selectedType = $('#eventPrice-modal input[name="costType"]:checked').val();
        var costEntered = $('#eventPrice-modal #costPrice').val();
        var customFee = Number($('#eventPrice-modal #customPrice').val());
        if(selectedType != '1')
        {
            if(costEntered == '' || costEntered == 0)
            {
                bootbox.alert('<label class="my-danger-text">Please Provide the Event Price!</label>');
                return false;
            }
        }
        if(isDirectCostChange)
        {
            var errUrl = eveApprovUrl;
            showCustomLoader();
            $.ajax({
                type:'POST',
                dataType:'json',
                url: eveApprovUrl,
                data:{costType: $('#eventPrice-modal input[name="costType"]:checked').val(),
                    costPrice: costEntered,
                    doolallyFee: customFee},
                success: function(data){
                    hideCustomLoader();
                    if(data.status == true)
                    {
                        if(typeof data.meetupError !== 'undefined')
                        {
                            bootbox.alert('Meetup Error: '+data.meetupError);
                        }
                        if(typeof data.apiData !== 'undefined')
                        {
                            createEventsHigh(data.apiData);
                        }
                        else
                        {
                            window.location.reload();
                        }
                    }
                    else
                    {
                        bootbox.alert(data.errorMsg, function(){
                            window.location.reload();
                        });
                    }
                },
                error: function(xhr, status, error){
                    hideCustomLoader();
                    bootbox.alert('Some Error Occurred!');
                    var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                    saveErrorLog(err);
                }
            });
        }
        else
        {
            var senderEmail = $('#eventView #senderEmail').val();
            bootbox.prompt({
                title: "Please provide your Gmail("+senderEmail+") password",
                inputType: 'password',
                callback: function (result) {
                    if(result != null && result != '')
                    {
                        var errUrl = base_url+'mailers/checkGmailLogin';
                        showCustomLoader();
                        var senderPass = result;
                        $.ajax({
                            type:'POST',
                            dataType:'json',
                            url: base_url+'mailers/checkGmailLogin',
                            data:{from:senderEmail,fromPass:senderPass},
                            success: function(data)
                            {
                                hideCustomLoader();
                                if(data.status === false)
                                {
                                    bootbox.alert('Invalid Gmail Credentials!');
                                }
                                else
                                {
                                    var errUrl = eveApprovUrl;
                                    showCustomLoader();
                                    $.ajax({
                                        type:'POST',
                                        dataType:'json',
                                        url: eveApprovUrl,
                                        data:{costType: $('#eventPrice-modal input[name="costType"]:checked').val(),
                                            costPrice: costEntered,
                                            doolallyFee: customFee,
                                            from:senderEmail,fromPass:senderPass},
                                        success: function(subData){
                                            hideCustomLoader();
                                            if(subData.status == true)
                                            {
                                                if(typeof subData.meetupError !== 'undefined')
                                                {
                                                    bootbox.alert('Meetup Error: '+subData.meetupError);
                                                }
                                                if(typeof subData.apiData !== 'undefined')
                                                {
                                                    createEventsHigh(subData.apiData);
                                                }
                                                else
                                                {
                                                    window.location.reload();
                                                }
                                            }
                                            else
                                            {
                                                bootbox.alert(subData.errorMsg, function(){
                                                    window.location.reload();
                                                });
                                            }
                                        },
                                        error: function(xhr, status, error){
                                            hideCustomLoader();
                                            bootbox.alert('Some Error Occurred!');
                                            var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                                            saveErrorLog(err);
                                        }
                                    });
                                }
                            },
                            error: function(xhr, status, error){
                                hideCustomLoader();
                                bootbox.alert('Some Error Occurred!');
                                var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                                saveErrorLog(err);
                            }
                        });
                    }
                }
            });
        }

    });
    var fixDoolallyFee = <?php echo NEW_DOOLALLY_FEE;?>;

    var isDirectCostChange = false
    $(document).on('click','#eventView .eventCostChange-icon', function(){
       var eveApprovType = $(this).attr('data-costType');
        eveApprovUrl = $(this).attr('data-url');
        costPrice = $(this).attr('data-costPrice');
        doolallyFee = <?php echo NEW_DOOLALLY_FEE;?>; // Number($(this).attr('data-doolallyFee'));
        isDirectCostChange = true;
        $('#eventPrice-modal input[name="costType"]').each(function(i,val){

            if($(val).val() != eveApprovType)
            {
                $(val).prop('checked', false);
                if(eveApprovType != '1')
                {
                    $('#eventPrice-modal #costPrice').parent().removeClass('hide');
                }
                else
                {
                    $('#eventPrice-modal #costPrice').parent().addClass('hide');
                }
            }
            else
            {
                $(val).prop('checked',true);
            }
        });
        $('#eventPrice-modal #costPrice').val(costPrice);
        $('#eventPrice-modal #customPrice').val(doolallyFee);
        if(eveApprovType == '2')
        {
            paid1Price = Number(costPrice);

            paid2Price = Number(costPrice) - doolallyFee;
        }
        else if(eveApprovType == '3' || eveApprovType == '4')
        {
            paid1Price = Number(costPrice) + doolallyFee;
            paid2Price = Number(costPrice);
        }
        else
        {
            paid1Price = costPrice;
            paid2Price = costPrice;
        }
        $('#eventPrice-modal').modal('show');

    });

    $(document).on("keyup","#eventPrice-modal #customPrice", function(){
        var oldFee = doolallyFee;
        if(Number($(this).val()) >= <?php echo NEW_DOOLALLY_FEE;?>)
        {
            doolallyFee = Number($(this).val());
        }
        else
        {
            doolallyFee = <?php echo NEW_DOOLALLY_FEE;?>;
        }
        var basicPrice = Number($('#eventPrice-modal #costPrice').val());
        if($('#eventPrice-modal input[name="costType"]:checked').val() == '2')
        {
            paid1Price = basicPrice + (doolallyFee - oldFee);
            $('#eventPrice-modal #costPrice').val(paid1Price);
        }
        else
        {
            paid1Price = paid1Price + (doolallyFee - oldFee);
        }
    });

    $(document).on('change','#eventPrice-modal input[name="costType"]', function(){
        if($(this).val() == '1')
        {
            $('#eventPrice-modal #costPrice').parent().addClass('hide');
        }
        else if($(this).val() == '2')
        {
            $('#eventPrice-modal #costPrice').val(paid1Price).parent().removeClass('hide');
        }
        else
        {
            if($('#eventPrice-modal #costPrice').val() != 0)
            {
                $('#eventPrice-modal #costPrice').val(paid2Price);
            }
            $('#eventPrice-modal #costPrice').parent().removeClass('hide');
        }
    });

    //focus out event on price input of modal
    $(document).on('focusout','#eventPrice-modal #costPrice', function(){
        if($(this).val() != 0)
        {
            var basicPrice = Number($(this).val());
            if($('#eventPrice-modal input[name="costType"]:checked').val() == '2')
            {
                paid1Price = basicPrice+doolallyFee;
                paid2Price = basicPrice;
                $(this).val(paid1Price);
            }
            else
            {
                paid2Price = basicPrice;
                paid1Price = basicPrice+doolallyFee;
                $(this).val(paid2Price);
            }
        }
    });

    var addPaid1 = 0;
    var addPaid2 = 0;
    $(document).on('change','#eventAdd input[name="costType"]', function(){
        if($(this).val() == "2")
        {
            $('.event-price input[name="eventPrice"]').val(addPaid1).parent().removeClass('hide');
        }
        else if($(this).val() == "3" || $(this).val() == "4")
        {
            if($('.event-price input[name="eventPrice"]').val() != 0)
            {
                $('.event-price input[name="eventPrice"]').val(addPaid2);
            }
            $('.event-price input[name="eventPrice"]').parent().removeClass('hide');
        }
        else
        {
            $('.event-price input[name="eventPrice"]').val('0');
            $('.event-price').addClass('hide');
        }
    });
    $(document).on('focusout','#eventAdd .event-price input[name="eventPrice"]', function(){
        if($(this).val() != 0)
        {
            var basicPrice = Number($(this).val());
            if($('#eventAdd input[name="costType"]:checked').val() == '2')
            {
                if(basicPrice != addPaid1)
                {
                    addPaid1 = basicPrice+fixDoolallyFee;
                    addPaid2 = basicPrice;
                    $(this).val(addPaid1);
                }
            }
            else
            {
                addPaid2 = basicPrice;
                addPaid1 = basicPrice+fixDoolallyFee;
                $(this).val(addPaid2);
            }
        }
    });
    $(document).on("keyup","#eventAdd #customPrice", function(){
        var oldFee = fixDoolallyFee;
        if(Number($(this).val()) >= <?php echo NEW_DOOLALLY_FEE;?>)
        {
            fixDoolallyFee = Number($(this).val());
        }
        else
        {
            fixDoolallyFee = <?php echo NEW_DOOLALLY_FEE;?>;
        }
        var basicPrice = Number($('#eventAdd .event-price input[name="eventPrice"]').val());
        if($('input[name="costType"]:checked').val() == '2')
        {
            addPaid1 = basicPrice + (fixDoolallyFee - oldFee);
            $('.event-price input[name="eventPrice"]').val(addPaid1);
        }
        else
        {
            addPaid1 = addPaid1 + (fixDoolallyFee - oldFee);
        }
    });
    $(document).on('click','#eventView .cancel-this-event', function(){
        var eveId = $(this).attr('data-eventId');
        var senderEmail = $('#eventView #senderEmail').val();
        bootbox.prompt({
            title: "Please provide your Gmail("+senderEmail+") password",
            inputType: 'password',
            callback: function (result) {
                if(result != null && result != '')
                {
                    var errUrl = base_url+'mailers/checkGmailLogin';
                    showCustomLoader();
                    var senderPass = result;
                    $.ajax({
                        type:'POST',
                        dataType:'json',
                        url: base_url+'mailers/checkGmailLogin',
                        data:{from:senderEmail,fromPass:senderPass},
                        success: function(data)
                        {
                            hideCustomLoader();
                            if(data.status === false)
                            {
                                bootbox.alert('Invalid Gmail Credentials!');
                            }
                            else
                            {
                                bootbox.confirm("Are you sure you want to Cancel Event?", function(result) {
                                    if(result === true)
                                    {
                                        var errUrl = base_url+'dashboard/cancelEvent/'+eveId;
                                        showCustomLoader();
                                        $.ajax({
                                            type:'POST',
                                            dataType:'json',
                                            url:base_url+'dashboard/cancelEvent/'+eveId,
                                            data:{from:senderEmail,fromPass:senderPass},
                                            success: function(data){
                                                hideCustomLoader();
                                                if(data.status == true)
                                                {
                                                    window.location.reload();
                                                }
                                                else
                                                {
                                                    bootbox.alert('<label class="my-danger-text">'+data.errorMsg+'</label>');
                                                }
                                            },
                                            error: function(xhr, status, error){
                                                hideCustomLoader();
                                                bootbox.alert('Some Error Occurred!');
                                                var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                                                saveErrorLog(err);
                                            }
                                        });
                                    }
                                });
                            }
                        },
                        error: function(xhr, status, error){
                            hideCustomLoader();
                            bootbox.alert('Some Error Occurred!');
                            var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                            saveErrorLog(err);
                        }
                    });
                }
            }
        });

    });

    $(document).on('change','#eventAdd input[name="isEventEverywhere"]', function(){
        if($(this).is(':checked'))
        {
            $('#eventAdd #eventPlace').attr('disabled','disabled');
           var placeHtml = '<input id="newPlaceInput" type="hidden" name="eventPlace" value="'+$('#eventAdd #eventPlace').val()+'"/>';
            $('#eventAdd #dashboardEventAdd').append(placeHtml);
        }
        else
        {
            $('#eventAdd #eventPlace').removeAttr('disabled');
            if(typeof $('#eventAdd #newPlaceInput').val() !='undefined')
                $('#eventAdd #dashboardEventAdd').find($('#newPlaceInput')).remove();
        }
    });
    $('#main-feedback-table').DataTable();

</script>

<script>
    $(document).on('click','.eventShareImg-icon', function(){
        var eventId = $(this).attr('data-eventId');
        var eventName = $(this).attr('data-eventName');
        var hasShareImg = $(this).attr('data-hasShareImg');

        $('#shareImg-modal .share-body #eventId').val(eventId);
        $('#shareImg-modal .eventName').html(eventName);
        $("#shareImg-modal .share-body input[name=hasShareImg][value=" + hasShareImg + "]").prop('checked', true);
       /* if(hasShareImg == '1')
        {

        }
        else
        {
            $('#shareImg-modal .share-body #hasShareImg').prop('checked',false);
        }*/
        fetchAllShareImgs(eventId);
        $('#shareImg-modal').modal('show');
    });

    function fetchAllShareImgs(eventId)
    {
        var errUrl = base_url+'dashboard/getShareImgs/'+eventId;
        //Get All the sharing images
        showCustomLoader();
        $.ajax({
            type:'GET',
            dataType:'json',
            url:base_url+'dashboard/getShareImgs/'+eventId,
            success: function(data){
                hideCustomLoader();
                if(data.status === false)
                {
                    bootbox.alert(data.errorMsg);
                }
                else
                {
                    if(typeof data.shareImgs !== 'undefined')
                    {
                        var share_body = '<div class="paymentWrap">';
                        share_body += '<div id="main-img-list" class="btn-group paymentBtnGroup btn-group-justified" data-toggle="buttons">';
                        for(var i=0;i<data.shareImgs.length;i++)
                        {
                            var imgUrl = '<?php echo MOBILE_URL.EVENT_PATH_THUMB;?>'+data.shareImgs[i].filename;
                            if(data.shareImgs[i].ifUsing == '1')
                            {
                                share_body += '<label class="btn paymentMethod active">';
                                share_body += '<div class="method" style="background-image:url('+imgUrl+');"></div>'; //set background
                                share_body += '<input type="radio" name="isUsing" value="'+data.shareImgs[i].id+'" checked>';
                                share_body += '</label>';
                            }
                            else
                            {
                                share_body += '<label class="btn paymentMethod">';
                                share_body += '<div class="method" style="background-image:url('+imgUrl+');"></div>'; //set background
                                share_body += '<input type="radio" name="isUsing" value="'+data.shareImgs[i].id+'">';
                                share_body += '</label>';
                            }
                        }
                        share_body += '</div></div>';
                        $('#shareImg-modal .uploaded-imgs-section').html(share_body);
                    }
                }
            },
            error: function(xhr, status, error){
                hideCustomLoader();
                bootbox.alert('Some Error Occurred!');
                var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                saveErrorLog(err);
            }
        });
    }

    function shareUploadChange(ele)
    {
        var eventId = $('#shareImg-modal .share-body #eventId').val();
        $('#shareImg-modal #event-alt-share-btn').attr('disabled','true');
        $('#shareImg-modal .share-progress').removeClass('hide');
        var xhr = [];
        var totalFiles = ele.files.length;
        for(var i=0;i<totalFiles;i++)
        {
            xhr[i] = new XMLHttpRequest();
            (xhr[i].upload || xhr[i]).addEventListener('progress', function(e) {
                var done = e.position || e.loaded;
                var total = e.totalSize || e.total;
                $('#shareImg-modal .share-progress .progress-bar').css('width', Math.round(done/total*100)+'%').attr('aria-valuenow', Math.round(done/total*100)).html(parseInt(Math.round(done/total*100))+'%');
            });
            xhr[i].addEventListener('load', function(e) {
                $('#shareImg-modal #event-alt-share-btn').removeAttr('disabled');
                fetchAllShareImgs(eventId);
            });
            xhr[i].open('post', '<?php echo base_url();?>dashboard/uploadShareFiles/'+eventId, true);

            var data = new FormData;
            data.append('attachment', ele.files[i]);
            xhr[i].send(data);
            xhr[i].onreadystatechange = function(e) {
                if (e.srcElement.readyState == 4 && e.srcElement.status == 200) {
                    if(e.srcElement.responseText == 'Some Error Occurred!')
                    {
                        bootbox.alert('File size Limit 30MB');
                        return false;
                    }
                    try
                    {
                        var obj = $.parseJSON(e.srcElement.responseText);
                        if(obj.status == false)
                        {
                            bootbox.alert('<label class="my-danger-text">Error: '+obj.errorMsg+'</label>');
                            return false;
                        }
                    }
                    catch(excep)
                    {
                        //filesEventsArr.push(e.srcElement.responseText);
                        //fillEventImgs();
                    }
                }
            }
        }
        $('#shareImg-modal .share-progress .progress-bar').css('width','0').attr('aria-valuenow','0');
        $('#shareImg-modal .share-progress').addClass('hide');
        $('#shareImg-modal .share-body input[type="file"]').val('');
    }

    $(document).on('click','#shareImg-modal #event-alt-share-btn',function(){
        var eventId = $('#shareImg-modal .share-body #eventId').val();
        var hasShareImg = $('#shareImg-modal input[name="hasShareImg"]:checked').val();

        if(typeof $('#shareImg-modal input[name="isUsing"]:checked').val() === 'undefined')
        {
            bootbox.alert('Please Select one of the images!');
            return false;
        }
        var imgId = $('#shareImg-modal input[name="isUsing"]:checked').val();

        var errUrl = base_url+'dashboard/saveAltShareImg';
        $.ajax({
            type:'post',
            dataType:'json',
            url: base_url+'dashboard/saveAltShareImg',
            data: {eventId: eventId,hasShareImg:hasShareImg,imgId: imgId},
            success: function(data){
                hideCustomLoader();
                if(data.status === true)
                {
                    bootbox.alert('Event Data Saved!', function(){
                        window.location.reload();
                    });
                }
                else
                {
                    bootbox.alert(data.errorMsg);
                }
            },
            error: function(xhr, status, error){
                hideCustomLoader();
                bootbox.alert('Some Error Occurred!');
                var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                saveErrorLog(err);
            }
        });

    });
</script>

</html>