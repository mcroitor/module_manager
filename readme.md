# git download manager

Git Download Manager allows download project from GITHUB (or other GIT) repositories.

## usage

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