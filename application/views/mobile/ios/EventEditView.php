<!DOCTYPE html>
<html lang="en">

<body>
<!-- Status bar overlay for full screen mode (PhoneGap) -->
<!-- Top Navbar-->
<?php
    if(isset($eventDetails) && myIsMultiArray($eventDetails))
    {
        foreach($eventDetails as $key => $row)
        {
            ?>
            <div class="navbar">
                <div class="navbar-inner">
                    <div class="left">
                        <a href="#" class="back link" data-ignore-cache="true">
                            <i class="ic_back_icon point-item"></i>
                        </a>
                    </div>
                    <!--<div class="center sliding"><?php /*echo $row['eventData']['eventName'];*/?></div>-->
                    <!--<div class="right">

                    </div>-->
                </div>
            </div>
            <div class="pages">
                <div data-page="eventEdit" class="page event-add">
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
                                    <form action="<?php echo base_url().'updateEvent';?>" id="eventSave" method="post" class="ajax-submit">
                                        <input type="hidden" name="eventId" value="<?php echo $row['eventId'];?>"/>
                                        <input type="hidden" name="attachment" value="<?php echo $row['filename']; ?>"/>
                                        <div class="event-img-space"
                                             style="background:linear-gradient(rgba(255, 255, 255, 0.5), rgba(255, 255, 255, 0.5)), 0 / cover url('<?php echo base_url().EVENT_PATH_THUMB.$row['filename'];?>') no-repeat">
                                            <div class="event-img-before hide">
                                                <input type="file" id="event-img-upload" onchange="uploadChange(this)" class="my-vanish"/>
                                                <!--<a href="#" class="button event-img-add-btn">
                                                    <i class="ic_add"></i>
                                                </a>-->
                                                <button type="button" class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored event-img-add-btn">
                                                    <i class="ic_add"></i>
                                                </button>
                                                <p class="add-img-caption">Add a cover photo<!--<br> The image must be at least 1080 x 540 pixels--></p>
                                            </div>
                                            <div class="event-img-after">
                                                <button type="button" class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored event-img-remove">
                                                    <i class="ic_add"></i>
                                                </button>
                                                <div class="progress-bar">
                                                    <div class="progressbar hide"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="cropContainerModal" class="hide">
                                            <div class="done-overlay hide">
                                                <button type="button" class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored event-overlay-remove">
                                                    <i class="ic_add"></i>
                                                    <span class="mdl-button__ripple-container"><span class="mdl-ripple"></span></span>
                                                </button>
                                            </div>
                                            <i class="fa fa-check upload-done-icon"></i>
                                            <i class="fa fa-times upload-img-close"></i>
                                            <img id="img-container" src="" style="max-width:100%"/>
                                        </div>
                                        <br>
                                        <div class="event-descrip-wrapper">
                                            <div class="event-header-name">Create an event</div>
                                            <p class="event-sub-text">To organise an event, please read the event guidelines and then fill up the form.</p>
                                            <div class="row">
                                                <div class="col-5"></div>
                                                <div class="col-90">
                                                    <div class="hide" id="event-guide"><?php echo $eventTc;?></div>
                                                    <a href="#" class="button button-big button-fill book-event-btn">
                                                        <i class="ic_me_info_icon info-icon"></i>&nbsp;&nbsp;Read Event Guidelines
                                                    </a>
                                                </div>
                                                <div class="col-5"></div>
                                            </div>
                                        </div>
                                        <div class="event-header-name">
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label my-fullWidth">
                                                <input class="mdl-textfield__input" type="text" id="eventName" name="eventName"
                                                       value="<?php echo $row['eventName'];?>">
                                                <label class="mdl-textfield__label" for="eventName">Name of event</label>
                                            </div>
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label my-fullWidth">
                                                <textarea class="mdl-textfield__input" type="text" rows= "3" id="eventDesc" name="eventDescription"><?php echo $row['eventDescription'];?></textarea>
                                                <label class="mdl-textfield__label" for="eventDesc">Describe your event</label>
                                            </div>
                                            <input class="mdl-textfield__input" type="text" id="eventDate" name="eventDate" placeholder="Date of Event" readonly
                                                   value="<?php echo $row['eventDate'];?>">
                                            <br>
                                            <div class="row">
                                                <div class="col-50">
                                                    <div class="input-group">
                                                        <input id="startTime" onchange="timeCheck()" type="text" class="mdl-textfield__input" name="startTime" placeholder="Start Time" readonly
                                                               value="<?php echo date("h:i A", strtotime($row['startTime']));?>">
                                                    </div>
                                                </div>
                                                <div class="col-50">
                                                    <div class="input-group">
                                                        <input id="endTime" onchange="timeCheck()" type="text" class="mdl-textfield__input" name="endTime" placeholder="End Time" readonly
                                                               value="<?php echo date("h:i A", strtotime($row['endTime']));?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="list-block">
                                                <ul>
                                                    <li>
                                                        <a href="#" class="item-link smart-select" data-back-on-select="true">
                                                            <!-- select -->
                                                            <select name="eventType" id="eventType" class="mdl-textfield__input">
                                                                <?php
                                                                foreach($this->config->item('eventTypes') as $subkey => $subrow)
                                                                {
                                                                    ?>
                                                                    <option value="<?php echo $subrow;?>" <?php if($subrow == $row['eventType']){echo 'selected';} ?>>
                                                                        <?php echo $subrow;?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </select>
                                                            <div class="item-content">
                                                                <div class="item-inner">
                                                                    <!-- Select label -->
                                                                    <div class="item-title">Type of event</div>
                                                                    <!-- Selected value, not required -->
                                                                    <div class="item-after"></div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" class="item-link smart-select" data-back-on-select="true">
                                                            <!-- select -->
                                                            <select id="eventPlace" name="eventPlace" class="mdl-textfield__input">
                                                                <option value="">Select</option>
                                                                <?php
                                                                if(isset($locData))
                                                                {
                                                                    foreach($locData as $subkey => $subrow)
                                                                    {
                                                                        if(isset($subrow['id']))
                                                                        {
                                                                            ?>
                                                                            <option value="<?php echo $subrow['id'];?>" <?php if($subrow['id'] == $row['eventPlace']){echo 'selected';} ?>>
                                                                                <?php echo $subrow['locName'];?></option>
                                                                            <?php
                                                                        }
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                            <div class="item-content">
                                                                <div class="item-inner">
                                                                    <!-- Select label -->
                                                                    <div class="item-title">Location of event</div>
                                                                    <!-- Selected value, not required -->
                                                                    <div class="item-after"></div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" class="item-link smart-select" data-back-on-select="true">
                                                            <!-- select -->
                                                            <select name="eventCapacity" id="eventCapacity" class="mdl-textfield__input">
                                                                <?php
                                                                for($i=1;$i<=20;$i++)
                                                                {
                                                                    ?>
                                                                    <option value="<?php echo $i;?>" <?php if($i == $row['eventCapacity']){echo 'selected';} ?>>
                                                                        <?php echo $i;?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </select>
                                                            <div class="item-content">
                                                                <div class="item-inner">
                                                                    <!-- Select label -->
                                                                    <div class="item-title">Number of People</div>
                                                                    <!-- Selected value, not required -->
                                                                    <div class="item-after"></div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="event-header-name">Is the event Free or Paid?</div>

                                            <?php
                                            if($row['costType'] == '2')
                                            {
                                                ?>
                                                <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="paidType">
                                                    <input type="radio" id="paidType" class="mdl-radio__button" name="costType" value="2" checked>
                                                    <span class="mdl-radio__label">Paid</span>
                                                </label>
                                                <p class="event-sub-text">For paid events, we charge Rs 250 per attendee which includes a pint or house fries.</p>
                                                <div class="row">
                                                    <div class="col-50">
                                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label event-price">
                                                            <input class="mdl-textfield__input" type="text" pattern="-?[0-9]*(\.[0-9]+)?" id="eventPrice"
                                                                   value="<?php echo $row['eventPrice'];?>">
                                                            <label class="mdl-textfield__label" for="eventPrice">Event Fee</label>
                                                            <span class="mdl-textfield__error">Input is not a number!</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-50">
                                                        <p class="event-sub-text">+ Rs. 250 Doolally Fee</p>
                                                    </div>
                                                </div>
                                                <div class="event-header-name">Total Price: Rs.
                                                    <span class="total-event-price">
                                                    <?php echo ($row['eventPrice']+250); ?>
                                                </span>
                                                </div>
                                                <input type="hidden" name="eventPrice" value="<?php echo $row['eventPrice'];?>"/>
                                                <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="freeType">
                                                    <input type="radio" id="freeType" class="mdl-radio__button" name="costType" value="1">
                                                    <span class="mdl-radio__label">Free</span>
                                                </label>
                                                <p class="event-sub-text">If you don't charge, we don't charge</p>
                                                <?php
                                            }
                                            elseif($row['costType'] == '1')
                                            {
                                                ?>
                                                <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="paidType">
                                                    <input type="radio" id="paidType" class="mdl-radio__button" name="costType" value="2">
                                                    <span class="mdl-radio__label">Paid</span>
                                                </label>
                                                <p class="event-sub-text">For paid events, we charge Rs 250 per attendee which includes a pint or house fries.</p>
                                                <div class="row">
                                                    <div class="col-50">
                                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label event-price">
                                                            <input class="mdl-textfield__input" type="text" pattern="-?[0-9]*(\.[0-9]+)?" id="eventPrice"
                                                                   value="">
                                                            <label class="mdl-textfield__label" for="eventPrice">Event Fee</label>
                                                            <span class="mdl-textfield__error">Input is not a number!</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-50">
                                                        <p class="event-sub-text">+ Rs. 250 Doolally Fee</p>
                                                    </div>
                                                </div>
                                                <div class="event-header-name">Total Price: Rs.
                                                    <span class="total-event-price">
                                                    0
                                                </span>
                                                </div>
                                                <input type="hidden" name="eventPrice" value="0"/>
                                                <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="freeType">
                                                    <input type="radio" id="freeType" class="mdl-radio__button" name="costType" value="1" checked>
                                                    <span class="mdl-radio__label">Free</span>
                                                </label>
                                                <p class="event-sub-text">If you don't charge, we don't charge</p>
                                                <?php
                                            }
                                            ?>
                                            <div class="row">
                                                <label class="col-100">Need Accessories: </label>
                                                <div class="col-100">
                                                    <ul class="my-mainMenuList">
                                                        <?php
                                                        if($row['ifMicRequired'] == '1')
                                                        {
                                                            ?>
                                                            <li id="micWrapper" class="isChecked">
                                                                <input type="checkbox" name="ifMicRequired" onchange="toggleAccess(this)" id="ifMicRequired" value="1" checked/>
                                                                <label for="ifMicRequired">
                                                                    <i class="ic_mic_icon on"></i>
                                                                    <span class="on">Microphone</span>
                                                                </label>
                                                            </li>
                                                            <?php
                                                        }
                                                        else
                                                        {
                                                            ?>
                                                            <li id="micWrapper" disabled="disabled">
                                                                <input type="checkbox" name="ifMicRequired" onchange="toggleAccess(this)" id="ifMicRequired" value="1"/>
                                                                <label for="ifMicRequired">
                                                                    <i class="ic_mic_icon"></i>
                                                                    <span>Microphone</span>
                                                                </label>
                                                            </li>
                                                            <?php
                                                        }
                                                        ?>
                                                        <?php
                                                        if($row['ifProjectorRequired'] == '1')
                                                        {
                                                            ?>
                                                            <li id="projWrapper" class="isChecked">
                                                                <input type="checkbox" name="ifProjectorRequired" onchange="toggleAccess(this)" id="ifProjectorRequired" value="1" checked>
                                                                <label for="ifProjectorRequired" class="">
                                                                    <i class="ic_projector_icon on"></i>
                                                                    <span class="on">Projector</span>
                                                                </label>
                                                            </li>
                                                            <?php
                                                        }
                                                        else
                                                        {
                                                            ?>
                                                            <li id="projWrapper" disabled="disabled">
                                                                <input type="checkbox" name="ifProjectorRequired" onchange="toggleAccess(this)" id="ifProjectorRequired" value="1"/>
                                                                <label for="ifProjectorRequired">
                                                                    <i class="ic_projector_icon"></i>
                                                                    <span>Projector</span>
                                                                </label>
                                                            </li>
                                                            <?php
                                                        }
                                                        ?>
                                                    </ul>
                                                    <!--<label class="mdl-icon-toggle mdl-js-icon-toggle mdl-js-ripple-effect" for="ifMicRequired">
                                                        <input type="checkbox" id="ifMicRequired" class="mdl-icon-toggle__input" name="ifMicRequired" value="1">
                                                        <i class="fa fa-microphone mdl-icon-toggle__label"></i>
                                                    </label>
                                                    <label class="mdl-icon-toggle mdl-js-icon-toggle mdl-js-ripple-effect" for="ifProjectorRequired">
                                                        <input type="checkbox" id="ifProjectorRequired" class="mdl-icon-toggle__input" value="1" name="ifProjectorRequired">
                                                        <i class="mdl-icon-toggle__label fa fa-video-camera"></i>
                                                    </label>-->
                                                </div>
                                            </div>

                                            <div class="event-header-name">Your details</div>
                                            <!--<p class="event-sub-text">We'll contact you while we curate your event.</p>-->
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label my-fullWidth">
                                                <input class="mdl-textfield__input" type="text" name="creatorName" id="creatorName"
                                                       value="<?php echo $row['creatorName']; ?>">
                                                <label class="mdl-textfield__label" for="creatorName">Name</label>
                                            </div>
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label my-fullWidth">
                                                <input class="mdl-textfield__input" type="number" name="creatorPhone" id="creatorPhone" maxlength="10"
                                                       oninput="maxLengthCheck(this)" value="<?php echo $row['creatorPhone']; ?>">
                                                <label class="mdl-textfield__label" for="creatorPhone">Phone Number</label>
                                            </div>
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label my-fullWidth">
                                                <input class="mdl-textfield__input" type="email" name="creatorEmail" id="creatorEmail"
                                                       value="<?php echo $row['creatorEmail']; ?>">
                                                <label class="mdl-textfield__label" for="creatorEmail">Email ID</label>
                                            </div>
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label my-fullWidth">
                                                <textarea class="mdl-textfield__input" type="text" rows= "3" id="aboutCreator" name="aboutCreator"><?php echo $row['aboutCreator']; ?></textarea>
                                                <label class="mdl-textfield__label" for="aboutCreator">Something about yourself (Optional)</label>
                                            </div>
                                            <input type="hidden" name="userId" value="<?php echo $row['userId'];?>"/>
                                            <div class="event-header-name">
                                                All events are reviewed and approved by Doolally. Once approved, we will create an Instamojo payment link and
                                                accept payments on your behalf.
                                            </div>
                                            <hr>
                                            <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="tnc">
                                                <input type="checkbox" id="tnc" value="1" class="mdl-checkbox__input">
                                                <span class="mdl-checkbox__label">I have read and agree to the
                                <a href="#">Terms and Conditions.</a>
                            </span>
                                            </label>
                                        </div>
                                        <br><br>
                                        <div class="row">
                                            <div class="col-5"></div>
                                            <div class="col-90">
                                                <input type="submit" class="button button-big button-fill submit-event-btn" value="Update Event"/>
                                            </div>
                                            <div class="col-5"></div>
                                        </div>
                                        <br>
                                    </form>
                                </div>
                                <?php
                            }
                        ?>
                    </div>
                </div>
            </div>

            <?php
        }
    }
    else
    {
        ?>
        <div class="navbar">
            <div class="navbar-inner">
                <div class="left">
                    <a href="#" class="back link" data-ignore-cache="true">
                        <i class="fa fa-arrow-left color-black"></i>
                    </a>
                </div>
                <!--<div class="center sliding"><?php /*echo $row['eventData']['eventName'];*/?></div>-->
                <!--<div class="right">

                </div>-->
            </div>
        </div>
        <div class="pages">
            <div data-page="eventEdit" class="page">
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
                        <div class="content-block">
                            <p>No result Found!</p>
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
</body>

</html>