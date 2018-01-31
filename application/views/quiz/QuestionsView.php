<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Questions :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="homePage">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-2 col-xs-0"></div>
                <div class="col-sm-8 col-xs-12">
                    <a href="<?php echo base_url();?>quiz/addQuestion" class="btn btn-success my-marginUp my-marginDown">Add New Question</a>
                    <?php
                        if(isset($questions) && myIsArray($questions))
                        {
                            ?>
                            <table id="questionsTab" class="table table-bordered table-responsive">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Question</th>
                                        <th>Category</th>
                                        <th>Options</th>
                                        <th>Answer(s)</th>
                                        <th>Added Date/Time</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                            <?php
                            foreach($questions as $key => $row)
                            {
                                $correctAnswers = array();
                                ?>
                                <tr>
                                    <td><?php echo $row['qid'];?></td>
                                    <td><?php echo $row['questionText'];?></td>
                                    <td><?php echo $row['categoryName'];?></td>
                                    <td>
                                        <?php
                                            $opts = explode(';',$row['optionText']);
                                            $ans = explode(';',$row['isCorrectOption']);
                                            echo '<ul>';
                                            for($i=0;$i<count($opts);$i++)
                                            {
                                                if($ans[$i] == '1')
                                                {
                                                    $correctAnswers[] = $opts[$i];
                                                }
                                                echo '<li>'.$opts[$i].'</li>';
                                            }
                                            echo '</ul>';
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            echo '<ul>';
                                            foreach($correctAnswers as $cKey)
                                            {
                                                echo '<li>'.$cKey.'</li>';
                                            }
                                            echo '</ul>';
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            $d = date_create($row['insertedDT']);
                                            echo date_format($d,DATE_TIME_FORMAT_UI);
                                        ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo base_url();?>quiz/editQuestion/<?php echo $row['qid']; ?>" class="even-tracker">
                                            <i class="glyphicon glyphicon-edit"></i>
                                        </a>
                                        <a data-qId="<?php echo $row['qid'];?>" href="#" class="question-delete even-tracker">
                                            <i class="glyphicon glyphicon-trash"></i>
                                        </a>
                                    </td>
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
                            echo 'No questions!';
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
    $(document).on('click','.even-tracker', function(){
        localStorageUtil.setLocal('tabQuestPage',evenTable.page());
    });
    if(localStorageUtil.getLocal('tabQuestPage') != null)
    {
        evenTable =  $('#questionsTab').DataTable({
            "displayStart": localStorageUtil.getLocal('tabQuestPage') * 10
        });
        localStorageUtil.delLocal('tabQuestPage');
    }
    else
    {
        evenTable =  $('#questionsTab').DataTable();
    }
    $('#questionsTab').dataTable();

    $(document).on('click','.question-delete', function(){
        var qid = $(this).attr('data-qId');
        bootbox.confirm("Are you sure you want to delete Question #"+qid+" ?", function(result) {
            if(result === true)
            {
                window.location.href='<?php echo base_url();?>quiz/deleteQuest/'+qid;
            }
        });
    });
</script>
</html>