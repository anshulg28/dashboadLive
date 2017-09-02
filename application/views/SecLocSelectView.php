<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Location :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="locSelectPage">
        <div class="container-fluid">
            <div class="row">
                <h2 class="text-center">Select Your Location</h2>
                <br>

                <form id="locationForm" method="post" action="<?php echo base_url().'dashboard/setCommLocation';?>">
                    <div class="col-sm-12 text-center">
                        <ul class="list-inline my-mainMenuList">
                            <?php
                                foreach($locData as $key => $row)
                                {
                                    ?>
                                    <li>
                                        <input type="radio" name="currentLoc" onchange="submitLocation()" id="<?php echo $row['locUniqueLink'];?>" value="<?php echo $row['id'];?>" />
                                        <label for="<?php echo $row['locUniqueLink'];?>">
                                            <i class="glyphicon glyphicon-map-marker fa-5x"></i>
                                            <br>
                                            <span><?php echo $row['locName'];?></span>
                                        </label>
                                    </li>
                                    <?php
                                }
                            ?>
                            <!--<li>
                                <input type="radio" name="currentLoc" onchange="submitLocation()" id="bandra" value="1" />
                                <label for="bandra">
                                    <i class="glyphicon glyphicon-map-marker fa-5x"></i>
                                    <br>
                                    <span>Bandra</span>
                                </label>
                            </li>-->
                        </ul>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
<?php echo $globalJs; ?>
<script>
    function submitLocation()
    {
        $('#locationForm').submit();
    }
</script>
</html>