<?php
use \FW\View\View;
use \FW\Helpers\Common;
/* @var $categories \Models\ViewModels\CategoryViewModel[] */
?>
<?= View::getLayoutData('header') ?>

    <div class="container">

        <div class="row">

            <div class="col-md-12">
                <h1>Categories</h1>
                <a class="btn btn-success" href="<?= Common::getBaseURL() ?>/admin/category/add">Add</a>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Category</th>
                        <th>Tools</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach($categories as $c):
                        ?>
                        <tr>
                            <td><?= $c->name ?></td>
                            <td>
                                <a class="btn btn-primary" href="<?= Common::getBaseURL() ?>/admin/category/<?= $c->id ?>/edit">Edit</a>
                                <a onclick="return confirm('Are your sure?')" class="btn btn-danger" data-id="<?= $c->id ?>" href="<?= Common::getBaseURL() ?>/admin/category/<?= $c->id ?>/delete">Delete</a>
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