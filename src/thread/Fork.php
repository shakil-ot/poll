<?php

class Fork
{

    public array $task = [];
    public array $dataQueue = [];
    public array $workerRunning = [];
    private bool $isRunning = true;
    public int $workerCount = 2;


    public function setWorker($workerCount = 2): void
    {
        $this->workerCount = $workerCount;
    }


    public function addData($data): void
    {
        $this->dataQueue[] = $data;
    }

    public function forkCreate($task): void
    {
        $pid = pcntl_fork();

        if (-1 === $pid) {
            throw new \RuntimeException('Failure on pcntl_fork');
        } elseif ($pid === 0) {
            $this->startChildProcess($task);
            exit();

        } else {
            $this->workerRunning[] = $pid;
            $this->startParentProcess($pid);
        }
    }

    private function checkWorkerQueue(): void
    {
        for ($i = 0; $i < count($this->workerRunning); $i++) {
            if (isset($this->workerRunning[$i])) {
                $pid = pcntl_waitpid($this->workerRunning[$i], $status);
//                echo $i . " " . $pid . " " . $this->workerRunning[$i] . "\n";

                if (-1 == $pid) {
                    unset($this->workerRunning[$i]);
                }

                if ($pid > 0) {
                    unset($this->workerRunning[$i]);
                } else if ($pid === -21) {

                }
            }
        }


    }


    private function runner(): void
    {
        while ($this->isRunning) {

            if ($this->workerCount > count($this->workerRunning)) {
                $this->dataQueueReceiver();
                $this->runWorker();
            }

            $this->checkWorkerQueue();

            if (count($this->workerRunning) == 0 && count($this->dataQueue) == 0) {
                break;
            }
        }
    }

    public function run(): void
    {
        $this->runner();

    }

    private function calculateRunQueuePerWorker(): int
    {
        // todo:: here will calculate 

        return 2;
    }


    public function dataQueueReceiver(): void
    {
        if (count($this->dataQueue) > 0) {
            foreach ($this->dataQueue as $queue) {
                $this->task[] = $queue;
            }
            $this->dataQueue = [];

            echo "\n +++  \n";
            echo count($this->task);
            echo "\n";
            echo count($this->dataQueue);
            echo "\n";
        }
    }

    private function runWorker(): void
    {
        if (count($this->task) > 0) {

            $workerCanRun = $this->runnableWorkerCalculate();

            for ($start = 0; $start < $workerCanRun; $start++) {
                $result = array_shift($this->task);
                $this->forkCreate($result);
            }
        }
    }

    private function runnableWorkerCalculate(): int
    {
        $workerRunning = count($this->workerRunning);

        if ($workerRunning == 0) {
            return $this->workerCount;
        }

        return $this->workerCount - $workerRunning;
    }


    public function startChildProcess($data): void
    {
        if (str_contains($data, "orange")) {
            sleep(10);
        } else {
            sleep(rand(2, 3));
        }

        echo "\n" . $data . "\n";

    }


    public function startParentProcess($pid): void
    {

    }


}


$f = new Fork();

$f->addData("orange");
$f->addData("apple");
$f->addData("cherry");

$f->run();

$f->addData("banana");
$f->addData("shakil");

