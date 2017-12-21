<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Careers :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="career">
        <div class="container">
            <div class="row">
                <div class="col-sm-9 col-xs-8">
                    <a class="btn btn-primary" href="<?php echo base_url().'career/addNewJob';?>">
                        <i class="fa fa-plus"></i>
                        Add New Job</a>
                </div>
            </div>
        </div>
        <br>
        <div class="container table-responsive">
            <table id="main-career-table" class="table table-hover table-bordered table-striped">
                <thead>
                <tr>
                    <th>Job Title</th>
                    <th>Job Description</th>
                    <th>Department</th>
                    <th>Contact Email</th>
                    <th>Any Skills</th>
                    <th>Location</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <?php
                if(isset($careers) && myIsArray($careers))
                {
                    ?>
                    <tbody>
                    <?php
                    foreach($careers as $key => $row)
                    {
                        ?>
                        <tr>
                            <td><?php echo $row['jobTitle'];?></td>
                            <td><?php echo $row['jobDescription'];?></td>
                            <td><?php echo $row['jobDepartment'];?></td>
                            <td><?php echo $row['contactEmail'];?></td>
                            <td><?php if(isset($row['jobSkills'])){echo $row['jobSkills'];}else{'No';};?></td>
                            <td>
                                <?php
                                if(isset($row['locId']))
                                {
                                    echo $row['locName'];
                                }
                                else
                                {
                                    echo $row['otherLocation'];
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if($row['ifActive'] == ACTIVE)
                                {
                                    ?>
                                    <a data-toggle="tooltip" title="Active" class="even-tracker" href="<?php echo base_url().'career/setCareerDeActive/'.$row['id'];?>">
                                        <i class="fa fa-15x fa-lightbulb-o my-success-text"></i></a>&nbsp;
                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <a data-toggle="tooltip" title="Not Active" class="even-tracker" href="<?php echo base_url().'career/setCareerActive/'.$row['id'];?>">
                                        <i class="fa fa-15x fa-lightbulb-o my-error-text"></i></a>&nbsp;
                                    <?php
                                }
                                ?>
                                <a data-toggle="tooltip" title="Edit" href="<?php echo base_url().'career/editJob/'.$row['id'];?>">
                                    <i class="glyphicon glyphicon-edit"></i></a>
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
                        <td colspan="7">No Data Found!</td>
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

    if(localStorageUtil.getLocal('tabPage') != null)
    {
        var mugClubTab =  $('#main-career-table').DataTable({
            "displayStart": localStorageUtil.getLocal('tabPage') * 10
        });
        localStorageUtil.delLocal('tabPage');
    }
    else
    {
        var mugClubTab =  $('#main-career-table').DataTable();
    }
</script>
</html>