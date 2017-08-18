<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Mug Club Edit :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="mugClub">
        <div class="container">
            <div class="row">
                <?php
                    if(isset($mugInfo) && myIsArray($mugInfo))
                    {
                        if($mugInfo['status'] === true)
                        {
                            foreach($mugInfo['checkInList'] as $key => $row)
                            {
                                if(isset($row['id']))
                                {
                                    ?>
                                    <h2><i class="fa fa-beer fa-1x"></i> Edit Check-In #<?php echo $row['id'];?></h2>
                                    <hr>
                                    <br>
                                    <form action="<?php echo base_url();?>check-ins/save/return" method="post" class="form-horizontal" role="form">
                                        <div class="mugNumber-status text-center"></div>
                                        <input type="hidden" name="checkId" value="<?php echo $row['id'];?>"/>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="mugNum">Mug No. :</label>
                                            <div class="col-sm-10">
                                                <input type="number" name="mugNum" class="form-control"
                                                       value="<?php echo $row['mugId'];?>" id="mugNum" placeholder="Eg. 100">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="baseLocation">Location:</label>
                                            <div class="col-sm-10">
                                                <?php
                                                if(isset($baseLocations))
                                                {
                                                    ?>
                                                    <select class="form-control" id="baseLocation" name="baseLocation">
                                                        <?php
                                                        foreach($baseLocations as $lockey => $locrow)
                                                        {
                                                            ?>
                                                            <option value="<?php echo $locrow['id'];?>" <?php if($row['location'] == $locrow['id'] ){echo 'selected';}?>>
                                                                <?php echo $locrow['locName'];?>
                                                            </option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="datetimepicker1">Invoice Date:</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="checkinDateTime" value="<?php echo $row['checkinDateTime'];?>"
                                                       class="form-control" id='datetimepicker1' placeholder="Eg. 12 June 2016, 7:00 am">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <button type="submit" class="btn btn-primary">Submit</button>
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
                    }
                ?>
            </div>
        </div>
    </main>
</body>
<?php echo $globalJs; ?>
<script>
    $(function () {
        $('#datetimepicker1').datetimepicker();
    });
</script>
</html>