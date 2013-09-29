<?php
    $tpl = new Template('pages/completed');
    $_SESSION['completed'] = true;
    $_SESSION['page'] = 'default';
    $design->setContentTpl($tpl);
?>
