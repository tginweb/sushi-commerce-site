<?php

namespace Main\Service;

use Bitrix;

class CommandService extends BaseService
{
    function getResultCommands($user = null)
    {
        $commandsByCode = $this->getHooks()->apply_filters('main:commands', []);
        $commands = [];
        foreach ($commandsByCode as $code => $command) {
            $commands[] = [
                    'code' => $code,
                ] + $command;
        }
        return $commands;
    }
}



