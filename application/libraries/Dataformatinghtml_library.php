<?php defined('BASEPATH') or exit('No direct script access allowed');

class Dataformatinghtml_library
{
    private $CI;

    function __construct()
    {
        $this->CI = &get_instance();
    }

    public function getGlobalStyleHtml($data)
    {
        $htmlPage = $this->CI->load->view('common/GlobalstyleView', $data, true);
        return $htmlPage;
    }
    public function getMobileStyleHtml($data)
    {
        $htmlPage = $this->CI->load->view('common/MobilestyleView', $data, true);
        return $htmlPage;
    }
    public function getAndroidStyleHtml($data)
    {
        $htmlPage = $this->CI->load->view('mobile/android/AndroidstyleView', $data, true);
        return $htmlPage;
    }
    public function getAndroidJsHtml($data)
    {
        $htmlPage = $this->CI->load->view('mobile/android/AndroidjsView', $data, true);
        return $htmlPage;
    }
    public function getIosStyleHtml($data)
    {
        $htmlPage = $this->CI->load->view('mobile/ios/IosstyleView', $data, true);
        return $htmlPage;
    }
    public function getIosJsHtml($data)
    {
        $htmlPage = $this->CI->load->view('mobile/ios/IosjsView', $data, true);
        return $htmlPage;
    }

    public function getGlobalJsHtml($data)
    {
        $htmlPage = $this->CI->load->view('common/GlobaljsView', $data, true);
        return $htmlPage;
    }
    public function getMobileJsHtml($data)
    {
        $htmlPage = $this->CI->load->view('common/MobilejsView', $data, true);
        return $htmlPage;
    }
    public function getHeaderHtml($data)
    {
        $this->CI->load->model('login_model');
        $this->CI->load->model('locations_model');
        if(isSessionVariableSet($this->CI->userId))
        {
            $usrData = $this->CI->login_model->getUserById($this->CI->userId);
            $data['userActive'] = $usrData['ifActive'];
            $rols = $this->CI->login_model->getUserRoles($this->CI->userId);
            $data['userModules'] = explode(',',$rols['modulesAssigned']);
            if($this->CI->userType == EXECUTIVE_USER)
            {
                if(!is_null($this->CI->commSecLoc) && isSessionVariableSet($this->CI->commSecLoc))
                {
                    $locations = $this->CI->locations_model->getLocationDetailsById($this->CI->commSecLoc);
                    $data['locInfo'] = $locations['locData'];
                }
                /*else
                {
                    redirect(base_url().'dashboard/setCommLoc');
                }*/
            }
        }

        $htmlPage = $this->CI->load->view('HeaderView', $data, true);
        return $htmlPage;
    }
    public function getFooterHtml($data)
    {
        $htmlPage = $this->CI->load->view('FooterView', $data, true);
        return $htmlPage;
    }
}
/* End of file */