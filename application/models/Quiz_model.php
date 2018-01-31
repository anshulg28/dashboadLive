<?php

/**
 * Class Login_Model
 * @property Mydatafetch_library $mydatafetch_library
 * @property Generalfunction_library $generalfunction_library
 */
class Quiz_Model extends CI_Model
{
	function __construct()
	{
		parent::__construct();

        $this->load->library('mydatafetch_library');
	}

	function getAllDrawnNames()
    {
        $query = "SELECT qm.*,lm.locName
                  FROM quizmaster qm 
                  LEFT JOIN locationmaster lm ON qm.quizLoc = lm.id";

        $result = $this->db->query($query)->result_array();

        return $result;
    }

    function getAllStaffQuizData()
    {
        $query = "SELECT qm.*,lm.locName
                  FROM staffquizrecords qm 
                  LEFT JOIN locationmaster lm ON qm.quizLoc = lm.id";

        $result = $this->db->query($query)->result_array();

        return $result;
    }

    function getDrawnNamesByLoc($locId)
    {
        $query = "SELECT qm.*,lm.locName
                  FROM quizmaster qm 
                  LEFT JOIN locationmaster lm ON qm.quizLoc = lm.id
                  WHERE lm.id = ".$locId;

        $result = $this->db->query($query)->result_array();

        return $result;
    }

    function getLastDrawn()
    {
        $query = "SELECT * 
                  FROM quizplaymaster
                  WHERE DATE_FORMAT(dateDrawn,'%c')= DATE_FORMAT(CURRENT_DATE(),'%c')";

        $result = $this->db->query($query)->row_array();
        return $result;
    }

    function createQuizListFetchEntry($details)
    {
        $this->db->insert('quizplaymaster',$details);
        $insertId = $this->db->insert_id();
        return $insertId;
    }

    function delQuizEntry($qId)
    {
        $this->db->where('id', $qId);
        $this->db->delete('quizplaymaster');
        return true;
    }

    function getPreviousPlayers()
    {
        $query = "SELECT GROUP_CONCAT(empName) as 'empNames', GROUP_CONCAT(id) as 'empIds', GROUP_CONCAT(monthCount) as 'monthCounts',
                  GROUP_CONCAT(repeatedDates SEPARATOR ';') as 'repeatedDates' 
                  FROM staffquizrecords";

        $result = $this->db->query($query)->row_array();
        return $result;
    }

    function updatePlayerRecord($details,$empId)
    {
        $this->db->where('id',$empId);
        $this->db->update('staffquizrecords',$details);
        return true;
    }
    function saveQuizLog($details)
    {
        $this->db->insert('quizlogs',$details);
        return true;
    }
    function savePlayersBatch($details)
    {
        $this->db->insert_batch('quizmaster',$details);
        return true;
    }
    function updateQuizPlayRecord($details, $id)
    {
        $this->db->where('id',$id);
        $this->db->update('quizplaymaster',$details);
        return true;
    }

    function updateQuizMaster($details,$quizId)
    {
        $this->db->where('quizId',$quizId);
        $this->db->update('quizmaster',$details);
        return true;
    }

    function getQuizDetailById($quizId)
    {
        $query = "SELECT * FROM quizmaster WHERE quizId = ".$quizId;
        $result = $this->db->query($query)->row_array();
        return $result;
    }
    function saveAttempRecord($details)
    {
        $this->db->insert('attemptsmaster',$details);
        return true;
    }

    function updateAttemptRecord($details,$quizId)
    {
        $this->db->where('quizId', $quizId);
        $this->db->update('attemptsmaster',$details);
        return true;
    }

    function getRandomQuizQuestions($quizLvl)
    {
        $this->db->simple_query('SET SESSION group_concat_max_len=1000000');
        $query = "SELECT qm.qid,TRIM(qm.questionText) as 'questionText', TRIM(qcm.categoryName) as 'categoryName', GROUP_CONCAT(TRIM(om.optionText) SEPARATOR ';') as 'optionText',
                  GROUP_CONCAT(TRIM(om.isCorrectOption) SEPARATOR ';') as 'isCorrectOption'
                    FROM `questionmaster` qm
                    LEFT JOIN qcategorymaster qcm ON qm.questionCat = qcm.catid
                    LEFT JOIN optionsmaster om ON qm.qid = om.qid
                    WHERE qm.questionLvl = ".$quizLvl."
                    GROUP BY qm.qid
                    ORDER BY RAND() LIMIT 10";
        $result = $this->db->query($query)->result_array();
        return $result;
    }

    function getQuizStaffById($id)
    {
        $query = "SELECT * FROM staffquizrecords WHERE id = ".$id;
        $result = $this->db->query($query)->row_array();
        return $result;
    }

    function saveStaffQuizBatch($details)
    {
        $this->db->insert_batch('staffquizrecords',$details);
        return true;
    }
    public function clearQuizMaster()
    {
        $this->db->truncate('quizmaster');
    }

    function getAllQuestions()
    {
        $this->db->simple_query('SET SESSION group_concat_max_len=1000000');
        $query = "SELECT qm.qid,TRIM(qm.questionText) as 'questionText', TRIM(qcm.categoryName) as 'categoryName', GROUP_CONCAT(TRIM(om.optionText) SEPARATOR ';') as 'optionText',
                  GROUP_CONCAT(TRIM(om.isCorrectOption) SEPARATOR ';') as 'isCorrectOption', qm.insertedDT
                    FROM `questionmaster` qm
                    LEFT JOIN qcategorymaster qcm ON qm.questionCat = qcm.catid
                    LEFT JOIN optionsmaster om ON qm.qid = om.qid 
                    GROUP BY qm.qid";
        $result = $this->db->query($query)->result_array();
        return $result;
    }

    function getSingleQuestion($qid)
    {
        $this->db->simple_query('SET SESSION group_concat_max_len=1000000');
        $query = "SELECT qm.qid,TRIM(qm.questionText) as 'questionText', TRIM(qcm.categoryName) as 'categoryName', qm.questionCat
                  , qm.questionLvl, GROUP_CONCAT(TRIM(om.optionText) SEPARATOR ';') as 'optionText',
                  GROUP_CONCAT(TRIM(om.isCorrectOption) SEPARATOR ';') as 'isCorrectOption',
                   GROUP_CONCAT(TRIM(om.opid) SEPARATOR ';') as 'optionIds', qm.insertedDT
                    FROM `questionmaster` qm
                    LEFT JOIN qcategorymaster qcm ON qm.questionCat = qcm.catid
                    LEFT JOIN optionsmaster om ON qm.qid = om.qid 
                    WHERE qm.qid = ".$qid." 
                    GROUP BY qm.qid";
        $result = $this->db->query($query)->row_array();
        return $result;
    }

    function getAllQCats()
    {
        $query = "SELECT * FROM qcategorymaster";
        $result = $this->db->query($query)->result_array();
        return $result;
    }
    function updateQuestionRecord($details,$qId)
    {
        $this->db->where('qid', $qId);
        $this->db->update('questionmaster',$details);
        return true;
    }

    function updateResetOptionsByQId($details,$qId)
    {
        $this->db->where('qid', $qId);
        $this->db->update('optionsmaster',$details);
        return true;
    }

    function updateOptions($details,$opId)
    {
        $this->db->where('opid', $opId);
        $this->db->update('optionsmaster',$details);
        return true;
    }
    function delQuestion($qid)
    {
        $this->db->where('qid', $qid);
        $this->db->delete('questionmaster');
        $this->db->where('qid', $qid);
        $this->db->delete('optionsmaster');
        return true;
    }

    function saveQuestionRecord($details)
    {
        $this->db->insert('questionmaster',$details);
        $insertId = $this->db->insert_id();
        return $insertId;
    }

    function saveOptionsBatch($details)
    {
        $this->db->insert_batch('optionsmaster',$details);
        return true;
    }
}
