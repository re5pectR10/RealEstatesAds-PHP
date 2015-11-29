<?php
use \FW\View\View;
use \FW\Helpers\Common;
use \FW\HTML\Form;
use \FW\Session\Session;
/* @var $title string */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?= $title ?> - Estates Ads</title>
    <?= Form::script('https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js') ?>
    <?= Form::script('js/bootstrap.min.js') ?>
    <?= Form::script('js/main.js') ?>

    <?= Form::style('css/bootstrap.min.css') ?>
    <?= Form::style('css/shop-homepage.css') ?>
    <?= Form::style('css/ekko-lightbox.min.css') ?>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <?= Form::script('https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js') ?>
    <?= Form::script('https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js') ?>
    <![endif]-->
</head>
<body>
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?= Common::getBaseURL() ?>">Estates</a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <?= View::getLayoutData('topBar') ?>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
</nav>

<?php if(Session::hasMessage()): ?>
    <div class="alert alert-success" role="alert"><?= Session::getMessage() ?></div>
<?php endif; ?>
<?php if(Session::hasError()): ?>
    <div class="alert alert-danger" role="alert">
        <?php foreach(Session::getError() as $error): ?>
            <p>
                <?= $error ?>
            </p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>