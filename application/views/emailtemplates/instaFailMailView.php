<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0056)file:///C:/Users/user1/Desktop/Emails/welcome-email.html -->
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>Unknown mug renew</title>
</head>

<body>
    <p>
        Following are the Info :<br><br>

        <?php
            echo 'Buyer Name: '.$mailData['buyer_name'].'<br>';
            echo 'Buyer Email: '.$mailData['buyer_email'].'<br>';
            echo 'Buyer Phone: '.$mailData['buyer_phone'].'<br>';
            echo 'Mug Number Provided: '.$mailData['mugNo'].'<br>';
            echo 'Payment Id: '.$mailData['payment_id'].'<br>';
            echo 'Quantity: '.$mailData['quantity'].'<br>';
        ?>
        <?php echo $mailData['senderName'];?>
        Cheers!
    </p>

</body>
</html>