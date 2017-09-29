<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Offers Stats :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="offerPage">
        <div class="container">
            <div class="row my-marginLR-zero">
                <div class="col-sm-9 col-xs-8">
                    <a href="<?php echo base_url().'offers';?>" class="btn btn-warning"><i class="fa fa-arrow-circle-o-left"></i> Go Back</a>
                </div>
            </div>
        </div>
        <br>
        <div class="container">
            <h2>Offer Stats</h2>
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#new">New Offers</a></li>
                <li><a data-toggle="tab" href="#old">Old Offers</a></li>
            </ul>
            <br>
            <div class="tab-content">
                <div id="new" class="tab-pane fade in active">
                    <?php
                        if(isset($newOfferStats) && myIsArray($newOfferStats))
                        {
                            ?>
                                <ul class="list-inline text-center">
                                    <li>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">Total Beer Redeemed</div>
                                            <div class="panel-body stats-nums"><?php echo $newOfferStats['offerStat']['TBeer'];?>/
                                                <?php echo $newOfferStats['offerStat']['TCBeer'];?></div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">Monthly Beer Redeemed</div>
                                            <div class="panel-body stats-nums"><?php echo $newOfferStats['offerStat']['MBeer'];?></div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">Total Breakfast Redeemed</div>
                                            <div class="panel-body stats-nums"><?php echo $newOfferStats['offerStat']['TBreakfast'];?>/
                                                <?php echo $newOfferStats['offerStat']['TCBreakfast'];?></div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">Monthly Breakfast Redeemed</div>
                                            <div class="panel-body stats-nums"><?php echo $newOfferStats['offerStat']['MBreakfast'];?></div>
                                        </div>
                                    </li>
                                </ul>
                            <?php
                        }
                    ?>
                    <table id="new-offers-table" class="table table-hover table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Offer Code</th>
                            <th>Offer Type</th>
                            <th>Location</th>
                            <th>Created Date</th>
                            <th>Used Date</th>
                            <th>Actions</th>
                        </tr>
                        </thead>

                    </table>
                </div>
                <div id="old" class="tab-pane fade">
                    <?php
                    if(isset($oldOfferStats) && myIsArray($oldOfferStats))
                    {
                        ?>
                        <ul class="list-inline text-center">
                            <li>
                                <div class="panel panel-default">
                                    <div class="panel-heading">Total Beer Redeemed</div>
                                    <div class="panel-body stats-nums"><?php echo $oldOfferStats['offerStat']['TBeer'];?>/
                                        <?php echo $oldOfferStats['offerStat']['TCBeer'];?></div>
                                </div>
                            </li>
                            <li>
                                <div class="panel panel-default">
                                    <div class="panel-heading">Monthly Beer Redeemed</div>
                                    <div class="panel-body stats-nums"><?php echo $oldOfferStats['offerStat']['MBeer'];?></div>
                                </div>
                            </li>
                            <li>
                                <div class="panel panel-default">
                                    <div class="panel-heading">Total Breakfast Redeemed</div>
                                    <div class="panel-body stats-nums"><?php echo $oldOfferStats['offerStat']['TBreakfast'];?>/
                                        <?php echo $oldOfferStats['offerStat']['TCBreakfast'];?></div>
                                </div>
                            </li>
                            <li>
                                <div class="panel panel-default">
                                    <div class="panel-heading">Monthly Breakfast Redeemed</div>
                                    <div class="panel-body stats-nums"><?php echo $oldOfferStats['offerStat']['MBreakfast'];?></div>
                                </div>
                            </li>
                        </ul>
                        <?php
                    }
                    ?>
                    <table id="old-offers-table" class="table table-hover table-bordered table-striped paginated">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Offer Code</th>
                            <th>Offer Type</th>
                            <th>Location</th>
                            <th>Created Date</th>
                            <th>Used Date</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <?php
                        if(isset($oldOffersCodes) && myIsArray($oldOffersCodes))
                        {
                            if($oldOffersCodes['status'] === false)
                            {
                                ?>
                                <tbody>
                                <tr class="my-danger-text text-center">
                                    <td colspan="6">No Data Found!</td>
                                </tr>
                                </tbody>
                                <?php
                            }
                            else
                            {
                                ?>
                                <tbody>
                                <?php
                                foreach($oldOffersCodes['codes'] as $key => $row)
                                {
                                    ?>
                                    <tr class="<?php if($row['isRedeemed'] == 1) {echo 'danger';};?>">
                                        <th scope="row"><?php echo $row['id'];?></th>
                                        <td><?php echo 'TW-'.$row['offerCode'];?></td>
                                        <td><?php echo $row['offerType'];?></td>
                                        <td><?php echo $row['locName'];?></td>
                                        <td><?php echo $row['createDateTime'];?></td>
                                        <td><?php echo $row['useDateTime'];?></td>
                                        <td>
                                            <a data-toggle="tooltip" class="oldmugDelete-icon" title="Delete" data-offerId="<?php echo $row['id'];?>"
                                               data-offerCode= "<?php echo $row['offerCode'];?>">
                                                <i class="fa fa-trash-o"></i></a>&nbsp;
                                            <?php
                                                if($row['isRedeemed'] == 1)
                                                {
                                                    ?>
                                                    <a data-toggle="tooltip" title="Renew" href="<?php echo base_url().'offers/oldOfferUnused/'.$row['id'];?>">
                                                        <i class="fa fa-repeat"></i></a>
                                                    <?php
                                                }
                                            ?>
                                        </td>
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
    </main>
</body>
<?php echo $globalJs; ?>

<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
    $(document).on('click','.mugDelete-icon',function(){
        var locName = $(this).attr('data-offerCode');
        var locId = $(this).attr('data-offerId');
        bootbox.confirm("Are you sure you want to delete Offer Code "+locName+" ?", function(result) {
            if(result === true)
            {
                window.location.href='<?php echo base_url();?>offers/delete/'+locId+'/new';
            }
        });
    });
    $(document).on('click','.oldmugDelete-icon',function(){
        var locName = $(this).attr('data-offerCode');
        var locId = $(this).attr('data-offerId');
        bootbox.confirm("Are you sure you want to delete Offer Code "+locName+" ?", function(result) {
            if(result === true)
            {
                window.location.href='<?php echo base_url();?>offers/delete/'+locId+'/old';
            }
        });
    });

    $(' #old-offers-table').DataTable({
        ordering: false,
        deferRender: true

    });
    var isOnce = false;
    $(window).load(function(){
        var newTab = $('#new-offers-table').DataTable({
            ordering: false,
            deferRender: true,
            ajax: base_url+'offers/getOfferStats'
        });

        newTab.on('draw', function () {
            $('.repeat-coupon').each(function(i,val){
                $(val).parent().parent().addClass('danger');
            });
        } )
    });
</script>
</html>