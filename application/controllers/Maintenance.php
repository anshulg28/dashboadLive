<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Users
 * @property Users_Model $users_model
*/

class Maintenance extends MY_Controller {

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

        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);

        $this->load->view('maintenance/MainView', $data);
	}

	public function logbook()
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }

        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);

        $this->load->view('maintenance/MainView', $data);
    }

    function checkMaintenanceUser()
    {
        $isValid = FALSE;
        if($this->userType == MAINTENANCE_ADMIN || $this->userType == MAINTENANCE_MANAGER ||
            $this->userType == MAINTENANCE_USER)
        {
            $isValid = TRUE;
        }
        $userInfo = $this->users_model->getUserDetailsById($this->userId);
        if($userInfo['status'] === TRUE)
        {

        }
    }

}
