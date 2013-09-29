<h2>Falsch!</h2>
<p>
    <?php echo $question->getWrongText() ?>
    <?php if ($quiz->getConfigEntry('fail_retry') == 'true'): ?>
    <p>
        Versuch es nochmal! <a href="<?php echo $_SERVER['SCRIPT_NAME'] ?>">Zurück</a>
    </p>
    <?php else: ?>
    <p>
        <?php if ($quiz->getConfigEntry('fail_showcorrect') == 'true'): ?>
        <p>
            Die korrekte Antwort wäre folgende gewesen:
            <?php echo $question->getCorrectAnswer() ?>
        </p>
        <?php endif ?>
        <a href="<?php echo $_SERVER['SCRIPT_NAME'] ?>">Weiter</a>
    </p>
    <?php endif ?>
</p>
<?php if ($quiz->getConfigEntry('playsounds') == 'true'): ?>
<audio src="<?php echo $question->getWrongSound() ?>" autoplay="" type="audio/ogg">
    <span class="error">
        Dein Browser unterstützt das Audio-Tag nicht und kann daher keine Sounds abspielen!<br>
        Wenn du Sounds möchtest musst du einen aktuellen HTML5-fähigen Browser benutzen.
    </span>
</audio>
<?php endif ?>