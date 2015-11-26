<?php
use \FW\View\View;
use \FW\Helpers\Common;
use \FW\HTML\Form;
use \FW\Security\Auth;
?>
<?= View::getLayoutData('header') ?>

    <!-- Page Content -->
    <div class="container">

        <div class="item">

            <div class="media">

                <div class="media-left">
                    <img style="max-width: 300px;max-height: 200px" src="<?=  Common::getBaseDir() . 'images/' .  $estate['main_image'] ?>" alt="No Image">
                </div>

                <div class="media-body">
                    <h3 class="pull-right"><?= $estate['price'] ?> EUR</h3>
                    <h5>ID: <?= $estate['id'] ?></h5>
                    <address><?= $estate['city'] ?>: <?= $estate['location'] ?></address>
                    <p>Category: <?= $estate['category'] ?></p>
                    <h4><?= $estate['area'] ?> m2 (<?= floor($estate['price'] / $estate['area']) ?> EUR per m2)</h4>
                    <h3><?= $estate['ad_type'] == 1 ? 'For Sale' : 'For Rent' ?></h3>
                    <h4>Floor: <?= $estate['floor'] ?></h4>
                    <h4><?= $estate['is_furnished'] ? 'Furnished' : 'Unfurnished' ?></h4>
                    <h4>Phone: <?= $estate['phone'] ?></h4>
                    <?php if(Auth::isUserInRole(array('admin'))): ?>
                        <a class="btn btn-primary" href="<?= Common::getBaseURL() ?>/admin/estate/<?= $estate['id'] ?>/edit">Edit</a>
                    <?php endif ?>
                    <a class="btn btn-primary" href="<?= Common::getBaseURL() ?>/estate/<?= $estate['id'] ?>/message">Send Message</a>
                </div>

                <div class="media-middle">
                    <p> <?= $estate['description'] ?></p>
                </div>

                <?php if(isset($estate['images'])): ?>
                    <div class="media-bottom">
                        <?php foreach($estate['images'] as $image): ?>
                            <img style="max-width: 150px;max-height: 100px" src="<?=  Common::getBaseDir() . '/images/' .  $image['image'] ?>" alt="No Image">
                        <?php endforeach; ?>
                    </div>
                <?php endif ?>
            </div>

        </div>

    </div>

<?= View::getLayoutData('footer') ?>