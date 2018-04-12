<?php

namespace Cli;

class Command
{
    /**
     * Command Line Actions
     */
    private const MIGRATE = 'migrate';

    public static function executeCommandAction(array $argv)
    {
        switch ($argv[1]) {
            case Command::MIGRATE: {
                Migration::migrate();
            }
            default: {
                echo 'No such command';
            }
        }
    }
}
