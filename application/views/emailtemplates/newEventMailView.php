<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0056)file:///C:/Users/user1/Desktop/Emails/welcome-email.html -->
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>Event Creation</title>
</head>

<body>
    <p>Dear <?php echo trim(ucfirst($mailData['creatorName']));?>,</p>
    <p>Thanks for creating <?php echo $mailData['eventName'];?> at Doolally, please give us a few days to approve your event. Once your event is approved, you will receive an email from us and will be given access to an event dashboard. <br><br>

        <?php
            if(isset($mailData['creatorPhone']))
            {
                ?>
        Since this is the first time you have created an event, here are your login details<br>
        Login: <?php echo $mailData['creatorEmail'];?><br>
        Password: <?php echo $mailData['creatorPhone'];?><br><br>
                <?php
            }
        ?>
        In case you have any questions/queries please don't hesitate to write to me at this mail address<!-- or you can reach me at
        --><?php /*echo $mailData['senderPhone'] .' ('.$mailData['senderName'].')';*/?><br><br>

        Cheers!<br>
        <?php echo ucfirst($mailData['senderName']);?>
    </p>

</body>
</html>