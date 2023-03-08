# personal modules definitions

Here a list of personal projects / modules are defined with module definition.

## \mc\database

A wrapper on sqlite / mysql. Provides classes `database`, `query` and `crud` .

```json
{
    "user" : "mcroitor",
    "repository" : "database",
    "destination" : "modules/database",
    "source" : "src"
}
```

## \mc\logger

Simple logger. Comes with `stdout` object, but can be created a custom logger (file output or database output).

```json
{
    "user": "mcroitor",
    "repository": "logger",
    "entrypoint": "mc/logger.php",
    "source": "src",
    "destination" : "modules/logger"
}
```

## metadb

I use this project for creating structures mapped with tables in the database.

```json
{
    "user": "mcroitor",
    "repository": "metadb",
    "source": "src",
    "branch" : "main",
    "destination" : "metadb"    
}
```

## router

The router class do simple thing: routes by label to the callable. The callable need to be registered. This router is GET method based.

```json
{
    "user" : "mcroitor",
    "repository" : "router",
    "destination" : "modules/router",
    "branch" : "master",
    "source" : "src"
}
```

## classifier

A classifier represents named associated array. It is usefull for enumerating, defining dictionaries or creating translations.

```json
{
    "user" : "mcroitor",
    "repository" : "classifier",
    "destination" : "modules/classifier",
    "branch" : "main",
    "source" : "src"
}
```

## \mc\http

PHP curl wrapper

```json
{
    "user" : "mcroitor",
    "repository" : "mc-http",
    "destination" : "modules/mc-http",
    "branch" : "main",
    "source" : "."
}
```