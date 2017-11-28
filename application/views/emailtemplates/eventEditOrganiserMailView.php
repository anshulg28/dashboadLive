<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0056)file:///C:/Users/user1/Desktop/Emails/welcome-email.html -->
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>Event Edit</title>
</head>

<body>
    <p>Hi <?php if(isset($mailData['orgName'])){echo $mailData['orgName'];}else{echo 'Organizer';}; ?>,</p>
    <p>
        The Community Manager has made the following event modifications:<br><br>
        <?php
            if(isset($mailData['attachment']))
            {
                $attachment = explode(';#;',$mailData['attachment']);
                ?>
                Old Event Image:<br>
                <img src="<?php echo base_url().EVENT_PATH_THUMB.$attachment[0];?>" alt="Old Image"/><br><br>
                New Event Image:<br>
                <img src="<?php echo base_url().EVENT_PATH_THUMB.$attachment[1];?>" alt="New Image"/><br><br>
                <?php
            }
            if(isset($mailData['eventName']))
            {
                $temp = explode(';#;',$mailData['eventName']);
                ?>
                <b>Event Name</b>:<br> From: <?php echo $temp[0];?><br><br>
                To: <?php echo $temp[1];?><br><br>
                <?php
            }
            if(isset($mailData['eventDescription']))
            {
                $temp = explode(';#;',$mailData['eventDescription']);
                ?>
                <b>Event Description</b>:<br> From: <?php echo $temp[0];?><br><br>
                To: <?php echo $temp[1];?><br><br>
                <?php
            }
            /*if(isset($mailData['eventType']))
            {
                $temp = explode(';#;',$mailData['eventType']);
                */?><!--
                <b>Event Type</b>:<br> From: <?php /*echo $temp[0];*/?><br>
                To: <?php /*echo $temp[1];*/?><br>
                --><?php
/*            }*/
            if(isset($mailData['eventDate']))
            {
                $temp = explode(';#;',$mailData['eventDate']);
                ?>
                <b>Event Date</b>:<br> From: <?php echo $temp[0];?><br><br>
                To: <?php echo $temp[1];?><br><br>
                <?php
            }
            if(isset($mailData['startTime']))
            {
                $temp = explode(';#;',$mailData['startTime']);
                ?>
                <b>Event Start Time</b>:<br> From: <?php echo $temp[0];?><br><br>
                To: <?php echo $temp[1];?><br><br>
                <?php
            }
            if(isset($mailData['endTime']))
            {
                $temp = explode(';#;',$mailData['endTime']);
                ?>
                <b>Event End Time</b>:<br> From: <?php echo $temp[0];?><br><br>
                To: <?php echo $temp[1];?><br><br>
                <?php
            }
            if(isset($mailData['costType']))
            {
                $temp = explode(';#;',$mailData['costType']);
                ?>
                <b>Event Cost</b>:<br> From: <?php if($temp[0] == '1'){echo 'Free';}else{echo 'Paid';}?><br><br>
                To: <?php if($temp[1] == '1'){echo 'Free';}else{echo 'Paid';}?><br><br>
                <?php
            }
            if(isset($mailData['eventPrice']))
            {
                $temp = explode(';#;',$mailData['eventPrice']);
                ?>
                <b>Event Price</b>:<br> From: Rs. <?php echo $temp[0];?><br><br>
                To: Rs. <?php echo $temp[1];?><br><br>
                <?php
            }
            if(isset($mailData['eventPlace']))
            {
                $temp = explode(';#;',$mailData['eventPlace']);
                ?>
                <b>Event Place</b>:<br> From: <?php echo $temp[0];?><br><br>
                To: <?php echo $temp[1];?><br><br>
                <?php
            }
            if(isset($mailData['eventCapacity']))
            {
                $temp = explode(';#;',$mailData['eventCapacity']);
                ?>
                <b>Event Capacity</b>:<br> From: <?php echo $temp[0];?><br><br>
                To: <?php echo $temp[1];?><br><br>
                <?php
            }

            if(isset($mailData['ifMicRequired']))
            {
                $temp = explode(';#;',$mailData['ifMicRequired']);
                ?>
                <b>Mic Required?</b>:<br> From: <?php if($temp[0] == '1'){echo 'Yes';}else{echo 'No';}?><br><br>
                To: <?php if($temp[1] == '1'){echo 'Yes';}else{echo 'No';}?><br><br>
                <?php
            }
            if(isset($mailData['ifProjectorRequired']))
            {
                $temp = explode(';#;',$mailData['ifProjectorRequired']);
                ?>
                <b>Projector Required?</b>:<br> From: <?php if($temp[0] == '1'){echo 'Yes';}else{echo 'No';}?><br><br>
                To: <?php if($temp[1] == '1'){echo 'Yes';}else{echo 'No';}?><br><br>
                <?php
            }
            if(isset($mailData['creatorName']))
            {
                $temp = explode(';#;',$mailData['creatorName']);
                ?>
                <b>Organiser Name</b>:<br> From: <?php echo $temp[0];?><br><br>
                To: <?php echo $temp[1];?><br><br>
                <?php
            }
            if(isset($mailData['creatorPhone']))
            {
                $temp = explode(';#;',$mailData['creatorPhone']);
                ?>
                <b>Organiser Phone</b>:<br> From: <?php echo $temp[0];?><br><br>
                To: <?php echo $temp[1];?><br><br>
                <?php
            }
            if(isset($mailData['creatorEmail']))
            {
                $temp = explode(';#;',$mailData['creatorEmail']);
                ?>
                <b>Organiser Email</b>:<br> From: <?php echo $temp[0];?><br><br>
                To: <?php echo $temp[1];?><br><br>
                <?php
            }
            if(isset($mailData['aboutCreator']))
            {
                $temp = explode(';#;',$mailData['aboutCreator']);
                ?>
                <b>About Organiser</b>:<br> From: <?php echo $temp[0];?><br><br>
                To: <?php echo $temp[1];?><br><br>
                <?php
            }
        ?>

        Cheers,<br>
        <?php echo $mailData['senderName'];?>
    </p>

</body>
</html>