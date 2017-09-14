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
	*@route("/{id}/shop-details", name = "shop_show_details")
	*/
	public function showShopAction($id)
	{
	    $em = $this->getDoctrine()->getManager();
	    $shop = $em->getRepository('MarketplaceBundle:Shop')->find($id);
	    $products = $em->getRepository('MarketplaceBundle:Shop')->findItems($id);

	    dump($shop);
	    dump($products);

	    if (!$shop) throw $this->createNotFoundException("la page demandee n'existe pas");

	    return $this->render('front/shop/shopdetails.html.twig', array(
	        'shop' => $shop,
	        'products' => $products,
	    ));
	}

	/**
	*@route("/{id}/product-details", name = "show_product_details")
	*/
	public function showProduct($id)
	{
	    $em = $this->getDoctrine()->getManager();
	    $product = $em->getRepository('MarketplaceBundle:Items')->find($id);
	    $item = $em->getRepository('MarketplaceBundle:Picture')->getItemPic($id); 
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

	public function showProductPerCategory()
	{
		$em = $this->getDoctrine()->getManager();
	    $products = $em->getRepository('MarketplaceBundle:Category')->itemsPerCategory($id);

	    if (!$products) throw $this->createNotFoundException("la page demandee n'existe pas");

	    return $this->render('front/shop/categorydetails.html.twig', array(
	        'products' => $products,
	    ));
	}
}
