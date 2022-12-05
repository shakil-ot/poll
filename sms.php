<?php


function send_sms() {
    $url = "https://bangladeshsms.com/smsapimany";
    $data = [
        "api_key" => "C200355262f48ecea05614.23175670",
        "senderid" => "8801847169884",
        "messages" => json_encode( [
            [
                "to" => "8801781144513",
                "message" => " নিয়ম অনুযায়ী, টারগেটিং/এপিআই/নাম্বারলিস্ট এর সকল ধরণের প্রোমোশনাল/"
            ]
        ])
    ];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

send_sms();