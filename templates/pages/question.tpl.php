<form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" method="post">
    <fieldset>
        <legend>Frage <?php echo $questionID ?></legend>
        <?php if ($message): ?>
        <p>
            <span class="error"><?php echo rawurldecode($message) ?></span>
        </p>
        <?php endif ?>
        <p>
            <span><?php echo $question->getText() ?></span>
        </p>
        <p>
        <?php foreach ($question as $index => $answer): ?>
            <input type="radio" name="answer" value="<?php echo $index ?>">
            <label><?php echo $answer ?></label><br>
        <?php endforeach ?>
        </p>
        <p>
            <input type="submit" value="Abschicken">
        </p>
    </fieldset>
</form>