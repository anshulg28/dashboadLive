<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Mailers
 * @property Mailers_Model $mailers_model
 * @property Mugclub_Model $mugclub_model
 * @property users_model $users_model
 * @property offers_model $offers_model
 * @property login_model $login_model
 * @property locations_model $locations_model
*/

class Mailers extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('mailers_model');
        $this->load->model('mugclub_model');
        $this->load->model('users_model');
        $this->load->model('offers_model');
        $this->load->model('login_model');
        $this->load->model('locations_model');
	}
	public function index()
	{
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url().'home');
        }
        if($this->userType == GUEST_USER || $this->userType == OFFERS_USER)
        {
            redirect(base_url());
        }

        if($this->userType == EXECUTIVE_USER)
        {
            if(!is_null($this->commSecLoc) && isSessionVariableSet($this->commSecLoc))
            {
                $data['expiredMugs'] = $this->mugclub_model->getExpiredMugsList(true,$this->commSecLoc);
                $data['expiringMugs'] = $this->mugclub_model->getExpiringMugsList(1,'week',true,$this->commSecLoc);
                $data['birthdayMugs'] = $this->mugclub_model->getBirthdayMugsList(true,$this->commSecLoc);
            }
            else
            {
                redirect(base_url().'dashboard/setCommLoc');
            }
            /*$userInfo = $this->users_model->getUserDetailsById($this->userId);
            if(!isset($userInfo['userData'][0]['assignedLoc']))
            {
                if(isset($userInfo['userData'][0]['secondaryLoc']))
                {
                    $userInfo['userData'][0]['assignedLoc'] = $userInfo['userData'][0]['secondaryLoc'];
                }
                else
                {
                    echo 'Location Not Set, Please Contact Admin!';
                    return false;
                }
            }*/
        }
        else
        {
            $data['expiredMugs'] = $this->mugclub_model->getExpiredMugsList();
            $data['expiringMugs'] = $this->mugclub_model->getExpiringMugsList(1,'week');
            $data['birthdayMugs'] = $this->mugclub_model->getBirthdayMugsList();
        }


        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);

       $this->load->view('MailersView',$data);
	}

    public function showMailAdd()
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url().'home');
        }
        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);

        $this->load->view('MailAddView',$data);

    }
    public function showPressMailAdd()
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }

        $data['pressTypes'] = $this->mailers_model->getPressMailTypes();

        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);

        $this->load->view('PressMailAddView',$data);

    }

    public function showPressMailEdit($pressId)
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }

        $data['mailInfo'] = $this->mailers_model->getPressMailById($pressId);
        $data['pressTypes'] = $this->mailers_model->getPressMailTypes();

        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);

        $this->load->view('PressMailEditView',$data);

    }

    public function savePressCategory()
    {
        $data = array();
        $post = $this->input->post();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Session Timeout! Login Again!';
            echo json_encode($data);
            return false;
        }

        $post['insertedDT'] = date('Y-m-d H:i:s');
        $this->mailers_model->saveMailType($post);
        $data['status'] = true;

        echo json_encode($data);
    }

    public function refreshMailTypes()
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Session Timeout! Login Again!';
            echo json_encode($data);
            return false;
        }
        $data['status'] = true;
        $data['mailTypes'] = $this->mailers_model->getPressMailTypes();
        echo json_encode($data);
    }
    public function saveMail()
    {

        $post = $this->input->post();

        $this->mailers_model->saveMailTemplate($post);

    }
    public function savePressMail()
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $post = $this->input->post();

        $this->mailers_model->savePressEmail($post);

        redirect(base_url().'mailers/pressSend');

    }

    public function removePressEmail($pressId)
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $this->mailers_model->deletePressEmail($pressId);
        redirect(base_url().'mailers/pressSend');
    }
    public function updatePressMail()
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $post = $this->input->post();
        if(isset($post) && myIsArray($post))
        {
            $PressId = $post['id'];
            unset($post['id']);
            $this->mailers_model->updatePressEmail($post,$PressId);
            redirect(base_url().'mailers/pressSend');
        }
    }
    public function sendMail($mailType)
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url().'home');
        }
        $data['mailType'] = $mailType;

        $mugData = $this->mugclub_model->getCheckInMugClubList();
        $data['mugData'] = $mugData;
        //fetching mail Templates according to mail type

        $mailResult = $this->mailers_model->getAllTemplatesByType($mailType);

        //check What type of mail it is
        if($mailType == EXPIRED_MAIL)
        {
            if($this->userType == EXECUTIVE_USER)
            {
                if(!is_null($this->commSecLoc) && isSessionVariableSet($this->commSecLoc))
                {
                    $expiredMails = $this->mugclub_model->getExpiredMugsList(true,$this->commSecLoc);
                }
                else
                {
                    redirect(base_url().'dashboard/setCommLoc');
                }
                /*$userInfo = $this->users_model->getUserDetailsById($this->userId);
                if(isset($userInfo['userData'][0]['assignedLoc']))
                {
                    $expiredMails = $this->mugclub_model->getExpiredMugsList(true,$userInfo['userData'][0]['assignedLoc']);
                }
                else
                {
                    $expiredMails = $this->mugclub_model->getExpiredMugsList(true,$userInfo['userData'][0]['secondaryLoc']);
                }*/
            }
            else
            {
                $expiredMails = $this->mugclub_model->getExpiredMugsList();
            }
            $data['mailMugs'] = $expiredMails;
        }
        elseif($mailType == EXPIRING_MAIL)
        {
            if($this->userType == EXECUTIVE_USER)
            {
                if(!is_null($this->commSecLoc) && isSessionVariableSet($this->commSecLoc))
                {
                    $expiringMails = $this->mugclub_model->getExpiringMugsList(1,'week',true,$this->commSecLoc);
                }
                else
                {
                    redirect(base_url().'dashboard/setCommLoc');
                }
                /*$userInfo = $this->users_model->getUserDetailsById($this->userId);
                if(isset($userInfo['userData'][0]['assignedLoc']))
                {
                    $expiringMails = $this->mugclub_model->getExpiringMugsList(1,'week',true,$userInfo['userData'][0]['assignedLoc']);
                }
                else
                {
                    $expiringMails = $this->mugclub_model->getExpiringMugsList(1,'week',true,$userInfo['userData'][0]['secondaryLoc']);
                }*/
            }
            else
            {
                $expiringMails = $this->mugclub_model->getExpiringMugsList(1,'week');
            }
            $data['mailMugs'] = $expiringMails;
        }
        elseif($mailType == BIRTHDAY_MAIL)
        {
            if($this->userType == EXECUTIVE_USER)
            {
                if(!is_null($this->commSecLoc) && isSessionVariableSet($this->commSecLoc))
                {
                    $expiringMails = $this->mugclub_model->getBirthdayMugsList(true,$this->commSecLoc);
                }
                else
                {
                    redirect(base_url().'dashboard/setCommLoc');
                }
                /*$userInfo = $this->users_model->getUserDetailsById($this->userId);
                if(isset($userInfo['userData'][0]['assignedLoc']))
                {
                    $expiringMails = $this->mugclub_model->getBirthdayMugsList(true,$userInfo['userData'][0]['assignedLoc']);
                }
                else
                {
                    $expiringMails = $this->mugclub_model->getBirthdayMugsList(true,$userInfo['userData'][0]['secondaryLoc']);
                }*/
            }
            else
            {
                $expiringMails = $this->mugclub_model->getBirthdayMugsList();
            }

            $data['mailMugs'] = $expiringMails;
        }

        $data['mailList'] = $mailResult;

        $data['loggedEmail'] = $this->userEmail;

        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);

        $this->load->view('MailSendView',$data);
    }

    public function sendAllMails($responseType = RESPONSE_RETURN)
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $post = $this->input->post();

        $mugNums = $post['mugNums'];
        if($post['mailType'] == CUSTOM_MAIL)
        {
            $mugNums = explode(',',$post['mugNums']);
        }
        foreach($mugNums as $key)
        {
            if(isset($key) && isStringSet($key))
            {
                $mugInfo = $this->mugclub_model->getMugDataForMailById($key);
                $commDetail = $this->users_model->searchUserByLoc($mugInfo['mugList'][0]['homeBase']);
                if($post['mailType'] == BIRTHDAY_MAIL)
                {
                    $newDate =array("membershipEnd"=> date('Y-m-d', strtotime($mugInfo['mugList'][0]['membershipEnd'].' +3 month')));
                    $this->mugclub_model->extendMemberShip($key,$newDate);
                    $mugInfo['mugList'][0]['membershipEnd'] = $newDate['membershipEnd'];
                }
                $fromName  = 'Doolally';
                if($commDetail['status'] == true && $this->userEmail == DEFAULT_COMM_EMAIL)
                {
                    $fromName = ucfirst(trim($commDetail['userData']['firstName']));
                }
                else
                {
                    if(isset($this->userFirstName))
                    {
                        $fromName = trim(ucfirst($this->userFirstName));
                    }
                }
                $newSubject = $this->replaceMugTags($post['mailSubject'],$mugInfo,$fromName);
                $newBody = $this->replaceMugTags($post['mailBody'],$mugInfo,$fromName);
                if(isset($post['isSimpleMail']) && $post['isSimpleMail'] == '1')
                {
                    $mainBody = '<html><body>';
                    $body = $newBody;
                    //$body = wordwrap($body, 70);
                    $body = nl2br($body);
                    $body = stripslashes($body);
                    $mainBody .= $body .'</body></html>';
                    $newBody = $mainBody;
                }
                //$cc        = implode(',',$this->config->item('ccList'));
                $fromEmail = DEFAULT_SENDER_EMAIL;
                $fromPass = DEFAULT_SENDER_PASS;
                $replyTo = $fromEmail;

                if($commDetail['status'] == true && $this->userEmail == DEFAULT_COMM_EMAIL)
                {
                    $replyTo = $commDetail['userData']['emailId'];
                }
                else
                {
                    if(isset($this->userEmail))
                    {
                        $replyTo = $this->userEmail;
                    }
                }

                if(isset($post['senderEmail']) && isStringSet($post['senderEmail'])
                    && isset($post['senderPass']) && isStringSet($post['senderPass']))
                {
                    $fromEmail = $post['senderEmail'];
                    $fromPass = $post['senderPass'];
                }

                $cc        = implode(',',$this->config->item('ccList'));
                if($commDetail['status'] == true && $this->userEmail != $commDetail['userData']['emailId'])
                {
                    $cc .= ','.$commDetail['userData']['emailId'];
                }
                /*$extraCc = getExtraCCEmail($fromEmail);
                if(isStringSet($extraCc))
                {
                    $cc = $cc.','.$extraCc;
                }*/

                $this->sendemail_library->sendEmail($mugInfo['mugList'][0]['emailId'],$cc,$fromEmail, $fromPass,$fromName,$replyTo,$newSubject,$newBody);
                $this->mailers_model->setMailSend($key,$post['mailType']);
            }
        }

        if($responseType == RESPONSE_JSON)
        {
            $data['status'] = true;
            echo json_encode($data);
        }
        else
        {
            return true;
        }
    }

    function replaceMugTags($tagStr,$mugInfo,$fromName = 'Doolally')
    {

        $tagStr = str_replace('[sendername]',$fromName,$tagStr);
        preg_match_all('/\[[brcode]\w+\]/', $tagStr, $output_array);
        if(myIsMultiArray($output_array))
        {
            foreach($output_array as $key => $row)
            {
                foreach($row as $subKey)
                {
                    if($subKey == '[brcode]')
                    {
                        $breakCode = $this->generateBreakfastTwoCode($mugInfo['mugList'][0]['mugId']);
                        $tagStr = str_replace('[brcode]',$breakCode,$tagStr);
                        break;
                    }
                }
                break;
            }
        }
        preg_match_all('/\[[docode]\w+\]/', $tagStr, $output_array);
        if(myIsMultiArray($output_array))
        {
            foreach($output_array as $key => $row)
            {
                foreach($row as $subKey)
                {
                    if($subKey == '[docode]')
                    {
                        $breakCode = $this->generateBeerCode($mugInfo['mugList'][0]['mugId']);
                        $tagStr = str_replace('[docode]',$breakCode,$tagStr);
                        break;
                    }
                }
                break;
            }
        }
        preg_match_all('/\[[offercode]\w+\]/', $tagStr, $offer_array);
        if(myIsMultiArray($offer_array))
        {
            foreach($offer_array as $key => $row)
            {
                foreach($row as $subKey)
                {
                    if($subKey == '[offercode]')
                    {
                        $olympicCode = $this->mailers_model->getOlympicsCode($mugInfo['mugList'][0]['mugId']);
                        //$breakCode = $this->generateBreakfastTwoCode($mugInfo['mugList'][0]['mugId']);
                        $tagStr = str_replace('[offercode]',$olympicCode['couponCode'],$tagStr);
                        break;
                    }
                }
                break;
            }
        }

        foreach($mugInfo['mugList'][0] as $key => $row)
        {
            switch($key)
            {
                case 'mugId':
                    $tagStr = str_replace('[mugno]',trim($row),$tagStr);
                    break;
                case 'firstName':
                    $tagStr = str_replace('[firstname]',trim(ucfirst($row)),$tagStr);
                    break;
                case 'lastName':
                    $tagStr = str_replace('[lastname]',trim(ucfirst($row)),$tagStr);
                    break;
                case 'birthDate':
                    $d = date_create($row);
                    $tagStr = str_replace('[birthdate]',date_format($d,DATE_MAIL_FORMAT_UI),$tagStr);
                    break;
                case 'mobileNo':
                    $tagStr = str_replace('[mobno]',trim($row),$tagStr);
                    break;
                case 'membershipEnd':
                    $d = date_create($row);
                    $tagStr = str_replace('[expirydate]',date_format($d,DATE_MAIL_FORMAT_UI),$tagStr);
                    break;
            }
        }
        return $tagStr;
    }

    function replacePressName($tagStr, $pressInfo)
    {
        preg_match_all('/\[[offercode]\w+\]/', $tagStr, $offer_array);
        if(myIsMultiArray($offer_array))
        {
            foreach($offer_array as $key => $row)
            {
                foreach($row as $subKey)
                {
                    if($subKey == '[offercode]')
                    {
                        $olympicCode = $this->mailers_model->getOlympicsRandomCode();
                        if(isset($olympicCode) && myIsArray($olympicCode))
                        {
                            $couponDetails = array(
                                'ownerDetails' => 'assigned'
                            );
                            $this->mailers_model->updateCouponDone($couponDetails,$olympicCode['id']);
                            $tagStr = str_replace('[offercode]',$olympicCode['couponCode'],$tagStr);
                        }
                        //$breakCode = $this->generateBreakfastTwoCode($mugInfo['mugList'][0]['mugId']);
                        break;
                    }
                }
                break;
            }
        }

        foreach($pressInfo as $key => $row)
        {
            switch($key)
            {
                case 'pressName':
                    $name = '';
                    if($row != '')
                    {
                        $name = explode(' ',$row)[0];
                    }
                    $tagStr = str_replace('[name]',trim(ucfirst($name)),$tagStr);
                    break;
            }
        }
        return $tagStr;
    }
    public function pressSend()
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $data = array();

        $mailResult = $this->mailers_model->getAllPressEmails();
        if($mailResult['status'] === true)
        {
            $data['pressMails'] = $mailResult['mailData'];
        }
        $data['loggedEmail'] = $this->userEmail;
        $data['mailCats'] = $this->mailers_model->fetchPressCats();
        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);

        $this->load->view('PressMailSendView',$data);
    }

    public function uploadFiles()
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $attchmentArr = '';
        $this->load->library('upload');
        if(isset($_FILES))
        {
            if($_FILES['attachment']['error'] != 1)
            {
                $config = array();
                $config['upload_path'] = './uploads/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|doc|docx';
                $config['max_size']      = '0';
                $config['overwrite']     = TRUE;

                $this->upload->initialize($config);
                $this->upload->do_upload('attachment');
                $upload_data = $this->upload->data();

                $attchmentArr = $upload_data['full_path'];
            }
            echo $attchmentArr;
        }
    }
    public function sendPressMails($responseType = RESPONSE_RETURN)
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $post = $this->input->post();
        $attchmentArr = array();

        if(isset($post['attachment']))
        {
            $attchmentArr = explode(',',$post['attachment']);
        }
        elseif(isset($post['attachmentUrls']))
        {
            $attchmentArr = explode(',',$post['attachmentUrls']);
        }

        $pressEmails = explode(',',$post['pressEmails']);

        $pressSub = $post['mailSubject'];
        $pressBody = $post['mailBody'];
        $mainBody = '<html><body>';
        $body = $pressBody;
        $mainBody .= $body .'</body></html>';

        foreach($pressEmails as $key)
        {
            if(isValidEmail($key))
            {
                $pressInfo = $this->mailers_model->getPressInfoByMail($key);
                $newBody = $mainBody;
                if(isset($pressInfo) && myIsArray($pressInfo))
                {
                    $newBody = $this->replacePressName($mainBody,$pressInfo);
                }
                $cc        = 'tresha@brewcraftsindia.com';
                $fromName  = 'Doolally';
                if(isset($this->userFirstName))
                {
                    $fromName = trim(ucfirst($this->userFirstName));
                }
                $fromEmail = DEFAULT_SENDER_EMAIL;
                $fromPass = DEFAULT_SENDER_PASS;
                $replyTo = $fromEmail;

                if(isset($this->userEmail))
                {
                    $replyTo = $this->userEmail;
                    /*$userInfo = $this->login_model->checkEmailSender($this->userEmail);
                    if(isset($userInfo) && myIsArray($this->userEmail))
                    {
                        $fromPass = $userInfo['gmailPass'];
                        $fromEmail = $this->userEmail;
                    }*/
                }

                /*if(isset($post['senderEmail']) && isStringSet($post['senderEmail'])
                    && isset($post['senderPass']) && isStringSet($post['senderPass']))
                {
                    $fromEmail = $post['senderEmail'];
                    $fromPass = $post['senderPass'];
                }*/

                if(myIsArray($attchmentArr))
                {
                    //Queuing the mails for press mailer
                    $logDetails = array(
                        'messageId' => null,
                        'sendTo' => $key,
                        'sendFrom' => $fromEmail,
                        'sendFromName' => $fromName,
                        'ccList' => $cc,
                        'replyTo' => $replyTo,
                        'mailSubject' => $pressSub,
                        'mailBody' => $newBody,
                        'attachments' => implode(',',$attchmentArr),
                        'sendStatus' => 'waiting',
                        'failIds' => null,
                        'sendDateTime' => date('Y-m-d H:i:s')
                    );
                }
                else
                {
                    //Queuing the mails for press mailer
                    $logDetails = array(
                        'messageId' => null,
                        'sendTo' => $key,
                        'sendFrom' => $fromEmail,
                        'sendFromName' => $fromName,
                        'ccList' => $cc,
                        'replyTo' => $replyTo,
                        'mailSubject' => $pressSub,
                        'mailBody' => $newBody,
                        'attachments' => '',
                        'sendStatus' => 'waiting',
                        'failIds' => null,
                        'sendDateTime' => date('Y-m-d H:i:s')
                    );
                }


                $this->mailers_model->saveWaitMailLog($logDetails);

            }
            //$this->sendemail_library->sendEmail($key,$cc,$fromEmail, $fromPass, $fromName,$replyTo,$pressSub,$newBody,$attchmentArr);
        }
        if($responseType == RESPONSE_JSON)
        {
            $data['status'] = true;
            echo json_encode($data);
        }
        else
        {
            return true;
        }

    }

    public function templates()
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }

        $data['templates'] = $this->mailers_model->getAllTemplates();

        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);

        $this->load->view('TemplatesView',$data);

    }

    public function templateAdd()
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

        $this->load->view('TemplateAddView',$data);
    }
    public function templateEdit($tempId)
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }

        $data['tempEdit'] = $this->mailers_model->getTemplateById($tempId);

        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);

        $this->load->view('TemplateAddView',$data);
    }
    public function tempUpdate()
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $post = $this->input->post();

        if(isset($post['id']))
        {
            $this->mailers_model->updateTemplate($post,$post['id']);
        }
        redirect(base_url().'mailers/templates');
    }

    public function tempSave()
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $post = $this->input->post();

        $this->mailers_model->saveTemplate($post);
        redirect(base_url().'mailers/templates');
    }
    public function generateBreakfastTwoCode($mugId)
    {
        $allCodes = $this->offers_model->getAllCodes();
        $usedCodes = array();
        $toBeInserted = array();
        if($allCodes['status'] === true)
        {
            foreach($allCodes['codes'] as $key => $row)
            {
                $usedCodes[] = $row['offerCode'];
            }
            $newCode = mt_rand(1000,99999);
            while(myInArray($newCode,$usedCodes))
            {
                $newCode = mt_rand(1000,99999);
            }
            $toBeInserted = array(
                'offerCode' => $newCode,
                'offerType' => 'Breakfast2',
                'offerLoc' => null,
                'offerMug' => $mugId,
                'isRedeemed' => 0,
                'ifActive' => 1,
                'createDateTime' => date('Y-m-d H:i:s'),
                'useDateTime' => null
            );
        }
        else
        {
            $newCode = mt_rand(1000,99999);
            $toBeInserted = array(
                'offerCode' => $newCode,
                'offerType' => 'Breakfast2',
                'offerLoc' => null,
                'offerMug' => $mugId,
                'isRedeemed' => 0,
                'ifActive' => 1,
                'createDateTime' => date('Y-m-d H:i:s'),
                'useDateTime' => null
            );
        }

        $this->offers_model->setSingleCode($toBeInserted);
        return 'BR-'.$newCode;
    }

    public function generateBeerCode($mugId)
    {
        $allCodes = $this->offers_model->getAllCodes();
        $usedCodes = array();
        $toBeInserted = array();
        if($allCodes['status'] === true)
        {
            foreach($allCodes['codes'] as $key => $row)
            {
                $usedCodes[] = $row['offerCode'];
            }
            $newCode = mt_rand(1000,99999);
            while(myInArray($newCode,$usedCodes))
            {
                $newCode = mt_rand(1000,99999);
            }
            $toBeInserted = array(
                'offerCode' => $newCode,
                'offerType' => 'Beer',
                'offerLoc' => null,
                'offerMug' => $mugId,
                'isRedeemed' => 0,
                'ifActive' => 1,
                'createDateTime' => date('Y-m-d H:i:s'),
                'useDateTime' => null
            );
        }
        else
        {
            $newCode = mt_rand(1000,99999);
            $toBeInserted = array(
                'offerCode' => $newCode,
                'offerType' => 'Beer',
                'offerLoc' => null,
                'offerMug' => $mugId,
                'isRedeemed' => 0,
                'ifActive' => 1,
                'createDateTime' => date('Y-m-d H:i:s'),
                'useDateTime' => null
            );
        }

        $this->offers_model->setSingleCode($toBeInserted);
        return 'DO-'.$newCode;
    }

    public function checkGmailLogin()
    {
        $data = array();

        $post = $this->input->post();
        require_once APPPATH.'libraries/swift_mailer/swift_required.php';

        $transport = Swift_SmtpTransport::newInstance ('smtp.gmail.com', 465, 'ssl')
            ->setUsername($post['from'])
            ->setPassword($post['fromPass']);

        try
        {
            $transport->start();
            $data['status'] = true;
        }
        catch(Swift_TransportException $st)
        {
            $data['status'] = false;
            $data['errorMsg'] = $st->getMessage();
        }

        echo json_encode($data);

    }

    public function sendBeer()
    {
        $data = array();

        $data['mailType'] = '0';

        $mugData = $this->mugclub_model->getCheckInMugClubList();
        $data['mugData'] = $mugData;
        //fetching mail Templates according to mail type

        $mailResult = $this->mailers_model->getAllTemplatesByType('0');

        $data['mailList'] = $mailResult;

        $data['loggedEmail'] = $this->userEmail;

        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);

        $this->load->view('MailBeerSendView',$data);
    }

    public function sendOlympicsMails($responseType = RESPONSE_RETURN)
    {
        $post = $this->input->post();

        $mugNums = explode(',',$post['mugNums']);;

        foreach($mugNums as $key)
        {
            if(isset($key) && isStringSet($key))
            {
                $mugInfo = $this->mugclub_model->getMugDataForMailById($key);

                $newSubject = $this->replaceMugTags($post['mailSubject'],$mugInfo);
                $newBody = $this->replaceMugTags($post['mailBody'],$mugInfo);
                if(isset($post['isSimpleMail']) && $post['isSimpleMail'] == '1')
                {
                    $mainBody = '<html><body>';
                    $body = $newBody;
                    $body = wordwrap($body, 70);
                    $body = nl2br($body);
                    $body = stripslashes($body);
                    $mainBody .= $body .'</body></html>';
                    $newBody = $mainBody;
                }
                $list = array(
                    'tresha@brewcraftsindia.com',
                    'priyanka@brewcraftsindia.com'
                );
                $cc        = implode(',',$list);
                $fromName  = 'Doolally';
                /*if(isset($this->userFirstName))
                {
                    $fromName = trim(ucfirst($this->userFirstName));
                }*/
                $fromEmail = DEFAULT_SENDER_EMAIL;
                $fromPass = DEFAULT_SENDER_PASS;
                $replyTo = $fromEmail;

                /*if(isset($post['senderEmail']) && isStringSet($post['senderEmail'])
                    && isset($post['senderPass']) && isStringSet($post['senderPass']))
                {
                    $fromEmail = $post['senderEmail'];
                    $fromPass = $post['senderPass'];
                }*/

                if(isValidEmail($mugInfo['mugList'][0]['emailId']))
                {
                    $logDetails = array(
                        'messageId' => null,
                        'sendTo' => $mugInfo['mugList'][0]['emailId'],
                        'sendFrom' => $fromEmail,
                        'sendFromName' => $fromName,
                        'ccList' => $cc,
                        'replyTo' => $replyTo,
                        'mailSubject' => $newSubject,
                        'mailBody' => $newBody,
                        'attachments' => '',
                        'sendStatus' => 'waiting',
                        'failIds' => null,
                        'sendDateTime' => null
                    );

                    $this->mailers_model->saveWaitMailLog($logDetails);
                }

                //$this->sendemail_library->sendEmail($mugInfo['mugList'][0]['emailId'],$cc,$fromEmail, $fromPass,$fromName,$replyTo,$newSubject,$newBody);
                //$this->mailers_model->setMailSend($key,$post['mailType']);
            }
        }

        if($responseType == RESPONSE_JSON)
        {
            $data['status'] = true;
            echo json_encode($data);
        }
        else
        {
            return true;
        }
    }

    public function sendPendingMails()
    {
        $mails = $this->mailers_model->getAllPendingMails();

        if(isset($mails) && myIsArray($mails))
        {
            $attachment = array();
            foreach($mails as $key => $row)
            {
                $attachment = explode(',',$row['attachments']);
                $mailStatus = $this->sendemail_library->sendEmail($row['sendTo'],$row['ccList'],$row['sendFrom'],
                    DEFAULT_SENDER_PASS,$row['sendFromName'],$row['replyTo'],$row['mailSubject'],
                    $row['mailBody'],$attachment);
                if($mailStatus == 'Success')
                {
                    $mailData = array(
                        'sendStatus' => 'done'
                    );
                    $this->mailers_model->updateMailDetails($mailData,$row['id']);
                }
            }
        }
    }

    public function tempMugFunc()
    {
        $tobeSaved = array();

        for($i=1;$i<=100;$i++)
        {
            if($i <= 50)
            {
                $tobeSaved[] = array(
                    'mugId' => $i,
                    'mugTag' => 'Tag',
                    'homeBase' => '1',
                    'firstName' => 'Gaurav',
                    'lastName' => 'Saha',
                    'mobileNo' => '9999999999',
                    'emailId' => 'gauravsaha84@gmail.com',
                    'birthDate' => '1985-01-01',
                    'invoiceNo' => '0000',
                    'invoiceAmt' => '3000',
                    'membershipStart' => date('Y-m-d'),
                    'membershipEnd' => date('Y-m-d'),
                    'oldHomeBase' => '1',
                    'ifActive' => '1',
                    'notes' => '',
                    'mailStatus' => '0',
                    'birthdayMailStatus' => '0',
                    'mailDate' => null,
                    'birthMailDate' => null
                );
            }
            else
            {
                $tobeSaved[] = array(
                    'mugId' => $i,
                    'mugTag' => 'Tag',
                    'homeBase' => '1',
                    'firstName' => 'Gaurav',
                    'lastName' => 'Saha',
                    'mobileNo' => '9999999999',
                    'emailId' => 'saha@brewcraftsindia.com',
                    'birthDate' => '1985-01-01',
                    'invoiceNo' => '0000',
                    'invoiceAmt' => '3000',
                    'membershipStart' => date('Y-m-d'),
                    'membershipEnd' => date('Y-m-d'),
                    'oldHomeBase' => '1',
                    'ifActive' => '1',
                    'notes' => '',
                    'mailStatus' => '0',
                    'birthdayMailStatus' => '0',
                    'mailDate' => null,
                    'birthMailDate' => null
                );
            }
        }

        $this->mailers_model->saveDummyMugs($tobeSaved);
    }

}
