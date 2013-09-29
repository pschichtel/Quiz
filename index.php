<?php

    error_reporting(-1);

    ob_start('ob_gzhandler');

    require_once 'loader.php';

    session_set_cookie_params(time() + 60 * 60 * 2);
    session_name('sid');
    session_start();
    
    $design = new Design('Infected Quiz System');
    $quiz =& $_SESSION['quiz'];
    if (isset($quiz) && $quiz !== null && !isset($_SESSION['completed']))
    {
        $design->addVar('quiz', $quiz);
        Design::addMinorTitle($quiz->getConfigEntry('name'));
    }
    elseif (isset($_SESSION['completed']))
    {
        unset($_SESSION['quizactive']);
        unset($_SESSION['completed']);
        unset($_SESSION['quiz']);
    }

    $router = Router::instance();

    if ($router->getParam(0) == 'endquiz')
    {
        $_SESSION['completed'] = true;
        $router->redirectToPage('default');
    }

    include $router->getPagePath();
    
    echo $design;

    ob_end_flush();

?>
