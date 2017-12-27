<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0056)file:///C:/Users/user1/Desktop/Emails/welcome-email.html -->
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>Event Rescheduled</title>
</head>

<body>
    <p>
        Dear <?php echo ucfirst(trim($mailData['attendeeName'])); ?>,<br><br>

        The organiser has rescheduled "<?php echo trim($mailData['eventName']); ?>" from
        "<?php $d = date_create($mailData['oldDate']); echo date_format($d,DATE_FORMAT_UI);?>,
        <?php echo date('h:i a',strtotime($mailData['oldStartTime'])).'-'.date('h:i a',strtotime($mailData['oldEndTime']));?>" to
        "<?php $d = date_create($mailData['newDate']); echo date_format($d,DATE_FORMAT_UI);?>,
        <?php echo date('h:i a',strtotime($mailData['newStartTime'])).'-'.date('h:i a',strtotime($mailData['newEndTime']));?>".<br><br>
        In case you can't make it on "<?php $d = date_create($mailData['newDate']); echo date_format($d,DATE_FORMAT_UI);?>,
        <?php echo date('h:i a',strtotime($mailData['newStartTime'])).'-'.date('h:i a',strtotime($mailData['newEndTime']));?>",
        just cancel your attendance.
        <?php
            if($mailData['costType'] != EVENT_FREE)
            {
                echo ' For paid events, the money will be fully refunded to you.';
            }
        ?>
        <br><br>

        <a href="<?php echo MOBILE_URL;?>?page/event_dash"
        target="_blank">Click here</a> to cancel your attendance.<br><br>

        Thanks,<br>
        <?php echo ucfirst($mailData['senderName']);?>
    </p>

</body>
</html>