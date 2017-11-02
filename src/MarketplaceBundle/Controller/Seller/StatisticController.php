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
			// $date = new \Datetime();

			// $an = $date->format("Y");
			// $mois = $date->format("m");
			// $jour = $date->format("d");

			// $debut = $date->format("Y-m-d 00:00:00.00");
			// $fin = $date->format("Y-m-d 23:59:59.999");

			// $dates = [
			// 	$debut,
			// 	$fin,
			// 	$an,
			// 	$mois,
			// 	$jour
			// ];

			// dump($dates);

			$em = $this->getDoctrine()->getManager();
			$orders = $em->getRepository('MarketplaceBundle:Orders')->findBy([
																		"shop"  => $id,
																		"valid" => '1'
																	]);


			$res = [];
			foreach ($orders as $key => $value) 
			{
				$res[$value->getDate()->format('Y-m-d')] =
						[$value->getId() => [ 
						"ref" 	=> $value->getReference(),
						"date"  => $value->getDate()->format('Y-m-d'),
						"heure" => $value->getDate()->format('H'),
						"user" 	=> $value->getUser(),
						"ttc" 	=> $value->getOrders()["totalTtc"],
						"marge" => "Marge à calculer",
						"id"    => $value->getId(),
						"an"    => $value->getDate()->format('Y'),
						"mois"  => $value->getDate()->format('m'),
						"jour"  => $value->getDate()->format('j'),
					]
				];

			}

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