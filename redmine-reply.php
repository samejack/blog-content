#!/usr/bin/env /usr/bin/php
<?php

// defined
$hostname = 'http://127.0.0.1:8000';
$key = 'YOUR_API_KEY';
$updateMode = 'XML'; // XML | JSON

error_reporting(E_ERROR);
ini_set('display_errors', 'On');

// check argv
if (count($argv) !== 4) {
    echo 'Parameter miss.';
    exit(1);
}
$message = $argv[1];
$author = $argv[2];
$rev = $argv[3];

// check log format
preg_match('/^#(\d+)\s(.+)$/is', $message, $matches);
if (count($matches) !== 3) {
    echo "Log format check fail. Example as follows:\n";
    echo "#ISSUE_NO CHANGE_LOG\n";
    exit(1);
}
$issueNo = $matches[1];
$log = $matches[2];
$url = sprintf('%s/issues/%d.json?key=%s', $hostname, $issueNo, $key);

// check issue no
$issueInfo = file_get_contents($url);
if ($issueInfo === false) {
    echo "Redmine issue '#{$issueNo}' not found.\n";
} else {
    // add commit log to redmine
    $issueObj = json_decode($issueInfo, true);
    if ($issueObj['issue']['done_ratio'] < 90) {
        $doneRatio = $issueObj['issue']['done_ratio'] + ((90 - $issueObj['issue']['done_ratio']) / 2);
    } else {
        $doneRatio = $issueObj['issue']['done_ratio'];
    }
    $notes = sprintf('%s: %s (commit:%s)', $author, $matches[2], substr($rev, 0, 8));
    if ($updateMode === 'JSON') {
        $data = json_encode(array(
            'issue' => array(
                'notes' => $notes,
                'done_ratio' => $doneRatio
            )
        ));
        $contentType = 'application/json; charset=utf-8';
    } else {
        $data = '<?xml version="1.0"?>';
        $data .= '<issue>';
        $data .= '<notes>' . $notes . '</notes>';
        $data .= '<done_ratio>' . $doneRatio . '</done_ratio>';
        $data .= '</issue>';
        $url = sprintf('%s/issues/%d.xml?key=%s', $hostname, $issueNo, $key);
        $contentType = 'application/xml; charset=utf-8';
    }

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Content-Type: ' . $contentType,
        'X-Redmine-API-Key: ' . $key
    ));
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    $response = curl_exec($curl);
    $httpStatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    if ($httpStatus !== 200) {
        echo "Update redmine issue fail! (#${issueNo})\n" . print_r($data, true) . "\n";
    } else {
        echo "Update change log to redmine issue #${issueNo}.\n";
    }
}
