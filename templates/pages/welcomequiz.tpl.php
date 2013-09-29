<h2>Willkommen beim Quiz "<?php echo $quiz->getConfigEntry('name') ?>"</h2>
<p>
    <?php echo nl2br($quiz->getWelcomeMessage()) ?>
</p>
<p>
    <a href="<?php echo dirname($_SERVER['SCRIPT_NAME']) ?>/index.php">Let's Go!</a>
</p>
