<?php

/**
 * Class Dashboard_Model
 * @property Mydatafetch_library $mydatafetch_library
 * @property Generalfunction_library $generalfunction_library
 */
class Dashboard_Model extends CI_Model
{
	function __construct()
	{
		parent::__construct();

        $this->load->library('mydatafetch_library');
	}

    public function getAvgCheckins($dateStart, $dateEnd, $locations)
    {
        $query = "SELECT DISTINCT (SELECT count(DISTINCT mugId, location) "
                ."FROM  mugcheckinmaster "
                ."WHERE checkinDateTime BETWEEN '$dateStart' AND '$dateEnd' AND location != 0) as overall";

            if(isset($locations))
            {
                $length = count($locations)-1;
                $counter = 0;
                foreach($locations as $key => $row)
                {
                    if(isset($row['id']))
                    {
                        $counter++;
                        if($counter <= $length)
                        {
                            $query .= ",";
                        }
                        $query .= "(SELECT count(DISTINCT mugId, location)"
                            ." FROM  mugcheckinmaster "
                            ."WHERE checkinDateTime BETWEEN '$dateStart' AND '$dateEnd' AND location =". $row['id'].")"
                            ." as '".$row['locUniqueLink']."'";

                    }
                }
            }
        $query .= " FROM mugcheckinmaster";


        $result = $this->db->query($query)->row_array();

        $data['checkInList'] = $result;
        if(myIsArray($result))
        {
            $data['status'] = true;
        }
        else
        {
            $data['status'] = false;
        }

        return $data;
    }

    public function getRegulars($dateStart, $dateEnd, $locations)
    {
        $query = "SELECT DISTINCT (SELECT count(*) FROM (SELECT m.mugId,homeBase FROM  mugmaster m
                LEFT JOIN mugcheckinmaster mc ON m.mugId = mc.mugId
                Where date(mc.checkinDateTime) BETWEEN '$dateStart' AND '$dateEnd'
				GROUP BY mc.mugId HAVING count(*) > 2) as tbl) as overall";

        if(isset($locations))
        {
            $length = count($locations)-1;
            $counter = 0;
            foreach($locations as $key => $row)
            {
                if(isset($row['id']))
                {
                    $counter++;
                    if($counter <= $length)
                    {
                        $query .= ",";
                    }
                    $query .= "(SELECT count(*) FROM (SELECT m.mugId,homeBase FROM  mugmaster m
                                LEFT JOIN mugcheckinmaster mc ON m.mugId = mc.mugId
                                Where homeBase = ".$row['id']." AND date(mc.checkinDateTime) BETWEEN '$dateStart' AND '$dateEnd'
                                GROUP BY mc.mugId HAVING count(*) > 2) as tbl) as '".$row['locUniqueLink']."'";
                }
            }
        }
        $query .= " FROM mugcheckinmaster";

        $result = $this->db->query($query)->row_array();

        $data['regularCheckins'] = $result;
        if(myIsArray($result))
        {
            $data['status'] = true;
        }
        else
        {
            $data['status'] = false;
        }

        return $data;
    }

    public function getIrregulars($dateStart, $dateEnd, $locations)
    {
        $query = "SELECT DISTINCT (SELECT count(*) FROM (SELECT m.mugId,homeBase FROM  mugmaster m
                LEFT JOIN mugcheckinmaster mc ON m.mugId = mc.mugId
                Where date(mc.checkinDateTime) BETWEEN '$dateStart' AND '$dateEnd'
				GROUP BY mc.mugId HAVING count(*) <= 1) as tbl) as overall";

        if(isset($locations))
        {
            $length = count($locations)-1;
            $counter = 0;
            foreach($locations as $key => $row)
            {
                if(isset($row['id']))
                {
                    $counter++;
                    if($counter <= $length)
                    {
                        $query .= ",";
                    }
                    $query .= "(SELECT count(*) FROM (SELECT m.mugId,homeBase FROM  mugmaster m
                                LEFT JOIN mugcheckinmaster mc ON m.mugId = mc.mugId
                                Where homeBase = ".$row['id']." AND date(mc.checkinDateTime) BETWEEN '$dateStart' AND '$dateEnd'
                                GROUP BY mc.mugId HAVING count(*) <= 1) as tbl) as '".$row['locUniqueLink']."'";
                }
            }
        }

        $query .= " FROM mugcheckinmaster";

        $result = $this->db->query($query)->row_array();

        $data['irregularCheckins'] = $result;
        if(myIsArray($result))
        {
            $data['status'] = true;
        }
        else
        {
            $data['status'] = false;
        }

        return $data;
    }

    public function getLapsers($dateStart, $dateEnd, $locations)
    {
        $query = "SELECT DISTINCT (SELECT count(*) FROM mugmaster 
                 WHERE membershipEnd BETWEEN '$dateStart' AND '$dateEnd' AND membershipEnd != '0000-00-00') as overall";

        if(isset($locations))
        {
            $length = count($locations)-1;
            $counter = 0;
            foreach($locations as $key => $row)
            {
                if(isset($row['id']))
                {
                    $counter++;
                    if($counter <= $length)
                    {
                        $query .= ",";
                    }
                    $query .= "(SELECT count(*) FROM mugmaster 
                             WHERE homeBase = ".$row['id']." AND membershipEnd BETWEEN '$dateStart' AND '$dateEnd'
                              AND membershipEnd != '0000-00-00') as '".$row['locUniqueLink']."'";
                }
            }
        }
        $query .= " FROM mugmaster";

        $result = $this->db->query($query)->row_array();

        $data['lapsers'] = $result;
        if(myIsArray($result))
        {
            $data['status'] = true;
        }
        else
        {
            $data['status'] = false;
        }

        return $data;
    }

    public function saveDashboardRecord($details)
    {
        $details['insertedDate'] = date('Y-m-d');

        $this->db->insert('dashboardmaster', $details);
        return true;
    }
    public function getDashboardRecord()
    {
        $query = "SELECT * "
                ." FROM dashboardmaster WHERE insertedDate = CURRENT_DATE()";

        $result = $this->db->query($query)->result_array();
        $data['todayStat'] = $result;
        if(myIsArray($result))
        {
            $data['status'] = true;
        }
        else
        {
            $data['status'] = false;
        }

        return $data;
    }
    public function getAllDashboardRecord()
    {
        $query = "SELECT * "
            ." FROM dashboardmaster ORDER BY insertedDate DESC LIMIT 30";

        $result = $this->db->query($query)->result_array();
        $data['dashboardPoints'] = array_reverse($result);
        if(myIsArray($result))
        {
            $data['status'] = true;
        }
        else
        {
            $data['status'] = false;
        }

        return $data;
    }

    public function saveInstaMojoRecord($details)
    {
        $this->db->insert('instamojomaster', $details);
        return true;
    }
    public function updateInstaMojoRecord($id,$details)
    {
        $this->db->where('id', $id);
        $this->db->update('instamojomaster', $details);
        return true;
    }

    public function getAllInstamojoRecord($locId = 0)
    {
        if($locId == 0)
        {
            $query = "SELECT * "
                ." FROM instamojomaster"
                ." WHERE status = 1 AND isApproved = 0";
        }
        else
        {
            $query = "SELECT imm.* "
                ." FROM instamojomaster imm"
                ." LEFT JOIN mugmaster mm ON imm.mugId = mm.mugId"
                ." WHERE status = 1 AND isApproved = 0 AND mm.homeBase = ".$locId;
        }

        $result = $this->db->query($query)->result_array();
        $data['instaRecords'] = $result;
        if(myIsArray($result))
        {
            $data['status'] = true;
        }
        else
        {
            $data['status'] = false;
        }

        return $data;
    }

    public function getAllInstamojoMugRecords($locId = 0)
    {
        if($locId == 0)
        {
            $query = "SELECT imm.id,imm.mugId,imm.firstName,imm.lastName,imm.emailId,lm.locName "
                ." FROM instamojomugmaster imm "
                ."LEFT JOIN locationmaster lm ON imm.homeBase = lm.id"
                ." WHERE imm.status = 1 AND imm.isApproved = 0";
        }
        else
        {
            $query = "SELECT imm.id,imm.mugId,imm.firstName,imm.lastName,imm.emailId,lm.locName "
                ." FROM instamojomugmaster imm "
                ."LEFT JOIN locationmaster lm ON imm.homeBase = lm.id"
                ." WHERE imm.status = 1 AND imm.isApproved = 0 AND imm.homeBase = ".$locId;
        }

        $result = $this->db->query($query)->result_array();
        $data['instaRecords'] = $result;
        if(myIsArray($result))
        {
            $data['status'] = true;
        }
        else
        {
            $data['status'] = false;
        }

        return $data;
    }
    public function getAllFeedbacks($locations)
    {
        $query = "SELECT DISTINCT (SELECT COUNT(overallRating) FROM usersfeedbackmaster 
                 WHERE feedbackLoc != 0) as 'total_overall',
                 (SELECT COUNT(overallRating) FROM usersfeedbackmaster 
                 WHERE feedbackLoc != 0 AND overallRating >= 9) as 'promo_overall',
                 (SELECT COUNT(overallRating) FROM usersfeedbackmaster 
                 WHERE feedbackLoc != 0 AND overallRating < 7) as 'de_overall'";
                 /*,
                 (SELECT COUNT(overallRating) FROM usersfeedbackmaster 
                 WHERE feedbackLoc = 2) as 'total_andheri',
                 (SELECT COUNT(overallRating) FROM usersfeedbackmaster 
                 WHERE feedbackLoc = 3) as 'total_kemps-corner',
                 (SELECT COUNT(overallRating) FROM usersfeedbackmaster 
                 WHERE feedbackLoc = 4) as 'total_colaba'";*/
        if(isset($locations))
        {
            $length = count($locations)-1;
            $counter = 0;
            foreach($locations as $key => $row)
            {
                if(isset($row['id']))
                {
                    $counter++;
                    if($counter <= $length)
                    {
                        $query .= ",";
                    }
                    $query .= "(SELECT COUNT(overallRating) FROM usersfeedbackmaster 
                              WHERE feedbackLoc = ".$row['id'].") as 'total_".$row['locUniqueLink']."',";
                    $query .= "(SELECT COUNT(overallRating) FROM usersfeedbackmaster 
                              WHERE feedbackLoc = ".$row['id']." AND overallRating >= 9) as 'promo_".$row['locUniqueLink']."',";
                    $query .= "(SELECT COUNT(overallRating) FROM usersfeedbackmaster 
                              WHERE feedbackLoc = ".$row['id']." AND overallRating < 7) as 'de_".$row['locUniqueLink']."'";
                }
            }
        }

        $result = $this->db->query($query)->result_array();
        $data['feedbacks'] = $result;
        if(myIsArray($result))
        {
            $data['status'] = true;
        }
        else
        {
            $data['status'] = false;
        }

        return $data;
    }
    public function getFeedbacksMonthWise($locations,$startDate,$endDate)
    {
        $query = "SELECT DISTINCT (SELECT COUNT(overallRating) FROM usersfeedbackmaster 
                 WHERE feedbackLoc != 0 AND (DATE(insertedDateTime) >= '".$startDate."' AND DATE(insertedDateTime) <= '".$endDate."')) as 'total_overall',
                 (SELECT COUNT(overallRating) FROM usersfeedbackmaster 
                 WHERE feedbackLoc != 0 AND overallRating >= 9 AND (DATE(insertedDateTime) >= '".$startDate."' AND DATE(insertedDateTime) <= '".$endDate."')) as 'promo_overall',
                 (SELECT COUNT(overallRating) FROM usersfeedbackmaster 
                 WHERE feedbackLoc != 0 AND overallRating < 7 AND (DATE(insertedDateTime) >= '".$startDate."' AND DATE(insertedDateTime) <= '".$endDate."')) as 'de_overall'";
        /*,
        (SELECT COUNT(overallRating) FROM usersfeedbackmaster
        WHERE feedbackLoc = 2) as 'total_andheri',
        (SELECT COUNT(overallRating) FROM usersfeedbackmaster
        WHERE feedbackLoc = 3) as 'total_kemps-corner',
        (SELECT COUNT(overallRating) FROM usersfeedbackmaster
        WHERE feedbackLoc = 4) as 'total_colaba'";*/
        if(isset($locations))
        {
            $length = count($locations)-1;
            $counter = 0;
            foreach($locations as $key => $row)
            {
                if(isset($row['id']))
                {
                    $counter++;
                    if($counter <= $length)
                    {
                        $query .= ",";
                    }
                    $query .= "(SELECT COUNT(overallRating) FROM usersfeedbackmaster 
                              WHERE feedbackLoc = ".$row['id']." AND (DATE(insertedDateTime) >= '".$startDate."' AND DATE(insertedDateTime) <= '".$endDate."')) as 'total_".$row['locUniqueLink']."',";
                    $query .= "(SELECT COUNT(overallRating) FROM usersfeedbackmaster 
                              WHERE feedbackLoc = ".$row['id']." AND overallRating >= 9 AND (DATE(insertedDateTime) >= '".$startDate."' AND DATE(insertedDateTime) <= '".$endDate."')) as 'promo_".$row['locUniqueLink']."',";
                    $query .= "(SELECT COUNT(overallRating) FROM usersfeedbackmaster 
                              WHERE feedbackLoc = ".$row['id']." AND overallRating < 7 AND (DATE(insertedDateTime) >= '".$startDate."' AND DATE(insertedDateTime) <= '".$endDate."')) as 'de_".$row['locUniqueLink']."'";
                }
            }
        }

        $result = $this->db->query($query)->result_array();
        $data['feedbacks'] = $result;
        if(myIsArray($result))
        {
            $data['status'] = true;
        }
        else
        {
            $data['status'] = false;
        }

        return $data;
    }

    public function getFeedbackData()
    {
        $query = "SELECT ufm.*, lm.locName FROM usersfeedbackmaster ufm
                  LEFT JOIN locationmaster lm ON ufm.feedbackLoc  = lm.id";
        $result = $this->db->query($query)->result_array();
        return $result;
    }
    public function getWeeklyFeedBack()
    {
        $query = "SELECT *
                  FROM feedbackweekscore";

        $result = $this->db->query($query)->result_array();
        return $result;
    }

    public function insertFeedBack($details)
    {
        $this->db->insert_batch('usersfeedbackmaster', $details);
        return true;
    }

    public function saveFnbRecord($details)
    {
        $details['updateDateTime'] = date('Y-m-d H:i:s');
        $details['insertedDateTime'] = date('Y-m-d H:i:s');
        $details['ifActive'] = '1';

        $this->db->insert('fnbmaster', $details);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }
    public function saveFnbAttachment($details)
    {
        $details['insertedDateTime'] = date('Y-m-d H:i:s');

        $this->db->insert('fnbattachment', $details);
        return true;
    }

    public function getAllFnB()
    {
        $query = "SELECT fnbId,itemType,itemName,itemHeadline,itemDescription,priceFull,priceHalf,ifActive
                  FROM fnbmaster ORDER BY fnbId DESC";

        $result = $this->db->query($query)->result_array();
        $data['fnbItems'] = $result;
        if(myIsArray($result))
        {
            $data['status'] = true;
        }
        else
        {
            $data['status'] = false;
        }

        return $data;
    }
    public function getAllActiveFnB()
    {
        $query = "SELECT fm.fnbId,fm.itemType,fm.taggedLoc,fm.itemName,fm.itemHeadline,fm.itemDescription,fm.priceFull,fm.priceHalf,
                  fm.ifActive,fa.id,fa.filename
                  FROM fnbmaster fm
                  LEFT JOIN fnbattachment fa ON fa.fnbId = fm.fnbId
                  WHERE fm.ifActive = 1 
                  GROUP BY fm.fnbId
                  ORDER BY fm.itemType DESC";

        $result = $this->db->query($query)->result_array();
        return $result;
    }
    public function getBeersCount()
    {
        $query = "SELECT count(*) as 'beers' FROM fnbmaster WHERE itemType = 2";

        $result = $this->db->query($query)->row_array();
        return $result;
    }
    public function getFnBById($fnbId)
    {
        $query = "SELECT fnbId,itemType,itemName,itemHeadline,itemDescription,priceFull,priceHalf,ifActive
                  FROM fnbmaster WHERE ifActive = 1 AND fnbId = ".$fnbId;

        $result = $this->db->query($query)->result_array();

        return $result;
    }

    public function getTagLocsFnb($fnbId)
    {
        $query = "SELECT fm.taggedLoc,lm.locName, lm.id
                  FROM fnbmaster fm
                  LEFT JOIN locationmaster lm ON FIND_IN_SET(lm.id,fm.taggedLoc)
                  WHERE fm.taggedLoc IS NOT NULL AND fm.fnbId = ".$fnbId;

        $result = $this->db->query($query)->result_array();

        return $result;
    }
    public function updateBeerLocTag($details, $fnbId)
    {
        $this->db->where('fnbId',$fnbId);
        $this->db->update('fnbmaster', $details);
        return true;
    }

    public function getFnbAttById($id)
    {
        $query = "SELECT id,fnbId,filename,attachmentType
                  FROM fnbattachment WHERE fnbId = ".$id;

        $result = $this->db->query($query)->result_array();

        return $result;
    }

    public function getTopSortNum()
    {
        $query = "SELECT sortOrder FROM fnbmaster
                    where ifActive = 1 and itemType = 1
                    order by sortOrder desc";
        $result = $this->db->query($query)->row_array();
        return $result;
    }

    //Event Related Functions

    public function saveEventRecord($details)
    {
        $details['createdDateTime'] = date('Y-m-d H:i:s');
        $details['ifActive'] = '0';
        $details['ifApproved'] = '0';

        $this->db->insert('eventmaster', $details);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }
    public function updateEventRecord($details, $eventId)
    {
        $this->db->where('eventId',$eventId);
        $this->db->update('eventmaster', $details);
        return true;
    }
    public function cancelEventOffers($eventId)
    {
        $details = array(
            'ifActive' => '0'
        );
        $this->db->where('offerEvent',$eventId);
        $this->db->where('offerType','Workshop');
        $this->db->update('offersmaster', $details);
        return true;
    }
    public function saveEventAttachment($details)
    {
        $details['insertedDateTime'] = date('Y-m-d H:i:s');

        $this->db->insert('eventattachment', $details);
        return true;
    }
    public function updateEventAttachment($details, $eventId)
    {
        $this->db->where('eventId',$eventId);
        $this->db->update('eventattachment', $details);
        return true;
    }
    public function getAllEvents()
    {
        $query = "SELECT *
                  FROM eventmaster ORDER BY eventDate ASC";

        $result = $this->db->query($query)->result_array();

        return $result;
    }

    public function getEventsByUserId($userId)
    {
        $query = "SELECT em.eventId, em.eventName, em.eventDescription, em.eventType, em.eventDate, em.startTime, em.endTime, em.costType, 
                  em.eventPrice, em.priceFreeStuff, em.eventPlace, em.eventCapacity, em.ifMicRequired, em.ifProjectorRequired, 
                  em.creatorName, em.creatorPhone, em.creatorEmail, em.aboutCreator, em.userId, em.eventShareLink, em.showEventDate,
                  em.showEventTime, em.showEventPrice,em.eventPaymentLink, em.ifActive, em.ifApproved, ea.filename, l.locName
                  FROM `eventmaster` em
                  LEFT JOIN eventattachment ea ON ea.eventId = em.eventId
                  LEFT JOIN locationmaster l ON eventPlace = l.id
                  WHERE userId = ".$userId." GROUP BY em.eventId";

        $result = $this->db->query($query)->result_array();

        return $result;
    }
    public function getEventsRegisteredByUser($userId)
    {
        $query = "SELECT erm.bookerId,erm.bookerUserId,erm.eventId,erm.quantity, em.eventId, em.eventName,
                  em.eventDescription, em.eventType, em.eventDate, em.startTime, em.endTime, em.costType, 
                  em.eventPrice, em.priceFreeStuff, em.eventPlace, em.eventCapacity, em.ifMicRequired, em.ifProjectorRequired, 
                  em.creatorName, em.creatorPhone, em.creatorEmail, em.aboutCreator, em.userId, em.eventShareLink,em.showEventDate,
                  em.showEventTime, em.showEventPrice, em.eventPaymentLink, em.ifActive, em.ifApproved, ea.filename, l.locName
                  FROM eventregistermaster erm
                  LEFT JOIN eventmaster em ON em.eventId = erm.eventId
                  LEFT JOIN eventattachment ea ON ea.eventId = erm.eventId
                  LEFT JOIN locationmaster l ON l.id = em.eventPlace
                  WHERE erm.eventDone != 1 AND bookerUserId = ".$userId." GROUP BY erm.eventId";

        $result = $this->db->query($query)->result_array();

        return $result;
    }
    public function getEventById($eventId)
    {
        $query = "SELECT *
                  FROM eventmaster WHERE eventId = ".$eventId;

        $result = $this->db->query($query)->result_array();

        return $result;
    }
    public function getWeeklyEvents()
    {
        $query = "SELECT GROUP_CONCAT(eventName SEPARATOR ',') as eventNames,
                  GROUP_CONCAT(eventPlace SEPARATOR ',') as eventPlaces,eventDate FROM eventmaster
                  WHERE eventDate BETWEEN CURRENT_DATE() AND (CURRENT_DATE() + INTERVAL 1 WEEK) 
                  AND ifActive  = ".ACTIVE." AND ifApproved = ".EVENT_APPROVED." GROUP BY eventDate 
                  ORDER BY eventDate ASC";

        $result = $this->db->query($query)->result_array();

        return $result;
    }
    public function getAllApprovedEvents()
    {
        $query = "SELECT em.eventId, em.eventName, em.eventDescription, em.eventType, em.eventDate, em.startTime, em.endTime, em.costType, 
                  em.eventPrice, em.priceFreeStuff, em.eventPlace, em.eventCapacity, em.ifMicRequired, em.ifProjectorRequired, 
                  em.creatorName, em.creatorPhone, em.creatorEmail, em.aboutCreator, em.userId, em.eventShareLink,em.showEventDate,
                  em.showEventTime, em.showEventPrice, em.eventPaymentLink, em.ifActive, em.ifApproved, ea.filename, l.locName, l.mapLink
                  FROM `eventmaster` em
                  LEFT JOIN eventattachment ea ON ea.eventId = em.eventId
                  LEFT JOIN locationmaster l ON eventPlace = l.id
                  WHERE em.ifActive = ".ACTIVE." AND em.ifApproved = ".EVENT_APPROVED." AND eventDate >= CURRENT_DATE() GROUP BY em.eventId";

        /*$query = "SELECT * FROM eventmaster where ifActive = ".ACTIVE."
         AND eventDate >= CURRENT_DATE()";*/
        $result = $this->db->query($query)->result_array();

        return $result;
    }
    public function getDashboardEventDetails($eventId)
    {
        $query = "SELECT em.eventId, em.eventName, em.costType,em.eventPrice,em.eventShareLink,
                  em.ifActive, em.ifApproved, SUM(erm.quantity) as 'totalQuant'
                  FROM `eventmaster`em
                  LEFT JOIN eventregistermaster erm ON erm.eventId = em.eventId
                  WHERE em.eventId = ".$eventId;

        $result = $this->db->query($query)->result_array();

        return $result;
    }
    public function getJoinersInfo($eventId)
    {
        $query = "SELECT um.firstName, um.lastName, um.emailId, um.mobNum, erm.paymentId, erm.quantity, erm.createdDT, erm.isDirectlyRegistered, erm.regPrice
                  FROM eventregistermaster erm
                  LEFT JOIN doolally_usersmaster um ON um.userId = erm.bookerUserId
                  WHERE erm.isUserCancel != 1 AND erm.eventId = $eventId ORDER BY erm.createdDT DESC";

        $result = $this->db->query($query)->result_array();

        return $result;
    }
    public function getDoolallyJoinersInfo($eventId)
    {
        $query = "SELECT um.firstName, um.lastName, um.emailId, um.mobNum, erm.paymentId, erm.quantity, erm.regPrice, erm.createdDT
                  FROM eventregistermaster erm
                  LEFT JOIN doolally_usersmaster um ON um.userId = erm.bookerUserId
                  WHERE erm.isUserCancel != 1 AND erm.isDirectlyRegistered = 1 AND erm.eventId = $eventId ORDER BY erm.createdDT DESC";

        $result = $this->db->query($query)->result_array();

        return $result;
    }
    public function getEhJoinersInfo($eventId)
    {
        $query = "SELECT um.firstName, um.lastName, um.emailId, um.mobNum, erm.paymentId, erm.quantity, erm.regPrice, erm.createdDT
                  FROM eventregistermaster erm
                  LEFT JOIN doolally_usersmaster um ON um.userId = erm.bookerUserId
                  WHERE erm.isUserCancel != 1 AND erm.isDirectlyRegistered = 0 AND erm.eventId = $eventId ORDER BY erm.createdDT DESC";

        $result = $this->db->query($query)->result_array();

        return $result;
    }
    function getCancelList($eventId)
    {
        $query = "SELECT um.firstName, um.lastName, um.emailId, um.mobNum, erm.paymentId, erm.regPrice, erm.quantity, erm.createdDT,erm.isDirectlyRegistered
                  FROM eventregistermaster erm
                  LEFT JOIN doolally_usersmaster um ON um.userId = erm.bookerUserId
                  WHERE erm.isUserCancel = 1 AND erm.eventId = $eventId ORDER BY erm.createdDT DESC";

        $result = $this->db->query($query)->result_array();

        return $result;
    }
    function getReminderList($eventId)
    {
        $query = "SELECT erm.emailId, erm.insertedDT
                FROM eventremindermaster erm
                WHERE erm.eventId = ".$eventId." AND erm.emailId NOT IN (SELECT dum.emailId FROM eventregistermaster ergm
                LEFT JOIN doolally_usersmaster dum ON ergm.bookerUserId = dum.userId
                WHERE ergm.eventId = ".$eventId.")";
        $result = $this->db->query($query)->result_array();
        return $result;
    }
    public function ApproveEvent($eventId)
    {
        $data['ifActive'] = 1;
        $data['ifApproved'] = 1;

        $this->db->where('eventId', $eventId);
        $this->db->update('eventmaster', $data);
        return true;
    }
    public function DeclineEvent($eventId)
    {
        $data['ifActive'] = 0;
        $data['ifApproved'] = 2;

        $this->db->where('eventId', $eventId);
        $this->db->update('eventmaster', $data);
        return true;
    }
    public function findCompletedEvents()
    {
        $query = "SELECT em.*, ea.filename, l.locName
                  FROM `eventcompletedmaster` em
                  LEFT JOIN eventattachment ea ON ea.eventId = em.eventId
                  LEFT JOIN locationmaster l ON eventPlace = l.id
                  GROUP BY em.eventId";

        $result = $this->db->query($query)->result_array();
        return $result;
    }
    public function checkUserBooked($userId, $eventId)
    {
        $query = "SELECT * FROM eventregistermaster
                  WHERE bookerUserId = ".$userId." AND eventId = ".$eventId;
        $result = $this->db->query($query)->result_array();

        if(myIsArray($result))
        {
            $data['status'] = true;
        }
        else
        {
            $data['status'] = false;
        }
        return $data;
    }
    public function checkUserCreated($userId, $eventId)
    {
        $query = "SELECT * FROM eventmaster
                  WHERE userId = ".$userId." AND eventId = ".$eventId;
        $result = $this->db->query($query)->result_array();

        if(myIsArray($result))
        {
            $data['status'] = true;
        }
        else
        {
            $data['status'] = false;
        }
        return $data;
    }
    public function checkEventSpace($details)
    {
        $data['status'] = false;
        return $data;
        $query = "SELECT * FROM eventmaster
                  WHERE startTime >= '".$details['startTime']."' AND endTime <= '".$details['endTime']."' AND 
                  eventPlace = '".$details['eventPlace']."' AND eventDate = '".$details['eventDate']."'";
        $result = $this->db->query($query)->result_array();

        if(myIsArray($result))
        {
            $data['status'] = true;
        }
        else
        {
            $data['status'] = false;
        }
        return $data;

    }
    public function activateEventRecord($eventId)
    {
        $data['ifActive'] = 1;

        $this->db->where('eventId', $eventId);
        $this->db->update('eventmaster', $data);
        return true;
    }
    public function deActivateEventRecord($eventId)
    {
        $data['ifActive'] = 0;

        $this->db->where('eventId', $eventId);
        $this->db->update('eventmaster', $data);
        return true;
    }
    public function eventDelete($eventId)
    {
        $this->db->where('eventId', $eventId);
        $this->db->delete('eventmaster');
        return true;
    }
    public function eventRegisDelete($eventId)
    {
        $data['eventDone'] = '1';
        $this->db->where('eventId', $eventId);
        $this->db->update('eventregistermaster',$data);
        return true;
    }
    public function eventCompDelete($eventId)
    {
        $query = "INSERT INTO eventdeletedmaster "
            ."SELECT * FROM eventcompletedmaster "
            ."where eventId = ".$eventId;

        $this->db->query($query);

        $this->db->where('eventId', $eventId);
        $this->db->delete('eventcompletedmaster');
        return true;
    }
    public function eventAttDeleteById($eventId)
    {
        $this->db->where('eventId', $eventId);
        $this->db->delete('eventattachment');
        return true;
    }
    public function eventAttDelete($attId)
    {
        $this->db->where('id', $attId);
        $this->db->delete('eventattachment');
        return true;
    }
    public function transferDeleteEvent($eventId)
    {
        $query = "INSERT INTO eventdeletedmaster "
            ."SELECT * FROM eventmaster "
            ."where eventId = ".$eventId;

        $this->db->query($query);

        $this->db->where('eventId', $eventId);
        $this->db->delete('eventmaster');
        return true;
    }
    public function fnbAttDelete($attId)
    {
        $this->db->where('id', $attId);
        $this->db->delete('fnbattachment');
        return true;
    }
    public function getEventAttById($id)
    {
        $query = "SELECT id, filename
                  FROM eventattachment WHERE eventId = ".$id;

        $result = $this->db->query($query)->result_array();

        return $result;
    }

    public function getFullEventInfoById($eventId)
    {
        $query = "SELECT em.*, ea.filename, l.locName, l.locAddress, l.mapLink, l.meetupVenueId, um.mobNum
                  FROM `eventmaster` em
                  LEFT JOIN eventattachment ea ON ea.eventId = em.eventId
                  LEFT JOIN locationmaster l ON eventPlace = l.id
                  LEFT JOIN doolally_usersmaster um ON FIND_IN_SET(l.id,um.assignedLoc)
                  WHERE em.eventId = ".$eventId." GROUP BY em.eventId";

        $result = $this->db->query($query)->result_array();

        return $result;
    }

    function commEventCheck($email)
    {
        $query = "SELECT * FROM doolally_usersmaster WHERE userType = ".EXECUTIVE_USER." AND emailId LIKE '".$email."'";

        $result = $this->db->query($query)->row_array();
        return $result;
    }

    public function saveEventRegis($details)
    {
        $details['createdDT'] = date('Y-m-d H:i:s');

        $this->db->insert('eventregistermaster', $details);
        return true;
    }
    public function saveEventHigh($details)
    {
        $this->db->insert('eventshighmaster', $details);
        return true;
    }
    public function saveMeetup($details)
    {
        $this->db->insert('meetupmaster', $details);
        return true;
    }
    public function getEventHighRecord($eventId)
    {
        $query = "SELECT id, highId FROM eventshighmaster WHERE highStatus = 1 AND eventId = ".$eventId;

        $result = $this->db->query($query)->row_array();

        return $result;
    }
    public function updateCancelEH($details, $id)
    {
        $this->db->where('id',$id);
        $this->db->update('eventshighmaster',$details);
        return true;
    }
    public function getEventCouponInfo($eventId, $payId)
    {
        $query = "SELECT isRedeemed, offerType
                  FROM offersmaster  
                  WHERE offerEvent = ".$eventId." AND bookerPaymentId = '".$payId."'";

        $result = $this->db->query($query)->result_array();

        return $result;
    }
    public function getMeetupRecord($eventId)
    {
        $query = "SELECT meetupId FROM meetupmaster WHERE meetupStatus = 1 AND eventId = ".$eventId;

        $result = $this->db->query($query)->row_array();

        return $result;
    }

    //For Fnb
    public function activateFnbRecord($fnbId)
    {
        $data['ifActive'] = 1;

        $this->db->where('fnbId', $fnbId);
        $this->db->update('fnbmaster', $data);
        return true;
    }
    public function DeActivateFnbRecord($fnbId)
    {
        $data['ifActive'] = 0;

        $this->db->where('fnbId', $fnbId);
        $this->db->update('fnbmaster', $data);
        return true;
    }
    public function fnbDelete($fnbId)
    {
        $this->db->where('fnbId', $fnbId);
        $this->db->delete('fnbmaster');
        return true;
    }

    public function updateFnbRecord($details, $fnbId)
    {
        $this->db->where('fnbId',$fnbId);
        $this->db->update('fnbmaster', $details);
        return true;
    }

    public function getTapSongs($tapId)
    {
        $query = 'SELECT * 
                  FROM jukeboxmaster
                  WHERE tapId = '.$tapId;

        $result = $this->db->query($query)->result_array();

        return $result;
    }

    /* Wallet related function */
    public function getAllCheckins()
    {
        $query = "SELECT *"
            ." FROM staffcheckinmaster"
            ." WHERE staffStatus = 1";

        $result = $this->db->query($query)->result_array();

        return $result;
    }
    public function getBalanceByInput($mobnum)
    {
        $query = "SELECT sm.id, sm.mobNum, sm.empId, sm.firstName, sm.middleName, sm.lastName, sm.walletBalance, sm.ifActive"
            ." FROM staffmaster sm"
            ." WHERE sm.mobNum = '".$mobnum."' OR sm.empId = '".$mobnum."'";

        $result = $this->db->query($query)->row_array();

        return $result;
    }

    public function getBalanceByEmp($empId)
    {
        $query = "SELECT sm.id, sm.mobNum, sm.empId, sm.firstName, sm.middleName, sm.lastName, sm.walletBalance, sm.ifActive"
            ." FROM staffmaster sm"
            ." WHERE sm.empId = '".$empId."'";

        $result = $this->db->query($query)->row_array();

        return $result;
    }
    public function checkStaffChecked($empId)
    {
        $query = "SELECT *"
            ." FROM staffcheckinmaster"
            ." WHERE empId = '".$empId."' AND staffStatus = 1";

        $result = $this->db->query($query)->result_array();
        $data['checkin'] = $result;
        if(myIsArray($result))
        {
            $data['status'] = true;
        }
        else
        {
            $data['status'] = false;
        }

        return $data;
    }

    public function checkStaffById($empId)
    {
        $query = "SELECT *"
            ." FROM staffmaster"
            ." WHERE empId = '".$empId."'";

        $result = $this->db->query($query)->result_array();
        $data['checkin'] = $result;
        if(myIsArray($result))
        {
            $data['status'] = true;
        }
        else
        {
            $data['status'] = false;
        }

        return $data;
    }
    public function checkStaffByMob($mobNum)
    {
        $query = "SELECT *"
            ." FROM staffmaster"
            ." WHERE mobNum = '".$mobNum."'";

        $result = $this->db->query($query)->result_array();
        $data['checkin'] = $result;
        if(myIsArray($result))
        {
            $data['status'] = true;
        }
        else
        {
            $data['status'] = false;
        }

        return $data;
    }

    public function getCheckinById($id)
    {
        $query = "SELECT *"
            ." FROM staffcheckinmaster"
            ." WHERE staffStatus = 1 AND id = ".$id;

        $result = $this->db->query($query)->result_array();

        return $result;
    }
    public function checkBillNum($billNum,$locId)
    {
        $query = "SELECT *"
            ." FROM staffbillingmaster"
            ." WHERE billNum LIKE '".$billNum."' AND billLoc = ".$locId;

        $result = $this->db->query($query)->result_array();

        return $result;
    }

    public function saveCheckinLog($details)
    {
        $details['updateDT'] = date('Y-m-d H:i:s');
        $details['staffStatus'] = '1';

        $this->db->insert('staffcheckinmaster', $details);
        return true;
    }

    public function clearCheckinLog($id)
    {
        //$details['updateDT'] = date('Y-m-d H:i:s');
        $details['staffStatus'] = '2';

        $this->db->where('id', $id);
        $this->db->update('staffcheckinmaster', $details);
        return true;
    }
    public function saveBillLog($details)
    {
        $this->db->insert('staffbillingmaster', $details);
        return true;
    }
    public function saveFailBillLog($details)
    {
        $this->db->insert('staffbillingfailmaster', $details);
        return true;
    }
    public function getWalletTrans($id)
    {
        $query = "SELECT wlm.amount, wlm.amtAction, wlm.notes, wlm.loggedDT, wlm.updatedBy, sb.billNum, lm.locName"
            ." FROM walletlogmaster wlm"
            ." LEFT JOIN staffbillingmaster sb ON wlm.id = sb.walletId"
            ." LEFT JOIN locationmaster lm ON lm.id = sb.billLoc"
            ." WHERE wlm.staffId = ".$id
            ." ORDER BY loggedDT ASC";

        $result = $this->db->query($query)->result_array();
        $data['walletDetails'] = $result;
        if(myIsArray($result))
        {
            $data['status'] = true;
        }
        else
        {
            $data['status'] = false;
        }

        return $data;
    }
    public function getWalletBalance($id)
    {
        $query = "SELECT sm.firstName, sm.middleName, sm.lastName, sm.walletBalance"
            ." FROM staffmaster sm"
            ." WHERE sm.id = ".$id;

        $result = $this->db->query($query)->result_array();

        return $result;
    }
    public function updateWalletLog($details)
    {
        $this->db->insert('walletlogmaster', $details);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    public function updateStaffRecord($id,$details)
    {
        $this->db->where('id', $id);
        $this->db->update('staffmaster', $details);
        return true;
    }
    function deleteStaffRecord($id)
    {
        $query = "INSERT INTO deletedstaffmaster "
            ."SELECT * FROM staffmaster "
            ."where id = ".$id;

        $this->db->query($query);

        $this->db->where('id', $id);
        $this->db->delete('staffmaster');
        return true;
    }
    public function updateStaffRecordByEmp($empid,$details)
    {
        $this->db->where('empId', $empid);
        $this->db->update('staffmaster', $details);
        return true;
    }
    public function walletLogsBatch($details)
    {
        $this->db->insert_batch('walletlogmaster', $details);
        return true;
    }
    function offWallBatch($details)
    {
        $this->db->insert_batch('offwalletrecord',$details);
        return true;
    }
    public function smsLogsBatch($details)
    {
        $this->db->insert_batch('smsmaster', $details);
        return true;
    }
    public function saveStaffRecord($details)
    {
        $details['insertedDT'] = date('Y-m-d H:i:s');
        $details['ifActive'] = '1';

        $this->db->insert('staffmaster', $details);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }
    public function blockStaffRecord($id)
    {
        $details = array(
            'ifActive'=> '0',
            'mobNum' => '9999999999'
        );
        $this->db->where('id', $id);
        $this->db->update('staffmaster', $details);
        return true;
    }

    public function freeStaffRecord($id,$mobNum)
    {
        $details = array(
            'ifActive'=> '1',
            'mobNum' => $mobNum
        );
        $this->db->where('id', $id);
        $this->db->update('staffmaster', $details);
        return true;
    }
    public function getStaffById($id)
    {
        $query = "SELECT *"
            ." FROM staffmaster"
            ." WHERE id = ".$id;

        $result = $this->db->query($query)->result_array();
        $data['staffDetails'] = $result;
        if(myIsArray($result))
        {
            $data['status'] = true;
        }
        else
        {
            $data['status'] = false;
        }

        return $data;
    }
    public function getAllStaffs($ifActive = '', $mobNum = '')
    {
        if($ifActive != '' && $mobNum != '')
        {
            $query = "SELECT sm.id, sm.empId, sm.firstName, sm.middleName, sm.lastName,
                   sm.walletBalance, sm.mobNum, sm.insertedDT, sm.ifActive"
                ." FROM staffmaster sm WHERE sm.ifActive = 1 AND sm.mobNum != '' AND sm.mobNum IS NOT NULL";
        }
        else
        {
            $query = "SELECT sm.id, sm.empId, sm.firstName, sm.middleName, sm.lastName,
                   sm.walletBalance, sm.mobNum, sm.insertedDT, sm.ifActive"
                ." FROM staffmaster sm";
        }

        $result = $this->db->query($query)->result_array();
        $data['staffList'] = $result;
        if(myIsArray($result))
        {
            $data['status'] = true;
        }
        else
        {
            $data['status'] = false;
        }

        return $data;
    }
    public function getStaffsByPeriod($period)
    {
        $query= "SELECT *"
            ." FROM staffmaster WHERE ifActive = 1 AND mobNum != '' AND mobNum IS NOT NULL "
            ."AND isRecurring = 1 AND userType = ".WALLET_RESTAURANT." AND recurringFrequency LIKE '%monthly%'";
        switch ($period)
        {
            case 'monthly':
                $query= "SELECT *"
                    ." FROM staffmaster WHERE ifActive = 1 AND mobNum != '' AND mobNum IS NOT NULL "
                    ."AND isRecurring = 1 AND userType = ".WALLET_RESTAURANT." AND recurringFrequency LIKE '%monthly%'";
                break;
            case 'quarterly':
                $query= "SELECT *"
                    ." FROM staffmaster WHERE ifActive = 1 AND mobNum != '' AND mobNum IS NOT NULL "
                    ."AND isRecurring = 1 AND userType = ".WALLET_RESTAURANT." AND recurringFrequency LIKE '%quarterly%'";
                break;
            case 'yearly':
                $query= "SELECT *"
                    ." FROM staffmaster WHERE ifActive = 1 AND mobNum != '' AND mobNum IS NOT NULL "
                    ."AND isRecurring = 1 AND userType = ".WALLET_RESTAURANT." AND recurringFrequency LIKE '%yearly%'";
        }

        $result = $this->db->query($query)->result_array();
        $data['staffList'] = $result;
        if(myIsArray($result))
        {
            $data['status'] = true;
        }
        else
        {
            $data['status'] = false;
        }

        return $data;
    }
    function getAllOfficeWallets()
    {
        $query = "SELECT * FROM staffmaster WHERE ifActive = 1 AND userType = ".WALLET_OFFICE;

        $result = $this->db->query($query)->result_array();

        return $result;
    }
    public function getStaffByEmpId($empid)
    {
        $query = "SELECT *"
            ." FROM staffmaster"
            ." WHERE empId = '".$empid."'";

        $result = $this->db->query($query)->result_array();

        return $result;
    }

    public function checkStaffOtp($mob, $otp)
    {
        $query = "SELECT id, walletBalance "
            ."FROM staffmaster "
            ."where id = '".$mob."' AND userOtp = ".$otp;

        $result = $this->db->query($query)->row_array();

        $data = $result;
        if(myIsArray($result))
        {
            $data['status'] = true;
        }
        else
        {
            $data['status'] = false;
        }

        return $data;
    }

    function getStaffIds()
    {
        $query = "SELECT id FROM staffmaster";

        $result = $this->db->query($query)->result_array();
        return $result;
    }
    function getAllStaffBills($staffId)
    {
        $query = "SELECT id, billAmount,insertedDT FROM staffbillingmaster WHERE staffId = ".$staffId;
        $result = $this->db->query($query)->result_array();
        return $result;
    }

    function getAllStaffWallets($staffId)
    {
        $query = "SELECT id, amount, loggedDT FROM walletlogmaster WHERE amtAction = 1 AND staffId = ".$staffId;
        $result = $this->db->query($query)->result_array();
        return $result;
    }
    function updateStaffBill($id,$details)
    {
        $this->db->where('id',$id);
        $this->db->update('staffbillingmaster',$details);
    }

    /* Meta Tag Sharing function */
    public function getRecentMeta()
    {
        $query = "SELECT *"
            ." FROM custommetatags"
            ." WHERE tagType = 0"
            ." ORDER BY id DESC";

        $result = $this->db->query($query)->result_array();

        return $result;
    }
    public function saveMetaRecord($details)
    {
        $details['updateDT'] = date('Y-m-d H:i:s');

        $this->db->insert('custommetatags', $details);
        return true;
    }
    public function getOlympicsMeta()
    {
        $query = "SELECT *"
            ." FROM custommetatags"
            ." WHERE tagType = 1"
            ." ORDER BY id DESC";

        $result = $this->db->query($query)->result_array();

        return $result;
    }

    public function checkWalletLog($id)
    {
        $query = "SELECT * from walletlogmaster 
                  WHERE amtAction = 2 AND notes LIKE '%New Staff Added%' AND staffId = ".$id;

        $result = $this->db->query($query)->result_array();

        $data = array();
        if(myIsArray($result))
        {
            $data['status'] = true;
        }
        else
        {
            $data['status'] = false;
        }
        return $data;
    }

    public function getMailLastId()
    {
        $query = "SELECT id FROM swiftmailerlogs ORDER BY id DESC LIMIT 0,1";

        $result = $this->db->query($query)->row_array();
        return $result;
    }

    public function mailUpdateCount($lastId,$senderEmail)
    {
        $query = "SELECT count(*) as 'total' FROM swiftmailerlogs WHERE id > ".$lastId." AND sendFrom= '".$senderEmail."'";

        $result = $this->db->query($query)->row_array();
        return $result;
    }

    public function saveEventSlug($details)
    {
        $this->db->insert('eventslugmaster', $details);
        return true;
    }
    public function saveErrorLog($details)
    {
        $details['insertedDateTime'] = date('Y-m-d H:i:s');
        $details['fromWhere'] = 'Dashboard';
        $this->db->insert('errorlogger', $details);
        return true;
    }
    public function getAllTweets()
    {
        $query = "SELECT * FROM twitterbotmaster";
        $result = $this->db->query($query)->result_array();
        return $result;
    }
    public function saveTweet($details)
    {
        $this->db->insert('twitterbotmaster', $details);
        return true;
    }

    public function saveEventChangeRecord($details)
    {
        $this->db->insert('eventchangesmaster', $details);
        return true;
    }
    public function getEditRecord($eventId)
    {
        $query = "SELECT * FROM eventchangesmaster WHERE isPending = 0 AND eventId = ".$eventId." ORDER BY insertedDT DESC";

        $result = $this->db->query($query)->row_array();
        return $result;
    }
    public function getEventEditRecord($eventId)
    {
        $query = "SELECT eventName,eventDescription,eventDate,startTime,endTime,
                  costType,eventPrice,eventPlace,creatorName,creatorEmail,creatorPhone,eventCapacity,
                  ifMicRequired,ifProjectorRequired,imgAttachment,verticalImg
                  FROM eventchangesmaster WHERE isPending = 0 AND eventId = ".$eventId." ORDER BY insertedDT DESC";

        $result = $this->db->query($query)->row_array();
        return $result;
    }
    public function updateEditRecord($details,$id)
    {
        $this->db->where('eventId', $id);
        $this->db->update('eventchangesmaster', $details);
        return true;
    }

    //Getting all event alternate share images
    public function getAllShareImgs($eventId)
    {
       $query = "SELECT * FROM eventimgsharemaster WHERE eventId = ".$eventId;
       $result = $this->db->query($query)->result_array();
       return $result;
    }
    public function saveShareImg($details)
    {
        $this->db->insert('eventimgsharemaster',$details);
        return true;
    }
    function resetShareImgs($eventId)
    {
        $details = array(
            'ifUsing' => '0'
        );
        $this->db->where('eventId',$eventId);
        $this->db->update('eventimgsharemaster',$details);
    }
    function makeShareImgActive($imgId)
    {
        $details = array(
            'ifUsing' => 1
        );
        $this->db->where('id',$imgId);
        $this->db->update('eventimgsharemaster',$details);
        return true;
    }

    function getEventByPaymentId($payId)
    {
        $query = "SELECT 
                  CASE WHEN em.eventName IS NULL THEN em1.eventName ELSE em.eventName END AS 'eventName',
                  CASE WHEN em.creatorName IS NULL THEN em1.creatorName ELSE em.creatorName END AS 'creatorName',
                  erm.quantity FROM eventregistermaster erm 
                  LEFT JOIN eventmaster em ON erm.eventId = em.eventId
                  LEFT JOIN eventcompletedmaster em1 ON erm.eventId = em1.eventId
                  WHERE erm.paymentId LIKE '".$payId."'";

        $result = $this->db->query($query)->row_array();
        return $result;

    }

    function fetchPendingSms()
    {
        $query = "SELECT * FROM smsmaster WHERE smsDescription LIKE 'Insufficient credits'";

        $result = $this->db->query($query)->result_array();

        return $result;
    }
    function saveEhRefundDetails($details)
    {
        $this->db->insert('ehrefundmaster',$details);
        return true;
    }
    public function saveUpiDump($details)
    {
        $this->db->insert('upidumpingtable', $details);
        return true;
    }
    public function saveSmsWall($details)
    {
        $this->db->insert('smsreceivemaster', $details);
        return true;
    }
    function getOrgCoupon($eventId)
    {
        $query = "SELECT * FROM offersmaster WHERE offerType != 'Workshop' AND isOrganiser = 1 AND offerEvent = ".$eventId;
        $result = $this->db->query($query)->row_array();

        return $result;
    }
    function updateOfferCode($details,$offerId)
    {
        $this->db->where('id',$offerId);
        $this->db->update('offersmaster',$details);
        return true;
    }
    public function saveDashLogs($details)
    {
        $this->db->insert('logsrecordsmaster', $details);
        return true;
    }
    public function saveCustomMailLog($details)
    {
        $this->db->insert('eventcustommaillogger', $details);
        return true;
    }

    function getOrgNewEvents()
    {
        $query = "SELECT GROUP_CONCAT(em.eventId) AS 'ids', GROUP_CONCAT(em.eventName SEPARATOR ';') AS 'eveNames', 
                  GROUP_CONCAT(em.eventPlace SEPARATOR ';') AS 'evePlaces', GROUP_CONCAT(lm.locName SEPARATOR ';') AS 'locNames',
                   em.creatorName, em.creatorEmail, em.creatorPhone
                    FROM eventmaster em
                    LEFT JOIN locationmaster lm ON em.eventPlace = lm.id
                    WHERE em.costType != 1 AND em.ifActive = ".ACTIVE." AND em.ifApproved = ".EVENT_APPROVED." AND em.isEventCancel = 0 
                    AND em.ifAutoCreated = 0 GROUP BY em.userId";

        $result = $this->db->query($query)->result_array();
        return $result;
    }
    function getOrgOldEvents()
    {
        $query = "SELECT GROUP_CONCAT(em.eventId) AS 'ids', GROUP_CONCAT(em.eventName SEPARATOR ';') AS 'eveNames',
                  GROUP_CONCAT(em.eventPlace SEPARATOR ';') AS 'evePlaces', GROUP_CONCAT(lm.locName SEPARATOR ';') AS 'locNames',
                   em.creatorName, em.creatorEmail, em.creatorPhone
                    FROM eventcompletedmaster em 
                    LEFT JOIN locationmaster lm ON em.eventPlace = lm.id
                    WHERE em.costType != 1 AND em.ifActive = ".ACTIVE." AND em.ifApproved = ".EVENT_APPROVED." AND em.isEventCancel = 0 
                    AND em.ifAutoCreated = 0 GROUP BY em.userId";

        $result = $this->db->query($query)->result_array();
        return $result;
    }

    function getOrgCombinedEvents()
    {
        $query = "SELECT GROUP_CONCAT(eventId) AS 'ids', GROUP_CONCAT(eventName SEPARATOR ';') AS 'eveNames', creatorName,
                    creatorEmail, creatorPhone, 'new' as 'type'
                    FROM eventmaster WHERE costType != 1 AND ifActive = ".ACTIVE." AND ifApproved = ".EVENT_APPROVED." AND isEventCancel = 0 
                    AND ifAutoCreated = 0 GROUP BY userId
                    UNION
                    SELECT GROUP_CONCAT(eventId) AS 'ids', GROUP_CONCAT(eventName SEPARATOR ';') AS 'eveNames', creatorName, 
                    creatorEmail, creatorPhone, 'old' as 'type'
                    FROM eventcompletedmaster WHERE costType != 1 AND ifActive = ".ACTIVE." AND ifApproved = ".EVENT_APPROVED." AND isEventCancel = 0 
                    AND ifAutoCreated = 0 GROUP BY userId";

        $result = $this->db->query($query)->result_array();
        return $result;
    }
    function getEventAllRegs($eventId)
    {
        $query = "SELECT erm.quantity,
                  CASE WHEN erm.regPrice IS NULL THEN em.eventPrice ELSE erm.regPrice END AS 'price' 
                  FROM eventregistermaster erm 
                  LEFT JOIN eventmaster em ON erm.eventId = em.eventId 
                  WHERE erm.eventId = ".$eventId;

        $result = $this->db->query($query)->result_array();
        return $result;
    }
    function getEventAllOldRegs($eventId)
    {
        $query = "SELECT erm.quantity,
                  CASE WHEN erm.regPrice IS NULL THEN em.eventPrice ELSE erm.regPrice END AS 'price' 
                  FROM eventregistermaster erm 
                  LEFT JOIN eventcompletedmaster em ON erm.eventId = em.eventId 
                  WHERE erm.eventId = ".$eventId;

        $result = $this->db->query($query)->result_array();
        return $result;
    }
    function getAllEventRegis($eventId)
    {
        $query = "SELECT erm.quantity,
                  CASE WHEN erm.regPrice IS NULL THEN
                  (CASE WHEN em.eventId IS NULL THEN ecm.eventPrice ELSE em.eventPrice END) ELSE erm.regPrice END AS 'price' 
                  FROM eventregistermaster erm 
                  LEFT JOIN eventmaster em ON erm.eventId = em.eventId 
                  LEFT JOIN eventcompletedmaster ecm ON erm.eventId = ecm.eventId
                  WHERE erm.eventId = ".$eventId;
        $result = $this->db->query($query)->result_array();
        return $result;
    }
}
