<?php

namespace MarketplaceBundle\Controller\Seller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use MarketplaceBundle\Entity\Orders;
use MarketplaceBundle\Entity\Shop;


/**
 * @Route("/statistique")
 */
class StatisticController extends Controller
{
	# Récuperer toutes les factures
	# Faire un tri par date (jour actuel/semaine/mois/an/custum)
	# Recenser les stock de produits
	# Faire le CA
	# 
	
	/**
	 * [doc: https://stackoverflow.com/questions/28141192/return-a-json-array-from-a-controller-in-symfony]
	 * @Route("/{id}/api", name="statistic")
	 * @param integer id [id de la boutique]
	 */
	public function listOrders(Request $request, $id = 3)
	{
		// if($request->isXmlHttpRequest())
		// {
			$em = $this->getDoctrine()->getManager();
			$orders = $em->getRepository('MarketplaceBundle:Orders')->findAll();

			// $query = $em->createQuery(
			// 					'SELECT o
			// 					FROM MarketplaceBundle:Orders o'
			// 				);
			// $ordersArray = $query->getArrayResult();

			$items= $this->parseOrders($orders, $id);

			$params = [
				'items' => $items,
				// 'orders' => $orders
			];

			// var_dump($params);
			// die;
			return new JsonResponse($items);
		// }
		// else
		// 	throw new NotFoundHttpException("La page demandée n'existe pas");
	}

	/**
	 * [parseOrders description]
	 * @param  [Orders] $orders   [Le resultat de la requete]
	 * @param  [integer] $userShop [id de la boutique]
	 * @return [array]           [Renvoie un tableau de json]
	 */
	private function parseOrders($orders, $userShop)
	{
		$em = $this->getDoctrine()->getManager();
		$res = [];
		$serializer = \JMS\Serializer\SerializerBuilder::create()->build(); //Transorme les objet en tableau, mais la sortie est DEGEULASSE

		foreach ($orders as $key => $value) 
		{
			$itemId = array_keys($value->getOrders()['item'])[0];
			$date = $value->getDate()->format('Y-m-d');
			$an = $value->getDate()->format('Y');
			$mois = $value->getDate()->format('m');
			$jour = $value->getDate()->format('d');

			// $t = ['date' => $date, 'an' => $an, 'mois' => $mois, 'jour' => $jour];
			// dump($t);
			// die;

			$item = $em->getRepository('MarketplaceBundle:Items')->find($itemId);
		
			$shopId = $item->getShop()->getId();

			if($userShop == $shopId) 
			{
			 	$query = $em->createQuery(
								'SELECT i
								FROM MarketplaceBundle:Items i
								WHERE i.id = :id'
							)
							->setParameter('id', $itemId);
				 // $res[] = $query->getArrayResult();
				 
				$sold = $value->getOrders()['item'][$itemId];
				$req = $query->getArrayResult()[0];

				$res[$sold['name']] = 
									[
				 						$an => [
				 							$mois => [
				 								$jour => [
												 	'qte'	   => $sold['qte'],
												 	'priceTtc' => $sold['priceTtc'],
												 	// 'reference'=> $query->getOrders()['item'][$itemId]['reference'],
												 	'reference'=> $req['reference'],
												 	'stock'    => $req['stock'],
				 								],
				 							],
				 						],
				 	
				];

				# Premiere version
				// $res[$sold['name']] = [
				//  	// 'name' 	   => $sold['name'],
				//  	'qte'	   => $sold['qte'],
				//  	'priceTtc' => $sold['priceTtc'],
				//  	// 'reference'=> $query->getOrders()['item'][$itemId]['reference'],
				//  	'reference'=> $req['reference'],
				//  	'stock'    => $req['stock'],
				//  	'date' 	   => $date,
				//  	'an' 	   => $an,
				//  	'mois' 	   => $mois,
				//  	'jour' 	   => $jour,
				// ];
			}
		}

		// $this->filterOrders($res);

		// foreach ($res as $key => $value) 
		// {
		// 	dump($key);
		// 	dump($value);
		// }
		// die;

		return $res;
	}

	private function filterOrders($array)
	{
		dump($array);
		$item = [];
		$itemAn = [];
		$itemMois = [];
		$itemJour = [];
		foreach ($array as $key => $value) 
		{
			// $item[$key] = 
		}

		die;
	}

	/**
	 * [statistic description]
	 #* @param  Shop   $shop [description]
	 * @Route("/{id}", name="show_stat")
	 */
	public function statistic(Shop $shop)
	{
		// return $this->render('seller/stat/statistic.html.twig');
		return $this->render('seller/stat/statistic.html.twig', ['shop' => $shop]);
	}
}