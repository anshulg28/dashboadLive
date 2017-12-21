<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Career
 * @property dashboard_model $dashboard_model
 * @property career_model $career_model
 * @property locations_model $locations_model
 */

class Career extends MY_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('career_model');
        $this->load->model('locations_model');
        $this->load->model('dashboard_model');
    }

	public function index()
	{
		$data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }

        $data['careers'] = $this->career_model->getAllCareers();

        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
		$data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
		$data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);

		$this->load->view('CareerView', $data);
	}

	public function addNewJob()
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }

        $data['locs'] = $this->locations_model->getAllActiveLocations();

        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);

        $this->load->view('CareerAddView', $data);
    }

    public function saveJob()
    {
        $post = $this->input->post();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }

        if(isset($post['locId']) && $post['locId'] == 'other')
        {
            $post['locId'] = null;
        }
        $post['insertedBy'] = $this->userId;
        $post['insertedDT'] = date('Y-m-d H:i:s');
        $this->career_model->saveCareerRecord($post);
        $logDetails = array(
            'logMessage' => 'Function: saveJob, User: '.$this->userId,
            'fromWhere' => 'Dashboard',
            'insertedDT' => date('Y-m-d H:i:s')
        );
        $this->dashboard_model->saveDashLogs($logDetails);
        redirect(base_url().'career');
    }

    public function editJob($jobId)
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }

        $data['jobData'] = $this->career_model->getCareerById($jobId);

        $data['locs'] = $this->locations_model->getAllActiveLocations();

        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);

        $this->load->view('CareerEditView', $data);
    }

    public function updateJob($jobId)
    {
        $post = $this->input->post();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }

        if(isset($post['locId']) && $post['locId'] == 'other')
        {
            $post['locId'] = null;
        }
        $post['insertedBy'] = $this->userId;
        $post['insertedDT'] = date('Y-m-d H:i:s');
        $this->career_model->updateCareerRecord($post,$jobId);
        $logDetails = array(
            'logMessage' => 'Function: updateJob, User: '.$this->userId.' id: '.$jobId,
            'fromWhere' => 'Dashboard',
            'insertedDT' => date('Y-m-d H:i:s')
        );
        $this->dashboard_model->saveDashLogs($logDetails);
        redirect(base_url().'career');
    }

    public function setCareerDeActive($jobId)
    {
        $details = array(
            'ifActive' => 0
        );
        $this->career_model->updateCareerRecord($details,$jobId);
        $logDetails = array(
            'logMessage' => 'Function: setCareerDeActive, User: '.$this->userId.' id: '.$jobId,
            'fromWhere' => 'Dashboard',
            'insertedDT' => date('Y-m-d H:i:s')
        );
        $this->dashboard_model->saveDashLogs($logDetails);
        redirect(base_url().'career');
    }

    public function setCareerActive($jobId)
    {
        $details = array(
            'ifActive' => 1
        );
        $this->career_model->updateCareerRecord($details,$jobId);
        $logDetails = array(
            'logMessage' => 'Function: setCareerActive, User: '.$this->userId.' id: '.$jobId,
            'fromWhere' => 'Dashboard',
            'insertedDT' => date('Y-m-d H:i:s')
        );
        $this->dashboard_model->saveDashLogs($logDetails);
        redirect(base_url().'career');
    }

}
