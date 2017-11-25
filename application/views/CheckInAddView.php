<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Mug New Check-In :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="mugCheckIn">
        <div class="container">
            <div class="row">
                <div class="col-sm-2 col-xs-1"></div>
                <div class="col-sm-8 col-xs-10 main-box">
                    <h2 class="text-center">
                        <i class="fa fa-calendar-check-o fa-1x"></i> Add New Check-In
                        <span class="pull-right fill-remaining-info hide">
                            <a href="#" data-placement="left"
                               data-toggle="popover" data-content="Fill Missing Info?">&nbsp;</a>
                            <i class="fa fa-file-text-o my-pointer-item" data-toggle="modal" data-target="#missingInfoModal"></i>
                        </span>
                    </h2>
                    <hr>
                    <div class="row text-center">
                        <p class="new-checkin-alerts"></p>
                        <p class="new-breakfast-status"></p>
                    </div>
                    <!--<div class="pull-right">
                        <a href="#" data-toggle="tooltip" title="Search"><i class="fa fa-search"></i></a> &nbsp;
                        <a href="#" class="my-info-filler"><i class="fa fa-file-text-o"></i></a>
                    </div>-->
                    <div class="col-sm-12 col-xs-12">
                        <ul class="list-inline my-mainMenuList text-center">
                            <li>
                                <input type="radio" name="checkInInput" onchange="showForm(this)" id="mugInput" value="1" checked />
                                <label for="mugInput">
                                    <i class="fa fa-beer fa-2x"></i>
                                    <br>
                                    <span>Mug #</span>
                                </label>
                            </li>
                            <!--<li>
                                <input type="radio" name="checkInInput" onchange="showForm(this)" id="mobInput" value="2" />
                                <label for="mobInput">
                                    <i class="fa fa-mobile fa-3x"></i>
                                    <br>
                                    <span>Mobile #</span>
                                </label>
                            </li>-->
                            <li>
                                <a href="#" data-toggle="modal" data-target="#searchModal">
                                    <div class="menuWrap text-center">
                                        <i class="fa fa-search fa-2x"></i>
                                        <br>
                                        <span>Search List</span>
                                    </div>
                                </a>
                            </li>
                        </ul>
                        <br>
                        <div class="form-group mugInput">
                            <div class="col-sm-1 col-xs-1 form-adjust-insmall"></div>
                            <div class="col-sm-8 col-xs-8">
                                <div class="mug-already-error my-danger-text text-center"></div>
                                <input type="number" name="mugNum" maxlength="4" oninput="maxLengthCheck(this)" class="form-control" id="mugNum" placeholder="Mug #">
                            </div>
                            <div class="col-sm-2 col-xs-2">
                                <button type="button" class="btn btn-primary verify-checkin-btn">Verify</button>
                            </div>
                            <div class="col-sm-1 col-xs-1"></div>
                        </div>
                        <div class="form-group mobileInput hide">
                            <div class="col-sm-1 col-xs-1"></div>
                            <div class="col-sm-8 col-xs-8">
                                <input type="number" name="mobNum" oninput="maxLengthCheck(this)" class="form-control" id="mobNum" maxlength="10" placeholder="Mobile #">
                            </div>
                            <div class="col-sm-2 col-xs-2">
                                <button type="button" class="btn btn-primary verify-checkin-btn">Verify</button>
                            </div>
                            <div class="col-sm-1 col-xs-1 form-adjust-insmall"></div>
                        </div>
                        <br><br>
                        <div class="row">
                            <div class="col-sm-8 col-xs-8 checkin-info-panel">
                                <div class="mugNumber-status"></div>
                            </div>
                            <div class="col-sm-4 col-xs-4 visual-status-icons hide">
                                <i class="fa fa-home fa-4x first-icon" data-toggle="tooltip" title="Home"></i>
                                <i class="fa fa-plane fa-4x last-icon" data-toggle="tooltip" title="Roaming"></i>
                            </div>
                        </div>

                        <div class="row final-checkIn-row hide">
                            <div class="col-sm-2 col-xs-2"></div>
                            <div class="col-sm-8 col-xs-8 text-center">
                                <button type="button" class="btn btn-success final-checkin-btn">Check-In</button>
                            </div>
                            <div class="col-sm-2 col-xs-2"></div>
                        </div>
                        <br>
                    </div>
                    <!--<div class="col-sm-2">
                        <i class="fa fa-home fa-5x"></i>
                        <i class="fa fa-plane fa-5x"></i>
                        <button onclick="getLocation()">bvbbv</button>
                        <p id="demo"></p>
                    </div>-->
                </div>
                <div class="col-sm-2 col-xs-1"></div>
            </div>
        </div>
        <div id="mobile-alert-toast" class="mdl-js-snackbar mdl-snackbar">
            <div class="mdl-snackbar__text"></div>
            <button class="mdl-snackbar__action" type="button"></button>
        </div>
    </main>

    <!-- Modal -->
    <div id="searchModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-md">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Search List</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12 modal-table table-responsive">
                            <table id="main-mugclub-table" class="table table-hover table-bordered table-striped my-checkIn-search-table">
                                <thead>
                                <tr>
                                    <th class="">Mug #</th>
                                    <th>Name</th>
                                    <th>Mobile #</th>
                                    <th>Location</th>
                                    <th>Birth Date</th>
                                </tr>
                                </thead>
                                <?php
                                if(isset($mugData) && myIsArray($mugData))
                                {
                                    if($mugData['status'] === false)
                                    {
                                        ?>
                                        <tbody>
                                        <tr class="my-danger-text text-center">
                                            <td colspan="10">No Data Found!</td>
                                        </tr>
                                        </tbody>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                        <tbody>
                                        <?php
                                        foreach($mugData['mugList'] as $key => $row)
                                        {
                                            ?>
                                            <tr>
                                                <!--<td class="hide location-info"><?php /*echo $row['homeBase'];*/?></td>
                                                <td class="hide membershipEnd-info"><?php /*echo $row['membershipEnd'];*/?></td>
                                                <td class="hide mugNumber-info"><?php /*echo $row['mugId'];*/?></td>-->
                                                <td class="">
                                                    <span class="infoLabel hide">Mug #</span>
                                                    <span class="infoData mugNumber-info"><?php echo $row['mugId'];?></span>
                                                </td>
                                                <td>
                                                    <span class="infoLabel hide">Name</span>
                                                    <span class="infoData"><?php echo ucfirst($row['firstName']) .' '.ucfirst($row['lastName']);?></span>
                                                </td>
                                                <!--<td class="hide">
                                                    <span class="infoLabel hide">Mug Tag</span>
                                                    <span class="infoData"><?php /*echo $row['mugTag'];*/?></span>
                                                </td>-->
                                                <td>
                                                    <span class="infoLabel hide">Mobile #</span>
                                                    <span class="infoData"><?php echo $row['mobileNo'];?></span>
                                                </td>
                                                <td>
                                                    <span class="infoLabel hide">Location</span>
                                                    <span class="infoData"><?php echo $row['locName'];?></span>
                                                </td>
                                                <td>
                                                    <span class="infoLabel hide">Birth Date</span>
                                                    <span class="infoData">
                                                        <?php
                                                        if(isset($row['birthDate']))
                                                        {
                                                            $d = date_create($row['birthDate']); echo date_format($d,DATE_FORMAT_UI);
                                                        }
                                                        else
                                                        {
                                                            echo '';
                                                        }
                                                        ?>
                                                    </span>
                                                </td>
                                                <!--<td class="hide">
                                                    <span class="infoLabel hide">Home Base</span>
                                                    <span class="infoData"><?php /*echo $row['locName'];*/?></span>
                                                </td>-->
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        </tbody>
                                        <?php
                                    }
                                }
                                ?>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    <div id="missingInfoModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit Information</h4>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <form id="saveMissingInfo" action="<?php echo base_url().'mugclub/ajaxSave';?>" method="post" role="form" class="form-horizontal">
                            <input type="hidden" name="mugNum" id="missingMugNum" />
                            <div class="form-group my-marginLR-zero hide" id="missingFirstName">
                                <div class="col-sm-1 col-xs-0"></div>
                                <div class="col-sm-10 col-xs-12">
                                    <input type="text" name="firstName"
                                           class="form-control" placeholder="First Name">
                                </div>
                                <div class="col-sm-1 col-xs-0"></div>
                            </div>
                            <div class="form-group my-marginLR-zero hide" id="missingLastName">
                                <div class="col-sm-1 col-xs-0"></div>
                                <div class="col-sm-10 col-xs-12">
                                    <input type="text" name="lastName"
                                           class="form-control" placeholder="Last Name">
                                </div>
                                <div class="col-sm-1 col-xs-0"></div>
                            </div>
                            <div class="form-group my-marginLR-zero hide" id="missingMobNum">
                                <div class="col-sm-1 col-xs-0"></div>
                                <div class="col-sm-10 col-xs-12">
                                    <input type="text" name="mobNum"
                                           class="form-control" placeholder="Mobile Number">
                                </div>
                                <div class="col-sm-1 col-xs-0"></div>
                            </div>
                            <div class="form-group my-marginLR-zero hide" id="missingEmail">
                                <div class="col-sm-1 col-xs-0"></div>
                                <div class="col-sm-10 col-xs-12">
                                    <input type="email" name="emailId"
                                           class="form-control" placeholder="Email">
                                </div>
                                <div class="col-sm-1 col-xs-0"></div>
                            </div>
                            <div class="form-group my-marginLR-zero hide" id="missingBdate">
                                <div class="col-sm-1 col-xs-0"></div>
                                <div class="col-sm-10 col-xs-12">
                                    <label for="birth_date">Birthdate :</label>
                                    <input id="birth_date" type="date" name="birthdate"
                                           class="form-control" placeholder="Birth Date">
                                </div>
                                <div class="col-sm-1 col-xs-0"></div>
                            </div>
                            <div class="form-group my-marginLR-zero">
                                <div class="col-sm-12 col-xs-12 text-center">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="info-fill-overlay"></div>
<?php echo $footerView; ?>
</body>
<?php echo $globalJs; ?>

<script>
    var myMugDataInfo,checkedInMugNum;
    var dataMissing= [];
    function showForm(ele)
    {
        if($(ele).val() == '1')
        {
            $('.mugInput').removeClass('hide');
            if(!$('.mobileInput').hasClass('hide'))
            {
                $('.mobileInput').addClass('hide');
            }
        }
        /*else
        {
            $('.mobileInput').removeClass('hide');
            if(!$('.mugInput').hasClass('hide'))
            {
                $('.mugInput').addClass('hide');
            }
        }*/
    }

    $(document).on('click','.verify-checkin-btn',function(){
        showCustomLoader();
        $('.mug-already-error').css('margin-bottom','0').empty();
        $(this).css('margin-top','0');
        var selectedInputVal = $('input[name="checkInInput"]:checked').val();
        if(selectedInputVal == '1')
        {
            if($('#mugNum').val() == '')
            {
                hideCustomLoader();
                bootbox.alert("Please Enter Mug Number!");
            }
            else
            {
                ajaxCheckIn({mugNum:$('#mugNum').val()});
                /*if(myMugDataInfo == 'error')
                {
                    ajaxCheckIn({mugNum:$('#mugNum').val()});
                }
                else
                {
                    localCheckIn({mugNum:$('#mugNum').val()});
                }*/
            }
        }
        /*else if(selectedInputVal == '2')
        {
            if($('#mobNum').val() == '')
            {
                hideCustomLoader();
                bootbox.alert("Please Enter Mobile Number!");
            }
            else
            {
                if(myMugDataInfo == 'error')
                {
                    ajaxCheckIn({mobNum:$('#mobNum').val()});
                }
                else
                {
                    localCheckIn({mobNum:$('#mobNum').val()});
                }
            }
        }*/
    });

    function ajaxCheckIn(inputData)
    {
        var errUrl = base_url+'check-ins/verify';
        $.ajax({
            type:"POST",
            dataType:"json",
            url: base_url+'check-ins/verify',
            data: inputData,
            success: function(data)
            {
                hideCustomLoader();
                $('.new-breakfast-status').html('');
                $('.new-checkin-alerts').html('');
                if(data.status === true)
                {
                    var isTodayBirth = false;
                    var myBigStatusHtml = '<ul>';
                    var mugList = data.mugList;

                    var myFormatedData = {
                        'Mug #' : mugList[0].mugId,
                        'Name': mugList[0].firstName +' '+mugList[0].lastName,
                        'Mug Tag': mugList[0].mugTag,
                        'Mobile #': mugList[0].mobileNo,
                        'Email': mugList[0].emailId,
                        'Birth Date': formatJsDate(mugList[0].birthDate),
                        'Home Base': mugList[0].locName,
                        'Expiry Date': formatJsDate(mugList[0].membershipEnd)
                    };

                    for(var mugIndex in myFormatedData)
                    {
                        if(myFormatedData[mugIndex] == '')
                        {
                            dataMissing[mugIndex] =  myFormatedData[mugIndex];
                            myBigStatusHtml += '<li class="my-common-highlighter">'+mugIndex+': '+myFormatedData[mugIndex]+'</li>';
                        }
                        else if(mugIndex == 'Birth Date')
                        {
                            var nowDate = new Date();
                            var mugBirth = new Date(mugList[0].birthDate);
                            if(nowDate.getDate() == mugBirth.getDate() && nowDate.getMonth() == mugBirth.getMonth())
                            {
                                isTodayBirth = true;
                                myBigStatusHtml += '<li>'+mugIndex+': '+myFormatedData[mugIndex]+'<a href="#" id="bday-item" data-toggle="popover" data-content="Mug holder has Birthday Today" data-placement="right">&nbsp;</a></li>';

                            }
                            else
                            {
                                myBigStatusHtml += '<li>'+mugIndex+': '+myFormatedData[mugIndex]+'</li>';
                            }
                        }
                        else
                        {
                            myBigStatusHtml += '<li>'+mugIndex+': '+myFormatedData[mugIndex]+'</li>';
                        }

                    }
                    myBigStatusHtml += '</ul>';
                    $('.mugNumber-status').html(myBigStatusHtml);
                    dataMissing['mugId'] = mugList[0].mugId;
                    checkedInMugNum = mugList[0].mugId;
                    $('.mugCheckIn .final-checkIn-row').removeClass('hide');

                    if(typeof mugList.offerDetails !== 'undefined')//mugList[0].ifActive == '1')
                    {
                        if(mugList.offerDetails.ifActive == '1')
                        {
                            if(mugList.offerDetails.isRedeemed == '0')
                                $('.new-breakfast-status').css('color','green').html('Breakfast Not Redeemed!');
                            else
                                $('.new-breakfast-status').css('color','red').html('Breakfast Already Redeemed!');
                        }
                    }

                    //validity and location check
                    if(checkMemberLocation(mugList[0].homeBase) === true)
                    {
                        if(checkMembershipValidity(mugList[0].membershipEnd) === true)
                        {
                            if(checkMembershipGrace(mugList[0].membershipEnd) === true) // If 1 month Grace Period is also over
                            {
                                $('.new-checkin-alerts').html('Your membership (grace period too) has ended.<br> Please renew your membership to continue the experience of drinking from a mug.');
                            }
                            else // Under the grace period
                            {
                                $('.new-checkin-alerts').html('The grace period to renew your mug is almost coming to an end.<br> Please renew your membership so that you can drink from your mug next time.');
                            }
                            $('.visual-status-icons').removeClass('hide').find('i').addClass('hide');
                            $('.visual-status-icons').find('i:first-child').removeClass('hide my-success-text').addClass('my-danger-text');
                        }
                        else
                        {
                            $('.visual-status-icons').removeClass('hide').find('i').addClass('hide');
                            $('.visual-status-icons').find('i:first-child').removeClass('hide my-danger-text').addClass('my-success-text');
                        }
                        if($('.new-checkin-alerts').html() == '')
                        {
                            $('.new-checkin-alerts').html('Please collect the right tag (number, tag name) and attach it to the mug.');
                        }
                    }
                    else
                    {
                        if (checkMembershipValidity(mugList[0].membershipEnd) === true)
                        {
                            if(checkMembershipGrace(mugList[0].membershipEnd) === true) // If 1 month Grace Period is also over
                            {
                                $('.new-checkin-alerts').html('Your membership (grace period too) has ended.<br> Please renew your membership to continue the experience of drinking from a mug.');
                            }
                            else // Under the grace period
                            {
                                $('.new-checkin-alerts').html('The grace period to renew your mug is almost coming to an end.<br> Please renew your membership so that you can drink from your mug next time.');
                            }

                            $('.visual-status-icons').removeClass('hide').find('i').addClass('hide');
                            $('.visual-status-icons').find('i:last-child').removeClass('hide my-success-text').addClass('my-danger-text');
                        }
                        else
                        {
                            $('.visual-status-icons').removeClass('hide').find('i').addClass('hide');
                            $('.visual-status-icons').find('i:last-child').removeClass('hide my-danger-text').addClass('my-success-text');
                        }
                        if($('.new-checkin-alerts').html() == '')
                        {
                            $('.new-checkin-alerts').html('Please collect a roaming tag and attach it to the mug.');
                        }
                    }

                    fillMissingParams(mugList[0]);
                    checkMissingInfo();

                    //Calling Toast
                    var snackbarContainer = document.querySelector('#mobile-alert-toast');
                    var toastData = {message: 'Please verify the mobile number!',timeout: 5000};
                    snackbarContainer.MaterialSnackbar.showSnackbar(toastData);
                    if(isTodayBirth)
                    {
                        $('[data-toggle="popover"]').popover();
                        $('#bday-item').click();
                    }
                }
                else
                {
                    if(typeof data.pageUrl !== 'undefined')
                    {
                        bootbox.alert('Session Timed Out! Login Again!', function(){
                            window.location.href= data.pageUrl;
                        });
                    }
                    checkedInMugNum = '';
                    $('.mugCheckIn .final-checkIn-row').addClass('hide');
                    $('.visual-status-icons').addClass('hide');
                    $('.mugNumber-status').html('Record Not Found!');
                    hideCustomLoader();
                }

            },
            error: function(xhr, status, error){
                $('.mugCheckIn .final-checkIn-row').addClass('hide');
                $('.visual-status-icons').addClass('hide');
                hideCustomLoader();
                bootbox.alert('Some Error Occurred!');
                var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                saveErrorLog(err);
            }
        });
    }

    function localCheckIn(inputData)
    {
        var mugList = myMugDataInfo.mugList;
        var foundAnyData = 0;

        for(var mugI in mugList)
        {
            if(typeof inputData['mugNum'] != 'undefined')
            {
                if(mugList[mugI].mugId == inputData['mugNum'])
                {
                    foundAnyData = 1;
                    fillMugData(mugList[mugI]);
                    fillMissingParams(mugList[mugI]);
                    checkMissingInfo();
                    return false;
                }
            }
            else if(typeof inputData['mobNum'] != 'undefined')
            {

                if(mugList[mugI].mobileNo == inputData['mobNum'])
                {
                    foundAnyData = 1;
                    fillMugData(mugList[mugI]);
                    fillMissingParams(mugList[mugI]);
                    checkMissingInfo();
                    return false;
                }
            }

        }

        if(foundAnyData == 0)
        {
            checkedInMugNum = '';
            $('.mugCheckIn .final-checkIn-row').addClass('hide');
            $('.visual-status-icons').addClass('hide');
            hideCustomLoader();
            $('.mugNumber-status').html('Record Not Found!');
        }
    }

    function fillMugData(mugList)
    {
       var myBigStatusHtml = '<ul>';
       var myFormatedData = {
            'Mug #' : mugList.mugId,
            'First Name': mugList.firstName,
            'Last Name': mugList.lastName,
            'Mug Tag': mugList.mugTag,
            'Mobile #': mugList.mobileNo,
            'Email': mugList.emailId,
            'Birth Date': formatJsDate(mugList.birthDate),
            'Home Base': mugList.locName,
            'Expiry Date': formatJsDate(mugList.membershipEnd)
        };

        for(var mugIndex in myFormatedData)
        {
            if(myFormatedData[mugIndex] == '')
            {
                myBigStatusHtml += '<li class="my-common-highlighter">'+mugIndex+': '+myFormatedData[mugIndex]+'</li>';
            }
            else
            {
                myBigStatusHtml += '<li>'+mugIndex+': '+myFormatedData[mugIndex]+'</li>';
            }

        }
        myBigStatusHtml += '</ul>';
        $('.mugNumber-status').html(myBigStatusHtml);
        hideCustomLoader();
        checkedInMugNum = mugList.mugId;
        $('.mugCheckIn .final-checkIn-row').removeClass('hide');

        //validity and location check
        if(checkMemberLocation(mugList.homeBase) === true)
        {
            if(checkMembershipValidity(mugList.membershipEnd) === true)
            {
                $('.visual-status-icons').removeClass('hide').find('i').addClass('hide');
                $('.visual-status-icons').find('i:first-child').removeClass('hide my-success-text').addClass('my-danger-text');
            }
            else
            {
                $('.visual-status-icons').removeClass('hide').find('i').addClass('hide');
                $('.visual-status-icons').find('i:first-child').removeClass('hide my-danger-text').addClass('my-success-text');
            }
        }
        else
        {
            if (checkMembershipValidity(mugList.membershipEnd) === true)
            {
                $('.visual-status-icons').removeClass('hide').find('i').addClass('hide');
                $('.visual-status-icons').find('i:last-child').removeClass('hide my-success-text').addClass('my-danger-text');
            }
            else
            {
                $('.visual-status-icons').removeClass('hide').find('i').addClass('hide');
                $('.visual-status-icons').find('i:last-child').removeClass('hide my-danger-text').addClass('my-success-text');
            }
        }
    }

    $(document).ready(function(){
       myMugDataInfo = 'error';
        $('[data-toggle="tooltip"]').tooltip();
        $('[data-toggle="popover"]').popover();
    });

    $(document).on('click','.my-checkIn-search-table td', function(){
        var row_index = $(this).parent().index();
        var selectedRow = $(this).parent()[0];
        var currentRowLocation,membershipEnd;
        var mugList = myMugDataInfo.mugList;

        $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + 'check-ins/verify',
            data: {mugNum: $(selectedRow).find('.mugNumber-info')[0].innerText},
            success: function (data) {
                hideCustomLoader();
                if (data.status === true)
                {
                    var mugList = data.mugList;

                    fillMugData(mugList[0]);
                    fillMissingParams(mugList[0]);
                    checkMissingInfo();
                    $('#searchModal').modal('hide');
                    /*return false;
                    var myFormatedData = {
                        'Mug #': mugList[0].mugId,
                        'Name': mugList[0].firstName + ' ' + mugList[0].lastName,
                        'Mug Tag': mugList[0].mugTag,
                        'Mobile #': mugList[0].mobileNo,
                        'Email': mugList[0].emailId,
                        'Birth Date': formatJsDate(mugList[0].birthDate),
                        'Home Base': mugList[0].locName,
                        'Expiry Date': formatJsDate(mugList[0].membershipEnd)
                    };*/
                }
            }
        });
        /*console.log($(selectedRow).find('.mugNumber-info')[0].innerText);
        for(var mugIndex in mugList)
        {
            if(mugList[mugIndex].mugId == $(selectedRow).find('.mugNumber-info')[0].innerText)
            {
                fillMugData(mugList[mugIndex]);
                fillMissingParams(mugList[mugIndex]);
                checkMissingInfo();
                $('#searchModal').modal('hide');
                return false;
            }
        }*/
    /*    var myBigStatusHtml = '<ul>';
        $(selectedRow).find('td').each(function(i,val){
            if($(val).hasClass('location-info'))
            {
                currentRowLocation = $(val)[0].innerText;
            }
            else if($(val).hasClass('membershipEnd-info'))
            {
                membershipEnd = $(val)[0].innerText;
            }
            else if($(val).hasClass('mugNumber-info'))
            {
                checkedInMugNum = $(val)[0].innerText;
            }
            else
            {
                if($(val).find('.infoData')[0].innerText == '')
                {
                    myBigStatusHtml += '<li class="my-common-highlighter">'+$(val).find('.infoLabel')[0].innerText+': '+$(val).find('.infoData')[0].innerText+'</li>';
                }
                else
                {
                    myBigStatusHtml += '<li>'+$(val).find('.infoLabel')[0].innerText+': '+$(val).find('.infoData')[0].innerText+'</li>';
                }

            }

        });
        myBigStatusHtml += '</ul>';
        $('.mugNumber-status').html(myBigStatusHtml);
        $('.mugCheckIn .final-checkIn-row').removeClass('hide');
        //validity and location check
        if(checkMemberLocation(currentRowLocation) === true)
        {
            if(checkMembershipValidity(membershipEnd) === true)
            {
                $('.visual-status-icons').removeClass('hide').find('i').addClass('hide');
                $('.visual-status-icons').find('i:first-child').removeClass('hide my-success-text').addClass('my-danger-text');
            }
            else
            {
                $('.visual-status-icons').removeClass('hide').find('i').addClass('hide');
                $('.visual-status-icons').find('i:first-child').removeClass('hide my-danger-text').addClass('my-success-text');
            }
        }
        else
        {
            if (checkMembershipValidity(membershipEnd) === true)
            {
                $('.visual-status-icons').removeClass('hide').find('i').addClass('hide');
                $('.visual-status-icons').find('i:last-child').removeClass('hide my-success-text').addClass('my-danger-text');
            }
            else
            {
                $('.visual-status-icons').removeClass('hide').find('i').addClass('hide');
                $('.visual-status-icons').find('i:last-child').removeClass('hide my-danger-text').addClass('my-success-text');
            }
        }*/
    });

    $(document).on('click','.final-checkin-btn', function(){
        if(checkedInMugNum != '')
        {
            var errUrl = base_url+'check-ins/save/json';
            showCustomLoader();
            $.ajax({
                type:"post",
                dataType:"json",
                url:base_url+'check-ins/save/json',
                data:{mugNum:checkedInMugNum},
                success: function(data)
                {
                    hideCustomLoader();
                    if(data.status === true)
                    {
                        bootbox.alert('Successfully Checked In!',function(){
                            window.location.reload();
                        });
                    }
                    else
                    {
                        if(typeof data.pageUrl != 'undefined')
                        {
                            window.location.href = data.pageUrl;
                        }
                        $('.verify-checkin-btn').css('margin-top','25px');
                        $('.mug-already-error').css('margin-bottom','5px').html(data.errorMsg);
                        bootbox.alert(data.errorMsg);
                    }
                },
                error: function(xhr, status, error)
                {
                    hideCustomLoader();
                    bootbox.alert('Some Error Occurred!');
                    var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                    saveErrorLog(err);
                }
            });
        }
    });

    //setInterval(fetchAllMugList,(60*60*1000));

    $(window).load(function(){
        $('#main-mugclub-table').DataTable({ordering:  false});
    });
    $(document).on('click','.my-pointer-item', function(){
        $('.fill-remaining-info').find('a').trigger('click');
        //$('body').removeClass('custom-loader-body');
        //$('.info-fill-overlay').hide();
    });

    function fillMissingParams(mugList)
    {
        dataMissing = [];
        if(mugList.mobileNo == '')
        {
            dataMissing.push('missingMobNum');
        }
        if(mugList.firstName == '')
        {
            dataMissing.push('missingFirstName');
        }
        if(mugList.lastName == '')
        {
            dataMissing.push('missingLastName');
        }
        if(mugList.emailId == '')
        {
            dataMissing.push('missingEmail');
        }
        if(mugList.birthDate == '' || mugList.birthDate == null)
        {
            dataMissing.push('missingBdate');
        }
    }
    function checkMissingInfo()
    {
        if(dataMissing.length != 0)
        {
            $('#missingInfoModal #missingMugNum').val(checkedInMugNum);
            for(var i=0;i<dataMissing.length;i++)
            {
                $('#missingInfoModal #'+dataMissing[i]).removeClass('hide');
            }

            //Fill Info Trigger
            $('.fill-remaining-info').removeClass('hide').find('a').trigger('click');
            //$('body').addClass('custom-loader-body');
            //$('.info-fill-overlay').css('top',$(window).scrollTop()).show();
        }
        else
        {
            $('.fill-remaining-info').addClass('hide');
        }

    }

    $(document).on('submit','#saveMissingInfo', function(e){
        e.preventDefault();
        showCustomLoader();
        var errUrl = $(this).attr('action');
        $.ajax({
            type:"POST",
            dataType:"json",
            url:$(this).attr('action'),
            data:$(this).serialize(),
            success: function(data){
                hideCustomLoader();
                if(data.status === false)
                {
                    bootbox.alert(data.errorMsg);
                }
                else
                {
                    bootbox.alert('Data Saved!');
                    $('#missingInfoModal').modal('hide');
                }
            },
            error: function(xhr, status, error){
                hideCustomLoader();
                bootbox.alert("Some Error Occurred!");
                var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                saveErrorLog(err);
            }
        });
    });
    $(document).on('keypress','#mugNum', function(event){

        var keycode = (event.keyCode ? event.keyCode : event.which);

        if(keycode == 13)
        {
            $('.verify-checkin-btn').trigger('click');
        }
    });
</script>
</html>