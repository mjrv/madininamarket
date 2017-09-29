<?php

namespace MarketplaceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

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

	/**
	 * @Route("/recherche" ,name = "recherche")
	 */
	public function searchAction(Request $request)
	{
		if ($_SERVER["REQUEST_METHOD"]=="GET") {

			$em=$this->getDoctrine()->getManager();

			$category= trim(strip_tags($request ->get("category")));
			$search = trim(strip_tags($request ->get("search")));

			$categories = $em->getRepository('MarketplaceBundle:Category')->findOneBySlug($category);

			#si on a une categorie, alors faire un filtre sur les produit par rapport a l'd
			#sinon, on fait un like sur les produi par rapport au nom
			if(strlen($search) > 0)
			{
				if ($categories !== null)
				{
					dump("reussi");
					$items = $em->getRepository('MarketplaceBundle:Items')->searchItemsWithCat($categories->getId(),$search);
				}
				else
				{
					dump("toto");
					$items = $em->getRepository('MarketplaceBundle:Items')->searchItems($search);
				}
			}
			else
			{
				if ($categories)
				{
					$items = $em->getRepository('MarketplaceBundle:Items')->findBy(["category" => $categories->getId()]);
				}
				else
				{
					$items = $em->getRepository('MarketplaceBundle:Items')->findAll();
				}
			}

			$pictures = $em->getRepository('MarketplaceBundle:Items')->findArray($items);
			$params = [
				'category' => $category,
				'search' => $search,
				'items' => $items,
				'pictures' => $pictures,
			];

			dump($params);
			return $this->render('front/shop/search.html.twig', array(
	        'pictures' => $pictures,
	    ));		

		}
	}

	
}
