<?php

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Exception\MessagingException;

function initializeFirebase()
{
    // Initialize Firebase with the path to your Firebase service account JSON file
    $firebase = (new Factory)
        ->withServiceAccount('bfpcalapancity/pushnotifbfp-c11343df49d3.json'); // Ensure this path is correct

    return $firebase->createMessaging(); // Create the messaging instance directly
}

// 
function sendNotification($title, $body, $tokens)
{
    $messaging = initializeFirebase();

    $message = CloudMessage::withTarget('token', $tokens) // Sending to individual tokens
        ->withNotification([
            'title' => $title,
            'body' => $body,
            'image' => 'image.jpg' // Optional image field
        ])
        ->withData(['click_action' => 'FLUTTER_NOTIFICATION_CLICK']);

    try {
        $result = $messaging->sendMulticast($message, $tokens); // Send to multiple tokens
        return 'Notification sent successfully!';
    } catch (MessagingException $e) {
        return 'Error sending notification: ' . $e->getMessage();
    }
}
