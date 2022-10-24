<?php

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

/**
 * print usage
 */
function usage () {
    echo "Usage: manager.php [options]" . PHP_EOL;
    echo "Options:" . PHP_EOL;
    echo "  --help                 Show this help" . PHP_EOL;
    echo "  --install              Install libraries" . PHP_EOL;
    echo "  --reinstall            Reinstall libraries" . PHP_EOL;
    echo "  --drop                 Drop all libraries" . PHP_EOL;
    echo "  --config=<path>        Path to the config file" . PHP_EOL;
}

/**
 * install modules defined in the config file
 */
function install ($config_file) {
    if(!file_exists($config_file)) {
        echo "Config file not found" . PHP_EOL;
        return;
    }
    $config = json_decode(file_get_contents($config_file), true);

    foreach ($config as $module_config) {
        echo "Installing {$module_config['user']}/{$module_config['repository']} ... ";
        $repo = new mc\repository($module_config);
        $repo->download();
        echo "[OK]" . PHP_EOL;
    }
}

/**
 * drop modules defined in config file
 */
function drop($config_file){
    if(!file_exists($config_file)) {
        echo "Config file not found" . PHP_EOL;
        return;
    }

    $config = json_decode(file_get_contents($config_file), true);

    foreach ($config as $module_config) {
        echo "Dropping {$module_config['user']}/{$module_config['repository']} ... ";
        $manager = new mc\repository([
            mc\repository::REPOSITORY => $module_config[mc\repository::REPOSITORY]
        ]);
        $manager->drop();
        echo "[OK]" . PHP_EOL;
    }
}

/**
 * reinstall modules defined in the config file
 */
function reinstall ($config_file) {
    echo "Reinstalling modules ... " . PHP_EOL;
    drop($config_file);
    install($config_file);
    echo "Done." . PHP_EOL;
}

$opts = getopt("", $longopts);

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
