<?php

namespace Src\thread;


use Exception;
use ReflectionClass;
use ReflectionException;
use Src\abstract\PollWorker;
use Src\abstract\Worker;

class ThreadPoll extends Worker
{
    private $class = null;

    private bool $isProcessRunning = false;

    private $param = [];

    private array $processListExist = [];

    public array $processData = [];

    protected int $job = 5;

    protected int $start = 0;


    private $max = 1;
    private $permissions = 0666;
    private $autoRelease = 1;


    protected array $currentJobs = [];


    /**
     * @throws \Exception
     */
    public function run()
    {
        $this->runProcess();
    }

    public function setData($data)
    {
        $this->processData[] = $data;
        if ($this->isProcessRunning) {
            $this->body();
        }
    }


    private function checkExtendedClass($class): bool
    {
        return is_subclass_of($class, PollWorker::class);
    }

    /**
     * @throws Exception
     */
    public function runProcess(): void
    {
        $this->isProcessRunning = true;
        $this->body();
    }

    public function setWorkerProcessClass($class, ...$param): void
    {
        if (!is_null($class)) {
            $this->class = $class;
            $this->param = $param;
        }
    }


    /**
     * @throws ReflectionException
     * @throws Exception
     */
    protected function body(): void
    {
        if (!$this->checkExtendedClass($this->class)) {
            throw new Exception('Please extends ' . PollWorker::class);
        }

        $key = rand(2323,54656);

        $semaphore = sem_get($key, 1, 0666, 1);

        if (!$semaphore) {
            echo "Failed on sem_get().\n";
            exit;
        }

//        sem_acquire($semaphore);

        for ($this->start = 0; $this->start < count($this->processData); $this->start++) {

            if (isset($this->processData[$this->start])) {

                if (!in_array($this->processData[$this->start], $this->processListExist)) {
                    $this->processListExist[] = $this->processData[$this->start];

                    $pid = pcntl_fork();

                    $instance = (new ReflectionClass($this->class))->newInstanceArgs($this->param);


                    if ($pid == -1) {
                        throw new Exception('Could not fork');
                        die();
                    } else if ($pid) {
                        $instance->processed($pid, $this->processData[$this->start]);
                    } else {
                        $instance->processing($pid, $this->processData[$this->start]);
//                        unset($this->processData[$this->start]);
                        exit();
                    }
                }
            }
        }
        $this->processListExist = [];
//        sem_release($semaphore);


        while (pcntl_waitpid(0, $status) != -1) {
            $status = pcntl_wexitstatus($status);
        }
    }


}