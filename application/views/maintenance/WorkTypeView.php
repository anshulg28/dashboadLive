<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Work Type :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="workType">
        <div class="container">
            <div class="row">
                <div class="col-sm-9 col-xs-8">
                    <a class="btn btn-primary" href="<?php echo base_url().'maintenance/addWorkType';?>">
                        <i class="fa fa-plus"></i>
                        Add New WorkType</a>
                </div>
            </div>
        </div>
        <br>
        <div class="container table-responsive">
            <table id="main-mugclub-table" class="table table-hover table-bordered table-striped">
                <thead>
                <tr>
                    <th>Type #</th>
                    <th>Area Name</th>
                    <th>Sub Types</th>
                    <th>Created Date/Time</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <?php
                if(isset($workTypes) && myIsArray($workTypes))
                {
                    ?>
                    <tbody>
                    <?php
                    foreach($workTypes as $key => $row)
                    {
                        if(isset($row['typeId']))
                        {
                            ?>
                            <tr>
                                <th scope="row"><?php echo $row['typeId'];?></th>
                                <td><?php echo $row['typeName'];?></td>
                                <td><?php echo $row['subTypes'];?></td>
                                <td><?php echo $row['createdDT'];?></td>
                                <td>
                                    <?php
                                    if($row['ifActive'] == '0')
                                    {
                                        echo 'Not Active';
                                    }
                                    else
                                    {
                                        echo 'Active';
                                        ?>
                                        <a href="<?php echo base_url();?>maintenance/editWorkType/<?php echo $row['typeId'];?>">
                                            <i class="fa fa-1x fa-edit my-success-text"></i></a>
                                        <?php
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }

                    }
                    ?>
                    </tbody>
                    <?php
                }
                else
                {
                    ?>
                    <tbody>
                    <tr class="my-danger-text text-center">
                        <td colspan="5">No Data Found!</td>
                    </tr>
                    </tbody>
                    <?php
                }
                ?>
            </table>
        </div>
    </main>
</body>
<?php echo $globalJs; ?>

<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });

    if(localStorageUtil.getLocal('tabPage') != null)
    {
        var mugClubTab =  $('#main-mugclub-table').DataTable({
            "displayStart": localStorageUtil.getLocal('tabPage') * 10
        });
        localStorageUtil.delLocal('tabPage');
    }
    else
    {
        var mugClubTab =  $('#main-mugclub-table').DataTable();
    }
</script>
</html>