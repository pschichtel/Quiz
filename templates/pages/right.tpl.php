<h2>Richtig!</h2>
<p>
    <?php echo $question->getRightText() ?>
</p>
<p>
    <a href="<?php echo $_SERVER['SCRIPT_NAME'] ?>">Weiter</a>
</p>
<?php if ($quiz->getConfigEntry('playsounds') == 'true'): ?>
<audio src="<?php echo $question->getRightSound() ?>" autoplay="" type="audio/ogg">
    <span class="error">
        Dein Browser unterstützt das Audio-Tag nicht und kann daher keine Sounds abspielen!<br>
        Wenn du Sounds möchtest musst du einen aktuellen HTML5-fähigen Browser benutzen.
    </span>
</audio>
<?php endif ?>
