<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0056)file:///C:/Users/user1/Desktop/Emails/welcome-email.html -->
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>Organiser Tds</title>
</head>

<body>
    <p>Dear <?php echo trim($mailData['creatorName']); ?>,</p>
    <p>
        You have crossed Rs 30,000 in earnings from events that you have held at Doolally. Your total collected till date is
        <?php echo $mailData['totalAmt'];?>. Good job!<br><br>
        As an organiser, once you have crossed Rs 30,000 in earnings, you are liable to pay Income Tax.
        This balance amount of Rs <?php echo $mailData['balAmt']; ?> will also be taxed at 10% TDS.
        This Rs <?php echo $mailData['tdsAmt'];?> amount will be deducted and adjusted in your next payment.<br><br>
        Doolally will also now begin deducting 10% TDS on your forthcoming events and issue a Form 16 to you.<br><br>
        This deduction will reflect in your dashboard or call me if you have any doubts.<br><br>
        Thanks!<br>
        <?php echo ucfirst($mailData['senderName']); ?>, Doolally
    </p>

</body>
</html>