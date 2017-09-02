<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Event Edit :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="eventEdit">
        <div class="container">
            <div class="row">
                <a href="<?php echo base_url().'dashboard';?>" class="btn btn-warning"><i class="fa fa-arrow-circle-o-left"></i> Go Back</a>
                <?php
                    if(isset($eventInfo) && myIsArray($eventInfo))
                    {
                        $eventDate = '';
                        $isAllLocs = false;
                        foreach($eventInfo as $key => $row)
                        {
                            if(isset($row['eventData']['eventId']))
                            {
                                ?>
                                <h2><i class="fa fa-calendar fa-1x"></i> Edit Event: <?php echo $row['eventData']['eventName'];?></h2>
                                <hr>
                                <br>
                                <form id="event-dash-edit" action="<?php echo base_url();?>dashboard/updateEvent" method="post" class="form-horizontal" role="form">
                                    <input type="hidden" name="eventId" value="<?php echo $row['eventData']['eventId'];?>"/>
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label my-fullWidth">
                                        <input class="mdl-textfield__input" type="text" name="eventName"
                                               id="eventName" value="<?php echo $row['eventData']['eventName'];?>">
                                        <label class="mdl-textfield__label" for="eventName">Event Name</label>
                                    </div>
                                    <br>
                                    <!--<div class="text-left">
                                        <label for="eventType">Event Type :</label>
                                        <select name="eventType" id="eventType" class="form-control">
                                            <?php
/*                                            $foundType = false;
                                            foreach($this->config->item('eventTypes') as $evekey => $everow)
                                            {
                                                */?>
                                                <option value="<?php /*echo $everow;*/?>"
                                                <?php /*if($row['eventData']['eventType'] == $everow){$foundType = true;echo 'selected';};*/?>><?php /*echo $everow;*/?></option>
                                                <?php
/*                                            }
                                            */?>
                                        </select>
                                        <div class="mdl-textfield mdl-js-textfield other-event hide">
                                            <input class="mdl-textfield__input" type="text"
                                                   id="otherType" <?php /*if($foundType == false){echo 'value="'.$row['eventData']['eventType'].'"';}*/?>>
                                            <label class="mdl-textfield__label" for="otherType">Other</label>
                                        </div>
                                    </div>
                                    <br>-->
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label my-fullWidth text-left">
                                        <label for="eventDescription">Event Description: </label>
                                        <textarea class="mdl-textfield__input my-singleBorder" type="text" name="eventDescription" rows="5"
                                                  id="eventDescription"><?php echo strip_tags($row['eventData']['eventDescription']);?></textarea>
                                    </div>
                                    <ul class="list-inline text-left">
                                        <li>
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                <input class="mdl-textfield__input" type="text" name="eventDate"
                                                       id="eventDate" placeholder="">
                                                <label class="mdl-textfield__label" for="eventDate">Event Date(old: <?php echo $row['eventData']['eventDate'];?>)</label>
                                                <?php
                                                $eventDate = $row['eventData']['eventDate'];
                                                ?>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                <input class="mdl-textfield__input" type="text" name="startTime"
                                                       id="startTime" placeholder="" value="<?php echo $row['eventData']['startTime'];?>">
                                                <label class="mdl-textfield__label" for="startTime">Start Time</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                <input class="mdl-textfield__input" type="text" name="endTime"
                                                       id="endTime" placeholder="" value="<?php echo $row['eventData']['endTime'];?>">
                                                <label class="mdl-textfield__label" for="endTime">End Time</label>
                                            </div>
                                        </li>
                                    </ul>
                                    <ul class="list-inline text-left">
                                        <li class="my-singleBorder">
                                            <label>Hide Event Date?</label>
                                            <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="yesDate">
                                                <input type="radio" id="yesDate" class="mdl-radio__button" name="showEventDate"
                                                       value="2" <?php if($row['eventData']['showEventDate'] == "2"){echo 'checked';}?>>
                                                <span class="mdl-radio__label">Yes</span>
                                            </label>
                                            <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="noDate">
                                                <input type="radio" id="noDate" class="mdl-radio__button" name="showEventDate"
                                                       value="1" <?php if($row['eventData']['showEventDate'] == "1"){echo 'checked';}?>>
                                                <span class="mdl-radio__label">No</span>
                                            </label>
                                        </li>
                                        <li class="my-singleBorder">
                                            <label>Hide Event Time?</label>
                                            <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="yesTime">
                                                <input type="radio" id="yesTime" class="mdl-radio__button" name="showEventTime"
                                                       value="2" <?php if($row['eventData']['showEventTime'] == "2"){echo 'checked';}?>>
                                                <span class="mdl-radio__label">Yes</span>
                                            </label>
                                            <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="noTime">
                                                <input type="radio" id="noTime" class="mdl-radio__button" name="showEventTime"
                                                       value="1" <?php if($row['eventData']['showEventTime'] == "1"){echo 'checked';}?>>
                                                <span class="mdl-radio__label">No</span>
                                            </label>
                                        </li>
                                        <li class="my-singleBorder">
                                            <label>Hide Event Price?</label>
                                            <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="yesPrice">
                                                <input type="radio" id="yesPrice" class="mdl-radio__button" name="showEventPrice"
                                                       value="2" <?php if($row['eventData']['showEventPrice'] == "2"){echo 'checked';}?>>
                                                <span class="mdl-radio__label">Yes</span>
                                            </label>
                                            <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="noPrice">
                                                <input type="radio" id="noPrice" class="mdl-radio__button" name="showEventPrice"
                                                       value="1" <?php if($row['eventData']['showEventPrice'] == "1"){echo 'checked';}?>>
                                                <span class="mdl-radio__label">No</span>
                                            </label>
                                        </li>
                                    </ul>
                                    <br>
                                    <div class="text-left">
                                        <label>Event Cost (<a href="#" data-toggle="modal" data-target="#costModal">?</a>):</label><br>
                                        <input type="hidden" name="priceFreeStuff" value=""/>
                                        <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="freeType">
                                            <input type="radio" id="freeType" class="mdl-radio__button" name="costType"
                                                   value="1" <?php if($row['eventData']['costType'] == "1"){echo 'checked';}?>>
                                            <span class="mdl-radio__label">Free</span>
                                        </label><br>
                                        <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="paidType">
                                            <input type="radio" id="paidType" class="mdl-radio__button" name="costType"
                                                   value="2" <?php if($row['eventData']['costType'] == "2"){echo 'checked';}?>>
                                            <span class="mdl-radio__label">Event Fee + Doolally Fee</span>
                                        </label>
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label custom-price hide">
                                            <input class="mdl-textfield__input" type="text" name="doolallyFee" value="<?php echo NEW_DOOLALLY_FEE;?>" pattern="-?[0-9]*(\.[0-9]+)?" id="customPrice">
                                            <label class="mdl-textfield__label" for="customPrice">Custom Price</label>
                                            <span class="mdl-textfield__error">Input is not a number!</span>
                                        </div><br>
                                        <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="paid2Type">
                                            <input type="radio" id="paid2Type" class="mdl-radio__button" name="costType"
                                                   value="3" <?php if($row['eventData']['costType'] == "3"){echo 'checked';}?>>
                                            <span class="mdl-radio__label">Event Fee</span>
                                        </label><br>
                                        <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="paid3Type">
                                            <input type="radio" id="paid3Type" class="mdl-radio__button" name="costType"
                                                   value="4" <?php if($row['eventData']['costType'] == "4"){echo 'checked';}?>>
                                            <span class="mdl-radio__label">Doolally Fee</span>
                                        </label><br>

                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label event-price hide">
                                            <input class="mdl-textfield__input" type="text" name="eventPrice" pattern="-?[0-9]*(\.[0-9]+)?"
                                                   id="eventPrice" value="<?php echo $row['eventData']['eventPrice'];?>">
                                            <label class="mdl-textfield__label" for="eventPrice">Price</label>
                                            <span class="mdl-textfield__error">Input is not a number!</span>
                                        </div>
                                        <!--<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label special-offer hide">
                                            <input class="mdl-textfield__input" type="text" name="priceFreeStuff" id="priceFreeStuff"
                                                   placeholder="" value="<?php /*echo $row['eventData']['priceFreeStuff'];*/?>">
                                            <label class="mdl-textfield__label" for="priceFreeStuff">Special Offer With Price?</label>
                                        </div>-->
                                    </div>
                                    <!--<br>
                                    <div class="text-left">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <?php
/*                                                    if($row['eventData']['isEventEverywhere'] == '1')
                                                    {
                                                        */?>
                                                        <input id="newPlaceInput" type="hidden" name="eventPlace"
                                                               value="<?php /*echo $row['eventData']['eventPlace'];*/?>"/>
                                                        <?php
/*                                                    }
                                                */?>
                                                <label>Event Place: </label>
                                                <select id="eventPlace" name="eventPlace" class="form-control"
                                                    <?php /*if($row['eventData']['isEventEverywhere'] == '1'){echo 'disabled="disabled"';} */?>>
                                                    <?php
/*                                                    if(isset($locations))
                                                    {
                                                        foreach($locations as $lockey => $locrow)
                                                        {
                                                            if(isset($locrow['id']))
                                                            {
                                                                */?>
                                                                <option value="<?php /*echo $locrow['id'];*/?>"
                                                                    <?php /*if($locrow['id'] == $row['eventData']['eventPlace']){echo 'selected';}*/?>><?php /*echo $locrow['locName'];*/?></option>
                                                                <?php
/*                                                            }
                                                        }
                                                    }
                                                    */?>
                                                </select>
                                            </div>
                                            <div class="col-sm-6 all-loc-block">
                                                <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="isEventEverywhere">
                                                    <input type="checkbox" name="isEventEverywhere" value="1" id="isEventEverywhere" class="mdl-checkbox__input"
                                                        <?php /*if($row['eventData']['isEventEverywhere'] == '1'){echo 'checked';} */?>>
                                                    <span class="mdl-checkbox__label">Available At All Locations?</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>-->
                                    <input type="hidden" name="eventPlace" value="<?php echo $row['eventData']['eventPlace'];?>"/>
                                    <input type="hidden" name="isEventEverywhere" value="<?php echo $row['eventData']['isEventEverywhere'];?>"/>

                                    <br>
                                    <div class="text-left">
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" name="eventCapacity" id="eventCapacity"
                                                   placeholder="" value="<?php echo $row['eventData']['eventCapacity'];?>">
                                            <label class="mdl-textfield__label" for="eventCapacity">Event Capacity</label>
                                        </div>
                                        <br>
                                        <label><input type="checkbox" value="1"
                                                      name="ifMicRequired" <?php if($row['eventData']['ifMicRequired'] == "1"){echo 'checked';}?>>Do you need a mic?</label>
                                        <label><input type="checkbox" value="2"
                                                      name="ifProjectorRequired" <?php if($row['eventData']['ifProjectorRequired'] == "1"){echo 'checked';}?>>Do you need a projector?</label>
                                    </div>
                                    <br>
                                    <div class="text-left">
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" name="creatorName" id="creatorName"
                                                   placeholder="" value="<?php echo $row['eventData']['creatorName'];?>">
                                            <label class="mdl-textfield__label" for="creatorName">Organizer Name</label>
                                        </div>
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="number" name="creatorPhone" id="creatorPhone"
                                                   placeholder="" value="<?php echo $row['eventData']['creatorPhone'];?>">
                                            <label class="mdl-textfield__label" for="creatorPhone">Organizer Phone</label>
                                        </div>
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="email" name="creatorEmail" id="creatorEmail"
                                                   placeholder="" value="<?php echo $row['eventData']['creatorEmail'];?>">
                                            <label class="mdl-textfield__label" for="creatorEmail">Organizer Email</label>
                                        </div>
                                        <br>
                                        <label for="eventDescription">Organizer Description: </label>
                                        <textarea class="mdl-textfield__input my-singleBorder" type="text" name="aboutCreator" rows="5"
                                                  id="aboutCreator"><?php echo $row['eventData']['aboutCreator'];?></textarea>
                                    </div>
                                    <br>
                                    <?php
                                        if(isset($row['eventAtt']) && myIsMultiArray($row['eventAtt']))
                                        {
                                            ?>
                                            <div class="text-left">
                                                <?php
                                                    foreach($row['eventAtt'] as $imgkey => $imgrow)
                                                    {
                                                        ?>
                                                        <div class="pics-preview-panel col-sm-2 col-xs-5">
                                                            <img src="<?php echo MOBILE_URL.EVENT_PATH_THUMB.$imgrow['filename'];?>"
                                                                 class="img-thumbnail"/>
                                                            <i class="fa fa-times img-remove-icon" data-picId="<?php echo $imgrow['id'];?>"></i>
                                                        </div>
                                                        <?php
                                                    }
                                                ?>
                                            </div>
                                            <?php
                                        }
                                    ?>
                                    <div class="myUploadPanel text-left">
                                        <input type="file" multiple class="form-control" onchange="eventUploadChange(this)" />
                                        <input type="hidden" name="attachment"/>
                                    </div>
                                    <br>
                                    <button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">Submit</button>
                                    <br><br>
                                    <div class="progress hide">
                                        <div class="progress-bar progress-bar-striped active" role="progressbar"
                                             aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                </form>
                                <?php
                            }
                        }
                    }
                    else
                    {
                        echo '<h2 class="my-danger-text text-center>Mug Number Not Found!</h2>"';
                    }
                ?>
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
        <?php echo $footerView; ?>
    </main>
</body>
<?php echo $globalJs; ?>

<script>
    var oldStartT,oldEndT;
    $(window).load(function(){
        oldStartT = $('#startTime').val();
        oldEndT = $('#endTime').val();
    });
    $(document).on('click','.img-remove-icon', function(){
        var picId = $(this).attr('data-picId');
        var parent = $(this).parent();
        bootbox.confirm("Remove Image?", function(result) {
            var errUrl = base_url+'dashboard/deleteEventAtt';
            if(result === true)
            {
                $.ajax({
                    type:"POST",
                    dataType:"json",
                    url:"<?php echo base_url();?>dashboard/deleteEventAtt",
                    data:{picId:picId},
                    success: function(data)
                    {
                        if(data.status === true)
                        {
                            $(parent).fadeOut();
                            $(parent).remove();
                        }
                    },
                    error: function(xhr, status, error){
                        bootbox.alert('Some Error Occurred!');
                        var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                        saveErrorLog(err);
                    }
                });
            }
        });
    });
    function fillEventImgs()
    {
        if(filesEventsArr.length > 0)
        {
            $('input[name="attachment"]').val(filesEventsArr.join());
        }
    }
    var filesEventsArr = [];
    function eventUploadChange(ele)
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
    var date = new Date();
    <?php
        if(isset($eventDate) && $eventDate != '')
        {
            ?>
    $('#eventDate').datetimepicker({
        format: 'YYYY-MM-DD',
        useCurrent: false,
        minDate: date
    });
    $('#eventDate').val('<?php echo $eventDate;?>');
            <?php
        }
        else
        {
            ?>
    $('#eventDate').datetimepicker({
        format: 'YYYY-MM-DD',
        minDate: date
    });
            <?php
        }
    ?>
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

    var fixDoolallyFee = Number($('#customPrice').val());

    $(document).on('change','input[name="costType"]', function(){
        costToggle();
    });

    function costToggle()
    {
        if($('input[name="costType"]:checked').val() == "2")
        {
            $('.event-price input[name="eventPrice"]').val(addPaid1).parent().removeClass('hide');
        }
        else if($('input[name="costType"]:checked').val() == "3" || $('input[name="costType"]:checked').val() == "4")
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
    }
    $(window).load(function(){
        costToggle();
    });
    var addPaid1,addPaid2;
    $(document).ready(function(){
        var eveApprovType = $('input[name="costType"]:checked').val();
        var costPrice = $('#eventPrice').val();
        if(eveApprovType == '2')
        {
            addPaid1 = Number(costPrice);
            addPaid2 = Number(costPrice) - fixDoolallyFee;
        }
        else if(eveApprovType == '3' || eveApprovType == '4')
        {
            addPaid1 = Number(costPrice) + fixDoolallyFee;
            addPaid2 = Number(costPrice);
        }
        else
        {
            addPaid1 = costPrice;
            addPaid2 = costPrice;
        }
    });
    $(document).on('focusout','.event-price input[name="eventPrice"]', function(){
        if($(this).val() != 0)
        {
            var basicPrice = Number($(this).val());
            if($('input[name="costType"]:checked').val() == '2')
            {
                addPaid1 = basicPrice+fixDoolallyFee;
                addPaid2 = basicPrice;
                $(this).val(addPaid1);
            }
            else
            {
                addPaid2 = basicPrice;
                addPaid1 = basicPrice+fixDoolallyFee;
                $(this).val(addPaid2);
            }
        }
    });
    $(document).on("keyup","#customPrice", function(){
        var oldFee = fixDoolallyFee;
        if(Number($(this).val()) >= <?php echo NEW_DOOLALLY_FEE;?>)
        {
            fixDoolallyFee = Number($(this).val());
        }
        else
        {
            fixDoolallyFee = <?php echo NEW_DOOLALLY_FEE;?>;
        }
        var basicPrice = Number($('.event-price input[name="eventPrice"]').val());

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
    var placeHtml='';
    $(document).on('submit','#event-dash-edit', function(e){
        var ele = $(this);
        e.preventDefault();
        if(typeof $(this).find('.pics-preview-panel').html() == 'undefined')
        {
            if( $(this).find('input[name="attachment"]').val() == '')
            {
                bootbox.alert('Please provide the event image!');
                return false;
            }
        }
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
        if($(this).find('#eventPrice').val() == 0 && $('input[name="costType"]:checked').val() != '1')
        {
            bootbox.alert('Event Price Cannot be Zero');
            return false;
        }
        var d = new Date($(this).find('#eventDate').val());
        var startT = $(this).find('#startTime').val();
        var endT = $(this).find('#endTime').val();

        /*if(startT > endT)
        {
            bootbox.alert('Event Time is not proper!');
            return false;
        }*/
        if($(this).find('#creatorName').val() == '' &&
            $(this).find('#creatorPhone').val() == '' &&
            $(this).find('#creatorEmail').val() == '')
        {
            bootbox.alert('Organizer details required!');
            return false;
        }
        if( oldStartT != startT || oldEndT != endT)
        {
            bootbox.confirm("Sure want to modify timings?", function(result) {
                if(result === true)
                {
                    var errUrl = $(ele).attr('action');
                    showCustomLoader();
                    $.ajax({
                        type:"POST",
                        dataType:'json',
                        url: $(ele).attr('action'),
                        data: $(ele).serialize(),
                        success: function(data){
                            hideCustomLoader();
                            if(data.status === true)
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
                                    window.location.href = base_url+'dashboard';
                                }
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
            });
        }
        else
        {
            var errUrl = $(this).attr('action');
            showCustomLoader();
            $.ajax({
                type:"POST",
                dataType:'json',
                url: $(this).attr('action'),
                data: $(this).serialize(),
                success: function(data){
                    hideCustomLoader();
                    if(data.status === true)
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
                            window.location.href = base_url+'dashboard';
                        }
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
    });
    $(document).on('change','input[name="isEventEverywhere"]', function(){
        if($(this).is(':checked'))
        {
            $('#eventPlace').attr('disabled','disabled');
            placeHtml = '<input id="newPlaceInput" type="hidden" name="eventPlace" value="'+$('#eventPlace').val()+'"/>';
            $('#event-dash-edit').append(placeHtml);
        }
        else
        {
            $('#eventPlace').removeAttr('disabled');
            if(typeof $('#newPlaceInput').val() !='undefined')
            $('#event-dash-edit').find($('#newPlaceInput')).remove();
        }
    });
</script>

</html>