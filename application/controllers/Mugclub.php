<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Mugclub
 * @property mugclub_model $mugclub_model
 * @property users_model $users_model
 * @property locations_model $locations_model
 */

class Mugclub extends MY_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('mugclub_model');
        $this->load->model('users_model');
        $this->load->model('locations_model');
    }
	public function index()
	{
        $data = array();
		if(isSessionVariableSet($this->isUserSession) === false)
		{
			redirect(base_url());
		}
        if(isset($this->userType) && $this->userType == GUEST_USER || $this->userType == OFFERS_USER)
        {
            redirect(base_url());
        }

        //Getting All Mug List
        $mugData = $this->mugclub_model->getAllMugClubList();

        $data['locArr'] = $this->locations_model->getAllLocations();
        /*if(isset($mugData['mugList']) && myIsArray($mugData['mugList']))
        {
            foreach($mugData['mugList'] as $key => $row)
            {
                if(myIsArray($row))
                {
                    $mugData['mugList'][$key]['locationName'] = $this->mydatafetch_library->getBaseLocationsById($row['homeBase']);
                }
            }
        }*/

        $data['mugData'] = $mugData;


		$data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
		$data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
		$data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        
		$this->load->view('MugClubView', $data);
	}

    public function mugAvail()
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        if(isset($this->userType) && $this->userType == GUEST_USER || $this->userType == OFFERS_USER)
        {
            redirect(base_url());
        }

        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);

        $this->load->view('MugAvailView', $data);
    }
    public function addNewMug()
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }

        $locations = $this->mydatafetch_library->getBaseLocations();
        $data['baseLocations'] = $locations;

        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);

        $this->load->view('MugAddView', $data);
    }

    public function editExistingMug($mugId)
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }

        $muginfo = $this->mugclub_model->getMugDataById($mugId);

        $data['mugInfo'] = $muginfo;

        $locations = $this->mydatafetch_library->getBaseLocations();
        $data['baseLocations'] = $locations;

        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);

        $this->load->view('MugEditView', $data);
    }

    public function renewExistingMug($mugId)
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }

        $muginfo = $this->mugclub_model->getMugEndDateById($mugId);

        $data['mugInfo'] = $muginfo;
        $data['mugId'] = $mugId;

        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);

        $this->load->view('MugRenewView', $data);
    }

    public function mugRenew($responseType = RESPONSE_RETURN)
    {
        $post = $this->input->post();

        if(isSessionVariableSet($this->isUserSession))
        {
            if(!isset($post['invoiceNo']))
            {
                $post['invoiceNo'] = '0000';
            }
            if(isset($post['mugEmail']))
            {
                $userEmail = $post['mugEmail'];
                unset($post['mugEmail']);
            }
            $mugDetails = $this->mugclub_model->getMugIdForRenew($post['mugId']);
            $userFirstName = $mugDetails['firstName'];
            unset($mugDetails['firstName']);
            $mugDetails['mugId'] = $post['mugId'];

            if(isset($mugDetails['emailId']))
            {
                $userEmail = $mugDetails['emailId'];
                unset($mugDetails['emailId']);
            }

            $this->mugclub_model->saveRenewRecord($mugDetails);

            $post['membershipStart'] = date($mugDetails['membershipEnd']);
            $post['membershipEnd'] = date('Y-m-d', strtotime($post['membershipStart'].' +12 month'));

            /*if(date('Y-m-d') <= $mugDetails['membershipEnd'])
            {
                $post['membershipEnd'] = date('Y-m-d', strtotime($mugDetails['membershipEnd'].' +12 month'));
            }
            else
            {
                $post['membershipEnd'] = date('Y-m-d', strtotime($post['membershipStart'].' +12 month'));
            }*/
            $post['invoiceDate'] = date('Y-m-d');
            $post['invoiceAmt'] = 3000;
            $post['mailStatus'] = 0;
            $post['birthdayMailStatus'] = 0;

            $fromEmail = '';
            $fromPass = '';
            $isUserSet = false;
            if(isset($post['senderEmail']) && isStringSet($post['senderEmail'])
                && isset($post['senderPass']) && isStringSet($post['senderPass']))
            {
                $isUserSet = true;
                $fromEmail = $post['senderEmail'];
                $fromPass = $post['senderPass'];
                unset($post['senderEmail'],$post['senderPass']);
            }

            $this->mugclub_model->setMugRenew($post);

            if(isset($userEmail) && $userEmail != '')
            {
                $mailData = array(
                    "mugId" => $post['mugId'],
                    "firstName" => $userFirstName,
                    "newEndDate" => $post['membershipEnd'],
                    "emailId" => $userEmail
                );
                if($isUserSet)
                {
                    $mailData['fromEmail'] = $fromEmail;
                    $mailData['fromPass'] = $fromPass;
                }
                $this->sendemail_library->membershipRenewSendMail($mailData);
            }

            if($responseType == RESPONSE_RETURN)
            {
                redirect(base_url().'mugclub');
            }
            else
            {
                $data['status'] = true;
                echo json_encode($data);
            }
        }
        else
        {
            redirect(PAGE_404);
        }

    }
    public function saveOrUpdateMug()
    {
        $post = $this->input->post();

        if(isSessionVariableSet($this->isUserSession))
        {
            if(isset($post['oldMugNum']))
            {
                $mugId = $post['oldMugNum'];
                unset($post['oldMugNum']);
            }

            if(isset($mugId))
            {
                $mugExists = $this->mugclub_model->getMugDataById($mugId);
            }
            else
            {
                $mugExists = $this->mugclub_model->getMugDataById($post['mugNum']);
            }

            $invalidKeys = array('ifMail','senderEmail','senderPass');
            $params = $this->mugclub_model->filterMugParameters($post,$invalidKeys);

            if($mugExists['status'] === false)
            {
                $this->mugclub_model->saveMugRecord($params);
                if(isset($post['ifMail']) && $post['ifMail'] == '1')
                {
                    if(isset($post['senderEmail']) && isStringSet($post['senderEmail'])
                        && isset($post['senderPass']) && isStringSet($post['senderPass']))
                    {
                        $params['fromEmail'] = $post['senderEmail'];
                        $params['fromPass'] = $post['senderPass'];
                    }
                    $this->sendemail_library->signUpWelcomeSendMail($params);
                }
            }
            else
            {
                $changes = array();
                foreach($mugExists['mugList'] as $key => $row)
                {
                    foreach($row as $subKey => $subRow)
                    {
                        if(myInArray($subKey,array_keys($params)))
                        {
                            if($subRow != $params[$subKey])
                            {
                                $newKey = $this->mapMugKeys($subKey);
                                $changes[$newKey] = $subRow.':'.$params[$subKey];
                            }
                        }
                    }
                    //var_dump($row);
                }

                if(myIsMultiArray($changes) && $this->userType == EXECUTIVE_USER)
                {
                    if(isset($mugId))
                    {
                        $mugDetail['mugId'] = $mugId;
                    }
                    else
                    {
                        $mugDetail['mugId'] = $params['mugId'];
                    }
                    $mugDetail['changes'] = $changes;
                    $senderName = 'Doolally';
                    $senderEmail = 'events@doolally.in';
                    if(isStringSet($this->userEmail) && isStringSet($this->userName))
                    {
                        $senderEmail = $this->userEmail;
                        $senderName = $this->userName;
                    }
                    $mugDetail['senderName'] = $senderName;
                    $mugDetail['senderEmail'] = $senderEmail;

                    $this->sendemail_library->mugEditSendMail($mugDetail);
                }
                if(isset($post['ifMail']) && $post['ifMail'] == '1')
                {
                    if(isset($post['senderEmail']) && isStringSet($post['senderEmail'])
                        && isset($post['senderPass']) && isStringSet($post['senderPass']))
                    {
                        $params['fromEmail'] = $post['senderEmail'];
                        $params['fromPass'] = $post['senderPass'];
                    }
                    $this->sendemail_library->signUpWelcomeSendMail($params);
                }
                unset($params['fromEmail'],$params['fromPass']);
                if(isset($mugId))
                {
                    $this->mugclub_model->updateMugRecord($params,$mugId);
                }
                else
                {
                    $this->mugclub_model->updateMugRecord($params);
                }
            }
            redirect(base_url().'mugclub');
        }
        else
        {
            redirect(PAGE_404);
        }
    }

    public function ajaxMugUpdate()
    {
        $post = $this->input->post();
        $data = array();

        if(isSessionVariableSet($this->isUserSession))
        {
            $mugExists = $this->mugclub_model->getMugDataById($post['mugNum']);

            if($mugExists['status'] === false)
            {
                $data['status'] = false;
                $data['errorMsg'] = 'Mug Number Not Found!';
            }
            else
            {
                $invalidKeys = array();
                $params = $this->mugclub_model->filterMugParameters($post,$invalidKeys);
                $saveMail = array();
                foreach($params as $key => $row)
                {
                    $gotTxt = $this->getProperFieldText($key);
                    if($gotTxt != 'error')
                    {
                        $saveMail[] = $gotTxt;
                    }
                }
                if(myIsArray($saveMail))
                {
                    $mailData = array(
                        'locId' => $this->currentLocation,
                        'missingData' => implode(',',$saveMail),
                        'mugId' => $params['mugId']
                    );
                    $this->sendemail_library->checkinInfoFillMail($mailData);
                }
                $this->mugclub_model->updateMugRecord($params);
                $data['status'] = true;
            }
        }
        else
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Unauthorized Access!';
        }

        echo json_encode($data);
    }
    function getProperFieldText($gotParam)
    {
        $paramText = '';
        switch ($gotParam)
        {
            case 'firstName':
                $paramText = "First Name";
                break;
            case "lastName":
                $paramText = "Last Name";
                break;
            case "mobileNo":
                $paramText = "Mobile Number";
                break;
            case "emailId":
                $paramText = "Email Address";
                break;
            case "birthDate":
                $paramText = "Birthday";
                break;
            default:
                $paramText = 'error';
        }
        return $paramText;
    }
    public function deleteMugData($mugId)
    {

        $mugExists = $this->mugclub_model->checkMugExists($mugId);

        if($mugExists['status'] === false)
        {
            redirect(base_url().'mugclub');
        }
        else
        {
            //Check if mug already has delete record
            $oldMug = $mugId;
            while(true)
            {
                $delMug = $this->mugclub_model->checkDelMug($oldMug);
                if(myIsArray($delMug))
                {
                    $oldMug += 0.1;
                }
                else
                {
                    break;
                }
            }
            $mugExists['mugId'] = $oldMug;
            $this->mugclub_model->saveDelRecord($mugExists);
            $this->mugclub_model->deleteMugRecord($mugId);
        }
        redirect(base_url().'mugclub');
    }
    public function holdMugData($mugId)
    {
        $mugExists = $this->mugclub_model->getMugDataById($mugId);

        if($mugExists['status'] === false)
        {
            redirect(base_url().'mugclub');
        }
        else
        {
            $this->mugclub_model->holdMugRecord($mugId);
        }
        redirect(base_url().'mugclub');
    }

    public function MugAvailability($responseType = RESPONSE_JSON, $isAdding = 1, $mugid)
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession))
        {
            //Setting operation
            $op = 'minus';
            //Initial search Capping limit
            $searchCap = 50;
            $opFlag = 1;
            if(isset($mugid))
            {
                if($isAdding == 1)
                {
                    //Getting mug number data if exists
                    $result = $this->mugclub_model->getMugDataById($mugid);
                    // Mug Data exists

                    if($result['status'] === true)
                    {
                        $holdMugs = $this->mugclub_model->getAllMugHolds();
                        $mugResult = $this->getAllUnusedMugs($mugid, $op, $searchCap, $holdMugs);
                        if(count($mugResult) < 1)
                        {
                            while(count($mugResult) < 1 && $searchCap != 500)
                            {
                                if($opFlag == 1)
                                {
                                    $opFlag = 2;
                                    $op = 'minus';
                                }
                                else
                                {
                                    $opFlag = 1;
                                    $op = 'plus';
                                    $searchCap += 50;
                                }

                                $mugResult = $this->getAllUnusedMugs($mugid, $op, $searchCap,$holdMugs);
                            }
                            $data['availMugs'] = $mugResult;
                        }
                        else
                        {
                            $data['availMugs'] = $mugResult;
                        }

                        $data['status'] = false;
                        $data['errorMsg'] = 'Mug Number Already Exists';
                    }
                    else // Mug Data not found
                    {
                        //Check if mug number is not on hold
                        $holdMug = $this->mugclub_model->getMugHoldById($mugid);
                        if($holdMug['status'] === true) //Mug Number on hold search new
                        {
                            $mugResult = $this->getAllUnusedMugs($mugid, $op, $searchCap, array($mugid));
                            if(count($mugResult) < 1)
                            {
                                while(count($mugResult) < 1 && $searchCap != 500)
                                {
                                    if($opFlag == 1)
                                    {
                                        $opFlag = 2;
                                        $op = 'minus';
                                    }
                                    else
                                    {
                                        $opFlag = 1;
                                        $op = 'plus';
                                        $searchCap += 50;
                                    }

                                    $mugResult = $this->getAllUnusedMugs($mugid, $op, $searchCap, array($mugid));
                                }
                                $data['availMugs'] = $mugResult;
                            }
                            else
                            {
                                $data['availMugs'] = $mugResult;
                            }

                            $data['status'] = false;
                            $data['errorMsg'] = 'Mug Number Already Exists';
                        }
                        else // Mug Not on hold and available
                        {
                            $data['status'] = true;
                        }
                    }
                }
                else
                {
                    if(!in_array($mugid,unserialize(MUG_BLOCK_RANGE)))//range(0,100)
                    {
                        //Getting mug number data if exists
                        $result = $this->mugclub_model->getMugDataById($mugid);
                        // Mug Data exists
                        if($result['status'] === true)
                        {
                            $holdMugs = $this->mugclub_model->getAllMugHolds();
                            $mugResult = $this->getAllUnusedMugs($mugid, $op, $searchCap, $holdMugs);
                            if(count($mugResult) < 1)
                            {
                                while(count($mugResult) < 1 && $searchCap != 500)
                                {
                                    if($opFlag == 1)
                                    {
                                        $opFlag = 2;
                                        $op = 'minus';
                                    }
                                    else
                                    {
                                        $opFlag = 1;
                                        $op = 'plus';
                                        $searchCap += 50;
                                    }

                                    $mugResult = $this->getAllUnusedMugs($mugid, $op, $searchCap,$holdMugs);
                                }
                                $data['availMugs'] = $mugResult;
                            }
                            else
                            {
                                $data['availMugs'] = $mugResult;
                            }

                            $data['status'] = false;
                            $data['errorMsg'] = 'Mug Number Already Exists';
                        }
                        else // Mug Data not found
                        {
                            //Check if mug number is not on hold
                            $holdMug = $this->mugclub_model->getMugHoldById($mugid);
                            if($holdMug['status'] === true) //Mug Number on hold search new
                            {
                                $mugResult = $this->getAllUnusedMugs($mugid, $op, $searchCap, array($mugid));
                                if(count($mugResult) < 1)
                                {
                                    while(count($mugResult) < 1 && $searchCap != 500)
                                    {
                                        if($opFlag == 1)
                                        {
                                            $opFlag = 2;
                                            $op = 'minus';
                                        }
                                        else
                                        {
                                            $opFlag = 1;
                                            $op = 'plus';
                                            $searchCap += 50;
                                        }

                                        $mugResult = $this->getAllUnusedMugs($mugid, $op, $searchCap, array($mugid));
                                    }
                                    $data['availMugs'] = $mugResult;
                                }
                                else
                                {
                                    $data['availMugs'] = $mugResult;
                                }

                                $data['status'] = false;
                                $data['errorMsg'] = 'Mug Number Already Exists';
                            }
                            else // Mug Not on hold and available
                            {
                                $data['status'] = true;
                            }
                        }
                    }
                    else
                    {
                        $data['status'] = false;
                        $data['errorMsg'] = 'Mug Number Not Available';
                    }
                }
            }
        }
        else
        {
            $data['status'] = 'error';
            $data['errorMsg'] = 'Unauthorized Access!';
        }

        //returning the response
        if($responseType == RESPONSE_JSON)
        {
            echo json_encode($data);
        }
        else
        {
            return $data;
        }
    }

    function getAllUnusedMugs($mugId, $op, $searchCap, $holdMugs = array())
    {
        $rangeEnd = $mugId + $searchCap;

        switch($op)
        {
            case 'plus':
                $rangeEnd = $mugId + $searchCap;
                if($rangeEnd > 9998)
                {
                    $rangeEnd = $mugId - $searchCap;
                }
                break;
            case 'minus':
                $rangeEnd = $mugId - $searchCap;
                if($rangeEnd < 0)
                {
                    $rangeEnd = $mugId + $searchCap;
                }
                break;
        }

        $result = $this->mugclub_model->getMugRange($mugId, $rangeEnd);

        $allMugs = range(($mugId-$searchCap),$mugId);
        switch($op)
        {
            case 'plus':
                $allMugs = range($mugId,($mugId+$searchCap));
                if(($mugId+$searchCap) > 9998)
                {
                    $allMugs = range(($mugId-$searchCap),$mugId);
                }
                break;
            case 'minus':
                $allMugs = range(($mugId-$searchCap),$mugId);
                if(($mugId-$searchCap) < 0)
                {
                    $allMugs = range($mugId,($mugId+$searchCap));
                }
                break;
        }

        if(myIsArray($result))
        {
            $availMugs = array_diff($allMugs, $result);
        }
        else
        {
            $availMugs = $allMugs;
        }

        $availMugs = array_values($availMugs);
        $blockedNums = unserialize(MUG_BLOCK_RANGE);// range(0,100);
        $availMugs = array_diff($availMugs,$blockedNums);
        if(myIsMultiArray($holdMugs))
        {
            //$holdMugs['mugList'] = array_merge($holdMugs['mugList'],$blockedNums);
            foreach($holdMugs['mugList'] as $key => $row)
            {
                $aKey = array_search($row['mugId'],$availMugs);
                if($aKey)
                {
                    unset($availMugs[$aKey]);
                }
            }
        }

        return $availMugs;
    }

    public function CheckMobileNumber($responseType = RESPONSE_JSON, $mobNo)
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession))
        {
            if(isset($mobNo))
            {
                $result = $this->mugclub_model->verifyMobileNo($mobNo);
                if($result['status'] === true)
                {
                    $data['status'] = false;
                    $data['errorMsg'] = 'Mobile Number Already Exists';
                }
                else
                {
                    $data['status'] = true;
                }
            }
        }
        else
        {
            $data['mugData']['status'] = false;
            $data['error'] = 'Unauthorized Access!';
        }

        //returning the response
        if($responseType == RESPONSE_JSON)
        {
            echo json_encode($data);
        }
        else
        {
            return $data;
        }
    }

    public function getAllMugListMembers()
    {
        if(isSessionVariableSet($this->isUserSession))
        {
            //Getting All Mug List
            $mugData = $this->mugclub_model->getAllMugClubList();
            $data['mugData'] = $mugData;
        }
        else
        {
            $data['mugData']['status'] = false;
            $data['error'] = 'Unauthorized Access!';
        }

        /*if(isset($mugData['mugList']) && myIsArray($mugData['mugList']))
        {
            foreach($mugData['mugList'] as $key => $row)
            {
                if(myIsArray($row))
                {
                    $mugData['mugList'][$key]['locationName'] = $this->mydatafetch_library->getBaseLocationsById($row['homeBase']);
                }
            }
        }*/

        echo json_encode($data);
    }

    public function getAllExpiringMugs($responseType = RESPONSE_RETURN, $intervalNum, $intervalSpan)
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession))
        {
            if($this->userType == EXECUTIVE_USER)
            {
                $userInfo = $this->users_model->getUserDetailsById($this->userId);
                if(!isset($userInfo['userData'][0]['assignedLoc']))
                {
                    if(isset($userInfo['userData'][0]['secondaryLoc']))
                    {
                        $userInfo['userData'][0]['assignedLoc'] = $userInfo['userData'][0]['secondaryLoc'];
                    }
                }
                $mugData = $this->mugclub_model->getExpiringMugsList($intervalNum, $intervalSpan,true,$userInfo['userData'][0]['assignedLoc']);
            }
            else
            {
                $mugData = $this->mugclub_model->getExpiringMugsList($intervalNum, $intervalSpan);
            }

            if($mugData['status'] === false)
            {
                $data['status'] = false;
                $data['errorMsg'] = "No Result Found!";
            }
            else
            {
                $data['status'] = true;
                $data['mugData'] = $mugData;
            }
        }
        else
        {
            $data['status'] = false;
            $data['errorMsg'] = "Unauthorized Access!";
        }

        if($responseType == RESPONSE_JSON)
        {
            echo json_encode($data);
        }
        else
        {
            return $data;
        }
    }

    public function getAllExpiredMugs($responseType = RESPONSE_RETURN)
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession))
        {
            if($this->userType == EXECUTIVE_USER)
            {
                $userInfo = $this->users_model->getUserDetailsById($this->userId);
                if(!isset($userInfo['userData'][0]['assignedLoc']))
                {
                    if(isset($userInfo['userData'][0]['secondaryLoc']))
                    {
                        $userInfo['userData'][0]['assignedLoc'] = $userInfo['userData'][0]['secondaryLoc'];
                    }
                }
                $mugData = $this->mugclub_model->getExpiredMugsList(true,$userInfo['userData'][0]['assignedLoc']);
            }
            else
            {
                $mugData = $this->mugclub_model->getExpiredMugsList();
            }

            if($mugData['status'] === false)
            {
                $data['status'] = false;
                $data['errorMsg'] = "No Mugs Expired!";
            }
            else
            {
                $data['status'] = true;
                $data['mugData'] = $mugData;
            }
        }
        else
        {
            $data['status'] = false;
            $data['errorMsg'] = "Unauthorized Access!";
        }

        if($responseType == RESPONSE_JSON)
        {
            echo json_encode($data);
        }
        else
        {
            return $data;
        }
    }

    public function getAllBirthdayMugs($responseType = RESPONSE_RETURN)
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession))
        {
            if($this->userType == EXECUTIVE_USER)
            {
                $userInfo = $this->users_model->getUserDetailsById($this->userId);
                if(!isset($userInfo['userData'][0]['assignedLoc']))
                {
                    if(isset($userInfo['userData'][0]['secondaryLoc']))
                    {
                        $userInfo['userData'][0]['assignedLoc'] = $userInfo['userData'][0]['secondaryLoc'];
                    }
                }
                $mugData = $this->mugclub_model->getBirthdayMugsList(true,$userInfo['userData'][0]['assignedLoc']);
            }
            else
            {
                $mugData = $this->mugclub_model->getBirthdayMugsList();
            }

            if($mugData['status'] === false)
            {
                $data['status'] = false;
                $data['errorMsg'] = "No Mugs Found!";
            }
            else
            {
                $data['status'] = true;
                $data['mugData'] = $mugData;
            }
        }
        else
        {
            $data['status'] = false;
            $data['errorMsg'] = "Unauthorized Access!";
        }

        if($responseType == RESPONSE_JSON)
        {
            echo json_encode($data);
        }
        else
        {
            return $data;
        }
    }

    public function transfer()
    {
        if(isSessionVariableSet($this->isUserSession))
        {
            $post = $this->input->post();
            $data = array();
            $invalidKeys = array();
            $params = $this->mugclub_model->filterMugParameters($post, $invalidKeys);
            $this->mugclub_model->updateMugRecord($params);
            $data['status'] = true;
        }
        else
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Unauthorized Access!';
        }
        
        echo json_encode($data);
    }

    function mapMugKeys($key)
    {
        $returnTxt = '';
        switch($key)
        {
            case 'mugId':
                $returnTxt = 'Mug_Number';
                break;
            case 'mugTag':
                $returnTxt = 'Mug_Tag';
                break;
            case 'homeBase':
                $returnTxt = 'HomeBase';
                break;
            case 'firstName':
                $returnTxt = 'First_Name';
                break;
            case 'lastName':
                $returnTxt = 'Last_Name';
                break;
            case 'mobileNo':
                $returnTxt = 'Mobile_Number';
                break;
            case 'emailId':
                $returnTxt = 'Email_Id';
                break;
            case 'birthDate':
                $returnTxt = 'Birth_Date';
                break;
            case 'invoiceDate':
                $returnTxt = 'Invoice_Date';
                break;
            case 'invoiceNo':
                $returnTxt = 'Invoice_No';
                break;
            case 'invoiceAmt':
                $returnTxt = 'Invoice_Amount';
                break;
            case 'membershipStart':
                $returnTxt = 'MemberShip_Start';
                break;
            case 'membershipEnd':
                $returnTxt = 'MemberShip_End';
                break;
            case 'notes':
                $returnTxt = 'Notes';
                break;
        }
        return $returnTxt;
    }

    function addInstaMug()
    {
        $data = array();
        $post = $this->input->post();

        if(isSessionVariableSet($this->isUserSession))
        {
            if(isset($post['memId']))
            {
                $instaRecord = $this->mugclub_model->getInstaMugById($post['memId']);
                if(isset($instaRecord) && myIsArray($instaRecord))
                {
                    $mugExists = $this->mugclub_model->getMugDataById($instaRecord['mugId']);
                    {
                        if($mugExists['status'] === false)
                        {
                            $mugTag = '';
                            if(isset($instaRecord['mugTag']))
                            {
                                $mugTag = $instaRecord['mugTag'];
                            }
                            $memStart = date('Y-m-d');
                            $details = array(
                                'mugId' => $instaRecord['mugId'],
                                'mugTag' => $mugTag,
                                'homeBase' => $instaRecord['homeBase'],
                                'firstName' => $instaRecord['firstName'],
                                'lastName' => $instaRecord['lastName'],
                                'mobileNo' => $instaRecord['mobileNo'],
                                'emailId' => $instaRecord['emailId'],
                                'birthDate' => $instaRecord['birthDate'],
                                'invoiceDate' => $instaRecord['invoiceDate'],
                                'invoiceNo' => $instaRecord['invoiceNo'],
                                'invoiceAmt' => $instaRecord['invoiceAmt'],
                                'membershipStart' => $memStart,
                                'membershipEnd' => date('Y-m-d', strtotime($memStart.' +12 month')),
                                'oldHomeBase' => '0',
                                'ifActive' => '1',
                                'notes' => '',
                                'mailStatus' => '0',
                                'birthdayMailStatus' => '0',
                                'mailDate' => null
                            );

                            $this->mugclub_model->saveMugRecord($details);

                            if($post['ifMail'] == '1')
                            {
                                $this->sendemail_library->signUpWelcomeSendMail($details);
                            }

                            $instaMug = array(
                                'isApproved' => '1'
                            );
                            $this->mugclub_model->updateInstaMug($instaMug,$post['memId']);
                            $data['status'] = true;
                        }
                        else
                        {
                            $data['status'] = false;
                            $data['errorMsg'] = 'Mug Number Already Exists!';
                        }
                    }
                }
            }
            else
            {
                $data['status'] = false;
                $data['errorMsg'] = 'No Mug Details Provided!';
            }
        }
        else
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Unauthorized Access!';
        }

        echo json_encode($data);
    }

    public function breakMugAssign()
    {
        $mugNums = array(3,6,8,13,25,26,35,50,51,69,88,93,94,97,119,123,145,159,160,180,181,184,200,201,204,220,222,230,
            238,239,241,242,288,299,401,416,419,425,434,458,488,489,504,511,520,569,590,595,690,693,710,717,725,784,788,
            905,913,917,927,986,996,1004,1009,1014,1069,1093,1108,1110,1164,1208,1211,1213,1223,1229,1237,1289,1300,1301,
            1309,1369,1402,1409,1505,1508,1509,1511,1519,1588,1609,1611,1612,1650,1701,1703,1714,1717,1800,1811,1886,
            1900,1918,1933,1950,1962,1973,1978,1980,1981,1983,1985,1986,1987,1990,1992,1993,2005,2011,2016,2104,2202,
            2230,2312,2323,2367,2389,2420,2455,2502,2504,2525,2580,2591,2603,2607,2693,2710,2805,2808,2810,2908,2911,
            2912,3103,3112,3586,3693,3825,4200,4343,4414,4646,4682,4887,4990,5050,5433,5444,5560,5653,5913,6603,6691,
            6900,6997,7000,7070,7147,7608,7860,8055,8848,8854,8880,9009,9090,9100,9209,9559,9819,9898,9990,9993);

        foreach($mugNums as $key)
        {

        }
    }
}
