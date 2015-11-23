<?php

namespace Controllers;


use FW\Security\Auth;
use FW\Helpers\Redirect;
use FW\Session\Session;
use FW\Security\Validation;
use FW\View\View;
use Models\ProductModel;

class ProductController {

    /**
     * @var \Models\Category
     */
    private $category;
    /**
     * @var \Models\Product
     */
    private $product;
    /**
     * @var \Models\Promotion
     */
    private $promotion;
    /**
     * @var \Models\Comment
     */
    private $comment;

    public function index() {
        $result['categories']=$this->category->getCategories();
        $result['title']='Shop';
        $result['isEditor'] = Auth::isUserInRole(array('editor', 'admin'));
        $result['isAdmin'] = Auth::isUserInRole(array('admin'));
        if ($result['isEditor']) {
            $result['products']=$this->product->getProductsWitnUnavailable();
        } else {
            $result['products']=$this->product->getProducts();
        }
        $all_promotion = $this->promotion->getHighestActivePromotion();
        foreach($result['products'] as $k => $p) {
            $productPromotion = max($all_promotion['discount'], $p['discount'], $p['category_discount']);
            if (is_numeric($productPromotion)) {
                $result['products'][$k]['promotion_price'] = $p['price'] - ($p['price'] * ($productPromotion / 100));
            }
        }

//        $val=new Validation();
//        $val->setRule('matches',3,4,'az');
//        $val->setRule('different',6,6,'min');
//        $val->setRule('afterDate','10/10/2010','10/10/2011','date');
//        $val->setRule('required','',null,'username')->validate();
//var_dump($val->getErrors());

        View::make('index', $result);
        if (Auth::isAuth()) {
            View::appendTemplateToLayout('topBar', 'top_bar.user');
        } else {
            View::appendTemplateToLayout('topBar', 'top_bar.guest');
        }

        View::appendTemplateToLayout('header', 'includes.header')
            ->appendTemplateToLayout('footer', 'includes.footer')
            ->appendTemplateToLayout('catMenu', 'side_bar.category_menu')
            ->render();
    }

    public function getProduct($id) {
        $result['comments']=$this->comment->getCommentsByProduct($id);
        $result['categories']=$this->category->getCategories();
        $result['isEditor'] = Auth::isUserInRole(array('editor', 'admin'));
        $result['isAdmin'] = Auth::isUserInRole(array('admin'));
        if ($result['isEditor']) {
            $result['product']=$this->product->getProductWitnUnavailable($id);
        } else {
            $result['product']=$this->product->getProduct($id);
        }
        $all_promotion = $this->promotion->getHighestActivePromotion();
        $productPromotion = max($all_promotion['discount'], $result['product']['discount'], $result['product']['category_discount']);
        if (is_numeric($productPromotion)) {
            $result['product']['promotion_price'] = $result['product']['price'] - ($result['product']['price'] * ($productPromotion / 100));
        }
        $result['title']='Shop';
        $result['currentCategory']=$result['product']['category_id'];

        View::make('product', $result);
        if (Auth::isAuth()) {
            View::appendTemplateToLayout('topBar', 'top_bar.user');
        } else {
            View::appendTemplateToLayout('topBar', 'top_bar.guest');
        }

        View::appendTemplateToLayout('header', 'includes.header')
            ->appendTemplateToLayout('footer', 'includes.footer')
            ->appendTemplateToLayout('catMenu', 'side_bar.category_menu')
            ->render();
    }

    public function getAdd() {
        $result['title']='Shop';
        $result['action'] = '/product/add';
        $result['submit'] = 'add';
        $categories = $this->category->getCategories();
        foreach($categories as $c) {
            $currentCategory = array();
            $currentCategory['text'] = $c['name'];
            $currentCategory['options'] = array('value' => $c['id']);
            $result['categories'][] = $currentCategory;
        }
        View::make('product.add', $result);
        if (Auth::isAuth()) {
            View::appendTemplateToLayout('topBar', 'top_bar/user');
        } else {
            View::appendTemplateToLayout('topBar', 'top_bar/guest');
        }

        View::appendTemplateToLayout('header', 'includes/header')
            ->appendTemplateToLayout('footer', 'includes/footer')
            ->render();
    }

    public function postAdd(ProductModel $product) {
        $validator = new Validation();
        $validator->setRule('required', $product->name, null, 'name');
        $validator->setRule('required', $product->description, null, 'description');
        $validator->setRule('required', $product->price, null, 'price');
        $validator->setRule('required', $product->quantity, null, 'quantity');
        $validator->setRule('required', $product->category_id, null, 'category');
        $validator->setRule('numeric', $product->quantity, null, 'quantity');
        $validator->setRule('numeric', $product->price, null, 'price');
        if (!$validator->validate()) {
            Session::setError($validator->getErrors()[0]);
            Redirect::back();
        }
        if ($this->product->add($product->name, $product->description,$product->price,$product->quantity,$product->category_id) !== 1) {
            Session::setError('something went wrong');
            Redirect::back();
        }

        Session::setMessage('done');
        Redirect::to('');
    }

    public function getEdit($id) {
        $result['isEditor'] = Auth::isUserInRole(array('editor', 'admin'));
        $result['isAdmin'] = Auth::isUserInRole(array('admin'));
        if ($result['isEditor']) {
            $result = array('product' => $this->product->getProductWitnUnavailable($id));
        } else {
            $result = array('product' => $this->product->getProduct($id));
        }
        $result['title']='Shop';
        $result['action'] = '/product/edit/' . $result['product']['id'];
        $result['submit'] = 'edit';
        $categories = $this->category->getCategories();
        foreach($categories as $c) {
            $currentCategory = array();
            $currentCategory['text'] = $c['name'];
            $currentCategory['options'] = array('value' => $c['id']);
            if ($id == $c['id']) {
                $currentCategory['options']['selected'] = 'true';
            }
            $result['categories'][] = $currentCategory;
        }
        View::make('product.add', $result);
        if (Auth::isAuth()) {
            View::appendTemplateToLayout('topBar', 'top_bar/user');
        } else {
            View::appendTemplateToLayout('topBar', 'top_bar/guest');
        }

        View::appendTemplateToLayout('header', 'includes/header')
            ->appendTemplateToLayout('footer', 'includes/footer')
            ->render();
    }

    public function postEdit($id, ProductModel $product) {
        $validator = new Validation();
        $validator->setRule('required', $product->name, null, 'name');
        $validator->setRule('required', $product->description, null, 'description');
        $validator->setRule('required', $product->price, null, 'price');
        $validator->setRule('required', $product->quantity, null, 'quantity');
        $validator->setRule('required', $product->category_id, null, 'category');
        $validator->setRule('numeric', $product->quantity, null, 'quantity');
        $validator->setRule('numeric', $product->price, null, 'price');
        if (!$validator->validate()) {
            Session::setError($validator->getErrors()[0]);
            Redirect::back();
        }
        if ($this->product->edit($id, $product->name, $product->description,$product->price,$product->quantity,$product->category_id) !== 1) {
            Session::setError('something went wrong');
            Redirect::back();
        }

        Session::setMessage('done');
        Redirect::to('');
    }

    public function delete($id) {
        if ($this->product->delete($id) !== 1) {
            Session::setError('can not delete this product');
            Redirect::back();
        }

        Session::setMessage('done');
        Redirect::to('');
    }
} 