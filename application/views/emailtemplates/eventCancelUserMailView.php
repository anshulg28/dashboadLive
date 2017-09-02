<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0056)file:///C:/Users/user1/Desktop/Emails/welcome-email.html -->
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>Event Canceled</title>
</head>

<body>
<p>Dear <?php echo trim($mailData[0]['creatorName'])?>,</p>
<p>
    Your event <?php echo $mailData[0]['eventName'];?> has been cancelled. Fees collected will be refunded to participants within 7 days.
    However, the payment gateway fee of 2.24 % per attendee will be borne by you.<br><br>

    We will deduct this from the next workshop/activity you do with us.<br><br>
    In case you have any questions/queries please don't hesitate to write to me at this (<?php echo $mailData['senderEmail'];?>) mail address.
    <!--For queries, contact <?php /*echo $mailData['senderPhone'] .' ('.$mailData['senderName'].')';*/?><br><br>-->

    Thanks,<br>
    <?php echo ucfirst($mailData['senderName']); ?>, Doolally
</p>

</body>
</html>