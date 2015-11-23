<?php

namespace Controllers;


use FW\Security\Auth;
use FW\Helpers\Redirect;
use FW\Session\Session;
use FW\Security\Validation;
use FW\View\View;
use Models\PromotionModel;

class PromotionController {

    /**
     * @var \Models\Promotion
     */
    private $promotion;
    /**
     * @var \Models\Category
     */
    private $category;
    /**
     * @var \Models\Product
     */
    private $product;

    public function getAll() {
        $result['promotions'] = $this->promotion->getPromotions();
        $result['isEditor'] = Auth::isUserInRole(array('editor', 'admin'));
        View::make('promotion.promotions', $result);
        if (Auth::isAuth()) {
            View::appendTemplateToLayout('topBar', 'top_bar/user');
        } else {
            View::appendTemplateToLayout('topBar', 'top_bar/guest');
        }

        View::appendTemplateToLayout('header', 'includes/header')
            ->appendTemplateToLayout('footer', 'includes/footer')
            ->render();
    }

    public function delete($id) {
        if ($this->promotion->delete($id) !== 1) {
            Session::setError('can not delete this product');
            Redirect::back();
        }

        Session::setMessage('done');
        Redirect::to('/promotion');
    }

    public function getAdd() {
        $result['title']='Shop';
        $result['action'] = '/promotion/add';
        $result['submit'] = 'add';
        $categories = $this->category->getCategories();
        $result['categories'][] = array('text' => 'No category', 'options' => array('value'=>0));
        foreach($categories as $c) {
            $currentCategory = array();
            $currentCategory['text'] = $c['name'];
            $currentCategory['options'] = array('value' => $c['id']);
            $result['categories'][] = $currentCategory;
        }
        $products = $this->product->getProducts();
        $result['products'][] = array('text' => 'No product', 'options' => array('value'=>0));
        foreach($products as $c) {
            $currentProduct = array();
            $currentProduct['text'] = $c['name'];
            $currentProduct['options'] = array('value' => $c['id']);
            $result['products'][] = $currentProduct;
        }
        View::make('promotion.add', $result);
        if (Auth::isAuth()) {
            View::appendTemplateToLayout('topBar', 'top_bar/user');
        } else {
            View::appendTemplateToLayout('topBar', 'top_bar/guest');
        }

        View::appendTemplateToLayout('header', 'includes/header')
            ->appendTemplateToLayout('footer', 'includes/footer')
            ->render();
    }

    public function postAdd(PromotionModel $promotion) {
        $validator = new Validation();
        $validator->setRule('required', $promotion->discount, null, 'discount');
        $validator->setRule('required', $promotion->date, null, 'date');
        $validator->setRule('date', $promotion->date, null, 'date');
        if (!$validator->validate()) {
            Session::setError($validator->getErrors()[0]);
            Redirect::back();
        }
        if ($this->promotion->add($promotion->discount,
                $promotion->date,
                $promotion->category_id == 0 ? null : $promotion->category_id,
                $promotion->product_id == 0 ? null : $promotion->product_id) !== 1) {
            Session::setError('something went wrong');
            Redirect::back();
        }

        Session::setMessage('done');
        Redirect::to('');
    }
} 