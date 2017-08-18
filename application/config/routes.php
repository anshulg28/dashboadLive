<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'home';
$route['404_override'] = 'Page404';
$route['translate_uri_dashes'] = FALSE;
$route['mugclub/add'] = 'mugclub/addNewMug';
$route['mugclub/edit/(:any)'] = 'mugclub/editExistingMug/$1';
$route['mugclub/save'] = 'mugclub/saveOrUpdateMug';
$route['mugclub/delete/(:any)'] = 'mugclub/deleteMugData/$1';
$route['mugclub/hold/(:any)'] = 'mugclub/holdMugData/$1';
$route['mugclub/check'] = 'mugclub/mugAvail';
$route['check-ins'] = 'checkin';
$route['check-ins/add'] = 'checkin/addNewCheckIn';
$route['check-ins/edit/(:any)'] = 'checkin/editExistingCheckin/$1';
$route['check-ins/save/(:any)'] = 'checkin/saveOrUpdateCheckIn/$1';
$route['check-ins/delete/(:any)'] = 'checkin/deleteMugCheckIn/$1';
$route['check-ins/verify'] = 'checkin/verifyCheckIn';
$route['location-select'] = 'home/getLocation';
$route['login/settings'] = 'login/changeSetting';
$route['users/edit/(:any)'] = 'users/editExistingUser/$1';
$route['users/save'] = 'users/saveOrUpdateUser';
$route['users/add'] = 'users/addNewUser';
$route['users/delete/(:any)'] = 'users/deleteUserData/$1';
$route['users/setActive/(:any)'] = 'users/setUserActive/$1';
$route['users/setDeActive/(:any)'] = 'users/setUserDeActive/$1';
$route['locations/edit/(:any)'] = 'locations/editExistingUser/$1';
$route['locations/save'] = 'locations/saveOrUpdateLocation';
$route['locations/add'] = 'locations/addNewLocation';
$route['mugclub/ajaxSave'] = 'mugclub/ajaxMugUpdate';
$route['mailers/send/(:any)'] = 'mailers/sendMail/$1';
$route['mailers/add'] = 'mailers/showPressMailAdd';
$route['mailers/edit/(:any)'] = 'mailers/showPressMailEdit/$1';
$route['mailers/delete/(:any)'] = 'mailers/removePressEmail/$1';
$route['mugclub/renew/(:any)'] = 'mugclub/renewExistingMug/$1';
$route['dashboard/custom'] = 'dashboard/getCustomStats';
$route['dashboard/save'] = 'dashboard/saveRecord';
//$route['dashboard/newmember'] = 'dashboard/instaMojoNewMember';
$route['dashboard/instamojo'] = 'dashboard/instaMojoRecord';
$route['dashboard/instadone/(:any)/(:any)'] = 'dashboard/setInstamojoDone/$1/$2';
$route['dashboard/approve/(:any)'] = 'dashboard/eventApproved/$1';
$route['dashboard/decline/(:any)'] = 'dashboard/eventDeclined/$1';
$route['main'] = 'home/main';

$route['updateStaff'] = 'home/updateStaff';
$route['walletManage/(:any)'] = 'home/walletManage/$1';
$route['check'] = 'home/checkWallet';
$route['checkinStaff'] = 'home/checkinStaff';
$route['clearBill/(:any)'] = 'home/clearBill/$1';
$route['staffBill'] = 'home/staffBill';
$route['getCoupon'] = 'home/getCoupon';
$route['edit/(:any)'] = 'home/staffEdit/$1';
$route['add'] = 'home/addStaff';
$route['wallet'] = 'home/checkWallet';
$route['empDetails'] = 'home/empDetails';
$route['getWallet'] = 'home/getWallet';
$route['saveStaff'] = 'home/saveStaff';
$route['updateWallet/(:any)'] = 'home/updateWallet/$1';
$route['blockStaff/(:any)'] = 'home/blockStaff/$1';
$route['freeStaff/(:any)'] = 'home/freeStaff/$1';
$route['generateOtp'] = 'home/sendOtp';
$route['getOtp'] = 'login/sendNormalOtp';
//$route['share-event/(:any)/(:any)'] = 'home/eventFetch/$1/$2';
$route['twitterPage'] = 'dashboard/twitterStuff';

/* Mobile Routes */
$route['mobile'] = 'page404';
$route['mobile/about'] = 'mobile/main/about';
$route['events/(:any)/(:any)'] = 'mobile/main/eventFetch/$1/$2';
$route['eventEdit/(:any)/(:any)'] = 'mobile/main/editEvent/$1/$2';
$route['create_event'] = 'mobile/main/createEvent';
$route['event_dash'] = 'mobile/main/myEvents';
$route['contact_us'] = 'mobile/main/contactUs';
$route['jukebox'] = 'mobile/main/jukeBox';
$route['taproom/(:any)'] = 'mobile/main/taproomInfo/$1';
$route['event_details/(:any)/(:any)'] = 'mobile/main/eventDetails/$1/$2';
$route['signup_list/(:any)/(:any)'] = 'mobile/main/signupList/$1/$2';
//$route['thankYou/(:any)'] = 'mobile/main/thankYou/$1';
$route['mobile/renderLink'] = 'mobile/main/renderLink';
$route['saveEvent'] = 'mobile/main/saveEvent';
$route['updateEvent'] = 'mobile/main/updateEvent';
$route['checkEventSpace'] = 'mobile/main/checkEventSpace';