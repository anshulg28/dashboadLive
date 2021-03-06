<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Assets Add :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="assets">
        <div class="container">
            <div class="row">
                <a href="<?php echo base_url().'assets';?>" class="btn btn-default">GO Back</a>
                <br>
                <form action="<?php echo base_url();?>assets/saveAsset" method="post" class="form-horizontal" role="form">
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="itemType">Item Type :</label>
                        <div class="col-sm-10">
                            <input type="text" name="itemType" class="form-control" id="itemType" placeholder="Laptop" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="itemBrand">Item Brand: </label>
                        <div class="col-sm-10">
                            <input type="text" name="itemBrand" class="form-control" id="itemBrand" placeholder="Eg. Lenovo" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="locId">Location:</label>
                        <div class="col-sm-10">
                            <?php
                                if(isset($locs))
                                {
                                    ?>
                                    <select class="form-control" name="locId" required>
                                        <?php
                                        foreach($locs as $key => $row)
                                        {
                                            ?>
                                            <option value="<?php echo $row['id'];?>"><?php echo $row['locName'];?></option>
                                            <?php
                                        }
                                        ?>
                                        <option value="other">Other</option>
                                    </select>
                                        <?php
                                }
                            ?>
                        </div>
                    </div>
                    <div class="form-group hide" id="otherPanel">
                        <label class="control-label col-sm-2" for="otherLoc">Other Location:</label>
                        <div class="col-sm-10">
                            <input type="text" name="otherLoc" class="form-control" id="otherLoc" placeholder="Eg. Office">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="itemQty">Quantity:</label>
                        <div class="col-sm-10">
                            <input type="number" name="itemQty" min="0" class="form-control" id="itemQty" placeholder="0" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="serialNum">Serial Number:</label>
                        <div class="col-sm-10">
                            <input type="text" name="serialNum" class="form-control" id="serialNum" placeholder="Eg. 23XXXX" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="modelNum">Model Number:</label>
                        <div class="col-sm-10">
                            <input type="text" name="modelNum" class="form-control" id="modelNum" placeholder="Eg. TB1-XXXX" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="purchaseDate">Purchase Date:</label>
                        <div class="col-sm-10">
                            <input type="text" name="purchaseDate" class="form-control" id="purchaseDate" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="warrantyTill">Warranty Till Date:</label>
                        <div class="col-sm-10">
                            <input type="text" name="warrantyTill" class="form-control" id="warrantyTill" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="itemPrice">Item Price:</label>
                        <div class="col-sm-10">
                            <input type="number" name="itemPrice" min="1" class="form-control" id="itemPrice" placeholder="Eg. 1000">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="vendorName">Vendor Name:</label>
                        <div class="col-sm-10">
                            <input type="text" name="vendorName" class="form-control" id="vendorName" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="pocDoolally">POC:</label>
                        <div class="col-sm-10">
                            <input type="text" name="pocDoolally" class="form-control" id="pocDoolally" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="assignedTo">Assigned To:</label>
                        <div class="col-sm-10">
                            <input type="text" name="assignedTo" class="form-control" id="assignedTo" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="currentStatus">Status:</label>
                        <div class="col-sm-10">
                            <select name="currentStatus" class="form-control" id="currentStatus" required>
                                <?php
                                    foreach($this->config->item('assetsStatus') as $key => $row)
                                    {
                                        ?>
                                        <option value="<?php echo $key;?>"><?php echo $row;?></option>
                                        <?php
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="comments">Comments:</label>
                        <div class="col-sm-10">
                            <textarea name="comments" class="form-control" id="comments"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="reset" class="btn btn-success">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
<?php echo $footerView;?>
</body>
<?php echo $globalJs; ?>

<script>
    function formatDate(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;

        return [year, month, day].join('-');
    }
    $('#purchaseDate, #warrantyTill').datetimepicker({
        format: 'YYYY-MM-DD'
    });
    $(document).on('focusout', '#purchaseDate', function(){
        var purDate = $(this).val();
        if(purDate != '')
        {
            var pNew = new Date(purDate);
            pNew.setFullYear(pNew.getFullYear() + 1);
            $('#warrantyTill').val(formatDate(pNew));
        }
    });
    $(document).on('change','select[name="locId"]',function(){
        if($(this).val() == 'other')
        {
            $('#otherPanel').removeClass('hide');
            $('#otherPanel #otherLocation').attr('required','required');
        }
        else
        {
            $('#otherPanel').addClass('hide').val('');
            $('#otherPanel #otherLocation').removeAttr('required');
        }
    });
</script>
</html>