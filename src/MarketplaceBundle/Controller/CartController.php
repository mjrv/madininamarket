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
	public function cartAction()
	{
		$session = new Session();
		if(!$session->has("cart")) $session->set("cart",array());
		$cart = $session->get("cart");
		$em = $this->getDoctrine()->getManager();
		$items = $em->getRepository("MarketplaceBundle:Items")->findArray(array_keys($cart));
		$params =[
			"items" => $items,
			"cart"	=> $cart
		];
		dump($params);
		return $this->render("front\cart\cart.html.twig",$params);
	}

	/**
	* @Route("/ajouter/{id}",name ="addToCart")
	*/
	public function addAction(Request $request, $id)
	{
		$session = new Session();
		$em = $this->getDoctrine()->getManager();
		$item = $em->getRepository("MarketplaceBundle:Items")->find($id);
		$stock = $item->getStock();
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

}