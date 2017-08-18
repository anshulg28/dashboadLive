<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0056)file:///C:/Users/user1/Desktop/Emails/welcome-email.html -->
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>Event Decline</title>
</head>

<body>
    <p>Dear <?php echo trim($mailData[0]['creatorName']); ?>,</p>
    <p>
        Your event has been disapproved because the timings you have selected are not available.
        You can either change your time slot or choose another taproom.<br><br>

        <!--You can reach out to me on <?php /*echo $mailData['senderPhone'] .' ('.ucfirst($mailData['senderName']).')';*/?> and I will help you schedule it better.<br><br>-->

        Thanks!<br>
        <?php echo ucfirst($mailData['senderName']); ?>
    </p>

</body>
</html>