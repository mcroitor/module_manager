<?php

include_once __DIR__ . "/mc/logger.php";
include_once __DIR__ . "/mc/repository.php";

$longopts = [
    "help",
    "install",
    "reinstall",
    "drop",
    "config::"
];

$config_file = "config.json";
$debug = false;

function usage () {
    echo "Usage: manager.php [options]" . PHP_EOL;
    echo "Options:" . PHP_EOL;
    echo "  --help                 Show this help" . PHP_EOL;
    echo "  --install              Install libraries" . PHP_EOL;
    echo "  --reinstall            Reinstall libraries" . PHP_EOL;
    echo "  --drop                 Drop all libraries" . PHP_EOL;
    echo "  --config=<path>        Path to the config file" . PHP_EOL;
}

function install ($config_file) {
    if(!file_exists($config_file)) {
        echo "Config file not found" . PHP_EOL;
        return;
    }
    $config = json_decode(file_get_contents($config_file), true);

    foreach ($config as $key => $value) {
        $repo = new mc\repository($value);
        $repo->download();
    }
}

function drop($config_file){
    $config = json_decode(file_get_contents($config_file), true);

    foreach ($config as $key => $value) {
        $manager = new mc\repository([
            mc\repository::REPOSITORY => $value[mc\repository::REPOSITORY]
        ]);
        $manager->drop();
    }
}

function reinstall ($config_file) {
    drop($config_file);
    install($config_file);
}

$opts = getopt(null, $longopts);

if(isset($opts["help"]) || !(isset($opts["install"]) || isset($opts["reinstall"]) || isset($opts["drop"]))) {
    usage();
    exit(0);
}

if(isset($opts["config"])) {
    $config_file = $opts["config"];
}

if(isset($opts["install"])) {
    install($config_file);
    exit(0);
}

if(isset($opts["reinstall"])) {
    reinstall($config_file);
    exit(0);
}

if(isset($opts["drop"])) {
    drop($config_file);
    exit(0);
}
