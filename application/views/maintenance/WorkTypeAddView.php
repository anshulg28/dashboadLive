<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Work Type Add :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="workType">
        <div class="container">
            <div class="row">
                <h2> Add New Work Type</h2>
                <hr>
                <br>
                <form action="<?php echo base_url();?>maintenance/saveWorkType" id="workArea-form" method="post" class="form-horizontal" role="form">
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="typeName">Type Name :</label>
                        <div class="col-sm-10">
                            <input type="text" name="typeName" class="form-control" id="typeName" placeholder="Outside Taproom" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-10">
                            <label class="control-label" for="subAdd">
                                <input type="checkbox" name="subAdd" id="subAdd" value="1" />
                                Add Sub Types?
                            </label>
                            <button type="button" id="addMoreSub" class="btn btn-default hide">Add More?</button>
                        </div>
                    </div>
                    <div class="subTypeAdd-panel">

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
    var subCount = 1;
    $(document).on('change','#subAdd', function(){
        if($(this).is(':checked'))
        {
            var subField = '<div class="form-group"><label class="control-label col-sm-2" for="subTypeName'+subCount+'">Sub Type Name :</label>\n' +
                            '<div class="col-sm-10">\n' +
                            '<input type="text" name="subTypeName[]" class="form-control" id="subTypeName'+subCount+'">\n' +
                            '</div></div>';
            $('#addMoreSub').removeClass('hide');
            $('.subTypeAdd-panel').append(subField);
            subCount++;
        }
        else
        {
            subCount = 1;
            $('#addMoreSub').addClass('hide');
            $('.subTypeAdd-panel').html('');
        }
    });
    $(document).on('click','#addMoreSub',function(){
        var subField = '<div class="form-group"><label class="control-label col-sm-2" for="subTypeName'+subCount+'">Sub Type Name :</label>\n' +
            '<div class="col-sm-10">\n' +
            '<input type="text" name="subTypeName[]" class="form-control" id="subTypeName'+subCount+'">\n' +
            '</div></div>';
        $('.subTypeAdd-panel').append(subField);
        subCount++;
    });
</script>
</html>