<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Users
 * @property Users_Model $users_model
 * @property Spotaward_Model $spotaward_model
 * @property Locations_Model $locations_model
 * @property Login_Model $login_model
*/

class Spotaward extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('users_model');
		$this->load->model('spotaward_model');
		$this->load->model('locations_model');
        $this->load->model('login_model');
        ini_set('memory_limit', "256M");
        ini_set('upload_max_filesize', "50M");
	}
	public function index()
	{
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }

        if(isSessionVariableSet($this->userId))
        {
            $rols = $this->login_model->getUserRoles($this->userId);
            $data['userModules'] = explode(',',$rols['modulesAssigned']);

        }

        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);

        $this->load->view('spotaward/MainView', $data);
	}

	public function addNewAwards()
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

        $this->load->view('spotaward/addSpotsView', $data);
    }
    public function uploadExcelFiles()
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Session Timeout, Please Login Again!';
            echo json_encode($data);
            return false;
        }
        $attchmentArr = '';
        $this->load->library('upload');
        if(isset($_FILES))
        {
            if($_FILES['attachment']['error'] != 1)
            {
                $config = array();
                $config['upload_path'] = '../dashboad/uploads/'; // FOOD_PATH_THUMB; //'uploads/food/';
                $config['allowed_types'] = 'xls|xlsx|csv';
                $config['max_size']      = '0';
                $config['overwrite']     = TRUE;

                $this->upload->initialize($config);
                if(!$this->upload->do_upload('attachment'))
                {
                    log_message('error','Job Upload: '.$this->upload->display_errors());
                    $data['status'] = false;
                    $data['errorMsg'] = $this->upload->display_errors();
                    echo json_encode($data);
                    return false;
                }
                else
                {
                    $upload_data = $this->upload->data();
                    echo $upload_data['file_name'];
                }
            }
            else
            {
                echo 'Some Error Occurred!';
            }
        }
        else
        {
            $data['status'] = false;
            $data['errorMsg'] = 'No Excel File Received!';
            echo json_encode($data);
            return false;
        }
    }

    public function saveSpotData()
    {
        require_once APPPATH.'libraries/spreadsheet-reader-master/excel_reader2.php';
        require_once APPPATH.'libraries/spreadsheet-reader-master/SpreadsheetReader.php';
        $post = $this->input->post();

        if(isset($post['excelFile']))
        {
            $Reader = new SpreadsheetReader($post['excelFile']);
            $Sheets = $Reader->Sheets();
            var_dump($Sheets);

        }
    }
}
