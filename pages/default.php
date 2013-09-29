<?php

    if ($router->countParams() > 0)
    {
        try
        {
            $_SESSION['quiz'] = new Quiz($router->getParam(0));
            $_SESSION['quizactive'] = true;
            $tpl = new Template('pages/welcomequiz');
            $tpl->addVar('quiz', $_SESSION['quiz']);
            $design->setContentTpl($tpl);
            $_SESSION['page'] = 'question';
            unset($_SESSION['action']);
        }
        catch (Exception $e)
        {
            die($e->getMessage());
            $router->redirectToPage('error', array(urlencode($e->getMessage())));
        }
    }
    else
    {
        $welcomeTpl = new Template('pages/welcome');
        $design->setContentTpl($welcomeTpl->addVar('quizes', Quiz::getQuizes()));
    }
?>
