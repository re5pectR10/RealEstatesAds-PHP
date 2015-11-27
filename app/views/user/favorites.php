<?php
use \FW\View\View;
use \FW\Helpers\Common;
use \FW\Security\Auth;
?>
<?= View::getLayoutData('header') ?>

    <!-- Page Content -->
    <div class="container">

    <div class="list-group">
        <?php /* @var $estates array */ ?>
        <?php foreach($estates as $e): ?>
            <div class="list-group-item">
                <div class="media">

                    <div class="media-left col-lg-2">
                        <img class="center-block" style="max-width: 150px;max-height: 100px" src="<?=  Common::getBaseDir()
                            . '/images/'
                            . (isset($e['name']) ? $e['name'] : 'No_image_available.jpg') ?>" alt="No Image">
                    </div>

                    <div class="media-body">
                        <h3 class="pull-right"><?= $e['price'] ?> EUR</h3>
                        <h5>ID: <?= $e['id'] ?></h5>
                        <address><?= $e['city'] ?>: <?= $e['location'] ?></address>
                        <p>Category: <?= $e['category'] ?></p>
                        <a class="btn bg-success pull-right" href="<?= Common::getBaseURL() ?>/estate/<?= $e['id'] ?>">Details</a>
                        <a class="btn bg-primary pull-right" href="<?= Common::getBaseURL() ?>/estate/favorites/<?= $e['id'] ?>/remove">Remove From Favourites</a>
                        <h4><?= $e['area'] ?> m2 (<?= $e['ad_type'] == 1 ? 'For Sale' : 'For Rent' ?>)</h4>
                        <?php if(Auth::isUserInRole(array('admin'))): ?>
                            <a class="btn btn-primary" href="<?= Common::getBaseURL() ?>/admin/estate/<?= $e['id'] ?>/edit">Edit</a>
                        <?php endif ?>
                    </div>

                </div>
            </div>
        <?php endforeach; ?>
    </div>

    </div>

<?= View::getLayoutData('footer') ?>