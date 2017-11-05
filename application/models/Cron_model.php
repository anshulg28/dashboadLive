<?php

/**
 * Class Cron_Model
 * @property Mydatafetch_library $mydatafetch_library
 * @property Generalfunction_library $generalfunction_library
 */
class Cron_Model extends CI_Model
{
	function __construct()
	{
		parent::__construct();

        $this->load->library('mydatafetch_library');
	}

    public function checkFeedByType($feedType)
    {
        $query = "SELECT * "
            ."FROM socialfeedmaster "
            ."where feedType = '".$feedType."' ";

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
    public function getAllFeeds()
    {
        $query = "SELECT * "
            ."FROM socialfeedmaster";

        $result = $this->db->query($query)->result_array();

        $data['feedData'] = $result;
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

    public function getAllSortedFeeds()
    {
        $query = "SELECT * "
            ."FROM socialfeedmaster WHERE feedType = 0";

        $result = $this->db->query($query)->result_array();

        return $result;
    }

    public function updateFeedByType($post,$feedType)
    {
        $post['updateDateTime'] = date('Y-m-d H:i:s');

        $this->db->where('feedType', $feedType);
        $this->db->update('socialfeedmaster', $post);
        return true;
    }
    public function updateFeedById($post,$feedId)
    {
        $post['updateDateTime'] = date('Y-m-d H:i:s');

        $this->db->where('id', $feedId);
        $this->db->update('socialfeedmaster', $post);
        return true;
    }
    public function insertFeedByType($post)
    {
        $post['updateDateTime'] = date('Y-m-d H:i:s');

        $this->db->insert('socialfeedmaster', $post);
        return true;
    }
    public function insertFeedBatch($details)
    {
        $this->db->insert_batch('socialviewmaster', $details);
        return true;
    }
    public function getTopViewFeed()
    {
        $query = "SELECT * "
            ."FROM socialviewmaster LIMIT 1";

        $result = $this->db->query($query)->row_array();

        return $result;
    }
    public function getAllViewFeeds()
    {
        $query = "SELECT feedId,feedText,updateDateTime "
            ."FROM socialviewmaster";

        $result = $this->db->query($query)->result_array();

        return $result;
    }
    public function clearViewFeeds()
    {
        $this->db->truncate('socialviewmaster');
    }
    public function getLastMainFeed()
    {
        $query = "SELECT * FROM socialfeedmaster WHERE feedType = 0 ORDER BY id DESC LIMIT 1";

        $result = $this->db->query($query)->row_array();

        return $result;
    }

    public function getMoreLatestFeeds($count)
    {
        if($count == 0)
        {
            $query = "SELECT id,feedText FROM socialfeedmaster
                  WHERE feedType = 0 ORDER BY updateDateTime DESC LIMIT ".$count.",".($count+1);
        }
        else
        {
            $query = "SELECT id,feedText FROM socialfeedmaster
                  WHERE feedType = 0 ORDER BY updateDateTime DESC LIMIT ".$count.",".$count;
        }

        $result = $this->db->query($query)->row_array();

        return $result;
    }

    public function findCompletedEvents()
    {
        $query = "SELECT * "
            ."FROM eventmaster "
            ."where eventDate < CURRENT_DATE()";

        $result = $this->db->query($query)->result_array();
        return $result;
    }

    public function updateEventRegis($eventId)
    {
        $post['eventDone'] = '1';

        $this->db->where('eventId', $eventId);
        $this->db->update('eventregistermaster', $post);
        return true;
    }

    public function extendAutoEvent($eventId, $newDate)
    {
        $post['eventDate'] = $newDate;

        $this->db->where('eventId', $eventId);
        $this->db->update('eventmaster', $post);
        return true;
    }

    public function transferEventRecord($eventId)
    {
        $query = "INSERT INTO eventcompletedmaster "
            ."SELECT * FROM eventmaster "
            ."where eventId = ".$eventId;

        $this->db->query($query);

        $this->db->where('eventId', $eventId);
        $this->db->delete('eventmaster');
        return true;
    }
    public function insertWeeklyFeedback($post)
    {
        $this->db->insert('feedbackweekscore', $post);
        return true;
    }

    public function updateWeeklyFeedback($post,$id)
    {
        $this->db->where('id',$id);
        $this->db->update('feedbackweekscore', $post);
        return true;
    }

    public function getAllWeekly()
    {
        $query = "SELECT * FROM feedbackweekscore";
        $result = $this->db->query($query)->result_array();
        return $result;
    }
    public function getSingleLocFeedbacks($uptoDate)
    {
        $query = "SELECT DISTINCT (SELECT COUNT(overallRating) FROM usersfeedbackmaster 
                 WHERE feedbackLoc = 4 AND DATE(insertedDateTime) <= '".$uptoDate."') as 'total_overall',
                 (SELECT COUNT(overallRating) FROM usersfeedbackmaster 
                 WHERE feedbackLoc = 4 AND overallRating >= 9 AND DATE(insertedDateTime) <= '".$uptoDate."') as 'promo_overall',
                 (SELECT COUNT(overallRating) FROM usersfeedbackmaster 
                 WHERE feedbackLoc = 4 AND overallRating < 7 AND DATE(insertedDateTime) <= '".$uptoDate."') as 'de_overall'";

        $result = $this->db->query($query)->row_array();
        return $result;
    }

    public function updateSongs($restId, $post)
    {
        $post['updateDateTime'] = date('Y-m-d H:i:s');

        $this->db->where('tapId',$restId);
        $this->db->update('jukeboxmaster', $post);
        return true;
    }

    public function insertSongs($post)
    {
        $post['updateDateTime'] = date('Y-m-d H:i:s');

        $this->db->insert('jukeboxmaster', $post);
        return true;
    }

    public function checkTapSongs($resId)
    {
        $query = "SELECT * "
            ."FROM jukeboxmaster "
            ."where tapId = '".$resId."' ";

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

    function getAllActiveEmps()
    {
        $query = "SELECT id,empId,firstName,middleName,lastName,walletBalance,userType, mobNum, insertedDT
                    FROM `staffmaster` WHERE ifActive = 1 ";
        $result = $this->db->query($query)->result_array();
        return $result;
    }

    public function getWalletTrans($id, $startDate, $endDate)
    {
        $query = "SELECT wlm.amount, wlm.notes, wlm.loggedDT, wlm.updatedBy, sb.billNum, lm.locName"
            ." FROM walletlogmaster wlm"
            ." LEFT JOIN staffbillingmaster sb ON wlm.id = sb.walletId"
            ." LEFT JOIN locationmaster lm ON lm.id = sb.billLoc"
            ." WHERE wlm.amtAction = 1 AND wlm.staffId = ".$id." AND (DATE(wlm.loggedDT) >= '".$startDate."' AND DATE(wlm.loggedDT) <= '".$endDate."')"
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
}
