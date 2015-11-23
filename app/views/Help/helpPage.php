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
<h1 class="page-header text-center"><?= $title ?></h1>
<?php foreach($data as $index => $item): ?>
    <div class="text-center">
        <a class="btn btn-link breadcrumb" href="helppage/<?= $index ?>"><?= $item['method'] . ' ' .$item['url'] ?></a>
    </div>
<?php endforeach; ?>
</body>
</html>