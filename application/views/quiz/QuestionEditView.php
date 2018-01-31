<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Question Edit :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="homePage">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-2 col-xs-0"></div>
                <div class="col-sm-8 col-xs-12">
                    <h2>Edit Question #<?php echo $qId;?></h2>
                    <br>
                    <?php
                        if(isset($questData) && myIsArray($questData))
                        {
                            $opts = explode(';',$questData['optionText']);
                            $optIds = explode(';',$questData['optionIds']);
                            $ans = explode(';',$questData['isCorrectOption']);
                            $ansCount = 0;
                            foreach($ans as $aKey)
                            {
                                if($aKey == '1')
                                {
                                    $ansCount++;
                                }
                            }

                            ?>
                            <form action="<?php echo base_url();?>quiz/updateQust/<?php echo $qId;?>" method="post" class="form" id="questEditForm">
                                <div class="form-group">
                                    <label for="questionText">Question: </label>
                                    <textarea class="form-control" rows="5" cols="10" name="questionText"
                                              id="questionText"><?php echo $questData['questionText'];?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="questionCat">Category:</label>
                                    <select id="questionCat" name="questionCat" class="form-control">
                                        <?php
                                            foreach($qCats as $key => $row)
                                            {
                                                ?>
                                                <option value="<?php echo $row['catid'];?>"
                                                <?php if($row['catid'] == $questData['questionCat']){echo 'selected';} ;?>><?php echo $row['categoryName'];?></option>
                                                <?php
                                            }
                                        ?>
                                    </select>
                                </div>
                                <label>Question Level: </label>
                                <label class="radio-inline"><input type="radio" name="questionLvl" value="1"
                                            <?php if($questData['questionLvl'] == '1'){echo 'checked';}?>>Easy</label>
                                <label class="radio-inline"><input type="radio" name="questionLvl" value="2"
                                        <?php if($questData['questionLvl'] == '2'){echo 'checked';}?>>Medium</label>
                                <label class="radio-inline"><input type="radio" name="questionLvl" value="3"
                                            <?php if($questData['questionLvl'] == '3'){echo 'checked';}?>>Hard</label>
                                <br>
                                <label>Options: </label>
                                <?php
                                    if($ansCount>1)
                                    {
                                        for($i=0;$i<count($opts);$i++)
                                        {
                                            if($ans[$i] == '1')
                                            {
                                                ?>
                                                <div class="checkbox">
                                                    <label><input type="checkbox" name="optIds[]" value="<?php echo $optIds[$i]; ?>"
                                                                  checked><?php echo $opts[$i]; ?></label>
                                                </div>
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                <div class="checkbox">
                                                    <label><input type="checkbox" name="optIds[]" value="<?php echo $optIds[$i]; ?>"
                                                        ><?php echo $opts[$i]; ?></label>
                                                </div>
                                                <?php
                                            }
                                        }
                                    }
                                    else
                                    {
                                        for($i=0;$i<count($opts);$i++)
                                        {
                                            if($ans[$i] == '1')
                                            {
                                                ?>
                                                <div class="radio">
                                                    <label><input type="radio" name="optIds" value="<?php echo $optIds[$i]; ?>"
                                                                  checked><?php echo $opts[$i]; ?></label>
                                                </div>
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                <div class="radio">
                                                    <label><input type="radio" name="optIds" value="<?php echo $optIds[$i]; ?>"
                                                        ><?php echo $opts[$i]; ?></label>
                                                </div>
                                                <?php
                                            }
                                        }
                                    }
                                ?>
                                <button type="submit" class="btn btn-default">Submit</button>
                            </form>
                            <?php
                        }
                        else
                        {
                            echo 'No question to edit!';
                        }
                    ?>
                </div>
                <div class="col-sm-2 col-xs-0"></div>
            </div>
        </div>
    </main>
    <?php echo $footerView;?>
</body>
<?php echo $globalJs; ?>
<script>
    $('#questionsTab').dataTable();
</script>
</html>