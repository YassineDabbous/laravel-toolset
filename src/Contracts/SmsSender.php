<?php

declare(strict_types=1);


namespace Ysn\SuperCore\Contracts;
 

interface SmsSender
{
    public function send(string $msg, string $to, ?string $from=null);
}
