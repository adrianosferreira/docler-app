# Docler Tasks REST API

[![Build Status](https://travis-ci.org/adrianosferreira/customer-collector-wp-plugin.svg?branch=master)](https://travis-ci.org/adrianosferreira/docler-app)
[![Build Status](https://codecov.io/gh/adrianosferreira/customer-collector-wp-plugin/branch/master/graph/badge.svg)](https://codecov.io/gh/adrianosferreira/docler-app)

## Description

The REST API is running in 2 docker containers inside of a public AWS EC2 instance:

- Application container
- Database container

The project comes out of the box with some Make commands:

- Make a production build of the plugin and place it inside of the `./build` folder:
```
$ make build
```

- Run unit tests, style check and static files check:
```
$ make test
```

- Development build:
```
$ make dev
```

## Routes

This REST API has the following routes:

| Route        | Methods           | Description  |
| ------------- |:-------------:| -----:|
| /tasks      | GET, POST | The GET endpoint should be used to return all the tasks stored. The POST should be used to create a new task. |  
| /task/{id}      | GET, PUT      |   The GET endpoint should be used to fetch a data from a particular task. The PUT endpoint should be used for update an already stored resource | 

## Stack

- PHP
- Slim Framework
- Doctrine
- MySQL
- PHPStan
- PHP_CodeSniffer
- PHPUnit