<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Log View :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-1 col-xs-0"></div>
            <div class="col-sm-10 col-xs-12 text-right">
                Maintenance Balance (Rs): <?php echo $mainBalance;?>
                <input type="hidden" id="mainBalance" value="<?php echo $mainBalance;?>"/>
            </div>
            <div class="col-sm-1 col-xs-0"></div>
        </div>
    </div>
    <main class="logComplaint">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-1 col-xs-0"></div>
                <div class="col-sm-10 col-xs-12">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#open">Open Issues</a></li>
                        <li><a data-toggle="tab" href="#inProgress">In Progress Issues</a></li>
                        <li><a data-toggle="tab" href="#closed">Closed Issues</a></li>
                        <li><a data-toggle="tab" href="#postpone">Postponed Issues</a></li>
                        <li><a data-toggle="tab" href="#payfilter">Payment Filters</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="open" class="tab-pane fade in active">
                            <table id="openTab" class="table table-hover table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Problem At</th>
                                    <th>Work Type</th>
                                    <th>Problem</th>
                                    <th>Location</th>
                                    <th>Log By</th>
                                    <?php
                                    if($this->userType == FINANCE_APPROVER || $this->userType == MAINTENANCE_APPROVER2)
                                    {
                                        ?>
                                        <th>Approx Cost</th>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                        <th>Logged Date/Time</th>
                                        <?php
                                    }
                                    ?>
                                    <th>Logged Since</th>
                                    <th>Media</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                    if(!isset($openComplaints) && !myIsArray($openComplaints))
                                    {
                                        ?>
                                        <tr class="my-danger-text text-center">
                                            <td colspan="8">No Records Found!</td>
                                        </tr>
                                        <?php
                                    }
                                    else
                                    {
                                        foreach($openComplaints as $key => $row)
                                        {
                                            ?>
                                            <tr>
                                                <td scope="row">
                                                    <?php
                                                        if($this->userType == MAINTENANCE_MANAGER)
                                                        {
                                                            ?>
                                                            <a href="#" data-compId="<?php echo $row['complaintId'];?>" data-priority="<?php echo $row['jobPriority'];?>" class="change-job-priority my-noUnderline">
                                                            <?php
                                                            switch($row['jobPriority'])
                                                            {
                                                                case JOB_PRIORITY_HIGH:
                                                                    ?>
                                                                    <div class="job-with-high"><?php echo $row['complaintId'];?></div>
                                                                    <?php
                                                                    break;
                                                                case JOB_PRIORITY_MEDIUM:
                                                                    ?>
                                                                    <div class="job-with-medium"><?php echo $row['complaintId'];?></div>
                                                                    <?php
                                                                    break;
                                                                case JOB_PRIORITY_LOW:
                                                                    ?>
                                                                    <div class="job-with-low"><?php echo $row['complaintId'];?></div>
                                                                    <?php
                                                                    break;
                                                            }
                                                            ?>
                                                            </a>
                                                            <?php
                                                        }
                                                        else
                                                        {
                                                            switch($row['jobPriority'])
                                                            {
                                                                case JOB_PRIORITY_HIGH:
                                                                    ?>
                                                                    <div class="job-with-high"><?php echo $row['complaintId'];?></div>
                                                                    <?php
                                                                    break;
                                                                case JOB_PRIORITY_MEDIUM:
                                                                    ?>
                                                                    <div class="job-with-medium"><?php echo $row['complaintId'];?></div>
                                                                    <?php
                                                                    break;
                                                                case JOB_PRIORITY_LOW:
                                                                    ?>
                                                                    <div class="job-with-low"><?php echo $row['complaintId'];?></div>
                                                                    <?php
                                                                    break;
                                                            }
                                                        }
                                                    ?>
                                                </td>
                                                <td><?php echo $row['areaName'];?></td>
                                                <td><?php
                                                    echo $row['typeName'];
                                                    if(isset($row['subTypeName']) && !empty($row['subTypeName']))
                                                    {
                                                        echo ' ('.$row['subTypeName'].')';
                                                    }
                                                    ?></td>
                                                <td><?php echo $row['problemDescription'];?></td>
                                                <td><?php echo $row['locName'];?></td>
                                                <td><?php echo $row['loggedUser'];?></td>
                                                <td>
                                                    <?php
                                                    if($this->userType == FINANCE_APPROVER || $this->userType == MAINTENANCE_APPROVER2)
                                                    {
                                                        if(isset($row['approxCost']))
                                                        {
                                                            echo 'Rs. '.$row['approxCost'];
                                                        }
                                                        else
                                                        {
                                                            $d = date_create($row['loggedDT']); echo date_format($d,DATE_TIME_FORMAT_UI);
                                                        }
                                                    }
                                                    else
                                                    {
                                                        $d = date_create($row['loggedDT']); echo date_format($d,DATE_TIME_FORMAT_UI);
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <time class="timeago time-stamp" datetime="<?php echo $row['loggedDT'];?>"></time>
                                                </td>
                                                <td>
                                                    <?php
                                                    if(isset($row['problemMedia']))
                                                    {
                                                        if($row['pMediaType'] == '1')
                                                        {
                                                            ?>
                                                            <a href="#" class="show-img-media" data-media="<?php echo base_url().JOB_MEDIA_PATH.$row['problemMedia'];?>">View Media</a>
                                                            <?php
                                                        }
                                                        else
                                                        {
                                                            ?>
                                                            <a href="#" class="show-vid-media" data-media="<?php echo base_url().JOB_MEDIA_PATH.$row['problemMedia'];?>">View Media</a>
                                                            <?php
                                                        }
                                                    }
                                                    else
                                                    {
                                                        echo 'No Media!';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <a href="#" class="get-update-info" data-jobId="<?php echo $row['complaintId'];?>">View Details |</a>
                                                    <?php

                                                        if($row['status'] == LOG_STATUS_DECLINED)
                                                        {
                                                            echo 'Job Declined ';
                                                            ?>
                                                            (<a href="#" class="view-reason" data-reason="<?php echo htmlspecialchars($row['declineReason']);?>">View Reason</a>)
                                                            <?php
                                                        }
                                                        else
                                                        {
                                                            if($this->userType == MAINTENANCE_APPROVER1)
                                                            {
                                                                if($row['status'] == LOG_STATUS_PENDING_APPROVAL)
                                                                {
                                                                    if((double)$row['approxCost'] > 15000 && (double)$row['approxCost'] < 50000)
                                                                    {
                                                                        ?>
                                                                        <a class="track-me" href="<?php echo base_url().'maintenance/approveJob/'.$row['complaintId'];?>">Approve</a>&nbsp;|&nbsp;
                                                                        <a href="#" data-complaintId="<?php echo $row['complaintId'];?>" class="decline-btn track-me">Decline</a>&nbsp;|&nbsp;
                                                                        <a href="#" data-complaintId="<?php echo $row['complaintId'];?>" class="postpone-btn track-me">Postpone</a>
                                                                        <?php
                                                                    }
                                                                    elseif((double)$row['approxCost'] < 15000)
                                                                    {
                                                                        echo 'Pending Budget Approval';
                                                                    }
                                                                    else
                                                                    {
                                                                        echo 'Pending Job Approval Lvl 2';
                                                                    }
                                                                }
                                                                elseif($row['status'] == LOG_STATUS_PENDING_BUDGET_APPROVAL)
                                                                {
                                                                    echo 'Pending Budget Approval';
                                                                }
                                                            }
                                                            elseif($this->userType == MAINTENANCE_APPROVER2)
                                                            {
                                                                if($row['status'] == LOG_STATUS_PENDING_APPROVAL)
                                                                {
                                                                    if((double)$row['approxCost'] >= 50000)
                                                                    {
                                                                        ?>
                                                                        <a class="track-me" href="<?php echo base_url().'maintenance/approveJob/'.$row['complaintId'];?>">Approve</a>&nbsp;|&nbsp;
                                                                        <a href="#" data-complaintId="<?php echo $row['complaintId'];?>" class="decline-btn track-me">Decline</a>&nbsp;|&nbsp;
                                                                        <a href="#" data-complaintId="<?php echo $row['complaintId'];?>" class="postpone-btn track-me">Postpone</a>
                                                                        <?php
                                                                    }
                                                                    elseif((double)$row['approxCost'] < 15000)
                                                                    {
                                                                        echo 'Pending Budget Approval';
                                                                    }
                                                                    else
                                                                    {
                                                                        echo 'Pending Job Approval Lvl 1';
                                                                    }
                                                                }
                                                                elseif($row['status'] == LOG_STATUS_PENDING_BUDGET_APPROVAL)
                                                                {
                                                                    //echo 'Pending Budget Approval';
                                                                    ?>
                                                                    <a href="#" data-complaintId="<?php echo $row['complaintId'];?>" data-compAmt="<?php echo $row['approxCost'];?>"
                                                                       class="update-budget track-me">Allocate Amount</a>
                                                                    <?php
                                                                }
                                                            }
                                                            elseif($this->userType == FINANCE_APPROVER || $this->userType == MAINTENANCE_APPROVER2)
                                                            {
                                                                if($row['status'] == LOG_STATUS_PENDING_BUDGET_APPROVAL)
                                                                {
                                                                    ?>
                                                                    <a href="#" data-complaintId="<?php echo $row['complaintId'];?>" data-compAmt="<?php echo $row['approxCost'];?>"
                                                                       class="update-budget track-me">Allocate Amount</a>
                                                                    <?php
                                                                }
                                                                else
                                                                {
                                                                    if((double)$row['approxCost'] > 15000 && (double)$row['approxCost'] < 50000)
                                                                    {
                                                                        echo 'Pending Approval Lvl 1';
                                                                    }
                                                                    elseif((double)$row['approxCost'] >= 50000)
                                                                    {
                                                                        echo 'Pending Approval Lvl 2';
                                                                    }

                                                                }
                                                            }
                                                            else
                                                            {
                                                                if($row['status'] == LOG_STATUS_OPEN)
                                                                {
                                                                    ?>
                                                                    <a href="#" data-complaintId="<?php echo $row['complaintId'];?>" class="update-complaint track-me">Update</a>
                                                                    <?php
                                                                }
                                                                elseif($row['status'] == LOG_STATUS_PENDING_APPROVAL)
                                                                {
                                                                    if((double)$row['approxCost'] > 15000 && (double)$row['approxCost'] < 50000)
                                                                    {
                                                                        echo 'Pending Approval Lvl 1';
                                                                    }
                                                                    elseif((double)$row['approxCost'] >= 50000)
                                                                    {
                                                                        echo 'Pending Approval Lvl 2';
                                                                    }
                                                                    else
                                                                    {
                                                                        echo 'Pending Approval';
                                                                    }
                                                                }
                                                                elseif($row['status'] == LOG_STATUS_PENDING_BUDGET_APPROVAL)
                                                                {
                                                                    echo 'Budget Approval Pending';
                                                                }
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
                            </table>
                        </div>
                        <div id="inProgress" class="tab-pane fade">
                            <table id="progressTab" class="table table-hover table-bordered table-striped">
                                <thead>
                                <tr>
                                    <?php
                                        if($this->userType == MAINTENANCE_MANAGER)
                                        {
                                            ?>
                                            <th></th>
                                            <?php
                                        }
                                    ?>
                                    <th>ID</th>
                                    <th>Problem At</th>
                                    <th>Work Type</th>
                                    <th>Problem</th>
                                    <th>Location</th>
                                    <th>Update</th>
                                    <th>Cost Involved</th>
                                    <th>Assigned To</th>
                                    <th>Update Date/Time</th>
                                    <th>Updated Since</th>
                                    <th>Budget Provided</th>
                                    <th>Media</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if(!isset($progressComplaints) && !myIsArray($progressComplaints))
                                {
                                    ?>
                                    <tr class="my-danger-text text-center">
                                        <td colspan="10">No Records Found!</td>
                                    </tr>
                                    <?php
                                }
                                else
                                {
                                    foreach($progressComplaints as $key => $row)
                                    {
                                        ?>
                                        <tr>
                                            <?php
                                            if($this->userType == MAINTENANCE_MANAGER)
                                            {
                                                if(isset($row['budgetInfo']) && myIsArray($row['budgetInfo']))
                                                {
                                                    ?>
                                                    <td>
                                                    <?php
                                                    $ifAllClear = true;
                                                    foreach($row['budgetInfo'] as $budKey => $budRow)
                                                    {
                                                        if(!isset($budRow['receiveDate']))
                                                        {
                                                            $ifAllClear = false;
                                                            ?>
                                                            <a href="#" class="budget-confirm-link track-me" data-jobId="<?php echo $budRow['fid'];?>">Confirm Rs <?php echo $budRow['payAmount'];?> Received</a><br>
                                                            <?php
                                                        }
                                                    }
                                                    if($ifAllClear)
                                                    {
                                                        if(isset($row['jobInfo']['remarkIfAny']) && isset($row['jobInfo']['actualCost']))
                                                        {
                                                            ?>
                                                            <input type="checkbox" name="jobs" value="<?php echo $row['jobInfo']['complaintId'];?>"/>
                                                            <?php
                                                        }
                                                        else
                                                        {
                                                            ?>
                                                            <a href="#" data-compAmt="<?php echo $row['jobInfo']['approxCost'];?>" data-compId="<?php echo $row['jobInfo']['complaintId']; ?>" class="final-job-update track-me">Final Job Update</a>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                    </td>
                                                    <?php
                                                }
                                                elseif($row['jobInfo']['approxCost'] == 0)
                                                {
                                                    if(isset($row['jobInfo']['remarkIfAny']) && isset($row['jobInfo']['actualCost']))
                                                    {
                                                        ?>
                                                        <td>
                                                            <input type="checkbox" name="jobs" value="<?php echo $row['jobInfo']['complaintId'];?>"/>
                                                        </td>
                                                        <?php
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                        <td>
                                                            <a href="#" data-compAmt="<?php echo $row['jobInfo']['approxCost'];?>" data-compId="<?php echo $row['jobInfo']['complaintId']; ?>" class="final-job-update track-me">Final Job Update</a>
                                                        </td>
                                                        <?php
                                                    }
                                                }
                                            }
                                            ?>
                                            <td scope="row">
                                                <?php
                                                switch($row['jobInfo']['jobPriority'])
                                                {
                                                    case JOB_PRIORITY_HIGH:
                                                        ?>
                                                        <div class="job-with-high"><?php echo $row['jobInfo']['complaintId'];?></div>
                                                        <?php
                                                        break;
                                                    case JOB_PRIORITY_MEDIUM:
                                                        ?>
                                                        <div class="job-with-medium"><?php echo $row['jobInfo']['complaintId'];?></div>
                                                        <?php
                                                        break;
                                                    case JOB_PRIORITY_LOW:
                                                        ?>
                                                        <div class="job-with-low"><?php echo $row['jobInfo']['complaintId'];?></div>
                                                        <?php
                                                        break;
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $row['jobInfo']['areaName'];?></td>
                                            <td><?php
                                                echo $row['jobInfo']['typeName'];
                                                if(isset($row['jobInfo']['subTypeName']) && !empty($row['jobInfo']['subTypeName']))
                                                {
                                                    echo ' ('.$row['jobInfo']['subTypeName'].')';
                                                }
                                                ?></td>
                                            <td><?php echo $row['jobInfo']['problemDescription'];?></td>
                                            <td><?php echo $row['jobInfo']['locName'];?></td>
                                            <td><?php echo $row['jobInfo']['updateOnComplaint'];?></td>
                                            <td><?php echo $row['jobInfo']['approxCost'];?></td>
                                            <td><?php
                                                    if(isset($row['jobInfo']['assignee']) && $row['jobInfo']['assignee'] != '')
                                                    {
                                                        echo $row['jobInfo']['assignee'];
                                                    }
                                                    else
                                                    {
                                                        echo $row['jobInfo']['workAssignedTo'];
                                                    }
                                                ?></td>
                                            <td><?php $d = date_create($row['jobInfo']['lastUpdateDT']); echo date_format($d,DATE_TIME_FORMAT_UI);?></td>
                                            <td>
                                                <time class="timeago time-stamp" datetime="<?php echo $row['jobInfo']['lastUpdateDT'];?>"></time>
                                            </td>
                                            <td>
                                                <?php
                                                    if(isset($row['budgetInfo']) && myIsArray($row['budgetInfo']))
                                                    {
                                                        $budInfo = array();
                                                        foreach($row['budgetInfo'] as $budKey => $budRow)
                                                        {
                                                            $d = date_create($budRow['payDate']);
                                                            $budInfo[]= 'Rs. '.$budRow['payAmount'].';'.date_format($d,DATE_TIME_FORMAT_UI).';'.$budRow['payType'];
                                                        }
                                                        ?>
                                                        <div>
                                                            <input type="hidden" class="budget-info-collection" value="<?php echo implode('|',$budInfo);?>"/>
                                                            <a href="#" class="fetch-budget-info">Payment Info</a>
                                                        </div>
                                                        <?php
                                                            if(isset($row['jobInfo']['vendorId']) && ($this->userType == FINANCE_APPROVER || $this->userType == MAINTENANCE_APPROVER2))
                                                            {
                                                                ?>
                                                                <a href="#" data-vendorId="<?php echo $row['jobInfo']['vendorId'];?>" class="vendor-info-fetch">Vendor Info</a>
                                                                <?php
                                                            }
                                                        ?>
                                                        <?php
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                if(isset($row['jobInfo']['problemMedia']))
                                                {
                                                    if($row['jobInfo']['pMediaType'] == '1')
                                                    {
                                                        ?>
                                                        <a href="#" class="show-img-media" data-media="<?php echo base_url().JOB_MEDIA_PATH.$row['jobInfo']['problemMedia'];?>">View Media</a>
                                                        <?php
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                        <a href="#" class="show-vid-media" data-media="<?php echo base_url().JOB_MEDIA_PATH.$row['jobInfo']['problemMedia'];?>">View Media</a>
                                                        <?php
                                                    }
                                                }
                                                else
                                                {
                                                    echo 'No Media!';
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                                </tbody>
                            </table>
                            <br>
                            <?php
                                if($this->userType == MAINTENANCE_MANAGER)
                                {
                                    ?>
                                        <button type="button" class="btn btn-warning" id="close-multiple-jobs">Close Job(s)</button>
                                    <?php
                                }
                            ?>
                        </div>
                        <div id="closed" class="tab-pane fade">
                            <table id="closeTab" class="table table-hover table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Problem At</th>
                                    <th>Work Type</th>
                                    <th>Problem</th>
                                    <th>Location</th>
                                    <th>Update Date/Time</th>
                                    <th>Cost Involved</th>
                                    <th>Media</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if(!isset($closeComplaints) && !myIsArray($closeComplaints))
                                {
                                    ?>
                                    <tr class="my-danger-text text-center">
                                        <td colspan="8">No Records Found!</td>
                                    </tr>
                                    <?php
                                }
                                else
                                {
                                    foreach($closeComplaints as $key => $row)
                                    {
                                        ?>
                                        <tr>
                                            <td scope="row">
                                                <?php
                                                switch($row['jobPriority'])
                                                {
                                                    case JOB_PRIORITY_HIGH:
                                                        ?>
                                                        <div class="job-with-high"><?php echo $row['complaintId'];?></div>
                                                        <?php
                                                        break;
                                                    case JOB_PRIORITY_MEDIUM:
                                                        ?>
                                                        <div class="job-with-medium"><?php echo $row['complaintId'];?></div>
                                                        <?php
                                                        break;
                                                    case JOB_PRIORITY_LOW:
                                                        ?>
                                                        <div class="job-with-low"><?php echo $row['complaintId'];?></div>
                                                        <?php
                                                        break;
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $row['areaName'];?></td>
                                            <td><?php
                                                echo $row['typeName'];
                                                if(isset($row['subTypeName']) && !empty($row['subTypeName']))
                                                {
                                                    echo ' ('.$row['subTypeName'].')';
                                                }
                                                ?></td>
                                            <td><?php echo $row['problemDescription'];?></td>
                                            <td><?php echo $row['locName'];?></td>
                                            <td><?php $d = date_create($row['lastUpdateDT']); echo date_format($d,DATE_TIME_FORMAT_UI);?></td>
                                            <td>
                                                <?php
                                                    echo 'Rs. '.$row['actualCost'];
                                                    if(isset($row['optionalTax']) && $row['optionalTax'] != 0)
                                                    {
                                                        echo '<br>Tax: Rs. '.$row['optionalTax'];
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                if(isset($row['solutionMedia']))
                                                {
                                                    if($row['sMediaType'] == '1')
                                                    {
                                                        ?>
                                                        <a href="#" class="show-img-media" data-media="<?php echo base_url().JOB_MEDIA_PATH.$row['solutionMedia'];?>">View Media</a>
                                                        <?php
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                        <a href="#" class="show-vid-media" data-media="<?php echo base_url().JOB_MEDIA_PATH.$row['solutionMedia'];?>">View Media</a>
                                                        <?php
                                                    }
                                                }
                                                else
                                                {
                                                    echo 'No Media';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                    if($row['status'] == LOG_STATUS_PARTIAL_CLOSE)
                                                    {
                                                        echo 'Waiting for closure by taproom';
                                                        if(isset($row['invoicePics']) && $row['invoicePics'] != '')
                                                        {
                                                            $inPics = explode(',',$row['invoicePics']);
                                                            $imgs = array();
                                                            foreach($inPics as $pic)
                                                            {
                                                                $imgs[] = base_url().JOB_MEDIA_PATH.$pic;
                                                            }
                                                            ?>
                                                            <a class="view-photos" href="#" data-imgs="<?php echo implode(',',$imgs);?>">
                                                                View Invoice(s)</a>
                                                            <?php
                                                        }
                                                        else
                                                        {
                                                            if($this->userType == MAINTENANCE_MANAGER)
                                                            {
                                                                ?>
                                                                <a class="upload-invoice-pics" data-compId="<?php echo $row['complaintId'];?>" href="#">Pending Invoice Images</a>
                                                                <?php
                                                            }
                                                            else
                                                            {
                                                                echo 'Pending Invoice Images';
                                                            }
                                                        }
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                        <a href="#" class="get-update-info" data-jobId="<?php echo $row['complaintId'];?>">View Details</a>
                                                        <?php
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <div id="postpone" class="tab-pane fade">
                            <table id="postponeTab" class="table table-hover table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Problem At</th>
                                    <th>Work Type</th>
                                    <th>Problem</th>
                                    <th>Location</th>
                                    <th>Logged Date/Time</th>
                                    <th>Logged Since</th>
                                    <th>Media</th>
                                    <th>Postponed Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if(!isset($postponeComplaints) && !myIsArray($postponeComplaints))
                                {
                                    ?>
                                    <tr class="my-danger-text text-center">
                                        <td colspan="8">No Records Found!</td>
                                    </tr>
                                    <?php
                                }
                                else
                                {
                                    foreach($postponeComplaints as $key => $row)
                                    {
                                        ?>
                                        <tr>
                                            <td scope="row">
                                                <?php
                                                switch($row['jobPriority'])
                                                {
                                                    case JOB_PRIORITY_HIGH:
                                                        ?>
                                                        <div class="job-with-high"><?php echo $row['complaintId'];?></div>
                                                        <?php
                                                        break;
                                                    case JOB_PRIORITY_MEDIUM:
                                                        ?>
                                                        <div class="job-with-medium"><?php echo $row['complaintId'];?></div>
                                                        <?php
                                                        break;
                                                    case JOB_PRIORITY_LOW:
                                                        ?>
                                                        <div class="job-with-low"><?php echo $row['complaintId'];?></div>
                                                        <?php
                                                        break;
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $row['areaName'];?></td>
                                            <td><?php
                                                echo $row['typeName'];
                                                if(isset($row['subTypeName']) && !empty($row['subTypeName']))
                                                {
                                                    echo ' ('.$row['subTypeName'].')';
                                                }
                                                ?></td>
                                            <td><?php echo $row['problemDescription'];?></td>
                                            <td><?php echo $row['locName'];?></td>
                                            <td><?php $d = date_create($row['loggedDT']); echo date_format($d,DATE_TIME_FORMAT_UI);?></td>
                                            <td>
                                                <time class="timeago time-stamp" datetime="<?php echo $row['loggedDT'];?>"></time>
                                            </td>
                                            <td>
                                                <?php
                                                if(isset($row['problemMedia']))
                                                {
                                                    if($row['pMediaType'] == '1')
                                                    {
                                                        ?>
                                                        <a href="#" class="show-img-media" data-media="<?php echo base_url().JOB_MEDIA_PATH.$row['problemMedia'];?>">View Media</a>
                                                        <?php
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                        <a href="#" class="show-vid-media" data-media="<?php echo base_url().JOB_MEDIA_PATH.$row['problemMedia'];?>">View Media</a>
                                                        <?php
                                                    }
                                                }
                                                else
                                                {
                                                    echo 'No Media!';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php echo $row['postpondDate'];?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <div id="payfilter" class="tab-pane fade">
                            <div class="row">
                                <div class="col-xs-12">
                                    <?php
                                        if(isset($allTotAmt) && isset($allClosedTotAmt))
                                        {
                                            ?>
                                            <table class="table table-responsive">
                                                <thead>
                                                <tr>
                                                    <th>Taproom</th>
                                                    <th>Approx Cost</th>
                                                    <th>Actual Spend</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                for($i=0;$i<count($allTotAmt);$i++)
                                                {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $allTotAmt[$i]['locName']; ?></td>
                                                        <td><?php echo $allTotAmt[$i]['locAmount']; ?></td>
                                                        <td><?php echo $allClosedTotAmt[$i]['locAmount']; ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                                </tbody>
                                            </table>
                                            <?php
/*                                            $allLocs = array();
                                            $allTot = array();
                                            $allClosedTot = array();
                                            foreach($allTotAmt as $key => $row)
                                            {
                                                $allLocs[] = $row['locName'];
                                                $allTot[] = $row['locAmount'];
                                            }
                                            foreach($allClosedTotAmt as $key => $row)
                                            {
                                                $allClosedTot[] = $row['locAmount'];
                                            }
                                            */?>
                                            <!--<input type="hidden" id="allLocs" value="<?php /*echo implode(',',$allLocs);*/?>"/>
                                            <input type="hidden" id="allTotAmt" value="<?php /*echo implode(',',$allTot);*/?>"/>
                                            <input type="hidden" id="allClosedTotAmt" value="<?php /*echo implode(',',$allClosedTot);*/?>"/>-->
                                            <?php
                                        }
                                    ?>
                                    <!--<canvas id="cost-canvas" class="mygraphs"></canvas>-->
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-12">
                                    <br>
                                    <p>Open Complaint Location wise Amount Balance for accounts approval:</p>
                                    <?php
                                    if(isset($openTot) && myIsArray($openTot))
                                    {
                                        ?>
                                        <table class="table table-responsive">
                                            <thead>
                                            <tr>
                                                <th>Taproom</th>
                                                <th>Amount</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            foreach($openTot as $key => $row)
                                            {
                                                ?>
                                                <tr>
                                                    <td><?php echo $row['locName'];?></td>
                                                    <td>
                                                        <?php
                                                        if(isset($row['locAmount']))
                                                        {
                                                            echo 'Rs. '. $row['locAmount'];
                                                        }
                                                        else
                                                        {
                                                            echo 'Rs. 0';
                                                        }
                                                        ?>
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
                                        echo 'Nothing To pay!';
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <br>
                                    <p>In Progress Amount To be paid (jobs approx amount less than 5k):</p>
                                    <?php
                                    if(isset($tapsTotal) && myIsArray($tapsTotal))
                                    {
                                        ?>
                                        <table class="table table-responsive">
                                            <thead>
                                            <tr>
                                                <th>Taproom</th>
                                                <th>Amount</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            foreach($tapsTotal as $key => $row)
                                            {
                                                ?>
                                                <tr>
                                                    <td><?php echo $row['locName'];?></td>
                                                    <td>
                                                        <?php
                                                        if(isset($row['locAmount']))
                                                        {
                                                            echo 'Rs. '. $row['locAmount'];
                                                        }
                                                        else
                                                        {
                                                            echo 'Rs. 0';
                                                        }
                                                        ?>
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
                                        echo 'Nothing To pay!';
                                    }
                                    ?>
                                </div>
                            </div>
                            <br>
                            <div class="row text-center">
                                <div class="col-xs-12">
                                    <form id="payDateFilter" action="<?php echo base_url().'maintenance/filterBudget';?>" class="form-inline">
                                        <div class="form-group">
                                            <label for="payStartDate">Start Date</label>
                                            <input type="text" class="form-control" name="payStartDate" id="payStartDate"/>
                                        </div>
                                        <div class="form-group">
                                            <label for="payEndDate">End Date</label>
                                            <input type="text" class="form-control" name="payEndDate" id="payEndDate"/>
                                        </div>
                                        <button type="submit" class="btn btn-warning">Submit</button>
                                    </form>
                                    <br>
                                    <span class="no-result hide">No Result Found!</span>
                                    <div class="pay-display hide text-left">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Job Id</th>
                                                    <th>Location</th>
                                                    <th>Amount</th>
                                                    <th>Pay Method</th>
                                                    <th>Pay Date/Time</th>
                                                </tr>
                                            </thead>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-1 col-xs-0"></div>
            </div>
        </div>
    </main>

    <!-- Modal -->
    <div id="openCompModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Updating Open Complaint</h4>
                </div>
                <div class="modal-body">
                    <p class="open-complaint-info"></p>
                    <br>
                    <form method="POST" id="moveToProgress" action="<?php echo base_url().'maintenance/updateOpenComplaint';?>">
                        <input type="hidden" name="complaintId" value=""/>
                        <div class="form-group">
                            <label for="updateOnComplaint">Update on Problem:</label>
                            <textarea id="updateOnComplaint" name="updateOnComplaint" rows="10" cols="20" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="approxCost">Approx Cost:</label>
                            <input type="number" id="approxCost" name="approxCost" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label for="userAssignId">Work Assigned To:</label>
                            <select class="form-control" name="userAssignId" id="userAssignId">
                                <option value="">Select</option>
                            </select>
                        </div>
                        <div class="form-group other-vendor hide">
                            <ul class="list-inline">
                                <li>
                                    <select id="workAssignedTo" name="workAssignedTo" class="form-control">
                                        <option value="">Select</option>
                                    </select>
                                </li>
                                <li>OR</li>
                                <li>
                                    <button type="button" class="btn btn-warning new-vendor-add">Add New</button>
                                </li>
                            </ul>
                            <br>
                            <div class="hide vendor-details-wrapper">
                                <div class="form-group">
                                    <label for="venName">Vendor Name:</label>
                                    <input type="text" id="venName" name="venName" class="form-control"/>
                                </div>
                                <div class="form-group">
                                    <label for="venAddress">Vendor's Address:</label>
                                    <textarea id="venAddress" name="venAddress" rows="5" cols="10" class="form-control"></textarea>
                                </div>
                                <!--<div class="form-group">
                                    <label for="venBName">Vendor's Bank Name:</label>
                                    <input type="text" id="venBName" name="venBName" class="form-control"/>
                                </div>
                                <div class="form-group">
                                    <label for="venBank">Vendor's Bank A/c Number:</label>
                                    <input type="number" id="venBank" name="venBank" class="form-control"/>
                                </div>
                                <div class="form-group">
                                    <label for="venIfsc">Vendor's IFSC Code:</label>
                                    <input type="text" id="venIfsc" name="venIfsc" class="form-control"/>
                                </div>-->
                                <div class="form-group">
                                    <label for="bankCheque">Cancel Cheque Photo</label>
                                    <input type="file" class="form-control" id="bankCheque" onchange="uploadVendorChange(this,1)" />
                                    <input type="hidden" name="bankCheque" value=""/>
                                </div>
                                <div class="form-group">
                                    <label for="venPan">Vendor's Pan Or GST Photo :</label>
                                    <input type="file" class="form-control" id="venPan" multiple onchange="uploadVendorChange(this,2)" />
                                    <input type="hidden" name="venPan" value=""/>
                                </div>
                                <br>
                                <div class="progress hide">
                                    <div class="progress-bar progress-bar-striped active" role="progressbar"
                                         aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal -->
    <div id="actionsModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="complaintId"/>
                    <input type="hidden" id="whatUpdating" value=""/>
                    <div class="form-group decline-panel hide">
                        <label for="declineReason">Decline Reason:</label>
                        <textarea id="declineReason" name="declineReason" rows="8" cols="20" class="form-control"></textarea>
                    </div>
                    <div class="form-group postpone-panel hide">
                        <label for="postponeDate">Postponed Date:</label>
                        <input type="text" class="form-control" id="postponeDate"/>
                    </div>
                    <button type="button" class="btn btn-success save-complaint-update">Submit</button>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal -->
    <div id="budgetModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Budget Approval</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="complaintId"/>
                    <input type="hidden" id="totAmt"/>
                    <ul class="list-inline">
                        <li>
                            <p>Amount: <span class="initial-compAmount"></span></p>
                        </li>
                        <li class="split-btn">
                            <button type="button" class="btn btn-success" id="splitAmount">Split Amount</button>
                        </li>
                    </ul>
                    <ul class="list-inline singleDate-panel">
                        <li>
                            <div class="form-group">
                                <label for="budgetDate">Budget Date:</label>
                                <input type="text" class="form-control" id="budgetDate"/>
                            </div>
                        </li>
                        <li>
                            <div class="form-group">
                                <label for="payType">Pay Options:</label>
                                <select id="payType" class="form-control">
                                    <option value="">Select</option>
                                    <?php
                                        $payTypes = $this->config->item('payTypes');
                                        foreach($payTypes as $key)
                                        {
                                            if($key == 'Maintenance Balance')
                                            {
                                                if($mainBalance != 0)
                                                {
                                                    ?>
                                                    <option value="<?php echo $key;?>"><?php echo $key;?></option>
                                                    <?php
                                                }
                                            }
                                            else
                                            {
                                                ?>
                                                <option value="<?php echo $key;?>"><?php echo $key;?></option>
                                                <?php
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </li>
                    </ul>

                    <div class="split-panel"></div>
                    <button type="button" class="btn btn-success save-budget-update">Submit</button>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal -->
    <div id="mediaModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content media-content-wrapper">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body media-body">

                </div>
            </div>

        </div>
    </div>

    <!-- Details Modal -->
    <div id="detailsModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Complaint #<span class="complaint-num"></span> Details</h4>
                </div>
                <div class="modal-body details-body">

                </div>
            </div>
        </div>
    </div>

    <div id="paymentInfoModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Payment Details</h4>
                </div>
                <div class="modal-body details-body">

                </div>
            </div>
        </div>
    </div>

    <div id="finalModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Complaint #<span class="complaint-num"></span> Final Update</h4>
                </div>
                <div class="modal-body details-body">
                    <input type="hidden" id="compId"/>
                    <input type="hidden" id="compAmt"/>
                    <div class="form-group">
                        <label for="remarkIfAny">Job Remark: </label>
                        <textarea id="remarkIfAny" class="form-control" rows="5" cols="10"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="actualCost">Job Actual Cost: </label>
                        <input type="number" id="actualCost" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label for="optTax">Tax (optional): </label>
                        <input type="number" id="optTax" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label for="solutionMedia">Invoice(s) Upload</label>
                        <input type="file" class="form-control" multiple id="invoicePics" onchange="uploadInvoiceChange(this)" />
                        <input type="hidden" name="invoicePics" value=""/>
                    </div>
                    <br>
                    <div class="progress hide">
                        <div class="progress-bar progress-bar-striped active" role="progressbar"
                             aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                    <button type="button" class="btn btn-success save-final-job">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div id="onlyInvoiceModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Invoice Upload</h4>
                </div>
                <div class="modal-body details-body">
                    <input type="hidden" id="compId"/>
                    <div class="form-group">
                        <label for="solutionMedia">Invoice(s) Upload</label>
                        <input type="file" class="form-control" multiple id="invoicePics" onchange="uploadOnlyInvoiceChange(this)" />
                        <input type="hidden" name="invoicePics" value=""/>
                    </div>
                    <br>
                    <div class="progress hide">
                        <div class="progress-bar progress-bar-striped active" role="progressbar"
                             aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                    <button type="button" class="btn btn-success save-final-invoices">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div id="priorityModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Complaint #<span class="complaint-num"></span> Priority Change</h4>
                </div>
                <div class="modal-body details-body">
                    <form action="<?php echo base_url().'maintenance/changePri';?>" method="POST" class="form">
                        <input type="hidden" name="complaintId" value=""/>
                        <div class="form-group pri-group">
                            <ul class="list-inline">
                                <li>
                                    <label class="radio-inline job-high-pri"><input type="radio" name="jobPriority" value="1">High</label>
                                </li>
                                <li>
                                    <label class="radio-inline job-medium-pri"><input type="radio" name="jobPriority" value="2">Medium</label>
                                </li>
                                <li>
                                    <label class="radio-inline job-low-pri"><input type="radio" name="jobPriority" value="3">Low</label>
                                </li>
                            </ul>
                        </div>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php echo $footerView;?>
</body>
<?php echo $globalJs; ?>

<script>

    $("time.timeago").timeago();
    var openTab,progressTab,closeTab,postponeTab;

    openTab = $('#openTab').DataTable({
        "ordering": false
    });
    progressTab = $('#progressTab').DataTable({
        "ordering": false
    });
    closeTab = $('#closeTab').DataTable({
        "ordering": false
    });
    postponeTab = $('#postponeTab').DataTable({
        "ordering": false
    });
    $('#payStartDate,#payEndDate').datetimepicker({
        format: 'YYYY-MM-DD'
    });
    $(document).ready(function(){
        if(localStorageUtil.getLocal('currTab') != null)
        {
            var curT = localStorageUtil.getLocal('currTab');
            $('.nav.nav-tabs li a[href="'+curT+'"]').tab('show');
            openTab.page(localStorageUtil.getLocal('openPage')).draw('page');
            progressTab.page(localStorageUtil.getLocal('progressPage')).draw('page');
            closeTab.page(localStorageUtil.getLocal('closePage')).draw('page');
            postponeTab.page(localStorageUtil.getLocal('postponePage')).draw('page');
            localStorageUtil.delLocal('currTab');
        }
    });

    $(document).on('submit','#openCompModal #moveToProgress', function(e){
        e.preventDefault();
        if($('#updateOnComplaint').val() == '')
        {
            bootbox.alert('Error: Update On Problem is Required!');
            return false;
        }
        if($('#approxCost').val() == '')
        {
            bootbox.alert('Error: Approx cost is Required!');
            return false;
        }
        if($('#userAssignId').val() == '')
        {
            bootbox.alert('Error: Assignment of work is Required!');
            return false;
        }

        if($('#userAssignId').val() == 'other' && $('#workAssignedTo').val() == '')
        {
            if($('#openCompModal .vendor-details-wrapper #venName').val() == '')
            {
                bootbox.alert('Error: Vendor Name required!');
                return false;
            }
            if($('#openCompModal .vendor-details-wrapper input[name="venPan"]').val() == '')
            {
                bootbox.alert('Error: Vendor Pan Image required!');
                return false;
            }
            if($('#openCompModal .vendor-details-wrapper input[name="bankCheque"]').val() == '')
            {
                bootbox.alert('Error: Vendor Cheque Image required!');
                return false;
            }
            /*if(!$.isNumeric($('#openCompModal .vendor-details-wrapper #venBank').val()))
            {
                bootbox.alert('Error: Enter a Valid a/c number!');
                return false;
            }
            var ifscReg = /^[^\s]{4}\d{7}$/;
            if(!ifscReg.test($('#openCompModal .vendor-details-wrapper #venIfsc').val()))
            {
                bootbox.alert('Error: Valid Ifsc code is required!');
                return false;
            }

            var regpan = /^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/;
            if(!regpan.test($('#openCompModal .vendor-details-wrapper #venPan').val()))
            {
                bootbox.alert('Error: Enter a Valid Pan number!');
                return false;
            }*/

        }
        var errUrl = $(this).attr('action');

        showCustomLoader();
        $.ajax({
            type:'POST',
            dataType:'json',
            data: $(this).serialize(),
            url:$(this).attr('action'),
            success: function(data)
            {
                if(data.status === true)
                {
                    window.location.reload();
                }
                else
                {
                    bootbox.alert(data.errorMsg);
                }
            },
            error:function(xhr, status, error)
            {
                hideCustomLoader();
                //bootbox.alert('Some Error Occurred, Try Again!');
                var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                bootbox.alert(err);
                saveErrorLog(err);
            }
        });

    });

    $(document).on('click','.update-complaint', function(){
        var compId = $(this).attr('data-complaintId');
        var errUrl = base_url+'maintenance/getComplaintInfo/'+compId;
        showCustomLoader();
        $.ajax({
            type:'GET',
            dataType:'json',
            url:base_url+'maintenance/getComplaintInfo/'+compId,
            success: function(data){
                hideCustomLoader();
                var complaintInfo = 'Complaint ID: '+compId+'<br>Location: '+data.compInfo.locName+'<br>Problem At: '+data.compInfo.areaName+
                        '<br>Work Type: '+data.compInfo.typeName+'<br>Raised By: '+data.compInfo.userName+'<br>Logged Date/Time: '+data.compInfo.loggedDT+
                        '<br>Problem: '+data.compInfo.problemDescription;

                $('#openCompModal .open-complaint-info').html(complaintInfo);
                $('#openCompModal #moveToProgress input[name="complaintId"]').val(compId);

                if(typeof data.userList !== 'undefined' && data.userList.length > 0)
                {
                    var optHtml = '';
                    for(var i=0;i<data.userList.length;i++)
                    {
                        optHtml += '<option value="'+data.userList[i].id+'">'+data.userList[i].userName+'</option>';
                    }
                    optHtml += '<option value="other">Outside Vendor</option>';
                    $('#openCompModal #userAssignId').html(optHtml);
                }

                $('#openCompModal').modal('show');
            },
            error:function(xhr, status, error)
            {
                hideCustomLoader();
                bootbox.alert('Some Error Occurred, Try Again!');
                var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                saveErrorLog(err);
            }
        });
    });

    $(document).on('change','#openCompModal #workAssignedTo', function(){
        if($(this).val() != '')
        {
            $('#openCompModal .vendor-details-wrapper').addClass('hide');
        }
    });

    $(document).on('change','#openCompModal #userAssignId', function(){
        if($(this).val() != '')
        {
            if($(this).val() == 'other')
            {
                var errUrl = base_url+'maintenance/searchVendor';
                showCustomLoader();
                $.ajax({
                    type:'GET',
                    dataType:'json',
                    url: base_url+'maintenance/searchVendor',
                    success: function(data){
                        hideCustomLoader();
                        if(data.status === true)
                        {
                            var optHtml = '<option value="">Select</option>';
                            for(var i=0;i<data.vendors.length;i++)
                            {
                                optHtml += '<option value="'+data.vendors[i].vendorName+'">'+data.vendors[i].vendorName+'</option>';
                            }
                            $('#openCompModal .other-vendor #workAssignedTo').html(optHtml);
                        }

                    },
                    error:function(xhr, status, error)
                    {
                        hideCustomLoader();
                        bootbox.alert('Some Error Occurred, Try Again!');
                        var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                        saveErrorLog(err);
                    }
                });
                $('#openCompModal .other-vendor').removeClass('hide');
            }
            else
            {
                $('#openCompModal .other-vendor').addClass('hide');
            }
        }
    });

    $(document).on('click','#openCompModal .new-vendor-add', function(){
        $('#openCompModal .other-vendor #workAssignedTo').val('');
        $('#openCompModal .vendor-details-wrapper').removeClass('hide');
    });

    $(document).on('click','.decline-btn', function(e){
       e.preventDefault();
       var compId = $(this).attr('data-complaintId');
       $('#actionsModal #complaintId').val(compId);
       $('#actionsModal .modal-title').html('Declining a complaint.');
       $('#actionsModal .decline-panel').removeClass('hide');
       if(!$('#actionsModal .postpone-panel').hasClass('hide'))
       {
           $('#actionsModal .postpone-panel').addClass('hide');
       }
       $('#actionsModal #whatUpdating').val('1');
       $('#actionsModal').modal('show');
    });
    $(document).on('click','.postpone-btn', function(e){
        e.preventDefault();
        var compId = $(this).attr('data-complaintId');
        $('#actionsModal #complaintId').val(compId);
        $('#actionsModal .modal-title').html('Postponing a complaint.');
        $('#actionsModal .postpone-panel').removeClass('hide');
        if(!$('#actionsModal .decline-panel').hasClass('hide'))
        {
            $('#actionsModal .decline-panel').addClass('hide');
        }
        $('#actionsModal #whatUpdating').val('2');
        $('#actionsModal #postponeDate').datetimepicker({
            format: 'YYYY-MM-DD'
        });
        $('#actionsModal').modal('show');
    });

    $(document).on('click','#actionsModal .save-complaint-update' , function(){
        var whatUp = $('#actionsModal #whatUpdating').val();

        if(whatUp == '1')
        {
            if($('#actionsModal #declineReason').val() == '')
            {
                bootbox.alert('Error: Please Provide the Reason!');
                return false;
            }

            var compId = $('#actionsModal #complaintId').val();
            var reason = $('#actionsModal #declineReason').val();
            var errUrl = base_url+'maintenance/declineJob';
            showCustomLoader();
            $.ajax({
                type:'POST',
                dataType:'json',
                data:{compId:compId,decReason:reason},
                url:base_url+'maintenance/declineJob',
                success: function(data){
                    if(data.status === true)
                    {
                        window.location.reload();
                    }
                    else
                    {
                        bootbox.alert(data.errorMsg);
                    }
                },
                error:function(xhr, status, error)
                {
                    hideCustomLoader();
                    bootbox.alert('Some Error Occurred, Try Again!');
                    var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                    saveErrorLog(err);
                }
            });

        }
        else
        {
            if($('#actionsModal #postponeDate').val() == '')
            {
                bootbox.alert('Error: Please Provide the Date!');
                return false;
            }

            var compId = $('#actionsModal #complaintId').val();
            var postdate = $('#actionsModal #postponeDate').val();
            var errUrl = base_url+'maintenance/postponeJob';
            showCustomLoader();
            $.ajax({
                type:'POST',
                dataType:'json',
                data:{compId:compId,postdate:postdate},
                url:base_url+'maintenance/postponeJob',
                success: function(data){
                    if(data.status === true)
                    {
                        window.location.reload();
                    }
                    else
                    {
                        bootbox.alert(data.errorMsg);
                    }
                },
                error:function(xhr, status, error)
                {
                    hideCustomLoader();
                    bootbox.alert('Some Error Occurred, Try Again!');
                    var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                    saveErrorLog(err);
                }
            });
        }
    });

    $(document).on('click','.view-reason',function(){
        var reason = $(this).attr('data-reason');
        if(reason != '')
        {
            bootbox.alert('Reason: '+reason);
        }
        else
        {
            bootbox.alert('Error: Reason Not Found!');
        }
    });

    $(document).on('click','.update-budget', function(){
        var compId = $(this).attr('data-complaintId');
        var compAmt = $(this).attr('data-compAmt');
        if(compId != '' && compAmt != '')
        {
            $('#budgetModal #complaintId').val(compId);
            $('#budgetModal .initial-compAmount').html('Rs '+compAmt);
            $('#budgetModal #totAmt').val(compAmt);
            if(Number(compAmt) > 15000)
            {
                //$('#budgetModal .split-btn').removeClass('hide');
            }
            else
            {
                //$('#budgetModal .split-btn').addClass('hide');
                $('#budgetModal .singleDate-panel').removeClass('hide');
            }
            $('#budgetModal #budgetDate').datetimepicker({
                format:'YYYY-MM-DD hh:mm A',
                minDate: new Date()
            });
            $('#budgetModal').modal('show');
        }
        else
        {
            bootbox.alert('Data is corrupted!');
        }
    });

    var amtCount = 1;
    $(document).on('click','#budgetModal #splitAmount',function(e){
        $(this).html('Add More?');
        if(!$('#budgetModal .singleDate-panel').hasClass('hide'))
        {
            $('#budgetModal .singleDate-panel').addClass('hide');
        }
        var payTypes = [];
        <?php
            $payTypes = $this->config->item('payTypes');
            foreach($payTypes as $key)
            {
                ?>
                payTypes.push('<?php echo $key;?>');
                <?php
            }
        ?>
        for(var i=0;i<2;i++)
        {
            var splitHtml = '<div class="form-group split-group">'+
                '<ul class="list-inline">'+
                '<li>'+
                '<label>Amount: </label>'+
                '<input type="number" class="form-control" name="amt[]"/>'+
                '</li><li>'+
                '<label>Date: </label>'+
                '<input type="text" class="form-control" id="split-bud'+amtCount+'" name="amtDate[]"/>'+
                '</li><li><label>Pay Options</label>'+
                '<select name="payType[]" class="form-control"><option value="">Select</option>';
            for(var j=0;j<payTypes.length;j++)
            {
                if(payTypes[j] == 'Maintenance Balance')
                {
                    if($('#mainBalance').val() != '0')
                    {
                        splitHtml += '<option value="'+payTypes[j]+'">'+payTypes[j]+'</option>';
                    }
                }
                else
                {
                    splitHtml += '<option value="'+payTypes[j]+'">'+payTypes[j]+'</option>';
                }
            }
            splitHtml += '</select></li></ul></div>';
            $('#budgetModal .split-panel').append(splitHtml);
            $('#budgetModal .split-panel #split-bud'+amtCount+'').datetimepicker({
                format:'YYYY-MM-DD hh:mm A',
                minDate: new Date()
            });
            amtCount++;
        }

        //$('#budgetModal .split-panel').append(splitHtml);

    });

    $(document).on('click','#budgetModal .save-budget-update', function(e){
        e.preventDefault();

        var compId = $('#budgetModal #complaintId').val();
        if($('#budgetModal .split-panel').html() == '')
        {
            if($('#budgetModal #budgetDate').val() != '')
            {
                var errUrl = base_url+'maintenance/saveBudget';
                showCustomLoader();
                $.ajax({
                    type:'POST',
                    dataType:'json',
                    url:base_url+'maintenance/saveBudget',
                    data:{compId:compId,budDate:$('#budgetModal #budgetDate').val(),payType:$('#budgetModal #payType').val()},
                    success: function(data){
                        hideCustomLoader();
                        if(data.status === true)
                        {
                            window.location.reload();
                        }
                        else
                        {
                            bootbox.alert('Error: '+data.errorMsg);
                        }
                    },
                    error:function(xhr, status, error)
                    {
                        hideCustomLoader();
                        bootbox.alert('Some Error Occurred, Try Again!');
                        var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                        saveErrorLog(err);
                    }
                });
            }
            else
            {
                bootbox.alert('Budget Date is Required!');
            }
        }
        else
        {
            var Tot = 0;
            var amts = [];
            var amtsDate = [];
            var payTypes = [];
            $('#budgetModal .split-group input[name="amt[]"]').each(function(i,val){
                Tot += Number($(val).val());
                amts.push($(val).val());
            });
            $('#budgetModal .split-group input[name="amtDate[]"]').each(function(i,val){
                amtsDate.push($(val).val());
            });
            $('#budgetModal .split-group select[name="payType[]"]').each(function(i,val){
                payTypes.push($(val).val());
            });
            if(Tot == $('#budgetModal #totAmt').val())
            {
                var errUrl = base_url+'maintenance/saveBudget';
                showCustomLoader();
                $.ajax({
                    type:'POST',
                    dataType:'json',
                    url:base_url+'maintenance/saveBudget',
                    data:{compId:compId,budAmts:amts,budDates:amtsDate,payTypes: payTypes},
                    success: function(data){
                        hideCustomLoader();
                        if(data.status === true)
                        {
                            window.location.reload();
                        }
                        else
                        {
                            bootbox.alert('Error: '+data.errorMsg);
                        }
                    },
                    error:function(xhr, status, error)
                    {
                        hideCustomLoader();
                        bootbox.alert('Some Error Occurred, Try Again!');
                        var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                        saveErrorLog(err);
                    }
                });
            }
            else
            {
                bootbox.alert('Request amount and approval amount doesn\'t match!');
            }
        }
    });

    $(document).on('click','#close-multiple-jobs',function(){
        var compIds = [];

        $('input[name="jobs"]').each(function(i,val){
            if($(val).is(':checked'))
            {
                compIds.push($(val).val());
            }
        });
        if(compIds.length > 0)
        {
            bootbox.confirm("Sure want to close "+compIds.length+" Job(s) now?", function(result) {
                if(result === true)
                {
                    var errUrl = base_url+'maintenance/partialClose';
                    showCustomLoader();
                    $.ajax({
                        type:"POST",
                        dataType:"json",
                        url:base_url+'maintenance/partialClose',
                        data:{compIds: compIds.join(',')},
                        success: function(data){
                            hideCustomLoader();
                            if(data.status === true)
                            {
                                window.location.reload();
                            }
                            else
                            {
                                bootbox.alert('Error: '+data.errorMsg);
                            }
                        },
                        error:function(xhr, status, error)
                        {
                            hideCustomLoader();
                            bootbox.alert('Some Error Occurred, Try Again!');
                            var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                            saveErrorLog(err);
                        }
                    });
                }
            });
        }
        else
        {
            bootbox.alert('Please select at least 1 job');
        }
    });

    $(document).on('click','.show-img-media',function(e){
        var media = $(this).attr('data-media');
        var meHtml = '<img src="'+media+'" alt="Media" class="img-responsive"/>';
        $('#mediaModal .media-body').html(meHtml);
        $('#mediaModal').modal('show');
    });
    $(document).on('click','.show-vid-media',function(e){
        var media = $(this).attr('data-media');
        var meHtml = '<video width="400" controls>'+
            '<source src="'+media+'">'+
            'Your browser does not support HTML5 video.'+
        '</video>';
        $('#mediaModal .media-body').html(meHtml);
        $('#mediaModal').modal('show');
    });

    $(document).on('click','.get-update-info',function(){
         var jobId = $(this).attr('data-jobId');
         if(jobId != '')
         {
             var errUrl = base_url+'maintenance/getJobDetails/'+jobId;
             showCustomLoader();
             $.ajax({
                 type:'GET',
                 dataType:'json',
                 url:base_url+'maintenance/getJobDetails/'+jobId,
                 success: function(data) {
                     hideCustomLoader();
                     if (data.status === true)
                     {
                         $('#detailsModal .complaint-num').html(jobId);
                         var complaintInfo = '<b>Location:</b> '+data.compInfo.locName+'<br><b>Problem At:</b> '+data.compInfo.areaName+
                             '<br><b>Work Type:</b> '+data.compInfo.typeName+'<br><b>Raised By:</b> '+data.compInfo.userName+'<br><b>Logged Date/Time:</b> '+data.compInfo.loggedDT+
                             '<br><b>Problem:</b> '+data.compInfo.problemDescription;
                         if(data.compInfo.updateOnComplaint != null)
                         {
                             complaintInfo += '<br><b>Update on Problem:</b> '+data.compInfo.updateOnComplaint;
                         }
                         if(data.compInfo.approxCost != null)
                         {
                             complaintInfo += '<br><b>Approx Cost:</b> Rs '+data.compInfo.approxCost;
                         }
                         if(data.compInfo.assignee != null)
                         {
                             complaintInfo += '<br><b>Assigned To:</b> '+data.compInfo.assignee;
                         }
                         if(data.compInfo.workAssignedTo != null)
                         {
                             complaintInfo += '<br><b>Assigned To:</b> '+data.compInfo.workAssignedTo;
                         }
                         if(data.compInfo.remarkIfAny != null)
                         {
                             complaintInfo += '<br><b>Final Remark:</b> '+data.compInfo.remarkIfAny;
                         }
                         if(data.compInfo.actualCost != null)
                         {
                             complaintInfo += '<br><b>Actual Cost:</b> Rs '+data.compInfo.actualCost;
                         }

                         $('#detailsModal .details-body').html(complaintInfo);

                         $('#detailsModal').modal('show');
                     }
                     else
                     {
                         bootbox.alert(data.errorMsg);
                     }
                 },
                 error:function(xhr, status, error)
                 {
                     hideCustomLoader();
                     bootbox.alert('Some Error Occurred, Try Again!');
                     var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                     saveErrorLog(err);
                 }
             });
         }
    });

    $(document).on('click','.budget-confirm-link',function(){
        var jobId = $(this).attr('data-jobId');
        if(jobId != '')
        {
            bootbox.dialog({
                message:'Received Date: <input class="form-control" id="budDate"/>',
                title: "Custom label",
                buttons: {
                    main: {
                        label: "Save",
                        className: "btn-primary",
                        callback: function() {
                            if($('#budDate').val() != '')
                            {
                                var budDate = $('#budDate').val();

                                var errUrl = base_url+'maintenance/updateAmtReceived';
                                showCustomLoader();
                                $.ajax({
                                    type:'POST',
                                    dataType:'json',
                                    data:{finId:jobId,finDate:budDate},
                                    url:base_url+'maintenance/updateAmtReceived',
                                    success: function(data) {
                                        hideCustomLoader();
                                        if (data.status === true)
                                        {
                                            window.location.reload();
                                        }
                                        else
                                        {
                                            bootbox.alert(data.errorMsg);
                                        }
                                    },
                                    error:function(xhr, status, error)
                                    {
                                        hideCustomLoader();
                                        bootbox.alert('Some Error Occurred, Try Again!');
                                        var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                                        saveErrorLog(err);
                                    }
                                });
                            }
                        }
                    }
                }
            });
            $('#budDate').datetimepicker({
                format:'YYYY-MM-DD hh:mm A'
            });
        }

    });

    $(document).on('click', '.fetch-budget-info', function(){
        var budInfo = $(this).parent().find('.budget-info-collection').val().split('|');
        var payHtml = '<table class="table table-bordered table-striped">';
        payHtml += '<thead><tr><th>Amount</th><th>Date/Time</th><th>Pay Method</th></thead><tbody>';
        for(var i=0;i<budInfo.length;i++)
        {
            var splitTxt = budInfo[i].split(';');
            payHtml += '<tr>';
            payHtml += '<td>'+splitTxt[0]+'</td>';
            payHtml += '<td>'+splitTxt[1]+'</td>';
            payHtml += '<td>'+splitTxt[2]+'</td>';
            payHtml += '</tr>';
        }
        payHtml += '</tbody></table>';
        $('#paymentInfoModal .details-body').html(payHtml);
        $('#paymentInfoModal').modal('show');
    });

    $(document).on('click','.vendor-info-fetch', function(){
        var venId = $(this).attr('data-vendorId');

        var errUrl = base_url+'maintenance/vendorInfo/'+venId;
        showCustomLoader();
        $.ajax({
            type:'GET',
            dataType: 'json',
            url: base_url+'maintenance/vendorInfo/'+venId,
            success:function(data){
                hideCustomLoader();
                if(data.status === true)
                {
                    var venHtml = '<b>Vendor Name:</b> '+data.venInfo.vendorName;
                    venHtml += '<br><b>Vendor Address:</b> '+data.venInfo.address;
                    venHtml += '<br><b>Cheque Image:</b><br><img src="'+base_url+'uploads/jobs/'+data.venInfo.bankCheque+'" class="img-responsive"/>';
                    venHtml += '<br><b>Vendor Pan Card:</b><br>';
                    var pImgs = data.venInfo.panCard.split(',');
                    for(var i=0;i<pImgs.length;i++)
                    {
                        venHtml += '<img src="'+base_url+'uploads/jobs/'+pImgs[i]+'" class="img-responsive"/><br>';
                    }

                    bootbox.alert(venHtml);
                }
                else
                {
                    bootbox.alert(data.errorMsg);
                }
            },
            error:function(xhr, status, error)
            {
                hideCustomLoader();
                bootbox.alert('Some Error Occurred, Try Again!');
                var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                saveErrorLog(err);
            }
        });
    });

    $(document).on('click','.final-job-update',function(){
        var compId = $(this).attr('data-compId');
        var compAmt = $(this).attr('data-compAmt');
        $('#finalModal .complaint-num').html(compId);
        $('#finalModal #compId').val(compId);
        $('#finalModal #compAmt').val(compAmt);
        if(compAmt == '0')
        {
            $('#finalModal #actualCost').val(0);
        }
        $('#finalModal').modal('show');
    });
    $(document).on('click','#finalModal .save-final-job', function(){
       var compId = $('#finalModal .details-body #compId').val();
       var remark = $('#finalModal .details-body #remarkIfAny').val();
       var acCost = Number($('#finalModal .details-body #actualCost').val());
       var optTax = Number($('#finalModal .details-body #optTax').val());
       var compAmt = Number($('#finalModal .details-body #compAmt').val());

       if(remark == '' && acCost == '')
       {
           bootbox.alert('All fileds are required!');
           return false;
       }
       /*if(acCost != 0 && $('#finalModal input[name="invoicePics"]').val() == '')
       {
           bootbox.alert('Invoice is Required!');
           return false;
       }*/
       /*var totAmt=acCost;
       if(optTax != 0)
       {
           totAmt = acCost+optTax;
       }
       else
       {
           optTax=0;
       }
       if(totAmt > compAmt)
       {
            bootbox.alert('Amount can\'t be more then approx cost');
            return false;
       }*/
       var errUrl = base_url+'maintenance/finalUpdate';
       showCustomLoader();
       $.ajax({
           type:'POST',
           dataType:'json',
           data:{compId:compId,remark:remark,acCost:acCost,optTax:optTax,invoicePics: $('#finalModal input[name="invoicePics"]').val()},
           url:base_url+'maintenance/finalUpdate',
           success: function(data){
               hideCustomLoader();
                if(data.status === true)
                {
                    window.location.reload();
                }
                else
                {
                    bootbox.alert(data.errorMsg);
                }
           },
           error:function(xhr, status, error)
           {
               hideCustomLoader();
               bootbox.alert('Some Error Occurred, Try Again!');
               var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
               saveErrorLog(err);
           }

       });
    });

    var filesArr = [];
    function uploadInvoiceChange(ele)
    {
        $('#finalModal button[type="button"]').attr('disabled','true');
        $('#finalModal .progress').removeClass('hide');
        var xhr = [];
        var totalFiles = ele.files.length;
        for(var i=0;i<totalFiles;i++)
        {
            xhr[i] = new XMLHttpRequest();
            (xhr[i].upload || xhr[i]).addEventListener('progress', function(e) {
                var done = e.position || e.loaded;
                var total = e.totalSize || e.total;
                $('#finalModal .progress-bar').css('width', Math.round(done/total*100)+'%').attr('aria-valuenow', Math.round(done/total*100)).html(parseInt(Math.round(done/total*100))+'%');
            });
            xhr[i].addEventListener('load', function(e) {
                $('#finalModal button[type="button"]').removeAttr('disabled');
            });
            xhr[i].open('post', '<?php echo base_url();?>maintenance/uploadInvoiceFiles', true);

            var data = new FormData;
            data.append('attachment', ele.files[i]);
            xhr[i].send(data);
            xhr[i].onreadystatechange = function(e) {
                if (e.srcElement.readyState == 4 && e.srcElement.status == 200) {
                    if(e.srcElement.responseText == 'Some Error Occurred!')
                    {
                        bootbox.alert('File size Limit 30MB');
                        return false;
                    }
                    try
                    {
                        var obj = $.parseJSON(e.srcElement.responseText);
                        if(obj.status == false)
                        {
                            bootbox.alert('<label class="my-danger-text">Error: '+obj.errorMsg+'</label>');
                            return false;
                        }
                    }
                    catch(excep)
                    {
                        filesArr.push(e.srcElement.responseText);
                        fillImgs();
                    }
                }
            }
        }
    }
    function fillImgs()
    {
        $('#finalModal input[name="invoicePics"]').val(filesArr.join());
    }

    var filesInvoiceArr = [];
    function uploadOnlyInvoiceChange(ele)
    {
        $('#onlyInvoiceModal button[type="button"]').attr('disabled','true');
        $('#onlyInvoiceModal .progress').removeClass('hide');
        var xhr = [];
        var totalFiles = ele.files.length;
        for(var i=0;i<totalFiles;i++)
        {
            xhr[i] = new XMLHttpRequest();
            (xhr[i].upload || xhr[i]).addEventListener('progress', function(e) {
                var done = e.position || e.loaded;
                var total = e.totalSize || e.total;
                $('#onlyInvoiceModal .progress-bar').css('width', Math.round(done/total*100)+'%').attr('aria-valuenow', Math.round(done/total*100)).html(parseInt(Math.round(done/total*100))+'%');
            });
            xhr[i].addEventListener('load', function(e) {
                $('#onlyInvoiceModal button[type="button"]').removeAttr('disabled');
            });
            xhr[i].open('post', '<?php echo base_url();?>maintenance/uploadInvoiceFiles', true);

            var data = new FormData;
            data.append('attachment', ele.files[i]);
            xhr[i].send(data);
            xhr[i].onreadystatechange = function(e) {
                if (e.srcElement.readyState == 4 && e.srcElement.status == 200) {
                    if(e.srcElement.responseText == 'Some Error Occurred!')
                    {
                        bootbox.alert('File size Limit 30MB');
                        return false;
                    }
                    try
                    {
                        var obj = $.parseJSON(e.srcElement.responseText);
                        if(obj.status == false)
                        {
                            bootbox.alert('<label class="my-danger-text">Error: '+obj.errorMsg+'</label>');
                            return false;
                        }
                    }
                    catch(excep)
                    {
                        filesInvoiceArr.push(e.srcElement.responseText);
                        fillInvoiceImgs();
                    }
                }
            }
        }
    }
    function fillInvoiceImgs()
    {
        $('#onlyInvoiceModal input[name="invoicePics"]').val(filesInvoiceArr.join());
    }

    $(document).on('click','.track-me',function(){
        var currentTab = $('.nav.nav-tabs li.active').find('a').attr('href');

        localStorageUtil.setLocal('currTab',currentTab);
        localStorageUtil.setLocal('openPage',openTab.page());
        localStorageUtil.setLocal('progressPage',progressTab.page());
        localStorageUtil.setLocal('closePage',closeTab.page());
        localStorageUtil.setLocal('postponePage',postponeTab.page());
    });

    $(document).on('click','.view-photos', function(){
        var pics = $(this).attr('data-imgs').split(',');
        if(typeof pics != 'undefined')
        {
            var newPics = [];
            for(var i=0;i<pics.length;i++)
            {
                var temp = {href:pics[i],title:''};
                newPics[i] = temp;
            }
            $.swipebox( newPics );
        }
    });

    $(document).on('click','.upload-invoice-pics',function(){
        var compId = $(this).attr('data-compId');
        $('#onlyInvoiceModal #compId').val(compId);
        $('#onlyInvoiceModal').modal('show');
    });

    $(document).on('click','.save-final-invoices', function(){
        var compId = $('#onlyInvoiceModal .details-body #compId').val();

        if($('#onlyInvoiceModal input[name="invoicePics"]').val() == '')
        {
            bootbox.alert('Invoice is Required!');
            return false;
        }

        var errUrl = base_url+'maintenance/invoiceUpdate';
        showCustomLoader();
        $.ajax({
            type:'POST',
            dataType:'json',
            data:{compId:compId,invoicePics: $('#onlyInvoiceModal input[name="invoicePics"]').val()},
            url:base_url+'maintenance/invoiceUpdate',
            success: function(data){
                hideCustomLoader();
                if(data.status === true)
                {
                    window.location.reload();
                }
                else
                {
                    bootbox.alert(data.errorMsg);
                }
            },
            error:function(xhr, status, error)
            {
                hideCustomLoader();
                bootbox.alert('Some Error Occurred, Try Again!');
                var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                saveErrorLog(err);
            }

        });
    });

    var fileChequeArr = [];
    var filesPanArr = [];
    function uploadVendorChange(ele,isCheque)
    {
        $('#openCompModal #moveToProgress button[type="submit"]').attr('disabled','true');
        $('#openCompModal .vendor-details-wrapper .progress').removeClass('hide');
        if(isCheque == 1)
        {
            fileChequeArr = [];
        }
        else
        {
            filesPanArr = [];
        }
        $('#openCompModal .vendor-details-wrapper .progress-bar').css('width','0%').attr('aria-valuenow', 0).html('0%');
        var xhr = [];
        var totalFiles = ele.files.length;
        for(var i=0;i<totalFiles;i++)
        {
            xhr[i] = new XMLHttpRequest();
            (xhr[i].upload || xhr[i]).addEventListener('progress', function(e) {
                var done = e.position || e.loaded;
                var total = e.totalSize || e.total;
                $('#openCompModal .vendor-details-wrapper .progress-bar').css('width', Math.round(done/total*100)+'%').attr('aria-valuenow', Math.round(done/total*100)).html(parseInt(Math.round(done/total*100))+'%');
            });
            xhr[i].addEventListener('load', function(e) {
                $('#openCompModal #moveToProgress button[type="submit"]').removeAttr('disabled');
            });
            xhr[i].open('post', '<?php echo base_url();?>maintenance/uploadVendorFiles', true);

            var data = new FormData;
            data.append('attachment', ele.files[i]);
            xhr[i].send(data);
            xhr[i].onreadystatechange = function(e) {
                if (e.srcElement.readyState == 4 && e.srcElement.status == 200) {
                    if(e.srcElement.responseText == 'Some Error Occurred!')
                    {
                        bootbox.alert('File size Limit 30MB');
                        return false;
                    }
                    try
                    {
                        var obj = $.parseJSON(e.srcElement.responseText);
                        if(obj.status == false)
                        {
                            bootbox.alert('<label class="my-danger-text">Error: '+obj.errorMsg+'</label>');
                            return false;
                        }
                    }
                    catch(excep)
                    {
                        if(isCheque == 1)
                        {
                            fileChequeArr.push(e.srcElement.responseText);
                            fillChequeImgs();
                        }
                        else
                        {
                            filesPanArr.push(e.srcElement.responseText);
                            fillPanImgs();
                        }
                    }
                }
            }
        }
    }
    function fillChequeImgs()
    {
        $('#openCompModal .vendor-details-wrapper input[name="bankCheque"]').val(fileChequeArr.join());
    }
    function fillPanImgs()
    {
        $('#openCompModal .vendor-details-wrapper input[name="venPan"]').val(filesPanArr.join());
    }

    $(document).on('submit','#payDateFilter', function(e){
        e.preventDefault();

        if($('#payStartDate').val() == '' && $('#payEndDate').val() == '')
        {
            bootbox.alert('All Fields are required!');
            return false;
        }

        showCustomLoader();
        $.ajax({
            type:'POST',
            url: $(this).attr('action'),
            dataType: 'json',
            data: $(this).serialize(),
            success: function(data){
                hideCustomLoader();
                if(data.status === true)
                {
                    if(data.payLogs.length>0)
                    {
                        var tbody = '<tbody>';
                        for(var i=0;i<data.payLogs.length;i++)
                        {
                            tbody += '<tr>';
                            tbody += '<td>'+data.payLogs[i].jobId+'</td>';
                            tbody += '<td>'+data.payLogs[i].locName+'</td>';
                            tbody += '<td>'+data.payLogs[i].payAmount+'</td>';
                            tbody += '<td>'+data.payLogs[i].payType+'</td>';
                            tbody += '<td>'+data.payLogs[i].payDate+'</td>';
                            tbody += '</tr>';
                        }
                        tbody += '</tbody>';
                        $('.pay-display table').append(tbody);
                        $('.pay-display').removeClass('hide');
                    }
                    else
                    {
                        $('.no-result').removeClass('hide');
                        $('.pay-display').addClass('hide');
                    }
                }
                else
                {
                    bootbox.alert(data.errorMsg);
                }
            },
            error:function(xhr, status, error)
            {
                hideCustomLoader();
                bootbox.alert('Some Error Occurred, Try Again!');
                var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                saveErrorLog(err);
            }
        });
    });

    $(document).on('click','.change-job-priority', function(){
        var compId = $(this).attr('data-compId');
        var pri = $(this).attr('data-priority');

        if(compId != '' && pri != '')
        {
            $('#priorityModal form input[name="complaintId"]').val(compId);
            $('#priorityModal .complaint-num').html(compId);
            $('#priorityModal form input[name="jobPriority"]').each(function(i,val){
                if($(val).val() == pri)
                {
                    $(val).attr('checked',true);
                    return false;
                }
            });
            $('#priorityModal').modal('show');
        }
    });
</script>
<script>


    /*var barChartData = {
        labels: $('#allLocs').val().split(','),
        datasets: [{
            label: 'Actual Spend',
            backgroundColor: 'red',
            data: $('#allClosedTotAmt').val().split(',')
        }, {
            label: 'Approx Cost',
            backgroundColor: 'skyblue',
            data:  $('#allTotAmt').val().split(',')
        }]

    };
    window.onload = function() {
        var ctx = document.getElementById("cost-canvas").getContext("2d");
        window.myBar = new Chart(ctx, {
            type: 'bar',
            data: barChartData,
            options: {
                title:{
                    display:true,
                    text:"Chart.js Bar Chart - Stacked"
                },
                tooltips: {
                    mode: 'index',
                    intersect: false
                },
                responsive: true,
                scales: {
                    xAxes: [{
                        stacked: true,
                    }],
                    yAxes: [{
                        stacked: true
                    }]
                }
            }
        });
    };*/

</script>
</html>