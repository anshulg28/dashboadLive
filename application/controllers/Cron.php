<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Cron
 * @property Cron_model $cron_model
 * @property Dashboard_Model $dashboard_model
 * @property Locations_Model $locations_model
 * @property Mugclub_Model $mugclub_model
 * @property Maintenance_Model $maintenance_model
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
        $this->load->model('maintenance_model');
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
            //$fbData = $this->cron_model->checkFeedByType("1");

            $fbPost = array(
                'feedText' => json_encode($facebook),
                'feedType' => '1'
            );
            $this->cron_model->updateFeedByType($fbPost,"1");
            /*if($fbData['status'] === true)
            {
                $this->cron_model->updateFeedByType($fbPost,"1");
            }
            else
            {
                $this->cron_model->insertFeedByType($fbPost);
            }*/
        }

        if(myIsArray($twitter))
        {
            //twitter
            //$fbData = $this->cron_model->checkFeedByType("2");

            $fbPost = array(
                'feedText' => json_encode($twitter),
                'feedType' => '2'
            );
            $this->cron_model->updateFeedByType($fbPost, "2");
            /*if($fbData['status'] === true)
            {
                $this->cron_model->updateFeedByType($fbPost, "2");
            }
            else
            {
                $this->cron_model->insertFeedByType($fbPost);
            }*/
        }

        if(myIsArray($instagram))
        {
            //Instagram
            //$fbData = $this->cron_model->checkFeedByType("3");

            $fbPost = array(
                'feedText' => json_encode($instagram),
                'feedType' => '3'
            );
            $this->cron_model->updateFeedByType($fbPost, "3");
            /*if($fbData['status'] === true)
            {
                $this->cron_model->updateFeedByType($fbPost, "3");
            }
            else
            {
                $this->cron_model->insertFeedByType($fbPost);
            }*/
        }

        $this->storeAllFeeds();
    }
    public function getTwitterFeeds()
    {
        $twitterFeeds = '';
        $this->twitter->tmhOAuth->reconfigure();
        $bearer = $this->twitter->tmhOAuth->bearer_token_credentials();
        $params = array(
            'grant_type' => 'client_credentials',
        );

        $code = $this->twitter->tmhOAuth->request(
            'POST',
            $this->twitter->tmhOAuth->url('/oauth2/token', null),
            $params,
            false,
            false,
            array(
                'Authorization' => "Basic ${bearer}"
            )
        );
        if ($code == 200)
        {
            $data = json_decode($this->twitter->tmhOAuth->response['response']);
            if (isset($data->token_type) && strcasecmp($data->token_type, 'bearer') === 0)
            {
                $new_bearer = $data->access_token;
            }
        }
        else
        {
            echo $code;
        }
        $this->twitter->tmhOAuth->reconfigure(array(
           'bearer' => $new_bearer
        ));
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
        $rsp = $this->twitter->tmhOAuth->apponly_request(array(
            'method'=> 'GET',
            'url' => $this->twitter->tmhOAuth->url('1.1/search/tweets'),
            'params' => $parmas
        ));

        //$responseCode = $this->twitter->tmhOAuth->request('GET','https://api.twitter.com/1.1/statuses/user_timeline.json',$parmas);
        //$responseCode = $this->twitter->tmhOAuth->request('GET','https://api.twitter.com/1.1/search/tweets.json',$parmas);
        if($rsp == 200)
        {
            $twitterFeeds = $this->twitter->tmhOAuth->response['response'];
            $oldrsp = $this->twitter->tmhOAuth->apponly_request(array(
                'method'=> 'GET',
                'url' => $this->twitter->tmhOAuth->url('1.1/statuses/user_timeline.json'),
                'params' => $oldparmas
            ));
            //$oldresponseCode = $this->twitter->tmhOAuth->request('GET','https://api.twitter.com/1.1/statuses/user_timeline.json',$oldparmas);

            if($oldrsp == 200)
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
            'fields' => 'message,permalink_url,id,from,name,full_picture,source,updated_time'
        );
        $fbFeeds[] = $this->curl_library->getFacebookPosts('godoolallyandheri',$params);
        $fbFeeds[] = $this->curl_library->getFacebookPosts('godoolallybandra',$params);
        //kemps
        $fbFeeds[] = $this->curl_library->getFacebookPosts('godoolallykemps', $params);
        $fbFeeds[] = $this->curl_library->getFacebookPosts('godoolallyColaba', $params);
        $fbFeeds[] = $this->curl_library->getFacebookPosts('godoolallykhar', $params);
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
        if($total['vashi'] != 0)
        {
            $data[] = (int)(($promo['vashi']/$total['vashi'])*100 - ($de['vashi']/$total['vashi'])*100);
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
            $this->splitAndStoreFeeds($allFeeds);
        }

        //$this->firstTimeFunc($allFeeds);

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
        $lastMainFeed = $this->cron_model->getLastMainFeed();

        $viewIds= array();
        foreach($topFeed as $key => $row)
        {
            $viewIds[] = $row['feedId'];
        }
        if(isset($lastMainFeed) && myIsArray($lastMainFeed))
        {
            $moreIds = json_decode($lastMainFeed['feedText'],TRUE);
            foreach($moreIds as $key => $row)
            {
                if(gettype($row) == 'string')
                {
                    $row = json_decode($row,TRUE);
                }
                switch($row['socialType'])
                {
                    case 'f':
                        $viewIds[] = $row['id'];
                        break;
                    case 'i':
                        $viewIds[] = $row['id'];
                        break;
                    case 't':
                        $viewIds[] = $row['id_str'];
                        break;
                }
            }
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
                        if(isset($row['full_picture']))
                        {
                            preg_match('/(=http:|=https:|http:|https:)\/\/.+?(\.jpg|\.png|\.gif|\.jpeg)/',urldecode($row['full_picture']),$matches);
                            if(myIsArray($matches))
                            {
                                $fileArray = explode('/',$matches[0]);
                                $fileName= $fileArray[count($fileArray)-1];
                                if(copy($row['full_picture'],'../mobile/socialimages/facebook/'.$fileName))
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

        if(myIsArray($newFeeds))
        {
            //Firstly append all new feeds to temp view table
            $this->cron_model->insertTempFeedBatch($newFeeds);

            //fetch all temp view feeds and check for following conditions
            $tempFeeds = $this->cron_model->getTempFeedView();
            if(isset($tempFeeds) && myIsArray($tempFeeds))
            {

                usort($tempFeeds,
                    function($a, $b) {
                        $aNew = $a['feedText'];
                        $bNew = $b['feedText'];
                        if(gettype($a['feedText']) == 'string')
                        {
                            $aNew = json_decode($a['feedText'],TRUE);
                        }
                        if(gettype($b['feedText']) == 'string')
                        {
                            $bNew = json_decode($b['feedText'],TRUE);
                        }
                        $ts_a = strtotime($aNew['created_at']);
                        $ts_b = strtotime($bNew['created_at']);

                        return $ts_a > $ts_b;
                    }
                );

                if(count($tempFeeds) > 150)
                {
                    //Dividing the temp view feeds
                    $fixFeeds = array_slice($tempFeeds,0,150);
                    $tempRemaining = array_slice($tempFeeds,150,(count($tempFeeds)-1));

                    $finalFixFeeds = array();
                    foreach($fixFeeds as $key => $row)
                    {
                        $finalFixFeeds[] = $row['feedText'];
                    }
                    //storing 150 chunk to data table
                    $details = array(
                        'feedText' => json_encode($finalFixFeeds),
                        'feedType' => '0',
                        'postsCount' => count($finalFixFeeds)
                    );
                    $this->cron_model->insertFeedByType($details);

                    //flashing the temp view and main view
                    $this->cron_model->clearTempViewFeeds();
                    $this->cron_model->clearViewFeeds();

                    //storing the remaining feeds to temp view and view table
                    $this->cron_model->insertTempFeedBatch($tempRemaining);
                    $this->cron_model->insertFeedBatch($tempRemaining);
                }
                elseif(count($tempFeeds) > 10)
                {
                    //Flash main view And replace with all temp view feeds
                    $this->cron_model->clearViewFeeds();
                    $this->cron_model->insertFeedBatch($tempFeeds);
                }
                else
                {
                    //Merge main view feeds and temp view feeds and upload to main view again
                    $newMainViewFeeds = array_merge($newFeeds,$topFeed);
                    $this->cron_model->clearViewFeeds();
                    $this->cron_model->insertFeedBatch($newMainViewFeeds);
                }
            }


            //$mergedFeeds = array_merge($newFeeds,$topFeed);
            //$finalFeeds = array_slice($mergedFeeds,0,150);
            //$this->cron_model->clearViewFeeds();
            //$this->cron_model->insertFeedBatch($finalFeeds);

            //Fetch Main Feed
           /* $mainFeed = $this->cron_model->getLastMainFeed();

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
            }*/

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

    public function fetchAllFeeds()
    {
        $allFeeds = $this->cron_model->getAllErrorFeeds();

        if(isset($allFeeds) && myIsArray($allFeeds))
        {
            $finalFeeds = array();
            foreach($allFeeds as $key => $row)
            {
                $finalFeeds[] = json_decode($row['feedText'],TRUE);
            }

            //var_dump($finalFeeds);
            $combinedFeeds = array();
            foreach($finalFeeds as $key => $row)
            {
                foreach($row as $subRow => $subKey)
                {
                    $subKey = json_encode($subKey);
                    $combinedFeeds[] = json_decode($subKey,TRUE);
                }
            }

            /*for($i=0;$i<count($combinedFeeds);$i++)
            {
                var_dump(gettype($combinedFeeds[$i]));
                echo '<br>';
            }*/
            //echo json_encode($combinedFeeds);

            usort($combinedFeeds,
                function($a, $b) {
                    $aNew = $a;
                    $bNew = $b;
                    if(gettype($a) == 'string')
                    {
                        $aNew = json_decode($a,TRUE);
                    }
                    if(gettype($b) == 'string')
                    {
                        $bNew = json_decode($b,TRUE);
                    }
                    $ts_a = strtotime($aNew['created_at']);
                    $ts_b = strtotime($bNew['created_at']);

                    return $ts_a > $ts_b;
                }
            );

            $dupFeeds = array();
            foreach($combinedFeeds as $key => $row)
            {
                if(gettype($row) == 'string')
                {
                    $row = json_decode($row,TRUE);
                }
                switch($row['socialType'])
                {
                    case 't':
                        if(in_array($row['id_str'],$dupFeeds))
                        {
                            unset($combinedFeeds[$key]);
                        }
                        break;
                    case 'i':
                        if(in_array($row['id'],$dupFeeds))
                        {
                            unset($combinedFeeds[$key]);
                        }
                        break;
                    case 'f':
                        if(in_array($row['id'],$dupFeeds))
                        {
                            unset($combinedFeeds[$key]);
                        }
                        break;
                }
                switch($row['socialType'])
                {
                    case 't':
                        $dupFeeds[] = $row['id_str'];
                        break;
                    case 'i':
                        $dupFeeds[] = $row['id'];
                        break;
                    case 'f':
                        $dupFeeds[] = $row['id'];
                        break;
                }
            }

            $fixedFeeds = array_values($combinedFeeds);

            $upDates = array("2017-01-11 15:24:13","2017-01-15 15:14:15","2017-01-15 16:55:12","2017-01-20 14:15:11",
                "2017-01-20 15:10:11","2017-01-27 14:15:10","2017-01-29 10:55:11","2017-02-03 14:15:10","2017-02-05 12:10:10",
                "2017-02-10 14:20:09","2017-02-14 21:55:10","2017-02-17 14:15:11","2017-02-17 15:10:10","2017-02-24 14:10:09",
                "2017-02-24 19:05:09","2017-03-03 13:40:10","2017-03-03 14:30:11","2017-03-10 13:55:10","2017-03-10 14:30:10",
                "2017-03-17 14:10:09","2017-03-17 14:35:10","2017-03-24 14:05:10","2017-03-24 14:25:09","2017-03-29 21:10:10",
                "2017-03-31 14:25:09","2017-04-03 15:55:11","2017-04-07 14:20:09","2017-04-08 22:20:08","2017-04-14 14:15:09",
                "2017-04-14 18:40:09","2017-04-21 14:15:09","2017-04-21 15:45:09","2017-04-28 14:15:09","2017-04-29 19:35:12",
                "2017-05-05 14:15:10","2017-05-06 20:20:10","2017-05-12 14:15:09","2017-05-15 01:40:09","2017-05-19 14:15:12",
                "2017-05-20 06:15:14","2017-05-26 14:10:12","2017-05-28 00:40:13","2017-06-02 14:10:13","2017-06-02 15:15:13",
                "2017-06-09 14:10:14","2017-06-09 15:15:14","2017-06-16 14:05:14","2017-06-16 14:30:15","2017-06-23 13:05:12",
                "2017-06-23 14:30:13","2017-06-30 13:05:13","2017-06-30 14:30:14","2017-07-07 14:05:14","2017-07-07 15:05:14",
                "2017-07-14 14:10:12","2017-07-14 15:00:12","2017-07-21 14:05:13","2017-07-21 14:50:13","2017-07-28 14:15:14",
                "2017-07-29 15:40:13","2017-08-04 14:15:13","2017-08-04 15:25:13","2017-08-11 14:20:23","2017-08-14 15:05:18",
                "2017-08-18 14:15:14","2017-08-19 21:30:14","2017-08-25 14:15:16","2017-08-26 21:10:15","2017-09-01 14:20:14",
                "2017-09-02 18:10:13","2017-09-08 14:25:16","2017-09-13 23:30:17","2017-09-15 14:30:13","2017-09-20 15:05:16",
                "2017-09-22 14:45:12","2017-09-29 14:10:13","2017-09-30 22:10:20","2017-10-06 14:20:12","2017-10-08 14:35:13",
                "2017-10-13 14:20:13","2017-10-15 04:15:14","2017-10-20 14:20:11","2017-10-22 18:20:10","2017-10-27 14:20:32",
                "2017-11-01 15:00:19","2017-11-03 14:31:42","2017-11-09 20:35:16");
            $chunkArr = array_chunk($fixedFeeds, 150);
            $saveArr = array();
            for($i=0;$i<count($chunkArr);$i++)
            {
                $saveArr[] = array(
                    'feedText' => json_encode($chunkArr[$i]),
                    'feedType' => 0,
                    'postsCount' => count($chunkArr[$i]),
                    'updateDateTime' => $upDates[$i]
                );
            }
            $this->cron_model->insertNewFeedsBatch($saveArr);
            echo 'done';
        }
    }

    public function fixDupFeeds()
    {
        $lastFeeds = $this->cron_model->getMoreLatestFeeds(0);
        $allTemps = $this->cron_model->getTempFeedView();

        $lastErr = json_decode($lastFeeds['feedText'],TRUE);
        $finalFeeds = $lastErr;
        foreach($allTemps as $key => $row)
        {
            $finalFeeds[] = $row['feedText'];
        }
        //$finalFeeds = array_merge($lastErr,$allTemps);

        $unique = array_map("unserialize", array_unique(array_map("serialize", $finalFeeds)));

        $newFixFeeds = array();
        foreach($unique as $key => $row)
        {
            if(gettype($row) == 'string')
            {
                $row = json_decode($row,TRUE);
            }
            $newFixFeeds[] = $row;
        }
        //Dividing the temp view feeds
        $fixFeeds = array_slice($newFixFeeds,0,150);
        $tempRemaining = array_slice($newFixFeeds,150,(count($newFixFeeds)-1));

        //storing 150 chunk to data table
        $details = array(
            'feedText' => json_encode($fixFeeds),
            'feedType' => '0',
            'postsCount' => count($fixFeeds)
        );
        $this->cron_model->insertFeedByType($details);

        //flashing the temp view and main view
        $this->cron_model->clearTempViewFeeds();
        $this->cron_model->clearViewFeeds();
        foreach($tempRemaining as $key => $row)
        {
            $viewIds = '';
            switch($row['socialType'])
            {
                case 'f':
                    $viewIds = $row['id'];
                    break;
                case 'i':
                    $viewIds = $row['id'];
                    break;
                case 't':
                    $viewIds = $row['id_str'];
                    break;
            }
            $viewBatch[] = array(
                'feedId' => $viewIds,
                'feedText' => json_encode($row),
                'updateDateTime' => date('Y-m-d H:i:s')
            );
        }
        $this->cron_model->insertFeedBatch($viewBatch);
        $this->cron_model->insertTempFeedBatch($viewBatch);

    }

    public function transferToViewFeed()
    {
        $lastMainFeed = $this->cron_model->getLastMainFeed();

        if(isset($lastMainFeed) && myIsArray($lastMainFeed))
        {
            $details = array();
            $moreIds = json_decode($lastMainFeed['feedText'],TRUE);
            foreach($moreIds as $key => $row)
            {
                if(gettype($row) == 'string')
                {
                    $row = json_decode($row,TRUE);
                }
                $viewId = '';
                switch($row['socialType'])
                {
                    case 'f':
                        $viewId = $row['id'];
                        break;
                    case 'i':
                        $viewId = $row['id'];
                        break;
                    case 't':
                        $viewId = $row['id_str'];
                        break;
                }
                $details[] = array(
                    'feedId' => $viewId,
                    'feedText' => json_encode($row),
                    'updateDateTime' => date('Y-m-d H:i:s')
                );
            }
            $this->cron_model->insertFeedBatch($details);
            $this->cron_model->insertTempFeedBatch($details);
        }
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

        //Monthly Office Wallet Reset
        $officeWalls = $this->dashboard_model->getAllOfficeWallets();

        if(isset($officeWalls) && myIsArray($officeWalls))
        {
            $offWallBatch = array();
            $offWalletLog = array();
            foreach($officeWalls as $key => $row)
            {
                $oldBal = (double)$row['walletBalance'];
                $updatedBal = OFFICE_WALLET_CAP;
                if($oldBal < 0)
                {
                    $updatedBal = (double)OFFICE_WALLET_CAP + $oldBal;
                }

                $details = array(
                    'walletBalance' => $updatedBal
                );
                $this->dashboard_model->updateStaffRecord($row['id'],$details);
                if($updatedBal > 0)
                {
                    $offWallBatch[] = array(
                        'amtCredit' => $updatedBal,
                        'empId' => $row['empId'],
                        'staffStatus' => '2',
                        'updateDT' => date('Y-m-d H:i:s')
                    );
                }
                $offWalletLog[] = array(
                    'staffId' => $row['id'],
                    'amount' => $updatedBal,
                    'amtAction' => '2',
                    'notes' => 'Monthly Balance Credit',
                    'loggedDT' => date('Y-m-d H:i:s'),
                    'updatedBy' => 'system'
                );
            }

            $this->dashboard_model->offWallBatch($offWallBatch);
            $this->dashboard_model->walletLogsBatch($offWalletLog);
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
        $birthMails = $this->mugclub_model->getCurrentBirthdayMails();
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
                $cc .= ','.$replyTo;
                /*$extraCc = getExtraCCEmail($fromEmail);
                if(isStringSet($extraCc))
                {
                    $cc = $cc.','.$extraCc;
                }*/

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
        //Monthly Office Wallet Reset
        $officeWalls = $this->dashboard_model->getAllOfficeWallets();

        if(isset($officeWalls) && myIsArray($officeWalls))
        {
            $offWallBatch = array();
            $offWalletLog = array();
            foreach($officeWalls as $key => $row)
            {
                $oldBal = (double)$row['walletBalance'];
                $updatedBal = OFFICE_WALLET_CAP;
                if($oldBal < 0)
                {
                    $updatedBal = (double)OFFICE_WALLET_CAP + $oldBal;
                }

                $details = array(
                    'walletBalance' => $updatedBal
                );
                $this->dashboard_model->updateStaffRecord($row['id'],$details);
                if($updatedBal > 0)
                {
                    $offWallBatch[] = array(
                        'amtCredit' => $updatedBal,
                        'empId' => $row['empId'],
                        'staffStatus' => '2',
                        'updateDT' => date('Y-m-d H:i:s')
                    );
                }
                $offWalletLog[] = array(
                    'staffId' => $row['id'],
                    'amount' => $updatedBal,
                    'amtAction' => '2',
                    'notes' => 'Monthly Balance Credit',
                    'loggedDT' => date('Y-m-d H:i:s'),
                    'updatedBy' => 'system'
                );
            }

            $this->dashboard_model->offWallBatch($offWallBatch);
            $this->dashboard_model->walletLogsBatch($offWalletLog);
        }

    }

    public function sendWalletUseReport()
    {
        $colKeys = array('Name','Employee Id','Mobile #','Employee Type','Bill #', 'Bill Location', 'Logged Date/Time','Amount');
        $colKeys1 = array('Name','Mobile #','Employee Id','Amount Used');

        $allFeedbacks = $this->cron_model->getAllActiveEmps();


        if( isset($allFeedbacks) && myIsArray($allFeedbacks))
        {
            $file1 = fopen("./uploads/monthly_wallet_detail_transactions_".date('m_Y', strtotime('-1 month')).".csv","w");
            $file2 = fopen("./uploads/monthly_wallet_usage_".date('m_Y', strtotime('-1 month')).".csv","w");
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

            $this->sendemail_library->sendEmail(array('purva@brewcraftsindia.com','hasti@brewcraftsindia.com','saha@brewcraftsindia.com','savio@brewcraftsindia.com','amit@brewcraftsindia.com','taronish@brewcraftsindia.com','finance@brewcraftsindia.com','jayant@brewcraftsindia.com'),'anshul@brewcraftsindia.com','admin@brewcraftsindia.com','ngks2009','Doolally'
                ,'admin@brewcraftsindia.com','Staff wallet usage report '.date('m_Y', strtotime('-1 month')),$content,array("./uploads/monthly_wallet_detail_transactions_".date('m_Y', strtotime('-1 month')).".csv",
                    "./uploads/monthly_wallet_usage_".date('m_Y', strtotime('-1 month')).".csv"));
            try
            {
                unlink("./uploads/monthly_wallet_detail_transactions_".date('m_Y', strtotime('-1 month')).".csv");
                unlink("./uploads/monthly_wallet_usage_".date('m_Y', strtotime('-1 month')).".csv");
            }
            catch(Exception $ex)
            {

            }
        }

    }
    public function checkPostponeJobs()
    {
        $postJobs = $this->cron_model->getPostJobs();

        if(isset($postJobs) && myIsArray($postJobs))
        {
            foreach($postJobs as $key => $row)
            {
                if($row['postpondDate'] == date('Y-m-d'))
                {
                    $details = array(
                        'status' => LOG_STATUS_OPEN
                    );
                    $this->maintenance_model->updateComplaint($details,$row['complaintId']);
                }
            }
        }

        //If more than 48 hours
        $openJobs = $this->maintenance_model->getOnlyOpenJobs();
        if(isset($openJobs) && myIsArray($openJobs) && isset($openJobs[0]['complaintId']) && $openJobs[0]['complaintId'] != '')
        {
            $subject = "Jobs Pending Action";
            $content = '<html><body><br><table border="2"><tr><th>Job #</th><th>Problem</th><th>logged By</th><th>logged date/time</th></tr><tbody>';
            $goneIn = false;
            foreach($openJobs as $key => $row)
            {
                if(isset($row['complaintId']) && isStringSet($row['complaintId']))
                {
                    $oldTime = strtotime($row['lastUpdateDT']) + (2 * 24 * 60 * 60);
                    if($oldTime <= strtotime(date('Y-m-d H:i:s')))
                    {
                        $goneIn = true;
                        $content .= '<tr>';
                        $content .= '<td>Job #'.$row['complaintId'].'-'.$row['locName'].'</td>';
                        $content .= '<td>'.$row['problemDescription'].'</td>';
                        $content .= '<td>'.$row['loggedUser'].'</td>';
                        $d = date_create($row['loggedDT']);
                        $content .= '<td>'.date_format($d,DATE_TIME_FORMAT_UI).'</td>';
                        $content .= '</tr>';
                    }
                }
            }
            if(!$goneIn)
            {
                $content = 'No Jobs Pending';
            }
            else
            {
                $content .= '</tbody></table>';
            }
            $this->sendemail_library->sendEmail(array('mandar@brewcraftsindia.com','taronish@brewcraftsindia.com','anil.jadhav@brewcraftsindia.com'),'saha@brewcraftsindia.com,anshul@brewcraftsindia.com','admin@brewcraftsindia.com','ngks2009','Doolally'
                ,'admin@brewcraftsindia.com',$subject,$content,array());
        }

        //Closed Jobs
        $closeJobs = $this->maintenance_model->getOnlyClosedJobs();
        if(isset($closeJobs) && myIsArray($closeJobs))
        {
            $subject = 'List of closed jobs for date '.date('Y_m_d',strtotime('-1 day'));
            $content = '<html><body><p>';
            foreach($closeJobs as $key => $row)
            {
                $content .= 'Job #'.$row['complaintId'].'-'.$row['locName'].'<br>';
            }
            $content .= '</p></body></html>';
            $this->sendemail_library->sendEmail(array('mandar@brewcraftsindia.com','taronish@brewcraftsindia.com'),'saha@brewcraftsindia.com,anshul@brewcraftsindia.com','admin@brewcraftsindia.com','ngks2009','Doolally'
                ,'admin@brewcraftsindia.com',$subject,$content,array());
        }

        //Jobs Pending for budget approval
        $openJobs = $this->maintenance_model->getOnlyBudgetJobs();
        if(isset($openJobs) && myIsArray($openJobs) && isset($openJobs[0]['complaintId']) && $openJobs[0]['complaintId'] != '')
        {
            $subject = "Jobs Pending Budget Approval";
            $content = '<html><body><br><table border="2"><tr><th>Job #</th><th>Problem</th><th>logged By</th><th>Approx Cost</th><th>logged date/time</th></tr><tbody>';
            $goneIn = false;
            foreach($openJobs as $key => $row)
            {
                if(isset($row['complaintId']) && isStringSet($row['complaintId']))
                {
                    $oldTime = strtotime($row['lastUpdateDT']) + (2 * 24 * 60 * 60);
                    if($oldTime <= strtotime(date('Y-m-d H:i:s')))
                    {
                        $goneIn = true;
                        $content .= '<tr>';
                        $content .= '<td>Job #'.$row['complaintId'].'-'.$row['locName'].'</td>';
                        $content .= '<td>'.$row['problemDescription'].'</td>';
                        $content .= '<td>'.$row['loggedUser'].'</td>';
                        $content .= '<td>'.$row['approxCost'].'</td>';
                        $d = date_create($row['loggedDT']);
                        $content .= '<td>'.date_format($d,DATE_TIME_FORMAT_UI).'</td>';
                        $content .= '</tr>';
                    }
                }
            }
            if(!$goneIn)
            {
                $content = 'No Jobs Pending for budget approval';
            }
            else
            {
                $content .= '</tbody></table>';
            }
            $this->sendemail_library->sendEmail(array('mandar@brewcraftsindia.com','taronish@brewcraftsindia.com','anil.jadhav@brewcraftsindia.com','suketu@brewcraftsindia.com'),'saha@brewcraftsindia.com,anshul@brewcraftsindia.com','admin@brewcraftsindia.com','ngks2009','Doolally'
                ,'admin@brewcraftsindia.com',$subject,$content,array());
        }
    }

    public function sendMusicReqReport()
    {
        $colsArray = array('Song Name','Location','EmailId','Date/Time');

        $file = fopen("./uploads/musicReqReport_".date('d_M_Y',strtotime('-1 day')).'.csv',"w");
        fputcsv($file,$colsArray);
        $date = date('Y-m-d',strtotime('-1 day'));
        $musicData = $this->cron_model->getMusicReqData($date);
        if(isset($musicData) && myIsArray($musicData) && isset($musicData[0]['id']))
        {
            $subject = "Daily Jukebox Music Request Report for ".date('Y_m_d',strtotime('-1 day'));
            $content = '<html><body><p>Daily Jukebox Music Request Report<br>PFA</p></body></html>';
            foreach($musicData as $key => $row)
            {
                $d = date_create($row['insertedDateTime']);
                $fData = array(
                    $row['songName'],
                    $row['locName'],
                    $row['userEmail'],
                    date_format($d,DATE_TIME_FORMAT_UI)
                );
                fputcsv($file,$fData);
            }
            fclose($file);
            $this->sendemail_library->sendEmail(array('saha@brewcraftsindia.com'),'anshul@brewcraftsindia.com','admin@brewcraftsindia.com','ngks2009','Doolally'
                ,'admin@brewcraftsindia.com',$subject,$content,array("./uploads/musicReqReport_".date('d_M_Y',strtotime('-1 day')).".csv"));
            try
            {
                unlink("./uploads/musicReqReport_".date('d_M_Y',strtotime('-1 day')).".csv");
            }
            catch(Exception $ex)
            {

            }
        }
        else
        {
            $subject = "No Music Request Report for ".date('Y_m_d',strtotime('-1 day'));
            $content = '<html><body><p>No Music Request Report</p>';
            $this->sendemail_library->sendEmail(array('saha@brewcraftsindia.com'),'anshul@brewcraftsindia.com','admin@brewcraftsindia.com','ngks2009','Doolally'
                ,'admin@brewcraftsindia.com',$subject,$content,array());
        }
    }

    public function resetGuestWallets()
    {
        $g2Users = $this->cron_model->getAllGuest2List();
        if(isset($g2Users) && myIsArray($g2Users))
        {
            $walRec = array();
            foreach($g2Users as $key => $row)
            {
                $walRec[] = array(
                    'staffId' => $row['id'],
                    'amount' => $row['walletBalance'],
                    'amtAction' => '1',
                    'notes' => 'Wallet Balance Expired',
                    'loggedDT' => date('Y-m-d H:i:s'),
                    'updatedBy' => 'system'
                );
                $details = array(
                    'walletBalance' => 0,
                    'expiryDateTime' => null
                );
                $this->dashboard_model->updateStaffRecord($row['id'],$details);
            }
            $this->dashboard_model->walletLogsBatch($walRec);
        }
    }

    public function resetTapAmounts()
    {
        $tapsTotal = $this->cron_model->getAllTapsTotal();
        if(isset($tapsTotal) && myIsArray($tapsTotal))
        {
            $taps = array();
            foreach($tapsTotal as $key => $row)
            {
                $taps[] = $row['locName'].': '.$row['jobCostCap'];
                $tapDetail = array(
                    'jobCostCap' => 0
                );
                $this->cron_model->updateTapTotal($row['id'],$tapDetail);
            }
            $ttps = implode(',',$taps);
            $details = array(
                'tapsArray' => json_encode($ttps),
                'insertedDT' => date('Y-m-d H:i:s')
            );
            $this->cron_model->saveTapsTotal($details);
        }
    }
}
