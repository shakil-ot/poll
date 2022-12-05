<?php
require __DIR__ . '/vendor/autoload.php';

use Src\abstract\PollWorker;
use Src\thread\ThreadPoll;


class Run extends PollWorker
{

    public function callApi($method, $url, $data = false): bool|string
    {
        $curl = curl_init();

        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);

                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        // Optional Authentication:
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, "username:password");

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }

    public function uCall($data)
    {
        $this->callApi('POST', 'https://webhook.site/54900d37-868a-4271-ac9f-9f5502f7d23a', ['id' => $data]);
    }

    public function processing($id, $data): array
    {
//        print_r($data);
//        echo "Run processing \n";
//        var_dump($data);
//        echo "End Run processing \n";
//
        foreach ($data as $currentValue) {
            if ($currentValue == 1) {
                sleep(6);
            }
            sleep(rand(1, 5));
            echo $currentValue . "\n";
            $this->uCall($currentValue);
        }

        return [$data];
    }

    function processed($id, $data)
    {
        // TODO: Implement processed() method.
    }

}


class RunNew extends PollWorker
{

    function processing($id, $data)
    {

        if (str_contains('kill', $data)) {
            sleep(4);
        }
        sleep(rand(1, 2));

        echo $id;
        var_dump($data);
        echo "\n";
    }

    function processed($id, $data)
    {

//        echo $id;
//        echo "\n";

//        if (str_contains('kill', $data)) {
//            sleep(30);
//        }
//
//        echo $data . "\n";

//        sleep(rand(2, 5));
//
//        if (str_contains('kill', $data)) {
//            sleep(30);
//        }
//
//        echo $data . "\n";
    }
}

try {

    // note
    // user define thread poll
    // thread poll start
    // thread safe resource
    // Login lib use // echo remove // Login faced used
    // access modifier

    $pollNew = new ThreadPoll();

    $pollNew->setWorkerProcessClass(RunNew::class);
    $pollNew->runProcess();

    $pollNew->setData('kill');
    $pollNew->setData('shakil');
    $pollNew->setData('ahmed');
    $pollNew->setData('Munna');


    $pollNew->setData('fffff');
    $pollNew->setData('dhfsdkhfk');
    $pollNew->setData('kill');
    $pollNew->setData('kdxcgdfgdfgdffjlsdk');


    die();

    $poll = new ThreadPoll();
    $poll->setWorkerProcessClass(Run::class);
    $poll->runProcess();

    $arr = [];
    foreach (range(1, 20) as $key => $value) {
        $arr[] = $value;
        if (count($arr) == 2) {
            $poll->setData($arr);

//            $data = array_chunk($arr, 2);
//
//            foreach ($data as $valueChunk) {
//
//            }

            $arr = [];
        }
    }
} catch (Exception $e) {
    var_dump($e->getMessage());
}



