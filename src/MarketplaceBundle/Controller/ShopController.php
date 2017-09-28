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
	*@route("/{slug}/shop-details", name = "shop_show_details")
	*/
	public function showShopAction($slug)
	{
	    $em = $this->getDoctrine()->getManager();
	    $shop = $em->getRepository('MarketplaceBundle:Shop')->findOneBySlug($slug);
	    $products = $em->getRepository('MarketplaceBundle:Shop')->findItems($shop->getId());

	    dump($shop);
	    dump($products);

	    if (!$shop) throw $this->createNotFoundException("la page demandee n'existe pas");

	    return $this->render('front/shop/shopdetails.html.twig', array(
	        'shop' => $shop,
	        'products' => $products,
	    ));
	}

	/**
	*@route("/{slug}/product-details", name = "show_product_details")
	*/
	public function showProduct($slug)
	{
	    $em = $this->getDoctrine()->getManager();
	    $product = $em->getRepository('MarketplaceBundle:Items')->findOneBySlug($slug);
	    $item = $em->getRepository('MarketplaceBundle:Picture')->getItemPic($product->getId()); 
	    $pictures = $em->getRepository('MarketplaceBundle:Picture')->findAll(); 
	    $params = [
	        'pictures' => $pictures,
	        'product' => $product,
	        'item' => $item
	    ];

	    dump($params);

	    if (!$product) throw $this->createNotFoundException("la page demandee n'existe pas");

	    return $this->render('front/shop/productdetails.html.twig',$params);
	}

	
}
