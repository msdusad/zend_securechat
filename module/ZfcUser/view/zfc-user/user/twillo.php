<?php
    
    require "vendor/twilio/sdk/Services/Twilio.php";
 
    // Step 2: set our AccountSid and AuthToken from www.twilio.com/user/account
    $AccountSid = "AC25b077f065860984d4436fffbb180f38";
    $AuthToken = "96a6952aa2cc0947f64f493fd76c2c01";
 
    // Step 3: instantiate a new Twilio Rest Client
    $client = new Services_Twilio($AccountSid, $AuthToken);
 
    // Step 4: make an array of people we know, to send them a message. 
    // Feel free to change/add your own phone number and name here.
    $people = array(
        "+918882331617" => "Mahender Dusad",
    );
 
    // Step 5: Loop over all our friends. $number is a phone number above, and 
    // $name is the name next to it
    foreach ($people as $number => $name) {
 
        $sms = $client->account->messages->sendMessage(
 
        // Step 6: Change the 'From' number below to be a valid Twilio number 
        // that you've purchased, or the (deprecated) Sandbox number
            "+12016902474", 
 
            // the number we are sending to - Any phone number
            $number,
 
            // the sms body
            "Hey $name, Got It Code!"
        );
 
        // Display a confirmation message on the screen
        echo "Sent message to $name";
    }
