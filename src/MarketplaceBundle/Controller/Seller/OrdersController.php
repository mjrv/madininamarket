<?php

namespace MarketplaceBundle\Controller\Seller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use MarketplaceBundle\Entity\Orders;

/**
* @Route("/commande")
*/
class OrdersController extends Controller
{
    /**
     * @Route("/{id}", name="seller_orders_show")
     */
    public function showAction(Orders $orders)
    {
    	$em = $this->getDoctrine()->getManager();
    	// $commande = $em->getRepository('MarketplaceBundle:Orders')->find($id);

    	$params = [
    		'commande' => $orders,
    	];
        return $this->render('seller/orders/show.html.twig', $params);
    }
}
