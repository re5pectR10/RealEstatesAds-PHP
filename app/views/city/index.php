<?php
use \FW\View\View;
use \FW\Helpers\Common;
?>
<?= View::getLayoutData('header') ?>

    <div class="container">

        <div class="row">

            <div class="col-md-12">
                <h1>Cities</h1>
                <a class="btn btn-success" href="<?= Common::getBaseURL() ?>/admin/city/add">Add</a>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>City</th>
                        <th>Tools</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach($cities as $c):
                        ?>
                        <tr>
                            <td><?= $c['name'] ?></td>
                            <td>
                                <a class="btn btn-primary" href="<?= Common::getBaseURL() ?>/admin/city/<?= $c['id'] ?>/edit">Edit</a>
                                <a class="btn btn-danger" href="<?= Common::getBaseURL() ?>/admin/city/<?= $c['id'] ?>/delete">Delete</a>
                            </td>
                        </tr>
                    <?php
                    endforeach;
                    ?>
                    </tbody>
                </table>
            </div>

        </div>

    </div>

<?= View::getLayoutData('footer') ?>