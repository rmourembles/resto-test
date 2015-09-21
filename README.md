# Resto unit tests

You can find here tests files for resto (https://github.com/jjrom/resto).

## Prerequesites

* resto (https://github.com/jjrom/resto)
* phpunit (https://phpunit.de/getting-started.html)    

## Resto configuration

### Create test database

In order to create a test database, you have to use resto installation script :

    ABSOLUTE_PATH_TO_FULL_RESTO/_install/installDB.sh -F -p resto -d restotest

### Create an admin user

    ABSOLUTE_PATH_TO_FULL_RESTO/_install/createAdminUser.sh -d restotest -u admin -p admin

## Configuration

Please set path to resto in conf/configuration.xml
   
## Run test 
    
    cd resto-test
    phpunit -c conf/configuration.xml --bootstrap conf/autoload.php tests/t_unit.php


    PHPUnit 4.7.7 by Sebastian Bergmann and contributors.
    .................................
    Time: 56.73 seconds, Memory: 153.50Mb
    OK (33 tests, 126 assertions)


## Analysis

Open the index.html generated file (located in the folder where you have decided to store reports).

# Use cases

## t_unit.php

### Create/Delete/Manage collection

* Create license
* Create collection
* Get collection attributes
* Add feature to collection
* Remove feature from collection
* Delete collection
* Delete license

### Manage user rights

* Create user
* Validate user
* Get user attributes
* Get user rights

### Search 

Validate following search parameters (with and without results) :
* update
* startdate
* location
* identifier (uuid and productIdentifier)