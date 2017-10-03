<?php

namespace MarketplaceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Route("/notification")
 */
class NotificationController extends Controller
	{
		public function notifyMeAction($id)
		{
			#on recupere l 'utilisateur actuel via la session 
			#on enregistre cet utilisateur avec le produit dans une table notifyme
			#Faire une alete pour dire a la personne qu elle sera notifié
			$session = new Session();

		}

		#methode permettant de verifier qu'un produit passe de 0 a stok
		#envoyer un email a tous ceux qui avaient souscrit a l alerte
	}