<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Location Add :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="locationsAdd">
        <div class="container">
            <div class="row">
                <h2><i class="fa fa-globe"></i> Add New Locations</h2>
                <hr>
                <br>
                <form id="locationsAdd-form" action="<?php echo base_url();?>locations/save" method="post" class="form-horizontal" role="form">
                    <div class="location-status"></div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="locName">Location Name :</label>
                        <div class="col-sm-10">
                            <input type="text" name="locName" class="form-control"
                                    id="locName" placeholder="Eg. Malad"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-2">
                        </div>
                        <div class="col-sm-10">
                            <button type="button" data-toggle="modal" data-target="#mapModal" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent loc-select">Select Place</button>
                            <div class="maps-container">
                                <input name="mapLink" id="mapLink" type="hidden" value="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <!-- Modal -->
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
    $(document).on('submit','#locationsAdd-form', function(e){
       e.preventDefault();
        if($('.maps-container input[name="mapLink"]').val() == "")
        {
            bootbox('please Select Location on Map');
            return false;
        }
        if($('#locName').val() != '')
        {
            showCustomLoader();
            $.ajax({
                type:'get',
                dataType:'json',
                url:'<?php echo base_url();?>locations/checkLocationByUniqueLink/'+$('#locName').val(),
                success: function(data){
                    if(data.status === true)
                    {
                        hideCustomLoader();
                        $.ajax({
                            type:"POST",
                            url:"<?php echo base_url();?>locations/save",
                            data:{locName:$('#locName').val(),mapLink:$('#mapLink').val()},
                            success: function(data){
                                window.location.href=base_url+'locations';
                            },
                            error: function(){
                                $('.location-status').css('color','red').html('Some Error Occurred');
                            }
                        });
                    }
                    else
                    {
                        hideCustomLoader();
                        $('.location-status').css('color','red').html(data.errorMsg);
                    }
                },
                error: function(xhr, status, error){
                    hideCustomLoader();
                    $('.location-status').css('color','red').html('Some Error Occurred');
                    var err = '<pre>'+xhr.responseText+'</pre>';
                    saveErrorLog(err);
                }
            });

        }
        else
        {
            bootbox.alert('Please Provide Location Name');
        }

    });
    var map = new google.maps.Map(document.getElementById('my_map'), {
        center: {lat: -33.8688, lng: 151.2195},
        zoom: 13
    });
    $("#mapModal #mapInput").geocomplete(
        {
            details: "#mapForm",
            map: map,
            types: ["geocode", "establishment"]
        });

    $('#mapModal').on('shown.bs.modal', function() {
        google.maps.event.trigger(map, 'resize');
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