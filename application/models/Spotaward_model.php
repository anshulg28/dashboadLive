<?php

/**
 * Class Login_Model
 * @property Mydatafetch_library $mydatafetch_library
 * @property Generalfunction_library $generalfunction_library
 */
class Spotaward_Model extends CI_Model
{
	function __construct()
	{
		parent::__construct();

        $this->load->library('mydatafetch_library');
	}

	function getAllAwardRecords()
    {
        $query = "SELECT sam.empId,sam.empName,sdeg.dName as 'empDesignation',sdep.dName AS 'empDepartment',lm.locName,sam.reasonText, DATE_FORMAT(sam.awardDate, '%b %Y') AS 'awardDate',
                  DATE_FORMAT(sam.awardDate,'%c') AS 'awMonth', DATE_FORMAT(sam.awardDate,'%Y') AS 'awYear' 
                  FROM spotawardsmaster sam
                  LEFT JOIN locationmaster lm ON sam.empLocation = lm.id
                  LEFT JOIN staffdepartmentmaster sdep ON sam.empDepartment = sdep.id
                  LEFT JOIN staffdesignationmaster sdeg ON sam.empDesignation = sdeg.id
                  ORDER BY DATE(awardDate) DESC";

        $result = $this->db->query($query)->result_array();

        return $result;
    }
    function saveAwardBatch($details)
    {
        $this->db->insert_batch('spotawardsmaster',$details);
        return true;
    }
    function getAllDeparts()
    {
        $query = "SELECT * FROM staffdepartmentmaster";
        $result = $this->db->query($query)->result_array();
        return $result;
    }
    function searchDepart($depart)
    {
        $query = "SELECT * FROM staffdepartmentmaster WHERE LOWER(dName) LIKE LOWER('%".$depart."%')";
        $result = $this->db->query($query)->row_array();
        return $result;
    }
    function getAllDesignations()
    {
        $query = "SELECT * FROM staffdesignationmaster";
        $result = $this->db->query($query)->result_array();
        return $result;
    }
    function searchDesignations($depart)
    {
        $query = "SELECT * FROM staffdesignationmaster WHERE LOWER(dName) LIKE LOWER('%".$depart."%')";
        $result = $this->db->query($query)->row_array();
        return $result;
    }

}
