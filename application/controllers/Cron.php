<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Cron
 * @property Cron_model $cron_model
 * @property Dashboard_Model $dashboard_model
 * @property Locations_Model $locations_model
 * @property Mugclub_Model $mugclub_model
 */

class Cron extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('cron_model');
        $this->load->model('dashboard_model');
        $this->load->model('locations_model');
        $this->load->model('mugclub_model');
    }
    public function index()
    {
        $this->load->view('Page404View');
    }

        public function feedsFetch()
    {

        $twitter = $this->getTwitterFeeds();

        $instagram = $this->getInstagramFeeds();

        $facebook =  $this->getFacebookResponse();

        if(myIsArray($facebook))
        {
            //facebook
            $fbData = $this->cron_model->checkFeedByType("1");

            $fbPost = array(
                'feedText' => json_encode($facebook),
                'feedType' => '1'
            );
            if($fbData['status'] === true)
            {
                $this->cron_model->updateFeedByType($fbPost,"1");
            }
            else
            {
                $this->cron_model->insertFeedByType($fbPost);
            }
        }

        if(myIsArray($twitter))
        {
            //twitter
            $fbData = $this->cron_model->checkFeedByType("2");

            $fbPost = array(
                'feedText' => json_encode($twitter),
                'feedType' => '2'
            );
            if($fbData['status'] === true)
            {
                $this->cron_model->updateFeedByType($fbPost, "2");
            }
            else
            {
                $this->cron_model->insertFeedByType($fbPost);
            }
        }

        if(myIsArray($instagram))
        {
            //Instagram
            $fbData = $this->cron_model->checkFeedByType("3");

            $fbPost = array(
                'feedText' => json_encode($instagram),
                'feedType' => '3'
            );
            if($fbData['status'] === true)
            {
                $this->cron_model->updateFeedByType($fbPost, "3");
            }
            else
            {
                $this->cron_model->insertFeedByType($fbPost);
            }
        }

        $this->storeAllFeeds();
    }
    public function getTwitterFeeds()
    {
        $twitterFeeds = '';
        $this->twitter->tmhOAuth->reconfigure();
        $oldparmas = array(
            'count' => '20',
            'exclude_replies' => 'true',
            'screen_name' => 'godoolally'
        );
        $parmas = array(
            'count' => '20',
            'q' => '#doolally OR #animalsofdoolally OR #ontapnow OR doolally OR doolaly
                     OR @godoolally -filter:retweets',
            'geocode' => '20.1885251,64.446117,1000km',
            'lang' => 'en',
            'result_type' => 'recent'
        );
        //$responseCode = $this->twitter->tmhOAuth->request('GET','https://api.twitter.com/1.1/statuses/user_timeline.json',$parmas);
        $responseCode = $this->twitter->tmhOAuth->request('GET','https://api.twitter.com/1.1/search/tweets.json',$parmas);
        if($responseCode == 200)
        {
            $twitterFeeds = $this->twitter->tmhOAuth->response['response'];
            $oldresponseCode = $this->twitter->tmhOAuth->request('GET','https://api.twitter.com/1.1/statuses/user_timeline.json',$oldparmas);

            if($oldresponseCode == 200)
            {
                $oldTwitterFeeds = $this->twitter->tmhOAuth->response['response'];
                $oldTwitterFeeds = json_decode($oldTwitterFeeds,true);
            }
        }
        $twitterFeeds = json_decode($twitterFeeds,true);

        if(isset($oldTwitterFeeds) && myIsMultiArray($oldTwitterFeeds))
        {
            return array_merge($twitterFeeds['statuses'], $oldTwitterFeeds);
        }
        else
        {
            return $twitterFeeds['statuses'];
        }
    }

    public function checkTweetValid()
    {
        $this->twitter->tmhOAuth->reconfigure();
        $responseCode = $this->twitter->tmhOAuth->request('GET','https://api.twitter.com/1.1/statuses/show/821638303996846080.json');
        // If 200 then found else 404 not found!
        echo '<pre>';
        var_dump($responseCode,$this->twitter->tmhOAuth->response['response']);
    }
    public function getInstagramFeeds()
    {
        $instaFeeds = $this->curl_library->getInstagramPosts();
        $moreInsta = $this->curl_library->getMoreInstaFeeds();

        if(!isset($instaFeeds) && !myIsMultiArray($instaFeeds))
        {
            $instaFeeds = null;
        }
        else
        {
            $instaFeeds = $instaFeeds['posts']['items'];
        }

        if(!isset($moreInsta) && !myIsMultiArray($moreInsta))
        {
            $moreInsta = null;
        }
        else
        {
            $moreInsta = $moreInsta['posts']['items'];
        }

        if(myIsMultiArray($instaFeeds) && myIsMultiArray($moreInsta))
        {
            $totalFeeds = array_merge($instaFeeds,$moreInsta);
            shuffle($totalFeeds);
            if(count($totalFeeds) > 90)
            {
                $totalFeeds = array_slice($totalFeeds,0, 85);
            }
        }
        else
        {
            $totalFeeds = (isset($instaFeeds) ? $instaFeeds : $moreInsta);
        }

        return $totalFeeds;
    }

    public function getFacebookResponse()
    {
        $params = array(
            'access_token' => FACEBOOK_TOKEN,
            'limit' => '15',
            'fields' => 'message,permalink_url,id,from,name,picture,source,updated_time'
        );
        $fbFeeds[] = $this->curl_library->getFacebookPosts('godoolallyandheri',$params);
        $fbFeeds[] = $this->curl_library->getFacebookPosts('godoolallybandra',$params);
        //kemps
        $fbFeeds[] = $this->curl_library->getFacebookPosts('1741740822733140',$params);
        $fbFeeds[] = $this->curl_library->getFacebookPosts('godoolally',$params);

        if(isset($fbFeeds) && myIsMultiArray($fbFeeds) && isset($fbFeeds[0]['data']))
        {
            return array_merge($fbFeeds[0]['data'],$fbFeeds[1]['data'],$fbFeeds[2]['data']);
        }
        else
        {
            return null;
        }
    }

    public function shiftEvents()
    {
        $events = $this->cron_model->findCompletedEvents();

        if(isset($events) && myIsMultiArray($events))
        {
            foreach($events as $key => $row)
            {
                if($row['ifAutoCreated'] == '1')
                {
                    $newDate = date('Y-m-d', strtotime($row['eventDate'].' +1 week'));
                    $this->cron_model->extendAutoEvent($row['eventId'],$newDate);
                }
                else
                {
                    $this->cron_model->updateEventRegis($row['eventId']);
                    $this->cron_model->transferEventRecord($row['eventId']);
                }
            }
        }
    }

    public function weeklyFeedback()
    {
        $locArray = $this->locations_model->getAllLocations();
        $feedbacks = $this->dashboard_model->getAllFeedbacks($locArray);

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

        if($total['overall'] != 0)
        {
            $data[] = (int)(($promo['overall']/$total['overall'])*100 - ($de['overall']/$total['overall'])*100);
        }
        if($total['bandra'] != 0)
        {
            $data[] = (int)(($promo['bandra']/$total['bandra'])*100 - ($de['bandra']/$total['bandra'])*100);
        }
        if($total['andheri'] != 0)
        {
            $data[] = (int)(($promo['andheri']/$total['andheri'])*100 - ($de['andheri']/$total['andheri'])*100);
        }
        if($total['kemps-corner'] != 0)
        {
            $data[] = (int)(($promo['kemps-corner']/$total['kemps-corner'])*100 - ($de['kemps-corner']/$total['kemps-corner'])*100);
        }
        if($total['colaba'] != 0)
        {
            $data[] = (int)(($promo['colaba']/$total['colaba'])*100 - ($de['colaba']/$total['colaba'])*100);
        }
        if($total['khar'] != 0)
        {
            $data[] = (int)(($promo['khar']/$total['khar'])*100 - ($de['khar']/$total['khar'])*100);
        }

        $details = array(
            'locs' => implode(',',$data),
            'insertedDate' => date('Y-m-d')
        );
        $this->cron_model->insertWeeklyFeedback($details);
    }

    public function monthWiseFeedback()
    {
        $feedCols = array('Month','Overall','Andheri','Bandra','Kemps','Colaba','Promoters','Detractors','Total');
        $months = array('Jan','Feb','March','April','May','June');

        $monthCounter = 0;

        $locArray = $this->locations_model->getAllLocations();
        $file = fopen("./uploads/Feedback_Monthwise_".date('d_M').".csv","w");
        fputcsv($file,$feedCols);
        $feedData = array();
        for($i=0;$i<count($months); $i++)
        {
            $startDate = '2017-0'.($i+1).'-01';
            if(($i+1) % 2 == 0)
            {
                $endDate = '2017-0'.($i+1).'-30';
            }
            else
            {
                $endDate = '2017-0'.($i+1).'-31';
            }

            $feedbacks = $this->dashboard_model->getFeedbacksMonthWise($locArray,$startDate,$endDate);

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
            if($total['overall'] != 0)
            {
                $data['overall'] = (int)(($promo['overall']/$total['overall'])*100 - ($de['overall']/$total['overall'])*100);
            }
            if($total['bandra'] != 0)
            {
                $data['bandra'] = (int)(($promo['bandra']/$total['bandra'])*100 - ($de['bandra']/$total['bandra'])*100);
            }
            if($total['andheri'] != 0)
            {
                $data['andheri'] = (int)(($promo['andheri']/$total['andheri'])*100 - ($de['andheri']/$total['andheri'])*100);
            }
            if($total['kemps-corner'] != 0)
            {
                $data['kemps'] = (int)(($promo['kemps-corner']/$total['kemps-corner'])*100 - ($de['kemps-corner']/$total['kemps-corner'])*100);
            }
            if($total['colaba'] != 0)
            {
                $data['colaba'] = (int)(($promo['colaba']/$total['colaba'])*100 - ($de['colaba']/$total['colaba'])*100);
            }

            $feedData[$months[$i]] = array(
                'total'=> $total,
                'promo'=> $promo,
                'de'=> $de,
                'data'=> $data
            ); //array_merge($total,$promo,$de,$data);
            /*if(isset($data['colaba']))
            {
                $feedData[] = array(
                    $months[$i],
                    $data['overall'],
                    $data['andheri'],
                    $data['bandra'],
                    $data['kemps'],
                    $data['colaba'],
                    $promo['overall'],
                    $de['overall'],
                    $total['overall']
                );
            }
            else
            {
                $feedData[] = array(
                    $months[$i],
                    $data['overall'],
                    $data['andheri'],
                    $data['bandra'],
                    $data['kemps'],
                    '0',
                    $promo['overall'],
                    $de['overall'],
                    $total['overall']
                );
            }*/

            //fputcsv($file,$feedData);
        }
        echo '<pre>';
        var_dump($feedData);
        die();
        fclose($file);

        $content = '<html><body><p>Doolally Feedback Monthwise<br>PFA</p></body></html>';

        $this->sendemail_library->sendEmail('saha@brewcraftsindia.com','anshul@brewcraftsindia.com','admin@brewcraftsindia.com','ngks2009','Doolally'
            ,'admin@brewcraftsindia.com','Doolally Feedback Monthwise | '.date('d_M_Y'),$content,array("./uploads/Feedback_Monthwise_".date('d_M').".csv"));
        try
        {
            unlink("./uploads/Feedback_Monthwise_".date('d_M').".csv");
        }
        catch(Exception $ex)
        {

        }

    }

    public function fixForColaba()
    {
        $weekFeed = $this->cron_model->getAllWeekly();
        if(isset($weekFeed) && myIsArray($weekFeed))
        {
            foreach($weekFeed as $key => $row)
            {
                $allData = explode(',',$row['locs']);
                $gotRec = $this->cron_model->getSingleLocFeedbacks($row['insertedDate']);
                if(isset($gotRec) && myIsArray($gotRec))
                {
                    if((int)$gotRec['total_overall'] != 0)
                    {
                        $allData[] = (int)(($gotRec['promo_overall']/$gotRec['total_overall'])*100 - ($gotRec['de_overall']/$gotRec['total_overall'])*100);
                        $postData = array(
                            'locs' => implode(',',$allData)
                        );
                        $this->cron_model->updateWeeklyFeedback($postData,$row['id']);
                    }
                    else
                    {
                        $allData[] = 0;
                        $postData = array(
                            'locs' => implode(',',$allData)
                        );
                        $this->cron_model->updateWeeklyFeedback($postData,$row['id']);
                    }
                }
            }
        }
    }

    public function fetchJukeBoxLists()
    {
        $rests = $this->curl_library->getJukeboxTaprooms();
        if(isset($rests) && myIsMultiArray($rests))
        {
            foreach($rests as $key => $row)
            {
                $details = array();
                $resId = $row['id'];
                $details['tapId'] = $resId;
                $details['tapName'] = $row['name'];
                $playlist = $this->curl_library->getTapPlaylist($resId);
                if(isset($playlist) && myIsMultiArray($playlist))
                {
                    $songs = array();
                    foreach($playlist as $playSub => $playKey)
                    {
                        if($playSub == 1)
                            break;
                        $playId = $playKey['id'];
                        $songs[] = $this->curl_library->getTapSongsByPlaylist($resId,$playId);
                    }
                    $details['tapSongs'] = json_encode($songs);
                }

                //save to DB
                $songs = $this->cron_model->checkTapSongs($resId);
                if($songs['status'] === true)
                {
                    $this->cron_model->updateSongs($resId,$details);
                }
                else
                {
                    $this->cron_model->insertSongs($details);
                }

            }
        }
    }

    public function storeAllFeeds()
    {
        $feedData = $this->cron_model->getAllFeeds();
        $facebook = array();
        $twitter = array();
        $instagram = array();

        $allFeeds = null;

        if($feedData['status'] === true)
        {
            foreach($feedData['feedData'] as $key => $row)
            {
                switch($row['feedType'])
                {
                    case "1":
                        $facebook = json_decode($row['feedText'],true);
                        break;
                    case "2":
                        $twitter = json_decode($row['feedText'],true);
                        break;
                    case "3":
                        $instagram  = json_decode($row['feedText'],true);
                        break;
                }
            }

            $allFeeds = $this->sortNjoin($twitter,$instagram, $facebook);
        }

        //$this->firstTimeFunc($allFeeds);
        $this->splitAndStoreFeeds($allFeeds);
        /*$firstHalf = array_slice($allFeeds,0,20,true);
        $secondHalf = array_slice($allFeeds,20,count($allFeeds),true);*/

        /*//Posting First Half
        $firstPost = array(
            'feedText' => json_encode($firstHalf),
            'feedType' => '4'
        );

        $this->cron_model->updateFeedByType($firstPost, "4");*/

        //Second Half
    }

    function firstTimeFunc()
    {

        $viewFeeds = $this->cron_model->getAllViewFeeds();
        $viewIds = array();
        foreach($viewFeeds as $key => $row)
        {
            $viewIds[] = $row['feedId'];
        }

        $oldFeeds = $this->cron_model->getMoreLatestFeeds(1);
        $firstId = $oldFeeds['id'];
        $oldFeeds = json_decode($oldFeeds['feedText'],true);

        $arrayExists = array();
        $arrayNew = array();

        foreach($oldFeeds as $key => $row)
        {
            $row = json_decode($row,true);
            switch($row['socialType'])
            {
                case 'f':
                    if(myInArray($row['id'],$viewIds))
                    {
                        $arrayExists[] = $row;
                    }
                    else
                    {
                        $arrayNew[] = $row;
                    }
                    break;
                case 'i':
                    if(myInArray($row['id'],$viewIds))
                    {
                        $arrayExists[] = $row;
                    }
                    else
                    {
                        $arrayNew[] = $row;
                    }
                    break;
                case 't':
                    if(myInArray($row['id_str'],$viewIds))
                    {
                        $arrayExists[] = $row;
                    }
                    else
                    {
                        $arrayNew[] = $row;
                    }
                    break;
            }
        }

        $lastestFeeds = $this->cron_model->getMoreLatestFeeds(0);
        $lastId = $lastestFeeds['id'];
        $lastestFeeds = json_decode($lastestFeeds['feedText'], true);

        if(myIsArray($arrayExists))
        {
            $finalNew = array_merge($lastestFeeds, $arrayExists);
        }
        $detailPost = array(
            'feedText' => json_encode($finalNew),
            'postsCount' => count($finalNew)
        );

        $this->cron_model->updateFeedById($detailPost,$lastId);

        $detailPost = array(
            'feedText' => json_encode($arrayNew),
            'postsCount' => count($arrayNew)
        );

        $this->cron_model->updateFeedById($detailPost,$firstId);

    }

    function splitAndStoreFeeds($allFeeds)
    {
        $topFeed = $this->cron_model->getAllViewFeeds();

        $viewIds= array();
        foreach($topFeed as $key => $row)
        {
            $viewIds[] = $row['feedId'];
        }
        $newFeeds = array();
        $newMainFeeds = array();
        $foundId = false;
        foreach($allFeeds as $key => $row)
        {
            switch($row['socialType'])
            {
                case 'f':
                    if(!myInArray($row['id'],$viewIds))
                    {
                        if(isset($row['picture']))
                        {
                            preg_match('/(=http:|=https:|http:|https:)\/\/.+?(\.jpg|\.png|\.gif|\.jpeg)/',urldecode($row['picture']),$matches);
                            if(myIsArray($matches))
                            {
                                $fileArray = explode('/',$matches[0]);
                                $fileName= $fileArray[count($fileArray)-1];
                                if(copy($row['picture'],'../mobile/socialimages/facebook/'.$fileName))
                                {
                                    $row['picture'] = MOBILE_URL.'socialimages/facebook/'.$fileName;
                                }
                            }
                        }
                        $newFeeds[] = array(
                            'feedId'=> $row['id'],
                            'feedText' => json_encode($row),
                            'updateDateTime' => date('Y-m-d H:i:s')
                        );
                        $newMainFeeds[] = json_encode($row);
                    }
                    else
                    {
                        $foundId = true;
                    }
                    break;
                case 'i':
                    if(!myInArray($row['id'],$viewIds))
                    {
                        if(isset($row['image']))
                        {
                            preg_match('/(http:|https:)\/\/.+?(\.jpg|\.png|\.gif|\.jpeg)/',urldecode($row['image']),$matches);
                            if(myIsArray($matches))
                            {
                                $fileArray = explode('/',$matches[0]);
                                $fileName= $fileArray[count($fileArray)-1];
                                if(copy($row['image'],'../mobile/socialimages/instagram/'.$fileName))
                                {
                                    $row['image'] = MOBILE_URL.'socialimages/instagram/'.$fileName;
                                }
                            }
                        }
                        $newFeeds[] = array(
                            'feedId'=> $row['id'],
                            'feedText' => json_encode($row),
                            'updateDateTime' => date('Y-m-d H:i:s')
                        );
                        $newMainFeeds[] = json_encode($row);
                    }
                    else
                    {
                        $foundId = true;
                    }
                    break;
                case 't':
                    if(!myInArray($row['id_str'],$viewIds))
                    {
                        if(isset($row['extended_entities']['media'][0]['media_url_https']))
                        {
                            preg_match('/(http:|https:)\/\/.+?(\.jpg|\.png|\.gif|\.jpeg)/',urldecode($row['extended_entities']['media'][0]['media_url_https']),$matches);
                            if(myIsArray($matches))
                            {
                                $fileArray = explode('/',$matches[0]);
                                $fileName= $fileArray[count($fileArray)-1];
                                if(copy($row['extended_entities']['media'][0]['media_url_https'],'../mobile/socialimages/twitter/'.$fileName))
                                {
                                    $row['extended_entities']['media'][0]['media_url_https'] = MOBILE_URL.'socialimages/twitter/'.$fileName;
                                }
                            }
                        }
                        $newFeeds[] = array(
                            'feedId'=> $row['id_str'],
                            'feedText' => json_encode($row),
                            'updateDateTime' => date('Y-m-d H:i:s')
                        );
                        $newMainFeeds[] = json_encode($row);
                    }
                    else
                    {
                        $foundId = true;
                    }
                    break;
            }
            if($foundId == true)
            {
                break;
            }
        }

        if($foundId && myIsArray($newFeeds))
        {
            $mergedFeeds = array_merge($newFeeds,$topFeed);
            $finalFeeds = array_slice($mergedFeeds,0,150);
            $this->cron_model->clearViewFeeds();
            $this->cron_model->insertFeedBatch($finalFeeds);

            //Fetch Main Feed
            $mainFeed = $this->cron_model->getLastMainFeed();

            $mainFeedRow = json_decode($mainFeed['feedText'],true);
            $mainFeedRow = array_merge($newMainFeeds,$mainFeedRow);

            if(count($mainFeedRow) > 150)
            {
                $feedPart1 = array_slice($mainFeedRow,0,150);
                $feedPart2 = array_slice($mainFeedRow,150,count($mainFeedRow)-1);

                $details = array(
                    'feedText' => json_encode($feedPart1),
                    'feedType' => '0',
                    'postsCount' => count($feedPart1)
                );
                $this->cron_model->updateFeedById($details,$mainFeed['id']);

                $details = array(
                    'feedText' => json_encode($feedPart2),
                    'feedType' => '0',
                    'postsCount' => count($feedPart2)
                );
                $this->cron_model->insertFeedByType($details);
            }
            else
            {
                $details = array(
                    'feedText' => json_encode($mainFeedRow),
                    'feedType' => '0',
                    'postsCount' => count($mainFeedRow)
                );
                $this->cron_model->updateFeedById($details,$mainFeed['id']);
            }

        }
    }

    function sortNjoin($arr1 = array(), $arr2 = array(), $arr3 = array())
    {
        $all = array();
        $arrs[] = $arr1;
        $arrs[] = $arr2;
        $arrs[] = $arr3;
        foreach($arrs as $arr) {
            if(is_array($arr)) {
                $all = array_merge($all, $arr);
            }
        }
        //$all = array_merge($arr1, $arr2,$arr3);

        $sortedArray = array_map(function($fb) {
            $arr = $fb;
            if(isset($arr['updated_time']))
            {
                $arr['socialType'] = 'f';
                $arr['created_at'] = $arr['updated_time'];
                unset($arr['updated_time']);
            }
            elseif (isset($arr['external_created_at']))
            {
                $arr['socialType'] = 'i';
                $arr['created_at'] = $arr['external_created_at'];
                unset($arr['external_created_at']);
            }
            elseif (isset($arr['created_at']))
            {
                $arr['socialType'] = 't';
            }
            return $arr;
        },$all);

        usort($sortedArray,
            function($a, $b) {
                $ts_a = strtotime($a['created_at']);
                $ts_b = strtotime($b['created_at']);

                return $ts_a < $ts_b;
            }
        );
        return $sortedArray;

    }

    function creditMonthlyBalance()
    {

        $data = array();
        $walletLog = array();
        //getting all staff
        $totalStaff = $this->dashboard_model->getStaffsByPeriod('monthly');

        //$mynums = array('8879103942', '9769952644');
        $smsNums = array();
        $smsBalances = array();
        $smsCredits = array();

        if($totalStaff['status'] === true)
        {
            foreach($totalStaff['staffList'] as $key => $row)
            {
                $oldBalance = $row['walletBalance'];
                $usedAmt = $row['recurringAmt'];
                $finalBal = $oldBalance + $usedAmt;
                //Equalizing wallet balance to max Rs 6000
                if($row['isCapping'] == '1')
                {
                    if($finalBal > $row['cappingAmt'])
                    {
                        $finalBal = $row['cappingAmt'];
                        $smsCredits[] = $finalBal - $oldBalance;
                    }
                    else
                    {
                        $smsCredits[] = $usedAmt;
                    }
                }

                //Update the staff record and creating a wallet log
                $data = array(
                    'walletBalance' => $finalBal
                );
                $this->dashboard_model->updateStaffRecord($row['id'],$data);
                $smsNums[] = '91'.$row['mobNum'];
                $smsBalances[] = $finalBal;

                $walletLog[] = array(
                    'staffId' => $row['id'],
                    'amount' => $usedAmt,
                    'amtAction' => '2',
                    'notes' => 'Monthly Balance Credit',
                    'loggedDT' => date('Y-m-d H:i:s'),
                    'updatedBy' => 'system'
                );
            }

            if(isset($data) && myIsMultiArray($data))
            {
                $smsLogs = array();
                //$this->dashboard_model->updateStaffBatch($data);
                $this->dashboard_model->walletLogsBatch($walletLog);

                for($i=0;$i<count($smsNums);$i++)
                {
                    // Sending SMS to each number
                    $postDetails = array(
                        'apiKey' => TEXTLOCAL_API,
                        'numbers' => implode(',', array($smsNums[$i])),
                        'sender'=> urlencode('DOLALY'),
                        'message' => rawurlencode($smsCredits[$i].' Credited, Total Available Balance: '.$smsBalances[$i])
                    );
                    $smsStatus = $this->curl_library->sendCouponSMS($postDetails);


                    //Creating a sms log (failure or success)
                    if($smsStatus['status'] == 'failure')
                    {
                        if(isset($smsStatus['warnings']))
                        {
                            $smsLogs[] = array(
                                'staffNum' => $smsNums[$i],
                                'smsStatus' => '2',
                                'smsDescription' => $smsStatus['warnings'][0]['message'],
                                'walletBal' => $smsBalances[$i],
                                'insertedDT' => date('Y-m-d H:i:s')
                            );
                        }
                        else
                        {
                            $smsLogs[] = array(
                                'staffNum' => $smsNums[$i],
                                'smsStatus' => '2',
                                'smsDescription' => $smsStatus['errors'][0]['message'],
                                'walletBal' => $smsBalances[$i],
                                'insertedDT' => date('Y-m-d H:i:s')
                            );
                        }
                    }
                    else
                    {
                        $smsLogs[] = array(
                            'staffNum' => $smsNums[$i],
                            'smsStatus' => '1',
                            'smsDescription' => null,
                            'walletBal' => $smsBalances[$i],
                            'insertedDT' => date('Y-m-d H:i:s')
                        );
                    }
                }

                $this->dashboard_model->smsLogsBatch($smsLogs);
            }
        }

    }

    function smsErrorCodes($code)
    {
        $returnVal = '';
        switch($code)
        {
            case 4:
                $returnVal = 'No recipients specified.';
                break;
            case 5:
                $returnVal = 'No message content.';
                break;
            case 6:
                $returnVal = 'Message too long.';
                break;
            case 7:
                $returnVal = 'Insufficient credits.';
                break;
            case 32:
                $returnVal = 'Invalid number format.';
                break;
            case 33:
                $returnVal = 'You have supplied too many numbers.';
                break;
            case 43:
                $returnVal = 'Invalid sender name.';
                break;
            case 44:
                $returnVal = 'No sender name specified.';
                break;
            case 51:
                $returnVal = 'No valid numbers specified.';
                break;
            case 192:
                $returnVal = 'You cannot send message at this time.';
                break;
        }
        return $returnVal;
    }

    function putOldWalletLogs()
    {
        $allStaffs = $this->dashboard_model->getAllStaffs();

        foreach($allStaffs['staffList'] as $key => $row)
        {
            if(isset($row['empId']) && $row['empId'] != '')
            {
                $walLog = $this->dashboard_model->checkWalletLog($row['id']);
                if($walLog['status'] == false)
                {
                    $details = array(
                        'staffId' => $row['id'],
                        'amount' => '1500',
                        'amtAction' => '2',
                        'notes' => 'New Staff Added',
                        'loggedDT' => $row['insertedDT'],
                        'updatedBy' => 'anshul'
                    );
                    $this->dashboard_model->updateWalletLog($details);
                }
            }
        }
    }

    function saveDashboardStats()
    {
        $locArray = $this->locations_model->getAllLocations();

        //Dashboard Data
        $startDate = date('Y-m-d', strtotime('-1 month'));
        $endDate = date('Y-m-d');
        $totalMugs = $this->mugclub_model->getAllMugsCount($locArray);
        $avgChecks = $this->dashboard_model->getAvgCheckins($startDate,$endDate,$locArray);
        $Regulars = $this->dashboard_model->getRegulars($startDate,$endDate,$locArray);
        $Irregulars = $this->dashboard_model->getIrregulars($startDate,$endDate,$locArray);
        $Lapsers = $this->dashboard_model->getLapsers($startDate,$endDate,$locArray);

        $avgCheckins = array();
        $regulars = array();
        $irregulars = array();
        $lapsers = array();

        if(isset($avgChecks))
        {
            for($i = 0;$i<count($avgChecks['checkInList']); $i++)
            {
                $mugkeys = array_keys($totalMugs);
                if($totalMugs[$mugkeys[$i]] != 0)
                {
                    $checkinKeys = array_keys($avgChecks['checkInList']);
                    $allStores = ((int)$avgChecks['checkInList'][$checkinKeys[$i]]/$totalMugs[$mugkeys[$i]]);
                    $avgCheckins[] = round($allStores,2);
                }
                else
                {
                    $avgCheckins[] = 0;
                }
            }
        }

        if(isset($Regulars))
        {
            for($i = 0;$i<count($Regulars['regularCheckins']); $i++)
            {
                $mugkeys = array_keys($totalMugs);
                if($totalMugs[$mugkeys[$i]] != 0)
                {
                    $checkinKeys = array_keys($Regulars['regularCheckins']);
                    $allStores = ((int)$Regulars['regularCheckins'][$checkinKeys[$i]]/$totalMugs[$mugkeys[$i]]);
                    $regulars[] = round($allStores,2);
                }
                else
                {
                    $regulars[] = 0;
                }
            }
        }

        if(isset($Irregulars))
        {
            for($i = 0;$i<count($Irregulars['irregularCheckins']); $i++)
            {
                $mugkeys = array_keys($totalMugs);
                if($totalMugs[$mugkeys[$i]] != 0)
                {
                    $checkinKeys = array_keys($Irregulars['irregularCheckins']);
                    $allStores = ((int)$Irregulars['irregularCheckins'][$checkinKeys[$i]]/$totalMugs[$mugkeys[$i]]);
                    $irregulars[] = round($allStores,2);

                }
                else
                {
                    $irregulars[] = 0;
                }
            }
        }

        if(isset($Lapsers))
        {
            for($i = 0;$i<count($Lapsers['lapsers']); $i++)
            {
                $mugkeys = array_keys($totalMugs);
                if($totalMugs[$mugkeys[$i]] != 0)
                {
                    $checkinKeys = array_keys($Lapsers['lapsers']);
                    $allStores = ((int)$Lapsers['lapsers'][$checkinKeys[$i]]/$totalMugs[$mugkeys[$i]]);
                    $lapsers[] = round($allStores,2);
                }
                else
                {
                    $lapsers[] = 0;
                }
            }
        }

        $details = array(
            'avgCheckins'=> implode(',',$avgCheckins),
            'regulars' => implode(',',$regulars),
            'irregulars' => implode(',',$irregulars),
            'lapsers' => implode(',',$lapsers)
        );

        $gotData = $this->dashboard_model->getDashboardRecord();
        if($gotData['status'] === false)
        {
            $this->dashboard_model->saveDashboardRecord($details);
        }
    }

    public function sendAllRefunds()
    {
        $allRefunds = $this->curl_library->allRefundsInsta();

        $colsArray = array('Event Name','Organizer Name','Quantity','Payment Id','Refund Date/Time');

        $file = fopen("./uploads/refundReport_".date('d_M_Y').'.csv',"w");
        fputcsv($file,$colsArray);
        if(isset($allRefunds) && myIsArray($allRefunds))
        {
            foreach($allRefunds['refunds'] as $key => $row)
            {
                $eveRecord = $this->dashboard_model->getEventByPaymentId($row['payment_id']);
                $date1 = date_parse($row['created_at']);
                $date_string1 = date('d M Y H:i:s', mktime($date1['hour'], $date1['minute'], $date1['second'], $date1['month'], $date1['day'], $date1['year']));
                if(isset($eveRecord) && myIsArray($eveRecord))
                {
                    $dataToWrite = array(
                        $eveRecord['eventName'],
                        $eveRecord['creatorName'],
                        $eveRecord['quantity'],
                        $row['payment_id'],
                        $date_string1
                    );
                }
                else
                {
                    $dataToWrite = array(
                        '',
                        '',
                        '',
                        $row['payment_id'],
                        $date_string1
                    );
                }
                fputcsv($file,$dataToWrite);
            }
            fclose($file);

            $content = '<html><body><p>Instamojo Refund Data!<br>PFA</p></body></html>';

            $this->sendemail_library->sendEmail('saha@brewcraftsindia.com','anshul@brewcraftsindia.com','admin@brewcraftsindia.com','ngks2009','Doolally'
                ,'admin@brewcraftsindia.com','Instamojo Refund Data | '.date('d_M_Y'),$content,array("./uploads/refundReport_".date('d_M_Y').".csv"));
            try
            {
                unlink("./uploads/refundReport_".date('d_M_Y').".csv");
            }
            catch(Exception $ex)
            {

            }
        }
    }

    public function sendWalletMissSms()
    {
        $pending = $this->dashboard_model->fetchPendingSms();

        if(isset($pending) && myIsArray($pending))
        {
            $smsLogs = array();
            foreach($pending as $key => $row)
            {
                // Sending SMS to each number
                $postDetails = array(
                    'apiKey' => TEXTLOCAL_API,
                    'numbers' => implode(',', array($row['staffNum'])),
                    'sender'=> urlencode('DOLALY'),
                    'message' => rawurlencode('Total available balance is '.$row['walletBal'].' after '.date('M jS').' credit of 1500')
                );
                $smsStatus = $this->curl_library->sendCouponSMS($postDetails);


                //Creating a sms log (failure or success)
                if($smsStatus['status'] != 'failure')
                {
                    $smsLogs[] = array(
                        'staffNum' => $row['staffNum'],
                        'smsStatus' => '1',
                        'smsDescription' => null,
                        'walletBal' => $row['walletBal'],
                        'insertedDT' => date('Y-m-d H:i:s')
                    );
                }
            }
            $this->dashboard_model->smsLogsBatch($smsLogs);
        }
    }

    public function lowSmsCredit()
    {
        $details = array(
            'apiKey' => TEXTLOCAL_API
        );
        $smsCredit = $this->curl_library->getSMSCredits($details);
        if($smsCredit['status'] == 'success')
        {
            if($smsCredit['balance']['sms'] < 100)
            {
                // Sending SMS to each number
                $postDetails = array(
                    'apiKey' => TEXTLOCAL_API,
                    'numbers' => implode(',', array('9975027683')),
                    'sender'=> urlencode('DOLALY'),
                    'message' => rawurlencode('Low SMS Credits Alert! Remaining Credits: '.$smsCredit['balance']['sms'])
                );
                $smsStatus = $this->curl_library->sendCouponSMS($postDetails);
            }
        }
    }

    public function sendAllBirthdayMails()
    {
        $this->load->model('mailers_model');
        $this->load->model('users_model');
        $birthMails = $this->mugclub_model->getBirthdayMugsList();
        $mailResult = $this->mailers_model->getAllTemplatesByType(BIRTHDAY_MAIL);

        if($birthMails['status'] == true)
        {
            foreach($birthMails['expiryMugList'] as $key => $row)
            {
                $mailRecord = $this->users_model->searchUserByLoc($row['homeBase']);
                $mugInfo = $this->mugclub_model->getMugDataForMailById($row['mugId']);

                $newDate =array("membershipEnd"=> date('Y-m-d', strtotime($mugInfo['mugList'][0]['membershipEnd'].' +3 month')));
                $this->mugclub_model->extendMemberShip($row['mugId'],$newDate);
                $mugInfo['mugList'][0]['membershipEnd'] = $newDate['membershipEnd'];
                $newSubject = $this->replaceMugTags($mailResult['mailData'][0]['mailSubject'],$mugInfo,$mailRecord['userData']['firstName']);
                $newBody = $this->replaceMugTags($mailResult['mailData'][0]['mailBody'],$mugInfo,$mailRecord['userData']['firstName']);


                $fromName  = 'Doolally';
                if(isset($mailRecord['userData']['firstName']))
                {
                    $fromName = trim(ucfirst($mailRecord['userData']['firstName']));
                }
                $fromEmail = DEFAULT_COMM_EMAIL;
                $fromPass = DEFAULT_COMM_PASS;
                $replyTo = $fromEmail;

                if(isset($mailRecord['userData']['emailId']))
                {
                    $replyTo = $mailRecord['userData']['emailId'];
                }

                $cc        = implode(',',$this->config->item('ccList'));
                $extraCc = getExtraCCEmail($fromEmail);
                if(isStringSet($extraCc))
                {
                    $cc = $cc.','.$extraCc;
                }

                $this->sendemail_library->sendEmail($mugInfo['mugList'][0]['emailId'],$cc,$fromEmail, $fromPass,$fromName,$replyTo,$newSubject,$newBody);
                $this->mailers_model->setMailSend($row['mugId'],BIRTHDAY_MAIL);
            }
        }
    }

    function replaceMugTags($tagStr,$mugInfo,$senderName)
    {

        $tagStr = str_replace('[sendername]',trim(ucfirst($senderName)),$tagStr);
        foreach($mugInfo['mugList'][0] as $key => $row)
        {
            switch($key)
            {
                case 'mugId':
                    $tagStr = str_replace('[mugno]',trim($row),$tagStr);
                    break;
                case 'firstName':
                    $tagStr = str_replace('[firstname]',trim(ucfirst($row)),$tagStr);
                    break;
                case 'lastName':
                    $tagStr = str_replace('[lastname]',trim(ucfirst($row)),$tagStr);
                    break;
                case 'birthDate':
                    $d = date_create($row);
                    $tagStr = str_replace('[birthdate]',date_format($d,DATE_MAIL_FORMAT_UI),$tagStr);
                    break;
                case 'mobileNo':
                    $tagStr = str_replace('[mobno]',trim($row),$tagStr);
                    break;
                case 'membershipEnd':
                    $d = date_create($row);
                    $tagStr = str_replace('[expirydate]',date_format($d,DATE_MAIL_FORMAT_UI),$tagStr);
                    break;
            }
        }
        return $tagStr;
    }

    public function regularizeOfficeWallets()
    {
        $officeWalls = $this->dashboard_model->getAllOfficeWallets();

        if(isset($officeWalls) && myIsArray($officeWalls))
        {
            $offWallBatch = array();
            foreach($officeWalls as $key => $row)
            {
                $oldBal = (double)$row['walletBalance'];
                $remainBal = (double)OFFICE_WALLET_CAP - $oldBal;
                $details = array(
                    'walletBalance' => OFFICE_WALLET_CAP
                );
                $this->dashboard_model->updateStaffRecord($row['id'],$details);
                if($remainBal > 0)
                {
                    $offWallBatch[] = array(
                        'amtCredit' => $remainBal,
                        'empId' => $row['empId'],
                        'staffStatus' => '2',
                        'updateDT' => date('Y-m-d H:i:s')
                    );
                }
            }

            $this->dashboard_model->offWallBatch($offWallBatch);
        }
    }

    public function sendWalletUseReport()
    {
        $colKeys = array('Name','Employee Id','Mobile #','Employee Type','Bill #', 'Bill Location', 'Logged Date/Time','Amount');
        $colKeys1 = array('Name','Mobile #','Employee Id','Amount Used');

        $allFeedbacks = $this->cron_model->getAllActiveEmps();


        if( isset($allFeedbacks) && myIsArray($allFeedbacks))
        {
            $file1 = fopen("./uploads/monthly_wallet_detail_transactions_".date('d_M_Y').".csv","w");
            $file2 = fopen("./uploads/monthly_wallet_usage_".date('d_M_Y').".csv","w");
            $otherRow = true;
            $firstRow = true;
            foreach($allFeedbacks as $key => $row)
            {
                if($otherRow)
                {
                    $otherRow = false;
                    $otherFileRow = $colKeys1;
                    fputcsv($file2,$otherFileRow);
                }
                if($firstRow)
                {
                    $firstRow = false;
                    $textToWrite = $colKeys;
                    fputcsv($file1,$textToWrite);
                }
                $userType = '';
                if($row['userType'] == WALLET_RESTAURANT)
                {
                    $userType = 'Restaurant Employee';
                }
                elseif($row['userType'] == WALLET_OFFICE)
                {
                    $userType = 'Office Employee';
                }

                $startDate = date('Y-m-d', strtotime('-1 month'));
                $endDate = date('Y-m-d');
                $walletTrans = $this->cron_model->getWalletTrans($row['id'],$startDate,$endDate);

                if($walletTrans['status'] === true)
                {
                    $otherArr = array(
                        $row['firstName'].' '.$row['middleName'].' '.$row['lastName'],
                        $row['mobNum'],
                        $row['empId'],
                        array_sum(array_map(function($foo){return $foo['amount'];}, $walletTrans['walletDetails']))
                    );
                    fputcsv($file2,$otherArr);
                    foreach($walletTrans['walletDetails'] as $wallKey => $wallRow)
                    {

                        $d = date_create($wallRow['loggedDT']);
                        $ehRow = array(
                            $row['firstName'].' '.$row['middleName'].' '.$row['lastName'],
                            $row['empId'],
                            $row['mobNum'],
                            $userType,
                            $wallRow['billNum'],
                            $wallRow['locName'],
                            date_format($d,DATE_TIME_FORMAT_UI),
                            $wallRow['amount']
                        );
                        $textToWrite = $ehRow;
                        fputcsv($file1,$textToWrite);
                    }
                }

            }
            fclose($file1);
            $content = '<html><body><p>Monthly Employee Expenditure Report<br>PFA</p></body></html>';

            $this->sendemail_library->sendEmail(array('saha@brewcraftsindia.com','savio@brewcraftsindia.com','amit@brewcraftsindia.com','pranjal.rathi@rubycapital.net'),'anshul@brewcraftsindia.com','admin@brewcraftsindia.com','ngks2009','Doolally'
                ,'admin@brewcraftsindia.com','Employee Monthly Report '.date('d_M_Y'),$content,array("./uploads/monthly_wallet_detail_transactions_".date('d_M_Y').".csv",
                    "./uploads/monthly_wallet_usage_".date('d_M_Y').".csv"));
            try
            {
                unlink("./uploads/monthly_wallet_detail_transactions_".date('d_M_Y').".csv");
                unlink("./uploads/monthly_wallet_usage_".date('d_M_Y').".csv");
            }
            catch(Exception $ex)
            {

            }
        }
    }

}
