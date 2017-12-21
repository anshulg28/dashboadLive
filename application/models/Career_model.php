<?php

/**
 * Class Career_Model
 * @property Mydatafetch_library $mydatafetch_library
 * @property Generalfunction_library $generalfunction_library
 */
class Career_Model extends CI_Model
{
	function __construct()
	{
		parent::__construct();

        $this->load->library('mydatafetch_library');
	}

	function getAllCareers()
    {
        $query = "SELECT cm.*,lm.locName 
                  FROM careermaster cm 
                  LEFT JOIN locationmaster lm ON cm.locId = lm.id";

        $result = $this->db->query($query)->result_array();
        return $result;
    }

    function getCareerById($jobId)
    {
        $query = "SELECT cm.*,lm.locName 
                  FROM careermaster cm 
                  LEFT JOIN locationmaster lm ON cm.locId = lm.id 
                  WHERE cm.id = ".$jobId;

        $result = $this->db->query($query)->row_array();
        return $result;
    }

    public function saveCareerRecord($post)
    {
        $this->db->insert('careermaster', $post);
        return true;
    }

    public function updateCareerRecord($post,$jobId)
    {
        $this->db->where('id', $jobId);
        $this->db->update('careermaster', $post);
        return true;
    }
}
