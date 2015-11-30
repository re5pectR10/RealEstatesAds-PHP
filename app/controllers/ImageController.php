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
        /* @var $image \Models\ViewModels\ImageViewModel */
        $image = $this->image->getById($id);
        if ($this->image->delete($id) !== 1) {
            Session::setError('something went wrong. Try again');
            Redirect::back();;
        }

        $this->unlinkImage($image->name);

        Session::setMessage('The image has been deleted');
        Redirect::back();
    }

    public function unlinkImage($name) {
        if (file_exists(Common::getPublicFilesDir() . EstateController::IMAGE_DIR . DIRECTORY_SEPARATOR . $name)) {
            unlink(Common::getPublicFilesDir() . EstateController::IMAGE_DIR . DIRECTORY_SEPARATOR . $name);
        }
        if(file_exists(Common::getPublicFilesDir() . EstateController::IMAGE_DIR . DIRECTORY_SEPARATOR . EstateController::IMAGE_THUMBNAIL_PREFIX . $name)) {
            unlink(Common::getPublicFilesDir() . EstateController::IMAGE_DIR . DIRECTORY_SEPARATOR . EstateController::IMAGE_THUMBNAIL_PREFIX . $name);
        }
    }

    /**
     * @param $images\Models\ViewModels\ImageViewModel[]
     * @return bool
     */
    public function removeMultiple(array $images) {
        if (empty($images)) {
            return true;
        }
        $imagesId = array();
        foreach ($images as $image) {
            $imagesId[] = $image->id;
            $this->unlinkImage($image->name);
        }

        return $this->image->deleteMultiple($imagesId) > 0;
    }
} 