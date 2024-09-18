<?php

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

function initializeFirebase()
{
    // Update the path to your Firebase service account JSON file
    $firebase = (new Factory)
        ->withServiceAccount('bfpcalapancity/pushnotifbfp-c11343df49d3.json') // Update this path
        ->create();

    return $firebase;
}

function sendNotification($title, $body, $tokens)
{
    $firebase = initializeFirebase();
    $messaging = $firebase->getMessaging();

    $message = CloudMessage::new()
        ->withNotification([
            'title' => $title,
            'body' => $body,
            'image' => 'image.jpg', // Optional: You can add an image here
        ])
        ->withData(['click_action' => 'FLUTTER_NOTIFICATION_CLICK']);

    try {
        $messaging->sendMulticast($message, $tokens); // Send the message to multiple tokens
        return 'Notification sent successfully!';
    } catch (\Kreait\Firebase\Exception\MessagingException $e) {
        return 'Error sending notification: ' . $e->getMessage();
    }
}
