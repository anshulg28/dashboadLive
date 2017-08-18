<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Users :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="userList">
        <div class="container">
            <div class="row">
                <div class="col-sm-9 col-xs-8">
                    <a class="btn btn-primary adduser-btn" href="<?php echo base_url().'users/add';?>">
                        <i class="fa fa-plus"></i>
                        Add New User</a>
                </div>
            </div>
        </div>
        <br>
        <div class="container">
            <table id="main-mugclub-table" class="table table-hover table-bordered table-striped paginated">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Username</th>
                    <th>Name</th>
                    <th>Roles</th>
                    <th>Created</th>
                    <th>Last Updated</th>
                    <th>Last Updated By</th>
                    <th>Last Login</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <?php
                if(isset($userData) && myIsArray($userData))
                {
                    if($userData['status'] === false)
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
                            foreach($userData as $key => $row)
                            {
                                if(isset($row['userId']))
                                {
                                    ?>
                                    <tr>
                                        <th scope="row"><?php echo $row['userId'];?></th>
                                        <td><?php echo $row['userName'];?></td>
                                        <td><?php echo ucfirst($row['firstName']) .' '.ucfirst($row['lastName']);?></td>
                                        <td>
                                            <?php
                                                switch($row['userType'])
                                                {
                                                    case ROOT_USER:
                                                        echo 'Root';
                                                        break;
                                                    case ADMIN_USER:
                                                        echo 'Admin';
                                                        break;
                                                    case EXECUTIVE_USER:
                                                        echo 'Executive';
                                                        break;
                                                    case SERVER_USER:
                                                        echo 'Server';
                                                        break;
                                                    case GUEST_USER:
                                                        echo 'Guest';
                                                        break;
                                                    case WALLET_USER:
                                                        echo 'Wallet Manager';
                                                        break;
                                                    case OFFERS_USER:
                                                        echo 'Guest';
                                                }
                                            ?>
                                        </td>
                                        <td><?php echo $row['insertedDate'];?></td>
                                        <td><?php echo $row['updateDate'];?></td>
                                        <td><?php echo $row['updatedBy'];?></td>
                                        <td><?php echo $row['lastLogin'];?></td>
                                        <td>
                                            <?php
                                                if($row['userId'] != 1)
                                                {
                                                    ?>
                                                    <a data-toggle="tooltip" title="Edit" href="<?php echo base_url().'users/edit/'.$row['userId'];?>">
                                                        <i class="glyphicon glyphicon-edit"></i></a>&nbsp;
                                                    <a data-toggle="tooltip" class="mugDelete-icon" title="Delete" data-userId = "<?php echo $row['userId'];?>">
                                                        <i class="fa fa-trash-o"></i></a>&nbsp;
                                                    <?php
                                                        if($row['ifActive'] == ACTIVE)
                                                        {
                                                            if($row['userName'] == $this->userName)
                                                            {
                                                                ?>
                                                                <a data-toggle="tooltip" title="Activated" href="#">
                                                                    <i class="fa fa-lightbulb-o my-success-text"></i></a>
                                                                <?php
                                                            }
                                                            else
                                                            {
                                                                ?>
                                                                <a data-toggle="tooltip" title="Activated" href="<?php echo base_url().'users/setDeActive/'.$row['userId'];?>">
                                                                    <i class="fa fa-lightbulb-o my-success-text"></i></a>
                                                                <?php
                                                            }
                                                        }
                                                        else
                                                        {
                                                            if($row['userName'] == $this->userName)
                                                            {
                                                                ?>
                                                                <a data-toggle="tooltip" title="Activated" href="#">
                                                                    <i class="fa fa-lightbulb-o my-success-text"></i></a>
                                                                <?php
                                                            }
                                                            else
                                                            {
                                                                ?>
                                                                <a data-toggle="tooltip" title="DeActivated" href="<?php echo base_url().'users/setActive/'.$row['userId'];?>">
                                                                    <i class="fa fa-lightbulb-o my-danger-text"></i></a>
                                                                <?php
                                                            }
                                                        }
                                                }
                                                else
                                                {
                                                    ?>
                                                    <a data-toggle="tooltip" title="Edit" href="#">
                                                        <i class="glyphicon glyphicon-edit"></i></a>&nbsp;
                                                    <a data-toggle="tooltip" href="#" title="Delete">
                                                        <i class="fa fa-trash-o"></i></a>&nbsp;
                                                    <?php
                                                        if($row['ifActive'] == ACTIVE)
                                                        {
                                                            ?>
                                                            <a data-toggle="tooltip" title="Activated" href="#">
                                                                <i class="fa fa-lightbulb-o my-success-text"></i></a>
                                                            <?php
                                                        }
                                                        else
                                                        {
                                                            ?>
                                                            <a data-toggle="tooltip" title="DeActivated" href="#">
                                                                <i class="fa fa-lightbulb-o my-danger-text"></i></a>
                                                            <?php
                                                        }
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
                }
                ?>
            </table>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-sm-9 col-xs-8">
                    <a class="btn btn-primary adduser-btn" href="<?php echo base_url().'users/add';?>">
                        <i class="fa fa-plus"></i>
                        Add New User</a>
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
        var mugId = $(this).attr('data-userId');
        bootbox.confirm("Are you sure you want to delete User #"+mugId+" ?", function(result) {
            if(result === true)
            {
                window.location.href='<?php echo base_url();?>users/delete/'+mugId;
            }
        });
    });

    $('#main-mugclub-table').DataTable();
</script>
</html>