<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Offer Generator :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="offerPage">
        <div class="container">
            <div class="row my-marginLR-zero">
                <h2><i class="fa fa-cogs"></i> Generate Offer Codes</h2>
                <a href="<?php echo base_url().'offers';?>" class="btn btn-warning"><i class="fa fa-arrow-circle-o-left"></i> Go Back</a>
                <hr>
                <br>
                <form id="offerGen-form" action="<?php echo base_url();?>offers/createCodes/json" method="post" class="form-horizontal" role="form">
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="beerNums">Number of Beer Codes :</label>
                        <div class="col-sm-10">
                            <input type="number" name="beerNums" class="form-control"
                                    id="beerNums" placeholder="Eg. 50"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="breakNums">Number of Breakfast Codes :</label>
                        <div class="col-sm-10">
                            <input type="number" name="breakNums" class="form-control"
                                   id="breakNums" placeholder="Eg. 50"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="eventNums">Number of Event Codes :</label>
                        <div class="col-sm-10">
                            <input type="number" name="eventNums" class="form-control"
                                   id="eventNums" placeholder="Eg. 50"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-10">
                            <label for="is_custom">
                                <input type="checkbox" onchange="toggleEmailField(this)" id="is_custom" name="customCode" value="1">
                                Custom Codes
                            </label>
                            <ul class="list-inline custom-input-list hide">
                                <li>
                                    <input type="number" name="customNums" class="form-control"
                                           id="customNums" placeholder="Eg. 50"/>
                                </li>
                                <li>
                                    <input type="text" name="customName" class="form-control"
                                           id="customName" placeholder="Eg. Beer"/>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary">Generate</button>
                            <p class="my-display-inline">Today's Generated Codes:
                                <?php
                                if($todayCount['status'] === true)
                                {
                                    echo count($todayCount['codes']);
                                    ?>
                                    <button type="button" data-toggle="modal" data-target="#todayModal" class="btn btn-danger">View</button>
                                    <?php
                                }
                                else
                                {
                                    echo '0';
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </form>
                <br>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="geneCodes">Generated Codes :</label>
                    <div class="col-sm-10">
                        <textarea class="form-control"
                               id="geneCodes" readonly></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10 my-marginUp">
                        <button type="button" class="btn btn-success" onclick="exporttocsv()">Export To Excel</button>
                    </div>
                </div>
                <div class="hide" id="exportTab">
                    <?php
                        if($todayCount['status'] === true)
                        {
                            ?>
                            <table>
                                <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Code</th>
                                    <th>Type</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach($todayCount['codes'] as $key => $row)
                                {
                                    ?>
                                    <tr>
                                        <td><?php echo date('d/m/Y');?></td>
                                        <td>
                                            <?php
                                            switch($row['offerType'])
                                            {
                                                case 'Breakfast2':
                                                    echo 'BR-'.$row['offerCode'];
                                                    break;
                                                case 'Beer':
                                                    echo 'DO-'.$row['offerCode'];
                                                    break;
                                                case 'Workshop':
                                                    echo 'EV-'.$row['offerCode'];
                                                    break;
                                                default:
                                                    echo 'DO-'.$row['offerCode'];
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            if($row['offerType'] == 'Breakfast2')
                                            {
                                                echo 'Breakfast For Two';
                                            }
                                            else
                                            {
                                                echo $row['offerType'];
                                            }
                                            ?>
                                        </td>
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

            </div>
        </div>
    </main>
    <div id="todayModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Today's Offer Codes</h4>
                </div>

                <div class="modal-body">
                    <div class="row my-marginLR-zero" id="exportModal">
                        <?php
                            if($todayCount['status'] === false)
                            {
                                echo 'No Codes Found!';
                            }
                            else
                            {
                                ?>
                                <table id="main-mugclub-table" class="table table-hover table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Code</th>
                                            <th>Type</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        foreach($todayCount['codes'] as $key => $row)
                                        {
                                            ?>
                                            <tr>
                                                <td><?php echo date('d/m/Y');?></td>
                                                <td>
                                                    <?php
                                                    switch($row['offerType'])
                                                    {
                                                        case 'Breakfast2':
                                                            echo 'BR-'.$row['offerCode'];
                                                            break;
                                                        case 'Beer':
                                                            echo 'DO-'.$row['offerCode'];
                                                            break;
                                                        case 'Workshop':
                                                            echo 'EV-'.$row['offerCode'];
                                                            break;
                                                        default:
                                                            echo 'DO-'.$row['offerCode'];
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if($row['offerType'] == 'Breakfast2')
                                                    {
                                                        echo 'Breakfast For Two';
                                                    }
                                                    else
                                                    {
                                                        echo $row['offerType'];
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    ?>
                                    </tbody>
                                </table>
                                <br>
                                <button type="button" class="btn btn-success" onclick="exporttocsv()">Export To Excel</button>
                                <?php
                            }
                        ?>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
<?php echo $footerView; ?>
</body>
<?php echo $globalJs; ?>

<script>
    $(document).on('submit','#offerGen-form', function(e){
       e.preventDefault();
        if($('input[name="customCode"]').is(':checked'))
        {
            if($('input[name="customName"]').val() == '')
            {
                $('input[name="customName"]').focus();
                return false;
            }
        }
        showCustomLoader();
        $.ajax({
            type:'POST',
            dataType:'json',
            url:$(this).attr('action'),
            data:$(this).serialize(),
            success: function(data){
                hideCustomLoader();
                if(typeof data === 'undefined' || data == '')
                {
                    $('.location-status').css('color','red').html('Some Error Occurred');
                }
                else
                {
                    var myCodesHtml = '';
                    var tableCodes = '<table><thead><tr><th>Date</th><th>Offer Code</th><th>Type</th></tr></thead><tbody>';
                    for(var num in data)
                    {
                        if(data[num].type == 'Breakfast2')
                        {
                            myCodesHtml += 'BR-'+data[num].code+'&#13;&#10;';
                        }
                        else if(data[num].type == 'Workshop')
                        {
                            myCodesHtml += 'EV-'+data[num].code+'&#13;&#10;';
                        }
                        else
                        {
                            myCodesHtml += 'DO-'+data[num].code+'&#13;&#10;';
                        }
                        tableCodes += '<tr><td><?php echo date('d/m/Y');?></td><td>';
                        if(data[num].type == 'Breakfast2')
                        {
                            tableCodes += 'BR-'+data[num].code;
                        }
                        else if(data[num].type == 'Workshop')
                        {
                            tableCodes += 'EV-'+data[num].code;
                        }
                        else
                        {
                            tableCodes += 'DO-'+data[num].code;
                        }
                        tableCodes += '</td><td>'+data[num].type+'</td></tr>';
                    }
                    tableCodes += '</tbody></table>';
                    $('#exportTab').html(tableCodes);
                    $('#geneCodes').html(myCodesHtml);
                }
            },
            error: function(xhr, status, error){
                hideCustomLoader();
                $('.location-status').css('color','red').html('Some Error Occurred');
                var err = '<pre>'+xhr.responseText+'</pre>';
                saveErrorLog(err);
            }
        });

    });

    $(document).on('keypress','#beerNums,#breakNums', function(event){

        var keycode = (event.keyCode ? event.keyCode : event.which);

        if(keycode == 45)
        {
            return false;
        }
    });
    function exporttocsv() {

        if($('#exportTab').html() != '')
        {
            var a = document.createElement('a');
            with (a) {
                href='data:application/vnd.ms-excel,' + $('#exportTab').html();
                download="<?php echo date('d/m/Y');?>_codes.xls";
            }
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }
    }

    $(document).ready(function(){
        $('#main-mugclub-table').DataTable();
    });

    function toggleEmailField(ele)
    {
        if($(ele).is(':checked'))
        {
            $('.custom-input-list').removeClass('hide');
        }
        else
        {
            $('.custom-input-list').addClass('hide');
        }
    }

</script>
</html>