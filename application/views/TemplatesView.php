<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Templates :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="templates">
        <div class="container">
            <div class="row">
                <div class="col-sm-9 col-xs-8">
                    <a class="btn btn-primary" href="<?php echo base_url().'mailers/templateAdd';?>">
                        <i class="fa fa-plus"></i>
                        Add New Template</a>
                </div>
            </div>
        </div>
        <br>
        <div class="container">
            <table id="main-template-table" class="table table-hover table-bordered table-striped paginated">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Mail Subject</th>
                    <th>Mail Body</th>
                    <th>Mail Type</th>
                    <th>Date & Time</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <?php
                if(isset($templates) && myIsArray($templates))
                {
                    ?>
                    <tbody>
                    <?php
                        foreach($templates as $key => $row)
                        {
                            ?>
                            <tr>
                                <th scope="row"><?php echo $row['id'];?></th>
                                <td><?php echo $row['mailSubject'];?></td>
                                <td><?php echo $row['mailBody'];?></td>
                                <td>
                                    <?php
                                        switch($row['mailType'])
                                        {
                                            case EXPIRED_MAIL:
                                                echo 'Expired';
                                                break;
                                            case EXPIRING_MAIL:
                                                echo 'Expiring';
                                                break;
                                            case BIRTHDAY_MAIL:
                                                echo 'Birthday';
                                                break;
                                            case CUSTOM_MAIL:
                                                echo 'Custom';
                                                break;
                                            default:
                                                echo 'Unknown';
                                        }
                                    ?>
                                </td>
                                <td><?php $d = date_create($row['insertedDT']); echo date_format($d,DATE_TIME_FORMAT_UI);?></td>
                                <td><a data-toggle="tooltip" title="Edit" href="<?php echo base_url().'mailers/templateEdit/'.$row['id'];?>">
                                        <i class="glyphicon glyphicon-edit"></i></a>&nbsp;
                                </td>
                            </tr>
                            <?php
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
                        <td colspan="6">No Templates</td>
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

    $('#main-template-table').DataTable({
        "ordering": false
    });
</script>
</html>