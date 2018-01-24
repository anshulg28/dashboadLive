<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Users
 * @property Users_Model $users_model
 * @property Maintenance_Model $maintenance_model
 * @property Locations_Model $locations_model
 * @property Login_Model $login_model
 * @property Dashboard_Model $dashboard_model
*/

class Maintenance extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('users_model');
		$this->load->model('maintenance_model');
		$this->load->model('locations_model');
        $this->load->model('login_model');
        $this->load->model('dashboard_model');
        ini_set('memory_limit', "256M");
        ini_set('upload_max_filesize', "50M");
	}
	public function index()
	{
        $this->load->model('mugclub_model');
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }

        if(isSessionVariableSet($this->userId))
        {
            $rols = $this->login_model->getUserRoles($this->userId);
            $data['userModules'] = explode(',',$rols['modulesAssigned']);

        }

        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);

        if($this->userType == SERVER_USER)
        {
            if(!isset($this->currentLocation) || isSessionVariableSet($this->currentLocation) === false)
            {
                $gotLocId = $this->mugclub_model->fetchLocIdByMob($this->userId);
                if(isset($gotLocId['id']))
                {
                    $this->generalfunction_library->setSessionVariable("currentLocation",$gotLocId['id']);
                    $this->currentLocation = $gotLocId['id'];
                }
                else
                {
                    redirect(base_url().'location-select');
                }
            }

            $data['openComplaints'] = $this->maintenance_model->getOpenComplaints($this->currentLocation);
            $data['progressComplaints'] = $this->maintenance_model->getProgressComplaints($this->currentLocation);
            $data['closeComplaints'] = $this->maintenance_model->getCloseComplaints($this->currentLocation);
            $data['postponeComplaints'] = $this->maintenance_model->getPostponeComplaints($this->currentLocation);
            $this->load->view('maintenance/ServerActionLogView',$data);
        }
        else
        {
            $this->load->view('maintenance/MainView', $data);
        }
	}

	public function logbook()
    {
        $data = array();
        $this->load->model('mugclub_model');
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }

        if($this->userType == SERVER_USER)
        {
            if(!isset($this->currentLocation) || isSessionVariableSet($this->currentLocation) === false)
            {
                $gotLocId = $this->mugclub_model->fetchLocIdByMob($this->userId);
                if(isset($gotLocId['id']))
                {
                    $this->generalfunction_library->setSessionVariable("currentLocation",$gotLocId['id']);
                    $this->currentLocation = $gotLocId['id'];
                }
                else
                {
                    redirect(base_url().'location-select');
                }
            }
        }
        else
        {
            $data['taprooms'] = $this->locations_model->getAllActiveLocations();
        }

        $data['workAreas'] = $this->maintenance_model->getAllWorkAreas();
        $data['workTypes'] = $this->maintenance_model->getAllWorkTypes();

        $logId = $this->maintenance_model->getLastLogId();
        if(!isset($logId) && !myIsArray($logId))
        {
            $data['logId'] = 1;
        }
        else
        {
            $data['logId'] = ((int)$logId['complaintId'] + 1);
        }

        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);

        $this->load->view('maintenance/LogBookView', $data);
    }

    public function saveComplaint()
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            $data['status'] = false;
            $data['errorMsg'] = 'User Session Expired!';
            $data['pageUrl'] = base_url();
            echo json_encode($data);
            return false;
        }

        $post = $this->input->post();

        if(!isSessionVariableSet($this->currentLocation))
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Location Not Set';
            echo json_encode($data);
            return false;
        }
        $media = '';
        if(isset($post['problemMedia']))
        {
            $media = $post['problemMedia'];
            unset($post['problemMedia']);
        }
        /*if($media == '')
        {
            $data['errorMsg'] = 'Error: No Job Media Provided!';
            $data['status'] = false;
            echo json_encode($data);
            return false;
        }*/
        if(!isset($post['locId']))
        {
            $post['locId'] = $this->currentLocation;
        }
        $post['loggedBy'] = $this->userId;
        $post['loggedDT'] = date('Y-m-d H:i:s');
        $post['status'] = LOG_STATUS_OPEN;
        $post['lastUpdateDT'] = date('Y-m-d H:i:s');

        $compId = $this->maintenance_model->saveComplaintRecord($post);

        if($media != '')
        {
            $mArr = explode('.',$media);
            $mediaType = 1;
            if(myInArray($mArr[count($mArr)-1],array('jpg','jpeg','png','gif')))
            {
                $mediaType = 1;
            }
            else
            {
                $mediaType = 2;
            }
            $details = array(
                'complaintId' => $compId,
                'problemMedia' => $media,
                'pMediaType' => $mediaType,
                'solutionMedia' => null,
                'sMediaType' => null,
                'insertedDT' => date('Y-m-d H:i:s')
            );
            $this->maintenance_model->saveComplaintMedia($details);
        }

        //Gettting Maintenance Manager Email
        $mainManager = $this->login_model->getMaintenanceManager();

        $locDetails = $this->locations_model->getLocationDetailsById($post['locId']);

        if($this->userType != MAINTENANCE_MANAGER)
        {
            $mailDetails = array(
                'toMail' => $mainManager['emailId'],
                'compId' => $compId,
                'location' => $locDetails['locData'][0]['locName']
            );

            $this->sendemail_library->sendCompOpenMail($mailDetails);
        }

        $this->maintenance_model->setCompAddTrue();

        $data['status'] = true;
        echo json_encode($data);

    }

    public function actionLog()
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }

        $data['openComplaints'] = $this->maintenance_model->getOpenComplaints();
        $progressComps = $this->maintenance_model->getProgressComplaints();
        if(isset($progressComps) && myIsArray($progressComps))
        {
            foreach($progressComps as $key => $row)
            {
                $budget = $this->maintenance_model->getBudgets($row['complaintId']);
                $data['progressComplaints'][$key]['jobInfo'] = $row;
                if(isset($budget) && myIsArray($budget))
                {
                    $data['progressComplaints'][$key]['budgetInfo'] = $budget;
                }
            }
        }
        else
        {
            $data['progressComplaints'] = array();
        }

        //$data['progressComplaints']
        $data['closeComplaints'] = $this->maintenance_model->getCloseComplaints();
        $data['postponeComplaints'] = $this->maintenance_model->getPostponeComplaints();
        /*$data['mainBalance'] = $this->calcMainBalance();*/
        /*$data['tapsTotal'] = $this->maintenance_model->showTotTapAmt();*/
        $data['openTot'] = $this->maintenance_model->showOpenTotAmt();
        $data['allTotAmt'] = $this->maintenance_model->getTotAmtByTap();
        $data['allClosedTotAmt'] = $this->maintenance_model->getTotClosedAmtByTap();

        //getting monthly cost
        $monthlyTotAmt = $this->maintenance_model->getTotAmtByTap(true);
        $monthlyClosedTotAmt = $this->maintenance_model->getTotClosedAmtByTap(true);
        $allLocs = $this->locations_model->getAllActiveLocations();
        $monthlyFinal = array();
        foreach($allLocs as $key => $row)
        {
            $monthlyFinal[$key]['locName'] = $row['locName'];
            $monthlyFinal[$key]['totAmt'] = 0;
            $monthlyFinal[$key]['totClosedAmt'] = 0;
            foreach($monthlyTotAmt as $monKey => $monRow)
            {
                if($monRow['locId'] == $row['id'])
                {
                    $monthlyFinal[$key]['totAmt'] = $monRow['locAmount'];
                    break;
                }
            }
            foreach($monthlyClosedTotAmt as $monKey => $monRow)
            {
                if($monRow['locId'] == $row['id'])
                {
                    $monthlyFinal[$key]['totClosedAmt'] = $monRow['locAmount'];
                    break;
                }
            }
        }
        $data['monthlyFinal'] = $monthlyFinal;
        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);

        $this->load->view('maintenance/ActionLogView', $data);
    }

    public function workArea()
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }

        $data['workAreas'] = $this->maintenance_model->getWorkAreas();

        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);

        $this->load->view('maintenance/WorkAreaView', $data);
    }

    public function addWorkArea()
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }

        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);

        $this->load->view('maintenance/WorkAreaAddView', $data);
    }

    public function editWorkArea($areaId)
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }

        $data['areaId'] = $areaId;
        $data['areaData'] = $this->maintenance_model->getWorkAreaById($areaId);

        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);

        $this->load->view('maintenance/WorkAreaEditView', $data);
    }

    public function saveWorkArea()
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $post = $this->input->post();

        if(isset($post['areaName']) && isStringSet($post['areaName']))
        {
            $post['ifActive'] = 1;
            $post['createdDT'] = date('Y-m-d H:i:s');
            $post['addedBy'] = $this->userId;

            $this->maintenance_model->saveWorkArea($post);
            redirect(base_url().'maintenance/workArea');
        }
    }

    public function updateWorkArea()
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $post = $this->input->post();

        if(isset($post['areaName']) && isStringSet($post['areaName']))
        {
            $areaId = $post['areaId'];
            unset($post['areaId']);

            $this->maintenance_model->updateWorkArea($post,$areaId);
            redirect(base_url().'maintenance/workArea');
        }
    }

    public function workType()
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }

        $data['workTypes'] = $this->maintenance_model->getWorkTypes();

        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);

        $this->load->view('maintenance/WorkTypeView', $data);
    }

    public function addWorkType()
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }

        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);

        $this->load->view('maintenance/WorkTypeAddView', $data);
    }

    public function editWorkType($typeId)
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }

        $data = array();

        $data['typeId'] = $typeId;
        $data['typeData'] = $this->maintenance_model->getWorkTypeById($typeId);

        $data['subTypeData'] = $this->maintenance_model->getSubTypes($typeId);

        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);

        $this->load->view('maintenance/WorkTypeEditView', $data);
    }

    public function updateWorkType()
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $post = $this->input->post();

        $mainDetails = array();
        $subDetails = array();
        $subAdd = array();
        if(isset($post['typeName']) && isStringSet($post['typeName']))
        {
            $typeId = $post['typeId'];
            $mainDetails['typeName'] = $post['typeName'];

            $this->maintenance_model->updateWorkType($mainDetails,$typeId);
            if(isset($post['subAdd']) && $post['subAdd'] == '1')
            {
                if(isset($post['subTypeName']) && myIsArray($post['subTypeName']))
                {
                    for($i=0;$i<count($post['subTypeName']);$i++)
                    {
                        if(isset($post['subTypeId'][$i]))
                        {
                            $subDetails = array(
                                'subTypeName' => $post['subTypeName'][$i]
                            );
                            $this->maintenance_model->updateSubWorkTypes($subDetails,$post['subTypeId'][$i]);
                        }
                        else
                        {
                            if($post['subTypeName'][$i] != '')
                            {
                                $subAdd[] = array(
                                    'typeId' => $typeId,
                                    'subTypeName' => $post['subTypeName'][$i],
                                    'ifActive' => 1,
                                    'createdDT' => date('Y-m-d H:i:s')
                                );
                            }
                        }
                    }
                    if(myIsArray($subAdd))
                    {
                        $this->maintenance_model->saveSubWorkTypes($subAdd);
                    }
                }
            }

        }
        redirect(base_url().'maintenance/workType');

    }

    public function saveWorkType()
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $post = $this->input->post();

        $mainDetails = array();
        $subDetails = array();
        if(isset($post['typeName']) && isStringSet($post['typeName']))
        {
            $mainDetails['typeName'] = $post['typeName'];
            $mainDetails['ifActive'] = 1;
            $mainDetails['createdDT'] = date('Y-m-d H:i:s');
            $mainDetails['addedBy'] = $this->userId;

            $TypeId = $this->maintenance_model->saveWorkType($mainDetails);
            if(isset($post['subAdd']) && $post['subAdd'] == '1')
            {
                if(isset($post['subTypeName']) && myIsArray($post['subTypeName']))
                {
                    foreach($post['subTypeName'] as $key)
                    {
                        if(isset($key) && $key != '')
                        {
                            $subDetails[] = array(
                                'typeId' => $TypeId,
                                'subTypeName' => $key,
                                'ifActive' => 1,
                                'createdDT' => date('Y-m-d H:i:s')
                            );
                        }
                    }
                    $this->maintenance_model->saveSubWorkTypes($subDetails);
                }
            }

        }
        redirect(base_url().'maintenance/workType');

    }

    public function assignees()
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }

        $data['assignees'] = $this->maintenance_model->getAssignees();

        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);

        $this->load->view('maintenance/AssigneesView', $data);
    }

    public function addAssignee()
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }

        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);

        $this->load->view('maintenance/AssigneeAddView', $data);
    }


    public function saveAssignee()
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $post = $this->input->post();

        if(isset($post['userName']) && $post['userName'] != '')
        {
            $post['insertedDT'] = date('Y-m-d H:i:s');
            $post['ifActive'] = 1;
            $this->maintenance_model->saveAssigneeName($post);
        }
        redirect(base_url().'maintenance/assignees');
    }
    public function getSubType($typeId)
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $data = array();
        $subTy= $this->maintenance_model->getSubTypes($typeId);
        if(isset($subTy) && myIsArray($subTy))
        {
            $data['status'] = true;
            $data['subTypes'] = $subTy;
        }
        else
        {
            $data['status'] = false;
        }
        echo json_encode($data);
    }

    public function getComplaintInfo($compId)
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $data = array();

        $compDetails = $this->maintenance_model->getComplaintById($compId);
        $data['userList'] = $this->maintenance_model->getAssignees();

        $data['compInfo'] = $compDetails;

        echo json_encode($data);
    }

    public function updateOpenComplaint()
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $data = array();

        $post = $this->input->post();

        if(isset($post['complaintId']) && $post['complaintId'] != '')
        {
            $compId = $post['complaintId'];
            unset($post['complaintId']);
            if($post['userAssignId'] == 'other')
            {
                unset($post['userAssignId']);
            }

            $isVendorSaved = false;
            if(isset($post['workAssignedTo']) && $post['workAssignedTo'] == '' && $post['venName'] != '')
            {
                $isVendorSaved = true;
                if(!isset($post['venAddress']))
                {
                    $post['venAddress'] = null;
                }
                $details = array(
                    'vendorName' => $post['venName'],
                    'address' => $post['venAddress'],
                    'panCard' => $post['venPan'],
                    'bankCheque' => $post['bankCheque'],
                    'insertedDT' => date('Y-m-d H:i:s')
                );
                $this->maintenance_model->saveNewVendor($details);
                $post['workAssignedTo'] = $post['venName'];
                unset($post['venName'],$post['venAddress'],$post['venPan'],$post['bankCheque']);
            }

            if(!$isVendorSaved)
            {
                if(isset($post['venName']) && $post['venName'] == '')
                {
                    if(!isset($post['venAddress']))
                    {
                        $post['venAddress'] = null;
                    }
                    unset($post['venName'],$post['venAddress'],$post['venPan'],$post['bankCheque']);
                }
                else
                {
                    if(!isset($post['venAddress']))
                    {
                        $post['venAddress'] = null;
                    }
                    $details = array(
                        'vendorName' => $post['venName'],
                        'address' => $post['venAddress'],
                        'panCard' => $post['venPan'],
                        'bankCheque' => $post['bankCheque'],
                        'insertedDT' => date('Y-m-d H:i:s')
                    );
                    $this->maintenance_model->saveNewVendor($details);
                    $post['workAssignedTo'] = $post['venName'];
                    unset($post['venName'],$post['venAddress'],$post['venPan'],$post['bankCheque']);
                }
            }

            if((double)$post['approxCost'] <= 15000)
            {
                $comDetails = $this->maintenance_model->getComplaintById($compId);
                if(isset($comDetails) && myIsArray($comDetails))
                {
                    $locBal = $this->maintenance_model->getBalanceByLoc($comDetails['locId']);
                    if((int)$locBal['jobCostCap'] <= 15000)
                    {
                        $totBal = (int)$locBal['jobCostCap'] + (int)$post['approxCost'];
                        if($totBal<=15000)
                        {
                            $post['status']  = LOG_STATUS_IN_PROGRESS;
                            $details = array(
                                'jobId' => $compId,
                                'payAmount' => $post['approxCost'],
                                'payDate' => date('Y-m-d H:i:s'),
                                'payType' => 'Happay Cash',
                                'addedDT' => date('Y-m-d H:i:s')
                            );

                            $this->maintenance_model->saveBudget($details);
                        }
                        else
                        {
                            $post['status'] = LOG_STATUS_PENDING_APPROVAL;
                        }
                    }
                    else
                    {
                        $post['status'] = LOG_STATUS_PENDING_APPROVAL;
                    }
                }
                else
                {
                    $post['status'] = LOG_STATUS_PENDING_APPROVAL;
                }
            }
            /*elseif((double)$post['approxCost'] < 15000 )
            {
                $post['status'] = LOG_STATUS_PENDING_BUDGET_APPROVAL;
            }*/
            elseif((double)$post['approxCost'] > 15000)
            {
                $post['status'] = LOG_STATUS_PENDING_APPROVAL;
            }
            $post['lastUpdateDT'] = date('Y-m-d H:i:s');

            $this->maintenance_model->updateComplaint($post,$compId);

            //status time record
            $details = array(
                'complaintId' => $compId,
                'jobStatus' => $post['status'],
                'lastUpdate' => date('Y-m-d H:i:s')
            );
            $this->maintenance_model->saveJobStamp($details);
            $data['status'] = true;
        }
        else
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Complaint Id is Missing';
        }

        echo json_encode($data);
    }

    public function approveJob($compId)
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        if(isset($compId) && $compId != '')
        {
           $details = array(
               'lastUpdateDT' => date('Y-m-d H:i:s'),
               'status' => LOG_STATUS_PENDING_BUDGET_APPROVAL
           );

           $this->maintenance_model->updateComplaint($details,$compId);
            //status time record
            $details = array(
                'complaintId' => $compId,
                'jobStatus' => LOG_STATUS_PENDING_BUDGET_APPROVAL,
                'lastUpdate' => date('Y-m-d H:i:s')
            );
            $this->maintenance_model->saveJobStamp($details);
        }
        redirect(base_url().'maintenance/actionLog');
    }
    public function declineJob()
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $post = $this->input->post();
        $data= array();

        if(isset($post['compId']) && isset($post['decReason']))
        {
            $details = array(
                'declineReason' => $post['decReason'],
                'status' => LOG_STATUS_DECLINED,
                'lastUpdateDT' => date('Y-m-d H:i:s')
            );
            $this->maintenance_model->updateComplaint($details,$post['compId']);
            //status time record
            $details = array(
                'complaintId' => $post['compId'],
                'jobStatus' => LOG_STATUS_DECLINED,
                'lastUpdate' => date('Y-m-d H:i:s')
            );
            $this->maintenance_model->saveJobStamp($details);
            $data['status'] = true;
        }
        else
        {
            $data['status']= false;
            $data['errorMsg'] = 'All Details Required!';
        }
        echo json_encode($data);
    }

    public function postponeJob()
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $post = $this->input->post();
        $data= array();

        if(isset($post['compId']) && isset($post['postdate']))
        {
            $details = array(
                'postpondDate' => $post['postdate'],
                'status' => LOG_STATUS_POSTPONE,
                'lastUpdateDT' => date('Y-m-d H:i:s')
            );
            $this->maintenance_model->updateComplaint($details,$post['compId']);
            //status time record
            $details = array(
                'complaintId' => $post['compId'],
                'jobStatus' => LOG_STATUS_POSTPONE,
                'lastUpdate' => date('Y-m-d H:i:s')
            );
            $this->maintenance_model->saveJobStamp($details);
            $data['status'] = true;
        }
        else
        {
            $data['status']= false;
            $data['errorMsg'] = 'All Details Required!';
        }
        echo json_encode($data);
    }

    public function saveBudget()
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $post = $this->input->post();
        $data = array();

        if(isset($post['compId']))
        {
            if(isset($post['budDate']))
            {
                $compInfo = $this->maintenance_model->getComplaintById($post['compId']);
                if(isset($compInfo) && myIsArray($compInfo))
                {
                    $details = array(
                        'jobId' => $post['compId'],
                        'payAmount' => $compInfo['approxCost'],
                        'payDate' => date('Y-m-d H:i:s',strtotime($post['budDate'])),
                        'payType' => $post['payType'],
                        'addedDT' => date('Y-m-d H:i:s')
                    );

                    $this->maintenance_model->saveBudget($details);

                    $details = array(
                        'status' => LOG_STATUS_IN_PROGRESS,
                        'lastUpdateDT' => date('Y-m-d H:i:s')
                    );
                    $this->maintenance_model->updateComplaint($details,$post['compId']);
                    if($post['payType'] == 'Happay Cash')
                    {
                        $locDetails = $this->locations_model->getLocationDetailsById($compInfo['locId']);
                        $d = date_create(date('Y-m-d H:i:s',strtotime($post['budDate'])));
                        $numbers = array('91'.$locDetails['locData'][0]['phoneNumber']);

                        $postDetails = array(
                            'apiKey' => TEXTLOCAL_API,
                            'numbers' => implode(',', $numbers),
                            'sender'=> urlencode('DOLALY'),
                            'message' => rawurlencode('Maintenance Job #'.$post['compId'].' happy amount '.$compInfo['approxCost'].
                            ' scheduled on '.date_format($d,DATE_FORMAT_UI))
                        );
                        $smsStatus = $this->curl_library->sendCouponSMS($postDetails);
                        if($smsStatus['status'] == 'failure')
                        {
                            if(isset($smsStatus['warnings']))
                            {
                                $data['errorMsg'] = $smsStatus['warnings'][0]['message'];
                            }
                            else
                            {
                                $data['errorMsg'] = $smsStatus['errors'][0]['message'];
                            }
                        }
                    }
                    //status time record
                    $details = array(
                        'complaintId' => $post['compId'],
                        'jobStatus' => LOG_STATUS_IN_PROGRESS,
                        'lastUpdate' => date('Y-m-d H:i:s')
                    );
                    $this->maintenance_model->saveJobStamp($details);
                    $data['status'] = true;
                }
                else
                {
                    $data['status'] = false;
                    $data['errorMsg'] = 'Complaint Not Found!';
                }
            }
            else
            {
                $details = array();
                $happyData = array();
                $compInfo = $this->maintenance_model->getComplaintById($post['compId']);
                $locDetails = $this->locations_model->getLocationDetailsById($compInfo['locId']);
                for($i=0;$i<count($post['budAmts']);$i++)
                {
                    if($post['budAmts'][$i] != '')
                    {
                        $details[] = array(
                            'jobId' => $post['compId'],
                            'payAmount' => $post['budAmts'][$i],
                            'payDate' => date('Y-m-d H:i:s',strtotime($post['budDates'][$i])),
                            'payType' => $post['payTypes'][$i],
                            'addedDT' => date('Y-m-d H:i:s')
                        );

                        if($post['payTypes'][$i] == 'Happy Cash')
                        {
                            $d = date_create(date('Y-m-d H:i:s',strtotime($post['budDates'][$i])));
                            $numbers = array('91'.$locDetails['locData'][0]['phoneNumber']);

                            $postDetails = array(
                                'apiKey' => TEXTLOCAL_API,
                                'numbers' => implode(',', $numbers),
                                'sender'=> urlencode('DOLALY'),
                                'message' => rawurlencode('Maintenance Job #'.$post['compId'].' happy amount '.$post['budAmts'][$i].
                                    ' scheduled on '.date_format($d,DATE_FORMAT_UI))
                            );
                            $smsStatus = $this->curl_library->sendCouponSMS($postDetails);
                            if($smsStatus['status'] == 'failure')
                            {
                                if(isset($smsStatus['warnings']))
                                {
                                    $data['errorMsg'] = $smsStatus['warnings'][0]['message'];
                                }
                                else
                                {
                                    $data['errorMsg'] = $smsStatus['errors'][0]['message'];
                                }
                            }
                        }
                    }
                }
                $this->maintenance_model->saveBudgetBatch($details);
                $details = array(
                    'status' => LOG_STATUS_IN_PROGRESS,
                    'lastUpdateDT' => date('Y-m-d H:i:s')
                );
                $this->maintenance_model->updateComplaint($details,$post['compId']);

                //status time record
                $details = array(
                    'complaintId' => $post['compId'],
                    'jobStatus' => LOG_STATUS_IN_PROGRESS,
                    'lastUpdate' => date('Y-m-d H:i:s')
                );
                $this->maintenance_model->saveJobStamp($details);
                $data['status'] = true;
            }
        }
        else
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Complaint Id Not set!';
        }

        echo json_encode($data);
    }

    public function partialClose()
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $post = $this->input->post();
        $data = array();

        if(isset($post['compIds']) && $post['compIds'] != '')
        {
            $compIds = explode(',',$post['compIds']);
            foreach($compIds as $key)
            {
                $details = array(
                    'status' => LOG_STATUS_PARTIAL_CLOSE,
                    'lastUpdateDT' => date('Y-m-d H:i:s')
                );
                $this->maintenance_model->updateComplaint($details,$key);
                //status time record
                $details = array(
                    'complaintId' => $key,
                    'jobStatus' => LOG_STATUS_PARTIAL_CLOSE,
                    'lastUpdate' => date('Y-m-d H:i:s')
                );
                $this->maintenance_model->saveJobStamp($details);
            }
            $data['status'] = true;
        }
        else
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Missing Complaint Id(s)';
        }

        echo json_encode($data);
    }

    public function sendJobOtp()
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $post = $this->input->post();
        $data = array();

        if(isset($post['compIds']) && $post['compIds'] != '')
        {
            //$this->currentLocation
            $userInfo = $this->login_model->getUserById($this->userId);
            if(isset($userInfo) && myIsArray($userInfo))
            {
                if(isset($userInfo['mobNum']))
                {
                    $compIds = explode(',',$post['compIds']);
                    $newOtp = mt_rand(1000,99999);
                    foreach($compIds as $key)
                    {
                        $details = array(
                            'jobOtp' => $newOtp
                        );
                        $this->maintenance_model->updateComplaint($details,$key);
                    }
                    $numbers = array('91'.$userInfo['mobNum']);

                    $postDetails = array(
                        'apiKey' => TEXTLOCAL_API,
                        'numbers' => implode(',', $numbers),
                        'sender'=> urlencode('DOLALY'),
                        'message' => rawurlencode($newOtp.' is Your OTP for job(s) completion')
                    );
                    $smsStatus = $this->curl_library->sendCouponSMS($postDetails);
                    if($smsStatus['status'] == 'failure')
                    {
                        if(isset($smsStatus['warnings']))
                        {
                            $data['errorMsg'] = $smsStatus['warnings'][0]['message'];
                        }
                        else
                        {
                            $data['errorMsg'] = $smsStatus['errors'][0]['message'];
                        }
                    }
                    $data['status'] = true;
                }
                else
                {
                    $data['status'] = false;
                    $data['errorMsg'] = 'Missing logged in user mobile number!';
                }
            }
            else
            {
                $data['status'] = false;
                $data['errorMsg'] = 'Logged in user not found!';
            }
        }
        else
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Missing Complaint Id(s)';
        }
        echo json_encode($data);
    }

    public function checkJobOtp()
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $post = $this->input->post();
        $data = array();

        if(isset($post['jobs']) && $post['jobs'] != '')
        {
            if(isset($post['otp']) && $post['otp'] != '')
            {
                $compIds = explode(',',$post['jobs']);
                $ifAnyError = false;
                foreach($compIds as $key)
                {
                    $compInfo = $this->maintenance_model->getComplaintById($key);
                    if($compInfo['jobOtp'] == $post['otp'])
                    {
                        $details = array(
                            'status' => LOG_STATUS_CLOSED,
                            'lastUpdateDT' => date('Y-m-d H:i:s')
                        );
                        $this->maintenance_model->updateComplaint($details,$key);
                        //status time record
                        $details = array(
                            'complaintId' => $key,
                            'jobStatus' => LOG_STATUS_CLOSED,
                            'lastUpdate' => date('Y-m-d H:i:s')
                        );
                        $this->maintenance_model->saveJobStamp($details);
                    }
                    else
                    {
                        $ifAnyError = true;
                        break;
                    }
                }
                if($ifAnyError)
                {
                    $data['status'] = false;
                    $data['errorMsg'] = "Invalid OTP!";
                }
                else
                {
                    $data['status'] = true;
                }
            }
            else
            {
                $data['status'] = false;
                $data['errorMsg'] = 'OTP Not Provided!';
            }
        }
        else
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Missing Job Id(s)';
        }

        echo json_encode($data);
    }

    public function uploadJobFiles()
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Session Timeout, Please Login Again!';
            echo json_encode($data);
            return false;
        }
        $attchmentArr = '';
        $this->load->library('upload');
        if(isset($_FILES))
        {
            if($_FILES['attachment']['error'] != 1)
            {
                $filePath = $_FILES['attachment']['name'];
                $fileName = preg_replace('/\(|\)/','',$filePath);
                $fileName = preg_replace('/[^a-zA-Z0-9.]\.]/', '', $fileName);
                $fileName = str_replace(' ','_',$fileName);
                $fileName = time().'_'.$fileName;
                $config = array();
                $config['upload_path'] = '../dashboad/'.JOB_MEDIA_PATH; // FOOD_PATH_THUMB; //'uploads/food/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg|3gp|avi|mp4|flv|mov|mpeg';
                $config['max_size']      = '0';
                $config['overwrite']     = TRUE;
                $config['file_name']     = $fileName;

                $this->upload->initialize($config);
                if(!$this->upload->do_upload('attachment'))
                {
                    log_message('error','Job Upload: '.$this->upload->display_errors());
                    $data['status'] = false;
                    $data['errorMsg'] = $this->upload->display_errors();
                    echo json_encode($data);
                    return false;
                }
                else
                {
                    $upload_data = $this->upload->data();
                    if(myInArray($upload_data['file_ext'],array('.gif','.jpg','.png','.jpeg')))
                    {
                        $attchmentArr= $this->image_thumb($upload_data['file_path'],$upload_data['file_name']);
                        if($attchmentArr == 'error')
                        {
                            $data['status'] = false;
                            $data['errorMsg'] = 'Error in resizing image!';
                            echo json_encode($data);
                            return false;
                        }
                        else
                        {
                            echo $attchmentArr;
                        }
                    }
                    else
                    {
                        echo $upload_data['file_name'];
                    }
                }
            }
            else
            {
                echo 'Some Error Occurred!';
            }
        }
        else
        {
            $data['status'] = false;
            $data['errorMsg'] = 'No Image/Video File Received!';
            echo json_encode($data);
            return false;
        }
    }
    function image_thumb( $image_path, $img_name)
    {
        $image_thumb = $image_path.$img_name;

        // LOAD LIBRARY
        $this->load->library( 'image_lib' );

        // CONFIGURE IMAGE LIBRARY
        $config['image_library']    = 'gd2';
        $config['source_image']     = $image_path.$img_name;
        $config['new_image']        = $image_thumb;
        $config['quality']          = 90;
        $config['maintain_ratio']   = TRUE;
        $config['height']           = 480;
        $config['width']            = 690;

        $this->image_lib->initialize( $config );
        if(!$this->image_lib->resize())
        {
            $this->image_lib->clear();
            log_message('error',$image_path.': '.$this->image_lib->display_errors());
            return 'error';
        }
        else
        {
            $this->image_lib->clear();
            return $img_name;
        }
    }

    public function uploadSolJobFiles()
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Session Timeout, Please Login Again!';
            echo json_encode($data);
            return false;
        }
        $attchmentArr = '';
        $this->load->library('upload');
        if(isset($_FILES))
        {
            if($_FILES['attachment']['error'] != 1)
            {
                $filePath = $_FILES['attachment']['name'];
                $fileName = preg_replace('/\(|\)/','',$filePath);
                $fileName = preg_replace('/[^a-zA-Z0-9.]\.]/', '', $fileName);
                $fileName = str_replace(' ','_',$fileName);
                $fileName = time().'_'.$fileName;
                $config = array();
                $config['upload_path'] = '../dashboad/'.JOB_MEDIA_PATH; // FOOD_PATH_THUMB; //'uploads/food/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg|3gp|avi|mp4|flv|mov|mpeg';
                $config['max_size']      = '0';
                $config['overwrite']     = TRUE;
                $config['file_name']     = $fileName;

                $this->upload->initialize($config);
                if(!$this->upload->do_upload('attachment'))
                {
                    log_message('error','Job Upload: '.$this->upload->display_errors());
                    $data['status'] = false;
                    $data['errorMsg'] = $this->upload->display_errors();
                    echo json_encode($data);
                    return false;
                }
                else
                {
                    $upload_data = $this->upload->data();
                    if(myInArray($upload_data['file_ext'],array('.gif','.jpg','.png','.jpeg')))
                    {
                        $attchmentArr= $this->image_thumb($upload_data['file_path'],$upload_data['file_name']);
                        if($attchmentArr == 'error')
                        {
                            $data['status'] = false;
                            $data['errorMsg'] = 'Error in resizing image!';
                            echo json_encode($data);
                            return false;
                        }
                        else
                        {
                            echo $attchmentArr;
                        }
                    }
                    else
                    {
                        echo $upload_data['file_name'];
                    }
                }
            }
            else
            {
                echo 'Some Error Occurred!';
            }
        }
        else
        {
            $data['status'] = false;
            $data['errorMsg'] = 'No Image/Video File Received!';
            echo json_encode($data);
            return false;
        }
    }

    public function uploadInvoiceFiles()
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Session Timeout, Please Login Again!';
            echo json_encode($data);
            return false;
        }
        $attchmentArr = '';
        $this->load->library('upload');
        if(isset($_FILES))
        {
            if($_FILES['attachment']['error'] != 1)
            {
                $filePath = $_FILES['attachment']['name'];
                $fileName = preg_replace('/\(|\)/','',$filePath);
                $fileName = preg_replace('/[^a-zA-Z0-9.]\.]/', '', $fileName);
                $fileName = str_replace(' ','_',$fileName);
                $fileName = time().'_'.$fileName;
                $config = array();
                $config['upload_path'] = '../dashboad/'.JOB_MEDIA_PATH; // FOOD_PATH_THUMB; //'uploads/food/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size']      = '0';
                $config['overwrite']     = TRUE;
                $config['file_name']     = $fileName;

                $this->upload->initialize($config);
                if(!$this->upload->do_upload('attachment'))
                {
                    log_message('error','invoice Upload: '.$this->upload->display_errors());
                    $data['status'] = false;
                    $data['errorMsg'] = $this->upload->display_errors();
                    echo json_encode($data);
                    return false;
                }
                else
                {
                    $upload_data = $this->upload->data();
                    $attchmentArr= $this->image_thumb($upload_data['file_path'],$upload_data['file_name']);
                    if($attchmentArr == 'error')
                    {
                        $data['status'] = false;
                        $data['errorMsg'] = 'Error in resizing image!';
                        echo json_encode($data);
                        return false;
                    }
                    else
                    {
                        echo $attchmentArr;
                    }
                }
            }
            else
            {
                echo 'Some Error Occurred!';
            }
        }
        else
        {
            $data['status'] = false;
            $data['errorMsg'] = 'No Image File Received!';
            echo json_encode($data);
            return false;
        }
    }

    public function saveSolMedia()
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Session Timeout, Please Login Again!';
            echo json_encode($data);
            return false;
        }
        $post = $this->input->post();

        if(isset($post['solutionMedia']) && $post['jobId'])
        {
            if($post['solutionMedia'] != '')
            {
                $mArr = explode('.',$post['solutionMedia']);
                $mediaType = 1;
                if(myInArray($mArr[count($mArr)-1],array('jpg','jpeg','png','gif')))
                {
                    $mediaType = 1;
                }
                else
                {
                    $mediaType = 2;
                }
                $details = array(
                    'solutionMedia' => $post['solutionMedia'],
                    'sMediaType' => $mediaType
                );
                $this->maintenance_model->updateComplaintMedia($details,$post['jobId']);
                $data['status'] = true;
            }
            else
            {
                $data['status'] = false;
                $data['errorMsg'] = 'No Media File Provided!';
            }
        }
        else
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Invalid Request!';
        }

        echo json_encode($data);
    }

    public function getJobDetails($jobId)
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Session Timeout, Please Login Again!';
            echo json_encode($data);
            return false;
        }

        $jobInfo = $this->maintenance_model->getComplaintById($jobId);

        if(isset($jobInfo) && myIsArray($jobInfo))
        {
            $data['compInfo'] = $jobInfo;
            $data['status'] = true;
        }
        else
        {
            $data['status'] = false;
            $data['errorMsg'] = "No Data Found!";
        }
        echo json_encode($data);

    }

    public function updateAmtReceived()
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Session Timeout, Please Login Again!';
            echo json_encode($data);
            return false;
        }
        $post = $this->input->post();

        if(isset($post['finId']) && isset($post['finDate']))
        {
            $details = array(
                'receiveDate' => date('Y-m-d H:i:s',strtotime($post['finDate'])),
                'receiveDT' => date('Y-m-d H:i:s')
            );
            $this->maintenance_model->updateFinRecord($details,$post['finId']);
            $data['status'] = true;
        }
        else
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Amount ID or Date not set!';
        }

        echo json_encode($data);
    }

    public function checkNewComp()
    {
        $data = array();
        $compStatus = $this->maintenance_model->getCompUpdate();

        $data['status'] = true;
        $data['compStatus'] = $compStatus['compAdded'];
        echo json_encode($data);

    }

    public function searchVendor()
    {
        $data = array();

        $vendors = $this->maintenance_model->getAllVendor();
        if(isset($vendors) && myIsArray($vendors))
        {
            $data['status'] = true;
            $data['vendors'] = $vendors;
        }
        else
        {
            $data['status'] = false;
        }

        echo json_encode($data);
    }

    public function calcMainBalance()
    {
        $mainBalance = 0;
        $allCosts = $this->maintenance_model->getAllCosts();

        if(isset($allCosts) && myIsArray($allCosts))
        {
            foreach($allCosts as $key => $row)
            {
                if(isset($row['approxCost']) && isset($row['actualCost']))
                {
                    if($row['approxCost'] >= $row['actualCost'])
                    {
                        $mainBalance += (double)($row['approxCost'] - ($row['actualCost']+$row['optionalTax']));
                    }
                    else
                    {
                        $mainBalance += (double)($row['actualCost'] - ($row['approxCost']+$row['optionalTax']));
                    }
                }
            }
        }

        return $mainBalance;
    }

    public function vendorInfo($venId)
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Session Timeout, Please Login Again!';
            echo json_encode($data);
            return false;
        }
        $venInfo = $this->maintenance_model->getVendorById($venId);
        if(isset($venInfo) && myIsArray($venInfo))
        {
            $data['venInfo'] = $venInfo;
            $data['status'] = true;
        }
        else
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Vendor Not Found!';
        }
        echo json_encode($data);
    }

    public function finalUpdate()
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Session Timeout, Please Login Again!';
            echo json_encode($data);
            return false;
        }
        $post = $this->input->post();

        if(isset($post['remark']) && isset($post['acCost']))
        {
            $comDetails = $this->maintenance_model->getComplaintById($post['compId']);
            $locBal = $this->maintenance_model->getBalanceByLoc($comDetails['locId']);
            if($post['invoicePics'] == '')
            {
                $post['invoicePics'] = null;
            }
            $details = array(
                'remarkIfAny' => $post['remark'],
                'actualCost' => $post['acCost'],
                'optionalTax' => $post['optTax'],
                'invoicePics' => $post['invoicePics']
            );

            $this->maintenance_model->updateComplaint($details,$post['compId']);
            $totBal = (int)$locBal['jobCostCap'] + (int)$post['acCost'];
            $details = array(
                'jobCostCap' => $totBal
            );
            $this->maintenance_model->updateBalByLoc($comDetails['locId'],$details);
            $data['status'] = true;
        }
        else
        {
            $data['status'] = false;
            $data['errorMsg'] = 'No Values set!';
        }
        echo json_encode($data);
    }
    public function invoiceUpdate()
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Session Timeout, Please Login Again!';
            echo json_encode($data);
            return false;
        }
        $post = $this->input->post();

        if($post['invoicePics'] != '')
        {
            $details = array(
                'invoicePics' => $post['invoicePics']
            );

            $this->maintenance_model->updateComplaint($details,$post['compId']);
            $data['status'] = true;
        }
        else
        {
            $data['status'] = false;
            $data['errorMsg'] = 'No Values set!';
        }
        echo json_encode($data);

    }

    public function uploadVendorFiles()
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Session Timeout, Please Login Again!';
            echo json_encode($data);
            return false;
        }
        $attchmentArr = '';
        $this->load->library('upload');
        if(isset($_FILES))
        {
            if($_FILES['attachment']['error'] != 1)
            {
                $filePath = $_FILES['attachment']['name'];
                $fileName = preg_replace('/\(|\)/','',$filePath);
                $fileName = preg_replace('/[^a-zA-Z0-9.]\.]/', '', $fileName);
                $fileName = str_replace(' ','_',$fileName);
                $fileName = time().'_'.$fileName;
                $config = array();
                $config['upload_path'] = '../dashboad/'.JOB_MEDIA_PATH; // FOOD_PATH_THUMB; //'uploads/food/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size']      = '0';
                $config['overwrite']     = TRUE;
                $config['file_name']     = $fileName;

                $this->upload->initialize($config);
                if(!$this->upload->do_upload('attachment'))
                {
                    log_message('error','Upload: '.$this->upload->display_errors());
                    $data['status'] = false;
                    $data['errorMsg'] = $this->upload->display_errors();
                    echo json_encode($data);
                    return false;
                }
                else
                {
                    $upload_data = $this->upload->data();
                    $attchmentArr= $this->image_thumb($upload_data['file_path'],$upload_data['file_name']);
                    if($attchmentArr == 'error')
                    {
                        $data['status'] = false;
                        $data['errorMsg'] = 'Error in resizing image!';
                        echo json_encode($data);
                        return false;
                    }
                    else
                    {
                        echo $attchmentArr;
                    }
                }
            }
            else
            {
                echo 'Some Error Occurred!';
            }
        }
        else
        {
            $data['status'] = false;
            $data['errorMsg'] = 'No Image File Received!';
            echo json_encode($data);
            return false;
        }
    }

    public function filterBudget()
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Session Timeout, Please Login Again!';
            echo json_encode($data);
            return false;
        }
        $post = $this->input->post();

        if(isset($post['payEndDate']) && isStringSet($post['payEndDate'])
            && isset($post['payStartDate']) && isStringSet($post['payStartDate']))
        {
            $data['payLogs'] = $this->maintenance_model->filterPayment($post['payStartDate'],$post['payEndDate']);
            $data['status'] = true;
        }
        else
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Start and end date required!';
        }
        echo json_encode($data);
    }

    public function changePri()
    {
        $post = $this->input->post();
        if(isset($post['complaintId']) && isset($post['jobPriority']))
        {
            $compId = $post['complaintId'];
            unset($post['complaintId']);
            $this->maintenance_model->updateComplaint($post,$compId);
            redirect(base_url().'maintenance/actionLog');
        }
    }
}
