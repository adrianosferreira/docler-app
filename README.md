# Docler Tasks REST API

[![Build Status](https://travis-ci.org/adrianosferreira/customer-collector-wp-plugin.svg?branch=master)](https://travis-ci.org/adrianosferreira/docler-app)
[![Build Status](https://codecov.io/gh/adrianosferreira/customer-collector-wp-plugin/branch/master/graph/badge.svg)](https://codecov.io/gh/adrianosferreira/docler-app)

## Description

The REST API is running in 2 docker containers inside of a public AWS EC2 instance.

- Application container (PHP 7.4 apache)
- Database container (MySQL 8.0)

It is available at: http://ec2-18-222-76-112.us-east-2.compute.amazonaws.com/tasks

## Routes

This REST API has the following routes:

| Route        | Methods           | Description  |
| ------------- |:-------------:| -----|
| /tasks      | GET, POST | The GET endpoint should be used to return all the tasks stored. The POST should be used to create a new task. |  
| /task/{id}      | GET, PUT, DELETE      |   The GET endpoint should be used to fetch a data from a particular task. The PUT endpoint should be used for update an already stored resource. The DELETE endpoint should be used to delete a resource. | 

## Stack

- PHP
- Slim Framework
- Doctrine ORM
- Doctrine Migrations
- MySQL
- PHPStan
- PHP_CodeSniffer
- PHPUnit

## Make commands

Make a production build of the application and place it inside of the `./build` folder:
```
$ make build
```

Run unit tests, standards check and static files check:
```
$ make test
```

Development build:
```
$ make dev
```