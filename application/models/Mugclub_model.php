<?php

/**
 * Class Login_Model
 * @property Mydatafetch_library $mydatafetch_library
 * @property Generalfunction_library $generalfunction_library
 */
class Mugclub_Model extends CI_Model
{
	function __construct()
	{
		parent::__construct();

        $this->load->library('mydatafetch_library');
	}

    public function getAllMugClubList()
    {
        $query = "SELECT mugId,mugTag,homeBase,l.locName,firstName,lastName,mobileNo, emailId, birthDate, membershipStart, membershipEnd,notes "
            ."FROM mugmaster m "
            ."LEFT JOIN locationmaster l ON id = m.homeBase";

        $result = $this->db->query($query)->result_array();

        $data['mugList'] = $result;
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

    public function getCheckInMugClubList()
    {
        $query = "SELECT mugId,mugTag,homeBase,l.locName,firstName,lastName,mobileNo, emailId, birthDate, membershipEnd "
                ."FROM mugmaster m "
                ."LEFT JOIN locationmaster l ON id = m.homeBase";

        $result = $this->db->query($query)->result_array();

        $data['mugList'] = $result;
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

    public function getAllMugsCount($locations)
    {
        $query = "SELECT DISTINCT (SELECT count(*) FROM mugmaster WHERE membershipStart <= (CURRENT_DATE() - INTERVAL 1 MONTH)) as oldOverall,
                  (SELECT count(*) FROM mugmaster WHERE membershipStart <= CURRENT_DATE()) as newOverall";

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
                    $lbl = explode('-',$row['locUniqueLink'])[0];
                    $query .= "(SELECT count(*) FROM mugmaster WHERE homeBase = ".$row['id']."
                                AND membershipStart <= (CURRENT_DATE() - INTERVAL 1 MONTH)) as old".ucfirst($lbl).",";
                    $query .= "(SELECT count(*) FROM mugmaster WHERE homeBase = ".$row['id']."
                                AND membershipStart <= CURRENT_DATE()) as new".ucfirst($lbl);

                }
            }
        }
        $query .= " FROM mugmaster";
        $result = $this->db->query($query)->row_array();

        $avgMugs['overall'] = ((int)$result['newOverall']+(int)$result['oldOverall'])/2;
        if(isset($locations))
        {
            $length = count($locations)-1;
            $counter = 0;
            foreach($locations as $key => $row)
            {
                if(isset($row['id']))
                {
                    $lbl = explode('-',$row['locUniqueLink'])[0];
                    $avgMugs[$lbl] = ((int)$result['new'.ucfirst($lbl)]+(int)$result['old'.ucfirst($lbl)])/2;

                }
            }
        }
        /*$avgMugs['bandra'] =
        $avgMugs['andheri'] = ((int)$result['newAndheri']+(int)$result['oldAndheri'])/2;
        $avgMugs['kemps'] = ((int)$result['newKemps']+(int)$result['oldKemps'])/2;
        $avgMugs['colaba'] = ((int)$result['newColaba']+(int)$result['oldColaba'])/2;*/

        $data = $avgMugs;
        return $data;

    }
    public function getMugDataById($mugId)
    {
        $query = "SELECT mugId,mugTag,homeBase,l.locName,firstName,lastName,mobileNo, emailId, birthDate, membershipEnd,"
            ."invoiceDate,invoiceNo,invoiceAmt,membershipStart,oldHomeBase,m.ifActive,notes,om.isRedeemed,om.ifActive "
            ."FROM mugmaster m "
            ."LEFT JOIN locationmaster l ON id = m.homeBase "
            ."LEFT JOIN offersmaster om ON m.mugId = om.offerMug"
            ." Where mugId = ".$mugId;

        $result = $this->db->query($query)->result_array();

        $data['mugList'] = $result;
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

    public function getMugHoldById($mugId)
    {
        $query = "SELECT * "
            ."FROM holdmugmaster "
            ."where mugId = ".$mugId;

        $result = $this->db->query($query)->result_array();

        $data['mugList'] = $result;
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
    public function getAllMugHolds()
    {
        $query = "SELECT mugId "
            ."FROM holdmugmaster ";

        $result = $this->db->query($query)->result_array();

        $data['mugList'] = $result;
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

    public function getMugIdForRenew($mugId)
    {
        $query = "SELECT emailId, firstName, invoiceDate, invoiceNo, membershipStart, membershipEnd "
            ."FROM mugmaster "
            ."where mugId = ".$mugId;

        $result = $this->db->query($query)->row_array();
        $data = $result;

        return $data;
    }

    public function getMugEndDateById($mugId)
    {
        $query = "SELECT membershipEnd "
            ."FROM mugmaster "
            ."where mugId = ".$mugId;

        $result = $this->db->query($query)->result_array();

        $data['mugList'] = $result;
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
    public function setMugRenew($post)
    {
        $this->db->where('mugId', $post['mugId']);
        $this->db->update('mugmaster', $post);
        return true;
    }
    public function getMugDataForMailById($mugId)
    {
        $query = "SELECT mugId, firstName, lastName, mobileNo, emailId, birthDate, membershipEnd "
            ."FROM mugmaster "
            ."where mugId = ".$mugId;

        $result = $this->db->query($query)->result_array();

        $data['mugList'] = $result;
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

    public function getMugRange($mugId, $rangeEnd)
    {

        if($mugId < $rangeEnd)
        {
            $query = "SELECT mugId FROM mugmaster"
                ." WHERE mugId BETWEEN ".$mugId." AND ".$rangeEnd;
        }
        else
        {
            $query = "SELECT mugId FROM mugmaster"
                ." WHERE mugId BETWEEN ".$rangeEnd." AND ".$mugId;
        }

        $result = $this->db->query($query)->result_array();

        if(myIsArray($result))
        {
            $numArray = array();
            foreach($result as $key)
            {
                $numArray[] = $key['mugId'];
            }

            return $numArray;
        }
        else
        {
            return $result;
        }

    }

    public function verifyMobileNo($mobNo)
    {
        $query = "SELECT mugId,mugTag,homeBase,l.locName,firstName,lastName,mobileNo, emailId, birthDate, membershipEnd "
            ."FROM mugmaster m "
            ."LEFT JOIN locationmaster l ON id = m.homeBase"
            ." Where mobileNo = '".$mobNo."'";

        $result = $this->db->query($query)->row_array();

        $data['mugList'] = $result;
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
    public function filterMugParameters($post, $invalidKeys)
    {
        if(myIsArray($post))
        {
            $parameter = array();

            foreach ($post as $key => $row)
            {
                if ($row != '' && !myInArray($key,$invalidKeys))
                {
                    switch ($key)
                    {
                        case 'mugNum':
                            $parameter['mugId'] = $row;
                            break;

                        case 'baseLocation':
                            $parameter['homeBase'] = $row;
                            break;

                        case 'mobNum':
                            $parameter['mobileNo'] = $row;
                            break;

                        case 'birthdate':
                            $parameter['birthDate'] = $row;
                            break;

                        case 'memberS':
                            $parameter['membershipStart'] = $row;
                            break;

                        case 'memberE':
                            $parameter['membershipEnd'] = $row;
                            break;

                        case 'mugNotes':
                            $parameter['notes'] = $row;
                            break;

                        default:
                            $parameter[$key] = $row;
                            break;
                    }
                }
            }

            return $parameter;
        }
        else
        {
            return false;
        }
    }

    public function saveMugRecord($post)
    {
        if(isset($post['birthDate']))
        {
            $post['birthDate'] = date('Y-m-d', strtotime($post['birthDate']));
        }

        if(isset($post['invoiceDate']))
        {
            $post['invoiceDate'] = date('Y-m-d', strtotime($post['invoiceDate']));
        }

        if(isset($post['membershipStart']))
        {
            $post['membershipStart'] = date('Y-m-d', strtotime($post['membershipStart']));
        }

        if(isset($post['membershipEnd']))
        {
            $post['membershipEnd'] = date('Y-m-d', strtotime($post['membershipEnd']));
        }

        if(isset($post['notes']))
        {
            $post['notes'] = trim($post['notes']);
        }
        $post['ifActive'] = '1';

        $this->db->insert('mugmaster', $post);
        return true;
    }

    public function updateMugRecord($post, $mugNum = '')
    {
        if(isset($post['birthDate']))
        {
            $post['birthDate'] = date('Y-m-d', strtotime($post['birthDate']));
        }

        if(isset($post['invoiceDate']))
        {
            $post['invoiceDate'] = date('Y-m-d', strtotime($post['invoiceDate']));
        }

        if(isset($post['membershipStart']))
        {
            $post['membershipStart'] = date('Y-m-d', strtotime($post['membershipStart']));
        }

        if(isset($post['membershipEnd']))
        {
            $post['membershipEnd'] = date('Y-m-d', strtotime($post['membershipEnd']));
        }

        if(isset($post['notes']))
        {
            $post['notes'] = trim($post['notes']);
        }

        $post['ifActive'] = '1';

        if(isset($mugNum) && isStringSet($mugNum))
        {
            $this->db->where('mugId', $mugNum);
        }
        else
        {
            $this->db->where('mugId', $post['mugId']);
        }
        $this->db->update('mugmaster', $post);
        return true;
    }
    public function deleteMugRecord($mugId)
    {
        $query = "INSERT INTO deletedmugmaster "
            ."SELECT * FROM mugmaster "
            ."where mugId = ".$mugId;

        $this->db->query($query);

        $this->db->where('mugId', $mugId);
        $this->db->delete('mugmaster');
        return true;
    }
    public function holdMugRecord($mugId)
    {
        $query = "INSERT INTO holdmugmaster "
            ."SELECT * FROM mugmaster "
            ."where mugId = ".$mugId;

        $this->db->query($query);

        $this->db->where('mugId', $mugId);
        $this->db->delete('mugmaster');
        return true;
    }
    public function extendMemberShip($mugId, $newDate)
    {
        $this->db->where('mugId', $mugId);
        $this->db->update('mugmaster',$newDate);
        return true;
    }

    public function getExpiringMugsList($intervalNum, $intervalSpan,$locSort = false, $locArray = '')
    {

        $timeInterval = 'DAY';

        switch(strtolower($intervalSpan))
        {
            case 'day':
                $timeInterval = 'DAY';
                break;
            case 'week':
                $timeInterval = 'WEEK';
                break;
            case 'month':
                $timeInterval = 'MONTH';
                break;
            case 'year':
                $timeInterval = 'YEAR';
                break;
        }

        $query = "SELECT mugId, firstName, emailId"
                ." FROM mugmaster "
                ."WHERE membershipEnd IS NOT NULL AND membershipEnd != '0000-00-00' AND mailStatus = 0 "
                ."AND membershipEnd = (CURRENT_DATE() + INTERVAL ".$intervalNum." ".$timeInterval.")";

        if($locSort === true)
        {
            $query .= ' AND homeBase IN('.$locArray.')';
        }

        $result = $this->db->query($query)->result_array();

        $data['expiryMugList'] = $result;
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

    public function getExpiredMugsList($locSort = false, $locArray = '')
    {
        $query = "SELECT mugId, firstName, emailId"
            ." FROM mugmaster "
            ."WHERE membershipEnd IS NOT NULL AND membershipEnd != '0000-00-00' "
            ."AND membershipEnd <= CURRENT_DATE() AND mailStatus = 0";

        if($locSort === true)
        {
            $query .= ' AND homeBase IN('.$locArray.')';
        }

        $result = $this->db->query($query)->result_array();

        $data['expiryMugList'] = $result;
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

    public function getBirthdayMugsList($locSort = false, $locArray = '')
    {
        $query = "SELECT mugId, firstName, emailId, birthDate "
            ." FROM mugmaster "
            ."WHERE birthDate IS NOT NULL AND birthDate != '0000-00-00' AND membershipEnd >= CURRENT_DATE() "
            ."AND DATE_FORMAT(birthDate,'%m-%d') BETWEEN DATE_FORMAT( (CURRENT_DATE() - INTERVAL 1 WEEK ) , '%m-%d')"
            ." AND DATE_FORMAT( CURRENT_DATE() , '%m-%d' ) AND birthdayMailStatus = 0 "
            ."AND CONCAT( YEAR( CURRENT_DATE( ) ) ,  '-', MONTH( birthDate ) ,  '-', DAY( birthDate ) ) > membershipStart"
            ." AND CONCAT( YEAR( CURRENT_DATE( ) ) ,  '-', MONTH( birthDate ) ,  '-', DAY( birthDate ) ) <= membershipEnd";
        if($locSort === true)
        {
            $query .= ' AND homeBase IN('.$locArray.')';
        }
        $query .= " ORDER BY DATE_FORMAT( birthDate, '%m-%d' ) DESC ";

        $result = $this->db->query($query)->result_array();

        $data['expiryMugList'] = $result;
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

    public function saveRenewRecord($post)
    {
        $this->db->insert('mugrenewmaster', $post);
        return true;
    }

    public function fetchLocIdByMob($userId)
    {
        $query = "SELECT lm.id
                  FROM doolally_usersmaster du
                  LEFT JOIN locationmaster lm ON lm.phoneNumber = du.mobNum
                  WHERE du.userId = ".$userId;

        $result  = $this->db->query($query)->row_array();

        return $result;
    }

    public function getInstaMugById($id)
    {
        $query = "SELECT * FROM instamojomugmaster WHERE id= ".$id;

        $result = $this->db->query($query)->row_array();
        return $result;
    }

    public function updateInstaMug($details,$id)
    {
        $this->db->where('id', $id);
        $this->db->update('instamojomugmaster', $details);
        return true;
    }

    public function getDummyMugDataForMail($mugId)
    {
        $query = "SELECT mugId, firstName, lastName, mobileNo, emailId, birthDate, membershipEnd "
            ."FROM dummymugmaster "
            ."where mugId = ".$mugId;

        $result = $this->db->query($query)->result_array();

        $data['mugList'] = $result;
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
