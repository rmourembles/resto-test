#resto unit tests

## Prerequesites

* resto
* resto test database
* phpunit (https://phpunit.de/getting-started.html)

## Init Context

    ./scripts/initContext -p <PATH>

## Run tests

Launch test script 
        ./resto_unitTests.sh

## Run test manually 
    
    phpunit --bootstrap src/autoload.php tests/myTest.php

