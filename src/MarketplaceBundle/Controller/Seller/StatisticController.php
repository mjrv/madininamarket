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
			$orders = $em->getRepository('MarketplaceBundle:Orders')->findAll();

			// $query = $em->createQuery(

			// );
			// var_dump($orders);
			// die;

			$items= $this->filterItems($orders, $id);

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
	 * [filterItems description]
	 * @param  [Orders] $orders   [Le resultat de la requete]
	 * @param  [integer] $userShop [id de la boutique]
	 * @return [array]           [Renvoie un tableau de json]
	 */
	private function filterItems($orders, $userShop)
	{
		$em = $this->getDoctrine()->getManager();
		$res = [];
		$serializer = \JMS\Serializer\SerializerBuilder::create()->build();

		foreach ($orders as $key => $value) 
		{
			$itemId = array_keys($value->getOrders()['item'])[0];

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
			 $res[] = $query->getArrayResult();
			}
			// if($userShop == $shopId) $res[] = $serializer->serialize($item, 'json');
			// die;

		}

		return $res;
	}

	/**
	 * [statistic description]
	 #* @param  Shop   $shop [description]
	 * @Route("/", name="show_stat")
	 */
	public function statistic()
	{
		return $this->render('seller/stat/statistic.html.twig');
		// $this->render('seller/stat/stat.html.twig', ['shop' => $shop]);
	}
}