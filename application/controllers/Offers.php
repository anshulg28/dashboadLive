<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Offers
 * @property Offers_Model $offers_model
*/

class Offers extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('offers_model');
	}
	public function index()
	{

        $data = array();
        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);

        if(isSessionVariableSet($this->isUserSession) === true)
        {
            $data['title'] = 'Offers :: Doolally';
        }
        else
        {
            $data['title'] = 'Login :: Doolally';
        }
        $this->load->view('OfferView', $data);
	}

    public function check()
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $this->load->model('mugclub_model');
        $this->session->set_userdata('page_url', base_url(uri_string()));
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

        $data = array();
        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);

        $this->load->view('OfferCheckView', $data);
    }


    public function generate()
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $data = array();
        $data['todayCount'] = $this->offers_model->getTodayCodes();
        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);

        $this->load->view('OfferGenView', $data);
    }

    public function createCodes($responseType = RESPONSE_JSON)
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $post = $this->input->post();
        $usedCodes = array();
        $unUsedCodes = array();
        $toBeInserted = array();
        $allCodes = $this->offers_model->getAllCodes();
        if($allCodes['status'] === true)
        {
            foreach($allCodes['codes'] as $key => $row)
            {
                $usedCodes[] = $row['offerCode'];
            }

            if(isset($post['beerNums']) && $post['beerNums'] != 0)
            {
                for($i=0;$i<$post['beerNums'];$i++)
                {
                    $newCode = mt_rand(1000,99999);
                    while(myInArray($newCode,$usedCodes))
                    {
                        $newCode = mt_rand(1000,99999);
                    }
                    $unUsedCodes[] = array(
                        'code' => $newCode,
                        'type' => 'Beer'
                    );

                    $toBeInserted[] = array(
                        'offerCode' => $newCode,
                        'offerType' => 'Beer',
                        'offerLoc' => null,
                        'isRedeemed' => 0,
                        'ifActive' => 1,
                        'createDateTime' => date('Y-m-d H:i:s'),
                        'useDateTime' => null
                    );

                }
            }
            if(isset($post['breakNums']) && $post['breakNums'] != 0)
            {
                for($i=0;$i<$post['breakNums'];$i++)
                {
                    $newCode = mt_rand(1000,99999);
                    while(myInArray($newCode,$usedCodes))
                    {
                        $newCode = mt_rand(1000,99999);
                    }
                    $unUsedCodes[] = array(
                        'code' => $newCode,
                        'type' => 'Breakfast2'
                    );
                    $toBeInserted[] = array(
                        'offerCode' => $newCode,
                        'offerType' => 'Breakfast2',
                        'offerLoc' => null,
                        'isRedeemed' => 0,
                        'ifActive' => 1,
                        'createDateTime' => date('Y-m-d H:i:s'),
                        'useDateTime' => null
                    );

                }
            }
            if(isset($post['eventNums']) && $post['eventNums'] != 0)
            {
                for($i=0;$i<$post['eventNums'];$i++)
                {
                    $newCode = mt_rand(1000,99999);
                    while(myInArray($newCode,$usedCodes))
                    {
                        $newCode = mt_rand(1000,99999);
                    }
                    $unUsedCodes[] = array(
                        'code' => $newCode,
                        'type' => 'Workshop'
                    );
                    $toBeInserted[] = array(
                        'offerCode' => $newCode,
                        'offerType' => 'Workshop',
                        'offerLoc' => null,
                        'isRedeemed' => 0,
                        'ifActive' => 1,
                        'createDateTime' => date('Y-m-d H:i:s'),
                        'useDateTime' => null
                    );

                }
            }
            if(isset($post['customCode']) && $post['customNums'] != 0)
            {
                for($i=0;$i<$post['customNums'];$i++)
                {
                    $newCode = mt_rand(1000,99999);
                    while(myInArray($newCode,$usedCodes))
                    {
                        $newCode = mt_rand(1000,99999);
                    }
                    $unUsedCodes[] = array(
                        'code' => $newCode,
                        'type' => $post['customName']
                    );
                    $toBeInserted[] = array(
                        'offerCode' => $newCode,
                        'offerType' => $post['customName'],
                        'offerLoc' => null,
                        'isRedeemed' => 0,
                        'ifActive' => 1,
                        'createDateTime' => date('Y-m-d H:i:s'),
                        'useDateTime' => null
                    );

                }
            }
        }
        else
        {
            if(isset($post['beerNums']) && $post['beerNums'] != 0)
            {
                for($i=0;$i<$post['beerNums'];$i++)
                {
                    $newCode = mt_rand(1000,99999);

                    $unUsedCodes[] = array(
                        'code' => $newCode,
                        'type' => 'Beer'
                    );
                    $toBeInserted[] = array(
                        'offerCode' => $newCode,
                        'offerType' => 'Beer',
                        'offerLoc' => null,
                        'isRedeemed' => 0,
                        'ifActive' => 1,
                        'createDateTime' => date('Y-m-d H:i:s'),
                        'useDateTime' => null
                    );

                }
            }
            if(isset($post['breakNums']) && $post['breakNums'] != 0)
            {
                for($i=0;$i<$post['breakNums'];$i++)
                {
                    $newCode = mt_rand(1000,99999);

                    $unUsedCodes[] = array(
                        'code' => $newCode,
                        'type' => 'Breakfast2'
                    );
                    $toBeInserted[] = array(
                        'offerCode' => $newCode,
                        'offerType' => 'Breakfast2',
                        'offerLoc' => null,
                        'isRedeemed' => 0,
                        'ifActive' => 1,
                        'createDateTime' => date('Y-m-d H:i:s'),
                        'useDateTime' => null
                    );

                }
            }
            if(isset($post['customCode']) && $post['customNums'] != 0)
            {
                for($i=0;$i<$post['customNums'];$i++)
                {
                    $newCode = mt_rand(1000,99999);

                    $unUsedCodes[] = array(
                        'code' => $newCode,
                        'type' => $post['customName']
                    );
                    $toBeInserted[] = array(
                        'offerCode' => $newCode,
                        'offerType' => $post['customName'],
                        'offerLoc' => null,
                        'isRedeemed' => 0,
                        'ifActive' => 1,
                        'createDateTime' => date('Y-m-d H:i:s'),
                        'useDateTime' => null
                    );

                }
            }
        }

        $this->offers_model->setAllCodes($toBeInserted);
        if($responseType == RESPONSE_JSON)
        {
            echo json_encode($unUsedCodes);
        }
        else
        {
            return $unUsedCodes;
        }
    }

    public function stats()
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $data = array();
        $data['offerCodes'] = $this->offers_model->getOfferCodes();
        $data['oldOffersCodes'] = $this->offers_model->getOldOfferCodes();

        //Getting All Offers Stats
        $data['newOfferStats'] = $this->offers_model->getOffersStats();
        $data['oldOfferStats'] = $this->offers_model->getOldOffersStats();

        $data['globalStyle'] = $this->dataformatinghtml_library->getGlobalStyleHtml($data);
        $data['globalJs'] = $this->dataformatinghtml_library->getGlobalJsHtml($data);
        $data['headerView'] = $this->dataformatinghtml_library->getHeaderHtml($data);
        $data['footerView'] = $this->dataformatinghtml_library->getFooterHtml($data);

        $this->load->view('OfferStatsView', $data);
    }

    public function getOfferStats()
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $data = array();

        $statsData = $this->offers_model->getOfferCodes();
        if(isset($statsData) && myIsArray($statsData))
        {
            foreach($statsData['codes'] as $key => $row)
            {
                $data['data'][$key][] = $row['id'];
                $offCode = '';

                switch($row['offerType'])
                {
                    case 'Breakfast2':
                        $offCode = 'BR-'.$row['offerCode'];
                        break;
                    case 'Beer':
                        $offCode = 'DO-'.$row['offerCode'];
                        break;
                    case 'Workshop':
                        $offCode = 'EV-'.$row['offerCode'];
                        break;
                    default:
                        if(isset($row['offerEvent']))
                        {
                            $offCode = 'EV-'.$row['offerCode'];
                        }
                        else
                        {
                            $offCode = 'DO-'.$row['offerCode'];
                        }
                        break;
                }
                $data['data'][$key][] = $offCode;
                if($row['offerType'] == 'Breakfast2')
                {
                    $data['data'][$key][]= 'Breakfast For Two';
                }
                else
                {
                    $data['data'][$key][]= $row['offerType'];
                }
                $data['data'][$key][] = $row['locName'];
                $data['data'][$key][] = $row['createDateTime'];
                $data['data'][$key][] = $row['useDateTime'];
                $actions = '<a data-toggle="tooltip" class="mugDelete-icon" title="Delete" data-offerId="'.$row['id'].'"
                                               data-offerCode= "'.$row['offerCode'].'">
                                                <i class="fa fa-trash-o"></i></a>&nbsp;';
                if($row['isRedeemed'] == 1)
                {
                    $actions .= '<a data-toggle="tooltip" class="repeat-coupon" title="Renew" href="'.base_url().'offers/offerUnused/'.$row['id'].'">
                                                    <i class="fa fa-repeat"></i></a>';
                }
                $data['data'][$key][] = $actions;
            }
        }
        else
        {
            $data['data'] = null;
        }
        echo json_encode($data);
    }

    public function delete($offerId, $offerAge)
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        if(isset($offerId))
        {
            if($offerAge == 'old')
            {
                $this->offers_model->deleteOldOfferRecord($offerId);
            }
            else
            {
                $this->offers_model->deleteOfferRecord($offerId);
            }
        }
        redirect(base_url().'offers/stats');
    }

    public function offerCheck($offerCode,$toRedeem)
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $data = array();
        $offerStatus = $this->offers_model->checkOfferCode($offerCode);
        if($offerStatus['status'] === false)
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Invalid Code!';
        }
        else
        {
            if($offerStatus['codeCheck']['isRedeemed'] == 1)
            {
                $data['status'] = false;
                $data['errorMsg'] = 'Sorry, this code has been redeemed before.';
            }
            elseif($toRedeem == '1')
            {
                $offerData = array();
                $offerData['offerCode'] = $offerCode;
                if(isset($this->currentLocation) || isSessionVariableSet($this->currentLocation) === true)
                {
                    $offerData['offerLoc'] = $this->currentLocation;
                }
                $offerData['isRedeemed'] = 1;
                $offerData['useDateTime'] = date('Y-m-d H:i:s');
                $offerTimes = $this->config->item('offerTimes');
                foreach($offerTimes as $key)
                {
                    $keySplit = explode('-',$key);
                    if((int)$keySplit[0] <= (int)date('H') && (int)date('H') <= (int)$keySplit[1] )
                    {
                        $offerData['usedTimeSpan'] = $key;
                        break;
                    }
                }
                if(!isset($offerData['usedTimeSpan']))
                {
                    $offerData['usedTimeSpan'] = '18-2';
                }
                $offerData['dayOfferUsed'] = date('D');
                $this->offers_model->setOfferUsed($offerData);
                $data['status'] = true;
                if($offerStatus['codeCheck']['offerEvent'] == '536' && stripos($offerStatus['codeCheck']['offerType'],'3000') !== false)
                {
                    $data['offerType'] = 'Valid for food & beer (3000)';
                }
                elseif($offerStatus['codeCheck']['offerEvent'] == '536' && stripos($offerStatus['codeCheck']['offerType'],'2000') !== false)
                {
                    $data['offerType'] = 'Valid for food (2000)';
                }
                else
                {
                    $data['offerType'] = $offerStatus['codeCheck']['offerType'];
                }
            }
            else
            {
                if(isset($offerStatus['codeCheck']['validFromDate']))
                {
                    $toDay = date('Y-m-d');
                    $d = date_create($offerStatus['codeCheck']['validFromDate']);
                    if(strtotime($toDay) >= strtotime($offerStatus['codeCheck']['validFromDate']))
                    {
                        $data['status'] = true;
                        if($offerStatus['codeCheck']['offerEvent'] == '536' && stripos($offerStatus['codeCheck']['offerType'],'3000') !== false)
                        {
                            $data['offerType'] = 'food & beer (3000)';
                        }
                        elseif($offerStatus['codeCheck']['offerEvent'] == '536' && stripos($offerStatus['codeCheck']['offerType'],'2000') !== false)
                        {
                            $data['offerType'] = 'food (2000)';
                        }
                        else
                        {
                            $data['offerType'] = $offerStatus['codeCheck']['offerType'];
                        }
                    }
                    else
                    {
                        $data['status'] = false;
                        $data['errorMsg'] = 'This code isn\'t active yet. Will be active on '.date_format($d,DATE_MAIL_FORMAT_UI);
                    }
                }
                else
                {
                    $data['status'] = true;
                    $data['offerType'] = $offerStatus['codeCheck']['offerType'];
                }
            }
        }
        echo json_encode($data);
    }

    public function oldOfferCheck($offerCode, $toRedeem)
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $data = array();
        $offerStatus = $this->offers_model->checkOldOfferCode($offerCode);

        if($offerStatus['status'] === false)
        {
            $data['status'] = false;
            $data['errorMsg'] = 'Invalid Code!';
        }
        else
        {
            if($offerStatus['codeCheck']['isRedeemed'] == "1")
            {
                $data['status'] = false;
                $data['errorMsg'] = 'Sorry, this code has been redeemed before.';
            }
            elseif($toRedeem == '1')
            {
                $offerData = array();
                $offerData['offerCode'] = $offerCode;
                if(isset($this->currentLocation) || isSessionVariableSet($this->currentLocation) === true)
                {
                    $offerData['offerLoc'] = $this->currentLocation;
                }
                $offerData['isRedeemed'] = 1;
                $offerData['useDateTime'] = date('Y-m-d H:i:s');
                $offerTimes = $this->config->item('offerTimes');

                foreach($offerTimes as $key)
                {
                    $keySplit = explode('-',$key);
                    if((int)$keySplit[0] <= (int)date('H') && (int)date('H') <= (int)$keySplit[1] )
                    {
                        $offerData['usedTimeSpan'] = $key;
                        break;
                    }
                }
                if(!isset($offerData['usedTimeSpan']))
                {
                    $offerData['usedTimeSpan'] = '18-2';
                }
                $offerData['dayOfferUsed'] = date('D');
                $this->offers_model->setoldOfferUsed($offerData);
                $data['status'] = true;
                $data['offerType'] = $offerStatus['codeCheck']['offerType'];
            }
            else
            {
                $data['status'] = true;
                $data['offerType'] = $offerStatus['codeCheck']['offerType'];
            }
        }

        echo json_encode($data);
    }
    
    public function offerUnused($id)
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $this->offers_model->setOfferUnused($id);

        redirect($this->pageUrl);
    }
    public function oldOfferUnused($id)
    {
        if(isSessionVariableSet($this->isUserSession) === false)
        {
            redirect(base_url());
        }
        $this->offers_model->setoldOfferUnused($id);

        redirect($this->pageUrl);
    }

    //Beer Olympics Coupon Codes for Mug Club Members
    public function mugBeerOlympicsCoupons()
    {
        $toBeInserted = array();
        $couponGens = array();

        $mugList = $this->offers_model->getAllMugClubList();

        if(isset($mugList) && myIsArray($mugList))
        {
            foreach($mugList as $key => $row)
            {
                $newCode = mt_rand(1000,99999);

                while(myInArray($newCode,$couponGens))
                {
                    $newCode = mt_rand(1000,99999);
                }

                $toBeInserted[] = array(
                    'couponCode' => 'BO'.$newCode,
                    'couponType' => 'Percentage',
                    'couponDetails' => '25',
                    'ownerDetails' => $row['mugId'],
                    'couponExpiry' => '2017-05-20',
                    'isRedeemed' => 0,
                    'ifActive' => 1,
                    'createDateTime' => date('Y-m-d H:i:s'),
                    'useDateTime' => null
                );
            }
        }

        $this->offers_model->saveOlympicsCodes($toBeInserted);
        echo 'Saved';
    }
    public function randomBeerOlympicsCoupons()
    {
        $toBeInserted = array();
        $couponGens = array();

        $coupons = $this->offers_model->getOlympicsCoupons();
        if(isset($coupons) && myIsArray($coupons))
        {
            $coupons = explode(',',$coupons['codes']);
            for($i=0;$i<100;$i++)
            {
                $newCode = mt_rand(1000,99999);

                while(myInArray('BO'.$newCode,$coupons))
                {
                    $newCode = mt_rand(1000,99999);
                }

                $toBeInserted[] = array(
                    'couponCode' => 'BO'.$newCode,
                    'couponType' => 'Percentage',
                    'couponDetails' => '12.5',
                    'ownerDetails' => 'unknown',
                    'couponExpiry' => '2017-05-20',
                    'isRedeemed' => 0,
                    'ifActive' => 1,
                    'createDateTime' => date('Y-m-d H:i:s'),
                    'useDateTime' => null
                );
            }

            $this->offers_model->saveOlympicsCodes($toBeInserted);
            echo 'Saved';
        }
        else{
            echo 'Failed';
        }
    }
}
