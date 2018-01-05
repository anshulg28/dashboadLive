<?php

/**
 * Class Login_Model
 * @property Mydatafetch_library $mydatafetch_library
 * @property Generalfunction_library $generalfunction_library
 */
class Maintenance_Model extends CI_Model
{
	function __construct()
	{
		parent::__construct();

        $this->load->library('mydatafetch_library');
	}

	function getAllWorkAreas()
    {
        $query = "SELECT * FROM workareamaster WHERE ifActive = ".ACTIVE;

        $result = $this->db->query($query)->result_array();

        return $result;
    }

    function getAllWorkTypes()
    {
        $query = "SELECT * FROM worktypemaster WHERE ifActive = ".ACTIVE;

        $result = $this->db->query($query)->result_array();

        return $result;
    }

    function getOpenComplaints($locId = '')
    {
        if($locId != '')
        {
            $query = "SELECT clm.*, lm.locName, wam.areaName, wtm.typeName, wst.subTypeName, um.userName, jmm.problemMedia, jmm.pMediaType
                  FROM complaintlogmaster clm 
                  LEFT JOIN locationmaster lm ON lm.id = clm.locId 
                  LEFT JOIN workareamaster wam ON clm.workAreaId = wam.areaId
                  LEFT JOIN worktypemaster wtm ON clm.workTypeId = wtm.typeId 
                  LEFT JOIN worksubtypemaster wst ON wst.subTypeId = clm.subTypeId
                  LEFT JOIN doolally_usersmaster um ON clm.loggedBy = um.userId
                  LEFT JOIN jobmediamaster jmm ON clm.complaintId = jmm.complaintId
                  WHERE clm.locId=".$locId." AND clm.status IN(".LOG_STATUS_OPEN.",".LOG_STATUS_PENDING_APPROVAL.",
                  ".LOG_STATUS_PENDING_BUDGET_APPROVAL.",".LOG_STATUS_DECLINED.") 
                  ORDER BY clm.lastUpdateDT DESC";
        }
        else
        {
            $query = "SELECT clm.*, lm.locName, wam.areaName, wtm.typeName, wst.subTypeName, um.userName,jmm.problemMedia, jmm.pMediaType
                  FROM complaintlogmaster clm 
                  LEFT JOIN locationmaster lm ON lm.id = clm.locId 
                  LEFT JOIN workareamaster wam ON clm.workAreaId = wam.areaId
                  LEFT JOIN worktypemaster wtm ON clm.workTypeId = wtm.typeId 
                  LEFT JOIN worksubtypemaster wst ON wst.subTypeId = clm.subTypeId
                  LEFT JOIN doolally_usersmaster um ON clm.loggedBy = um.userId
                  LEFT JOIN jobmediamaster jmm ON clm.complaintId = jmm.complaintId
                  WHERE clm.status IN(".LOG_STATUS_OPEN.",".LOG_STATUS_PENDING_APPROVAL.",
                  ".LOG_STATUS_PENDING_BUDGET_APPROVAL.",".LOG_STATUS_DECLINED.") 
                  ORDER BY clm.lastUpdateDT DESC";
        }

        $result = $this->db->query($query)->result_array();

        return $result;
    }

    function getProgressComplaints($locId = '')
    {
        if($locId != '')
        {
            $query = "SELECT clm.*, lm.locName, wam.areaName, wtm.typeName, wst.subTypeName, um.userName, cum.userName as 'assignee',
                  jmm.problemMedia, jmm.pMediaType, vdm.vendorId
                  FROM complaintlogmaster clm 
                  LEFT JOIN locationmaster lm ON lm.id = clm.locId 
                  LEFT JOIN workareamaster wam ON clm.workAreaId = wam.areaId
                  LEFT JOIN worktypemaster wtm ON clm.workTypeId = wtm.typeId 
                  LEFT JOIN doolally_usersmaster um ON clm.loggedBy = um.userId
                  LEFT JOIN worksubtypemaster wst ON wst.subTypeId = clm.subTypeId
                  LEFT JOIN complaintusermaster cum ON clm.userAssignId = cum.id
                  LEFT JOIN jobmediamaster jmm ON clm.complaintId = jmm.complaintId
                  LEFT JOIN financestatusmaster fsm ON clm.complaintId  = fsm.jobId
                  LEFT JOIN vendordetailsmaster vdm ON clm.workAssignedTo LIKE vdm.vendorName
                  WHERE clm.locId = ".$locId." AND clm.status = ".LOG_STATUS_IN_PROGRESS." 
                  GROUP BY clm.complaintId
                  ORDER BY fsm.payDate ASC";
        }
        else
        {
            $query = "SELECT clm.*, lm.locName, wam.areaName, wtm.typeName, wst.subTypeName, um.userName, cum.userName as 'assignee',
                  jmm.problemMedia, jmm.pMediaType, vdm.vendorId
                  FROM complaintlogmaster clm 
                  LEFT JOIN locationmaster lm ON lm.id = clm.locId 
                  LEFT JOIN workareamaster wam ON clm.workAreaId = wam.areaId
                  LEFT JOIN worktypemaster wtm ON clm.workTypeId = wtm.typeId 
                  LEFT JOIN doolally_usersmaster um ON clm.loggedBy = um.userId
                  LEFT JOIN worksubtypemaster wst ON wst.subTypeId = clm.subTypeId
                  LEFT JOIN complaintusermaster cum ON clm.userAssignId = cum.id
                  LEFT JOIN jobmediamaster jmm ON clm.complaintId = jmm.complaintId
                  LEFT JOIN financestatusmaster fsm ON clm.complaintId  = fsm.jobId
                  LEFT JOIN vendordetailsmaster vdm ON clm.workAssignedTo LIKE vdm.vendorName
                  WHERE clm.status = ".LOG_STATUS_IN_PROGRESS." 
                  GROUP BY clm.complaintId
                  ORDER BY fsm.payDate ASC";
        }

        $result = $this->db->query($query)->result_array();

        return $result;
    }

    function getCloseComplaints($locId = '')
    {
        if($locId != '')
        {
            $query = "SELECT clm.*, lm.locName, wam.areaName, wtm.typeName, wst.subTypeName, um.userName,jmm.problemMedia, jmm.solutionMedia, jmm.sMediaType
                  FROM complaintlogmaster clm 
                  LEFT JOIN locationmaster lm ON lm.id = clm.locId 
                  LEFT JOIN workareamaster wam ON clm.workAreaId = wam.areaId
                  LEFT JOIN worktypemaster wtm ON clm.workTypeId = wtm.typeId 
                  LEFT JOIN doolally_usersmaster um ON clm.loggedBy = um.userId
                  LEFT JOIN worksubtypemaster wst ON wst.subTypeId = clm.subTypeId
                  LEFT JOIN jobmediamaster jmm ON clm.complaintId = jmm.complaintId
                  WHERE clm.locId=".$locId." AND clm.status IN (".LOG_STATUS_PARTIAL_CLOSE.", ".LOG_STATUS_CLOSED.") 
                  ORDER BY clm.lastUpdateDT DESC";
        }
        else
        {
            $query = "SELECT clm.*, lm.locName, wam.areaName, wtm.typeName, wst.subTypeName, um.userName, jmm.solutionMedia, jmm.sMediaType
                  FROM complaintlogmaster clm 
                  LEFT JOIN locationmaster lm ON lm.id = clm.locId 
                  LEFT JOIN workareamaster wam ON clm.workAreaId = wam.areaId
                  LEFT JOIN worktypemaster wtm ON clm.workTypeId = wtm.typeId 
                  LEFT JOIN doolally_usersmaster um ON clm.loggedBy = um.userId
                  LEFT JOIN worksubtypemaster wst ON wst.subTypeId = clm.subTypeId
                  LEFT JOIN jobmediamaster jmm ON clm.complaintId = jmm.complaintId
                  WHERE clm.status IN (".LOG_STATUS_PARTIAL_CLOSE.", ".LOG_STATUS_CLOSED.") 
                  ORDER BY clm.lastUpdateDT DESC";
        }

        $result = $this->db->query($query)->result_array();

        return $result;
    }

    function getPostponeComplaints($locId = '')
    {
        if($locId != '')
        {
            $query = "SELECT clm.*, lm.locName, wam.areaName, wtm.typeName, wst.subTypeName, um.userName, cum.userName as 'assignee',
            jmm.problemMedia, jmm.pMediaType
                  FROM complaintlogmaster clm 
                  LEFT JOIN locationmaster lm ON lm.id = clm.locId 
                  LEFT JOIN workareamaster wam ON clm.workAreaId = wam.areaId
                  LEFT JOIN worktypemaster wtm ON clm.workTypeId = wtm.typeId 
                  LEFT JOIN doolally_usersmaster um ON clm.loggedBy = um.userId
                  LEFT JOIN worksubtypemaster wst ON wst.subTypeId = clm.subTypeId
                  LEFT JOIN complaintusermaster cum ON clm.userAssignId = cum.id
                  LEFT JOIN jobmediamaster jmm ON clm.complaintId = jmm.complaintId
                  WHERE clm.locId=".$locId." AND clm.status = ".LOG_STATUS_POSTPONE." 
                  ORDER BY clm.lastUpdateDT DESC";
        }
        else
        {
            $query = "SELECT clm.*, lm.locName, wam.areaName, wtm.typeName, wst.subTypeName, um.userName, cum.userName as 'assignee',
                  jmm.problemMedia, jmm.pMediaType
                  FROM complaintlogmaster clm 
                  LEFT JOIN locationmaster lm ON lm.id = clm.locId 
                  LEFT JOIN workareamaster wam ON clm.workAreaId = wam.areaId
                  LEFT JOIN worktypemaster wtm ON clm.workTypeId = wtm.typeId 
                  LEFT JOIN doolally_usersmaster um ON clm.loggedBy = um.userId
                  LEFT JOIN worksubtypemaster wst ON wst.subTypeId = clm.subTypeId
                  LEFT JOIN complaintusermaster cum ON clm.userAssignId = cum.id
                  LEFT JOIN jobmediamaster jmm ON clm.complaintId = jmm.complaintId
                  WHERE clm.status = ".LOG_STATUS_POSTPONE." 
                  ORDER BY clm.lastUpdateDT DESC";
        }

        $result = $this->db->query($query)->result_array();

        return $result;
    }

    function getComplaintById($compId)
    {
        $query = "SELECT clm.*, lm.locName, wam.areaName, wtm.typeName, wst.subTypeName, um.userName,cum.userName as 'assignee',vdm.*
                  FROM complaintlogmaster clm 
                  LEFT JOIN locationmaster lm ON lm.id = clm.locId 
                  LEFT JOIN workareamaster wam ON clm.workAreaId = wam.areaId
                  LEFT JOIN worktypemaster wtm ON clm.workTypeId = wtm.typeId 
                  LEFT JOIN doolally_usersmaster um ON clm.loggedBy = um.userId
                  LEFT JOIN worksubtypemaster wst ON wst.subTypeId = clm.subTypeId
                  LEFT JOIN complaintusermaster cum ON clm.userAssignId = cum.id
                  LEFT JOIN vendordetailsmaster vdm ON clm.workAssignedTo LIKE vdm.vendorName
                  WHERE complaintId = ".$compId;

        $result = $this->db->query($query)->row_array();
        return $result;
    }

    function updateComplaint($details,$compId)
    {
        $this->db->where('complaintId',$compId);
        $this->db->update('complaintlogmaster',$details);

        return true;
    }

    function getLastLogId()
    {
        $query = "SELECT complaintId FROM complaintlogmaster order by complaintId DESC LIMIT 1";

        $result = $this->db->query($query)->row_array();

        return $result;
    }

    function getWorkAreas()
    {
        $query = "SELECT * FROM workareamaster";
        $result = $this->db->query($query)->result_array();
        return $result;
    }

    function getWorkTypes()
    {
        $this->db->simple_query('SET SESSION group_concat_max_len=1000000');
        $query = "SELECT wtm.*, GROUP_CONCAT(wst.subTypeName SEPARATOR ',') as subTypes 
                FROM worktypemaster wtm 
                LEFT JOIN worksubtypemaster wst ON wtm.typeId = wst.typeId  GROUP BY wtm.typeId";

        $result = $this->db->query($query)->result_array();
        return $result;
    }

    function getAssignees()
    {
        $query = "SELECT * FROM complaintusermaster WHERE ifActive = 1";
        $result = $this->db->query($query)->result_array();
        return $result;
    }
    function saveAssigneeName($details)
    {
        $this->db->insert('complaintusermaster',$details);
        return true;
    }

    function getSubTypes($typeId)
    {
        $query = "SELECT * FROM worksubtypemaster WHERE typeId = ".$typeId;
        $result = $this->db->query($query)->result_array();

        return $result;
    }

    function saveWorkArea($details)
    {
        $this->db->insert('workareamaster',$details);
        return true;
    }
    function saveWorkType($details)
    {
        $this->db->insert('worktypemaster',$details);
        $insertId = $this->db->insert_id();
        return $insertId;
    }
    function saveSubWorkTypes($subDetails)
    {
        $this->db->insert_batch('worksubtypemaster',$subDetails);
        return true;
    }

    public function saveComplaintRecord($post)
    {
        $this->db->insert('complaintlogmaster', $post);
        $insertId = $this->db->insert_id();
        return $insertId;
    }
    public function saveComplaintMedia($post)
    {
        $this->db->insert('jobmediamaster', $post);
        return true;
    }
    function updateComplaintMedia($post,$jobId)
    {
        $this->db->where('complaintId',$jobId);
        $this->db->update('jobmediamaster', $post);
        return true;
    }

    function saveBudget($details)
    {
        $this->db->insert('financestatusmaster',$details);
        return true;
    }
    function saveBudgetBatch($subDetails)
    {
        $this->db->insert_batch('financestatusmaster',$subDetails);
        return true;
    }

    function getBudgets($compId)
    {
        $query= "SELECT * FROM financestatusmaster WHERE jobId=".$compId;

        $result=$this->db->query($query)->result_array();
        return $result;
    }
    function getOnlyOpenJobs()
    {
        $query = "SELECT clm.*,lm.locName FROM complaintlogmaster clm
                  LEFT JOIN locationmaster lm ON lm.id = clm.locId
                  WHERE status = ".LOG_STATUS_OPEN;
        $result = $this->db->query($query)->result_array();
        return $result;
    }

    function getOnlyClosedJobs()
    {
        $query = "SELECT clm.*,lm.locName FROM complaintlogmaster clm
                  LEFT JOIN locationmaster lm ON lm.id = clm.locId
                  WHERE status = ".LOG_STATUS_CLOSED." AND DATE(clm.lastUpdateDT) = (CURRENT_DATE() - INTERVAL 1 DAY)";
        $result = $this->db->query($query)->result_array();
        return $result;
    }

    function getOnlyBudgetJobs()
    {
        $query = "SELECT clm.*,lm.locName FROM complaintlogmaster clm
                  LEFT JOIN locationmaster lm ON lm.id = clm.locId
                  WHERE status = ".LOG_STATUS_PENDING_BUDGET_APPROVAL;
        $result = $this->db->query($query)->result_array();
        return $result;
    }

    function updateFinRecord($details,$fid)
    {
        $this->db->where('fid',$fid);
        $this->db->update('financestatusmaster',$details);
        return true;
    }

    function setCompAddTrue()
    {
        $details = array(
            'compAdded' => 1
        );
        $this->db->update('compaddedmaster',$details);
        return true;
    }

    function setCompAddFalse()
    {
        $details = array(
            'compAdded' => 0
        );
        $this->db->update('compaddedmaster',$details);
        return true;
    }

    function getCompUpdate()
    {
        $query = "SELECT compAdded FROM compaddedmaster";
        $result = $this->db->query($query)->row_array();

        return $result;
    }

    function getAllVendor()
    {
        $query = "SELECT * FROM vendordetailsmaster ";

        $result = $this->db->query($query)->result_array();
        return $result;
    }

    function saveNewVendor($details)
    {
        $this->db->insert('vendordetailsmaster',$details);
        return true;
    }
    function getAllCosts()
    {
        $query = "SELECT approxCost, actualCost, optionalTax FROM complaintlogmaster WHERE status IN (".LOG_STATUS_PARTIAL_CLOSE.",".LOG_STATUS_CLOSED.")";
        $result = $this->db->query($query)->result_array();
        return $result;
    }
    function getVendorById($venId)
    {
        $query = "SELECT * FROM vendordetailsmaster WHERE vendorId = ".$venId;

        $result = $this->db->query($query)->row_array();
        return $result;
    }
    function getWorkAreaById($areaId)
    {
        $query = "SELECT * FROM workareamaster WHERE areaId = ".$areaId;
        $result = $this->db->query($query)->row_array();
        return $result;
    }
    function updateWorkArea($details,$areaId)
    {
        $this->db->where('areaId',$areaId);
        $this->db->update('workareamaster',$details);
        return true;
    }
    function getWorkTypeById($typeId)
    {
        $query = "SELECT * FROM worktypemaster WHERE typeId = ".$typeId;
        $result = $this->db->query($query)->row_array();

        return $result;
    }
    function updateWorkType($details,$typeId)
    {
        $this->db->where('typeId',$typeId);
        $this->db->update('worktypemaster',$details);
        return true;
    }
    function updateSubWorkTypes($details,$subTypeId)
    {
        $this->db->where('subTypeId',$subTypeId);
        $this->db->update('worksubtypemaster',$details);
        return true;
    }

    function saveJobStamp($details)
    {
        $this->db->insert('complaintstatusstamp',$details);
        return true;
    }
    function showTotTapAmt()
    {
        $query = "SELECT lm.locName,SUM(fsm.payAmount) as 'locAmount'
                FROM `complaintlogmaster` clm
                LEFT JOIN locationmaster lm ON clm.locId = lm.id
                LEFT JOIN financestatusmaster fsm ON clm.complaintId = fsm.jobId
                WHERE clm.approxCost <= 5000 AND clm.status IN(".LOG_STATUS_IN_PROGRESS.") AND fsm.receiveDate IS NULL
                GROUP BY lm.locName";

        $result = $this->db->query($query)->result_array();
        return $result;
    }
    function showOpenTotAmt()
    {
        $query = "SELECT lm.locName,SUM(clm.approxCost) as 'locAmount'
                FROM `complaintlogmaster` clm
                LEFT JOIN locationmaster lm ON clm.locId = lm.id
                WHERE clm.status IN(".LOG_STATUS_PENDING_BUDGET_APPROVAL.")
                GROUP BY lm.locName";

        $result = $this->db->query($query)->result_array();
        return $result;
    }
    function filterPayment($startDate, $endDate)
    {
        $query = "SELECT lm.locName,fsm.jobId,fsm.payAmount,fsm.payType, DATE_FORMAT(fsm.payDate, '%d %b %Y %h:%i %p') AS 'payDate' 
                FROM financestatusmaster fsm
                LEFT JOIN complaintlogmaster clm ON fsm.jobId = clm.complaintId
                LEFT JOIN locationmaster lm ON clm.locId = lm.id
                WHERE DATE(fsm.payDate) >= '".$startDate."' AND DATE(fsm.payDate) <= '".$endDate."' AND fsm.receiveDate IS NULL";
        $result = $this->db->query($query)->result_array();
        return $result;
    }

    function getTotAmtByTap()
    {
        $query = "SELECT lm.locName, sum(clm.approxCost) as 'locAmount' 
                  FROM `complaintlogmaster` clm 
                  LEFT JOIN locationmaster lm ON clm.locId = lm.id 
                  GROUP BY lm.id";

        $result = $this->db->query($query)->result_array();
        return $result;
    }
    function getTotClosedAmtByTap()
    {
        $query = "SELECT lm.locName, sum(clm.approxCost) as 'locAmount' 
                  FROM `complaintlogmaster` clm 
                  LEFT JOIN locationmaster lm ON clm.locId = lm.id 
                  WHERE clm.status = ".LOG_STATUS_CLOSED."
                  GROUP BY lm.id";

        $result = $this->db->query($query)->result_array();
        return $result;
    }
}
