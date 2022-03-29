# module download manager

Module Download Manager allows download project from GITHUB (or other GIT) repositories.

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
  "entrypoint" : "<entry point>"
 }
```

The next default values are set:

 - _host_ : "https://github.com/"
 - _branch_ : "main"
 - _destination_ : "./modules/"
 - _entrypoint_ : not set

 If _entrypoint_ is missing, no files will be included to autoload.
 
 It means, the minimal configuration file with 2 modules will be:
 
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
 
