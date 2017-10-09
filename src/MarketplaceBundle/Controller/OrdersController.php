<?php 

namespace MarketplaceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use MarketplaceBundle\Entity\Orders;

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
		$order = array();
		$totalTtc = 0;
		$totalHt = 0;

		$livraison = $em->getRepository('MarketplaceBundle:UserAdress')->find($adress['livraison']);
		$facturation = $em->getRepository('MarketplaceBundle:UserAdress')->find($adress['facturation']);
		$items = $em->getRepository('MarketplaceBundle:Items')->findItemsInArray(array_keys($cart));
		$shipment = $em->getRepository('MarketplaceBundle:ShipmentWay')->find( $session->get('shipment')['shipment']);

		foreach ($items as $item) {
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
						'priceHt' => $priceHt,
						'priceTtc'=> $priceTtc,
						'price' => $item->getPriceHT()
					];
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

			$order['totalHt'] = $totalHt;
			$order['totalTtc'] = $totalTtc;
			$order['total'] = $totalTtc + $shipment->getPrice(); //Total ttc + frais de port
			$order['token'] = bin2hex($generator);

			return $order;
	}

	public function prepareOrderAction()
	{
		$session = new Session();
		$em = $this->getDoctrine()->getManager();
		if (!$session->has('order')) {
			$order = new Orders();
			}else{
				$order = $em->getRepository('MarketplaceBundle:Orders')->find($session->get('order'));
			}

		$order
			->setDate(new \Datetime())	
			->setUser($this->getUser())
			->setValid(0)
			->setStatut(0)
			->setReference(0)
			->setOrders($this->facture());

			if (!$session->has('order')) {
				$em->persist($order);
				$session->set('order',$order);
			}
			$em->flush();
			
			return new Response($order->getId());
		}
}