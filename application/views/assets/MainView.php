<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Assets :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="homePage">
        <div class="container-fluid">
            <div class="row">
                <h2 class="text-center">Welcome <?php echo ucfirst($this->userName); ?></h2>
                <br>
                <div class="col-sm-12 col-xs-12">
                    <?php
                    if(myInArray('assets_add',$userModules))
                    {
                        ?>
                        <a href="<?php echo base_url();?>assets/addAsset" class="btn btn-warning">Add New Asset</a>
                        <br>
                        <?php
                    }
                    ?>
                    <?php
                        if(isset($assetsRecord) && myIsArray($assetsRecord))
                        {
                            ?>
                            <table id="assetsTab" class="table table-bordered table-responsive">
                                <thead>
                                    <tr>
                                        <th>Item Type</th>
                                        <th>Item Brand</th>
                                        <th>Location</th>
                                        <th>Item Quantity</th>
                                        <th>Serial Number</th>
                                        <th>Model Number</th>
                                        <th>Purchase Date</th>
                                        <th>Warranty Till</th>
                                        <th>Price</th>
                                        <th>Vendor Name</th>
                                        <th>POC</th>
                                        <th>Assigned To</th>
                                        <th>Status</th>
                                        <th>Comments</th>
                                        <?php
                                        if(myInArray('assets_crud',$userModules))
                                        {
                                            ?>
                                            <th>Actions</th>
                                            <?php
                                        }
                                        ?>
                                    </tr>
                                </thead>
                                <tbody>
                            <?php
                            foreach($assetsRecord as $key => $row)
                            {
                                ?>
                                <tr>
                                    <td><?php echo $row['itemType'];?></td>
                                    <td><?php echo $row['itemBrand'];?></td>
                                    <td>
                                        <?php
                                            if(isset($row['locName']))
                                            {
                                                echo $row['locName'];
                                            }
                                            else
                                            {
                                                echo $row['otherLoc'];
                                            }

                                        ?>
                                    </td>
                                    <td><?php echo $row['itemQty'];?></td>
                                    <td><?php echo $row['serialNum'];?></td>
                                    <td><?php echo $row['modelNum'];?></td>
                                    <td><?php $d = date_create($row['purchaseDate']); echo date_format($d, DATE_FORMAT_UI);?></td>
                                    <td><?php $d = date_create($row['warrantyTill']); echo date_format($d, DATE_FORMAT_UI);?></td>
                                    <td><?php echo 'Rs. '.$row['itemPrice'];?></td>
                                    <td><?php echo $row['vendorName'];?></td>
                                    <td><?php echo $row['pocDoolally'];?></td>
                                    <td><?php echo $row['assignedTo'];?></td>
                                    <td><?php echo $this->config->item('assetsStatus')[$row['currentStatus']];?></td>
                                    <td><?php echo $row['comments'];?></td>
                                    <?php
                                        if(myInArray('assets_crud',$userModules))
                                        {
                                            ?>
                                            <td>
                                                <a data-toggle="tooltip" title="Edit" href="<?php echo base_url().'assets/editItem/'.$row['aId'];?>">
                                                    <i class="glyphicon glyphicon-edit"></i></a>&nbsp;
                                                <a data-toggle="tooltip" class="assetsDelete-icon" title="Delete" data-mugId = "<?php echo $row['aId'];?>">
                                                    <i class="fa fa-trash-o"></i></a>
                                            </td>
                                            <?php
                                        }
                                    ?>
                                </tr>
                                <?php
                            }
                            ?>
                                </tbody>
                            </table>
                            <?php
                        }
                        else
                        {
                            echo 'Nothing Here!';
                        }
                    ?>
                    <br>
                    <form method="POST" class="form hide" id="startQuiz-form">
                        <input type="hidden" name="qid" id="qid"/>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <?php echo $footerView;?>
</body>
<?php echo $globalJs; ?>
<script>
    $('#assetsTab').dataTable();

</script>
</html>