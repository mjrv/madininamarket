<?php 

namespace MarketplaceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use MarketplaceBundle\Entity\UserAdress;

/**
 * @Route("/validation")
 */
class ValidationController extends Controller
{
	/**
	 * @Route("/livraison", name="livraison")
	 */
	public function livraisonAction(Request $request)
	{
		$user = $this->getUser();
		$ua = new UserAdress();
		$form = $this->createForm('MarketplaceBundle\Form\UserAdressType',$ua); //preparation du formulaire
		$em = $this->getDoctrine()->getManager();

		if($request->isMethod('POST'))
		{
			$form->handleRequest($request);
			if($form->isValid())
			{
				$ua->setUser($user);
				$em->persist($ua);
				$em->flush();
			}
		}
		$adress = $em->getRepository('MarketplaceBundle\Entity\UserAdress')->findBy(['user'=>$user->getId()]);

		$params = [
			'form' 	=> $form->createView(),
			'adress'	=> $adress
		];
		// dump($params);
		return $this->render('front\validation\livraison.html.twig',$params);
	}
	/**
	 * @Route("/supprimer/{id}",name="deleteAdress")
	 */
	public function deleteAdressAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$adress = $em->getRepository('MarketplaceBundle\Entity\UserAdress')->find($id);
		if ($this->getUser() != $adress->getUser() || !$adress) {
			return $this->redirect($this->generateUrl('livraison'));
		}
		$em->remove($adress);//supprimerl adresse
		$em->flush();//traitement de la requete envoyer dans la base
		return $this->redirect($this->generateUrl('livraison'));
	}

	public function setLivraisonSession(Request $request)
	{
		$session = new Session();
		if (!$session->has('adress')) {
				$session->set('adress',array());
			}
		$adress = $session->get('adress');

		if ($request->get('livraison') !=null && $request->get('facturation')){
			$adress['livraison'] = $request->get('livraison');
			$adress['facturation'] = $request->get('facturation');
		}else{
			return $this->redirect($this->generateUrl('livraison'));
		}
		$session->set('adress',$adress);
		return $this->redirect($this->generateUrl('shipmentWay'));
	}

	/**
	 * @Route("/methode-de-livraison",name="shipmentWay")
	 */
	public function chooseShipmentWayAction(Request $request)
	{
		if ($request->isMethod('POST')) {
			$this->setLivraisonSession($request);
		}
		$em = $this->getDoctrine()->getManager();
		$shipment = $em->getRepository('MarketplaceBundle:ShipmentWay')->findALL();

		return $this->render('front/cart/cart.html.twig',array('shipment' => $shipment));
	}

	public function setShipmentSession(Request $request)
	{
		$session = new Session();
		if (!$session->has('shipment')) {
			$session->set('shipment',array());
		}
		$shipment = $session->get('shipment');

		if ($request->get('shipment')!=null) {
		$shipment['shipment'] = $request->get('shipment');
		$session->set('shipment',$shipment);
		}else{
			return $this->redirect($this->generateUrl('shipmentWay'));
		}

		return $this->redirect($this->generateUrl('validation'));

	}

	/**
	 * @route("/validation",name="validation")
	 */
	public function validateCartAction(Request $request)
	{
		if ($request->isMethod('POST')) {
			$this->setShipmentSession($request);
		}
		$session = new Session();
		$em = $this->getDoctrine()->getManager();
		//on fera appel a notre fonction de preparation de commenade
		$prepareOrder = $this->forward('MarketplaceBundle:Orders:prepareOrder');
		$orderId = intval($prepareOrder->getContent());
		$order = $em->getRepository('MarketplaceBundle:Orders')->find($orderId);
		dump($prepareOrder);
		if ($order == null) {
			throw new NotFoundHttpException("Session expiree..");
		}

		
		return $this->render('front/validation/validation.html.twig',['order'=>$order]);
	}

	/*
		Mettre en session les adresses choisie
		Proposer les methodes de livraison
		Mettre en session le choix de livraison
		->Préparer la facture(controller de commande)
		->Mettre tout ca en bdd (controller de commande)
		Présenté une page de validation
		->Traitement du paiement (controller de paiement)
	 */
	
}

