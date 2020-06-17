<?php

namespace App\Service;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService {

    private $session;
    private $productRepository;

    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {   
        $this->session = $session;
        $this->productRepository = $productRepository;
    }

    /**
     * add item in cart
     */
    public function add(int $id)
    {
        $panier = $this->session->get('panier', []);
        if(!empty($panier[$id])){
            $panier[$id]++;
        } else {
            $panier[$id] = 1 ;
        }
        $this->session->set('panier', $panier);
    }

    /**
     * remove item from cart
     */
    public function remove(int $id)
    {
        $panier = $this->session->get('panier', []);
        if(!empty($panier[$id])){
            unset($panier[$id]);
        }
        $this->session->set('panier', $panier);
    }

    public function getCartItems()
    {
        $panier = $this->session->get('panier', []);
        $panierInfos = [];
        foreach($panier as $id => $quantity){
            $panierInfos[] = [
                'product' => $this->productRepository->find($id),
                'quantity' => $quantity
            ];
        }
        return $panierInfos;
    }

    public function getTotalCart()
    {
        $total = 0;
        foreach ($this->getCartItems() as $item){
            $total += $item['quantity'] * $item['product']->getPrice();
        }
        return $total;
    }
}