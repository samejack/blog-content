<?php

$channelAccessToken = '{Your_Access_Token}';
$userIds = [];
$message = isset($argv[1]) ? $argv[1] : 'Hello!';
$dbFilePath = __DIR__ . '/line-db.json';  // user info database file path

// open json database
if (!file_exists($dbFilePath)) {
   file_put_contents($dbFilePath, json_encode(['user' => []]));
}
$db = json_decode(file_get_contents($dbFilePath), true);

if (count($db['user']) === 0) {
   echo 'No user login.';
   exit(1);
} else {
   foreach ($db['user'] as &$userInfo) {
       $userIds[] = $userInfo['userId'];
   }
}

// make payload
$payload = [
   'to' => $userIds,
   'messages' => [
       [
           'type' => 'text',
           'text' => $message
       ]
   ]
];

// Send Request by CURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.line.me/v2/bot/message/multicast');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
   'Content-Type: application/json',
   'Authorization: Bearer ' . $channelAccessToken
]);
$result = curl_exec($ch);
curl_close($ch);
