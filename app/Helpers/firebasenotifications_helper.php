<?php

function sendNotification($title, $body, $tokens)
{
    $headers = [
        'Authorization: key=AAAAMdjqKPk:APA91bH4dQbOlZJbcnrviv8Cak23oGKjVbzs3O0V9s1jEo_SLynqGa-XqxLa4rXtXAWn7eSeeyuqjf9fexjsxzJJVPXmU3GzY8sjddKyRqiFoZdr14ryMhvpGD2I-KmfRjL2rVWVVPnV',
        'Content-Type: application/json'
    ];

    $request = [
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

    $res = curl_exec($ch);

    curl_close($ch);

    return $res;
}