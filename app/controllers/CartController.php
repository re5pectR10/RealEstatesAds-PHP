<?php

namespace Controllers;


use FW\Security\Auth;
use FW\Helpers\Redirect;
use FW\Session\Session;
use FW\View\View;
use Models\User;

class CartController {

    /**
     * @var \Models\Product
     */
    private $product;
    /**
     * @var \Models\Promotion
     */
    private $promotion;
    /**
     * @var \Models\User
     */
    private $user;
    public  function getAll() {
        $cart = array();
        if (Session::containKey('cart')) {
            $cart = Session::get('cart');
        }
        $result['title']='Shop';
        $result['products'] = $this->getProductsFromCart($cart);
        $result['isEditor'] = Auth::isUserInRole(array('editor', 'admin'));
        $result['isAdmin'] = Auth::isUserInRole(array('admin'));
        $result['user_cash'] = $this->user->getUserMoney(Auth::getUserId());
        View::make('cart', $result);
        if (Auth::isAuth()) {
            View::appendTemplateToLayout('topBar', 'top_bar/user');
        } else {
            View::appendTemplateToLayout('topBar', 'top_bar/guest');
        }
        View::appendTemplateToLayout('header', 'includes/header')
            ->appendTemplateToLayout('footer', 'includes/footer')
            ->appendTemplateToLayout('catMenu', 'side_bar/category_menu')
            ->render();
    }

    public function add($id) {
        if (!Session::containKey('cart')) {
            Session::set('cart', array());
        }
        $cart = Session::get('cart');
        $result = $this->product->getProduct($id);
        if (!array_key_exists($id, $cart)) {
            $cart[$id] = array(
                'quantity' => 1,
                'name' => $result['name'],
                'price' => $result['price']
            );
        }

        Session::setMessage('added to cart');
        Session::set('cart', $cart);
        Redirect::to('/category/'.$result['category_id']);
    }

    public function changeQuantity($id, $quantity) {
        if (!Session::containKey('cart') || !array_key_exists($id, Session::get('cart'))) {
            throw new \Exception('This products dont exist in your cart', 500);
        }

        $_SESSION['cart'][$id]['quantity'] = $quantity;
        Redirect::to('/user/cart');
    }

    public function removeProduct($id) {
        if (!Session::containKey('cart') || !array_key_exists($id, Session::get('cart'))) {
            throw new \Exception('This products dont exist in your cart', 500);
        }

        unset($_SESSION['cart'][$id]);
        Redirect::to('/user/cart');
    }

    public function buy() {
        $totalSum = 0;
        $cart = Session::get('cart');

        $this->product->startTran();
        $productsFromCart = $this->getProductsFromCart($cart);
        foreach($productsFromCart as $item) {
            if ($this->product->changeQuantity($item['id'], $item['cart_quantity']) !== 1) {
                $this->product->rollback();
                Session::setError('not enough available product');
                Redirect::back();
            }

            $totalSum += $item['price']*$item['cart_quantity'];
        }

        $user = new User();
        if ($user->changeUserCash(Auth::getUserId(), $totalSum) !== 1) {
            $this->product->rollback();
            Session::setError('not enough money');
            Redirect::back();
        }
        foreach($productsFromCart as $item) {
            if ($user->addProduct(Auth::getUserId(), $item['id'], $item['cart_quantity'], $item['price']) !== 1) {
                $this->product->rollback();
                Session::setError('something went wrong');
                Redirect::back();
            }
        }
        $this->product->commit();
        Session::remove('cart');
        Session::setMessage('Done');
        Redirect::to('user/cart');
    }

    private function getProductsFromCart($cart) {
        $all_promotion = $this->promotion->getHighestActivePromotion();
        $productsFromCart = array();
        foreach($cart as $id => $q) {
            if (($currentProduct = $this->product->getProduct($id))) {
                $productPromotion = max($all_promotion['discount'], $currentProduct['discount'], $currentProduct['category_discount']);
                if (is_numeric($productPromotion)) {
                    $currentProduct['price'] = $currentProduct['price'] - ($currentProduct['price'] * ($productPromotion / 100));
                }
                $currentProduct['cart_quantity'] = $q['quantity'];
                $productsFromCart[] = $currentProduct;
            }
        }

        return $productsFromCart;
    }
} 