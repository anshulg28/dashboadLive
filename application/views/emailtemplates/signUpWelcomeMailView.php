<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0056)file:///C:/Users/user1/Desktop/Emails/welcome-email.html -->
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>Welcome Member</title>
</head>

<body>
    <p>Dear <?php echo trim(ucfirst($mailData['firstName']));?>,</p>
    <p>Thanks for being part of the Doolally Mug Club.<br><br>
        We serve breakfast everyday, 7 am onwards, and since we really want you to try it out - your first Breakfast of Champions (includes a beer) is on us! Don't hurry or anything, it's valid till hell freezes over! Also, this is valid at any of our Mumbai taprooms.<br><br>

        Just show this code <?php echo trim($breakfastCode);?> to the person taking your order the next time you're in the mood for breakfast.<br><br>

        Cheers!<br>
        <?php echo trim(ucfirst($this->userFirstName));?>,<br>
        Doolally<br><br>
        P.S. We promise to never spam you or give out your information to anyone. If you don't wish to receive these emails, do let me know by replying to this mail. The offers will still always be valid for you. Just inquire at the bar for Mug Club goodies.
    </p>

</body>
</html>