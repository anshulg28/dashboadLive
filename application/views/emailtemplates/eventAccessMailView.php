<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0056)file:///C:/Users/user1/Desktop/Emails/welcome-email.html -->
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

</head>

<body>
    <p>
        Event Name: "<?php echo $mailData['eventName'];?>"<br>
        Event Place: <?php echo $mailData['locName'];?><br>
        Event Date/Time: <?php $d = date_create($mailData['eventDate'].' '.$mailData['startTime']); echo date_format($d,DATE_TIME_FORMAT_UI);?>
    </p>

</body>
</html>