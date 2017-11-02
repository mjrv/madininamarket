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
	 * @Route("/{id}/{periode}/{debut}/{fin}/api", name="statistic")
	 * @param integer id [id de la boutique]
	 */
	public function listOrders(Request $request, $id, $periode, $debut = "1965-01-01", $fin = "9999-12-31")
	{
		// if($request->isXmlHttpRequest())
		// {
			
			// $periode = $request->get("periode");
			// $debut = $request->get("debut");
			// $fin = $request->get("fin");

			# https://stackoverflow.com/questions/5174789/php-add-7-days-to-date-format-mm-dd-yyyy
			#Si les parametres n'existe paas (null), on leurs donnent des valeurs par defaut
			// if(!$periode) $periode = 1;
			if(!$fin) $fin = date("Y-m-d"); //Date du jour

			switch($periode) // En fonction de la periode on modifie les paramettre
			{
				case 1: //Toutes les factures
					$debut = "1965-01-01";
					$fin = "9999-12-31";
					break;
				
				case 2: //Aujourd'hui
					$debut = date("Y-m-d");
					break;
			
				case 3: //La semaine
					$debut = date("Y-m-d", strtotime("$fin - 7 day"));
					break;
				
				case 4: //Le mois
					$debut = date("Y-m-d", strtotime("$fin - 1 month"));
					break;
				
				case 5: //L'année
					$debut = date("Y-m-d", strtotime("$fin - 1 year"));
					break;

				case 6: //Custum
					$debut = $debut;
					$fin = $fin;
					break;
			}

			$params = [
				$periode,
				$debut,
				$fin
			];

			dump($params);

			$em = $this->getDoctrine()->getManager();
			$orders = $em->getRepository('MarketplaceBundle:Orders')->findOrdersByDate($id, $debut." 00:00:00.00", $fin." 23:59:59.999");


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
					// "an"    => $value->getDate()->format('Y'),
					// "mois"  => $value->getDate()->format('m'),
					// "jour"  => $value->getDate()->format('j'),
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
	public function statistic(Request $resquest, Shop $shop)
	{
		return $this->render('seller/stat/statistic.html.twig', ['shop' => $shop]);
	}
}