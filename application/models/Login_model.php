<?php

/**
 * Class Login_Model
 * @property Mydatafetch_library $mydatafetch_library
 * @property Generalfunction_library $generalfunction_library
 */
class Login_Model extends CI_Model
{
	function __construct()
	{
		parent::__construct();

        $this->load->library('mydatafetch_library');
	}

    public function checkUser($userName, $userPassword)
    {
        $query = "SELECT userId,ifActive,userType, attemptTimes "
            ."FROM doolally_usersmaster "
            ."where userName = '".$userName."' "
            ."AND password = '".$userPassword."' ";

        $result = $this->db->query($query)->row_array();

        $data = $result;
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

    public function checkUsername($userName)
    {
        $query = "SELECT userId,ifActive,attemptTimes "
            ."FROM doolally_usersmaster "
            ."where userName = '".$userName."' ";

        $result = $this->db->query($query)->row_array();

        $data = $result;
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

    public function checkUserByEmail($email)
    {

        $query = "SELECT userId,ifActive,attemptTimes,mobNum "
            ."FROM doolally_usersmaster "
            ."where userType IN(0,1,2,3,5,6) AND emailId = '".$email."'";

        $result = $this->db->query($query)->row_array();

        $data = $result;
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

    public function checkUserByMob($mobNum)
    {

        $query = "SELECT userId,ifActive,attemptTimes,emailId "
            ."FROM doolally_usersmaster "
            ."where userType IN(0,1,2,3,5,6) AND mobNum = '".$mobNum."'";

        $result = $this->db->query($query)->row_array();

        $data = $result;
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

    public function checkUserOtp($mobNum, $otp)
    {
        $query = "SELECT userId,ifActive "
            ."FROM doolally_usersmaster "
            ."where userType IN(0,1,2,3,5,6) AND mobNum = '".$mobNum."' OR emailId = '".$mobNum."' AND userOtp = ".$otp;

        $result = $this->db->query($query)->row_array();

        $data = $result;
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

    public function checkEmailSender($email)
    {

        $query = "SELECT firstName,gmailPass "
            ."FROM doolally_usersmaster "
            ."where userType IN(0,1,2) AND emailId LIKE '".$email."'";

        $result = $this->db->query($query)->row_array();

        return $result;
    }

    public function checkAppUser($userEmail, $userPassword)
    {
        $query = "SELECT userId,ifActive "
            ."FROM doolally_usersmaster "
            ."where emailId = '".$userEmail."' "
            ."AND password = '".$userPassword."' ";

        $result = $this->db->query($query)->row_array();

        $data['userData'] = $result;
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

    public function checkUserByPin($loginPin)
    {
        $query = "SELECT userId, isPinChanged, ifActive "
            ."FROM doolally_usersmaster "
            ."where LoginPin = '".$loginPin."' ";

        $result = $this->db->query($query)->row_array();

        $data = $result;
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

    public function setLastLogin($userId)
    {
        $data = array(
          'lastLogin'=> date('Y-m-d H:i:s')
        );

        $this->db->where('userId', $userId);
        $this->db->update('doolally_usersmaster', $data);
        return true;
    }

    public function updateUserRecord($userId,$data)
    {
        $this->db->where('userId', $userId);
        $this->db->update('doolally_usersmaster', $data);
        return true;
    }

    public function updateUserPass($post)
    {
        $post['updateDate'] = date('Y-m-d H:i:s');
        $post['updatedBy'] = $this->userName;
        $post['password'] = md5($post['password']);

        $this->db->where('userId', $post['userId']);
        $this->db->update('doolally_usersmaster', $post);
        return true;
    }
    public function updateUserPin($post)
    {
        $post['updateDate'] = date('Y-m-d H:i:s');
        $post['updatedBy'] = $this->userName;
        $post['LoginPin'] = md5($post['LoginPin']);


        $this->db->where('userId', $post['userId']);
        $this->db->update('doolally_usersmaster', $post);
        return true;
    }

    function getUserRoles($userId)
    {
        $query = "SELECT modulesAssigned FROM roletomodulemaster WHERE userId = ".$userId;

        $result = $this->db->query($query)->row_array();

        return $result;
    }

    function getAllDashboardUsers()
    {
        $query = "SELECT * "
            ."FROM doolally_usersmaster "
            ."where userType IN(0,1,2,3,5,6)";

        $result = $this->db->query($query)->result_array();

        return $result;
    }

    function saveModuleUser($details)
    {
        $this->db->insert('roletomodulemaster',$details);
    }

}
