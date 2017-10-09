<?php

namespace MarketplaceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Session\Session;

/**
* 
*/
class NavSellerController extends Controller
{
	
	public function userShopAction()
	{
		 $user = $this->getUser()->getShop();

        dump($user->getValues());
        // die;
        return $this->render('seller\nav\userShop.html.twig',['user'=>$user]);
	}
}