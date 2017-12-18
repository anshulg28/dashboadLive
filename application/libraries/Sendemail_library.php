<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Sendemail_library
 * @property Offers_model $offers_model
 * @property Users_Model $users_model
 */
class Sendemail_library
{
    private $CI;

    function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('offers_model');
        $this->CI->load->model('users_model');
        $this->CI->load->model('login_model');
        $this->CI->load->model('mailers_model');
    }
    //Done
    public function signUpWelcomeSendMail($userData)
    {
        $data['mailData'] = $userData;
        $data['breakfastCode'] = $this->generateBreakfastCode($userData['mugId']);
        if(isset($userData['homeBase']))
        {
            $commDetail = $this->CI->users_model->searchUserByLoc($userData['homeBase']);
            if($commDetail['status'] === true)
            {
                $data['fromName'] = ucfirst(trim($commDetail['userData']['firstName']));
            }
            else
            {
                $data['fromName'] = ucfirst($this->CI->userFirstName);
            }
        }

        $content = $this->CI->load->view('emailtemplates/signUpWelcomeMailView', $data, true);

        $fromEmail = DEFAULT_SENDER_EMAIL;
        $fromPass = DEFAULT_SENDER_PASS;
        $replyTo = $fromEmail;

        if(isset($userData['fromEmail']) && isset($userData['fromPass']))
        {
            $fromEmail = $userData['fromEmail'];
            $fromPass = $userData['fromPass'];
            $replyTo = $userData['fromEmail'];
        }

        $cc        = implode(',',$this->CI->config->item('ccList'));
        /*$extraCc = getExtraCCEmail($fromEmail);
        if(isStringSet($extraCc))
        {
            $cc = $cc.','.$extraCc;
        }*/
        $fromName  = 'Doolally';
        if( isset($commDetail) && myIsArray($commDetail) && $commDetail['status'] === true)
        {
            if($fromEmail != $commDetail['userData']['emailId'])
            {
                $cc .= ','.$commDetail['userData']['emailId'];
                if($fromEmail == DEFAULT_COMM_EMAIL)
                {
                    $fromName = ucfirst(trim($commDetail['userData']['firstName']));
                }
            }
            else
            {
                $fromName = ucfirst(trim($commDetail['userData']['firstName']));
            }
        }
        /*if(isset($this->CI->userFirstName))
        {
            $fromName = ucfirst($this->CI->userFirstName);
        }*/
        $subject = 'Breakfast for Mug #'.$userData['mugId'];
        $toEmail = $userData['emailId'];

        $this->sendEmail($toEmail, $cc, $fromEmail, $fromPass, $fromName,$replyTo, $subject, $content);
    }

    public function refundFailSendMail($userData)
    {
        $data['mailData'] = $userData;

        $content = $this->CI->load->view('emailtemplates/refundFailMailView', $data, true);

        $fromEmail = DEFAULT_SENDER_EMAIL;
        $fromPass = DEFAULT_SENDER_PASS;
        $replyTo = $fromEmail;

        $cc        = '';
        $fromName  = 'Doolally';

        $subject = 'EventsHigh Refund Failed Booking Id '.$userData['bookingId'];
        $toEmail = array('saha@brewcraftsindia.com','anshul@brewcraftsindia.com','tresha@brewcraftsindia.com','taronish@brewcraftsindia.com');

        $this->sendEmail($toEmail, $cc, $fromEmail, $fromPass, $fromName,$replyTo, $subject, $content);
    }

    //Not in Use
    public function memberWelcomeMail($userData, $eventPlace)
    {
        $mailRecord = $this->CI->users_model->searchUserByLoc($eventPlace);

        $data['mailData'] = $userData;

        $content = $this->CI->load->view('emailtemplates/memberWelcomeMailView', $data, true);

        $fromEmail = DEFAULT_SENDER_EMAIL;
        $fromPass = DEFAULT_SENDER_PASS;
        $replyTo = $mailRecord['userData']['emailId'];
        //$fromEmail = ;

        $cc        = implode(',',$this->CI->config->item('ccList'));
        $fromName  = 'Doolally';
        if(isset($mailRecord['userData']['firstName']))
        {
            $fromName = $mailRecord['userData']['firstName'];
        }

        $subject = 'Welcome to Doolally';
        $toEmail = $userData['creatorEmail'];

        $this->sendEmail($toEmail, $cc, $fromEmail, $fromPass, $fromName,$replyTo, $subject, $content);
    }

    //Not in Use
    public function eventVerifyMail($userData)
    {
        $mailRecord = $this->CI->users_model->searchUserByLoc($userData[0]['eventPlace']);
        $senderUser = 'U-0';

        if($mailRecord['status'] === true)
        {
            $senderUser = 'U-'.$mailRecord['userData']['userId'];
        }
        $userData['senderUser'] = $senderUser;

        $data['mailData'] = $userData;

        $content = $this->CI->load->view('emailtemplates/eventVerifyMailView', $data, true);

        $fromEmail = DEFAULT_SENDER_EMAIL;
        $fromPass = DEFAULT_SENDER_PASS;
        $replyTo = $fromEmail;

        //$fromEmail = 'events@doolally.in';

        $cc        = implode(',',$this->CI->config->item('ccList'));
        $fromName  = 'Doolally';

        $subject = 'Event Details';
        $toEmail = 'events@doolally.in';

        if($mailRecord['status'] === true)
        {
            $toEmail = $mailRecord['userData']['emailId'];
        }

        $this->sendEmail($toEmail, $cc, $fromEmail, $fromPass, $fromName,$replyTo, $subject, $content);
    }

    //Not in Use
    public function eventCancelMail($userData)
    {
        $mailRecord = $this->CI->users_model->searchUserByLoc($userData[0]['eventPlace']);

        $data['mailData'] = $userData;

        $content = $this->CI->load->view('emailtemplates/eventCancelMailView', $data, true);

        $fromEmail = DEFAULT_SENDER_EMAIL;
        $fromPass = DEFAULT_SENDER_PASS;
        $replyTo = $fromEmail;

       // $fromEmail = 'info@doolally.in';
        /*if(isset($userData[0]['creatorEmail']))
        {
            $fromEmail = $userData[0]['creatorEmail'];
        }*/
        $cc        = implode(',',$this->CI->config->item('ccList'));
        $fromName  = 'Doolally';

        $subject = 'Event Cancel';
        $toEmail = 'events@doolally.in';

        if($mailRecord['status'] === true)
        {
            $toEmail = $mailRecord['userData']['emailId'];
        }

        $this->sendEmail($toEmail, $cc, $fromEmail, $fromPass, $fromName,$replyTo, $subject, $content);
    }

    //Done
    public function eventCancelUserMail($userData)
    {
        $fromEmail = DEFAULT_SENDER_EMAIL;
        $fromPass = DEFAULT_SENDER_PASS;
        $replyTo = $fromEmail;
        $senderPhone = '9999999999';
        $cc = implode(',',$this->CI->config->item('ccList'));
        if(isset($userData['fromEmail']) && isset($userData['fromPass']))
        {
            $fromEmail = $userData['fromEmail'];
            $fromPass = $userData['fromPass'];
            $senderName = 'Doolally';
            $senderEmail = $fromEmail;
            $senderUser = $this->CI->users_model->getSenderUsername($userData['fromEmail']);
            if(isset($senderUser) && myIsArray($senderUser))
            {
                $senderName = $senderUser['firstName'];
                $senderPhone = $senderUser['mobNum'];
            }
            if($senderEmail == DEFAULT_COMM_EMAIL)
            {
                $commDetail = $this->CI->users_model->searchUserByLoc($userData[0]['eventPlace']);
                if($commDetail['status'] === true)
                {
                    $cc .= ','.$commDetail['userData']['emailId'];
                    $senderName = ucfirst(trim($commDetail['userData']['firstName']));
                    $senderEmail = $commDetail['userData']['emailId'];
                    $senderPhone = $commDetail['userData']['mobNum'];
                }
            }
        }
        else
        {
            $mailRecord = $this->CI->users_model->searchUserByLoc($userData[0]['eventPlace']);
            if($mailRecord['status'] === true)
            {
                $senderName = $mailRecord['userData']['firstName'];
                $senderEmail = $mailRecord['userData']['emailId'];
                $cc .= ','.$mailRecord['userData']['emailId'];
            }
            else
            {
                $senderName = 'Doolally';
                $senderEmail = DEFAULT_SENDER_EMAIL;
            }

        }

        $userData['senderName'] = $senderName;
        $userData['senderEmail'] = $senderEmail;
        $userData['senderPhone'] = $senderPhone;

        $data['mailData'] = $userData;

        $content = $this->CI->load->view('emailtemplates/eventCancelUserMailView', $data, true);


        /*if(isset($mailRecord['userData']['emailId']) && isStringSet($mailRecord['userData']['emailId']))
        {
            $replyTo = $mailRecord['userData']['emailId'];
        }*/
        /*if(isset($mailRecord['userData']['gmailPass']))
        {
            $fromPass = $mailRecord['userData']['gmailPass'];
        }*/

        /*$extraCc = getExtraCCEmail($fromEmail);
        if(isStringSet($extraCc))
        {
            $cc = $cc.','.$extraCc;
        }*/
        $fromName  = 'Doolally';
        if(isset($senderName) && isStringSet($senderName))
        {
            $fromName = ucfirst($senderName);
        }

        $subject = $userData[0]['eventName'].' has been cancelled';
        $toEmail = $userData[0]['creatorEmail'];

        $this->sendEmail($toEmail, $cc, $fromEmail, $fromPass, $fromName,$replyTo, $subject, $content);
    }

    public function attendeeMojoCancelMail($userData)
    {
        $fromEmail = DEFAULT_SENDER_EMAIL;
        $fromPass = DEFAULT_SENDER_PASS;
        $replyTo = $fromEmail;
        $senderPhone = '9999999999';
        if(isset($userData['fromEmail']) && isset($userData['fromPass']))
        {
            $fromEmail = $userData['fromEmail'];
            $fromPass = $userData['fromPass'];
            $senderName = 'Doolally';
            $senderEmail = $fromEmail;
            $senderUser = $this->CI->users_model->getSenderUsername($userData['fromEmail']);
            if(isset($senderUser) && myIsArray($senderUser))
            {
                $senderName = $senderUser['firstName'];
                $senderPhone = $senderUser['mobNum'];
            }
        }
        else
        {
            $mailRecord = $this->CI->users_model->searchUserByLoc($userData['eventPlace']);
            if($mailRecord['status'] === true)
            {
                $senderName = $mailRecord['userData']['firstName'];
                $senderEmail = $mailRecord['userData']['emailId'];
            }
            else
            {
                $senderName = 'Doolally';
                $senderEmail = DEFAULT_SENDER_EMAIL;
            }

        }

        $userData['senderName'] = $senderName;
        $userData['senderEmail'] = $senderEmail;
        $userData['senderPhone'] = $senderPhone;

        $data['mailData'] = $userData;

        $content = $this->CI->load->view('emailtemplates/attendeeMojoCancelMailView', $data, true);

        $cc        = implode(',',$this->CI->config->item('ccList'));
        $extraCc = getExtraCCEmail($fromEmail);
        if(isStringSet($extraCc))
        {
            $cc = $cc.','.$extraCc;
        }
        $fromName  = $senderName;

        $subject = $userData['eventName'].' has been cancelled by the organiser';
        $toEmail = $userData['emailId'];

        $this->sendEmail($toEmail, $cc, $fromEmail, $fromPass, $fromName,$replyTo, $subject, $content);
    }

    //Done Will change after getting text
    public function attendeeCancelMail($userData)
    {
        $fromEmail = DEFAULT_SENDER_EMAIL;
        $fromPass = DEFAULT_SENDER_PASS;
        $replyTo = $fromEmail;
        $senderPhone = '9999999999';
        $cc        = implode(',',$this->CI->config->item('ccList'));
        if(isset($userData['fromEmail']) && isset($userData['fromPass']))
        {
            $fromEmail = $userData['fromEmail'];
            $fromPass = $userData['fromPass'];
            $senderName = 'Doolally';
            $senderEmail = $fromEmail;
            $senderUser = $this->CI->users_model->getSenderUsername($userData['fromEmail']);
            if(isset($senderUser) && myIsArray($senderUser))
            {
                $senderName = $senderUser['firstName'];
                $senderPhone = $senderUser['mobNum'];
            }
            if($senderEmail == DEFAULT_COMM_EMAIL)
            {
                $commDetail = $this->CI->users_model->searchUserByLoc($userData['eventPlace']);
                if($commDetail['status'] === true)
                {
                    $cc .= ','.$commDetail['userData']['emailId'];
                    $senderName = ucfirst(trim($commDetail['userData']['firstName']));
                    $senderEmail = $commDetail['userData']['emailId'];
                    $senderPhone = $commDetail['userData']['mobNum'];
                }
            }
        }
        else
        {
            $mailRecord = $this->CI->users_model->searchUserByLoc($userData['eventPlace']);
            if($mailRecord['status'] === true)
            {
                $senderName = $mailRecord['userData']['firstName'];
                $senderEmail = $mailRecord['userData']['emailId'];
                $cc .= ','.$mailRecord['userData']['emailId'];
            }
            else
            {
                $senderName = 'Doolally';
                $senderEmail = DEFAULT_SENDER_EMAIL;
            }

        }

        $userData['senderName'] = $senderName;
        $userData['senderEmail'] = $senderEmail;
        $userData['senderPhone'] = $senderPhone;

        $data['mailData'] = $userData;

        $content = $this->CI->load->view('emailtemplates/attendeeCancelMailView', $data, true);


        /*$extraCc = getExtraCCEmail($fromEmail);
        if(isStringSet($extraCc))
        {
            $cc = $cc.','.$extraCc;
        }*/
        $fromName  = $senderName;

        $subject = $userData['eventName'].' has been cancelled by the organiser';
        $toEmail = $userData['emailId'];

        $this->sendEmail($toEmail, $cc, $fromEmail, $fromPass, $fromName,$replyTo, $subject, $content);
    }

    //Done
    public function eventApproveMail($userData)
    {
        $senderUser = $this->CI->users_model->getSenderUsername($userData['senderEmail']);
        $commDetail = $this->CI->users_model->searchUserByLoc($userData[0]['eventPlace']);
        if(isset($senderUser) && myIsArray($senderUser))
        {
            $userData['senderPhone'] = $senderUser['mobNum'];
        }
        else
        {
            $userData['senderPhone'] = '9999999999';
        }
        if($userData['eventStatus'] != 'reviewed')
        {
            $data['orgCode'] = $this->generateCustomOrgCode($userData[0]['eventId'],$userData[0]['eventDate'],$userData[0]['startTime'],"500",$userData[0]['eventPlace']);
        }

        $fromEmail = DEFAULT_SENDER_EMAIL;
        $fromPass = DEFAULT_SENDER_PASS;
        $replyTo = $fromEmail;

        if(isset($userData['fromEmail']) && isset($userData['fromPass']))
        {
            $fromEmail = $userData['fromEmail'];
            $fromPass = $userData['fromPass'];
            $replyTo = $userData['fromEmail'];
        }

        if($fromEmail == DEFAULT_COMM_EMAIL && $commDetail['status'] === true)
        {
            $userData['senderName'] = ucfirst(trim($commDetail['userData']['firstName']));
            $userData['senderEmail'] = $commDetail['userData']['emailId'];
        }
        //$userData['senderPhone'] = $phons[ucfirst($userData['senderName'])];
        $data['mailData'] = $userData;

        $content = $this->CI->load->view('emailtemplates/eventApproveMailView', $data, true);

        /*if(isset($userData['senderEmail']) && isStringSet($userData['senderEmail']))
        {
            $replyTo = $userData['senderEmail'];
            $userInfo = $this->CI->login_model->checkEmailSender($userData['senderEmail']);
            if(isset($userInfo) && myIsArray($userInfo))
            {
                $fromPass = $userInfo['gmailPass'];
                $fromEmail = $userData['senderEmail'];
            }
        }*/
        $cc        = implode(',',$this->CI->config->item('ccList'));
        /*$extraCc = getExtraCCEmail($fromEmail);
        if(isStringSet($extraCc))
        {
            $cc = $cc.','.$extraCc;
        }*/

        if($commDetail['status'] === true)
        {
            if($fromEmail != $commDetail['userData']['emailId'])
            {
                $cc .= ','.$commDetail['userData']['emailId'];
            }
        }

        $fromName  = 'Doolally';
        if( $fromEmail == DEFAULT_COMM_EMAIL && $commDetail['status'] === true)
        {
            $fromName = ucfirst(trim($commDetail['userData']['firstName']));
        }
        elseif(isset($userData['senderName']) && isStringSet($userData['senderName']))
        {
            $fromName = ucfirst($userData['senderName']);
        }

        $subject = 'Event Approved';
        if($userData['eventStatus'] == 'Reviewed')
        {
            $subject = 'Event Reviewed';
        }
        $toEmail = $userData[0]['creatorEmail'];

        $this->sendEmail($toEmail, $cc, $fromEmail, $fromPass, $fromName,$replyTo, $subject, $content);
    }

    //Done
    public function eventDeclineMail($userData)
    {
        $senderUser = $this->CI->users_model->getSenderUsername($userData['senderEmail']);
        if(isset($senderUser) && myIsArray($senderUser))
        {
            $userData['senderPhone'] = $senderUser['mobNum'];
        }
        else
        {
            $userData['senderPhone'] = '9999999999';
        }
        //$userData['senderPhone'] = $phons[$userData['senderName']];
        $data['mailData'] = $userData;
        if(isset($userData[0]['eventPlace']))
        {
            $commDetail = $this->CI->users_model->searchUserByLoc($userData[0]['eventPlace']);
            if($commDetail['status'] === true)
            {
                $data['senderName'] = ucfirst(trim($commDetail['userData']['firstName']));
            }
        }

        $content = $this->CI->load->view('emailtemplates/eventDeclineMailView', $data, true);

        $fromEmail = DEFAULT_SENDER_EMAIL;
        $fromPass = DEFAULT_SENDER_PASS;
        $replyTo = $fromEmail;

        if(isset($userData['fromEmail']) && isset($userData['fromPass']))
        {
            $fromEmail = $userData['fromEmail'];
            $fromPass = $userData['fromPass'];
            $replyTo = $userData['fromEmail'];
        }

        /*if(isset($userData['senderEmail']) && isStringSet($userData['senderEmail']))
        {
            $replyTo = $userData['senderEmail'];
            $userInfo = $this->CI->login_model->checkEmailSender($userData['senderEmail']);
            if(isset($userInfo) && myIsArray($userInfo))
            {
                $fromPass = $userInfo['gmailPass'];
                $fromEmail = $userData['senderEmail'];
            }
        }*/

        $cc        = implode(',',$this->CI->config->item('ccList'));
        /*$extraCc = getExtraCCEmail($fromEmail);
        if(isStringSet($extraCc))
        {
            $cc = $cc.','.$extraCc;
        }*/
        if(isset($commDetail) && $commDetail['status'] === true)
        {
            if($fromEmail != $commDetail['userData']['emailId'])
            {
                $cc .= ','.$commDetail['userData']['emailId'];
            }
        }
        $fromName  = 'Doolally';
        if( $fromEmail == DEFAULT_COMM_EMAIL && isset($commDetail) && $commDetail['status'] === true)
        {
            $fromName = ucfirst(trim($commDetail['userData']['firstName']));
        }
        elseif(isset($userData['senderName']) && isStringSet($userData['senderName']))
        {
            $fromName = $userData['senderName'];
        }

        $subject = 'Sorry, '.$userData[0]['eventName'].' has not been approved';
        $toEmail = $userData[0]['creatorEmail'];

        $this->sendEmail($toEmail, $cc, $fromEmail, $fromPass, $fromName,$replyTo, $subject, $content);
    }

    //Done
    public function newEventMail($userData)
    {
        $fromEmail = DEFAULT_SENDER_EMAIL;
        $fromPass = DEFAULT_SENDER_PASS;
        $replyTo = $fromEmail;
        $senderPhone = '9999999999';
        if(isset($userData['fromEmail']) && isset($userData['fromPass']))
        {
            $fromEmail = $userData['fromEmail'];
            $fromPass = $userData['fromPass'];
            $senderName = 'Doolally';
            $senderEmail = $fromEmail;
            $senderUser = $this->CI->users_model->getSenderUsername($userData['fromEmail']);
            if(isset($senderUser) && myIsArray($senderUser))
            {
                $senderName = $senderUser['firstName'];
                $senderPhone = $senderUser['mobNum'];
            }
        }
        else
        {
            $mailRecord = $this->CI->users_model->searchUserByLoc($userData['eventPlace']);
            if($mailRecord['status'] === true)
            {
                $senderName = $mailRecord['userData']['firstName'];
                $senderEmail = $mailRecord['userData']['emailId'];
            }
            else
            {
                $senderName = 'Doolally';
                $senderEmail = DEFAULT_SENDER_EMAIL;
            }

        }

        $userData['senderName'] = $senderName;
        $userData['senderEmail'] = $senderEmail;
        $userData['senderPhone'] = $senderPhone;

        $data['mailData'] = $userData;

        $content = $this->CI->load->view('emailtemplates/newEventMailView', $data, true);

        $cc        = implode(',',$this->CI->config->item('ccList'));
        $extraCc = getExtraCCEmail($fromEmail);
        if(isStringSet($extraCc))
        {
            $cc = $cc.','.$extraCc;
        }
        $fromName  = $senderName;

        $subject = 'Event Details';
        $toEmail = $userData['creatorEmail'];

        $this->sendEmail($toEmail, $cc, $fromEmail, $fromPass, $fromName,$replyTo, $subject, $content);
    }

    //Done
    public function membershipRenewSendMail($userData)
    {
        $userData['breakCode'] = $this->generateBreakfastTwoCode($userData['mugId']);
        $data['mailData'] = $userData;

        if(isset($userData['homeBase']))
        {
            $commDetail = $this->CI->users_model->searchUserByLoc($userData['homeBase']);
            if($commDetail['status'] === true)
            {
                $data['fromName'] = ucfirst(trim($commDetail['userData']['firstName']));
            }
            else
            {
                $data['fromName'] = ucfirst($this->CI->userFirstName);
            }
        }

        $content = $this->CI->load->view('emailtemplates/membershipRenewMailView', $data, true);

        $fromEmail = DEFAULT_SENDER_EMAIL;
        $fromPass = DEFAULT_SENDER_PASS;
        $replyTo = $fromEmail;

        if(isset($userData['fromEmail']) && isset($userData['fromPass']))
        {
            $fromEmail = $userData['fromEmail'];
            $fromPass = $userData['fromPass'];
            $replyTo = $userData['fromEmail'];
        }

        /*if(isset($this->CI->userEmail))
        {
            $replyTo = $this->CI->userEmail;
            $userInfo = $this->CI->login_model->checkEmailSender($this->CI->userEmail);
            if(isset($userInfo) && myIsArray($userInfo))
            {
                $fromPass = $userInfo['gmailPass'];
                $fromEmail = $this->CI->userEmail;
            }
        }*/
        $cc        = implode(',',$this->CI->config->item('ccList'));
        /*$extraCc = getExtraCCEmail($fromEmail);
        if(isStringSet($extraCc))
        {
            $cc = $cc.','.$extraCc;
        }*/

        $fromName  = 'Doolally';
        if(isset($commDetail) && myIsArray($commDetail) && $commDetail['status'] === true)
        {
            if($fromEmail != $commDetail['userData']['emailId'])
            {
                $cc .= ','.$commDetail['userData']['emailId'];
                if($fromEmail == DEFAULT_COMM_EMAIL)
                {
                    $fromName = ucfirst(trim($commDetail['userData']['firstName']));
                }
            }
            else
            {
                $fromName = ucfirst(trim($commDetail['userData']['firstName']));
            }
        }
        /*if(isset($this->CI->userFirstName))
        {
            $fromName = ucfirst($this->CI->userFirstName);
        }*/
        $subject = 'Mug #'.$userData['mugId'].' has been Renewed';
        $toEmail = $userData['emailId'];

        $this->sendEmail($toEmail, $cc, $fromEmail, $fromPass, $fromName,$replyTo, $subject, $content);
    }

    public function generateBreakfastCode($mugId)
    {
        $allCodes = $this->CI->offers_model->getAllCodes();
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
                'offerType' => 'Breakfast',
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
                'offerType' => 'Breakfast',
                'offerLoc' => null,
                'offerMug' => $mugId,
                'isRedeemed' => 0,
                'ifActive' => 1,
                'createDateTime' => date('Y-m-d H:i:s'),
                'useDateTime' => null
            );
        }

        $this->CI->offers_model->setSingleCode($toBeInserted);
        return 'DO-'.$newCode;
    }

    public function otpSendMail($userData)
    {
        $data['mailData'] = $userData;

        $content = $this->CI->load->view('emailtemplates/otpMailView', $data, true);

        $fromEmail = DEFAULT_SENDER_EMAIL;
        $fromPass = DEFAULT_SENDER_PASS;
        $replyTo = $fromEmail;
        $cc = '';
        $fromName  = 'Doolally';

        $subject = 'Your Requested Otp '.$userData['otp'];
        $toEmail = $userData['emailId'];

        $this->sendEmail($toEmail, $cc, $fromEmail, $fromPass, $fromName,$replyTo, $subject, $content);
    }

    //Done
    public function mugEditSendMail($userData)
    {
        $data['mailData'] = $userData;

        $content = $this->CI->load->view('emailtemplates/mugEditMailView', $data, true);

        $fromEmail = DEFAULT_SENDER_EMAIL;
        $fromPass = DEFAULT_SENDER_PASS;
        $replyTo = $fromEmail;
        if(isset($userData['senderEmail']) && isStringSet($userData['senderEmail']))
        {
            $replyTo = $userData['senderEmail'];
            /*$userInfo = $this->CI->login_model->checkEmailSender($userData['senderEmail']);
            if(isset($userInfo) && myIsArray($userInfo))
            {
                $fromPass = $userInfo['gmailPass'];
                $fromEmail = $userData['senderEmail'];
            }*/
        }
        $cc = '';
        $fromName  = 'Doolally';
        if(isset($userData['senderName']) && isStringSet($userData['senderName']))
        {
            $fromName = ucfirst($userData['senderName']);
        }

        $subject = 'Mug Member Edited';
        $toEmail = 'tresha@brewcraftsindia.com';

        $this->sendEmail($toEmail, $cc, $fromEmail, $fromPass, $fromName,$replyTo, $subject, $content);
    }

    public function sendCompOpenMail($userData)
    {
        $data['mailData'] = $userData;

        $content = $this->CI->load->view('emailtemplates/compOpenMailView', $data, true);

        $fromEmail = ADMIN_SENDER_EMAIL;
        $fromPass = ADMIN_SENDER_PASS;
        $replyTo = $fromEmail;
        $cc = '';
        $fromName  = 'Doolally';

        $subject = 'New Complaint #'.$userData['compId'].'-'.$userData['location'];
        $toEmail = $userData['toMail'];

        $this->sendEmail($toEmail, $cc, $fromEmail, $fromPass, $fromName,$replyTo, $subject, $content);
    }

    //Done
    public function checkinMissMail($userData)
    {
        $mailRecord = $this->CI->users_model->searchUserByLoc($userData['locId']);
        $senderName = 'Doolally';
        $senderEmail = DEFAULT_SENDER_EMAIL;
        $fromPass = DEFAULT_SENDER_PASS;
        $replyTo = $senderEmail;

        $toEmail = 'tresha@brewcraftsindia.com';
        if($mailRecord['status'] === true)
        {
            $toEmail = $mailRecord['userData']['emailId'];
        }
        $data['mailData'] = $userData;

        $content = $this->CI->load->view('emailtemplates/checkinMissInfoMailView', $data, true);

        $fromEmail = $senderEmail;

        $cc        = implode(',',$this->CI->config->item('ccList'));
        $extraCc = getExtraCCEmail($fromEmail);
        if(isStringSet($extraCc))
        {
            $cc = $cc.','.$extraCc;
        }
        $fromName  = $senderName;

        $subject = 'Mug #'.$userData['mugId'].' has missing info';

        $this->sendEmail($toEmail, $cc, $fromEmail, $fromPass, $fromName,$replyTo, $subject, $content);
    }

    //Done
    public function checkinInfoFillMail($userData)
    {
        $mailRecord = $this->CI->users_model->searchUserByLoc($userData['locId']);
        $senderName = 'Doolally';
        $senderEmail = DEFAULT_SENDER_EMAIL;
        $fromPass = DEFAULT_SENDER_PASS;
        $replyTo = $senderEmail;

        $toEmail = 'tresha@brewcraftsindia.com';
        if($mailRecord['status'] === true)
        {
            $toEmail = $mailRecord['userData']['emailId'];
        }
        $data['mailData'] = $userData;

        $content = $this->CI->load->view('emailtemplates/checkinFilledMailView', $data, true);

        $fromEmail = $senderEmail;

        $cc        = implode(',',$this->CI->config->item('ccList'));
        $extraCc = getExtraCCEmail($fromEmail);
        if(isStringSet($extraCc))
        {
            $cc = $cc.','.$extraCc;
        }
        $fromName  = $senderName;

        $subject = 'Mug #'.$userData['mugId'].' has missing info';

        $this->sendEmail($toEmail, $cc, $fromEmail, $fromPass, $fromName,$replyTo, $subject, $content);
    }

    //Done
    public function instamojoFailMail($userData)
    {
        $data['mailData'] = $userData;

        $content = $this->CI->load->view('emailtemplates/mugEditMailView', $data, true);

        $fromEmail = DEFAULT_SENDER_EMAIL;
        $fromPass = DEFAULT_SENDER_PASS;
        $replyTo = $fromEmail;

        $cc = '';
        $fromName  = 'Doolally';

        $subject = 'Unknown Mug Renewed via Instamojo';
        $toEmail = 'tresha@brewcraftsindia.com';

        $this->sendEmail($toEmail, $cc, $fromEmail, $fromPass, $fromName,$replyTo, $subject, $content);
    }

    public function attendeeChangeMail($userData)
    {
        $phons = $this->CI->config->item('phons');
        $mailRecord = $this->CI->users_model->searchUserByLoc($userData['eventPlace']);
        $senderName = 'Doolally';
        $senderEmail = DEFAULT_SENDER_EMAIL;
        $fromPass = DEFAULT_SENDER_PASS;
        $replyTo = $senderEmail;

        $senderPhone = $phons['Tresha'];

        if($mailRecord['status'] === true)
        {
            $senderName = $mailRecord['userData']['firstName'];
            $replyTo = $mailRecord['userData']['emailId'];
            //$senderEmail = $mailRecord['userData']['emailId'];
            if(isset($phons[ucfirst($senderName)]))
            {
                $senderPhone = $phons[ucfirst($senderName)];
            }
            else
            {
                $senderPhone = '9999999999';
            }
            //$senderPhone = $phons[$senderName];
            //$fromPass = $mailRecord['userData']['gmailPass'];
        }
        $userData['senderName'] = $senderName;
        $userData['senderEmail'] = $replyTo;
        $userData['senderPhone'] = $senderPhone;
        $data['mailData'] = $userData;

        $content = $this->CI->load->view('emailtemplates/attendeeChangeMailView', $data, true);

        $fromEmail = $senderEmail;

        if(isset($userData['fromEmail']) && isset($userData['fromPass']))
        {
            $fromEmail = $userData['fromEmail'];
            $fromPass = $userData['fromPass'];
            $replyTo = $userData['fromEmail'];
        }

        $cc        = implode(',',$this->CI->config->item('ccList'));
        /*$extraCc = getExtraCCEmail($fromEmail);
        if(isStringSet($extraCc))
        {
            $cc = $cc.','.$extraCc;
        }*/
        //$commDetail = $this->CI->users_model->searchUserByLoc($userData['eventPlace']);
        if($mailRecord['status'] === true)
        {
            $cc .= ','.$mailRecord['userData']['emailId'];
        }
        $fromName  = $senderName;

        $subject = $userData['eventName'].' has been Rescheduled';
        $toEmail = $userData['emailId'];

        $this->sendEmail($toEmail, $cc, $fromEmail, $fromPass, $fromName,$replyTo, $subject, $content);
    }

    public function eventEditToOrganiserMail($userData,$commPlace)
    {
        $mailRecord = $this->CI->users_model->searchUserByLoc($commPlace);
        $senderUser = 'U-0';

        if($mailRecord['status'] === true)
        {
            $senderUser = 'U-'.$mailRecord['userData']['userId'];
        }
        $userData['senderUser'] = $senderUser;

        $userData['senderName'] = ucfirst(trim($mailRecord['userData']['firstName']));
        $data['mailData'] = $userData;

        $content = $this->CI->load->view('emailtemplates/eventEditOrganiserMailView', $data, true);

        $fromEmail = DEFAULT_SENDER_EMAIL;
        $fromPass = DEFAULT_SENDER_PASS;
        $replyTo = $mailRecord['userData']['emailId'];

        $cc        = implode(',',$this->CI->config->item('ccList'));
        $cc .= ','.$mailRecord['userData']['emailId'];
        /*$extraCc = getExtraCCEmail($replyTo);
        if(isStringSet($extraCc))
        {
            $cc = $cc.','.$extraCc;
        }*/
        $fromName  = 'Doolally';
        if(isset($mailRecord['userData']['firstName']))
        {
            $fromName = $mailRecord['userData']['firstName'];
        }

        if(isset($userData['oldEventName']))
        {
            $subject = $userData['oldEventName'].' Event Modified';
        }
        else
        {
            $subject = 'Event Modified';
        }
        $toEmail = 'events@brewcraftsindia.com';

        if(isset($userData['orgEmail']))
        {
            $toEmail = $userData['orgEmail'];
        }

        $this->sendEmail($toEmail, $cc, $fromEmail, $fromPass, $fromName,$replyTo, $subject, $content);
    }

    public function lowResImageFailMail($userData)
    {
        $mailRecord = $this->CI->users_model->searchUserByLoc($userData['locId']);
        $senderName = 'Doolally';
        $senderEmail = DEFAULT_SENDER_EMAIL;
        $fromPass = DEFAULT_SENDER_PASS;
        $replyTo = $senderEmail;

        $toEmail = 'tresha@brewcraftsindia.com';
        if($mailRecord['status'] === true)
        {
            $toEmail = $mailRecord['userData']['emailId'];
        }
        $data['mailData'] = $userData;

        $content = $this->CI->load->view('emailtemplates/lowResImageMailView', $data, true);

        $fromEmail = $senderEmail;

        $cc        = implode(',',$this->CI->config->item('ccList'));
        /*$extraCc = getExtraCCEmail($fromEmail);
        if(isStringSet($extraCc))
        {
            $cc = $cc.','.$extraCc;
        }*/
        $fromName  = $senderName;

        $subject = 'Whatsapp Image size more than 300KB';

        $this->sendEmail($toEmail, $cc, $fromEmail, $fromPass, $fromName,$replyTo, $subject, $content);
    }

    public function eventExtraToMaintMail($userData)
    {
        $senderName = 'Doolally';
        $senderEmail = DEFAULT_SENDER_EMAIL;
        $fromPass = DEFAULT_SENDER_PASS;
        $replyTo = $senderEmail;

        $toEmail = array('mandar@brewcraftsindia.com','anil.jadhav@brewcraftsindia.com');
        $data['mailData'] = $userData;

        $content = $this->CI->load->view('emailtemplates/eventAccessMailView', $data, true);

        $fromEmail = $senderEmail;

        $cc        = implode(',',$this->CI->config->item('ccList'));
        /*$extraCc = getExtraCCEmail($fromEmail);
        if(isStringSet($extraCc))
        {
            $cc = $cc.','.$extraCc;
        }*/
        $fromName  = $senderName;

        $subject = 'Maintenance person required';

        $this->sendEmail($toEmail, $cc, $fromEmail, $fromPass, $fromName,$replyTo, $subject, $content);
    }

    public function sendEmail($to, $cc = '', $from, $fromPass, $fromName,$replyTo, $subject, $content, $attachment = array())
    {
        //Create the Transport
        /*$CI =& get_instance();
        $CI->load->library('swift_mailer/swift_required.php');*/

        require_once APPPATH.'libraries/swift_mailer/swift_required.php';

        $transport = Swift_SmtpTransport::newInstance ('smtp.gmail.com', 465, 'ssl')
            ->setUsername($from)
            ->setPassword($fromPass);
        //$transport = Swift_SendmailTransport::newInstance('/usr/sbin/sendmail -bs');

        $mailer = Swift_Mailer::newInstance($transport);

        //Create a message
        $message = null;
        $message = Swift_Message::newInstance($subject)
            ->setSubject($subject)
            ->setReplyTo($replyTo)
            ->setReadReceiptTo($from)
            //->setCc($cc)
            ->setFrom(array($from => $fromName))
            ->setSender($replyTo)
            ->setTo($to) ->setBody($content, 'text/html');

        if($cc != '')
        {
            $message->setBcc(explode(',',$cc));
        }
        if(isset($attachment) && myIsMultiArray($attachment))
        {
            foreach($attachment as $key)
            {
                if($key != '')
                {
                    $message->attach(Swift_Attachment::fromPath($key));
                }
            }
        }
        //$message->attach($attachment);
        //Send the message
        $failedId = array();
        $status = 'Success';
        $errorMsg = implode(',',$failedId);

        try
        {
            $result = $mailer->send($message,$failedId);
            if(!$result)
            {
                $status = 'Failed';
                $errorMsg = implode(',',$failedId);
            }
        }
        catch(Swift_TransportException $st)
        {
            $status = 'Login Failed';
            $errorMsg = $st->getMessage();
        }
        catch(Exception $ex)
        {
            $status = 'Failed';
            $errorMsg = $ex->getMessage();
        }

        if(is_array($to))
        {
            $to = implode(',',$to);
        }

        $logDetails = array(
            'messageId' => $message->getId(),
            'sendTo' => $to,
            'sendFrom' => $from,
            'sendFromName' => $fromName,
            'ccList' => $cc,
            'replyTo' => $replyTo,
            'mailSubject' => $subject,
            'mailBody' => $content,
            'attachments' => implode(',',$attachment),
            'sendStatus' => $status,
            'failIds' => $errorMsg,
            'sendDateTime' => date('Y-m-d H:i:s')
        );

        $this->CI->mailers_model->saveSwiftMailLog($logDetails);
        return $status;
        /*$CI =& get_instance();
        $CI->load->library('email');
        $config['mailtype'] = 'html';
        $CI->email->clear(true);
        $CI->email->initialize($config);
        $CI->email->from($from, $fromName);
        $CI->email->to($to);
        if ($cc != '') {
            $CI->email->bcc($cc);
        }
        if(isset($attachment) && myIsArray($attachment))
        {
            foreach($attachment as $key)
            {
                $CI->email->attach($key);
            }
        }

        $CI->email->subject($subject);
        $CI->email->message($content);
        return $CI->email->send();*/
    }

    public function generateBreakfastTwoCode($mugId)
    {
        $allCodes = $this->CI->offers_model->getAllCodes();
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

        $this->CI->offers_model->setSingleCode($toBeInserted);
        return 'BR-'.$newCode;
    }

    public function generateCustomOrgCode($eveId,$eveDate,$eveTime,$cusAmt,$offerLoc)
    {
        $allCodes = $this->CI->offers_model->getAllCodes();
        $usedCodes = array();
        $toBeInserted = array();
        $dt = $eveDate.' '.$eveTime;
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
                'offerType' => 'Rs '.$cusAmt,
                'offerLoc' => $offerLoc,
                'offerMug' => '0',
                'offerEvent' => $eveId,
                'bookerPaymentId' => null,
                'isRedeemed' => 0,
                'ifActive' => 1,
                'createDateTime' => date('Y-m-d H:i:s'),
                'expiryDateTime' => date('Y-m-d H:i',strtotime('+12 hours', strtotime($dt))),
                'validFromDate' => $eveDate,
                'validFromTime' => $eveTime,
                'useDateTime' => null,
                'isOrganiser' => 1
            );
        }
        else
        {
            $newCode = mt_rand(1000,99999);
            $toBeInserted = array(
                'offerCode' => $newCode,
                'offerType' => 'Rs '.$cusAmt,
                'offerLoc' => $offerLoc,
                'offerMug' => '0',
                'offerEvent' => $eveId,
                'bookerPaymentId' => null,
                'isRedeemed' => 0,
                'ifActive' => 1,
                'createDateTime' => date('Y-m-d H:i:s'),
                'expiryDateTime' => date('Y-m-d H:i',strtotime('+12 hours', strtotime($dt))),
                'validFromDate' => $eveDate,
                'validFromTime' => $eveTime,
                'useDateTime' => null,
                'isOrganiser' => 1
            );
        }

        $this->CI->offers_model->setSingleCode($toBeInserted);
        return 'ORG-'.$newCode;
    }

}
/* End of file */