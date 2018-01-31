<?php

/**
 * Class Question_Model
 * @property Mydatafetch_library $mydatafetch_library
 * @property Generalfunction_library $generalfunction_library
 */
class Question_Model extends CI_Model
{
	function __construct()
	{
		parent::__construct();

        $this->load->library('mydatafetch_library');
	}

    function saveQuestion($details)
    {
        $this->db->insert('questionmaster',$details);
        $insertId = $this->db->insert_id();
        return $insertId;
    }
    function saveOptionsBatch($details)
    {
        $this->db->insert_batch('optionsmaster', $details);
        return true;
    }
}
