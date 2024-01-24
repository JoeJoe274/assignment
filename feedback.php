<?php
    const NAME_REQUIRED = 'Please enter your name!';
    const EMAIL_REQUIRED = 'Please enter your email!';
    const EMAIL_INVALID = 'Please enter a valid email!';
    const FEEDBACK_REQUIRED = 'Please enter your feedback!';

    const FB_SENT = 'Feedback sent successfully!';
    const FB_NOT_SENT = 'Please try again. Unable to send you feedback!';
    
    if(isset($_POST['btnsubmit'])) {
        $error = [];
        $msg = [];

        // validation process
        $name = filter_input(INPUT_POST, 'txtName', FILTER_SANITIZE_STRING);

        if ($name) {
            $name = trim($name);
            if ($name === ' ') {
                $error['name'] = NAME_REQUIRED;
            }
        } else {
            $error['name'] = NAME_REQUIRED;
        }

        $email = filter_input(INPUT_POST, 'txtEmail', FILTER_SANITIZE_STRING);

        if ($email) {
            if (preg_match('/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/', $email) === 0) {
                $error['email'] = EMAIL_INVALID;
            }
        } else {
            $error['email'] = EMAIL_REQUIRED;
        }

        $feedback = filter_input(INPUT_POST, 'txtFeedback', FILTER_SANITIZE_STRING);

        if ($feedback) {
            $feedback = trim($feedback);
            if ($feedback === ' ') {
                $error['feedback'] = FEEDBACK_REQUIRED;
            }
        } else {
            $error['feedback'] = FEEDBACK_REQUIRED;
        }

        function sendMail($to, $sub, $content, $add) {
            if (mail($to, $sub, $content, $add)) {
                return FB_SENT;
            } else {
                return FB_NOT_SENT;
            }
        }

        if (count($error) === 0) {
            $toAdd = "joe254595782@gmail.com";

            $subject = "Feedback from Joe website";

            if (preg_match('/shop|customer service|retail/', $feedback)) {
                $subject .= ' : retail';
            } else if (preg_match('/deliver|fulfill|shipping/', $feedback)) {
                $subject .= ' : delivery';
            } else if (preg_match('/bill|account/', $feedback)) {
                $subject .= ' : billing';
            }

            $mailContent = "Customer name: ". str_replace("\r\n", "", $name) . "\n".
            "Customer email: ". str_replace("\r\n", "", $email)  . "\n".
            "Customer comments:\n". str_replace("\r\n", "", $feedback)  . "\n";

            $fromAddress = "From: pfalllay@gmail.com";

            // send mail
            $msg['success'] = sendMail($toAdd, $subject, $mailContent, $fromAddress);
        }

    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Joe's Auto Part's Customer Feedback</title>
    <link rel="stylesheet" href="css/style_feedback.css" />
</head>
<body>

    <div class="container">
        <h1>Customer Feedback</h1>
        <h3><code>Please tell us what you think.</code></h3>

        <form action="feedback.php" method="post">

            <label for="txtName">Your Name</label>
            <input type="text" name="txtName" id="">
            <small><?php echo $error['name'] ?? '' ?></small>

            <label for="txtEmail">Your E-mail</label>
            <input type="text" name="txtEmail" id="">
            <small><?php echo $error['email'] ?? ''?></small>

            <label for="txtFeedback">Your Feedback</label>
            <textarea name="txtFeedback" id="" cols="" rows=""></textarea>

            <small class="error"><?php echo $error['feedback'] ?? '' ?></small>

            <small class="info"><?php echo $msg['success'] ?? '' ?></small>

            <button type="submit" name="btnsubmit" class="btnsubmit">Send FeedBack</button>

        </form>
    </div>
    
</body>
</html>