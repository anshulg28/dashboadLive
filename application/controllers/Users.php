<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Users
 * @property Users_Model $users_model
*/

class Users extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('users_model');
	}
	public function index()
	{
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        if($this->userType == GUEST_USER || $this->userType == OFFERS_USER)
        {
            redirect(base_url());
        }

        $data['userData'] = $this->users_model->getAllUsers();
        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);

        $this->load->view('UsersView', $data);
	}

    public function addNewUser()
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }

        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);

        $this->load->view('UserAddView', $data);
    }

    public function editExistingUser($userId)
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }

        $userInfo = $this->users_model->getUserDetailsById($userId);

        $data['userInfo'] = $userInfo;

        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);

        $this->load->view('UserEditView', $data);
    }

    public function saveOrUpdateUser()
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $post = $this->input->post();

        $userExists = $this->users_model->getUserDetailsByUsername($post['userName']);

        $params = $this->users_model->filterUserParameters($post);

        if($userExists['status'] === false)
        {
            $this->users_model->saveUserRecord($params);
        }
        else
        {
            $this->users_model->updateUserRecord($params);
        }
        redirect(base_url().'users');
    }

    public function checkUserByUsername($userName)
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $data = array();
        $userExists = $this->users_model->getUserDetailsByUsername($userName);

        if($userExists['status'] === true)
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Username Already Taken!';
        }
        else
        {
            $data['status'] = true;
        }
        echo  json_encode($data);
    }

    public function deleteUserData($userId)
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $mugExists = $this->users_model->getUserDetailsById($userId);

        if($mugExists['status'] === false)
        {
            redirect(base_url().'users');
        }
        else
        {
            $this->users_model->deleteUserRecord($userId);
        }
        redirect(base_url().'users');
    }

    public function setUserActive($userId)
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $userExists = $this->users_model->getUserDetailsById($userId);

        if($userExists['status'] === false)
        {
            redirect(base_url().'users');
        }
        else
        {
            $this->users_model->activateUserRecord($userId);
        }
        redirect(base_url().'users');
    }

    public function setUserDeActive($userId)
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $userExists = $this->users_model->getUserDetailsById($userId);

        if($userExists['status'] === false)
        {
            redirect(base_url().'users');
        }
        else
        {
            $this->users_model->deActivateUserRecord($userId);
        }
        redirect(base_url().'users');
    }
}
