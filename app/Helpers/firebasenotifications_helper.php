<?php

function sendNotification($title, $body, $tokens)
{
    $headers = [
        'Authorization: key=aaaamdjqkpk:APA91bH4dQbOlZJbcnrviv8Cak23oGKjVbzs3O0V9s1jEo_SLynqGa-XqxLa4rXtXAWn7eSeeyuqjf9fexjsxzJJVPXmU3GzY8sjddKyRqiFoZdr14ryMhvpGD2I-KmfRjL2rVWVVPnV',
        'Content-Type: application/json'
    ];

    $request = [
        'notification' => [
            'title' => $title,
            'body' => $body,
            'sound' => 'default',
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK'
        ],
        'data' => [
            'title' => $title,
            'body' => $body,
        ],
        'registration_ids' => $tokens,
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
        curl_close($ch);
        return 'cURL error: ' . $error_msg;
    }

    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code != 200) {
        return 'FCM Error: HTTP status code ' . $http_code . ' - Response: ' . $response;
    }

    return $response;
}


