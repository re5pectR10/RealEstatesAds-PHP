<?php
use \FW\Helpers\Common;
use \FW\Security\Auth;
/* @var $estate \Models\ViewModels\EstateBasicViewModel */
?>
<div class="media-left col-lg-2">
    <img data-toggle="lightbox" data-remote="<?=  Common::getBaseDir() . 'images/' .  $estate->image ?>" class="image-cursor center-block" style="max-width: 150px;max-height: 100px" src="<?=  Common::getBaseDir() . 'images/' .  (isset($estate->thumbnailName) ? $estate->thumbnailName : $estate->image) ?>" alt="No Image">
</div>

<div class="media-body">
    <a href="<?= Common::getBaseURL() ?>/estate/<?= $estate->id ?>"><h3 class="pull-right"><?= $estate->price ?> EUR</h3></a>
    <h5>ID: <?= $estate->id ?></h5>
    <a href="<?= Common::getBaseURL() ?>/estate/<?= $estate->id ?>"><address><?= $estate->city ?>: <?= $estate->location ?></address></a>
    <p>Category: <?= $estate->category ?></p>
    <a class="btn bg-success pull-right" href="<?= Common::getBaseURL() ?>/estate/<?= $estate->id ?>">Details</a>
    <h4><?= $estate->area ?> m2 (<?= $estate->ad_type == 1 ? 'For Sale' : 'For Rent' ?>)</h4>
    <?php if(Auth::isUserInRole(array('admin'))): ?>
        <a onclick="return confirm('Are your sure?')" class="btn btn-danger" href="<?= Common::getBaseURL() ?>/admin/estate/<?= $estate->id ?>/delete">Delete</a>
        <a class="btn btn-primary" href="<?= Common::getBaseURL() ?>/admin/estate/<?= $estate->id ?>/edit">Edit</a>
    <?php endif ?>
</div>