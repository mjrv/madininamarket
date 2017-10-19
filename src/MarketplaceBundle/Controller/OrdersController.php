<?php 

namespace MarketplaceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use MarketplaceBundle\Entity\Orders;
use MarketplaceBundle\Entity\Shop;

/**
* 
*/
class OrdersController extends Controller
{
	public function facture()
	{
		$session = new Session();
		$em = $this->getDoctrine()->getManager();
		$generator = random_bytes(20);
		$adress = $session->get('adress');
		$cart = $session->get('cart');
		$order = array(); //Contient le détails de chaque commande par boutique
		$orders = []; //Contient l'ensemble des commandes que l'utilisateur actuel fait
		$totalTtc = 0;
		$totalHt = 0;

		# Modification a faire le 18/10/2017
		# Récupérer les shop
		# Faire les factures par shop
		# Renvoyer les factures
  			
		$livraison = $em->getRepository('MarketplaceBundle:UserAdress')->find($adress['livraison']);
		$facturation = $em->getRepository('MarketplaceBundle:UserAdress')->find($adress['facturation']);
		$items = $em->getRepository('MarketplaceBundle:Items')->findItemsInArray(array_keys($cart));
		$shops = $em->getRepository('MarketplaceBundle:Shop')->findItemShop(array_keys($cart));
		$shipment = $em->getRepository('MarketplaceBundle:ShipmentWay')->find( $session->get('shipment')['shipment'] );

		foreach ($shops as $shop) 
		{
			// $items = $em->getRepository('MarketplaceBundle:Items')->findItemsForOrders(array_keys($cart), $shop->getId());

			foreach ($items as $item) 
			{
				if($item->getShop()->getId() == $shop->getId()) # Si l'item appartient au shop
				{
					$tva = $item->getTva()->getValue();
					$id = $item->getId();
					$priceHt = round($item->getPriceHT()*$cart[$id],2);
					$priceTtc = round($priceHt*(1+($tva/100)),2);
					$totalHt += $priceHt;
					$totalTtc += $priceTtc;

					if (!isset($order['tva']['%'.$tva])) {
						$order['tva']['%'.$tva] = round($priceTtc - $priceHt, 2);
					}else{
						$order['tva']['%'.$tva] += round($priceTtc - $priceHt, 2);
					}


					$order['item'][$id] = [
								'id' => $id,
								'name' => $item->getName(),
								'qte' => $cart[$id],
								'priceHt' => $priceHt, //Prix hors taxe du total des produits du panier
								'priceTtc'=> $priceTtc, //
								'price' => $item->getPriceHT(), //Prix du produit hors taxe a l'unité
								'reference' => $item->getReference(),
							];
				}
			}
					
			$order['livraison'] = [
						'firstname' => $livraison->getFirstname(),
						'lastname' => $livraison->getLastname(),
						'phone' => $livraison->getPhone(),
						'phone2' => $livraison->getPhone2(),
						'adress' => $livraison->getAdress(),
						'zipcode' => $livraison->getZipcode(),
						'city' => $livraison->getCity(),
					];

			$order['facturation'] = [
						'firstname' => $facturation->getFirstname(),
						'lastname' => $facturation->getLastname(),
						'phone' => $facturation->getPhone(),
						'phone2' => $facturation->getPhone2(),
						'adress' => $facturation->getAdress(),
						'zipcode' => $facturation->getZipcode(),
						'city' => $facturation->getCity(),
					];

			$order['shipmentWay'] = [
						'price' => $shipment->getPrice(),
						'name' => $shipment->getName()
					];

			$order['shop'] = [
								'id' => $shop->getId(),
								'slug' => $shop->getSlug(),
								'name' => $shop->getCommercialName(),
								'raisonSocial' => $shop->getRaisonSocial(),
								'immatriculation' => $shop->getImmatriculation(),
								'apeCode' => $shop->getApeCode(),
								'nameGerant' => $shop->getNameGerant(),
								'phone' => $shop->getPhone(),
								'phone2' => $shop->getPhone2(),
								'email' => $shop->getEmail(),
								'adress' => $shop->getAdress(),
								'city' => $shop->getCity(),
								'zipcode' => $shop->getZipcode(),
					];

			$order['totalHt'] = $totalHt;
			$order['totalTtc'] = $totalTtc;
			$order['total'] = $totalTtc + $shipment->getPrice(); //Total ttc + frais de port
			$order['token'] = bin2hex($generator);

			$orders[] = $order; # V3
			// return $order;

		}
		
			return $orders; # V3
	}

	/**
	 * [prepareOrderAction description]
	 * @Route("prepare-orders")
	 */
	public function prepareOrderAction()
	{
		$session = new Session();
		$em = $this->getDoctrine()->getManager();
		$commandes = $this->facture(); # On recupere chaque facture
		$ordersId = []; # Va contenir les id de nos differentes factures

		if (!$session->has('orders'))  # Si la session n'a pas l'entrée 'orders'
		{
			// $this->newOrders($commandes);
			// $shop = $em->getRepository('MarketplaceBundle:Shop')->find($commande['shop']['id']);
			
			foreach ($commandes as $commande) 
			{

				// die(dump($commande['shop']['id']));
				$order = new Orders();

				$order
					->setDate(new \Datetime())	
					->setUser($this->getUser())
					->setValid(0)
					->setStatut(0)
					->setReference(0)
					->setOrders($commande)
					->setShop($commande['shop']['id']);

					// if (!$session->has('orders')) {
						$em->persist($order);
					// }
					$em->flush();
					$ordersId[] = $order->getId();

			}
			$session->set('orders',$ordersId);
		}
		else
		{
			# Modification a faire le 18/10/2017
			# Recuperer le tableau avec toutes les factures
			# bouclé pour enregistrer chaque facture par boutique
			# Renvoyer un tableau qui contient les id de chaque commande
			
			// $this->updateOrders($commandes);

			$orders = $em->getRepository('MarketplaceBundle:Orders')->findOrdersArray(array_keys($session->get('orders')));

			foreach ($orders as $order) # on boucle sur chaque facture
			{
				$order
					->setDate(new \Datetime())	
					->setUser($this->getUser())
					->setValid(0)
					->setStatut(0)
					->setReference(0);

				foreach ($commandes as  $commande) 
				{
					// var_dump($order->getOrders()['shop']);
					dump($orders);
					if($commande['shop']['id'] == $order->getOrders()['shop']['id'])
						$order
							->setOrders($commande)
							->setShop($em->getRepository('MarketplaceBundle:Shop')->find($commande['shop']['id']));
				}

					// if (!$session->has('orders')) {
						$em->persist($order);
					// }
					$em->flush();
					$ordersId[] = $order->getId(); 
			}

			$session->set('orders',$ordersId);
			
		}
		
		

		

		
			
			return new Response(implode(",",$ordersId));
			// return new JsonResponse(implode(",",$ordersId));
		
}}