<?php
    if (!isset($_SESSION['action']))
    {
        $_SESSION['action'] = 'showquestion';
    }
    if ($_SESSION['action'] == 'showquestion')
    {
        if ($quiz->key() >= count($quiz))
        {
            $router->redirectToPage('completed');
        }
        
        $tpl = new Template('pages/question');
        $question = $quiz->current();
        $questionID = $quiz->key() + 1;
        $tpl->addVarsAssoc(array(
            'questionID' => $questionID,
            'question' => $question,
            'message' => $router->getParam(0)
        ));
        Design::addMinorTitle('Frage ' . $questionID);

        $design->setContentTpl($tpl);

        $_SESSION['action'] = 'validate';
    }
    elseif ($_SESSION['action'] == 'validate' && isset($_POST['answer']))
    {
        $answer =& $_POST['answer'];
        if (!is_numeric($answer))
        {
            unset($_SESSION['action']);
            $router->redirectToPage('question', array(rawurlencode('Deine Antwort war ungültig!')));
        }

        $question = $quiz->current();

        if (!isset($question[$answer]))
        {
            unset($_SESSION['action']);
            $router->redirectToPage('question', array(rawurlencode('Deine Antwort war ungültig!')));
        }

        if ($question[$answer]->isCorrect())
        {
            $_SESSION['action'] = 'right';
            $router->reload();
        }
        else
        {
            $_SESSION['action'] = 'wrong';
            $router->reload();
        }
    }
    elseif ($_SESSION['action'] == 'right')
    {
        $tpl = new Template('pages/right');
        $tpl->addVar('question', $quiz->current());
        $quiz->next();
        $quiz->increasePoints();
        unset($_SESSION['action']);
        $design->setContentTpl($tpl);
    }
    elseif($_SESSION['action'] == 'wrong')
    {
        $tpl = new Template('pages/wrong');
        $tpl->addVar('question', $quiz->current());
        if ($quiz->getConfigEntry('fail_retry') == 'true')
        {
            if ($quiz->getConfigEntry('retry_decreasepoints') == 'true')
            {
                $quiz->decreasePoints();
            }
            unset($_SESSION['action']);
        }
        else
        {
            $quiz->next();
            $quiz->decreasePoints();
            unset($_SESSION['action']);
        }

        $quiz->failed();

        $design->setContentTpl($tpl);
    }
    else
    {
        unset($_SESSION['action']);
        $params = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $params[] = rawurlencode('Du musst eine Antwort auswählen um fortzufahren!');
        }
        $router->redirectToPage('question', $params);
        
    }

?>
