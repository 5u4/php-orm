<?php

namespace Cli;

class Migration
{
    private const ROOT_DIR = './migrations';

    public static function migrate()
    {
        if (is_dir(Migration::ROOT_DIR)) {
            $files = glob(Migration::ROOT_DIR . '/*.php');
            foreach ($files as $file) {
                shell_exec('php ' . $file);
            }
        }
    }
}
