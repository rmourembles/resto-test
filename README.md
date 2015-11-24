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

Please set path to resto in configuration.xml
   
## Run test 
    
    cd resto-test
    phpunit -c configuration.xml


    PHPUnit 4.7.7 by Sebastian Bergmann and contributors.
    .................................
    Time: 1.43 minutes, Memory: 363.00Mb
    OK (84 tests, 264 assertions)


Add --coverage-html /where/you/want/to/store/reports to get coverage reports.

## Analysis

Open the index.html generated file (located in the folder where you have decided to store reports).