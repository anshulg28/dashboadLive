<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Location Edit :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="locationsAdd">
        <div class="container">
            <div class="row">
                <?php
                    if(isset($locInfo) && myIsArray($locInfo))
                    {
                        if($locInfo['status'] === true)
                        {
                            foreach($locInfo['locData'] as $key => $row)
                            {
                                if(isset($row['id']))
                                {
                                    ?>
                                    <h2><i class="fa fa-globe"></i> Edit Location <?php echo $row['locName'];?></h2>
                                    <hr>
                                    <br>
                                    <form id="locationsEdit-form" action="<?php echo base_url();?>locations/save" method="post" class="form-horizontal" role="form">
                                        <div class="location-status"></div>
                                        <input type="hidden" name="id" value="<?php echo $row['id'];?>"/>
                                        <input type="hidden" name="locUniqueLink" value="<?php echo $row['locUniqueLink'];?>"/>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="locName">Location Name :</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="locName" class="form-control"
                                                       value="<?php echo $row['locName'];?>" id="locName" placeholder="Eg. Malad"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-2">
                                            </div>
                                            <div class="col-sm-10">
                                                <button type="button" data-toggle="modal" data-target="#mapModal" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent loc-select">Select Place</button>
                                                <div class="maps-container">
                                                    <input name="mapLink" class="my-marginUp" placeholder="Map Link" id="mapLink" type="text" value="<?php echo $row['mapLink'];?>" required>
                                                </div>
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
                            echo '<h2 class="my-danger-text text-center>Location Not Found!</h2>"';
                        }
                    }
                ?>
            </div>
        </div>
    </main><!-- Modal -->
    <div id="mapModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Place Selection</h4>
                </div>
                <div class="modal-body">
                    <input type="text" id="mapInput" class="my-fullWidth"/>
                    <br>
                    <div id="my_map"></div>
                    <form id="mapForm" class="hide">
                        Latitude:   <input name="lat" type="text" value="">
                        Longitude:  <input name="lng" type="text" value="">
                        Address:    <input name="formatted_address" type="text" value="">
                    </form>
                </div>
                <div class="modal-footer">
                    <button onclick="addPlaces()" type="button" class="btn btn-default" data-dismiss="modal">Done</button>
                </div>
            </div>

        </div>
    </div>

</body>
<?php echo $globalJs; ?>
<script>
    $("#mapModal #mapInput").geocomplete(
        {
            details: "#mapForm",
            map: "#my_map",
            types: ["geocode", "establishment"]
        });
    function addPlaces()
    {
        var lat = $('#mapForm input[name="lat"]').val();
        var lng = $('#mapForm input[name="lng"]').val();
        var adds = $('#mapInput').val();

        if(lat != '' && lng != '' && adds != '')
        {
            //https://www.google.com/maps/place/19.0979741,72.8273923
            $('.maps-container input[name="mapLink"]').val('https://www.google.com/maps/place/'+lat+','+lng);
            $('.loc-select').html('Done');
        }
    }
</script>
</html>