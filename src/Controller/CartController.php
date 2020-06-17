<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    private $session;
    private $productRepository;

    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {   
        $this->session = $session;
        $this->productRepository = $productRepository;
    }

    /**
     * @Route("/cart", name="cart")
     */
    public function index()
    {
        // get $cart[] / empty ? get cart empty array
        $panier = $this->session->get('panier', []);

        // item infos in cart
        $panierInfos = [];
        foreach($panier as $id => $quantity){
            $panierInfos[] = [
                'product' => $this->productRepository->find($id),
                'quantity' => $quantity
            ];
        }

        // calculate total cart
        $total = 0;
        foreach ($panierInfos as $item){
            $total += $item['quantity'] * $item['product']->getPrice();
        }

        return $this->render('cart/index.html.twig', [
            'items' => $panierInfos,
            'total' => $total
        ]);
    }

    /**
     * Add item 
     * @Route("/cart/add/{id}", name="cart_add")
     */
    public function add($id)
    {
        $panier = $this->session->get('panier', []);
        if(!empty($panier[$id])){
            $panier[$id]++;
        } else {
            $panier[$id] = 1 ;
        }
        $this->session->set('panier', $panier);

        return $this->redirectToRoute('product');
    }

    /**
     * Remove item
     * @Route("/cart/remove/{id}", name="cart_remove")
     */
    public function remove($id)
    {
        $panier = $this->session->get('panier', []);
        if(!empty($panier[$id])){
            unset($panier[$id]);
        }
        $this->session->set('panier', $panier);

        return $this->redirectToRoute('cart');
    }
}
