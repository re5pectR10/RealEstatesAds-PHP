<?php

namespace Models\ViewModels;


class EstateViewModel extends EstateBasicViewModel{

    public $floor;
    public $main_image_id;
    public $is_furnished;
    public $phone;
    public $description;
    /**
     * @var $images ImageViewModel[]
     */
    public $images;
} 