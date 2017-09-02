<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0056)file:///C:/Users/user1/Desktop/Emails/welcome-email.html -->
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>Registration Cancel</title>
</head>

<body>
    <p>
        Dear <?php echo trim($mailData['firstName']); ?><br><br>

        The organiser <?php echo str_replace('.','',$mailData['creatorName']); ?> has cancelled the event <?php echo trim($mailData['eventName']); ?>.

        <?php
            if(isset($mailData['refundId']))
            {
                ?>
                Your payment will be fully refunded to you within 7 days.<br><br>
                Refund Id: <?php echo $mailData['refundId'];?><br>
                <?php
                if($mailData['couponAmt'] != 0)
                {
                    ?>
                    Refund details: We have initiated partial refund for Rs. <?php echo $mailData['refundAmt'];?> (Rs. <?php echo $mailData['couponAmt'];?>
                    deducted against event code(s) redemption at the Taproom) against your booking
                    for <?php echo $mailData['quantity'];?> for <?php echo $mailData['eventName'];?>.
                    It should be transferred into your account within 7 bank working days.
                    <br><br>
                    <?php
                }
                else
                {
                    ?>
                    Refund details: We have initiated the refund for Rs. <?php echo $mailData['refundAmt'];?> against your booking
                    for <?php echo $mailData['quantity'];?> for <?php echo $mailData['eventName'];?>.
                    It should be transferred into your account within 7 bank working days.
                    <br><br>
                    <?php
                }
                ?>
                <br><br>
                <?php
            }
            else
            {
                ?>
                <br><br>
                <?php
            }
        ?>
        In case you have any questions/queries please don't hesitate to write to me at
        this (<?php echo $mailData['senderEmail'];?>) mail address <!--or you can reach me at --><?php /*echo $mailData['senderPhone'];*/?><br><br>

        Thanks,<br>
        <?php echo ucfirst($mailData['senderName']);?>
    </p>

</body>
</html>