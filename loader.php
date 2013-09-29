<?php

    defined('DS')           or define('DS', DIRECTORY_SEPARATOR);
    defined('iQUIZ_ROOT')   or define('iQUIZ_ROOT', dirname(__FILE__));
    
    function __autoload($class)
    {
        static $classmap = array(
            'Template' => 'Template.php',
            'Design' => 'Design.php',
            'IView' => 'IView.php',
            'Quiz' => 'Quiz.php',
            'QuizQuestion' => 'Quiz.php',
            'QuizAnswer' => 'Quiz.php',
            'IFilter' => 'IFilter.php',
            'Router' => 'Router.php'
        );

        if (isset($classmap[$class]))
        {
            if (file_exists(iQUIZ_ROOT . DS . 'includes' . DS . $classmap[$class]))
            {
                require_once iQUIZ_ROOT . DS . 'includes' . DS . $classmap[$class];
            }
        }
    }

?>
