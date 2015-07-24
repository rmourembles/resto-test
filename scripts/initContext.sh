#!/bin/bash

USER=admin
SUPERUSER=postgres
DB=resto
PASSWORD=admin


usage="## resto tests - Init context \n\n  Usage $0 -p <path to test folder, must contains data folder> \n"
while getopts "p:h" options; do
    case $options in
        p ) PATH=`echo $OPTARG`;;
        h ) echo -e $usage;;
        \? ) echo -e $usage
            exit 1;;
        * ) echo -e $usage
            exit 1;;
    esac
done
if [ "$PATH" = "" ]
then
    echo -e $usage
    exit 1
fi


# Change password !!!
SHA1PASSWORD=`php -r "echo sha1('$PASSWORD');"`
ACTIVATIONCODE=`php -r "echo sha1(mt_rand() . microtime());"`
psql -d $DB -U $SUPERUSER << EOF
INSERT INTO usermanagement.users (email,groups,username,password,activationcode,activated,registrationdate) VALUES ('$USER','admin','$USER','$SHA1PASSWORD','$ACTIVATIONCODE', 1, now());
EOF

curl -X POST -H "Content-Type: application/json" -d @$PATH/Landsat.json http://admin:admin@localhost/resto/collections
curl -X POST -H "Content-Type: application/json" -d @$PATH/LANDSAT5_TM_XS_20110520_N2A_France-MetropoleD0005H0003.xml http://admin:admin@localhost/resto/collections/Landsat