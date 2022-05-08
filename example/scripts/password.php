<?php

echo ($argv[1] ? password_hash($argv[1], PASSWORD_DEFAULT) : 'Please provide a password') . PHP_EOL;
