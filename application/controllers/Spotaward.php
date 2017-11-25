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

        $data['locs'] = $this->locations_model->getAllActiveLocations();
        $data['departs'] = $this->spotaward_model->getAllDeparts();
        $data['designations'] = $this->spotaward_model->getAllDesignations();

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
        $post = $this->input->post();
        $data = array();

        if(isset($post['excelFile']))
        {
            $inputFileName = '../dashboad/uploads/'.$post['excelFile'];
            //  Read your Excel workbook
            try {
                PHPExcel_Cell::setValueBinder( new PHPExcel_Cell_AdvancedValueBinder() );
                $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                //$objReader->setReadDataOnly(true);
                $objPHPExcel = $objReader->load($inputFileName);
                $allVal = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
                $abc = array_filter($allVal, function($var){return !is_null($var["A"]);} );
                $abc = array_values($abc);
                unset($abc[0]);
                $abc = array_values($abc);
                $finalData = array();
                $anyError = false;
                $allErrors = array();
                foreach($abc as $key => $row)
                {
                    $desigVal = null;
                    $departVal = null;
                    $actualVals = array_values($row);
                    $loc = getUniqueLink(trim($actualVals[4]));
                    $locInfo = $this->locations_model->getLocDetailsByUniqueLink($loc);
                    if(trim($actualVals[2]) != '')
                    {
                        $departInfo = $this->spotaward_model->searchDepart(trim($actualVals[2]));
                    }
                    else
                    {
                        $anyError = true;
                        $allErrors[] = 'Department Missing At Line No. '.((int)$key+2);
                        //break;
                    }
                    if(trim($actualVals[3]) != '')
                    {
                        $desigInfo = $this->spotaward_model->searchDesignations(trim($actualVals[3]));
                    }
                    else
                    {
                        $anyError = true;
                        $allErrors[] = 'Designation Missing At Line No. '.((int)$key+2);
                        //break;
                    }

                    if($locInfo['status'] === false)
                    {
                        $anyError = true;
                        $allErrors[] = 'Location Error At Line No. '.((int)$key+2);
                        //break;
                    }
                    elseif(!myIsArray($departInfo))
                    {
                        $anyError = true;
                        $allErrors[] = 'Department Error At Line No. '.((int)$key+2);
                        //break;
                    }
                    elseif(!myIsArray($desigInfo))
                    {
                        $anyError = true;
                        $allErrors[] = 'Designation Error At Line No. '.((int)$key+2);
                        //break;
                    }
                    else
                    {
                        //date('d').'-'.$post['awardDate'];
                        $datetime = new DateTime();
                        $newDate = date('d').'-'.$post['awardDate'];// $datetime->createFromFormat('m-d-Y', $actualVals[0]);
                        $finalData[] = array(
                            'awardDate' => date('Y-m-d',strtotime($newDate)),
                            'empId' => trim($actualVals[0]),
                            'empName' => trim($actualVals[1]),
                            'empDepartment' => $departInfo['id'],
                            'empDesignation' => $desigInfo['id'],
                            'empLocation' => $locInfo['locData'][0]['id'],
                            'reasonText' => trim($actualVals[5]),
                            'insertedDT' => date('Y-m-d H:i:s')
                        );
                    }
                }
                if(!$anyError)
                {
                    $this->spotaward_model->saveAwardBatch($finalData);
                    $data['status'] = true;
                }
                else
                {
                    $data['errorMsg'] = implode(',',$allErrors);
                    $data['status'] = false;
                }
                //var_dump($abc);
                //die();
/*                $htmlTab = '<table border="2">';

                for($i=0;$i<$objPHPExcel->getSheetCount();$i++)
                {
                    //  Get worksheet dimensions
                    $sheet = $objPHPExcel->getSheet($i);
                    $htmlTab .= '<tr><td>'.$sheet->getTitle().'</td></tr>';
                    foreach($sheet->getRowIterator() as $row)
                    {
                        $htmlTab .= '<tr>';
                        foreach($row->getCellIterator() as $cell)
                        {
                            if($cell->getValue() != '')
                            {
                                $htmlTab .= '<td>'.trim($cell->getValue()).'</td>';
                            }
                        }
                        $htmlTab .='</tr>';
                    }
                }
                $htmlTab .= '</table>';
                echo $htmlTab;*/

            } catch(Exception $e) {
                $data['status'] = false;
                $data['errorMsg'] = 'Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage();
            }
        }
        else
        {
            $data['status'] = false;
            $data['errorMsg'] = 'File Not Set!';
        }
        echo json_encode($data);
    }

    public function getSpotRecords()
    {
        $data = array();
        $awardData = $this->spotaward_model->getAllAwardRecords();
        if(isset($awardData) && myIsArray($awardData))
        {
            foreach($awardData as $key => $row)
            {
                //$data['data'][$key][] = '<p>'.$row['awardDate'].'<span data-month="'.$row['awMonth'].'" data-year="'.$row['awYear'].'"></span></p>';
                $data['data'][$key][] = '<span data-month="'.$row['awMonth'].'" data-year="'.$row['awYear'].'">'.$row['awardDate'].'</span>';
                $data['data'][$key][] = $row['empId'];
                $data['data'][$key][] = $row['empName'];
                $data['data'][$key][] = $row['empDesignation'];
                $data['data'][$key][] = $row['empDepartment'];
                $data['data'][$key][] = $row['locName'];
                $data['data'][$key][] = $row['reasonText'];
            }
        }
        else
        {
            $data['data'] = null;
        }
        echo json_encode($data);
    }
}
