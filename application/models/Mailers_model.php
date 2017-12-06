<?php

/**
 * Class Mailers_Model
 * @property Mydatafetch_library $mydatafetch_library
 * @property Generalfunction_library $generalfunction_library
 */
class Mailers_Model extends CI_Model
{
	function __construct()
	{
		parent::__construct();

        $this->load->library('mydatafetch_library');
	}

    public function getAllTemplatesByType($mailType)
    {
        $query = "SELECT mailSubject, mailBody, mailType "
            ."FROM mailtemplates "
            ."where mailType = ".$mailType;

        $result = $this->db->query($query)->result_array();

        $data['mailData'] = $result;
        if(myIsArray($result))
        {
            $data['status'] = true;
        }
        else
        {
            $data['status'] = false;
        }

        return $data;
    }
    public function getAllPressEmails()
    {
        $query = "SELECT pmm.id, pmm.publication, pmm.pressName, pmm.pressEmail, pmm.pressMailType,"
                ." ptm.catName"
                ." FROM pressmailmaster pmm"
                ." LEFT JOIN presstypemaster ptm ON ptm.id = pmm.pressMailType";

        $result = $this->db->query($query)->result_array();

        $data['mailData'] = $result;
        if(myIsArray($result))
        {
            $data['status'] = true;
        }
        else
        {
            $data['status'] = false;
        }

        return $data;
    }
    function fetchPressCats()
    {
        $this->db->simple_query('SET SESSION group_concat_max_len=1000000');
        $query = "SELECT GROUP_CONCAT(pmm.pressEmail) as 'emails', ptm.catName
                    FROM pressmailmaster pmm
                    LEFT JOIN presstypemaster ptm ON ptm.id = pmm.pressMailType
                    GROUP BY ptm.id";
        $result = $this->db->query($query)->result_array();

        return $result;
    }
    public function getPressMailTypes()
    {
        $query = "SELECT * FROM presstypemaster";

        $result = $this->db->query($query)->result_array();

        return $result;
    }
    public function getPressMailById($pressId)
    {
        $query = "SELECT * FROM pressmailmaster 
                  WHERE id = ".$pressId;

        $result = $this->db->query($query)->row_array();

        return $result;
    }
    public function getAllTemplates()
    {
        $query = "SELECT * FROM mailtemplates";

        $result = $this->db->query($query)->result_array();

        return $result;
    }
    public function getTemplateById($tempId)
    {
        $query = "SELECT * FROM mailtemplates 
                  WHERE id = ".$tempId;

        $result = $this->db->query($query)->row_array();

        return $result;
    }
    public function getPressInfoByMail($email)
    {
        $query = "SELECT pressName "
            ."FROM pressmailmaster "
            ."WHERE pressEmail = '".$email."'";

        $result = $this->db->query($query)->row_array();

        $data = $result;

        return $data;
    }

    public function setMailSend($mugId,$mailType)
    {
        if($mailType == BIRTHDAY_MAIL)
        {
            $details['birthdayMailStatus'] = 1;
            $details['birthMailDate'] = date('Y-m-d');
        }
        else
        {
            $details['mailStatus'] = 1;
            $details['mailDate'] = date('Y-m-d');
        }

        $this->db->where('mugId', $mugId);
        $this->db->update('mugmaster', $details);
        return true;
    }
    
    public function saveMailTemplate($post)
    {
        $this->db->insert('mailtemplates', $post);
        return true;
    }

    public function saveMailType($post)
    {
        $this->db->insert('presstypemaster', $post);
        return true;
    }
    public function savePressEmail($post)
    {
        $this->db->insert('pressmailmaster', $post);
        return true;
    }
    public function deletePressEmail($pressId)
    {
        $this->db->where('id', $pressId);
        $this->db->delete('pressmailmaster');
        return true;
    }
    public function updatePressEmail($details,$pressId)
    {
        $this->db->where('id', $pressId);
        $this->db->update('pressmailmaster',$details);
        return true;
    }
    public function updateTemplate($details,$tempId)
    {
        $this->db->where('id', $tempId);
        $this->db->update('mailtemplates',$details);
        return true;
    }
    public function saveTemplate($post)
    {
        $this->db->insert('mailtemplates', $post);
        return true;
    }

    public function saveSwiftMailLog($post)
    {
        $this->db->insert('swiftmailerlogs', $post);
        return true;
    }

    //Get Beer Olympics Code
    public function getOlympicsCode($mugId)
    {
        $query="SELECT couponCode FROM olympicscouponmaster WHERE ownerDetails LIKE '".$mugId."'";

        $result = $this->db->query($query)->row_array();
        return $result;
    }
    public function getOlympicsRandomCode()
    {
        $query="SELECT id, couponCode FROM olympicscouponmaster WHERE ownerDetails LIKE 'unknown'";

        $result = $this->db->query($query)->row_array();
        return $result;
    }
    public function updateCouponDone($details,$couponId)
    {
        $this->db->where('id', $couponId);
        $this->db->update('olympicscouponmaster',$details);
        return true;
    }
    public function saveWaitMailLog($post)
    {
        $this->db->insert('pendingmailsmaster', $post);
        return true;
    }
    public function getAllPendingMails()
    {
        $query = "SELECT * FROM pendingmailsmaster WHERE isPressMail = 0 AND sendStatus LIKE 'waiting' LIMIT 2";

        $result = $this->db->query($query)->result_array();
        return $result;
    }
    public function getAllPendingPressMails()
    {
        $query = "SELECT * FROM pendingmailsmaster WHERE isPressMail = 1 AND sendStatus LIKE 'waiting' LIMIT 25";

        $result = $this->db->query($query)->result_array();
        return $result;
    }
    public function updateMailDetails($details, $mailId)
    {
        $this->db->where('id',$mailId);
        $this->db->update('pendingmailsmaster', $details);
        return true;
    }
    public function saveDummyMugs($data)
    {
        $this->db->insert_batch('dummymugmaster',$data);
        return true;
    }
}
