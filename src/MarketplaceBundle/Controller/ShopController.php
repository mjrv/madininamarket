<?php

namespace MarketplaceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
* @Route("boutique")
*/
class ShopController extends Controller
{
	/*
	1 - afficher le detail de la boutique quand on clic dessus
	2 - afficher un produit (obliger de passer par un id de boutique) 
	3 - afficher les produits parcategories
	*/
/**
*@route("/{id}/details", name = "shop_show_details")
*/
	public function showShopAction($id)
	{
	    $em = $this->getDoctrine()->getManager();
	    $shop = $em->getRepository('MarketplaceBundle:Shop')->find($id);
	   var_dump($shop);
	    if (!$shop) throw $this->createNotFoundException("la page demandee n'existe pas");

	    return $this->render('front/shop/shopdetails.html.twig', array(
	        'shop' => $shop,
	    ));
	}

	public function showProduct()
	{
		# code...
	}

	public function showProductPerCategory()
	{
		# code...
	}
}
