<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0056)file:///C:/Users/user1/Desktop/Emails/welcome-email.html -->
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>Welcome Member</title>
</head>

<body>
    <p>Dear <?php echo trim(ucfirst($mailData['creatorName']));?>,</p>
    <p>Welcome To Doolally.<br><br>
        We have created your account, here are the details: <br><br>

        Username: <?php echo $mailData['creatorEmail'];?><br>
        Password: Your Mobile Number<br><br>

        You can access your events from the <a href="<?php echo base_url().'?page/event_dash';?>" target="_blank">My Events</a> section. This is a place where information on date, timings, organiser will be available to you. You can also cancel your attendance from this dashboard.<br><br>

        Cheers!<br>
        Doolally
    </p>

</body>
</html>