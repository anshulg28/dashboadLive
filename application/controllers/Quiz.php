<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Users
 * @property Users_Model $users_model
 * @property Quiz_Model $quiz_model
 * @property Locations_Model $locations_model
 * @property Login_Model $login_model
 * @property  Mugclub_Model $mugclub_model
*/

class Quiz extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('users_model');
		$this->load->model('quiz_model');
		$this->load->model('locations_model');
        $this->load->model('login_model');
        $this->load->model('mugclub_model');
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

        if($this->userType == SERVER_USER)
        {
            if(!isset($this->currentLocation) || isSessionVariableSet($this->currentLocation) === false)
            {
                $gotLocId = $this->mugclub_model->fetchLocIdByMob($this->userId);
                if(isset($gotLocId['id']))
                {
                    $this->generalfunction_library->setSessionVariable("currentLocation",$gotLocId['id']);
                    $this->currentLocation = $gotLocId['id'];
                }
                else
                {
                    redirect(base_url().'location-select');
                }
            }
            $data['quizRecord'] = $this->quiz_model->getDrawnNamesByLoc($this->currentLocation);
            $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
            $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
            $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
            $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);
            $this->load->view('quiz/ServerView', $data);
        }
        else
        {
            if(isSessionVariableSet($this->userId))
            {
                $rols = $this->login_model->getUserRoles($this->userId);
                $data['userModules'] = explode(',',$rols['modulesAssigned']);
            }

            $data['quizRecord'] = $this->quiz_model->getAllDrawnNames();
            $data['staffRecords'] = $this->quiz_model->getAllStaffQuizData();
            $data['lastDrawn'] = $this->quiz_model->getLastDrawn();
            $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
            $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
            $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
            $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);
            $this->load->view('quiz/HrView', $data);
        }
	}

	//not in use
	public function createQuiz()
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Session Timeout, Please Login Again!';
            echo json_encode($data);
            return false;
        }

        $fetchEntry = array(
            'dateDrawn' => date('Y-m-d'),
            'drawnStatus' => '0',
            'drawnBy' => $this->userId,
            'insertedDT' => date('Y-m-d H:i:s')
        );
        $this->quiz_model->createQuizListFetchEntry($fetchEntry);

        //Set a trigger and return true;

    }

    public function verfiyExcel()
    {
        $post = $this->input->post();
        $data = array();
        if(isset($post['filename']))
        {
            $inputFileName = '../dashboad/uploads/'.$post['filename'];
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
                    $finalLoc = '';
                    if(stripos(trim($actualVals[3]),',') !== false)
                    {
                        $temp = explode(',',trim($actualVals[3]));
                        $finalLoc = explode(' ',$temp[0])[0];
                    }
                    $loc = getUniqueLink($finalLoc);
                    $locInfo = $this->locations_model->getLocDetailsByUniqueLink($loc);

                    if($locInfo['status'] === false)
                    {
                        $anyError = true;
                        $allErrors[] = 'Location Error At Line No. '.((int)$key+2);
                        //break;
                    }
                    if(trim($actualVals[4]) != '')
                    {
                        $depart = array(
                            'service',
                            'kitchen'
                        );
                        if(!myInArray(strtolower(trim($actualVals[4])),$depart))
                        {
                            $anyError = true;
                            $allErrors[] = 'Department Error At Line No. '.((int)$key+2);
                        }
                    }
                    else
                    {
                        $anyError = true;
                        $allErrors[] = 'Department Missing At Line No. '.((int)$key+2);
                        //break;
                    }
                }
                if(!$anyError)
                {
                    $data['status'] = true;
                }
                else
                {
                    $data['errorMsg'] = implode(',',$allErrors);
                    $data['status'] = false;
                }

            }
            catch(Exception $e)
            {
                $data['status'] = false;
                $data['errorMsg'] = 'Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage();
            }
        }
        else
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Excel File not provided';
        }
        echo json_encode($data);
    }

    //not using
    public function createQuizList()
    {
        //Fetching All locations
        $locs = $this->locations_model->getAllLocations();
        if($locs['status'] == true)
        {
            $empList = array();
            foreach($locs as $key => $row)
            {
                if(isset($row['id']))
                {
                    //First Fetch Current Date Employee list from Quikchex
                    $todayList = array(
                        'access_token' => QUIKCHEX_ACCESS_TOKEN,
                        'store_id' => $row['quikchexId'],
                        'start_date' => date('Y-m-d')
                    );
                    $list1 = $this->curl_library->getQuikchexAttendance($todayList);
                    if($list1['exception_occured'] == "N")
                    {
                        $pastList = array(
                            'access_token' => QUIKCHEX_ACCESS_TOKEN,
                            'store_id' => $row['quikchexId'],
                            'start_date' => date('Y-m-d',strtotime('-1 day'))
                        );
                        $list2 = $this->curl_library->getQuikchexAttendance($pastList);
                        if($list2['exception_occured'] == "Y")
                        {
                            echo $list2['exception_message'];
                            log_message('error',$list2['exception_message']);
                            break;
                        }
                        else
                        {

                            //Sorting the list based on names
                            $prevList = $list2['employee_details'];
                            $nowList = $list1['employee_details'];
                            usort($prevList,
                                function($a, $b) {
                                    $ts_a = trim($a[0]);
                                    $ts_b = trim($b[0]);

                                    return $ts_a > $ts_b;
                                }
                            );
                            usort($nowList,
                                function($a, $b) {
                                    $ts_a = trim($a[0]);
                                    $ts_b = trim($b[0]);

                                    return $ts_a > $ts_b;
                                }
                            );
                            echo '<pre>';
                            var_dump($prevList,$nowList);
                            die();
                            //combine, iterate and filter the list
                            $finalList = array_merge($list1['employee_details'], $list2['employee_details']);
                            var_dump($finalList);
                        }
                    }
                    else
                    {
                        echo $list1['exception_message'];
                        log_message('error',$list1['exception_message']);
                        break;
                    }
                }
            }
        }
        else
        {
            echo 'Location list Error';
            log_message('error','Location List Error');
        }
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

    public function createQuizExcel()
    {
        $post = $this->input->post();
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Session Timeout, Please Login Again!';
            echo json_encode($data);
            return false;
        }

        $quizPlay = array(
            'dateDrawn' => date('Y-m-d'),
            'drawnStatus' => 0,
            'uploadedExcelFile' => $post['filename'],
            'drawnBy' => $this->userId,
            'insertedDT' => date('Y-m-d H:i:s')
        );
        $QuizId = $this->quiz_model->createQuizListFetchEntry($quizPlay);

        $quizData = $this->createLocalQuiz();

        if($quizData['status'] === true)
        {
            $data['status'] = true;

            //Set trigger to finish quiz after 3 days
            $params = array(
                'key' => TRIGGER_KEY,
                'secret' => TRIGGER_SECRET,
                'timeSlice' => '3day',
                'count' => '1',
                'tag_id' => $this->userId,
                'url' => base_url().'quiz/wrapQuiz'
            );
            $s =  $this->curl_library->setTrigger($params);
        }
        else
        {
            if(isset($quizData['reDraw']) && $quizData['reDraw'] === true)
            {
                $this->quiz_model->delQuizEntry($QuizId);
            }
            $data['status'] = false;
            $data['errorMsg'] = $quizData['errorMsg'];
        }

        //Setting a trigger
        /*$params = array(
            'key' => TRIGGER_KEY,
            'secret' => TRIGGER_SECRET,
            'timeSlice' => '2minute',
            'count' => '1',
            'tag_id' => $this->userId,
            'url' => base_url().'quiz/wrapQuiz'
        );
        $s =  $this->curl_library->setTrigger($params);*/
        echo json_encode($data);
    }

    public function createLocalQuiz()
    {
        $data = array();
        $quizFile = $this->quiz_model->getLastDrawn();
        if(isset($quizFile) && myIsArray($quizFile))
        {
            $previousData = $this->quiz_model->getPreviousPlayers();
            $empNames = array();
            $monthCounts = array();
            $empIds = array();
            $repeatedDates = array();
            if(isset($previousData['empNames']))
            {
                $empIds = explode(',',$previousData['empIds']);
                $empNames = explode(',',$previousData['empNames']);
                $monthCounts = explode(',',$previousData['monthCounts']);
                $repeatedDates = explode(';',$previousData['repeatedDates']);
            }
            //reading excel file
            $inputFileName = '../dashboad/uploads/'.$quizFile['uploadedExcelFile'];
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
                $finalData = array();
                $anyError = false;
                $allErrors = array();
                foreach($abc as $key => $row)
                {
                    $desigVal = null;
                    $departVal = null;
                    $actualVals = array_values($row);
                    $ignoreDesign = array(
                        'Restaurant Manager',
                        'Housekeeping',
                        'Sous Chef',
                        'Floor manager',
                        'CDP'
                    );
                    if(!in_array(trim($actualVals[2]),$ignoreDesign))
                    {
                        $finalLoc = '';
                        if(stripos(trim($actualVals[3]),',') !== false)
                        {
                            $temp = explode(',',trim($actualVals[3]));
                            $finalLoc = explode(' ',$temp[0])[0];
                        }
                        $loc = getUniqueLink($finalLoc);
                        $locInfo = $this->locations_model->getLocDetailsByUniqueLink($loc);
                        $empKey = array_search($actualVals[0],$empNames);
                        if($empKey !== false)
                        {
                            if($monthCounts[$empKey] >= 3)
                            {
                                $mainFileArray[$locInfo['locData'][0]['id']][] = array(
                                    'rId' => $empIds[$empKey],
                                    'empId' => $actualVals[1],
                                    'empName' => $actualVals[0],
                                    'empDepart' => $actualVals[4],
                                    'empDesignation' => $actualVals[2]
                                );
                            }
                            else
                            {
                                //update the month count
                                $details = array(
                                    'monthCount' => ((int)$monthCounts[$empKey] + 1)
                                );
                                $this->quiz_model->updatePlayerRecord($details,$empIds[$empKey]);
                            }
                        }
                        else
                        {
                            $mainFileArray[$locInfo['locData'][0]['id']][] = array(
                                'rId' => null,
                                'empId' => $actualVals[1],
                                'empName' => $actualVals[0],
                                'empDepart' => $actualVals[4],
                                'empDesignation' => $actualVals[2]
                            );
                        }
                    }
                }

                //$data['status'] = true;
                $finalPlayerList = array();
                // Got all available employees list in mainFileArray
                foreach($mainFileArray as $key => $row)
                {
                    $serviceCount = 0;
                    $kitchenCount = 0;
                    foreach($row as $pKey => $pRow)
                    {
                        $testLevel = 'easy';
                        switch(strtolower($pRow['empDepart']))
                        {
                            case 'service':
                                if($serviceCount<5)
                                {
                                    if(isset($pRow['rId']))
                                    {
                                        $testLevel = 'medium';
                                    }
                                    $finalPlayerList[] = array(
                                        'rId' => $pRow['rId'],
                                        'empId' => $pRow['empId'],
                                        'empName' => $pRow['empName'],
                                        'empDepart' => $pRow['empDepart'],
                                        'empDesignation' => $pRow['empDesignation'],
                                        'attemptNum' => 0,
                                        'quizLoc' => $key,
                                        'ifTestStarted' => 0,
                                        'testLevel' => $testLevel,
                                        'insertedDT' => date('Y-m-d H:i:s')
                                    );
                                    $serviceCount++;
                                }
                                break;
                            case 'kitchen':
                                if($kitchenCount<5)
                                {
                                    if(isset($pRow['rId']))
                                    {
                                        $testLevel = 'medium';
                                    }
                                    $finalPlayerList[] = array(
                                        'rId' => $pRow['rId'],
                                        'empId' => $pRow['empId'],
                                        'empName' => $pRow['empName'],
                                        'empDepart' => $pRow['empDepart'],
                                        'empDesignation' => $pRow['empDesignation'],
                                        'attemptNum' => 0,
                                        'quizLoc' => $key,
                                        'ifTestStarted' => 0,
                                        'testLevel' => $testLevel,
                                        'insertedDT' => date('Y-m-d H:i:s')
                                    );
                                    $kitchenCount++;
                                }
                                break;
                        }
                    }
                }

                $allLocs = $this->locations_model->getAllActiveLocations();
                if(count($finalPlayerList) == (count($allLocs) * 10))
                {
                    $this->quiz_model->savePlayersBatch($finalPlayerList);
                    $quizDetails = array(
                        'drawnStatus' => 1
                    );
                    $this->quiz_model->updateQuizPlayRecord($quizDetails,$quizFile['id']);
                    $data['status'] = true;
                }
                else
                {
                    $data['status'] = false;
                    $data['reDraw'] = true;
                    $data['errorMsg'] = 'Not Enough Employees for test, Please update Excel file and try again!';
                }

            } catch(Exception $e) {
                $data['status'] = false;
                $data['errorMsg'] = 'Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage();
            }
        }
        else
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Excel File Not Found!';
        }

        return $data;
    }

    public function startServerQuiz($qId)
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Session Timeout, Please Login Again!';
            echo json_encode($data);
            return false;
        }

        $quizInfo = $this->quiz_model->getQuizDetailById($qId);

        //Update Test started
        $details = array(
            'ifTestStarted' => 1,
            'attemptNum' => 1
        );
        $this->quiz_model->updateQuizMaster($details,$qId);

        //Make attempts entry
        $atDetails = array(
            'quizId' => $qId,
            'empName' => $quizInfo['empName'],
            'isInterrupted' => '0',
            'insertedDT' => date('Y-m-d H:i:s')
        );

        $this->quiz_model->saveAttempRecord($atDetails);

        $quizLvl = '3';
        switch($quizInfo['testLevel'])
        {
            case 'easy':
                $quizLvl = '1';
                break;
            case 'medium':
                $quizLvl = '2';
                break;
        }
        $data['status'] = true;
        $data['quizLvl'] = $quizLvl;

        echo json_encode($data);
    }

    public function quizPage($quizLvl)
    {
        $data = array();
        $post = $this->input->post();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Session Timeout, Please Login Again!';
            echo json_encode($data);
            return false;
        }

        if(!isset($post['qid']))
        {
            redirect(base_url().'quiz');
        }

        $quizQuests = $this->quiz_model->getRandomQuizQuestions($quizLvl);
        $data['quizQts'] = $quizQuests;
        $data['qId'] = $post['qid'];
        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);
        $this->load->view('quiz/TestView', $data);
    }

    public function restartServerQuiz($qId)
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Session Timeout, Please Login Again!';
            echo json_encode($data);
            return false;
        }

        $quizInfo = $this->quiz_model->getQuizDetailById($qId);

        //checking for attempts
        if((int)$quizInfo['attemptNum'] >= 3)
        {
            $quizUp = array(
                'ifTestFinished' => '2'
            );
            $this->quiz_model->updateQuizMaster($quizUp,$qId);
            $atDetails = array(
                'isInterrupted' => '1'
            );
            $this->quiz_model->updateAttemptRecord($atDetails,$qId);
            $data['status'] = true;
            $data['isBlocked'] = true;
        }
        else
        {
            $oldAttempt = (int)$quizInfo['attemptNum'];
            $newAttempt = $oldAttempt + 1;

            $qUpdate = array(
                'attemptNum' => $newAttempt
            );

            $this->quiz_model->updateQuizMaster($qUpdate,$qId);

            $atDetails = array(
                'isInterrupted' => '1'
            );
            $this->quiz_model->updateAttemptRecord($atDetails,$qId);

            //Make attempts entry
            $atDetails = array(
                'quizId' => $qId,
                'empName' => $quizInfo['empName'],
                'isInterrupted' => '0',
                'insertedDT' => date('Y-m-d H:i:s')
            );

            $this->quiz_model->saveAttempRecord($atDetails);

            $quizLvl = '3';
            switch($quizInfo['testLevel'])
            {
                case 'easy':
                    $quizLvl = '1';
                    break;
                case 'medium':
                    $quizLvl = '2';
                    break;
            }
            $data['status'] = true;
            $data['attempNum'] = $newAttempt;
            $data['quizLvl'] = $quizLvl;
        }

        echo json_encode($data);
    }

    public function endQuiz()
    {
        $data= array();
        $post = $this->input->post();

        if(isset($post['qid']) && isset($post['marks']))
        {
            $qUpdate = array(
                'marksScored' => $post['marks'],
                'ifTestFinished' => '1'
            );
            $this->quiz_model->updateQuizMaster($qUpdate,$post['qid']);
            $data['status'] = true;
        }
        else
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Marks not submitted!';
        }
        echo json_encode($data);
    }

    public function wrapQuiz()
    {
        $quizRecord = $this->quiz_model->getAllDrawnNames();

        $finalEmpList = array();

        $colsArray = array('Employee Id','Employee Name','Employee Department','Designation','Location','No. of Attempts','Marks Scored',
            'Test Started', 'Test Finished', 'Test Level', 'Quiz Date/Time');

        $file = fopen("./uploads/quizReport_".date('d_M_Y').'.csv',"w");

        fputcsv($file,$colsArray);

        foreach($quizRecord as $key => $row)
        {
            $testStart = array(
                '0' => 'No',
                '1' => 'Yes'
            );
            $testFinish = array(
                '0' => 'No',
                '1' => 'Yes',
                '2' => 'Blocked'
            );
            $d = date_create($row['insertedDT']);
            $rowToCsv = array(
                $row['empId'],
                $row['empName'],
                $row['empDepart'],
                $row['empDesignation'],
                $row['locName'],
                $row['attemptNum'],
                $row['marksScored'],
                $testStart[$row['ifTestStarted']],
                $testFinish[$row['ifTestFinished']],
                ucfirst($row['testLevel']),
                date_format($d, DATE_TIME_FORMAT_UI)
            );
            fputcsv($file,$rowToCsv);

            if($row['ifTestFinished'] == '1')
            {
                if(isset($row['rId']))
                {
                    $details = array(
                        'logTxt' => 'Employee with Name: '.$row['empName'].' Location: '.$row['locName'].' has waited 3 months. With Attempts: '
                            .$row['attemptNum'].' and previous score: '.$row['marksScored'].' , resetting now',
                        'insertedDT' => date('Y-m-d H:i:s')
                    );
                    $this->quiz_model->saveQuizLog($details);
                    $rData = $this->quiz_model->getQuizStaffById($row['rId']);

                    $pUpdate = array(
                        'repeatedDates' => $rData['repeatedDates'].','.date('Y-m-d'),
                        'monthCount' => 1
                    );
                    $this->quiz_model->updatePlayerRecord($pUpdate, $row['rId']);
                }
                else
                {
                    $finalEmpList[] = array(
                        'empId' => $row['empId'],
                        'empName' => $row['empName'],
                        'empDepart' => $row['empDepart'],
                        'empDesignation' => $row['empDesignation'],
                        'quizLoc' => $row['quizLoc'],
                        'monthCount' => 1,
                        'repeatedDates' => date('Y-m-d'),
                        'noOfAttempts' => $row['attemptNum'],
                        'totalMarks' => $row['marksScored'],
                        'insertedDT' => date('Y-m-d H:i:s')
                    );
                }
            }
        }
        fclose($file);

        if(myIsArray($finalEmpList))
        {
            $this->quiz_model->saveStaffQuizBatch($finalEmpList);
        }
        $this->quiz_model->clearQuizMaster();

        $content = '<html><body><p>Quiz final report attached!<br>PFA</p></body></html>';

        $this->sendemail_library->sendEmail(array('purva@brewcraftsindia.com','hasti@brewcraftsindia.com','suketu@brewcraftsindia.com',
            'savio@brewcraftsindia.com','tresha@brewcraftsindia.com'),'saha@brewcraftsindia.com',ADMIN_SENDER_EMAIL,ADMIN_SENDER_PASS,'Doolally'
            ,'admin@brewcraftsindia.com','Quiz Final Report | '.date('d_M_Y'),$content,array("./uploads/quizReport_".date('d_M_Y').'.csv'));
        try
        {
            unlink("./uploads/quizReport_".date('d_M_Y').'.csv');
        }
        catch(Exception $ex)
        {

        }

        echo true;
    }

    public function manageQuestions()
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }

        $data['questions'] = $this->quiz_model->getAllQuestions();

        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);
        $this->load->view('quiz/QuestionsView', $data);
    }

    public function editQuestion($qId)
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }

        $data['qId'] = $qId;
        $data['questData'] = $this->quiz_model->getSingleQuestion($qId);
        $data['qCats'] = $this->quiz_model->getAllQCats();
        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);
        $this->load->view('quiz/QuestionEditView', $data);
    }

    public function addQuestion()
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }

        $data['qCats'] = $this->quiz_model->getAllQCats();
        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);
        $this->load->view('quiz/QuestionAddView', $data);
    }

    public function updateQust($qId)
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $post = $this->input->post();

        $optIds=  $post['optIds'];
        unset($post['optIds']);
        $this->quiz_model->updateQuestionRecord($post,$qId);
        $qReset = array(
            'isCorrectOption' => 0
        );
        $this->quiz_model->updateResetOptionsByQId($qReset,$qId);
        if(is_array($optIds))
        {
            foreach($optIds as $key)
            {
                $optUpdate = array(
                    'isCorrectOption' => 1
                );
                $this->quiz_model->updateOptions($optUpdate,$key);
            }
        }
        else
        {
            $optUpdate = array(
                'isCorrectOption' => 1
            );
            $this->quiz_model->updateOptions($optUpdate,$optIds);
        }
       redirect(base_url().'quiz/manageQuestions');
    }

    public function deleteQuest($qid)
    {
        $this->quiz_model->delQuestion($qid);
        redirect(base_url().'quiz/manageQuestions');
    }

    public function addQust()
    {
        $post = $this->input->post();

        $optionTxt = $post['optionTxt'];
        $optionCorrect = $post['optionCorrect'];
        unset($post['optionTxt'], $post['optionCorrect']);
        $post['addedBy'] = $this->userId;
        $post['insertedDT'] = date('Y-m-d H:i:s');
        $questId = $this->quiz_model->saveQuestionRecord($post);

        $correctArray = array();
        foreach($optionCorrect as $key => $row)
        {
            $correctArray[] = (int)$row;
        }
        $optsAr = array();
        foreach($optionTxt as $key => $row)
        {
            $isCorrect = 0;
            if(in_array($key,$correctArray))
            {
                $isCorrect = 1;
            }
            $optsAr[] = array(
                'qid' => $questId,
                'optionText' => $row,
                'isCorrectOption' => $isCorrect,
                'createdDT' => date('Y-m-d H:i:s')
            );
        }
        $this->quiz_model->saveOptionsBatch($optsAr);
        redirect(base_url().'quiz/manageQuestions');
    }

    //not in use
    public function getorder()
    {
        //&=&=&=&=&=&=true&=false&=false&=false
        /*$p = array(
            '__resultsetname' => 'ELEMENT_361',
                '__selectedcolumnnumber' => '2',
            '__selectedcolumn0' =>'order_number',
            '__selectedcolumn1' => 'table_name',
            '__extractextension' => 'org.eclipse.birt.report.engine.dataextraction.csv',
            '__exportencoding' => 'UTF-8',
            '__sep' => '0',
            '__asattachment' => 'true',
            '__exportdatatype' => 'false',
            '__localeneutral' => 'false',
            '__carriagereturn' => 'false'
        );
        $f = $this->curl_library->checkBill($p);
        echo '<pre>';
        var_dump($f);*/
        $data = array();

        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $this->load->view('feedbackView', $data);
    }

}
