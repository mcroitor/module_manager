<?php

include_once __DIR__ . "/mc/configmanager.php";

$longopts = [
    "help",
    "init",
    "truncate",
    "add",
    "remove",
    "config::"
];


$config_file = "modules.json";

/**
 * print usage
 */
function usage()
{
    echo "Usage: " . __FILE__ . " [options]" . PHP_EOL;
    echo "Options:" . PHP_EOL;
    echo "  --help                  Show this help" . PHP_EOL;
    echo "  --init                  Create empty config file" . PHP_EOL;
    echo "  --truncate              truncate current config file" . PHP_EOL;
    echo "  --add                   add new repo" . PHP_EOL;
    echo "  --remove                remove a repo" . PHP_EOL;
    echo "  --config=<path>         Path to the config file" . PHP_EOL;
}

/**
 * create blank config file.
 * @param string config file
 */
function init(string $config_file)
{
    if (file_exists($config_file)) {
        echo "Config file '{$config_file}' exists, "
            . "if you want to recreate it, remove it manually" . PHP_EOL;
        return;
    }
    file_put_contents($config_file, "[]");
}

if (
    isset($opts["help"]) ||
    !(isset($opts["truncate"]) || isset($opts["add"]) ||
        isset($opts["remove"]) || isset($opts["init"]))
) {
    usage();
    exit(0);
}

if (isset($opts["config"])) {
    $config_file = $opts["config"];
}

if (isset($opts["entrypoint"])) {
    $entrypoint = $opts["entrypoint"];
}

if (isset($opts["info"])) {
    info($config_file);
    exit(0);
}

if (isset($opts["init"])) {
    init($config_file);
    exit(0);
}
