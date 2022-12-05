<?php

namespace Src\abstract;


abstract class PollWorker
{
    abstract public function processing($id, $data);

    abstract function processed($id, $data);

}