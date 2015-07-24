#resto unit tests

## Prerequesites

* resto
* phpunit (https://phpunit.de/getting-started.html)

## Init Context

First you have to initialized the context. Launch the script to create collections, add features, add users... to resto.

    ./scripts/initContext -p <path to resto-test>

## Run test 
    
    phpunit --bootstrap conf/autoload.php tests/myTest.php <path to resto>

