<?php

/**
 * Class Assets_Model
 * @property Mydatafetch_library $mydatafetch_library
 * @property Generalfunction_library $generalfunction_library
 */
class Assets_Model extends CI_Model
{
	function __construct()
	{
		parent::__construct();

        $this->load->library('mydatafetch_library');
	}

	function getAllAssets()
    {
        $query = "SELECT am.*,lm.locName
                  FROM doolallyassetsmaster am 
                  LEFT JOIN locationmaster lm ON am.locId = lm.id";

        $result = $this->db->query($query)->result_array();

        return $result;
    }

    function saveAsset($details)
    {
        $this->db->insert('doolallyassetsmaster', $details);
        return true;
    }
    function updateAsset($details, $aid)
    {
        $this->db->where('aId',$aid);
        $this->db->update('doolallyassetsmaster', $details);
    }
    function getAssetsById($aId)
    {
        $query = "SELECT am.*,lm.locName
                  FROM doolallyassetsmaster am 
                  LEFT JOIN locationmaster lm ON am.locId = lm.id
                   WHERE am.aId = ".$aId;

        $result = $this->db->query($query)->row_array();

        return $result;
    }
}
