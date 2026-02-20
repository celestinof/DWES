<?php


 function autoload_12ada217e3b19962135159a32ba704ba($class)
{
    $classes = array(
        'Clases1\ClasesOperacionesService' => __DIR__ .'/ClasesOperacionesService.php'
    );
    if (!empty($classes[$class])) {
        include $classes[$class];
    };
}

spl_autoload_register('autoload_12ada217e3b19962135159a32ba704ba');

// Do nothing. The rest is just leftovers from the code generation.
{
}
