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
        $query = "SELECT * FROM spotawardsmaster";

        $result = $this->db->query($query)->result_array();

        return $result;
    }

}
