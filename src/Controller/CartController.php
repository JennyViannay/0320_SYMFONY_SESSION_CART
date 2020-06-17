<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    
    private $cartService;

    public function __construct(CartService $cartService)
    {   
        $this->cartService = $cartService;
    }

    /**
     * @Route("/cart", name="cart")
     */
    public function index()
    {
        return $this->render('cart/index.html.twig', [
            'items' => $this->cartService->getCartItems(),
            'total' => $this->cartService->getTotalCart()
        ]);
    }

    /**
     * Add item 
     * @Route("/cart/add/{id}", name="cart_add")
     */
    public function add($id)
    {
        $this->cartService->add($id);
        return $this->redirectToRoute('product');
    }

    /**
     * Remove item
     * @Route("/cart/remove/{id}", name="cart_remove")
     */
    public function remove($id)
    {
        $this->cartService->remove($id);
        return $this->redirectToRoute('cart');
    }
}
