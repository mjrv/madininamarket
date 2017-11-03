<?php 
namespace MarketplaceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("panier")
 */

class CartController extends Controller{
	/**
	 * @Route("/", name ="cart")
	 */
	public function cartAction() //affiche le panier
	{
		$session = new Session();
		if(!$session->has("cart")) $session->set("cart",array());
		$cart = $session->get("cart");
		$em = $this->getDoctrine()->getManager();
		$items = $em->getRepository("MarketplaceBundle:Items")->findItemsInArray(array_keys($cart));

		$shipment = $em->getRepository('MarketplaceBundle:ShipmentWay')->findALL();

		$prixMax = [];
		$max = 0;
		$previous = null;
		$cat = null;
		$min = 50;
		$middle = 150;
		$hight = 300;
		$priceLivraison = 0;
		
		foreach ($items as $item) {
			# verifier la boutique actuelle
			# Si c'est la meme boutique comparer les prix
			# sinon enregistrer le nom de la nouvelleboutique
			# puis faire la comparaison prixmax*
			
			$current = $item->getShop()->getSlug();
			
			if ($previous != $current) {

				$max = 0;
				$previous = $current;
				$cat = $item->getshipmentPrice()->getType();

				if ($max < $item->getPriceHt()) {

					$max = $item ->getPriceHt();

					if ($max < $min) {
						# prix de la livraison est egal a type category price1
						$priceLivraison = $item->getshipmentPrice()->getPrice1();
					}elseif ($max >= $min && $max <$middle) {
						$priceLivraison = $item->getshipmentPrice()->getPrice2();
					}elseif ($max >= $middle && $max < $hight ) {
						$priceLivraison = $item->getshipmentPrice()->getPrice3();
					}else {
						$priceLivraison = $item->getshipmentPrice()->getPrice4();
					}

					$prixMax[$item->getShop()->getCommercialName()] = [
						'item' => $item,
						'shop'=> $item->getShop()->getSlug(),
						'prix' => $item->getPriceHt(),
						'typeCategory' => $cat, 
						'priceLivraison' => $priceLivraison,
					];
				}
			}else{
				if ($max < $item->getPriceHt()) {

					$max = $item ->getPriceHt();

					$cat = $item->getshipmentPrice()->getType();

					if ($max < $min) {
						# prix de la livraison est egal a type category price1
						$priceLivraison = $item->getshipmentPrice()->getPrice1();
					}elseif ($max >= $min && $max <$middle) {
						$priceLivraison = $item->getshipmentPrice()->getPrice2();
					}elseif ($max >= $middle && $max < $hight ) {
						$priceLivraison = $item->getshipmentPrice()->getPrice3();
					}else {
						$priceLivraison = $item->getshipmentPrice()->getPrice4();
					}

					$prixMax[$item->getShop()->getCommercialName()] = [
						'item' => $item,
						'shop'=> $item->getShop()->getSlug(),
						'prix' => $item->getPriceHt(),
						'typeCategory' => $cat, 
						'priceLivraison' => $priceLivraison,
					];
				}
			}
			
		} //Fin foreach

		$params =[
			"items" 		=> $items,
			"cart"			=> $cart,
			"shipment" 		=> $shipment,
			"prixMaxItem" 	=> $prixMax,			
		];
		dump($params);
		return $this->render("front\cart\cart.html.twig",$params);
	}

	/**
	* @Route("/ajouter/{slug}",name ="addToCart")
	*/
	public function addAction(Request $request, $slug)
	{
		$session = new Session();
		$em = $this->getDoctrine()->getManager();
		$item = $em->getRepository("MarketplaceBundle:Items")->findOneBySlug($slug);

		$stock = $item->getStock();
		$id = $item->getId();
		$qte = intval($request->get("qte"));
		if(!$session->has("cart")) $session->set("cart",array());

		$cart = $session->get("cart");

		if (array_key_exists($id, $cart)) {
			if ($qte!=null) {
				$cart[$id] = $qte;
			}
			elseif($qte < $stock){
				$cart[$id] +=1;
			}
			else{
				$cart[$id] = $stock;
			}
		}
		else{
			if ($qte !=null) {
				$cart[$id] = $qte;
			}else{
				$cart[$id] = 1;
			}
		}
		$session ->set("cart",$cart);
		return $this->redirect($this->generateUrl("cart"));
	}

	/**
 	* @Route("supprimer/{id}",name="deleteFromCart")
	*/
	public function deleteAction($id)
	{
		$session = new Session();
		$cart = $session->get('cart');

		if (array_key_exists($id, $cart)) {
			unset($cart[$id]);//retirer cette enttree cart du tableau
			$session ->set('cart',$cart);
		}
		return $this->redirect($this->generateUrl('cart'));
	}

}