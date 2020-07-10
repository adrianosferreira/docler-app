# Docler Tasks REST API

[![Build Status](https://travis-ci.org/adrianosferreira/customer-collector-wp-plugin.svg?branch=master)](https://travis-ci.org/adrianosferreira/docler-app)
[![Build Status](https://codecov.io/gh/adrianosferreira/customer-collector-wp-plugin/branch/master/graph/badge.svg)](https://codecov.io/gh/adrianosferreira/docler-app)

## Description

This REST API has the following routes:

| Route        | Methods           | Description  |
| ------------- |:-------------:| -----:|
| /tasks      | GET, POST | The GET endpoint should be used to return all the tasks stored. The POST should be used to create a new task. |  
| /task/{id}      | GET, PUT      |   The GET endpoint should be used to fetch a data from a particular task. The PUT endpoint should be used for update an already stored resource | 