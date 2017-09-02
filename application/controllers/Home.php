<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Home
 * @property generalfunction_library $generalfunction_library
 * @property locations_model $locations_model
 * @property dashboard_model $dashboard_model
 * @property mugclub_model $mugclub_model
 * @property login_model $login_model
 */

class Home extends MY_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('locations_model');
        $this->load->model('dashboard_model');
        $this->load->model('login_model');
    }

    public function index()
	{
/*        if(!isset($this->currentLocation) || isSessionVariableSet($this->currentLocation) === false)
        {
            if($this->userType != GUEST_USER)
            {
                redirect(base_url().'location-select');
            }
        }*/


		$data = array();
		$data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
		$data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
		$data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        $data['locArray'] = $this->locations_model->getAllLocations();
        if(isSessionVariableSet($this->isUserSession) === true)
        {
            $data['title'] = 'Home :: Doolally';
        }
        else
        {
            $data['title'] = 'Login :: Doolally';
        }
		$this->load->view('HomeView', $data);
	}

    public function main()
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);

        $this->load->view('MainView', $data);
    }
    public function getLocation()
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $data = array();
        if(isset($this->session->page_url))
        {
            $data['pageUrl']= $this->session->page_url;
            $this->session->unset_userdata('page_url');
        }

        $data['locData'] = $this->locations_model->getAllLocations();
        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);

        $this->load->view('LocSelectView', $data);
    }

    public function setLocation()
    {
        $post = $this->input->post();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }

        $this->generalfunction_library->setSessionVariable("currentLocation",$post['currentLoc']);
        if(isset($post['pageUrl']))
        {
            redirect($post['pageUrl']);
        }
        else
        {
            redirect(base_url());
        }

    }
    public function eventFetch($eventId, $evenHash)
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        if(hash_compare(encrypt_data($eventId),$evenHash))
        {
            $data = array();

            /*if($this->session->userdata('osType') == 'android')
            {*/
            $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
            $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
            $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
            $decodedS = explode('-',$eventId);
            $eventId = $decodedS[count($decodedS)-1];
            $events = $this->dashboard_model->getEventById($eventId);
            if(isset($events) && myIsMultiArray($events))
            {
                foreach($events as $key => $row)
                {
                    $loc = $this->locations_model->getLocationDetailsById($row['eventPlace']);
                    $row['locData'] = $loc['locData'];
                    $data['eventDetails'][$key]['eventData'] = $row;
                    $data['eventDetails'][$key]['eventAtt'] = $this->dashboard_model->getEventAttById($row['eventId']);
                }
            }

            $this->load->view('EventViewer', $data);
        }
        else
        {
            redirect(PAGE_404);
        }

        //echo json_encode($aboutView);
    }

    function sendOtp()
    {
        $data = array();
        $post = $this->input->post();

        if(isset($post['loc']))
        {

            $locCheck = $this->locations_model->getLocationDetailsById($post['loc']);

            if($locCheck['status'] == true)
            {
                if($locCheck['locData'][0]['ifActive'] == NOT_ACTIVE)
                {
                    $data['status'] = false;
                    $data['errorMsg'] = 'Location is Disabled!';
                }
                else
                {
                    $this->generalfunction_library->setSessionVariable("currentLocation",$post['loc']);
                    $userCheck = $this->login_model->checkUserByMob($locCheck['locData'][0]['phoneNumber']);

                    //code for attempt validation
                    /*if($userCheck['attemptTimes'] == 3)
                    {
                        $postData = array(
                            'ifActive'=>'0'
                        );
                        $this->login_model->updateUserRecord($userCheck['userId'],$postData);
                        $data['status'] = false;
                        $data['errorMsg'] = 'User is Disabled!';
                    }*/
                    /*else
                    {*/
                    /*$newAttempt = $userCheck['attemptTimes'] + 1;
                    $details = array(
                        'attemptTimes'=> $newAttempt
                    );
                    $this->login_model->updateUserRecord($userCheck['userId'],$details);*/

                    $newOtp = mt_rand(1000,99999);

                    $details = array(
                        'userOtp'=> $newOtp
                    );
                    $this->login_model->updateUserRecord($userCheck['userId'],$details);

                    $numbers = array('91'.$locCheck['locData'][0]['phoneNumber']);

                    $postDetails = array(
                        'apiKey' => TEXTLOCAL_API,
                        'numbers' => implode(',', $numbers),
                        'sender'=> urlencode('DOLALY'),
                        'message' => rawurlencode($newOtp.' is Your OTP for login')
                    );
                    $smsStatus = $this->curl_library->sendCouponSMS($postDetails);
                    $email = $userCheck['emailId'];
                    if(isset($email) && $email != '')
                    {
                        $mailData = array(
                            'emailId' => $email,
                            'otp' =>$newOtp
                        );
                        $this->sendemail_library->otpSendMail($mailData);
                    }
                    if($smsStatus['status'] == 'failure')
                    {
                        $data['status'] = false;
                        /*$details = array(
                            'attemptTimes'=> $userCheck['attemptTimes']
                        );
                        $this->login_model->updateUserRecord($userCheck['userId'],$details);*/
                        if(isset($smsStatus['warnings']))
                        {
                            $data['errorMsg'] = $smsStatus['warnings'][0]['message'];
                        }
                        else
                        {
                            $data['errorMsg'] = $smsStatus['errors'][0]['message'];
                        }
                    }
                    else
                    {
                        $data['mobNum'] = $locCheck['locData'][0]['phoneNumber'];
                        $data['status'] = true;
                    }
                    /*}*/
                }
            }
            else
            {
                $data['status'] = false;
                $data['errorMsg'] = 'User Not Found!';
            }

        }
        else
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Invalid Location Provided!';
        }

        echo json_encode($data);
    }

    /* Wallet Managing functions */

    function checkWallet()
    {
        $data = array();

        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $data['checkins'] = array();// $this->dashboard_model->getAllCheckins();

        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);

        $this->load->view('WalletCheckView', $data);
    }
    function walletManage($id)
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $wallet = $this->dashboard_model->getWalletTrans($id);
        $data = array();
        if(isset($wallet['status']) && $wallet['status'] === true)
        {
            $data['walletDetails'] = $wallet['walletDetails'];
        }

        $data['walletBalance'] = $this->dashboard_model->getWalletBalance($id);
        $data['walletId'] = $id;
        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);

        $this->load->view('WalletView', $data);
    }
    function getWallet()
    {
        $post = $this->input->post();
        $data = array();
        $isDefaultNum = false;
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }

        if(isset($post['userInput']))
        {
            if($post['userInput'] == DEFAULT_STAFF_MOB)
            {
                $isDefaultNum = TRUE;
            }
            else
            {
                $walletBal = $this->dashboard_model->getBalanceByInput($post['userInput']);
            }
        }
        /*else
        {
            $walletBal = $this->dashboard_model->getBalanceByEmp($post['userInput']);
        }*/
        if(isset($walletBal) && myIsMultiArray($walletBal))
        {
            /*if(!$isMobile && isset($walletBal['mobNum']))
            {
                $mob = $walletBal['mobNum'];
                $newOtp = mt_rand(10000,999999);

                $details = array(
                    'userOtp'=> $newOtp
                );
                $this->dashboard_model->updateStaffRecord($walletBal['id'],$details);

                $numbers = array('91'.$mob);

                $postDetails = array(
                    'apiKey' => TEXTLOCAL_API,
                    'numbers' => implode(',', $numbers),
                    'sender'=> urlencode('DOLALY'),
                    'message' => rawurlencode($newOtp.' is Your OTP for wallet')
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
            elseif($isMobile)
            {
                $mob = $post['userInput'];
                $newOtp = mt_rand(10000,999999);

                $details = array(
                    'userOtp'=> $newOtp
                );
                $this->dashboard_model->updateStaffRecord($walletBal['id'],$details);

                $numbers = array('91'.$mob);

                $postDetails = array(
                    'apiKey' => TEXTLOCAL_API,
                    'numbers' => implode(',', $numbers),
                    'sender'=> urlencode('DOLALY'),
                    'message' => rawurlencode($newOtp.' is Your OTP for wallet')
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
            }*/
            $data['status'] = true;
            $data['balance'] = $walletBal;
        }
        elseif($isDefaultNum)
        {
            $data['status'] = 'false';
            $data['errorMsg'] = 'Invalid Mobile Number!';
        }
        else
        {
            $data['status'] = false;
            $data['errorMsg'] = 'No Employee Found!';
        }
        echo json_encode($data);
    }

    function checkOtp()
    {
        $post = $this->input->post();
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        if(isset($post['mob']) && isset($post['otp']))
        {
            $userOtp = $this->dashboard_model->checkStaffOtp($post['mob'], $post['otp']);
            if($userOtp['status'] == false)
            {
                $data['status'] = false;
                $data['errorMsg'] = 'Invalid OTP';
            }
            else
            {
                $details = array(
                    'userOtp' => null
                );
                $this->dashboard_model->updateStaffRecord($userOtp['id'],$details);
                $data['status'] = true;
            }
        }
        else
        {
            $data['status'] = false;
            $data['errorMsg'] = 'OTP or Mobile Error';
        }

        echo json_encode($data);
    }

    function requestStaffOtp()
    {
        $post = $this->input->post();
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        if(isset($post['mob']))
        {
            $walletBal = $this->dashboard_model->getBalanceByMob($post['mob']);
            $newOtp = mt_rand(1000,99999);

            $details = array(
                'userOtp'=> $newOtp
            );
            $this->dashboard_model->updateStaffRecord($walletBal['id'],$details);

            $numbers = array('91'.$post['mob']);

            $postDetails = array(
                'apiKey' => TEXTLOCAL_API,
                'numbers' => implode(',', $numbers),
                'sender'=> urlencode('DOLALY'),
                'message' => rawurlencode($newOtp.' is Your OTP for wallet')
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
            $data['errorMsg'] = 'Mobile Number error!';
        }

        echo json_encode($data);
    }
    function checkinStaff()
    {
        $post = $this->input->post();
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }

        $checkin = $this->dashboard_model->checkStaffChecked($post['empId']);
        if($checkin['status'] === true)
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Employee Already Checked In';
        }
        else
        {
            if($post['walletBalance'] > 0)
            {
                $details = array(
                    'staffName'=> $post['staffName'],
                    'walletBalance'=> $post['walletBalance'],
                    'empId'=> $post['empId']
                );
                $this->dashboard_model->saveCheckinLog($details);
                $data['status'] = true;
            }
            else
            {
                $data['status'] = false;
                $data['errorMsg'] = 'Zero or Negative balance!';
            }
        }
        echo  json_encode($data);
    }

    function clearBill($id)
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $this->dashboard_model->clearCheckinLog($id);
        redirect(base_url().'check');
    }
    function staffBill()
    {
        $data = array();

        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $this->load->model('mugclub_model');
        if(!isset($this->currentLocation) || isSessionVariableSet($this->currentLocation) === false)
        {
            $this->session->set_userdata('page_url', base_url(uri_string()));
            if($this->userType != GUEST_USER || $this->userType == OFFERS_USER)
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
        /*$checkinDetail = $this->dashboard_model->getCheckinById($id);
        if (isset($checkinDetail) && myIsMultiArray($checkinDetail))
        4{
            $data['billDetails'] = $this->dashboard_model->getBalanceByEmp($checkinDetail[0]['empId']);
        }*/

        //$data['checkinId'] = $id;
        $data['locArray'] = $this->locations_model->getAllLocations();
        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);

        $this->load->view('staffBillView', $data);
    }
    function updateWallet($id)
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $post = $this->input->post();
        $oldBal = $post['oldBalance'];
        $finalBal = 0;
        $dorc = 1;
        $gotAmount = 0;
        if(isset($post['addAmt']) && $post['addAmt'] != '')
        {
            $addBal = $post['addAmt'];
            $gotAmount = $post['addAmt'];
            $finalBal = $oldBal + $addBal;
            $dorc = 2;
        }
        elseif(isset($post['subAmt']) && $post['subAmt'] != '')
        {
            $subBal = $post['subAmt'];
            $gotAmount = $post['subAmt'];
            $finalBal = $oldBal - $subBal;
        }

        $walletRecord = array(
            'staffId' => $id,
            'amount' => $gotAmount,
            'amtAction' => $dorc,
            'notes' => $post['notes'],
            'loggedDT' => date('Y-m-d H:i:s'),
            'updatedBy' => $this->userName
        );
        $this->dashboard_model->updateWalletLog($walletRecord);

        $details = array(
            'walletBalance' => $finalBal
        );
        $this->dashboard_model->updateStaffRecord($id,$details);
        $data['status'] = true;
        echo json_encode($data);
    }
    function saveStaff()
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $post = $this->input->post();

        $empCheck = $this->dashboard_model->checkStaffById($post['empId']);
        if($empCheck['status'] == true)
        {
            $data['status'] = false;
            $data['errorMsg'] = "Employee Already Exists!";
        }
        else
        {
            $id = $this->dashboard_model->saveStaffRecord($post);

            $walletRecord = array(
                'staffId' => $id,
                'amount' => $post['walletBalance'],
                'amtAction' => '2',
                'notes' => 'New Staff Added',
                'loggedDT' => date('Y-m-d H:i:s'),
                'updatedBy' => $this->userName
            );
            $this->dashboard_model->updateWalletLog($walletRecord);
            $data['status'] = true;
        }

        echo json_encode($data);
    }
    function updateStaff()
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $post = $this->input->post();
        /*$oldBal = $post['oldBalance'];
        unset($post['oldBalance']);

        $walletDiff = 0;
        $dorc = 1;
        if( $oldBal > $post['walletBalance'])
        {
            $walletDiff = $oldBal - $post['walletBalance'];
            $dorc = 1;
        }
        elseif($oldBal < $post['walletBalance'])
        {
            $walletDiff = $post['walletBalance'] - $oldBal;
            $dorc = 2;
        }*/
        $this->dashboard_model->updateStaffRecord($post['id'],$post);
        /*if($walletDiff != 0)
        {
            $walletRecord = array(
                'staffId' => $post['id'],
                'amount' => $walletDiff,
                'amtAction' => $dorc,
                'notes' => 'Staff details updated',
                'loggedDT' => date('Y-m-d H:i:s'),
                'updatedBy' => $this->userName
            );
            $this->dashboard_model->updateWalletLog($walletRecord);
        }*/
        redirect(base_url().'empDetails');
    }
    function addStaff()
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $data = array();

        //$locArray = $this->locations_model->getAllLocations();
        //$data['locations'] = $locArray;

        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);

        $this->load->view('StaffAddView', $data);

    }

    function blockStaff($id)
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $this->dashboard_model->blockStaffRecord($id);
        redirect(base_url().'empDetails');
    }
    function freeStaff($id)
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $data = array();
        $post = $this->input->post();
        if(isset($post['mobNum']) && isStringSet($post['mobNum']))
        {
            $staffCheck = $this->dashboard_model->checkStaffByMob($post['mobNum']);
            if($staffCheck['status'] == true)
            {
                $data['status'] = false;
            }
            else
            {
                $this->dashboard_model->freeStaffRecord($id,$post['mobNum']);
                $data['status'] = true;
            }
        }
        //$data['status'] = true;
        echo json_encode($data);
    }

    function staffEdit($id)
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $data = array();
        $staff = $this->dashboard_model->getStaffById($id);

        if(isset($staff['status']) && $staff['status'] === true)
        {
            $data['staffDetails'] = $staff['staffDetails'];
        }
        $locArray = $this->locations_model->getAllLocations();
        $data['locations'] = $locArray;

        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);

        $this->load->view('StaffEditView', $data);

    }
    function checkEmpId()
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $post = $this->input->post();
        $data = array();

        if(isset($post['empId']))
        {
            $staffCheck = $this->dashboard_model->checkStaffById($post['empId']);
            if($staffCheck['status'] == true)
            {
                $data['status'] = false;
            }
            else
            {
                $data['status'] = true;
            }
        }
        else
        {
            $data['status'] = false;
            $data['errorMsg'] = 'No Employee Id Provided!';
        }
        
        echo json_encode($data);
    }
    function checkStaffMob()
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $post = $this->input->post();
        $data = array();

        if(isset($post['mobNum']))
        {
            if($post['mobNum'] == DEFAULT_STAFF_MOB)
            {
                $data['status'] = true;
            }
            else
            {
                $staffCheck = $this->dashboard_model->checkStaffByMob($post['mobNum']);
                if($staffCheck['status'] == true)
                {
                    $data['status'] = false;
                }
                else
                {
                    $data['status'] = true;
                }
            }
        }
        else
        {
            $data['status'] = false;
            $data['errorMsg'] = 'No Mobile Number Provided!';
        }

        echo json_encode($data);
    }
    function empDetails()
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        if($this->userType == SERVER_USER)
        {
            redirect(base_url().'wallet');
        }
        $data = array();
        $staff = $this->dashboard_model->getAllStaffs();
        if(isset($staff['status']) &&$staff['status'] === true)
        {
            $data['staffList'] = $staff['staffList'];
        }

        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);

        $this->load->view('StaffView', $data);

    }

    function requestWalletOtp()
    {
        $post = $this->input->post();
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        if(isset($post['empId']))
        {
            if($post['empId'] == DEFAULT_STAFF_MOB)
            {
                $data['status'] = false;
                $data['errorMsg'] = 'Invalid Mobile Number!';
            }
            else
            {
                $walletBal = $this->dashboard_model->getBalanceByInput($post['empId']);
                /*if(is_numeric($post['empId']))
                {

                }*/
                /*else
                {
                    $walletBal = $this->dashboard_model->getBalanceByEmp($post['empId']);
                }*/

                if(isset($walletBal['mobNum']) && $walletBal['mobNum'] != '')
                {
                    if($walletBal['mobNum'] == DEFAULT_STAFF_MOB)
                    {
                        $locDetail = $this->locations_model->getLocationDetailsById($this->currentLocation);
                        if($locDetail['status'] == true)
                        {
                            $walletBal['mobNum'] = $locDetail['locData'][0]['phoneNumber'];
                        }
                        else
                        {
                            $data['status'] = false;
                            $data['errorMsg'] = 'No Mobile Number Available!';
                            echo json_encode($data);
                            return false;
                        }
                    }
                    $newOtp = mt_rand(1000,99999);

                    $details = array(
                        'userOtp'=> $newOtp
                    );
                    $this->dashboard_model->updateStaffRecord($walletBal['id'],$details);

                    $numbers = array('91'.$walletBal['mobNum']);

                    $postDetails = array(
                        'apiKey' => TEXTLOCAL_API,
                        'numbers' => implode(',', $numbers),
                        'sender'=> urlencode('DOLALY'),
                        'message' => rawurlencode($newOtp.' is Your OTP for wallet')
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
                    $locDetail = $this->locations_model->getLocationDetailsById($this->currentLocation);
                    if($locDetail['status'] == true)
                    {
                        $walletBal['mobNum'] = $locDetail['locData'][0]['phoneNumber'];
                        $newOtp = mt_rand(1000,99999);

                        $details = array(
                            'userOtp'=> $newOtp
                        );
                        if(isset($walletBal['id']))
                        {
                            $this->dashboard_model->updateStaffRecord($walletBal['id'],$details);
                        }
                        else
                        {
                            $this->dashboard_model->updateStaffRecordByEmp($post['empId'],$details);
                        }

                        $numbers = array('91'.$walletBal['mobNum']);

                        $postDetails = array(
                            'apiKey' => TEXTLOCAL_API,
                            'numbers' => implode(',', $numbers),
                            'sender'=> urlencode('DOLALY'),
                            'message' => rawurlencode($newOtp.' is Your OTP for wallet')
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
                        $data['errorMsg'] = 'No Mobile Number Available!';
                        echo json_encode($data);
                        return false;
                    }
                }
            }
        }
        else
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Mobile Number error!';
        }

        echo json_encode($data);
    }

    function getCoupon()
    {
        $post = $this->input->post();

        $data = array();
        //$coupon = $this->dashboard_model->getOneCoupon();

        if(isset($post['empId']) && isStringSet($post['empId']))
        {
            if($post['empId'] == DEFAULT_STAFF_MOB)
            {
                $data['status'] = false;
                $data['errorMsg'] = 'Invalid Mobile Number!';
            }
            else
            {
                $staffDetails = $this->dashboard_model->getBalanceByInput($post['empId']);

                if(isset($staffDetails) && myIsArray($staffDetails))
                {
                    $userOtp = $this->dashboard_model->checkStaffOtp($staffDetails['id'], $post['userOtp']);
                    if($userOtp['status'] == false)
                    {
                        $data['status'] = false;
                        $data['errorMsg'] = 'Invalid OTP';
                    }
                    else
                    {
                        $details = array(
                            'userOtp' => null
                        );
                        $this->dashboard_model->updateStaffRecord($userOtp['id'],$details);
                        $billCheck = $this->dashboard_model->checkBillNum($post['billNum'],$post['billLoc']);
                        if(!myIsArray($billCheck))
                        {
                            $postBillNum = $post['billNum'];
                            $postBillAmt = $post['billAmount'];

                            //Wallet Balance Calculation
                            $oldBalance = $staffDetails['walletBalance']; // $post['walletBalance'];
                            if((int)$oldBalance < (int)$postBillAmt)
                            {
                                $data['status'] = FALSE;
                                $data['errorMsg'] = "Insufficient Wallet Balance!";
                            }
                            else
                            {
                                $usedAmt = $postBillAmt;
                                $finalBal = $oldBalance - $usedAmt;
                                //$this->dashboard_model->setCouponUsed($coupon['id']);
                                $billLog = array(
                                    'billNum' => $postBillNum,
                                    'billLoc' => $post['billLoc'],
                                    'offerId' => null,
                                    'staffId' => $staffDetails['id'],
                                    'billAmount' => $postBillAmt,
                                    'insertedDT' => date('Y-m-d H:i:s')
                                );
                                $this->dashboard_model->saveBillLog($billLog);
                                //$this->dashboard_model->clearCheckinLog($post['checkInId']);

                                $walletRecord = array(
                                    'staffId' => $staffDetails['id'],
                                    'amount' => $usedAmt,
                                    'amtAction' => '1',
                                    'notes' => 'Wallet Balance Used',
                                    'loggedDT' => date('Y-m-d H:i:s'),
                                    'updatedBy' => 'system'
                                );
                                //Log Insertion in the wallet
                                $this->dashboard_model->updateWalletLog($walletRecord);

                                $details = array(
                                    'walletBalance' => $finalBal
                                );
                                $this->dashboard_model->updateStaffRecord($staffDetails['id'],$details);

                                if(isset($staffDetails['mobNum']) && isStringSet($staffDetails['mobNum']) && $staffDetails['mobNum'] != DEFAULT_STAFF_MOB)
                                {
                                    $numbers = array('91'.$staffDetails['mobNum']);

                                    $postDetails = array(
                                        'apiKey' => TEXTLOCAL_API,
                                        'numbers' => implode(',', $numbers),
                                        'sender'=> urlencode('DOLALY'),
                                        'message' => rawurlencode('Available Wallet Balance: '.$finalBal)
                                    );
                                    $smsStatus = $this->curl_library->sendCouponSMS($postDetails);
                                    if($smsStatus['status'] == 'failure')
                                    {
                                        if(isset($smsStatus['warnings']))
                                        {
                                            $data['smsError'] = $smsStatus['warnings'][0]['message'];
                                        }
                                        else
                                        {
                                            $data['smsError'] = $smsStatus['errors'][0]['message'];
                                        }
                                    }
                                }
                                $data['status'] = true;
                            }
                        }
                        else
                        {
                            $billLog = array(
                                'billNum' => $post['billNum'],
                                'billLoc' => $post['billLoc'],
                                'offerId' => null,
                                'staffId' => $staffDetails['id'],
                                'billAmount' => $post['billAmount'],
                                'insertedDT' => date('Y-m-d H:i:s')
                            );
                            $this->dashboard_model->saveFailBillLog($billLog);

                            $data['status'] = false;
                            $data['errorMsg'] = 'Bill Number Already Associated!';
                        }
                    }
                }
                else
                {
                    $data['status'] = false;
                    $data['errorMsg'] = 'No Employee Found!';
                }
            }
        }
        else
        {
            $data['status'] = false;
            $data['errorMsg'] = 'No Employee Information Available';
        }

        echo json_encode($data);
    }

    function smsErrorCodes($code)
    {
        $returnVal = '';
        switch($code)
        {
            case 4:
                $returnVal = 'No recipients specified.';
                break;
            case 5:
                $returnVal = 'No message content.';
                break;
            case 6:
                $returnVal = 'Message too long.';
                break;
            case 7:
                $returnVal = 'Insufficient credits.';
                break;
            case 32:
                $returnVal = 'Invalid number format.';
                break;
            case 33:
                $returnVal = 'You have supplied too many numbers.';
                break;
            case 43:
                $returnVal = 'Invalid sender name.';
                break;
            case 44:
                $returnVal = 'No sender name specified.';
                break;
            case 51:
                $returnVal = 'No valid numbers specified.';
                break;
            case 192:
                $returnVal = 'You cannot send message at this time.';
                break;
        }
        return $returnVal;
    }
}
