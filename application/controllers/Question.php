<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Question
 * @property Users_Model $users_model
 * @property Question_Model $question_model
*/

class Question extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('users_model');
		$this->load->model('question_model');
        ini_set('memory_limit', "256M");
        ini_set('upload_max_filesize', "50M");
	}
	public function index()
	{

	}

	public function uploadFromExcel()
    {
        $data = array();

        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);
        $this->load->view('quiz/ExcelView', $data);
    }

    public function uploadFile()
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
                    log_message('error','Quiz Upload: '.$this->upload->display_errors());
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

    public function parseQuestions()
    {
        $post = $this->input->post();
        $data = array();

        //reading excel file
        $inputFileName = '../dashboad/uploads/'.$post['filename'];
        $mainFileArray = array();
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
            foreach($abc as $key => $row)
            {
                $actualVals = array_values($row);
                $qCat = '3';
                $questLvl = '3';
                switch(strtolower($actualVals[0]))
                {
                    case 'kitchen':
                        $qCat = '1';
                        break;
                    case 'service':
                        $qCat = '2';
                        break;
                }
                switch(strtolower($actualVals[1]))
                {
                    case 'easy':
                        $questLvl = '1';
                        break;
                    case 'medium':
                        $questLvl = '2';
                        break;
                }
                $qDetails = array(
                    'questionText' => $actualVals[2],
                    'questionCat' => $qCat,
                    'questionLvl' => $questLvl,
                    'addedBy' => $this->userId,
                    'insertedDT' => date('Y-m-d H:i:s')
                );
                $qId = $this->question_model->saveQuestion($qDetails);

                $correctOpts = explode(',',$actualVals[7]);

                $optDetails = array();
                $optDetails[] = array(
                    'qid' => $qId,
                    'optionText' => $actualVals[3],
                    'isCorrectOption' => myInArray('1',$correctOpts)?'1':'0',
                    'createdDT' => date('Y-m-d H:i:s')
                );
                $optDetails[] = array(
                    'qid' => $qId,
                    'optionText' => $actualVals[4],
                    'isCorrectOption' => myInArray('2',$correctOpts)?'1':'0',
                    'createdDT' => date('Y-m-d H:i:s')
                );
                $optDetails[] = array(
                    'qid' => $qId,
                    'optionText' => $actualVals[5],
                    'isCorrectOption' => myInArray('3',$correctOpts)?'1':'0',
                    'createdDT' => date('Y-m-d H:i:s')
                );
                $optDetails[] = array(
                    'qid' => $qId,
                    'optionText' => $actualVals[6],
                    'isCorrectOption' => myInArray('4',$correctOpts)?'1':'0',
                    'createdDT' => date('Y-m-d H:i:s')
                );

                $this->question_model->saveOptionsBatch($optDetails);
            }
            $data['status'] = true;

        } catch(Exception $e) {
            $data['status'] = false;
            $data['errorMsg'] = 'Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage();
        }

        echo json_encode($data);
    }

}
