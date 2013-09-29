<h2>Willkommen</h2>
<p>
    Willkommen im Infected Quiz System von <a href="http://code-infection.de" title="Code Infection">Phillip Schichtel</a>
</p>
<p>
    Hier kannst du das Quiz auswählen, dass du machen möchtest:
    <ol>
    <?php foreach ($quizes as $quiz): ?>
        <li><a href="<?php echo dirname($_SERVER['SCRIPT_NAME']) ?>/index.php/<?php echo urlencode($quiz) ?>"><?php echo $quiz ?></a></li>
    <?php endforeach ?>
    </ol>
</p>