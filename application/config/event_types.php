<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: anshul
 * Date: 8/29/2016
 * Time: 4:16 PM
 */

$config['login_ip_mob_map'] = array(
    '27.106.15.10' => '9975027683'
);

$config['insta_locationMap'] = array(
    '1020175853' => 'Andheri Taproom',
    '402256524' => 'Bandra Taproom',
    '1741740822733140' => 'Kemps Taproom'
);
$config['phons'] = array(
    'Anshul' => '8879103942',
    'Tresha' => '9820570311',
    'Priyanka' => '9920087855',
    'Daksha' => '9892110655',
    'Shweta' => '7045170006',
    'Richa' => '7678039911',
    'Belinda' => '9930415379',
    'Fn Com4' => '9999999999',
    'Fn Com1' => '9999999999',
    'Fn Com2' => '9999999999',
    'Fn Com3' => '9999999999'
);
$config['offerTimes'] = array(
    '7-10',
    '10-14',
    '14-18'
);
$config['eventTypes'] = array(
    "Presentation",
    "Meet up",
    "Pet Event",
    "Talk",
    "Discussion",
    "Exhibition",
    "Workshop",
    "Screening",
    "Book Club",
    "Others"
);

$config['ccList'] = array(
    "tresha@brewcraftsindia.com"
);
$config['eventTc'] = '<div class="page-content">
    <div class="content-block">
        <h6><strong>Timings:</strong></h6>
        <p><ol><li>Doolally is open all days of the week.</li>
            <li>On weekdays, events can be organised from 7 am to 6 pm.</li>
            <li>On weekends, events can be organised from 7 am to 2 pm</li></ol></p>

        <h6><strong>General rules:</strong></h6>
        <p><ol><li>All events have to be open for all.</li>
            <li>Events with corporate sponsorship are not allowed.</li>
            <li>Sale of any material is not permitted in the premises. Only the works of the performer(s)/artist(s) who are at the event can be sold.</li></ol></p>

        <h6><strong>Organizers are expected:</strong></h6>
        <p><ol><li>To set up and manage the ticket counter for any ticketed events.</li>
            <li>To chip in with the arrangements of the event.</li>
            <li>To have visited the venue prior to the event.</li>
            <li>To arrive at the venue at least 45 minutes before the scheduled time of the event.</li></ol></p>

        <h6><strong>Charges for utilization of the space:</strong></h6>
        <p><ol><li>If you don\'t charge, we don\'t charge.</li>
            <li>For paid events, we charge Rs '.NEW_DOOLALLY_FEE.' per attendee which includes a pint or house fries.</li>
            <li>The Rs '.NEW_DOOLALLY_FEE.' is a cover charge that Doolally levies in form of a voucher. The voucher, however, does not have to be redeemed on that day.</li></ol></p>

        <h6><strong>Payment:</strong></h6>
        <p><ol><li>For paid events, we will collect money from the customer on your behalf. This is to ensure complete refund in case of cancelled events.</li>
        <li>Instamojo is our payment partner. Please check out their credentials here - <a href="http://www.instamojo.com" class="external">www.instamojo.com</a></li>
        <li>Once your event is approved, we will create an Event/Instamojo link.</li>
        <li>Please use this link to accept payments.</li>
        <li>The transaction fee will be borne by Doolally.</li>
        <li>Your dashboard is a place where information on the number of sign ups, fees collected, payout details will be available to you. You can also edit your event or cancel your event from this dashboard.</li>
        <li>For events below 5000, organisers will be reimbursed by cash on the day of the event. For events greater than 5000, we will hand over a cheque in the name of organiser.</li></ol></p>

        <h6><strong>Contact:</strong></h6>
        <p>Email: events@brewcraftsindia.com</p>

    </div>
</div>';

$config['defaultRoles'] = array(
    ROOT_USER => array(
        'mug_portal',
        'mug_club',
        'mug_add',
        'mug_num_check',
        'mailers',
        'user_mgt',
        'location_mgt',
        'offers',
        'offers_check',
        'offers_gen',
        'offers_stats',
        'checkins',
        'dashboard_report',
        'dashboard_feedback',
        'dashboard_events',
        'dashboard_fnb',
        'dashboard_beerolympics',
        'wallet_check',
        'wallet_users',
        'twitter_page'
    ),
    ADMIN_USER => array(
        'mug_portal',
        'mug_club',
        'mug_add',
        'mug_num_check',
        'mailers',
        'location_mgt',
        'offers',
        'offers_check',
        'offers_gen',
        'offers_stats',
        'checkins',
        'dashboard_report',
        'dashboard_feedback',
        'dashboard_events',
        'dashboard_fnb',
        'dashboard_beerolympics',
        'wallet_check',
        'wallet_users'
    ),
    EXECUTIVE_USER => array(
        'mug_portal',
        'mug_club',
        'mug_add',
        'mug_num_check',
        'mailers',
        'offers',
        'offers_check',
        'offers_gen',
        'offers_stats',
        'checkins',
        'dashboard_report',
        'dashboard_feedback',
        'dashboard_events',
        'dashboard_fnb',
        'dashboard_beerolympics'
    ),
    SERVER_USER => array(
        'mug_club',
        'offers_check',
        'checkins',
        'wallet_check'
    ),
    WALLET_USER => array(
        'wallet_check',
        'wallet_users'
    ),
    OFFERS_USER => array(
        'offers',
        'offers_gen'
    ),

);