<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Mail</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <main class="mailPrank">
        <div class="container-fluid">
            <h1 class="text-center">Send Mail</h1>
            <hr>
            <form enctype="multipart/form-data" action="<?php echo base_url();?>login/sendSample" method="post" class="form-horizontal" role="form">
                <div class="form-group">
                    <label class="control-label col-sm-2" for="usrName">From Name:</label>
                    <div class="col-sm-10">
                        <input type="text" name="fromName" class="form-control" id="usrName">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="userName">From:</label>
                    <div class="col-sm-10">
                        <input type="email" name="fromEmail" class="form-control" id="userName">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="pwd">To:</label>
                    <div class="col-sm-10">
                        <input type="email" name="toEmail" class="form-control" id="pwd">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="sub">Subject:</label>
                    <div class="col-sm-10">
                        <input type="text" name="subEmail" class="form-control" id="sub">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="bdy">Body:</label>
                    <div class="col-sm-10">
                        <textarea name="bodyEmail" class="form-control" id="bdy"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <!--<button type="button" class="btn btn-danger col-sm-2 my-marginDown" data-toggle="modal" data-target="#bodyModal" >Select Body</button>-->
                    <label class="control-label col-sm-2" for="attchment">To:</label>
                    <div class="col-sm-10">
                        <input type="text" name="attachment" class="form-control" id="attchment" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-default">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </main>
</body>
<?php echo $globalJs; ?>
</html>