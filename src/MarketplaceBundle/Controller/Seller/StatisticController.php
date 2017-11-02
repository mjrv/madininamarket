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
	public function listOrders(Request $request, $id)
	{
		// if($request->isXmlHttpRequest())
		// {
			$em = $this->getDoctrine()->getManager();
			$orders = $em->getRepository('MarketplaceBundle:Orders')->findBy([
																		"shop"  => $id,
																		"valid" => '1'
																	]);

			// $query = $em
			// 		->createQuery(
			// 					'
			// 					SELECT o.reference, DATE_FORMAT(o.date, %d "%M %Y"), DATE_FORMAT(o.date, %k %i %s"), o.user, o.orders.totalTtc
			// 					FROM MarketplaceBundle:Orders o
			// 					WHERE o.shop = :shop
			// 					AND o.valid = 1
			// 					'
			// 				)
			// 		->setParameter('shop', $id)
			// 				;
			// $ordersArray = $query->getArrayResult();
			// $ordersParsed = $this->parseOrders($orders);

			$res = [];
			foreach ($orders as $key => $value) 
			{
				$res[$value->getId()] = [ 
					"ref" 	=> $value->getReference(),
					"date"  => $value->getDate()->format('Y-m-d'),
					"heure" => $value->getDate()->format('H'),
					"user" 	=> $value->getUser(),
					"ttc" 	=> $value->getOrders()["totalTtc"],
					"marge" => "Marge à calculer",
					"id"    => $value->getId(),
				];

			}
			// $items= $this->parseOrders($orders, $id);

			// $params = [
			// 	// 'items' => $items,
			// 	'orders' => $orders,
			// ];

			// dump($params);
			// die;
			return new JsonResponse($res);
		// }
		// else
		// 	throw new NotFoundHttpException("La page demandée n'existe pas");
	}

	/**
	 * [statistic description]
	 #* @param  Shop   $shop [description]
	 * @Route("/{id}", name="show_stat")
	 */
	public function statistic(Shop $shop)
	{
		return $this->render('seller/stat/statistic.html.twig', ['shop' => $shop]);
	}
}