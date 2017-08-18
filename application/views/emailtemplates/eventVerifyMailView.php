<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0056)file:///C:/Users/user1/Desktop/Emails/welcome-email.html -->
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>Event Creation</title>
</head>

<body>
    <p>Event Detail:</p>
    <p>
        Event Image:
        <img src="<?php echo MOBILE_URL.EVENT_PATH_THUMB.$mailData[0]['attachment'];?>"/><br>
        Event Name: <?php echo $mailData[0]['eventName'];?><br>
        Event description: <?php echo $mailData[0]['eventDescription'];?><br>
        <!--Event Type: <?php /*echo $mailData[0]['eventType'];*/?><br>-->
        Event Date: <?php echo $mailData[0]['eventDate'];?><br>
        Event Time: <?php echo $mailData[0]['startTime'] .' - '.$mailData[0]['endTime'];?><br>
        Event Price:
        <?php
            if($mailData[0]['costType'] == '1')
            {
                echo 'Free';
            }
            else
            {
                echo $mailData[0]['eventPrice'];
            }
        ?><br>
        Event Place: <?php echo $mailData[0]['locData'][0]['locName'];?><br>
        Event Capacity: <?php echo $mailData[0]['eventCapacity'];?><br>
        Mic Required:
        <?php
            if($mailData[0]['ifMicRequired'] == '1')
            {
                echo 'Yes';
            }
            else
            {
                echo 'No';
            }

        ?><br>
        Projector Required:
        <?php
        if($mailData[0]['ifProjectorRequired'] == '1')
        {
            echo 'Yes';
        }
        else
        {
            echo 'No';
        }

        ?><br>
        Organiser Name: <?php echo $mailData[0]['creatorName'];?><br>
        Organiser Phone: <?php echo $mailData[0]['creatorPhone'];?><br>
        Organiser Email: <?php echo $mailData[0]['creatorEmail'];?><br>
        Organiser Email: <?php echo $mailData[0]['creatorEmail'];?><br>
        About Organiser: <?php echo $mailData[0]['aboutCreator'];?><br><br>

        <a href="<?php echo DASHBOARD_URL.'dashboard#events';?>"
           style="text-decoration: none;border: 2px solid #000;padding: 5px;border-radius: 5px;color:green;">Go To Dashboard</a>

        <!--<a href="<?php /*echo base_url().'dashboard/eventEmailApprove/'.$mailData['senderUser'].'/'.$mailData[0]['eventId'];*/?>"
        style="text-decoration: none;border: 2px solid #000;padding: 5px;border-radius: 5px;color:green;">Approve Event</a>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <a href="<?php /*echo base_url().'dashboard/eventEmailDecline/'.$mailData['senderUser'].'/'.$mailData[0]['eventId'];*/?>"
           style="text-decoration: none;border: 2px solid #000;padding: 5px;border-radius: 5px;color:red;">Decline Event</a>-->
    </p>

</body>
</html>