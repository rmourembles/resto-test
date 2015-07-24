<?php

/*
 * Autoload controllers and modules
 */
function autoload($className) {
    
    $path_to_resto = "/home/remi/share/resto_test/";
    
    foreach (array(
        $path_to_resto . 'include/resto/',
        $path_to_resto . 'include/resto/Drivers/',
        $path_to_resto . 'include/resto/Collections/',
        $path_to_resto . 'include/resto/Models/',
        $path_to_resto . 'include/resto/Dictionaries/',
        $path_to_resto . 'include/resto/Modules/',
        $path_to_resto . 'include/resto/Routes/', 
        $path_to_resto . 'include/resto/Utils/', 
        $path_to_resto . 'include/resto/XML/',
        $path_to_resto . 'lib/iTag/',
        $path_to_resto . 'lib/JWT/') as $current_dir) {
        $path = $current_dir . sprintf('%s.php', $className);
        if (file_exists($path)) {
            include $path;
            return;
        }
    }
}
spl_autoload_register('autoload');

/*
 * Load manualy mocked classes
 */
//Resto
include '/home/remi/share/02_TEST/tests/mock/Resto.php';