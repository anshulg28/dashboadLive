<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0056)file:///C:/Users/user1/Desktop/Emails/welcome-email.html -->
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>Event Decline</title>
</head>

<body>
    <p>Hi <?php echo trim($mailData['creatorName']); ?>,</p>
    <p>
        Your event modification<br>
        <?php
        if(isset($mailData['changeRecord']['imgAttachment']))
        {
            $attachment = explode(';#;',$mailData['changeRecord']['imgAttachment']);
            ?>
            Old Event Image:<br>
            <img src="<?php echo MOBILE_URL.EVENT_PATH_THUMB.$attachment[0];?>" alt="Old Image"/><br><br>
            New Event Image:<br>
            <img src="<?php echo MOBILE_URL.EVENT_PATH_THUMB.$attachment[1];?>" alt="New Image"/><br><br>
            <?php
        }
        if(isset($mailData['changeRecord']['eventName']))
        {
            $temp = explode(';#;',$mailData['changeRecord']['eventName']);
            ?>
            <b>Event Name</b>:<br> From: <?php echo $temp[0];?><br><br>
            To: <?php echo $temp[1];?><br><br>
            <?php
        }
        if(isset($mailData['changeRecord']['eventDescription']))
        {
        $temp = explode(';#;',$mailData['changeRecord']['eventDescription']);
        ?>
        <b>Event Description</b>:<br> From: <?php echo $temp[0];?><br><br>
         To: <?php echo $temp[1];?><br><br>
        <?php
        }
        if(isset($mailData['changeRecord']['eventDate']))
        {
            $temp = explode(';#;',$mailData['changeRecord']['eventDate']);
            ?>
            <b>Event Date</b>:<br> From: <?php echo $temp[0];?><br><br>
            To: <?php echo $temp[1];?><br><br>
            <?php
        }
        if(isset($mailData['changeRecord']['startTime']))
        {
            $temp = explode(';#;',$mailData['changeRecord']['startTime']);
            ?>
            <b>Event Start Time</b>:<br> From: <?php echo $temp[0];?><br><br>
            To: <?php echo $temp[1];?><br><br>
            <?php
        }
        if(isset($mailData['changeRecord']['endTime']))
        {
            $temp = explode(';#;',$mailData['changeRecord']['endTime']);
            ?>
            <b>Event End Time</b>:<br> From: <?php echo $temp[0];?><br><br>
            To: <?php echo $temp[1];?><br><br>
            <?php
        }
        if(isset($mailData['changeRecord']['eventCapacity']))
        {
            $temp = explode(';#;',$mailData['changeRecord']['eventCapacity']);
            ?>
            <b>Event Capacity</b>:<br> From: <?php echo $temp[0];?><br><br>
            To: <?php echo $temp[1];?><br><br>
            <?php
        }

        if(isset($mailData['changeRecord']['ifMicRequired']))
        {
            $temp = explode(';#;',$mailData['changeRecord']['ifMicRequired']);
            ?>
            <b>Mic Required?</b>:<br> From: <?php echo $temp[0];?><br><br>
            To: <?php echo $temp[1];?><br><br>
            <?php
        }
        if(isset($mailData['changeRecord']['ifProjectorRequired']))
        {
            $temp = explode(';#;',$mailData['changeRecord']['ifProjectorRequired']);
            ?>
            <b>Projector Required?</b>:<br> From: <?php echo $temp[0];?><br><br>
            To: <?php echo $temp[1];?><br><br>
            <?php
        }
        if(isset($mailData['changeRecord']['creatorName']))
        {
            $temp = explode(';#;',$mailData['changeRecord']['creatorName']);
            ?>
            <b>Organiser Name</b>:<br> From: <?php echo $temp[0];?><br><br>
            To: <?php echo $temp[1];?><br><br>
            <?php
        }
        if(isset($mailData['changeRecord']['creatorPhone']))
        {
            $temp = explode(';#;',$mailData['changeRecord']['creatorPhone']);
            ?>
            <b>Organiser Phone</b>:<br> From: <?php echo $temp[0];?><br><br>
            To: <?php echo $temp[1];?><br><br>
            <?php
        }
        if(isset($mailData['changeRecord']['creatorEmail']))
        {
            $temp = explode(';#;',$mailData['changeRecord']['creatorEmail']);
            ?>
            <b>Organiser Email</b>:<br> From: <?php echo $temp[0];?><br><br>
            To: <?php echo $temp[1];?><br><br>
            <?php
        }
        ?><br>
        has been declined.<br>
        For further queries, please contact the <?php echo ucfirst($mailData['senderName']);?> (<?php echo $mailData['senderEmail'];?>)<br><br>
        <!--You can reach out to me on <?php /*echo $mailData['senderPhone'] .' ('.ucfirst($mailData['senderName']).')';*/?> and I will help you schedule it better.<br><br>-->

        <?php echo ucfirst($mailData['senderName']); ?>, Doolally<br>
    </p>

</body>
</html>