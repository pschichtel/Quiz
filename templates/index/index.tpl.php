<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $title; if (count($minorTitles)) echo ' :: ' . implode(' :: ', $minorTitles) ?></title>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="<?php echo dirname($_SERVER['SCRIPT_NAME']) ?>/css/style.css">
        <?php if(isset($_SESSION['quiz']) && !is_null($_SESSION['quiz']) && $_SESSION['quiz']->getBackground()): ?>
        <style type="text/css">
            body {
                background-image: url('<?php echo $_SESSION['quiz']->getBackground() ?>');
            }
        </style>
        <?php endif ?>
    </head>
    <body>
        <div id="container">
            <div id="header">
                <?php $this->displaySubtemplate('header') ?>
            </div>
            <div id="colcontainer">
                <div id="col_right">
                    <div id="content">
                        <?php $this->displaySubtemplate('content') ?>
                    </div>
                </div>
                <div id="col_left">
                    <?php if (isset($_SESSION['quizactive'])): ?>
                    <div id="miscbox">
                        <a href="<?php echo $_SERVER['SCRIPT_NAME'] . '/endquiz' ?>">Quiz beenden</a>
                    </div>
                    <?php endif ?>
                </div>
                <div class="clear"></div>
            </div>
            <div id="footer">
                <?php $this->displaySubtemplate('footer') ?>
            </div>
        </div>
    </body>
</html>