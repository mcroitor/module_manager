<?php

function test(bool $expression, string $passed = "PASS", string $failed = "FAIL"): void {
    echo $expression ? $passed : $failed;
    echo PHP_EOL;
}

function info(string $message, $object = null): void {
    echo "[info] $message";
    if ($object != null){
        echo " - " . json_encode($object);
    }
    echo PHP_EOL;
}

include_once __DIR__ . "/../src/mc/configmanager.php";


info("TEST 1 create empty config");
$config_file = "modules.json";

$configManager = new \mc\ConfigManager($config_file);

$configManager->truncate();

test(file_exists($config_file));
test(empty($configManager->modules()));

$configManager->add([\mc\ConfigManager::USER => "mcroitor", \mc\ConfigManager::REPOSITORY => "database"]);

test(count($configManager->modules()) == 1);

$configManager->add([
    "user" => "mcroitor",
    "repository" => "logger",
    "entrypoint" => "mc/logger.php",
    "source" => "src",
    "destination" => "modules/logger"
]);

test(count($configManager->modules()) == 2);

info("done.");
