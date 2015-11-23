<?php
use \FW\HTML\Form;
?>
<html>
<head>
    <title><?= $title ?></title>
    <?= Form::script('https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js') ?>
    <?= Form::script('js/bootstrap.min.js') ?>
    <?= Form::style('css/bootstrap.min.css') ?>
</head>
<body>
<h1 class="page-header"><?= $title ?></h1>
<h3 class="panel-heading"><?= $data['method'] ?> method</h3>
        <pre><?= $data['params'] ?></pre>
</body>
</html>