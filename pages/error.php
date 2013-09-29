<?php
    $tpl = new Template('pages/error');
    $tpl->addVar('msg', $router->getParam(0));
?>
