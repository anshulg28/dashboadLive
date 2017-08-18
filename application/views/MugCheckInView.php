<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Mug Check-In :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="mugClub">
        <?php
            if($this->userType != EXECUTIVE_USER)
            {
                ?>
                <div class="container">
                    <div class="row">
                        <div class="col-sm-9 col-xs-8">
                            <a class="btn btn-primary" href="<?php echo base_url().'check-ins/add';?>">
                                <i class="fa fa-plus"></i>
                                New Check-In</a>
                        </div>
                    </div>
                </div>
                <?php
            }
        ?>
        <br>
        <div class="container">
            <table id="main-mugclub-table" class="table table-hover table-bordered table-striped paginated">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Mug #</th>
                    <th>Location</th>
                    <th>Date & Time</th>
                    <th>Actions</th>
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
                                <td colspan="5">No Today Check-Ins!</td>
                            </tr>
                        </tbody>
                        <?php
                    }
                    else
                    {
                        ?>
                        <tbody>
                        <?php
                            foreach($mugData['checkInList'] as $key => $row)
                            {
                                if(isset($row['id']))
                                {
                                    ?>
                                    <tr>
                                        <th scope="row"><?php echo $row['id'];?></th>
                                        <td>
                                            <?php echo $row['firstName'].' '.$row['lastName'];?>
                                        </td>
                                        <td><?php echo $row['mugId'];?></td>
                                        <td><?php echo $row['locName'];?></td>
                                        <td><?php $d = date_create($row['checkinDateTime']); echo date_format($d,DATE_TIME_FORMAT_UI);?></td>
                                        <td><a data-toggle="tooltip" title="Edit" href="<?php echo base_url().'check-ins/edit/'.$row['id'];?>">
                                                <i class="glyphicon glyphicon-edit"></i></a>&nbsp;
                                            <a data-toggle="tooltip" class="mugDelete-icon" title="Delete" data-mugId = "<?php echo $row['id'];?>">
                                                <i class="fa fa-trash-o"></i></a>
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
    </main>
</body>
<?php echo $globalJs; ?>

<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
    $(document).on('click','.mugDelete-icon',function(){
        var mugId = $(this).attr('data-mugId');
        bootbox.confirm("Are you sure you want to delete Check-In Entry #"+mugId+" ?", function(result) {
            if(result === true)
            {
                window.location.href='<?php echo base_url();?>check-ins/delete/'+mugId;
            }
        });
    });

    $('#main-mugclub-table').DataTable({
        "ordering": false
    });
</script>
</html>