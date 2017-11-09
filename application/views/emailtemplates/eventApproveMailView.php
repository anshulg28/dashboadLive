<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0056)file:///C:/Users/user1/Desktop/Emails/welcome-email.html -->
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>Event Approve</title>
</head>

<body>
    <p>Dear <?php echo trim($mailData[0]['creatorName']); ?>,</p>
    <p>
        1. Your event has been <?php echo $mailData['eventStatus'];?>! Here's a link to your event - <?php echo $mailData[0]['eventShareLink'] ?><br><br>
        2. Additionally, as an organiser, you are expected:
        <ul><li>To set up and manage the ticket counter for any ticketed events.</li>
        <li>To chip in with the arrangements of the event.</li>
        <li>To have visited the venue prior to the event.</li>
        <li>To arrive at the venue at least 45 minutes before the scheduled time of the event.</li></ul><br><br>

        3. You will also be given access to a dashboard. This is a place where information on the number of sign ups, fees collected,
        payout details will be available to you. You can also edit your event from this dashboard.<br><br>

        4. For paid events/workshops, we charge a venue fee of Rs. 118.<?php
            if(isset($orgCode))
            {
                echo ' As an organizer of an event/workshop, you will be provided Food & Beverage worth Rs. 500. 
                Please show this code '.$orgCode.' to the waiter who is serving you before placing your order.';
            }
        ?><br><br>

        5. Doolally will also list and promote your event on the following platforms - Zomato, Eventshigh.com, Meetup, and Doolally social media profiles.<br><br>

        6. Cancellation Policy:<br>
    <ul><li>If you need to cancel an event, you will need to get in touch with the venue's community manager.</li>

        <li>If you cancel your event, we will refund the full amount to the attendees. However, the payment fee of 2.24% per attendee will be borne by you.</li>

        <li>If an attendee cancels their attendance to your event, the transaction fee of 2.24% will be borne by the attendee.</li></ul><br><br>

        7. Pay Outs to Organisers:<br>
    <ul><li>For paid events, we will collect money from the customer on your behalf. This is to ensure a refund in case of cancelled events.</li>

        <li>Doolally will bear the 2.24% payment gateway fees except during cancellations by either the organiser or attendee.</li>

        <li>Eventshigh is our payment partner. Please check out their credentials here - www.eventshigh.com</li>

        <li>For payment for events, we will hand over a cheque in the name of organiser.
            As an organiser, once you have crossed Rs 30,000 in earnings, Doolally will deduct 10% TDS.</li></ul><br><br>

        In case you have any questions/queries please don't hesitate to write to me at this (<?php echo $mailData['senderEmail'];?>) mail address.<!-- or you can reach me at
        --><?php /*echo $mailData['senderPhone'] .' ('.$mailData['senderName'].')';*/?><br><br>

        Cheers!<br>
        <?php echo ucfirst($mailData['senderName']); ?>, Doolally
    </p>

</body>
</html>