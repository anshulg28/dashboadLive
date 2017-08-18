<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0056)file:///C:/Users/user1/Desktop/Emails/welcome-email.html -->
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>Mug Club Edit</title>
</head>

<body>
    <p>
        Following Info Edited For Mug #<?php echo $mailData['mugId'];?>:<br><br>

        <?php
            foreach($mailData['changes'] as $key => $row)
            {
                echo str_replace('_',' ',$key).': <br>';
                $subVals = explode(':',$row);
                echo 'From: '.$subVals[0].'<br>';
                echo 'To: '.$subVals[1].'<br><br>';
            }
        ?>
        <?php echo $mailData['senderName'];?>
        Cheers!
    </p>

</body>
</html>