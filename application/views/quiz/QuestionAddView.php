<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Question Add :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="homePage">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-2 col-xs-0"></div>
                <div class="col-sm-8 col-xs-12">
                    <form action="<?php echo base_url();?>quiz/addQust" method="post" class="form" id="questAddForm">
                        <div class="form-group">
                            <label for="questionText">Question: </label>
                            <textarea class="form-control" rows="5" cols="10" name="questionText"
                                      id="questionText" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="questionCat">Category:</label>
                            <select id="questionCat" name="questionCat" class="form-control" required>
                                <?php
                                foreach($qCats as $key => $row)
                                {
                                    ?>
                                    <option value="<?php echo $row['catid'];?>"><?php echo $row['categoryName'];?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <label>Question Level: </label>
                        <label class="radio-inline"><input type="radio" name="questionLvl" value="1">Easy</label>
                        <label class="radio-inline"><input type="radio" name="questionLvl" value="2">Medium</label>
                        <label class="radio-inline"><input type="radio" name="questionLvl" value="3">Hard</label>
                        <br>
                        <label>Options: <button type="button" id="add-opts" class="btn btn-primary">Add Options</button></label>
                        <div class="options-panel">

                        </div>

                        <button type="submit" class="btn btn-default">Submit</button>
                    </form>
                </div>
                <div class="col-sm-2 col-xs-0"></div>
            </div>
        </div>
    </main>
    <?php echo $footerView;?>
</body>
<?php echo $globalJs; ?>
<script>
    var optNumber = 0;
    $(document).on('click','#add-opts', function(){
        var optHtml = '<div><ul class="list-inline">';
        optHtml += '<li><label>Option Text: </label><br><input type="text" name="optionTxt['+optNumber+']" class="form-control"/></li>';
        optHtml += '<li><label class="checkbox-inline"><input type="checkbox" name="optionCorrect['+optNumber+']" value="'+optNumber+'"/>Correct Option?</label></li></ul></div>';
        $('.options-panel').append(optHtml);
        optNumber++;
    });
    $(window).load(function(){
       $('#add-opts').click();
    });
</script>
</html>