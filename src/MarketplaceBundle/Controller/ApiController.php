<?php

namespace MarketplaceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/**
 * @Route("/api")
 */
class ApiController extends Controller
{
	/**
	 * [doc: https://stackoverflow.com/questions/28141192/return-a-json-array-from-a-controller-in-symfony]
	 * @Route("/list-items", name="listItems")
	 */
	public function listItems(Request $request)
	{
		if($request->isXmlHttpRequest())
		{
			$em = $this->getDoctrine()->getManager();
			$query = $em->createQuery(
			    'SELECT i
			    FROM MarketplaceBundle:Items i'
			);
			$items = $query->getArrayResult();

			return new JsonResponse($items);
			// return new JsonResponse($this->getDoctrine()->getManager()->getRepository("MarketplaceBundle:Items")->findAll());
		}
		else
			throw new NotFoundHttpException("La page demandée n'existe pas");
	}
	/**
	 * [doc: https://stackoverflow.com/questions/28141192/return-a-json-array-from-a-controller-in-symfony]
	 * @Route("/list-shop", name="listShop")
	 */
	public function listSop(Request $request)
	{
		if($request->isXmlHttpRequest())
		{
			$em = $this->getDoctrine()->getManager();
			$query = $em->createQuery(
			    'SELECT s
			    FROM MarketplaceBundle:Shop s'
			);
			$shop = $query->getArrayResult();

			return new JsonResponse($shop);
			// return new JsonResponse($this->getDoctrine()->getManager()->getRepository("MarketplaceBundle:Items")->findAll());
		}
		else
			throw new NotFoundHttpException("La page demandée n'existe pas");
	}
}