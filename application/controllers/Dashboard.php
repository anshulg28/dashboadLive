<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Dashboard
 * @property generalfunction_library $generalfunction_library
 * @property Dashboard_Model $dashboard_model
 * @property Mugclub_Model $mugclub_model
 * @property Users_Model $users_model
 * @property  Locations_Model $locations_model
 * @property Login_Model $login_model
 */

class Dashboard extends MY_Controller {

    function __construct()
    {
        //Loading all the models on start
        parent::__construct();
        $this->load->model('dashboard_model');
        $this->load->model('mugclub_model');
        $this->load->model('users_model');
        $this->load->model('locations_model');
        $this->load->model('login_model');
        ini_set('memory_limit', "256M");
        ini_set('upload_max_filesize', "50M");
    }

    /* Main Function (entry point) */
    public function index()
	{
        if(isSessionVariableSet($this->isUserSession) === false || $this->userType == SERVER_USER)
        {
            redirect(base_url());
        }

		$data = array();

        if(isSessionVariableSet($this->userId))
        {
            $rols = $this->login_model->getUserRoles($this->userId);
            $data['userModules'] = explode(',',$rols['modulesAssigned']);
        }

        $locArray = $this->locations_model->getAllLocations();
        $data['locations'] = $locArray;
        if($this->userType == EXECUTIVE_USER)
        {
            //Check for Community manager secondary location set

            /*$userInfo = $this->users_model->getUserDetailsById($this->userId);
            if(isset($userInfo['userData'][0]['secondaryLoc']))
            {
                $data['secLocs'] = $userInfo['userData'][0]['secondaryLoc'];
            }*/
            if( !isSession($this->commSecLoc) || !isSessionVariableSet($this->commSecLoc))
            {
                redirect(base_url().'dashboard/setCommLoc');
                /*if($userInfo['status'] === true && isset($userInfo['userData'][0]['secondaryLoc']))
                {
                    $this->getCommLocation(base64_encode($userInfo['userData'][0]['secondaryLoc']));
                    return false;
                }*/
            }
            //$data['secLocs'] = $this->commSecLoc;
            $allLocs = explode(',',$this->commSecLoc);
            /*if(isSession($this->commSecLoc) && isSessionVariableSet($this->commSecLoc))
            {

            }*/
            /*else
            {
                //Get assigned location for community manager
                $allLocs = explode(',',$userInfo['userData'][0]['assignedLoc']);
            }*/

            foreach($allLocs as $key)
            {
                $data['userInfo'][$key] = $this->locations_model->getLocationDetailsById($key);
            }

            $data['commLocs'] = $allLocs;
        }
        //Dashboard Data
        $startDate = date('Y-m-d', strtotime('-1 month'));
        $endDate = date('Y-m-d');
        $data['totalMugs'] = $this->mugclub_model->getAllMugsCount($locArray);
        $data['avgChecks'] = $this->dashboard_model->getAvgCheckins($startDate,$endDate,$locArray);
        $data['Regulars'] = $this->dashboard_model->getRegulars($startDate,$endDate,$locArray);
        $data['Irregulars'] = $this->dashboard_model->getIrregulars($startDate,$endDate,$locArray);
        $data['lapsers'] = $this->dashboard_model->getLapsers($startDate,$endDate,$locArray);

        //getting graph data for dashboard
        $graphData = $this->dashboard_model->getAllDashboardRecord();
        if($graphData['status'] === true)
        {
            foreach($graphData['dashboardPoints'] as $key => $row)
            {
                $data['graph']['avgChecks'][$key] = $row['avgCheckins'];
                $data['graph']['regulars'][$key] = $row['regulars'];
                $data['graph']['irregulars'][$key] = $row['irregulars'];
                $data['graph']['lapsers'][$key] = $row['lapsers'];
                $d = date_create($row['insertedDate']);
                $data['graph']['labelDate'][$key] = date_format($d,DATE_FORMAT_GRAPH_UI);
            }
        }

        //Instamojo Records
        $data['instamojo'] = $this->dashboard_model->getAllInstamojoRecord();
        $data['instamojoMugs'] = $this->dashboard_model->getAllInstamojoMugRecords();

        $weeklyFeed = $this->dashboard_model->getWeeklyFeedBack();
        foreach($weeklyFeed as $key => $row)
        {
            $d = date_create($row['insertedDate']);
            $data['weeklyFeed'][$key]['labelDate'] = date_format($d,DATE_FORMAT_GRAPH_UI);
            $data['weeklyFeed'][$key]['feeds'] = $row['locs'];
        }
        $feedbacks = $this->dashboard_model->getAllFeedbacks($locArray);

        //Feedback net promoters code calculation
        foreach($feedbacks['feedbacks'][0] as $key => $row)
        {
            $keySplit = explode('_',$key);
            switch($keySplit[0])
            {
                case 'total':
                    $total[$keySplit[1]] = (int)$row;
                    break;
                case 'promo':
                    $promo[$keySplit[1]] = (int)$row;
                    break;
                case 'de':
                    $de[$keySplit[1]] = (int)$row;
                    break;
            }
        }

        $data['feedbacks']['overall'] = (int)(($promo['overall']/$total['overall'])*100 - ($de['overall']/$total['overall'])*100);
        $data['feedbacks']['bandra'] = (int)(($promo['bandra']/$total['bandra'])*100 - ($de['bandra']/$total['bandra'])*100);
        $data['feedbacks']['andheri'] = (int)(($promo['andheri']/$total['andheri'])*100 - ($de['andheri']/$total['andheri'])*100);
        $data['feedbacks']['kemps-corner'] = (int)(($promo['kemps-corner']/$total['kemps-corner'])*100 - ($de['kemps-corner']/$total['kemps-corner'])*100);
        if($total['colaba'] == '0')
        {
            $data['feedbacks']['colaba'] = 0;
        }
        else
        {
            $data['feedbacks']['colaba'] = (int)(($promo['colaba']/$total['colaba'])*100 - ($de['colaba']/$total['colaba'])*100);
        }
        if($total['khar'] == '0')
        {
            $data['feedbacks']['khar'] = 0;
        }
        else
        {
            $data['feedbacks']['khar'] = (int)(($promo['khar']/$total['khar'])*100 - ($de['khar']/$total['khar'])*100);
        }

        $data['allFeedbacks'] = $this->dashboard_model->getFeedbackData();
        $events = $this->dashboard_model->getAllEvents();

        if(isset($events) && myIsMultiArray($events))
        {
            $ImpEvents = array();
            $otherEvents = array();
            foreach($events as $key => $row)
            {
                $loc = $this->locations_model->getLocationDetailsById($row['eventPlace']);
                $row['locData'] = $loc['locData'];
                if($row['ifApproved'] == EVENT_WAITING)
                {
                    $ImpEvents[$key]['eventData'] = $row;
                    $ImpEvents[$key]['eventAtt'] = $this->dashboard_model->getEventAttById($row['eventId']);
                }
                else
                {
                    $otherEvents[$key]['eventData'] = $row;
                    $otherEvents[$key]['eventAtt'] = $this->dashboard_model->getEventAttById($row['eventId']);
                }
            }
            $data['eventDetails'] = array_merge($ImpEvents,$otherEvents);
        }
        $data['completedEvents'] = $this->dashboard_model->findCompletedEvents();

        $fnb = $this->dashboard_model->getAllFnB();

        if(isset($fnb) && myIsMultiArray($fnb))
        {
            foreach($fnb['fnbItems'] as $key => $row)
            {
                $data['fnbData'][$key]['fnb']= $row;
                $data['fnbData'][$key]['fnbAtt'] = $this->dashboard_model->getFnbAttById($row['fnbId']);
            }
        }
        $data['shareMeta'] = $this->dashboard_model->getRecentMeta();
        $data['olympicsMeta'] = $this->dashboard_model->getOlympicsMeta();
        //$data['feedbacks'];
		$data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
		$data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
		$data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);
        

		$this->load->view('DashboardView', $data);
	}

    public function getCommLocation($allLocs)
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $data = array();

        $allLocs = base64_decode($allLocs);
        $data['locData'] = $this->locations_model->getMultiLocs($allLocs);
        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);

        $this->load->view('SecLocSelectView', $data);
    }

    public function setCommLoc()
    {
        $refUrl = $_SERVER['HTTP_REFERER'];
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $data = array();

        $userInfo = $this->users_model->getUserDetailsById($this->userId);
        if($userInfo['status'] === true && isset($userInfo['userData'][0]['secondaryLoc']))
        {
            $data['refUrl'] = $refUrl;
            $data['locData'] = $this->locations_model->getMultiLocs($userInfo['userData'][0]['secondaryLoc']);
            $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
            $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
            $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);

            $this->load->view('SecLocSelectView', $data);
        }
        elseif($userInfo['status'] === true && isset($userInfo['userData'][0]['assignedLoc']))
        {
            $this->generalfunction_library->setSessionVariable("commSecLoc",$userInfo['userData'][0]['assignedLoc']);
            redirect($refUrl);
        }
        else
        {
            echo 'Location Error Or Invalid User!';
            die();
        }
    }

	public function setCommLocation()
    {
        $post = $this->input->post();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }

        $this->generalfunction_library->setSessionVariable("commSecLoc",$post['currentLoc']);

        redirect($post['refUrl']);
    }
    public function getCustomStats()
    {
        $post = $this->input->post();

        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }

        $locArray = $this->locations_model->getAllLocations();

        $startDate = $post['startDate'].' 07:00:00';
        $endDate = $post['endDate'].' 01:30:00';

        $data['totalMugs'] = $this->mugclub_model->getAllMugsCount($locArray);
        $data['avgChecks'] = $this->dashboard_model->getAvgCheckins($startDate,$endDate,$locArray);
        $data['Regulars'] = $this->dashboard_model->getRegulars($startDate,$endDate,$locArray);
        $data['Irregulars'] = $this->dashboard_model->getIrregulars($startDate,$endDate,$locArray);
        $data['lapsers'] = $this->dashboard_model->getLapsers($startDate,$endDate,$locArray);

        echo json_encode($data);

    }

    public function saveRecord()
    {
        $post = $this->input->post();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }

        $gotData = $this->dashboard_model->getDashboardRecord();
        if($gotData['status'] === false)
        {
            $this->dashboard_model->saveDashboardRecord($post);
        }
        $data['status'] = true;
        echo json_encode($data);
    }

    /*public function instaMojoNewMember()
    {
        $post = $this->input->post();

        // Get the MAC from the POST data
        if(isset($post['mac']))
        {
            $mac_provided = $post['mac'];
            unset($post['mac']);
            $ver = explode('.', phpversion());
            $major = (int) $ver[0];
            $minor = (int) $ver[1];
            if($major >= 5 and $minor >= 4){
                ksort($post, SORT_STRING | SORT_FLAG_CASE);
            }
            else{
                uksort($post, 'strcasecmp');
            }

            $mac_calculated = hash_hmac("sha1", implode("|", $post), "34e1f545c8f7745c624752d319ae9b26");
            if($mac_provided == $mac_calculated){
                if($post['status'] == "Credit"){

                    $tagName = '';
                    $dob = '';
                    $homeBase = '';

                    $custom_array = json_decode($post['custom_fields'],true);
                    foreach($custom_array as $key => $row)
                    {
                        if($row['label'] == "Name On Tag")
                        {
                            if(isStringSet($row['value']))
                            {
                                $tagName = $row['value'];
                            }
                        }
                        elseif($row['label'] == "Date of Birth")
                        {
                            $d = date_parse($row['value']);
                            if($d)
                            {
                                $dob = $d['year'].'-'.$d['month'].'-'.$d['day'];
                            }
                        }
                        elseif()
                        $mugNum = $row['value'];
                    }

                    $mugStatus = $this->mugclub_model->getMugDataById($mugNum);

                    if($mugStatus['status'] === false)
                    {
                        $details = array(
                            'buyer_name' => $post['buyer_name'],
                            'payment_id' => $post['payment_id'],
                            'quantity' => $post['quantity'],
                            'buyer_email' =>  $post['buyer'],
                            'buyer_phone' => $post['buyer_phone'],
                            'mugNo' => $mugNum
                        );
                        $this->sendemail_library->instamojoFailMail($details);
                    }
                    $details = array(
                        "mugId" => $mugNum,
                        "buyerName" => $post['buyer_name'],
                        "buyerEmail" => $post['buyer'],
                        "price" => $post['amount'],
                        "paymentId" => $post['payment_id'],
                        "status" => 1,
                        "isApproved" => 0,
                        "insertedDT" => date('Y-m-d H:i:s')
                    );
                    $this->dashboard_model->saveInstaMojoRecord($details);
                    echo 'Saved with success';
                }
                else{
                    $mugNum = '';
                    $custom_array = json_decode($post['custom_fields'],true);
                    foreach($custom_array as $key => $row)
                    {
                        $mugNum = $row['value'];
                    }

                    $details = array(
                        "mugId" => $mugNum,
                        "buyerName" => $post['buyer_name'],
                        "buyerEmail" => $post['buyer'],
                        "price" => $post['amount'],
                        "paymentId" => $post['payment_id'],
                        "status" => 1,
                        "isApproved" => 0,
                        "insertedDT" => date('Y-m-d H:i:s')
                    );
                    $this->dashboard_model->saveInstaMojoRecord($details);
                    echo 'Saved with failed';
                }
            }
            else{
                echo "MAC mismatch";
            }
        }
        else
        {
            echo "MAC Not Found!";
        }
    }*/
    public function instaMojoRecord()
    {
        $post = $this->input->post();

        // Get the MAC from the POST data
        if(isset($post['mac']))
        {
            $mac_provided = $post['mac'];
            unset($post['mac']);
            $ver = explode('.', phpversion());
            $major = (int) $ver[0];
            $minor = (int) $ver[1];
            if($major >= 5 and $minor >= 4){
                ksort($post, SORT_STRING | SORT_FLAG_CASE);
            }
            else{
                uksort($post, 'strcasecmp');
            }

            $mac_calculated = hash_hmac("sha1", implode("|", $post), "34e1f545c8f7745c624752d319ae9b26");
            if($mac_provided == $mac_calculated){
                if($post['status'] == "Credit"){

                    $mugNum = '';
                    $custom_array = json_decode($post['custom_fields'],true);
                    foreach($custom_array as $key => $row)
                    {
                        $mugNum = $row['value'];
                    }

                    $mugStatus = $this->mugclub_model->getMugDataById($mugNum);

                    if($mugStatus['status'] === false)
                    {
                        $details = array(
                            'buyer_name' => $post['buyer_name'],
                            'payment_id' => $post['payment_id'],
                            'quantity' => $post['quantity'],
                            'buyer_email' =>  $post['buyer'],
                            'buyer_phone' => $post['buyer_phone'],
                            'mugNo' => $mugNum
                        );
                        $this->sendemail_library->instamojoFailMail($details);
                    }
                    $details = array(
                        "mugId" => $mugNum,
                        "buyerName" => $post['buyer_name'],
                        "buyerEmail" => $post['buyer'],
                        "price" => $post['amount'],
                        "paymentId" => $post['payment_id'],
                        "status" => 1,
                        "isApproved" => 0,
                        "insertedDT" => date('Y-m-d H:i:s')
                    );
                    $this->dashboard_model->saveInstaMojoRecord($details);
                    echo 'Saved with success';
                }
                else{
                    $mugNum = '';
                    $custom_array = json_decode($post['custom_fields'],true);
                    foreach($custom_array as $key => $row)
                    {
                        $mugNum = $row['value'];
                    }

                    $details = array(
                        "mugId" => $mugNum,
                        "buyerName" => $post['buyer_name'],
                        "buyerEmail" => $post['buyer'],
                        "price" => $post['amount'],
                        "paymentId" => $post['payment_id'],
                        "status" => 1,
                        "isApproved" => 0,
                        "insertedDT" => date('Y-m-d H:i:s')
                    );
                    $this->dashboard_model->saveInstaMojoRecord($details);
                    echo 'Saved with failed';
                }
            }
            else{
                echo "MAC mismatch";
            }
        }
        else
        {
            echo "MAC Not Found!";
        }
    }

    public function setInstamojoDone($responseType = RESPONSE_JSON,$id)
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $details = array("isApproved"=>1);
        $this->dashboard_model->updateInstaMojoRecord($id,$details);

        $data['status'] = true;
        if($responseType == RESPONSE_JSON)
        {
            echo json_encode($data);
        }
        else
        {
            return $data;
        }
    }

    public function saveFeedback($responseType = RESPONSE_RETURN)
    {
        $post = $this->input->post();

        if(isSessionVariableSet($this->isUserSession) === false || $this->userType == SERVER_USER)
        {
            if($responseType == RESPONSE_JSON)
            {
                $data['status'] = false;
                $data['pageUrl'] = base_url();
            }
            else
            {
                redirect(base_url());
            }

        }
        $post['overallRating'] = array_values($post['overallRating']);
        $post['userGender'] = array_values($post['userGender']);
        $post['userAge'] = array_values($post['userAge']);
        $post['feedbackLoc'] = array_values($post['feedbackLoc']);

        $insert_values = array();
        for($i=0;$i<count($post['overallRating']);$i++)
        {
            if($post['overallRating'][$i] != '')
            {
                $insert_values[] = array(
                    'overallRating' => $post['overallRating'][$i],
                    'userGender' => (!isset($post['userGender'][$i])) ? null : $post['userGender'][$i],
                    'userAge' => (!isset($post['userAge'][$i])) ? null : $post['userAge'][$i],
                    'feedbackLoc' => (!isset($post['feedbackLoc'][$i])) ? null : $post['feedbackLoc'][$i],
                    'insertedDateTime' => date('Y-m-d H:i:s')
                );
            }
        }
        $this->dashboard_model->insertFeedBack($insert_values);

        $logDetails = array(
            'logMessage' => 'Function: saveFeedback, User: '.$this->userId,
            'fromWhere' => 'Dashboard',
            'insertedDT' => date('Y-m-d H:i:s')
        );
        $this->dashboard_model->saveDashLogs($logDetails);

        if($responseType == RESPONSE_JSON)
        {
            $data['status'] = true;
            echo json_encode($data);
        }
        else
        {
            redirect(base_url().'dashboard');
        }

    }

    public function savefnb()
    {
        $post = $this->input->post();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        if($post['itemType'] == '1')
        {
            $sortNum = $this->dashboard_model->getTopSortNum();
            $newNum = ((int)$sortNum['sortOrder'])+1;
            $details = array(
                'itemType'=> $post['itemType'],
                'itemName' => $post['itemName'],
                'itemHeadline'=> $post['itemHeadline'],
                'itemDescription' => $post['itemDescription'],
                'priceFull' => $post['priceFull'],
                'priceHalf' => $post['priceHalf'],
                'sortOrder' => $newNum,
                'insertedBy' => $this->userId
            );
        }
        else
        {
            $details = array(
                'itemType'=> $post['itemType'],
                'itemName' => $post['itemName'],
                'itemHeadline'=> $post['itemHeadline'],
                'itemDescription' => $post['itemDescription'],
                'priceFull' => $post['priceFull'],
                'priceHalf' => $post['priceHalf'],
                'insertedBy' => $this->userId
            );
        }
        $fnbId = $this->dashboard_model->saveFnbRecord($details);

        $fnbShareLink = MOBILE_URL.'?page/fnbshare/fnb-'.$fnbId;

        $fnbLink = array(
            'fnbShareLink' => $fnbShareLink
        );
        $this->dashboard_model->updateFnbRecord($fnbLink,$fnbId);
        if(isset($post['attachment']) && isStringSet($post['attachment']))
        {
            $img_names = explode(',',$post['attachment']);
            for($i=0;$i<count($img_names);$i++)
            {
                $attArr = array(
                    'fnbId' => $fnbId,
                    'filename'=> $img_names[$i],
                    'attachmentType' => $post['itemType']
                );
                $this->dashboard_model->saveFnbAttachment($attArr);
            }
        }

        $logDetails = array(
            'logMessage' => 'Function: saveFnb, User: '.$this->userId,
            'fromWhere' => 'Dashboard',
            'insertedDT' => date('Y-m-d H:i:s')
        );
        $this->dashboard_model->saveDashLogs($logDetails);

        redirect(base_url().'dashboard');

    }

    public function beerLocation($fnbId)
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $locRecord = $this->dashboard_model->getTagLocsFnb($fnbId);
        if(isset($locRecord) && myIsMultiArray($locRecord))
        {
            $data['status'] = true;
            $data['locData'] = $locRecord;
        }
        else
        {
            $data['status'] = false;
        }
        $logDetails = array(
            'logMessage' => 'Function: beerLocation, User: '.$this->userId,
            'fromWhere' => 'Dashboard',
            'insertedDT' => date('Y-m-d H:i:s')
        );
        $this->dashboard_model->saveDashLogs($logDetails);
        echo json_encode($data);
    }
    public function fnbTagSet($fnbId)
    {
        $post = $this->input->post();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }

        $this->dashboard_model->updateBeerLocTag($post,$fnbId);

        $data['status'] = true;
        echo json_encode($data);
        $logDetails = array(
            'logMessage' => 'Function: fnbTagSet, User: '.$this->userId,
            'fromWhere' => 'Dashboard',
            'insertedDT' => date('Y-m-d H:i:s')
        );
        $this->dashboard_model->saveDashLogs($logDetails);

    }
    public function uploadFiles()
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
                $config['upload_path'] = '../mobile/'.FOOD_PATH_NORMAL; // FOOD_PATH_THUMB; //'uploads/food/';
                if(isset($_POST['itemType']) && $_POST['itemType'] == '2')
                {
                    $config['upload_path'] = '../mobile/'.BEVERAGE_PATH_NORMAL; //BEVERAGE_PATH_THUMB; //uploads/beverage/';
                }
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size']      = '0';
                $config['overwrite']     = TRUE;

                $this->upload->initialize($config);
                if(!$this->upload->do_upload('attachment'))
                {
                    log_message('error','Fnb: '.$this->upload->display_errors());
                    $data['status'] = false;
                    $data['errorMsg'] = $this->upload->display_errors();
                    echo json_encode($data);
                    return false;
                }
                else
                {
                    $upload_data = $this->upload->data();
                    $attchmentArr= $this->image_thumb($upload_data['file_path'],$upload_data['file_name']);
                    if($attchmentArr == 'error')
                    {
                        $data['status'] = false;
                        $data['errorMsg'] = 'Error in resizing image!';
                        echo json_encode($data);
                        return false;
                    }
                    else
                    {
                        echo $attchmentArr;
                    }
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
            $data['errorMsg'] = 'No Image Files Received!';
            echo json_encode($data);
            return false;
        }
    }
    function image_thumb( $image_path, $img_name)
    {
        $image_thumb = $image_path.'thumb/'.$img_name;

        // LOAD LIBRARY
        $this->load->library( 'image_lib' );

        // CONFIGURE IMAGE LIBRARY
        $config['image_library']    = 'gd2';
        $config['source_image']     = $image_path.$img_name;
        $config['new_image']        = $image_thumb;
        $config['quality']          = 90;
        $config['maintain_ratio']   = TRUE;
        $config['height']           = 480;
        $config['width']            = 690;

        $this->image_lib->initialize( $config );
        if(!$this->image_lib->resize())
        {
            $this->image_lib->clear();
            log_message('error',$image_path.': '.$this->image_lib->display_errors());
            return 'error';
        }
        else
        {
            $this->image_lib->clear();
            return $img_name;
        }
    }

    public function cropEventImage()
    {
        $data = $this->input->post()['data'];
        $src = $data['imgUrl'];
        $img = $data['imgData'];
        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);
        $filename = explode('.',basename($src));
        $dst = './uploads/events/thumb/'.$filename[0].'_cropped'.'.'.$filename[1];
        if(file_put_contents($dst, $data) === false)
        {
            $response = Array(
                "status" => 'error',
                "message" => 'Failed to save image'
            );
        }
        else
        {
            $response = Array(
                "status" => 'success',
                "url" => $dst
            );
        }
        echo json_encode($response);
    }

    /*public function cropEventImage()
    {
        $post = $this->input->post()['data'];

        $imgUrl = $post['imgUrl'];
        // original sizes
        $what = getimagesize($imgUrl);

        $imgInitW = $what[0];
        $imgInitH = $what[1];

        // resized sizes
        $imgW = $post['width'];
        $imgH = $post['height'];

        // offsets
        $imgY1 = $post['y'];
        $imgX1 = $post['x'];

        // crop box
        $cropW = $post['cWidth'];
        $cropH = $post['cHeight'];

        // rotation angle
        $angle = $post['rotate'];

        $jpeg_quality = 100;

        $filename = explode('.',basename($imgUrl));
        $output_filename = './uploads/events/thumb/'.$filename[0].'_cropped';


        switch(strtolower($what['mime']))
        {
            case 'image/png':
                $source_image = imagecreatefrompng($imgUrl);
                $type = '.png';
                break;
            case 'image/jpeg':
                $source_image = imagecreatefromjpeg($imgUrl);
                error_log("jpg");
                $type = '.jpeg';
                break;
            case 'image/gif':
                $source_image = imagecreatefromgif($imgUrl);
                $type = '.gif';
                break;
            default:
                $response = Array(
                "status" => 'error',
                "message" => 'image type not supported'
            );
        }

        if(isset($source_image))
        {
            // resize the original image to size of editor
            $resizedImage = imagecreatetruecolor($imgW, $imgH);

            imagecopyresampled($resizedImage, $source_image, 0, 0, 0, 0, $imgW, $imgH, $imgInitW, $imgInitH);
            // rotate the rezized image
            $rotated_image = imagerotate($resizedImage, -$angle, 0);
            // find new width & height of rotated image
            $rotated_width = imagesx($rotated_image);
            $rotated_height = imagesy($rotated_image);
            // diff between rotated & original sizes
            $dx = $rotated_width - $imgW;
            $dy = $rotated_height - $imgH;
            // crop rotated image to fit into original rezized rectangle
            $cropped_rotated_image = imagecreatetruecolor($imgW, $imgH);
            imagecolortransparent($cropped_rotated_image, imagecolorallocate($cropped_rotated_image, 0, 0, 0));
            imagecopyresampled($cropped_rotated_image, $rotated_image, 0, 0, $dx / 2, $dy / 2, $imgW, $imgH, $imgW, $imgH);
            // crop image into selected area
            $final_image = imagecreatetruecolor($cropW, $cropH);
            imagecolortransparent($final_image, imagecolorallocate($final_image, 0, 0, 0));
            imagecopyresampled($final_image, $cropped_rotated_image, 0, 0, $imgX1, $imgY1, $cropW, $cropH, $cropW, $cropH);
            // finally output png image
            //imagepng($final_image, $output_filename.$type, $png_quality);
            imagejpeg($final_image, $output_filename.$type, $jpeg_quality);
            $response = Array(
                "status" => 'success',
                "url" => $output_filename.$type
            );
        }

        echo json_encode($response);

    }*/

    public function uploadEventFiles()
    {
        /*if(strpos($_SERVER['HTTP_HOST'],'doolally.io'))
        {

        }*/
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Session Timeout, Please Login Again!';
            echo json_encode($data);
            return false;
        }

        $this->load->library('upload');
        if(isset($_FILES))
        {
            if($_FILES['attachment']['error'] != 1)
            {
                $filePath = $_FILES['attachment']['name'];
                $fileName = preg_replace('/\(|\)/','',$filePath);
                $fileName = preg_replace('/[^a-zA-Z0-9.]\.]/', '', $fileName);
                $fileName = str_replace(' ','_',$fileName);
                $fileName = time().'_'.$fileName;
                $config = array();
                $config['upload_path'] = '../mobile/uploads/events/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size']      = '0';
                $config['overwrite']     = TRUE;
                $config['file_name']     = $fileName;

                $this->upload->initialize($config);
                if(!$this->upload->do_upload('attachment'))
                {
                    log_message('error','Event: '.$this->upload->display_errors());
                    $data['status'] = false;
                    $data['errorMsg'] = $this->upload->display_errors();
                    echo json_encode($data);
                    return false;
                }
                else
                {
                    $upload_data = $this->upload->data();
                    $attchmentArr= $this->image_thumb($upload_data['file_path'],$upload_data['file_name']);
                    if($attchmentArr == 'error')
                    {
                        $data['status'] = false;
                        $data['errorMsg'] = 'Error in resizing image!';
                        echo json_encode($data);
                        return false;
                    }
                    else
                    {
                        echo $attchmentArr;
                    }
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
            $data['errorMsg'] = 'No Image Files Received!';
            echo json_encode($data);
            return false;
        }
    }

    function saveEvent()
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $post = $this->input->post();

        if(isset($post['attachment']))
        {
            $attachement = $post['attachment'];
            unset($post['attachment']);
        }

        $Edetails = array(
            "startTime" => date('H:i', strtotime($post['startTime'])),
            "endTime" => date('H:i', strtotime($post['endTime'])),
            "eventPlace" => $post['eventPlace'],
            "eventDate" => $post['eventDate']
        );
        $eventSpace = $this->dashboard_model->checkEventSpace($Edetails);

        $userId = $this->userId;
        if($eventSpace['status'] === false)
        {
            if(isset($post['creatorPhone']) && isset($post['creatorEmail']))
            {
                $userStatus = $this->checkPublicUser($post['creatorEmail'],$post['creatorPhone']);

                if($userStatus['status'] === false)
                {
                    $userId = $userStatus['userData']['userId'];
                }
                else
                {
                    $userName = explode(' ',$post['creatorName']);
                    if(count($userName)< 2)
                    {
                        $userName[1] = '';
                    }

                    $user = array(
                        'userName' => $post['creatorEmail'],
                        'firstName' => $userName[0],
                        'lastName' => $userName[1],
                        'password' => md5($post['creatorPhone']),
                        'LoginPin' => null,
                        'isPinChanged' => null,
                        'emailId' => $post['creatorEmail'],
                        'mobNum' => $post['creatorPhone'],
                        'userType' => '4',
                        'assignedLoc' => null,
                        'ifActive' => '1',
                        'insertedDate' => date('Y-m-d H:i:s'),
                        'updateDate' => date('Y-m-d H:i:s'),
                        'updatedBy' => $post['creatorName'],
                        'lastLogin' => date('Y-m-d H:i:s')
                    );

                    $userId = $this->users_model->savePublicUser($user);
                }
            }

            $post['userId'] = $userId;

            $post['startTime'] = date('H:i', strtotime($post['startTime']));
            $post['endTime'] = date('H:i', strtotime($post['endTime']));
            $eveSlug = slugify($post['eventName']);
            $post['eventSlug'] = $eveSlug;
            $post['eventShareLink'] = MOBILE_URL.'?page/events/'.$eveSlug;
            $post['shortUrl'] = null;

            $fromEmail = '';
            $fromPass = '';
            $isUserSet = false;
            if(isset($post['senderEmail']) && isStringSet($post['senderEmail'])
                && isset($post['senderPass']) && isStringSet($post['senderPass']))
            {
                $fromEmail = $post['senderEmail'];
                $fromPass = $post['senderPass'];
                $isUserSet = true;
                unset($post['senderEmail'],$post['senderPass']);
            }
            $eventId = $this->dashboard_model->saveEventRecord($post);

            // Adding event slug to new table
            $newSlugTab = array(
                'eventId' => $eventId,
                'eventSlug' => $eveSlug,
                'insertedDateTime' => date('Y-m-d H:i:s')
            );
            $this->dashboard_model->saveEventSlug($newSlugTab);

            $shortDWName = $this->googleurlapi->shorten(MOBILE_URL.'?page/events/'.$eveSlug);
            if($shortDWName !== false)
            {
                $details['shortUrl'] = $shortDWName;
                $this->dashboard_model->updateEventRecord($details,$eventId);
            }

            $img_names = array();
            if(isset($attachement))
            {
                $img_names = explode(',',$attachement);
                for($i=0;$i<count($img_names);$i++)
                {
                    $attArr = array(
                        'eventId' => $eventId,
                        'filename'=> $img_names[$i],
                        'attachmentType' => '1'
                    );
                    $this->dashboard_model->saveEventAttachment($attArr);
                }
            }
            $mailEvent= array(
                'creatorName' => $post['creatorName'],
                'creatorEmail' => $post['creatorEmail'],
                'creatorPhone' => $post['creatorPhone'],
                'eventName' => $post['eventName'],
                'eventPlace' => $post['eventPlace']
            );

            if($isUserSet)
            {
                $mailEvent['fromEmail'] = $fromEmail;
                $mailEvent['fromPass'] = $fromPass;
            }
            //$loc = $this->locations_model->getLocationDetailsById($post['eventPlace']);

            $this->sendemail_library->newEventMail($mailEvent);
            $data['status'] = true;
        }
        else
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Sorry, This time slot is already booked!';
        }
        echo json_encode($data);
        $logDetails = array(
            'logMessage' => 'Function: saveEvent, User: '.$this->userId,
            'fromWhere' => 'Dashboard',
            'insertedDT' => date('Y-m-d H:i:s')
        );
        $this->dashboard_model->saveDashLogs($logDetails);

    }
    
    function editEvent($eventId)
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $data = array();
        $events = $this->dashboard_model->getEventById($eventId);
        if(isset($events) && myIsMultiArray($events))
        {
            foreach($events as $key => $row)
            {
                $data['eventInfo'][$key]['eventData'] = $row;
                $data['eventInfo'][$key]['eventAtt'] = $this->dashboard_model->getEventAttById($row['eventId']);
            }
        }

        $locArray = $this->locations_model->getAllLocations();
        $data['locations'] = $locArray;
        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);

        $this->load->view('EventEditView', $data);
    }

    function updateEvent()
    {
        //checking for session
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $post = $this->input->post();
        $data = array();
        $isImpChange = false;
        $oldImgId = '';
        $impChanges = array('eventName','eventDescription','eventDate','startTime','endTime','costType',
                        'eventPrice','eventPlace','creatorName','creatorPhone','creatorEmail','eventCapacity',
                        'ifMicRequired','ifProjectorRequired');

        $changeCheck = array();
        $changesRecord = array();
        $changesMade = array();
        $isEventNameChanged = false;

        $eventId = $post['eventId'];
        unset($post['eventId']);
        $eventDetails = $this->dashboard_model->getFullEventInfoById($eventId);

        //Separating attachment
        if(isset($post['attachment']))
        {
            if($post['attachment'] != '')
            {
                $isImpChange = true;
                $changesMade['attachment'] = $post['oldEventImg'].';#;'.$post['attachment'];
                $attachement = $post['attachment'];
                $oldImgId = $post['oldEventImgId'];
                unset($post['attachment'],$post['oldEventImg'],$post['oldEventImgId']);
            }
            else
            {
                unset($post['attachment']);
                if(isset($post['oldEventImg']) && !isStringSet($post['oldEventImg']))
                {
                    unset($post['oldEventImg']);
                }
                if(isset($post['oldEventImgId']) && !isStringSet($post['oldEventImgId']))
                {
                    unset($post['oldEventImgId']);
                }
            }
        }

        $eventOldInfo = $eventDetails[0];

        //Checking for the actual change in the event details
        foreach($eventOldInfo as $key => $row)
        {
            if(isset($post[$key]))
            {
                if($post[$key] != $row)
                {
                    if(myInArray($key,$impChanges))
                    {
                        $isImpChange = true; // change is detected in the event details;
                        $changeCheck[] = $key;
                        $changesRecord[$key] = $row;
                        if($key == 'eventPlace')
                        {
                            $oldLoc = $this->locations_model->getLocationDetailsById($row);
                            $newLoc = $this->locations_model->getLocationDetailsById($post[$key]);
                            $changesMade[$key] = $oldLoc['locData'][0]['locName'].';#;'.$newLoc['locData'][0]['locName'];
                        }
                        elseif($key == 'ifMicRequired' || $key == 'ifProjectorRequired')
                        {
                            $ynPoll = array(
                                '1' => 'Yes',
                                '2' => 'No'
                            );
                            $changesMade[$key] = $ynPoll[$row].';#;'.$ynPoll[$post[$key]];
                        }
                        else
                        {
                            $changesMade[$key] = $row.';#;'.$post[$key];
                        }
                    }
                }
            }
        }


        if($eventDetails[0]['ifApproved'] == EVENT_WAITING || $eventDetails[0]['ifActive'] == NOT_ACTIVE)
        {
            if(myInArray('eventName', $changeCheck))
            {
                $isEventNameChanged = true;
                $eveSlug = slugify($post['eventName']);
                $post['eventSlug'] = $eveSlug;
                $post['eventShareLink'] = MOBILE_URL.'?page/events/'.$eveSlug;
                $post['shortUrl'] = null;

                // Adding event slug to new table
                $newSlugTab = array(
                    'eventId' => $eventId,
                    'eventSlug' => $eveSlug,
                    'insertedDateTime' => date('Y-m-d H:i:s')
                );
                $this->dashboard_model->saveEventSlug($newSlugTab);

                $shortDWName = $this->googleurlapi->shorten(MOBILE_URL.'?page/events/'.$eveSlug);
                if($shortDWName !== false)
                {
                    $post['shortUrl'] = $shortDWName;
                }
            }
            //If organiser details are changed
            if(myInArray('creatorEmail',$changeCheck) && myInArray('creatorPhone',$changeCheck))
            {
                $userStatus = $this->checkPublicUser($post['creatorEmail'],$post['creatorPhone']);

                if($userStatus['status'] === false)
                {
                    $post['userId'] = $userStatus['userData']['userId'];
                }
                else
                {
                    $userName = explode(' ',$post['creatorName']);
                    if(count($userName)< 2)
                    {
                        $userName[1] = '';
                    }

                    $user = array(
                        'userName' => $post['creatorEmail'],
                        'firstName' => $userName[0],
                        'lastName' => $userName[1],
                        'password' => md5($post['creatorPhone']),
                        'LoginPin' => null,
                        'isPinChanged' => null,
                        'emailId' => $post['creatorEmail'],
                        'mobNum' => $post['creatorPhone'],
                        'userType' => '4',
                        'assignedLoc' => null,
                        'ifActive' => '1',
                        'insertedDate' => date('Y-m-d H:i:s'),
                        'updateDate' => date('Y-m-d H:i:s'),
                        'updatedBy' => $post['creatorName'],
                        'lastLogin' => date('Y-m-d H:i:s')
                    );

                    $post['userId'] = $this->users_model->savePublicUser($user);
                }
            }


            $post['startTime'] = date('H:i', strtotime($post['startTime']));
            $post['endTime'] = date('H:i', strtotime($post['endTime']));
            if(!isset($post['isEventEverywhere']))
            {
                $post['isEventEverywhere'] = '2';
            }
            if(isset($post['costType']) && $post['costType'] == EVENT_DOOLALLY_FEE)
            {
                $post['doolallyFee'] = $post['eventPrice'];
            }
            $this->dashboard_model->updateEventRecord($post,$eventId);

            if(isset($attachement) && $attachement != '')
            {
                $img_names = explode(',',$attachement);
                for($i=0;$i<count($img_names);$i++)
                {
                    $attArr = array(
                        'eventId' => $eventId,
                        'filename'=> $img_names[$i],
                        'attachmentType' => '1'
                    );
                    $this->dashboard_model->saveEventAttachment($attArr);
                }
                if($oldImgId != '')
                {
                    $this->dashboard_model->eventAttDelete($oldImgId);
                }
                //Creating whatsapp image
                $imgPath = '/var/www/html/mobile/'.EVENT_PATH_THUMB;
                $lowResImg = $this->image_thumb_low_res($imgPath,$img_names[0]);

                $low_size = (int)$this->human_filesize(filesize($imgPath.$lowResImg),0);
                if($low_size>300)
                {
                    //Sending mail if image resize fails
                    if(isset($post['eventName']))
                    {
                        $mailData = array(
                            'locId' => $eventOldInfo['eventPlace'],
                            'eventName' => $post['eventName']
                        );
                    }
                    else
                    {
                        $mailData = array(
                            'locId' => $eventOldInfo['eventPlace'],
                            'eventName' => $eventOldInfo['eventName']
                        );
                    }
                    $this->sendemail_library->lowResImageFailMail($mailData);
                    log_message('error','Low res Image size is more than 300KB eve-'.$eventId);
                }
                else
                {
                    $attDetails = array(
                        'lowResImage' => $lowResImg
                    );
                    $this->dashboard_model->updateEventAttachment($attDetails,$eventId);
                }
            }
            $changesRecord['eventId'] = $eventId;
            $changesRecord['fromWhere'] = 'Dashboard';
            $changesRecord['insertedDT'] = date('Y-m-d H:i:s');
            $changesRecord['isPending'] = 1;
            $this->dashboard_model->saveEventChangeRecord($changesRecord);
            $data['status'] = true;
        }
        else
        {
            //$instaLinkFailed = false;
            //if(isset($eventDetails) && myIsArray($eventDetails) && $isImpChange)
            //{
                /*if(isset($eventDetails[0]['instaSlug']) && isStringSet($eventDetails[0]['instaSlug']))
                {
                    //Deleting old link
                    $this->curl_library->archiveInstaLink($eventDetails[0]['instaSlug']);
                }*/
                //Get location info
                //$locInfo = $this->locations_model->getLocationDetailsById($post['eventPlace']);

                // Getting image upload url from api;
                //$instaImgLink = $this->curl_library->getInstaImageLink();
                //$donePost = array();
                /*if($instaImgLink['success'] === true)
                {
                    if(isset($attachement) && isStringSet($attachement))
                    {
                        $coverImg =  $this->curl_library->uploadInstaImage($instaImgLink['upload_url'],$attachement);
                    }
                    else
                    {
                        $coverImg =  $this->curl_library->uploadInstaImage($instaImgLink['upload_url'],$eventDetails[0]['filename']);
                    }
                    if(isset($coverImg) && myIsMultiArray($coverImg) && isset($coverImg['url']))
                    {
                        $postData = array(
                            'title' => $post['eventName'],
                            'description' => $post['eventDescription'],
                            'currency' => 'INR',
                            'base_price' => $post['eventPrice'],
                            'start_date' => $post['eventDate'].' '.date("H:i", strtotime($post['startTime'])),
                            'end_date' => $post['eventDate'].' '.date("H:i", strtotime($post['endTime'])),
                            'venue' => $locInfo['locData'][0]['locName'].', Doolally Taproom',
                            'redirect_url' => MOBILE_URL.'?event='.$eventId.'&hash='.encrypt_data('EV-'.$eventId),
                            'cover_image_json' => json_encode($coverImg),
                            'timezone' => 'Asia/Kolkata'
                        );
                        $donePost = $this->curl_library->createInstaLink($postData);
                    }
                }
                if(!myIsMultiArray($donePost)) //Creating event without image
                {
                    if($post['costType'] == EVENT_FREE)
                    {
                        $postData = array(
                            'title' => $post['eventName'],
                            'description' => $post['eventDescription'],
                            'currency' => 'INR',
                            'base_price' => '0',
                            'start_date' => $post['eventDate'].' '.date("H:i", strtotime($post['startTime'])),
                            'end_date' => $post['eventDate'].' '.date("H:i", strtotime($post['endTime'])),
                            'venue' => $locInfo['locData'][0]['locName'].', Doolally Taproom',
                            'redirect_url' => MOBILE_URL.'?event='.$eventId.'&hash='.encrypt_data('EV-'.$eventId),
                            'timezone' => 'Asia/Kolkata'
                        );
                    }
                    else
                    {
                        $postData = array(
                            'title' => $post['eventName'],
                            'description' => $post['eventDescription'],
                            'currency' => 'INR',
                            'base_price' => $post['eventPrice'],
                            'start_date' => $post['eventDate'].' '.date("H:i", strtotime($post['startTime'])),
                            'end_date' => $post['eventDate'].' '.date("H:i", strtotime($post['endTime'])),
                            'venue' => $locInfo['locData'][0]['locName'].', Doolally Taproom',
                            'redirect_url' => MOBILE_URL.'?event='.$eventId.'&hash='.encrypt_data('EV-'.$eventId),
                            'timezone' => 'Asia/Kolkata'
                        );
                    }
                    $donePost = $this->curl_library->createInstaLink($postData);
                }*/

                /*if(isset($donePost['link']))
                {
                    if(isset($donePost['link']['shorturl']))
                    {
                        $post['eventPaymentLink'] = $donePost['link']['shorturl'];
                        $post['instaSlug'] = $donePost['link']['slug'];
                    }
                    else
                    {
                        $post['eventPaymentLink'] = $donePost['link']['url'];
                        $post['instaSlug'] = $donePost['link']['slug'];
                    }
                }
                else
                {
                    $instaLinkFailed = true;
                }*/
            //}

            /*if($instaLinkFailed === true)
            {
                $data['status'] = false;
                $data['errorMsg'] = 'Failed To Create Link On Instamojo! Try Again Later';
            }
            else
            {

            }*/
            if(myInArray('eventName', $changeCheck))
            {
                $isEventNameChanged = true;
                $eveSlug = slugify($post['eventName']);
                $post['eventSlug'] = $eveSlug;
                $post['eventShareLink'] = MOBILE_URL.'?page/events/'.$eveSlug;
                $post['shortUrl'] = null;

                // Adding event slug to new table
                $newSlugTab = array(
                    'eventId' => $eventId,
                    'eventSlug' => $eveSlug,
                    'insertedDateTime' => date('Y-m-d H:i:s')
                );
                $this->dashboard_model->saveEventSlug($newSlugTab);

                $shortDWName = $this->googleurlapi->shorten(MOBILE_URL.'?page/events/'.$eveSlug);
                if($shortDWName !== false)
                {
                    $post['shortUrl'] = $shortDWName;
                }
            }

            //If organiser details are changed
            if(myInArray('creatorEmail',$changeCheck) && myInArray('creatorPhone',$changeCheck))
            {
                $userStatus = $this->checkPublicUser($post['creatorEmail'],$post['creatorPhone']);

                if($userStatus['status'] === false)
                {
                    $post['userId'] = $userStatus['userData']['userId'];
                }
                else
                {
                    $userName = explode(' ',$post['creatorName']);
                    if(count($userName)< 2)
                    {
                        $userName[1] = '';
                    }

                    $user = array(
                        'userName' => $post['creatorEmail'],
                        'firstName' => $userName[0],
                        'lastName' => $userName[1],
                        'password' => md5($post['creatorPhone']),
                        'LoginPin' => null,
                        'isPinChanged' => null,
                        'emailId' => $post['creatorEmail'],
                        'mobNum' => $post['creatorPhone'],
                        'userType' => '4',
                        'assignedLoc' => null,
                        'ifActive' => '1',
                        'insertedDate' => date('Y-m-d H:i:s'),
                        'updateDate' => date('Y-m-d H:i:s'),
                        'updatedBy' => $post['creatorName'],
                        'lastLogin' => date('Y-m-d H:i:s')
                    );

                    $post['userId'] = $this->users_model->savePublicUser($user);
                }
            }

            //Check if event date is changed or not
            if(myInArray('eventDate',$changeCheck))
            {
                $orgCode = $this->dashboard_model->getOrgCoupon($eventId);
                if(isset($orgCode) && myIsArray($orgCode))
                {
                    $details = array(
                        'validFromDate'=>$post['eventDate']
                    );
                    $this->dashboard_model->updateOfferCode($details,$orgCode['id']);
                }
                if($isEventNameChanged)
                {
                    $dateMailData = array(
                        'eventName' => $post['eventName'],
                        'eventSlug' => $post['eventSlug'],
                        'oldDate' => $eventDetails[0]['eventDate'],
                        'newDate' => $post['eventDate'],
                        'costType' => $eventDetails[0]['costType'],
                        'eventPlace' => $eventDetails[0]['eventPlace']
                    );
                }
                else
                {
                    $dateMailData = array(
                        'eventName' => $eventDetails[0]['eventName'],
                        'eventSlug' => $eventDetails[0]['eventSlug'],
                        'oldDate' => $eventDetails[0]['eventDate'],
                        'newDate' => $post['eventDate'],
                        'costType' => $eventDetails[0]['costType'],
                        'eventPlace' => $eventDetails[0]['eventPlace']
                    );
                }

                $allAttendees = $this->dashboard_model->getJoinersInfo($eventId);
                if(isset($allAttendees) && myIsArray($allAttendees))
                {
                    foreach($allAttendees as $key => $row)
                    {
                        $dateMailData['attendeeName'] = $row['firstName'];
                        $dateMailData['emailId'] = $row['emailId'];
                        $this->sendemail_library->attendeeChangeMail($dateMailData);
                    }
                }
            }

            $post['startTime'] = date('H:i', strtotime($post['startTime']));
            $post['endTime'] = date('H:i', strtotime($post['endTime']));
            if(!isset($post['isEventEverywhere']))
            {
                $post['isEventEverywhere'] = '2';
            }
            if(isset($post['costType']) && $post['costType'] == EVENT_DOOLALLY_FEE)
            {
                $post['doolallyFee'] = $post['eventPrice'];
            }
            $this->dashboard_model->updateEventRecord($post,$eventId);

            if(isset($attachement) && $attachement != '')
            {
                $img_names = explode(',',$attachement);
                for($i=0;$i<count($img_names);$i++)
                {
                    $attArr = array(
                        'eventId' => $eventId,
                        'filename'=> $img_names[$i],
                        'attachmentType' => '1'
                    );
                    $this->dashboard_model->saveEventAttachment($attArr);
                }
                if($oldImgId != '')
                {
                    $this->dashboard_model->eventAttDelete($oldImgId);
                }

                //Creating whatsapp image
                $imgPath = '/var/www/html/mobile/'.EVENT_PATH_THUMB;
                $lowResImg = $this->image_thumb_low_res($imgPath,$img_names[0]);

                $low_size = (int)$this->human_filesize(filesize($imgPath.$lowResImg),0);
                if($low_size>300)
                {
                    //Sending mail if image resize fails
                    if(isset($post['eventName']))
                    {
                        $mailData = array(
                            'locId' => $eventOldInfo['eventPlace'],
                            'eventName' => $post['eventName']
                        );
                    }
                    else
                    {
                        $mailData = array(
                            'locId' => $eventOldInfo['eventPlace'],
                            'eventName' => $eventOldInfo['eventName']
                        );
                    }
                    $this->sendemail_library->lowResImageFailMail($mailData);
                    log_message('error','Low res Image size is more than 300KB eve-'.$eventId);
                }
                else
                {
                    $attDetails = array(
                        'lowResImage' => $lowResImg
                    );
                    $this->dashboard_model->updateEventAttachment($attDetails,$eventId);
                }
            }

            $changesRecord['eventId'] = $eventId;
            $changesRecord['fromWhere'] = 'Dashboard';
            $changesRecord['insertedDT'] = date('Y-m-d H:i:s');
            $changesRecord['isPending'] = 1;
            $this->dashboard_model->saveEventChangeRecord($changesRecord);

            $mailVerify = $changesMade;
            $mailVerify['eventId'] = $eventId;
            $commPlace = $eventOldInfo['eventPlace'];
            $mailVerify['oldEventName'] = $eventOldInfo['eventName'];
            $mailVerify['orgName'] = $eventOldInfo['creatorName'];
            $mailVerify['orgEmail'] = $eventOldInfo['creatorEmail'];

            $this->sendemail_library->eventEditToOrganiserMail($mailVerify,$commPlace);

            $externalAPIData = $this->dashboard_model->getFullEventInfoById($eventId);
            $externalAPIData = $externalAPIData[0];
            // Editing the event at meetup
            $meetupRecord = $this->dashboard_model->getMeetupRecord($eventId);
            if(isset($meetupRecord) && myIsArray($meetupRecord))
            {
                $meetupResponse = $this->meetMeUp($externalAPIData,$eventId,$meetupRecord['meetupId']);
            }
            else
            {
                $meetupResponse = $this->meetMeUp($externalAPIData, $eventId);
            }

            if($meetupResponse['status'] === false)
            {
                $data['meetupError'] = $meetupResponse['errorMsg'];
            }

            //Checking any eventsHigh record in DB for corresponding event
            $eventHighRecord = $this->dashboard_model->getEventHighRecord($eventId);
            if(isset($eventHighRecord) && myIsArray($eventHighRecord))
            {
                $externalAPIData['highId'] = $eventHighRecord['highId'];
            }
            $comCheck = $this->dashboard_model->commEventCheck($externalAPIData['creatorEmail']);
            if(isset($comCheck) && myIsArray($comCheck))
            {
                $externalAPIData['creatorPhone'] = DEFAULT_EVENTS_NUMBER;
            }
            $data['apiData'] = $externalAPIData;

            $data['status'] = true;
        }

        echo json_encode($data);
        $logDetails = array(
            'logMessage' => 'Function: updateEvent, User: '.$this->userId,
            'fromWhere' => 'Dashboard',
            'insertedDT' => date('Y-m-d H:i:s')
        );
        $this->dashboard_model->saveDashLogs($logDetails);

    }

    public function cancelEvent($eventId)
    {
        $data = array();
        $post = $this->input->post();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Session Timeout! Please login Again';
            echo json_encode($data);
            return false;
        }

        $events = $this->dashboard_model->getEventById($eventId);
        $highId = $this->dashboard_model->getEventHighRecord($eventId);
        $details = array(
            'ifActive' => '0',
            'isEventCancel' => '2'
        );
        $this->dashboard_model->updateEventRecord($details,$eventId);
        if(isset($post['from']) && isStringSet($post['from'])
            && isset($post['fromPass']) && isStringSet($post['fromPass']))
        {
            $events['fromEmail'] = $post['from'];
            $events['fromPass'] = $post['fromPass'];
        }
        $this->sendemail_library->eventCancelUserMail($events);
        //Removing Organiser offer code
        $orgCode = $this->dashboard_model->getOrgCoupon($eventId);
        if(isset($orgCode) && myIsArray($orgCode))
        {
            $details = array(
                'ifActive'=>0
            );
            $this->dashboard_model->updateOfferCode($details,$orgCode['id']);
        }
        if($events[0]['costType'] != EVENT_FREE && $events[0]['eventPrice'] != '0' && isset($eventId))
        {
            $this->dashboard_model->cancelEventOffers($eventId);
        }
        $allAttendees = $this->dashboard_model->getJoinersInfo($eventId);
        if(isset($allAttendees) && myIsArray($allAttendees))
        {
            foreach($allAttendees as $key => $row)
            {
                $row['eventPlace'] = $events[0]['eventPlace'];
                $row['eventName'] = $events[0]['eventName'];
                $row['creatorName'] = $events[0]['creatorName'];
                $whichGate = '';
                //removing instamojo refund for now
                if($events[0]['costType'] != EVENT_FREE && $events[0]['eventPrice'] != '0')
                {
                    if(stripos($row['paymentId'],'MOJO') !== FALSE)
                    {
                        $whichGate = 'MOJO';
                        $details = array(
                            'payment_id'=> $row['paymentId'],
                            'type'=> 'TAN',
                            'body'=> 'Not Attending Event'
                        );
                        $refundStats = $this->curl_library->refundInstaPayment($details);
                    }
                    else
                    {
                        if(isset($highId) && myIsArray($highId))
                        {
                            $couponArr = $this->dashboard_model->getEventCouponInfo($eventId, $row['paymentId']);
                            $couponAmt = 0;
                            if(isset($couponArr) && myIsArray($couponArr))
                            {
                                foreach($couponArr as $subkey => $subrow)
                                {
                                    if(isset($subrow['offerType']))
                                    {
                                        if($subrow['offerType'] == 'Workshop')
                                        {
                                            if($subrow['isRedeemed'] == '1')
                                            {
                                                $couponAmt += (int)NEW_DOOLALLY_FEE;
                                            }
                                        }
                                        else
                                        {
                                            if(stripos($subrow['offerType'],'Rs') !== false)
                                            {
                                                if($subrow['isRedeemed'] == '1')
                                                {
                                                    $offer = (int)trim(str_replace('Rs','',$subrow['offerType']));
                                                    $couponAmt += $offer;
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            $priceToUse = (int)$events[0]['eventPrice'];
                            if(isset($row['regPrice']))
                            {
                                $priceToUse = $row['regPrice'];
                            }

                            $actualRefundAmt = ((int)$priceToUse * (int)$row['quantity']);
                            if($row['isDirectlyRegistered'] == '1') //Doolally signup
                            {
                                $totalPrice = ((int)$priceToUse * (int)$row['quantity']);
                                $commision = 0; // ($totalPrice * DOOLALLY_GATEWAY_CHARGE) / 100;
                                $actualRefundAmt = (($totalPrice - $commision) - $couponAmt);
                            }
                            else // EventsHigh Signup
                            {
                                $totalPrice = ((int)$priceToUse * (int)$row['quantity']);
                                $commision = 0; // ($totalPrice * EH_GATEWAY_CHARGE) / 100;
                                $actualRefundAmt = (($totalPrice - $commision) - $couponAmt);
                            }

                            $details = array(
                                'event_id' => $highId['highId'],
                                'booking_id' => $row['paymentId'],
                                'charge_commission_to_user' => false,
                                'refund_amount' => $actualRefundAmt
                            );
                            $ehRefund = $this->curl_library->refundEventsHigh($details);

                            if($ehRefund['status'] == 'success')
                            {
                                if(isset($ehRefund['refund_info']['id']))
                                {
                                    $refDetails = array(
                                        'eventId' => $eventId,
                                        'transType' => 'Paid',
                                        'refundId' => $ehRefund['refund_info']['id'],
                                        'refundAmount' => $ehRefund['refund_info']['refundAmount'],
                                        'refundReason' => $ehRefund['refund_info']['refundReason'],
                                        'refundGateway' => $ehRefund['refund_info']['refundGateway'],
                                        'bookingId' => $row['paymentId'],
                                        'pgRefundId' => $ehRefund['refund_info']['paymentGatewayRefundId'],
                                        'transStatus' => 'Success',
                                        'refundError' => null,
                                        'refundDateTime' => date('Y-m-d H:i:s')
                                    );
                                    $row['refundId'] = $ehRefund['refund_info']['id'];
                                }
                                else
                                {
                                    $refDetails = array(
                                        'eventId' => $eventId,
                                        'transType' => 'Free',
                                        'refundId' => null,
                                        'refundAmount' => 0,
                                        'refundReason' => null,
                                        'refundGateway' => null,
                                        'bookingId' => $row['paymentId'],
                                        'pgRefundId' => null,
                                        'transStatus' => 'Success',
                                        'refundError' => null,
                                        'refundDateTime' => date('Y-m-d H:i:s')
                                    );
                                }
                            }
                            else
                            {
                                $errorTxt = '';
                                if(isset($ehRefund['message']))
                                {
                                    $errr = json_decode($ehRefund['message'],true);
                                    $errorTxt = $errr['type'];
                                }
                                $refDetails = array(
                                    'eventId' => $eventId,
                                    'transType' => 'Failed',
                                    'refundId' => null,
                                    'refundAmount' => 0,
                                    'refundReason' => null,
                                    'refundGateway' => null,
                                    'bookingId' => $row['paymentId'],
                                    'pgRefundId' => null,
                                    'transStatus' => 'Failed',
                                    'refundError' => $errorTxt,
                                    'refundDateTime' => date('Y-m-d H:i:s')
                                );
                                if($errorTxt != '')
                                {
                                    $mailTxt = array(
                                        'eventName' => $events[0]['eventName'],
                                        'bookingId' => $row['paymentId'],
                                        'errorTxt' => $errorTxt,
                                        'refundDateTime' => date('Y-m-d H:i:s')
                                    );
                                    $this->sendemail_library->refundFailSendMail($mailTxt);
                                }
                            }
                            $this->dashboard_model->saveEhRefundDetails($refDetails);
                            $row['refundAmt']= $actualRefundAmt;
                            $row['couponAmt'] = $couponAmt;
                        }
                    }
                }

                if(isset($refundStats) && myIsArray($refundStats))
                {
                    if($refundStats['success'] === true && isset($refundStats['refund']))
                    {
                        $row['refundId'] = $refundStats['refund']['id'];
                    }
                }
                if(isset($post['from']) && isStringSet($post['from'])
                    && isset($post['fromPass']) && isStringSet($post['fromPass']))
                {
                    $row['fromEmail'] = $post['from'];
                    $row['fromPass'] = $post['fromPass'];
                }
                if($whichGate == 'MOJO')
                {
                    $this->sendemail_library->attendeeMojoCancelMail($row);
                }
                else
                {
                    $this->sendemail_library->attendeeCancelMail($row);
                }
            }
        }
        //$this->sendemail_library->eventCancelMail($events);

        /*if(isset($events[0]['instaSlug']) && isStringSet($events[0]['instaSlug']))
        {
            //Deleting old link
            $this->curl_library->archiveInstaLink($events[0]['instaSlug']);
        }*/

        //Pause Event listing on EventsHigh and Meetup
        $meetupRecord = $this->dashboard_model->getMeetupRecord($eventId);
        if(isset($meetupRecord) && myIsArray($meetupRecord))
        {
            $meetupResponse = $this->cancelMeetMeUp($meetupRecord['meetupId']);
        }

        //Checking any eventsHigh record in DB for corresponding event
        $eventHighRecord = $this->dashboard_model->getEventHighRecord($eventId);
        if(isset($eventHighRecord) && myIsArray($eventHighRecord))
        {
            $this->curl_library->disableEventsHigh($eventHighRecord['highId']);
        }

        $data['status'] = true;
        echo json_encode($data);

        $logDetails = array(
            'logMessage' => 'Function: cancelEvent, User: '.$this->userId,
            'fromWhere' => 'Dashboard',
            'insertedDT' => date('Y-m-d H:i:s')
        );
        $this->dashboard_model->saveDashLogs($logDetails);
    }

    public function cancelMeetMeUp($meetupId = '')
    {
        $meetData = array();

        //Meetup Event On Pause
        try
        {
            $meetUpPost = array();
            $meetupCreate = $this->meetup->deleteEvent($meetUpPost,$meetupId);
            $meetData['status'] = true;

        }
        catch(Exception $ex)
        {
            $meetData['status'] = false;
            $meetData['errorMsg'] = $ex->getMessage();
        }

        return $meetData;
    }
    function eventEmailApprove($sUser, $eventId)
    {
        $sExplode = explode('-',$sUser);
        if($sExplode[1] == '0')
        {
            $this->userName = 'Doolally';
            $this->userEmail = 'events@doolally.in';
        }
        else
        {
            $userDetails = $this->users_model->getUserDetailsById($sExplode[1]);
            $this->userName = $userDetails['userData'][0]['firstName'];
            $this->userEmail = $userDetails['userData'][0]['emailId'];
        }
        $this->eventApprove($eventId);
        $data['msg'] = 'Event Approved!';
        $this->load->view('PageThankYouView',$data);
    }
    function eventEmailDecline($sUser, $eventId)
    {
        $sExplode = explode('-',$sUser);
        if($sExplode[1] == '0')
        {
            $this->userName = 'Doolally';
            $this->userEmail = 'events@doolally.in';
        }
        else
        {
            $userDetails = $this->users_model->getUserDetailsById($sExplode[1]);
            $this->userName = $userDetails['userData'][0]['firstName'];
            $this->userEmail = $userDetails['userData'][0]['emailId'];
        }
        $this->eventDecline($eventId);
        $data['msg'] = 'Event Declined!';
        $this->load->view('PageThankYouView',$data);
    }
    /*function eventApproved($eventId)
    {
        $this->eventApprove($eventId);
        redirect(base_url().'dashboard');
    }
    function eventDeclined($eventId)
    {
        $this->eventDecline($eventId);
        redirect(base_url().'dashboard');
    }*/
    function changeCostType($eventId)
    {
        $post = $this->input->post();
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            $data['status']= false;
            $data['errorMsg'] = 'Session Timed Out, Please Re-login!';
            echo json_encode($data);
            return false;
        }
        // Check if free option selected
        if($post['costType'] == '1')
        {
            $postData = array(
                'costType' => $post['costType'],
                'eventPrice' => '0'
            );
        }
        else
        {
            if($post['costType'] == EVENT_DOOLALLY_FEE)
            {
                $postData = array(
                    'costType' => $post['costType'],
                    'eventPrice' => $post['costPrice'],
                    'doolallyFee' => $post['costPrice']
                );
            }
            else
            {
                $postData = array(
                    'costType' => $post['costType'],
                    'eventPrice' => $post['costPrice'],
                    'doolallyFee' => $post['doolallyFee']
                );
            }

        }

        $this->dashboard_model->updateEventRecord($postData,$eventId);

        $eventDetails = $this->dashboard_model->getFullEventInfoById($eventId);
        $externalAPIData = $eventDetails[0];
        // Instamojo Section Start

        /*$instaLinkFailed = false;
        if(isset($eventDetails[0]['instaSlug']) && isStringSet($eventDetails[0]['instaSlug']))
        {
            //Deleting old link
            $this->curl_library->archiveInstaLink($eventDetails[0]['instaSlug']);
        }

        // Getting image upload url from api;
        $instaImgLink = $this->curl_library->getInstaImageLink();
        $donePost = array();
        if($instaImgLink['success'] === true)
        {
            if(isset($attachement) && isStringSet($attachement))
            {
                $coverImg =  $this->curl_library->uploadInstaImage($instaImgLink['upload_url'],$attachement);
            }
            else
            {
                $coverImg =  $this->curl_library->uploadInstaImage($instaImgLink['upload_url'],$eventDetails[0]['filename']);
            }
            if(isset($coverImg) && myIsMultiArray($coverImg) && isset($coverImg['url']))
            {
                $postData = array(
                    'title' => $eventDetails[0]['eventName'],
                    'description' => $eventDetails[0]['eventDescription'],
                    'currency' => 'INR',
                    'base_price' => $eventDetails[0]['eventPrice'],
                    'start_date' => $eventDetails[0]['eventDate'].' '.date("H:i", strtotime($eventDetails[0]['startTime'])),
                    'end_date' => $eventDetails[0]['eventDate'].' '.date("H:i", strtotime($eventDetails[0]['endTime'])),
                    'venue' => $eventDetails[0]['locName'].', Doolally Taproom',
                    'redirect_url' => MOBILE_URL.'?event='.$eventId.'&hash='.encrypt_data('EV-'.$eventId),
                    'cover_image_json' => json_encode($coverImg),
                    'timezone' => 'Asia/Kolkata'
                );
                $donePost = $this->curl_library->createInstaLink($postData);
            }
        }
        if(!myIsMultiArray($donePost)) //Creating event without image
        {
            if($post['costType'] == EVENT_FREE)
            {
                $postData = array(
                    'title' => $eventDetails[0]['eventName'],
                    'description' => $eventDetails[0]['eventDescription'],
                    'currency' => 'INR',
                    'base_price' => '0',
                    'start_date' => $eventDetails[0]['eventDate'].' '.date("H:i", strtotime($eventDetails[0]['startTime'])),
                    'end_date' => $eventDetails[0]['eventDate'].' '.date("H:i", strtotime($eventDetails[0]['endTime'])),
                    'venue' => $eventDetails[0]['locData'][0]['locName'].', Doolally Taproom',
                    'redirect_url' => MOBILE_URL.'?event='.$eventId.'&hash='.encrypt_data('EV-'.$eventId),
                    'timezone' => 'Asia/Kolkata'
                );
            }
            else
            {
                $postData = array(
                    'title' => $eventDetails[0]['eventName'],
                    'description' => $eventDetails[0]['eventDescription'],
                    'currency' => 'INR',
                    'base_price' => $eventDetails[0]['eventPrice'],
                    'start_date' => $eventDetails[0]['eventDate'].' '.date("H:i", strtotime($eventDetails[0]['startTime'])),
                    'end_date' => $eventDetails[0]['eventDate'].' '.date("H:i", strtotime($eventDetails[0]['endTime'])),
                    'venue' => $eventDetails[0]['locData'][0]['locName'].', Doolally Taproom',
                    'redirect_url' => MOBILE_URL.'?event='.$eventId.'&hash='.encrypt_data('EV-'.$eventId),
                    'timezone' => 'Asia/Kolkata'
                );
            }
            $donePost = $this->curl_library->createInstaLink($postData);
        }

        $instaUpLink = array();
        if(isset($donePost['link']))
        {
            if(isset($donePost['link']['shorturl']))
            {
                $instaUpLink['eventPaymentLink'] = $donePost['link']['shorturl'];
                $instaUpLink['instaSlug'] = $donePost['link']['slug'];
            }
            else
            {
                $instaUpLink['eventPaymentLink'] = $donePost['link']['url'];
                $instaUpLink['instaSlug'] = $donePost['link']['slug'];
            }

            $this->dashboard_model->updateEventRecord($instaUpLink,$eventId);
        }
        else
        {
            $instaLinkFailed = true;
        }*/

        //Instamojo Section End

        // Editing the event at meetup
        $meetupRecord = $this->dashboard_model->getMeetupRecord($eventId);
        if(isset($meetupRecord) && myIsArray($meetupRecord))
        {
            $meetupResponse = $this->meetMeUp($externalAPIData,$eventId,$meetupRecord['meetupId']);
        }
        else
        {
            $meetupResponse = $this->meetMeUp($externalAPIData, $eventId);
        }

        if($meetupResponse['status'] === false)
        {
            $data['meetupError'] = $meetupResponse['errorMsg'];
        }

        //Checking any eventsHigh record in DB for corresponding event
        $eventHighRecord = $this->dashboard_model->getEventHighRecord($eventId);
        if(isset($eventHighRecord) && myIsArray($eventHighRecord))
        {
            $externalAPIData['highId'] = $eventHighRecord['highId'];
        }
        $data['apiData'] = $externalAPIData;
        $data['status']= true;

        echo json_encode($data);
        $logDetails = array(
            'logMessage' => 'Function: changeCostType, User: '.$this->userId,
            'fromWhere' => 'Dashboard',
            'insertedDT' => date('Y-m-d H:i:s')
        );
        $this->dashboard_model->saveDashLogs($logDetails);
    }
    function eventApproved($eventId)
    {
        $post = $this->input->post();
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            $data['status']= false;
            $data['errorMsg'] = 'Session Timed Out, Please Re-login!';
            echo json_encode($data);
            return false;
        }

        if(isset($post['costType']) && $post['costType'] != '')
        {
            if($post['costType'] == '1')
            {
                $postData = array(
                    'costType' => $post['costType'],
                    'eventPrice' => '0'
                );
            }
            else
            {
                if($post['costType'] == EVENT_DOOLALLY_FEE)
                {
                    $postData = array(
                        'costType' => $post['costType'],
                        'eventPrice' => $post['costPrice'],
                        'doolallyFee' => $post['costPrice']
                    );
                }
                else
                {
                    $postData = array(
                        'costType' => $post['costType'],
                        'eventPrice' => $post['costPrice'],
                        'doolallyFee' => $post['doolallyFee']
                    );
                }
            }
            $this->dashboard_model->updateEventRecord($postData,$eventId);
        }

        //Checking if event is editted and came for review
        $editRecord = $this->dashboard_model->getEditRecord($eventId);
        $eventStatus = 'approved';
        $isEventEdit = false;
        if(isset($editRecord) && myIsArray($editRecord))
        {
            $isEventEdit = true;
            $eventStatus = 'reviewed';
            $upDetail = array(
                'isPending' => 1
            );
            $this->dashboard_model->updateEditRecord($upDetail,$eventId);
        }

        $eventDetail = $this->dashboard_model->getFullEventInfoById($eventId);
        $externalAPIData = $eventDetail[0];
        if(isset($post['from']) && isStringSet($post['from'])
            && isset($post['fromPass']) && isStringSet($post['fromPass']))
        {
            $eventDetail['fromEmail'] = $post['from'];
            $eventDetail['fromPass'] = $post['fromPass'];
        }

        if(isset($eventDetail[0]['eventPaymentLink']) && isStringSet($eventDetail[0]['eventPaymentLink']))
        {
            $this->dashboard_model->ApproveEvent($eventId);
            $senderName = 'Doolally';
            $senderEmail = 'events@doolally.in';
            if(isStringSet($this->userEmail) && isStringSet($this->userName))
            {
                $senderEmail = $this->userEmail;
                $senderName = $this->userName;
            }
            $eventDetail['senderName'] = $senderName;
            $eventDetail['senderEmail'] = $senderEmail;
            $eventDetail['eventStatus'] = $eventStatus;

            $this->sendemail_library->eventApproveMail($eventDetail);
        }
        else
        {
            /*$instaImgLink = $this->curl_library->getInstaImageLink();
            $donePost = array();
            if($instaImgLink['success'] === true)
            {
                $coverImg =  $this->curl_library->uploadInstaImage($instaImgLink['upload_url'],$eventDetail[0]['filename']);
                if(isset($coverImg) && myIsMultiArray($coverImg) && isset($coverImg['url']))
                {
                    $postData = array(
                        'title' => $eventDetail[0]['eventName'],
                        'description' => $eventDetail[0]['eventDescription'],
                        'currency' => 'INR',
                        'base_price' => $eventDetail[0]['eventPrice'],
                        'start_date' => $eventDetail[0]['eventDate'].' '.date("H:i", strtotime($eventDetail[0]['startTime'])),
                        'end_date' => $eventDetail[0]['eventDate'].' '.date("H:i", strtotime($eventDetail[0]['endTime'])),
                        'venue' => $eventDetail[0]['locName'].', Doolally Taproom',
                        'redirect_url' => MOBILE_URL.'?event='.$eventDetail[0]['eventId'].'&hash='.encrypt_data('EV-'.$eventDetail[0]['eventId']),
                        'cover_image_json' => json_encode($coverImg),
                        'timezone' => 'Asia/Kolkata'
                    );
                    $donePost = $this->curl_library->createInstaLink($postData);
                }
            }

            if(!myIsMultiArray($donePost))
            {
                if($eventDetail[0]['costType'] == EVENT_FREE)
                {
                    $postData = array(
                        'title' => $eventDetail[0]['eventName'],
                        'description' => $eventDetail[0]['eventDescription'],
                        'currency' => 'INR',
                        'base_price' => '0',
                        'start_date' => $eventDetail[0]['eventDate'].' '.date("H:i", strtotime($eventDetail[0]['startTime'])),
                        'end_date' => $eventDetail[0]['eventDate'].' '.date("H:i", strtotime($eventDetail[0]['endTime'])),
                        'venue' => $eventDetail[0]['locName'].', Doolally Taproom',
                        'redirect_url' => MOBILE_URL.'?event='.$eventDetail[0]['eventId'].'&hash='.encrypt_data('EV-'.$eventDetail[0]['eventId']),
                        'timezone' => 'Asia/Kolkata'
                    );
                }
                else
                {
                    $postData = array(
                        'title' => $eventDetail[0]['eventName'],
                        'description' => $eventDetail[0]['eventDescription'],
                        'currency' => 'INR',
                        'base_price' => $eventDetail[0]['eventPrice'],
                        'start_date' => $eventDetail[0]['eventDate'].' '.date("H:i", strtotime($eventDetail[0]['startTime'])),
                        'end_date' => $eventDetail[0]['eventDate'].' '.date("H:i", strtotime($eventDetail[0]['endTime'])),
                        'venue' => $eventDetail[0]['locName'].', Doolally Taproom',
                        'redirect_url' => MOBILE_URL.'?event='.$eventDetail[0]['eventId'].'&hash='.encrypt_data('EV-'.$eventDetail[0]['eventId']),
                        'timezone' => 'Asia/Kolkata'
                    );
                }
                $donePost = $this->curl_library->createInstaLink($postData);
            }*/
            $this->dashboard_model->ApproveEvent($eventId);
            $senderName = 'Doolally';
            $senderEmail = 'events@doolally.in';
            if(isStringSet($this->userEmail) && isStringSet($this->userName))
            {
                $senderEmail = $this->userEmail;
                $senderName = $this->userName;
            }
            $eventDetail['senderName'] = $senderName;
            $eventDetail['senderEmail'] = $senderEmail;
            $eventDetail['eventStatus'] = $eventStatus;
            $this->sendemail_library->eventApproveMail($eventDetail);

            /*if(isset($donePost['link']))
            {
                if(isset($donePost['link']['shorturl']))
                {
                    $details = array(
                        'eventPaymentLink' => $donePost['link']['shorturl'],
                        'instaSlug' => $donePost['link']['slug']
                    );
                }
                else
                {
                    $details = array(
                        'eventPaymentLink' => $donePost['link']['url'],
                        'instaSlug' => $donePost['link']['slug']
                    );
                }

            }*/
        }

        //Sending mails if event date is Modified!
        if(isset($editRecord) && myIsArray($editRecord))
        {
            if(isset($editRecord['eventDate']))
            {
                $dateMailData = array(
                    'eventName' => $externalAPIData['eventName'],
                    'eventSlug' => $externalAPIData['eventSlug'],
                    'oldDate' => $editRecord['eventDate'],
                    'newDate' => $externalAPIData['eventDate'],
                    'costType' => $externalAPIData['costType'],
                    'eventPlace' => $externalAPIData['eventPlace']
                );

                $allAttendees = $this->dashboard_model->getJoinersInfo($eventId);
                if(isset($allAttendees) && myIsArray($allAttendees))
                {
                    foreach($allAttendees as $key => $row)
                    {
                        $dateMailData['attendeeName'] = $row['firstName'];
                        $dateMailData['emailId'] = $row['emailId'];
                        $this->sendemail_library->attendeeChangeMail($dateMailData);
                    }
                }
            }
        }

        //Creating whatsapp image
        $imgPath = '/var/www/html/mobile/'.EVENT_PATH_THUMB;
        $lowResImg = $this->image_thumb_low_res($imgPath,$eventDetail[0]['filename']);

        $low_size = (int)$this->human_filesize(filesize($imgPath.$lowResImg),0);
        if($low_size>300)
        {
            //Sending mail if image resize fails
            $mailData = array(
                'locId' => $eventDetail[0]['eventPlace'],
                'eventName' => $eventDetail[0]['eventName']
            );
            $this->sendemail_library->lowResImageFailMail($mailData);
            log_message('error','Low res Image size is more than 300KB');
        }
        else
        {
            $eveAtt = $this->dashboard_model->getEventAttById($eventId);
            if(isset($eveAtt) && myIsArray($eveAtt))
            {
                if(!isset($eveAtt[0]['lowResImage']))
                {
                    $attDetails = array(
                        'lowResImage' => $lowResImg
                    );
                    $this->dashboard_model->updateEventAttachment($attDetails,$eventId);
                }
            }
        }

        //Sending mail if projector is selected
        if($eventDetail[0]['ifProjectorRequired'] == '1' && !$isEventEdit)
        {
            $mDetails = array(
                'eventName' => $eventDetail[0]['eventName'],
                'locName' => $eventDetail[0]['locName'],
                'eventDate' => $eventDetail[0]['eventDate'],
                'startTime' => $eventDetail[0]['startTime']
            );
            $this->sendemail_library->eventExtraToMaintMail($mDetails);
        }

        // Editing the event at meetup
        $meetupRecord = $this->dashboard_model->getMeetupRecord($eventId);
        if(isset($meetupRecord) && myIsArray($meetupRecord))
        {
            $meetupResponse = $this->meetMeUp($externalAPIData,$eventId,$meetupRecord['meetupId']);
        }
        else
        {
            $meetupResponse = $this->meetMeUp($externalAPIData, $eventId);
        }
        if($meetupResponse['status'] === false)
        {
            $data['meetupError'] = $meetupResponse['errorMsg'];
        }
        $data['status'] = true;
        //Checking any eventsHigh record in DB for corresponding event
        $eventHighRecord = $this->dashboard_model->getEventHighRecord($eventId);
        if(isset($eventHighRecord) && myIsArray($eventHighRecord))
        {
            $externalAPIData['highId'] = $eventHighRecord['highId'];
        }
        $comCheck = $this->dashboard_model->commEventCheck($externalAPIData['creatorEmail']);
        if(isset($comCheck) && myIsArray($comCheck))
        {
            $externalAPIData['creatorPhone'] = DEFAULT_EVENTS_NUMBER;
        }
        $data['apiData'] = $externalAPIData;
        echo json_encode($data);
        $logDetails = array(
            'logMessage' => 'Function: eventApproved, User: '.$this->userId,
            'fromWhere' => 'Dashboard',
            'insertedDT' => date('Y-m-d H:i:s')
        );
        $this->dashboard_model->saveDashLogs($logDetails);
    }

    function image_thumb_low_res( $image_path, $img_name)
    {
        $image_thumb = $image_path.'low_res_'.$img_name;

        // LOAD LIBRARY
        $this->load->library( 'image_lib' );

        // CONFIGURE IMAGE LIBRARY
        $config['image_library']    = 'gd2';
        $config['source_image']     = $image_path.$img_name;
        $config['new_image']        = $image_thumb;
        $config['quality']          = 40;
        $config['maintain_ratio']   = TRUE;
        $config['height']           = 300;
        $config['width']            = 200;

        $this->image_lib->initialize( $config );
        if(!$this->image_lib->resize())
        {
            log_message('error',$image_path.': '.$this->image_lib->display_errors());
            $this->image_lib->clear();
            return 'error';
        }
        else
        {
            $this->image_lib->clear();
            return 'low_res_'.$img_name;
        }
    }
    function human_filesize($bytes, $decimals = 2)
    {
        $factor = floor((strlen($bytes) - 1) / 3);
        if ($factor > 0) $sz = 'KMGT';
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor));
    }

    public function meetMeUp($eventInfo, $eventId, $meetupId = '')
    {
        $meetData = array();
        if($eventInfo['costType'] == EVENT_FREE)
        {
            $description = '<a href="'.$eventInfo['shortUrl'].'">Register Here (Free)</a> - '.$eventInfo['eventDescription'];
        }
        else
        {
            $description = '<a href="'.$eventInfo['shortUrl'].'">Register Here ('.$eventInfo['eventPrice'].')</a> - '.$eventInfo['eventDescription'];
        }

        $description = (strlen($description) > 45000) ? substr($description, 0, 45000) . '..' : $description;

        $assigned_time = $eventInfo['startTime'];
        $completed_time= $eventInfo['endTime'];

        $d1 = new DateTime($assigned_time);
        $d2 = new DateTime($completed_time);
        $interval = $d2->diff($d1);

        $hours = $interval->format('%H');

        $eventStart = $eventInfo['eventDate'].' '.$eventInfo['startTime'];

        //Meetup Event Creation And Update
        try
        {
            $venueId = $eventInfo['meetupVenueId'];
            if($eventInfo['isSpecialEvent'] == '1')
            {
                $venueId = SPECIAL_EVENT_MEETUP_ID;
            }
            if(!isStringSet($meetupId))
            {
                $meetUpPost = array(
                    'description' => $description,
                    'guest_limit' => '50',
                    'duration' => $hours * 60 * 60 * 1000,
                    'announce' => true,
                    'time' => strtotime($eventStart)*1000,
                    'name' => (strlen($eventInfo['eventName']) > 75) ? substr($eventInfo['eventName'], 0, 75) . '..' : $eventInfo['eventName'],
                    'venue_id' => $venueId
                );
                $meetupCreate = $this->meetup->postEvent($meetUpPost);
                $saveMeetup = array(
                    'meetupId' => $meetupCreate->id,
                    'eventId' => $eventId,
                    'meetupStatus' => 1,
                    'meetupError' => null,
                    'meetupLink' => $meetupCreate->link,
                    'insertedDT' => date('Y-m-d H:i:s')
                );
                $this->dashboard_model->saveMeetup($saveMeetup);
            }
            else
            {
                $announceStatus = true;
                if($eventInfo['ifActive'] == NOT_ACTIVE && $eventInfo['ifApproved'] == EVENT_WAITING)
                {
                    $announceStatus = false;
                }
                $meetUpPost = array(
                    'description' => $description,
                    'guest_limit' => '50',
                    'duration' => $hours * 60 * 60 * 1000,
                    'announce' => $announceStatus,
                    'time' => strtotime($eventStart)*1000,
                    'name' => (strlen($eventInfo['eventName']) > 75) ? substr($eventInfo['eventName'], 0, 75) . '..' : $eventInfo['eventName'],
                    'venue_id' => $venueId
                );
                $meetupCreate = $this->meetup->updateEvent($meetUpPost,$meetupId);
            }
            $meetData['status'] = true;

        }
        catch(Exception $ex)
        {
            $saveMeetup = array(
                'meetupId' => null,
                'eventId' => $eventId,
                'meetupStatus' => 2,
                'meetupError' => $ex->getMessage(),
                'meetupLink' => null,
                'insertedDT' => date('Y-m-d H:i:s')
            );
            $this->dashboard_model->saveMeetup($saveMeetup);
            $meetData['status'] = false;
            $meetData['errorMsg'] = $ex->getMessage();
        }

        return $meetData;
    }

    public function saveEventHighData($eventId)
    {
        $post = $this->input->post();
        $data = array();

        if($post['status'] == 'error')
        {
            $postData = array(
                'highId' => null,
                'eventId' => $eventId,
                'highStatus' => 2,
                'highError' => $post['message'],
                'insertedDT' => date('Y-m-d H:i:s')
            );
        }
        else
        {
            if(isset($post['id']) && $post['id'] != '')
            {
                $extraMsg = '';
                if(isset($post['extraMsg']))
                {
                    $extraMsg = $post['extraMsg'];
                }
                $postData = array(
                    'highId' => $post['id'],
                    'eventId' => $eventId,
                    'highStatus' => 1,
                    'highError' => $extraMsg,
                    'insertedDT' => date('Y-m-d H:i:s')
                );
                $details = array(
                    'eventPaymentLink' => 'https://ticketing.eventshigh.com/ticketModal.jsp?eid='.$post['id'].'&src=fbTicketWidget&theme=jet-black&bg0=1'
                );
                $this->dashboard_model->updateEventRecord($details, $eventId);
            }
        }
        $this->dashboard_model->saveEventHigh($postData);
        $data['status'] = true;
        echo json_encode($data);
    }
    public function enableEventHigh()
    {
        $post = $this->input->post();
        $data = array();

        if(isset($post['highId']))
        {
            $this->curl_library->enableEventsHigh($post['highId']);
        }
        $data['status'] = true;
        echo json_encode($data);
    }
    function eventDeclined($eventId)
    {
        $data = array();
        $post = $this->input->post();
        $eventDetail = $this->dashboard_model->getFullEventInfoById($eventId);
        $this->dashboard_model->DeclineEvent($eventId);
        $senderName = 'Doolally';
        $senderEmail = 'events@doolally.in';
        if(isStringSet($this->userEmail) && isStringSet($this->userName))
        {
            $senderName = $this->userName;
            $senderEmail = $this->userEmail;
        }
        $eventDetail['senderName'] = $senderName;
        $eventDetail['senderEmail'] = $senderEmail;

        if(isset($post['from']) && isStringSet($post['from'])
            && isset($post['fromPass']) && isStringSet($post['fromPass']))
        {
            $eventDetail['fromEmail'] = $post['from'];
            $eventDetail['fromPass'] = $post['fromPass'];
        }
        $this->sendemail_library->eventDeclineMail($eventDetail);
        $data['status'] = true;
        echo json_encode($data);
        $logDetails = array(
            'logMessage' => 'Function: eventDeclined, User: '.$this->userId,
            'fromWhere' => 'Dashboard',
            'insertedDT' => date('Y-m-d H:i:s')
        );
        $this->dashboard_model->saveDashLogs($logDetails);
    }
    function setEventDeActive($eventId)
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $this->dashboard_model->deActivateEventRecord($eventId);
        $eventInfo = $this->dashboard_model->getFullEventInfoById($eventId);
        $this->deactiveOtherPlatforms($eventInfo,$eventId);
        $logDetails = array(
            'logMessage' => 'Function: setEventDeActive, User: '.$this->userId,
            'fromWhere' => 'Dashboard',
            'insertedDT' => date('Y-m-d H:i:s')
        );
        $this->dashboard_model->saveDashLogs($logDetails);

        redirect(base_url().'dashboard');
    }
    function setEventActive($eventId)
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $this->dashboard_model->activateEventRecord($eventId);
        $eventInfo = $this->dashboard_model->getFullEventInfoById($eventId);

        $this->activeOtherPlatforms($eventInfo,$eventId);
        $logDetails = array(
            'logMessage' => 'Function: setEventActive, User: '.$this->userId,
            'fromWhere' => 'Dashboard',
            'insertedDT' => date('Y-m-d H:i:s')
        );
        $this->dashboard_model->saveDashLogs($logDetails);

        redirect(base_url().'dashboard');
    }
    function deleteEvent($eventId)
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $this->dashboard_model->transferDeleteEvent($eventId);

        //$this->dashboard_model->eventDelete($eventId);
        $this->dashboard_model->eventRegisDelete($eventId);
        //$this->dashboard_model->eventAttDeleteById($eventId);
        redirect(base_url().'dashboard');
    }
    function deleteCompEvent($eventId)
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $this->dashboard_model->eventCompDelete($eventId);
        redirect(base_url().'dashboard');
    }
    function deleteEventAtt()
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $post = $this->input->post();
        $picId = $post['picId'];
        $this->dashboard_model->eventAttDelete($picId);
        $data['status'] = true;
        echo json_encode($data);
        $logDetails = array(
            'logMessage' => 'Function: deleteEventAtt, User: '.$this->userId,
            'fromWhere' => 'Dashboard',
            'insertedDT' => date('Y-m-d H:i:s')
        );
        $this->dashboard_model->saveDashLogs($logDetails);
    }

    function openReg($eventId)
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $details = array(
            'isRegFull' => '0'
        );
        $this->dashboard_model->updateEventRecord($details,$eventId);
        $eventInfo = $this->dashboard_model->getFullEventInfoById($eventId);

        $this->activeOtherPlatforms($eventInfo,$eventId);
        $logDetails = array(
            'logMessage' => 'Function: openReg, User: '.$this->userId,
            'fromWhere' => 'Dashboard',
            'insertedDT' => date('Y-m-d H:i:s')
        );
        $this->dashboard_model->saveDashLogs($logDetails);
        redirect(base_url().'dashboard');
    }
    function closeReg($eventId)
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $details = array(
            'isRegFull' => '1'
        );
        $this->dashboard_model->updateEventRecord($details,$eventId);
        $eventInfo = $this->dashboard_model->getFullEventInfoById($eventId);

        $this->deactiveOtherPlatforms($eventInfo,$eventId);
        $logDetails = array(
            'logMessage' => 'Function: closeReg, User: '.$this->userId,
            'fromWhere' => 'Dashboard',
            'insertedDT' => date('Y-m-d H:i:s')
        );
        $this->dashboard_model->saveDashLogs($logDetails);
        redirect(base_url().'dashboard');
    }
    public function getSignupList($eventId)
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            $data['status'] = false;
        }
        else
        {
            /*$eventHighRecord = $this->dashboard_model->getEventHighRecord($eventId);
            if(isset($eventHighRecord) && myIsArray($eventHighRecord))
            {
                $EHAtendees = $this->curl_library->attendeeEventsHigh($eventHighRecord['highId']);
                if(isset($EHAtendees) && myIsArray($EHAtendees))
                {
                    $data['EHData'] = $EHAtendees;
                }
            }*/
            $data['status'] = true;
            $data['joinData'] = $this->dashboard_model->getDoolallyJoinersInfo($eventId);
            $data['EHData'] = $this->dashboard_model->getEhJoinersInfo($eventId);
            $data['canData'] = $this->dashboard_model->getCancelList($eventId);
            $data['reminderData'] = $this->dashboard_model->getReminderList($eventId);
        }

        echo json_encode($data);
    }

    function deactiveOtherPlatforms($eventDetails,$eventId)
    {
        //Checking any eventsHigh record in DB for corresponding event
        $eventHighRecord = $this->dashboard_model->getEventHighRecord($eventId);
        if(isset($eventHighRecord) && myIsArray($eventHighRecord))
        {
            $this->curl_library->disableEventsHigh($eventHighRecord['highId']);
        }

        //Creating new Instamojo link also
        /*if(isset($eventDetails[0]['instaSlug']) && isStringSet($eventDetails[0]['instaSlug']))
        {
            //Deleting old link
            $this->curl_library->archiveInstaLink($eventDetails[0]['instaSlug']);
        }*/
    }
    function activeOtherPlatforms($eventDetails,$eventId)
    {
        //Checking any eventsHigh record in DB for corresponding event
        $eventHighRecord = $this->dashboard_model->getEventHighRecord($eventId);
        if(isset($eventHighRecord) && myIsArray($eventHighRecord))
        {
            $abc = $this->curl_library->enableEventsHigh($eventHighRecord['highId']);
        }

        /*$instaImgLink = $this->curl_library->getInstaImageLink();
        $donePost = array();
        if($instaImgLink['success'] === true)
        {
            $coverImg =  $this->curl_library->uploadInstaImage($instaImgLink['upload_url'],$eventDetails[0]['filename']);
            if(isset($coverImg) && myIsMultiArray($coverImg) && isset($coverImg['url']))
            {
                $postData = array(
                    'title' => $eventDetails[0]['eventName'],
                    'description' => $eventDetails[0]['eventDescription'],
                    'currency' => 'INR',
                    'base_price' => $eventDetails[0]['eventPrice'],
                    'start_date' => $eventDetails[0]['eventDate'].' '.date("H:i", strtotime($eventDetails[0]['startTime'])),
                    'end_date' => $eventDetails[0]['eventDate'].' '.date("H:i", strtotime($eventDetails[0]['endTime'])),
                    'venue' => $eventDetails[0]['locName'].', Doolally Taproom',
                    'redirect_url' => MOBILE_URL.'?event='.$eventDetails[0]['eventId'].'&hash='.encrypt_data('EV-'.$eventDetails[0]['eventId']),
                    'cover_image_json' => json_encode($coverImg),
                    'timezone' => 'Asia/Kolkata'
                );
                $donePost = $this->curl_library->createInstaLink($postData);
            }
        }

        if(!myIsMultiArray($donePost))
        {
            if($eventDetails[0]['costType'] == EVENT_FREE)
            {
                $postData = array(
                    'title' => $eventDetails[0]['eventName'],
                    'description' => $eventDetails[0]['eventDescription'],
                    'currency' => 'INR',
                    'base_price' => '0',
                    'start_date' => $eventDetails[0]['eventDate'].' '.date("H:i", strtotime($eventDetails[0]['startTime'])),
                    'end_date' => $eventDetails[0]['eventDate'].' '.date("H:i", strtotime($eventDetails[0]['endTime'])),
                    'venue' => $eventDetails[0]['locName'].', Doolally Taproom',
                    'redirect_url' => MOBILE_URL.'?event='.$eventDetails[0]['eventId'].'&hash='.encrypt_data('EV-'.$eventDetails[0]['eventId']),
                    'timezone' => 'Asia/Kolkata'
                );
            }
            else
            {
                $postData = array(
                    'title' => $eventDetails[0]['eventName'],
                    'description' => $eventDetails[0]['eventDescription'],
                    'currency' => 'INR',
                    'base_price' => $eventDetails[0]['eventPrice'],
                    'start_date' => $eventDetails[0]['eventDate'].' '.date("H:i", strtotime($eventDetails[0]['startTime'])),
                    'end_date' => $eventDetails[0]['eventDate'].' '.date("H:i", strtotime($eventDetails[0]['endTime'])),
                    'venue' => $eventDetails[0]['locName'].', Doolally Taproom',
                    'redirect_url' => MOBILE_URL.'?event='.$eventDetails[0]['eventId'].'&hash='.encrypt_data('EV-'.$eventDetails[0]['eventId']),
                    'timezone' => 'Asia/Kolkata'
                );
            }
            $donePost = $this->curl_library->createInstaLink($postData);
        }

        if(isset($donePost['link']))
        {
            if(isset($donePost['link']['shorturl']))
            {
                $details = array(
                    'eventPaymentLink' => $donePost['link']['shorturl'],
                    'instaSlug' => $donePost['link']['slug']
                );
            }
            else
            {
                $details = array(
                    'eventPaymentLink' => $donePost['link']['url'],
                    'instaSlug' => $donePost['link']['slug']
                );
            }
            $this->dashboard_model->updateEventRecord($details, $eventId);
        }*/
    }

    //For Fnb Section
    function setFnbActive($fnbId)
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $this->dashboard_model->activateFnbRecord($fnbId);
        $logDetails = array(
            'logMessage' => 'Function: setFnbActive, User: '.$this->userId,
            'fromWhere' => 'Dashboard',
            'insertedDT' => date('Y-m-d H:i:s')
        );
        $this->dashboard_model->saveDashLogs($logDetails);
        redirect(base_url().'dashboard');
    }
    function setFnbDeActive($fnbId)
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $this->dashboard_model->DeActivateFnbRecord($fnbId);
        $logDetails = array(
            'logMessage' => 'Function: setFnbDeActive, User: '.$this->userId,
            'fromWhere' => 'Dashboard',
            'insertedDT' => date('Y-m-d H:i:s')
        );
        $this->dashboard_model->saveDashLogs($logDetails);
        redirect(base_url().'dashboard');
    }
    function deleteFnb($fnbId)
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $this->dashboard_model->fnbDelete($fnbId);
        $logDetails = array(
            'logMessage' => 'Function: deleteFnb, User: '.$this->userId,
            'fromWhere' => 'Dashboard',
            'insertedDT' => date('Y-m-d H:i:s')
        );
        $this->dashboard_model->saveDashLogs($logDetails);
        redirect(base_url().'dashboard');
    }
    function editFnb($fnbId)
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $data = array();
        $fnb = $this->dashboard_model->getFnBById($fnbId);
        if(isset($fnb) && myIsMultiArray($fnb))
        {
            foreach($fnb as $key => $row)
            {
                $data['fnbInfo'][$key]['fnbData'] = $row;
                $data['fnbInfo'][$key]['fnbAtt'] = $this->dashboard_model->getFnbAttById($row['fnbId']);
            }
        }
        
        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);

        $this->load->view('FnbEditView', $data);
    }
    function deleteFnbAtt()
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $post = $this->input->post();
        $picId = $post['picId'];
        $this->dashboard_model->fnbAttDelete($picId);
        $data['status'] = true;
        echo json_encode($data);
        $logDetails = array(
            'logMessage' => 'Function: deleteFnbAtt, User: '.$this->userId,
            'fromWhere' => 'Dashboard',
            'insertedDT' => date('Y-m-d H:i:s')
        );
        $this->dashboard_model->saveDashLogs($logDetails);
    }
    public function updatefnb()
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $post = $this->input->post();
        $details = array(
            'itemType'=> $post['itemType'],
            'itemName' => $post['itemName'],
            'itemHeadline' => $post['itemHeadline'],
            'itemDescription' => $post['itemDescription'],
            'priceFull' => $post['priceFull'],
            'priceHalf' => $post['priceHalf']
        );
        $this->dashboard_model->updateFnbRecord($details,$post['fnbId']);

        if(isset($post['attachment']) && isStringSet($post['attachment']))
        {
            $img_names = explode(',',$post['attachment']);
            for($i=0;$i<count($img_names);$i++)
            {
                $attArr = array(
                    'fnbId' => $post['fnbId'],
                    'filename'=> $img_names[$i],
                    'attachmentType' => $post['itemType']
                );
                $this->dashboard_model->saveFnbAttachment($attArr);
            }
        }

        $logDetails = array(
            'logMessage' => 'Function: updateFnb, User: '.$this->userId,
            'fromWhere' => 'Dashboard',
            'insertedDT' => date('Y-m-d H:i:s')
        );
        $this->dashboard_model->saveDashLogs($logDetails);
        redirect(base_url().'dashboard');

    }

    function saveEventMeta()
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Session Timeout, Please Login Again!';
            echo json_encode($data);
            return false;
        }
        $post = $this->input->post();
        $this->dashboard_model->saveMetaRecord($post);
        $data['status'] = true;
        echo json_encode($data);
        $logDetails = array(
            'logMessage' => 'Function: saveEventMeta, User: '.$this->userId,
            'fromWhere' => 'Dashboard',
            'insertedDT' => date('Y-m-d H:i:s')
        );
        $this->dashboard_model->saveDashLogs($logDetails);
    }

    function saveBeerMeta()
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Session Timeout, Please Login Again!';
            echo json_encode($data);
            return false;
        }
        $post = $this->input->post();
        $metaData = array(
            'metaTitle' => $post['olympicsTitle'],
            'metaDescription' => $post['olympicsDescription'],
            'metaImg' => $post['olympicsImg'],
            'tagType' => '1'
        );
        $this->dashboard_model->saveMetaRecord($metaData);
        $this->curl_library->saveOlympicsMeta($metaData);
        $data['status'] = true;
        echo json_encode($data);
    }

    //upload img for meta tag
    public function uploadMetaFiles()
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
                $config['upload_path'] = '../mobile/asset/images/'; // FOOD_PATH_THUMB; //'uploads/food/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size']      = '0';
                $config['overwrite']     = TRUE;

                $this->upload->initialize($config);
                if(!$this->upload->do_upload('attachment'))
                {
                    log_message('error','Meta: '.$this->upload->display_errors());
                    $data['status'] = false;
                    $data['errorMsg'] = $this->upload->display_errors();
                    echo json_encode($data);
                    return false;
                }
                else
                {
                    $upload_data = $this->upload->data();
                    $attchmentArr= $this->image_thumb($upload_data['file_path'],$upload_data['file_name']);
                    if($attchmentArr == 'error')
                    {
                        $data['status'] = false;
                        $data['errorMsg'] = 'Error in resizing image!';
                        echo json_encode($data);
                        return false;
                    }
                    else
                    {
                        echo $attchmentArr;
                    }
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
            $data['errorMsg'] = 'No Image Files Received!';
            echo json_encode($data);
            return false;
        }
    }

    public function checkPublicUser($email, $mob)
    {
        $uData = array();
        $userExists = $this->users_model->checkUserDetails($email, $mob);

        if($userExists['status'] === true)
        {
            $uData['status'] = false;
            $uData['userData'] = $userExists['userData'];
        }
        else
        {
            $uData['status'] = true;
        }
        return $uData;
    }

    public function getLastMailLog()
    {
        $data = array();
        $mailLogId = $this->dashboard_model->getMailLastId();
        $data['id'] = $mailLogId['id'];

        echo json_encode($data);
    }

    public function getUpdateMailCount()
    {
        $data = array();
        $post = $this->input->post();

        $mailLogId = $this->dashboard_model->mailUpdateCount($post['lastId'],$post['senderEmail']);
        $data['Count'] = $mailLogId['total'];

        echo json_encode($data);
    }

    public function saveErrorLog()
    {
        $post = $this->input->post();

        if(isset($post['errorTxt']))
        {
            if(isset($_SERVER['HTTP_REFERER']))
            {
                $post['refUrl'] = $_SERVER['HTTP_REFERER'];
            }
            $this->dashboard_model->saveErrorLog($post);
        }
        return true;
    }

    public function twitterStuff()
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $data = array();

        $data['existingTweets'] = $this->dashboard_model->getAllTweets();

        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);

        $this->load->view('TwitterStuffView', $data);
    }
    public function uploadTweetFiles()
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
                $config['upload_path'] = '../mobile/'.TWITTER_BOT_PATH; // FOOD_PATH_THUMB; //'uploads/food/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size']      = '0';
                $config['overwrite']     = TRUE;

                $this->upload->initialize($config);
                if(!$this->upload->do_upload('attachment'))
                {
                    log_message('error','Fnb: '.$this->upload->display_errors());
                    $data['status'] = false;
                    $data['errorMsg'] = $this->upload->display_errors();
                    echo json_encode($data);
                    return false;
                }
                else
                {
                    $upload_data = $this->upload->data();
                    $attchmentArr= $this->image_thumb($upload_data['file_path'],$upload_data['file_name']);
                    if($attchmentArr == 'error')
                    {
                        $data['status'] = false;
                        $data['errorMsg'] = 'Error in resizing image!';
                        echo json_encode($data);
                        return false;
                    }
                    else
                    {
                        echo $attchmentArr;
                    }
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
            $data['errorMsg'] = 'No Image Files Received!';
            echo json_encode($data);
            return false;
        }
    }

    public function saveTweet()
    {
        $post = $this->input->post();
        $data = array();
        if(isset($post['tweetText']) || isset($post['attachment']))
        {
            $dataToSave = array(
                'userId' => $this->userId,
                'tweetText' => $post['tweetText'],
                'tweetImage' => $post['attachment'],
                'masterTweetCount' => $post['masterTweetCount'],
                'insertedDateTime' => date('Y-m-d H:i:s')
            );
            $this->dashboard_model->saveTweet($dataToSave);
        }
        $data['status'] = true;
        echo json_encode($data);
    }

    //Geting All Alternate event share images
    public function getShareImgs($eventId)
    {
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Logged Out of dashboard!';
        }
        else
        {
            $data['status'] = true;
            $shareImgs = $this->dashboard_model->getAllShareImgs($eventId);
            if(isset($shareImgs) && myIsArray($shareImgs))
            {
                $data['shareImgs'] = $shareImgs;
            }
        }
        echo json_encode($data);
    }
    public function uploadShareFiles($eventId)
    {
        /*if(strpos($_SERVER['HTTP_HOST'],'doolally.io'))
        {

        }*/
        $data = array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Session Timeout, Please Login Again!';
            echo json_encode($data);
            return false;
        }

        $this->load->library('upload');
        if(isset($_FILES))
        {
            if($_FILES['attachment']['error'] != 1)
            {
                $filePath = $_FILES['attachment']['name'];
                $fileName = preg_replace('/\(|\)/','',$filePath);
                $fileName = preg_replace('/[^a-zA-Z0-9.]\.]/', '', $fileName);
                $fileName = str_replace(' ','_',$fileName);
                $config = array();
                $config['upload_path'] = '../mobile/uploads/events/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size']      = '0';
                $config['overwrite']     = TRUE;
                $config['file_name']     = $fileName;

                $this->upload->initialize($config);
                if(!$this->upload->do_upload('attachment'))
                {
                    log_message('error','Event: '.$this->upload->display_errors());
                    $data['status'] = false;
                    $data['errorMsg'] = $this->upload->display_errors();
                    echo json_encode($data);
                    return false;
                }
                else
                {
                    $upload_data = $this->upload->data();
                    $attchmentArr= $this->image_thumb($upload_data['file_path'],$upload_data['file_name']);
                    if($attchmentArr == 'error')
                    {
                        $data['status'] = false;
                        $data['errorMsg'] = 'Error in resizing image!';
                        echo json_encode($data);
                        return false;
                    }
                    else
                    {
                        $details = array(
                            'eventId' => $eventId,
                            'filename' => $attchmentArr,
                            'ifUsing' => 0,
                            'insertedDT' => date('Y-m-d H:i:s')
                        );
                        $this->dashboard_model->saveShareImg($details);
                    }
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
            $data['errorMsg'] = 'No Image Files Received!';
            echo json_encode($data);
            return false;
        }
    }

    public function saveAltShareImg()
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

        if(isset($post['eventId']) && isset($post['hasShareImg']) && isset($post['imgId']))
        {
            $details = array(
                'hasShareImg' => $post['hasShareImg']
            );
            $this->dashboard_model->updateEventRecord($details,$post['eventId']);
            $this->dashboard_model->resetShareImgs($post['eventId']);
            $this->dashboard_model->makeShareImgActive($post['imgId']);
        }
        $data['status'] = true;

        echo  json_encode($data);
        $logDetails = array(
            'logMessage' => 'Function: saveAltShareImg, User: '.$this->userId,
            'fromWhere' => 'Dashboard',
            'insertedDT' => date('Y-m-d H:i:s')
        );
        $this->dashboard_model->saveDashLogs($logDetails);
    }

    public function setCustomMailText()
    {
        $post = $this->input->post();
        $data= array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Session Timeout, Please Login Again!';
            echo json_encode($data);
            return false;
        }
        if(isset($post['eventId']) && isset($post['customEmailText']) && $post['customEmailText'] != '')
        {
            $eveUpdate = array(
                'customEmailText' => $post['customEmailText']
            );
            $this->dashboard_model->updateEventRecord($eveUpdate,$post['eventId']);
            $data['status'] = true;

            $logDetails = array(
                'eventId' => $post['eventId'],
                'mailText' => $post['customEmailText'],
                'insertedDT' => date('Y-m-d H:i:s')
            );
            $this->dashboard_model->saveCustomMailLog($logDetails);

            $logDetails = array(
                'logMessage' => 'Function: setCustomMailText, User: '.$this->userId,
                'fromWhere' => 'Dashboard',
                'insertedDT' => date('Y-m-d H:i:s')
            );
            $this->dashboard_model->saveDashLogs($logDetails);
        }
        else
        {
            $data['status'] = false;
            $data['errorMsg'] = 'All fields are required!';
        }
        echo json_encode($data);
    }

    public function clearEveMail($eventId)
    {
        $data= array();
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Session Timeout, Please Login Again!';
            echo json_encode($data);
            return false;
        }
        $eveUpdate = array(
            'customEmailText' => null
        );
        $this->dashboard_model->updateEventRecord($eveUpdate,$eventId);
        $data['status'] = true;
        echo json_encode($data);
    }

    // Getting organiser wise events collection
    public function getOrgCollection($isNew = 1)
    {
        $data = array();
        if($isNew == 1)
        {
            //Total for current active events
            $newOrgs = $this->dashboard_model->getOrgNewEvents();
            if(isset($newOrgs) && myIsArray($newOrgs))
            {
                foreach($newOrgs as $key => $row)
                {
                    $data['data'][$key][] = $row['creatorName'];
                    $data['data'][$key][] = $row['creatorEmail'];
                    $data['data'][$key][] = $row['creatorPhone'];

                    $totEveIds = explode(',',$row['ids']);
                    $totEveNames = explode(';',$row['eveNames']);
                    $total = 0;
                    $eveNames = array();
                    $eveAmts = array();
                    for($i=0;$i<count($totEveIds);$i++)
                    {
                        $eveNames[] = $totEveNames[$i];
                        $allRegis = $this->dashboard_model->getEventAllRegs($totEveIds[$i]);
                        if(isset($allRegis) && myIsArray($allRegis))
                        {
                            $subTot = 0;
                            foreach($allRegis as $regKey => $regRow)
                            {
                                $subTot += ((int)$regRow['quantity'] * (int)$regRow['price']);
                            }
                            $eveAmts[] = $subTot;
                            $total += $subTot;
                        }
                        else
                        {
                            $eveAmts[] = 0;
                            $total += 0;
                        }
                    }
                    $data['data'][$key][] = 'Rs. '.$total;
                    $data['data'][$key][] = '<a href="#" class="viewDetails-icon" data-eveNames="'.implode(';',$eveNames).'"
                                               data-eveAmts= "'.implode(';',$eveAmts).'">
                                                View Details</a>';
                }
            }
            else
            {
                $data['data'] = null;
            }
        }
        else
        {
            //Total for Completed Events
            $newOrgs = $this->dashboard_model->getOrgOldEvents();
            if(isset($newOrgs) && myIsArray($newOrgs))
            {
                foreach($newOrgs as $key => $row)
                {
                    $data['data'][$key][] = $row['creatorName'];
                    $data['data'][$key][] = $row['creatorEmail'];
                    $data['data'][$key][] = $row['creatorPhone'];

                    $totEveIds = explode(',',$row['ids']);
                    $totEveNames = explode(';',$row['eveNames']);
                    $total = 0;
                    $eveNames = array();
                    $eveAmts = array();
                    for($i=0;$i<count($totEveIds);$i++)
                    {
                        $eveNames[] = $totEveNames[$i];
                        $allRegis = $this->dashboard_model->getEventAllOldRegs($totEveIds[$i]);
                        if(isset($allRegis) && myIsArray($allRegis))
                        {
                            $subTot = 0;
                            foreach($allRegis as $regKey => $regRow)
                            {
                                $subTot += ((int)$regRow['quantity'] * (int)$regRow['price']);
                            }
                            $eveAmts[] = $subTot;
                            $total += $subTot;
                        }
                        else
                        {
                            $eveAmts[] = 0;
                            $total += 0;
                        }
                    }
                    $data['data'][$key][] = 'Rs. '.$total;
                    $data['data'][$key][] = '<a href="#" class="viewDetails-icon" data-eveNames="'.implode(';',$eveNames).'"
                                               data-eveAmts= "'.implode(';',$eveAmts).'">
                                                View Details</a>';
                }
            }
            else
            {
                $data['data'] = null;
            }
        }
        echo json_encode($data);
    }

    public function mailTest()
    {
        $mD = array(
            'eventName' => 'abc Test',
            'locName' => 'andheri',
            'eventDate' => '2017-12-20',
            'startTime' => '10:00'
        );
        $this->sendemail_library->eventExtraToMaintMail($mD);
    }
}
