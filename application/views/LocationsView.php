<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Locations :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="locationsList">
        <div class="container">
            <div class="row">
                <div class="col-sm-9 col-xs-8">
                    <a class="btn btn-primary adduser-btn" href="<?php echo base_url().'locations/add';?>">
                        <i class="fa fa-plus"></i>
                        Add New Location</a>
                </div>
            </div>
        </div>
        <br>
        <div class="container">
            <table id="main-mugclub-table" class="table table-hover table-bordered table-striped paginated">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Location</th>
                    <th>Created</th>
                    <th>Last Updated</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <?php
                if(isset($storeLocations) && myIsArray($storeLocations))
                {
                    if($storeLocations['status'] === false)
                    {
                        ?>
                        <tbody>
                            <tr class="my-danger-text text-center">
                                <td colspan="9">No Data Found!</td>
                            </tr>
                        </tbody>
                        <?php
                    }
                    else
                    {
                        ?>
                        <tbody>
                        <?php
                            foreach($storeLocations as $key => $row)
                            {
                                if(isset($row['id']))
                                {
                                    ?>
                                    <tr>
                                        <th scope="row"><?php echo $row['id'];?></th>
                                        <td><?php echo $row['locName'];?></td>
                                        <td><?php echo $row['insertedDate'];?></td>
                                        <td><?php echo $row['lastUpdate'];?></td>
                                        <td>
                                            <a data-toggle="tooltip" title="Edit" href="<?php echo base_url().'locations/edit/'.$row['id'];?>">
                                                <i class="glyphicon glyphicon-edit"></i></a>&nbsp;
                                            <a data-toggle="tooltip" class="mugDelete-icon" title="Delete" data-locId="<?php echo $row['id'];?>"
                                               data-locName= "<?php echo $row['locName'];?>">
                                                <i class="fa fa-trash-o"></i></a>&nbsp;
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                        ?>
                        </tbody>
                        <?php
                    }
                }
                ?>
            </table>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-sm-9 col-xs-8">
                    <a class="btn btn-primary adduser-btn" href="<?php echo base_url().'locations/add';?>">
                        <i class="fa fa-plus"></i>
                        Add New Location</a>
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
        var locName = $(this).attr('data-locName');
        var locId = $(this).attr('data-locId');
        bootbox.confirm("Are you sure you want to delete Location "+locName+" ?", function(result) {
            if(result === true)
            {
                window.location.href='<?php echo base_url();?>locations/delete/'+locId;
            }
        });
    });

    $('#main-mugclub-table').DataTable();
</script>
</html>