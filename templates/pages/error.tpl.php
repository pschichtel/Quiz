<h2>Es ist ein Fehler aufgetreten!</h2>
<p>
    Im System ist ein Fehler aufgetreten, der nicht abgefangen werden konnte!<br>
    <?php if ($msg): ?>
    Folgende Fehlermeldung wurde angegeben:<br>
    <p>
        <?php echo $msg ?>
    </p>
    <?php else: ?>
    Die Ursache des Problems ist leider unbekannt, melden sie dem Entwickler bitte das Problem.
    <?php endif ?>
</p>
