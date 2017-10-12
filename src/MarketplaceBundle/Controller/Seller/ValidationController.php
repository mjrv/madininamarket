<?php 

namespace MarketplaceBundle\Controller\Seller;

use MarketplaceBundle\Entity\Shop;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


/**
* @Route("/validation")
*/
class ValidationController extends Controller
{
	// apres notification de la commande de l utilisateur
	// le seller selectionne la commande
	// traitement de la commande (stock ok --livraison ou recuperation)
	// en cas de recuperation informer le client produit disponible en magasin et en de livraison informer le client du numero de suivi(avec email revendeur) 

/**
 * @Route("/" ,name = "list_orders")
 */
	public function listOrdersAction(Request $request)
	{
		//afficher la liste des commandes pour le magasin en question
		 $em = $this->getDoctrine()->getManager();
		 $orders = $em->getRepository('MarketplaceBundle:Orders')->findAll();

		 return $this->render('seller\validation\list_orders.html.twig',['orders' => $orders]);
	}


	public function showOrderAction()
	{
		//possibilite de confirmer le receptionnement (magasin ou poster) apres avoir verifier la disponibilité
		# code...
	}
}
