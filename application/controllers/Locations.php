<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Locations
 * @property Locations_Model $locations_model
*/

class Locations extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('locations_model');
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

        $data['storeLocations'] = $this->locations_model->getAllLocations();

        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);

        $this->load->view('LocationsView', $data);
	}

    public function addNewLocation()
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }

        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);

        $this->load->view('LocationsAddView', $data);
    }

    public function editExistingUser($locId)
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }

        $userInfo = $this->locations_model->getLocationDetailsById($locId);

        $data['locInfo'] = $userInfo;

        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);

        $this->load->view('LocationsEditView', $data);
    }

    public function saveOrUpdateLocation()
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $post = $this->input->post();

        if(!isset($post['locUniqueLink']))
        {
            $post['locUniqueLink'] = getUniqueLink($post['locName']);
        }
        $userExists = $this->locations_model->getLocDetailsByUniqueLink($post['locUniqueLink']);
        
        if($userExists['status'] === false)
        {
            $this->locations_model->saveLocationRecord($post);
        }
        else
        {
            $this->locations_model->updateLocationRecord($post);
        }
        redirect(base_url().'locations');
    }

    public function checkLocationByUniqueLink($locName)
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $data = array();
        $locUniqueLink = getUniqueLink($locName);
        $locExists = $this->locations_model->getLocDetailsByUniqueLink($locUniqueLink);

        if($locExists['status'] === true)
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Location Already Exists!';
        }
        else
        {
            $data['status'] = true;
        }
        echo  json_encode($data);
    }

    public function deleteLocationData($locId)
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $locExists = $this->locations_model->getLocationDetailsById($locId);

        if($locExists['status'] === false)
        {
            redirect(base_url().'locations');
        }
        else
        {
            $this->locations_model->deleteLocationRecord($locId);
        }
        redirect(base_url().'locations');
    }
}
