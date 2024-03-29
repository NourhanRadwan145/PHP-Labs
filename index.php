<?php
require_once "vendor/autoload.php";
require_once "functions.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Contact Form</title>
</head>
<body>

<h3>Contact Form</h3>
<div id="after_submit">
    <?php
    // Define variables and set to empty values
    $nameErr = $emailErr = $messageErr = "";
    $name = $email = $message = "";

    // Check if form is submitted
    if (isset($_POST["submit"])) {
        // Name validation
        if (empty($_POST["name"])) {
            $nameErr = "Name is required";
        } elseif (strlen($_POST["name"]) > 100) {
            $nameErr = "Name must be less than 100 characters";
        } else {
            $name = $_POST["name"];
        }

        // Email validation
        if (empty($_POST["email"])) {
            $emailErr = "Email is required";
        } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        } else {
            $email = $_POST["email"];
        }

        // Message validation
        if (empty($_POST["message"])) {
            $messageErr = "Message is required";
        } elseif (strlen($_POST["message"]) > 255) {
            $messageErr = "Message must be less than 255 characters";
        } else {
            $message = $_POST["message"];
        }

        // If all validations pass
        if (empty($nameErr) && empty($emailErr) && empty($messageErr)) {
            session_start();
            $view = isset($_GET["view"]) ? $_GET["view"] : default_view;
            $first_request_time = isset($_SESSION["first_request_time"]) ? $_SESSION["first_request_time"] : date("Y-m-d H:i:s");
            $_SESSION["first_request_time"] = $first_request_time;
            echo "Hello, This visit started at $first_request_time <br>";
            if ($view == "display") {
                // Display all submissions immediately
                display_all_submits();
                die("<br> To add a new submission <a href='index.php?view=add'>Click Here</a>");
            } else {
                if (store_submit_to_file($name, $email, $message)) {
                    die("Thank you for contacting us!<br>
                        Name: $name<br>
                        Email: $email<br>
                        Message: $message<br>
                        To view all submissions <a href='index.php?view=display'>Click Here</a>");
                } else {
                    die("Error saving contact.");
                }
            }
        }
    }
    ?>
</div>

<form id="contact_form" action="#" method="POST" enctype="multipart/form-data">

    <div class="row">
        <label class="required" for="name">Your name:</label><br />
        <input id="name" class="input" name="name" type="text" value="<?php echo isset($name) ? $name : ''; ?>" size="30" />
        <span class="error">* <?php echo isset($nameErr) ? $nameErr : ''; ?></span><br />
    </div>
    <div class="row">
        <label class="required" for="email">Your email:</label><br />
        <input id="email" class="input" name="email" type="text" value="<?php echo isset($email) ? $email : ''; ?>" size="30" />
        <span class="error">* <?php echo isset($emailErr) ? $emailErr : ''; ?></span><br />
    </div>
    <div class="row">
        <label class="required" for="message">Your message:</label><br />
        <textarea id="message" class="input" name="message" rows="7" cols="30"><?php echo isset($message) ? $message : ''; ?></textarea>
        <span class="error">* <?php echo isset($messageErr) ? $messageErr : ''; ?></span><br />
    </div>

    <input id="submit" name="submit" type="submit" value="Send email" />
    <input id="clear" name="clear" type="reset" value="Clear form" />

</form>
</body>
</html>
