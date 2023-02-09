# module download manager

Module Download Manager allows download project from GITHUB (or other GIT) repositories. The file 
`manager.php` is a standalone minified version of manager, `src` folder contains development version.

## usage

### configuration

For the first, you need to have a `config.json` configuration file, that describes modules. The common structure of module in this file is the next:

```json
 {
  "host" : "https://github.com/",
  "user" : "<user>", 
  "repository" : "<repo>",
  "branch" : "<branch>",
  "destination" : "<destination>",
  "source" : "<source>",
  "entrypoint" : "<entry point>"
 }
```

The next default values are set:

 - _host_ : "https://github.com/"
 - _branch_ : "main"
 - _destination_ : "./modules/"
 - _entrypoint_ : not set

 If _entrypoint_ is missing, no files will be included to autoload.
 
 It means, the minimal configuration file `modules.json` with 2 modules will be:
 
 ```json
 [
   {
     "user" : "mcroitor",
     "repository" : "logger"
   },
   {
     "user" : "mcroitor",
     "repository" : "database"
   }
 ]
 ```
 
### check usage

Just type in the command line:

```shell
php manager.php --help
```

or without any key.

### install modules

Type in command line next command:

```shell
php manager.php --install
```

If you want to install modules from other config file, `modules.json` for example,
you can specify this in the `--config` key:

```shell
php manager.php --install --config=modules.json
```

### reinstall modules

Type in command line next command for reinstalling / updating modules:

```shell
php manager.php --reinstall
```

### drop modules

The next command will remove all downloaded / installed modules:

```shell
php manager.php --drop
```
