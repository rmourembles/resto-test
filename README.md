# Resto unit tests

You can find here tests files for resto (https://github.com/jjrom/resto).

## Prerequesites

* resto (https://github.com/jjrom/resto)
* phpunit (https://phpunit.de/getting-started.html)    

## Configuration

Please set the path to resto in conf/configuration.xml
   
    env name="resto" value="ABSOLUTE_PATH_TO_RESTO"
    
## Resto configuration

### Create test database

In order to create a test database, you have to use resto installation script :

    ABSOLUTE_PATH_TO_FULL_RESTO/_install/installDB.sh -F -p resto -d restotest

### Create an admin user

Whith email = admin and groups = admin

    SHA1PASSWORD=`php -r "echo sha1('admin');"`
    ACTIVATIONCODE=`php -r "echo sha1(mt_rand() . microtime());"`
    psql -d restotest -U postgres << EOF
    INSERT INTO usermanagement.users (email,groups,username,password,activationcode,activated,registrationdate) VALUES ('admin','admin','admin','$SHA1PASSWORD','$ACTIVATIONCODE', 1, now());
    EOF

## Run test 
    
    cd resto-test
    phpunit -c conf/configuration.xml --bootstrap conf/autoload.php --coverage-html /absolute/path/to/the/folder/where/you/want/to/store/reports tests/t_unit.php

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