<?php

namespace MarketplaceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use MarketplaceBundle\Entity\Notification;


/**
 * @Route("/notification")
 */
class NotificationController extends Controller
{
	/**
	 * @Route("/{id}/no-stock",name="notifyMe")
	 */
	public function notifyMeAction($id)
	{
		#on recupere l 'utilisateur actuel via la session 
		#on enregistre cet utilisateur avec le produit dans une table notifyme
		#Faire une alerte pour dire a la personne qu elle sera notifié

		$user = $this->getUser()->getEmailCanonical();
		// dump($user);
		// if(!$user)
		// 	return	$this->redirect($this->generateUrl('homepage'));
		$em = $this->getDoctrine()->getManager();
		$notif = new Notification();
		$notif
			->setEmail($user)
			->setItem($id)
		;
		$em->persist($notif);
		$em->flush();

		return new JsonResponse(true);
	}

	#methode permettant de verifier qu'un produit passe de 0 a stok
	#envoyer un email a tous ceux qui avaient souscrit a l alerte
	/**
	* @Route("/notify-back", name="notifyBack")
	 */	
	public function notifyBackAction($id)
	{
		$em = $this->getDoctrine()->getManager();

		$users = $em->getRepository("MarketplaceBundle:Notification")->findBy(['item' => $id]);

		foreach ($users as $user) 
		{
			// override transport options so that parameters.yml is by-passed
			// https://stackoverflow.com/questions/26255081/overriding-swiftmailer-transport-options-dynamically-instead-of-using-parameters
			// https://stackoverflow.com/questions/11033641/is-a-global-variable-for-a-twig-template-available-inside-a-symfony2-controller
			$transport = \Swift_SmtpTransport::newInstance($this->container->getParameter('mail_send_host'),$this->container->getParameter('mail_send_port'), $this->container->getParameter('mail_send_encryption'))
			    ->setUsername($this->container->getParameter('mail_send_user'))
			    ->setPassword($this->container->getParameter('mail_send_password'))
			;

			$this->mailer = \Swift_Mailer::newInstance($transport);

			$message = \Swift_Message::newInstance()
			    ->setSubject('retour en stock')
			    ->setFrom([$this->container->getParameter('mail_send_user')=> "madininamarket"])
			    ->setTo($user->getEmail())
			    ->setBody('le produit x est a nouveau en stock')
			;
			$this->mailer->send($message);	
		}

		return new Response(true);
			
	}
}