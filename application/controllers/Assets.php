<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Assets
 * @property Assets_Model $assets_model
 * @property Locations_Model $locations_model
 * @property Login_Model $login_model
*/

class Assets extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('assets_model');
		$this->load->model('locations_model');
        $this->load->model('login_model');
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

        $data['assetsRecord'] = $this->assets_model->getAllAssets();
        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);
        $this->load->view('assets/MainView', $data);
	}

	public function addAsset()
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
        $this->load->view('assets/AssetAddView', $data);
    }

    public function saveAsset()
    {
        $post = $this->input->post();
        $post['insertedDT'] = date('Y-m-d H:i:s');
        $this->assets_model->saveAsset($post);
        redirect(base_url().'assets');
    }
    public function editItem($aId)
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }

        $data['assetData'] = $this->assets_model->getAssetsById($aId);
        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);
        $this->load->view('assets/AssetEditView', $data);
    }

    public function updateAsset($aId)
    {
        $post = $this->input->post();
        //$post['insertedDT'] = date('Y-m-d H:i:s');
        $this->assets_model->updateAsset($post, $aId);
        redirect(base_url().'assets');
    }

}
