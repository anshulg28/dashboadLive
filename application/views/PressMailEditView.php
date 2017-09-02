<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Press Email Edit :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="mailPage">
        <div class="container-fluid">
            <h1 class="text-center">Email Edit</h1>
            <hr>
            <div class="row">
                <div class="col-sm-2"></div>
                <div class="col-sm-8">
                    <a href="<?php echo base_url().'mailers/pressSend';?>" class="btn btn-warning"><i class="fa fa-arrow-circle-o-left"></i> Go Back</a>
                </div>
                <div class="col-sm-4"></div>
            </div>
            <br>
            <form action="<?php echo base_url();?>mailers/updatePressMail" method="post" class="form-horizontal" role="form">
                <?php
                    if(isset($mailInfo) && myIsArray($mailInfo))
                    {
                        ?>
                        <input type="hidden" name="id" value="<?php echo $mailInfo['id'];?>"/>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="pressName">Name:</label>
                            <div class="col-sm-10">
                                <input type="text" name="pressName" class="form-control"
                                       id="pressName" placeholder="Enter Name" value="<?php echo $mailInfo['pressName'];?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="pressEmail">Email:</label>
                            <div class="col-sm-10">
                                <input type="email" name="pressEmail" class="form-control"
                                       id="pressEmail" placeholder="Enter Email" value="<?php echo $mailInfo['pressEmail'];?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="publication">Publication (if any):</label>
                            <div class="col-sm-10">
                                <input type="text" name="publication" class="form-control"
                                       id="publication" value="<?php echo $mailInfo['publication'];?>" placeholder="Enter Publication">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="pressMailType">Email Category:</label>
                            <div class="col-sm-8">
                                <select id="pressMailType" name="pressMailType" class="form-control" required>
                                    <?php
                                    if(isset($pressTypes) && myIsArray($pressTypes))
                                    {
                                        foreach($pressTypes as $key => $row)
                                        {
                                            ?>
                                            <option value="<?php echo $row['id'];?>"
                                                <?php if($mailInfo['pressMailType'] == $row['id']){echo 'selected';}?>>
                                                <?php echo $row['catName'];?>
                                            </option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <a href="#" class="my-black-text press-type-refresh"
                                   data-toggle="tooltip" title="Refresh List">
                                    <i class="fa fa-refresh"></i>
                                </a>&nbsp;
                                <a href="#" class="my-black-text press-type-add"
                                   data-toggle="tooltip" title="Add Category">
                                    <i class="fa fa-plus-circle"></i>
                                </a>
                            </div>
                        </div>
                        <?php
                    }
                ?>

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-default">Submit</button>
                    </div>
                </div>
            </form>

        </div>

        <div id="typeAddModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Add Press Category</h4>
                    </div>
                    <div class="modal-body">
                        <form id="category-save-form" action="<?php echo base_url();?>mailers/savePressCategory" method="post" class="form-horizontal" role="form">
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="catName">Category Name:</label>
                                <div class="col-sm-10">
                                    <input type="text" name="catName" class="form-control" id="catName" placeholder="Enter Name">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-default">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
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

    $(document).on('click','.press-type-add', function(){
        $('#typeAddModal').modal('show');
    });
    $(document).on('submit','#typeAddModal #category-save-form', function(e){
        e.preventDefault();

        if($(this).find('#catName').val() == '')
        {
            bootbox.alert('Category Name Required!');
            return false;
        }

        showCustomLoader();
        var errUrl = $(this).attr('action');
        $.ajax({
            type:'POST',
            dataType:'json',
            url:$(this).attr('action'),
            data:$(this).serialize(),
            success: function(data){
                hideCustomLoader();
                if(data.status == true)
                {
                    $('#typeAddModal').modal('hide');
                    $('.press-type-refresh').click();
                }
                else
                {
                    bootbox.alert(data.errorMsg);
                }
            },
            error: function(xhr, status, error){
                hideCustomLoader();
                bootbox.alert('Some Error Occurred!');
                var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                saveErrorLog(err);
            }
        });
    });

    $(document).on('click','.press-type-refresh', function(){
        showCustomLoader();
        var errUrl = base_url+'mailers/refreshMailTypes';
        $.ajax({
            type:'GET',
            dataType:'json',
            url:base_url+'mailers/refreshMailTypes',
            success: function(data){
                hideCustomLoader();
                if(data.status == true)
                {
                    if(typeof data.mailTypes != 'undefined')
                    {
                        var optionsHtml = '';
                        for(var i=0;i<data.mailTypes.length;i++)
                        {
                            optionsHtml += '<option value="'+data.mailTypes[i].id+'">'+data.mailTypes[i].catName+'</option>';
                        }
                        $('#pressMailType').html(optionsHtml);
                    }
                }
                else
                {
                    bootbox.alert(data.errorMsg);
                }
            },
            error: function(xhr, status, error){
                hideCustomLoader();
                bootbox.alert('Error in refreshing list!');
                var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                saveErrorLog(err);
            }
        });
    });
</script>
</html>