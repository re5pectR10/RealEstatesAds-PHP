<?php

namespace Controllers;

use FW\Helpers\Common;
use FW\Helpers\Redirect;
use FW\Session\Session;

class ImageController {

    /**
     * @var \Models\Image
     */
    private $image;

    public function delete($id) {
        $image = $this->image->getById($id);
        if ($this->image->delete($id) !== 1) {
            Session::setError('can not delete this image');
            Redirect::back();
        }

        unlink(Common::getPublicFilesDir() . $image['name']);

        Session::setMessage('The image has been deleted');
        Redirect::back();
    }
} 