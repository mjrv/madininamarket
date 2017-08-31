<?php

namespace MarketplaceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
* @Route("nav")
*/
class NavController extends Controller
{
    /**
     * @Route("/shop-list", name="side_shop")
     */
    public function shopAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$shop = $em->getRepository('MarketplaceBundle:Shop')->findAll(); 
        return $this->render('front\nav\shoplist.html.twig',array('shop' => $shop));
    }

    public function categoriesAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$categories = $em->getRepository('MarketplaceBundle:Category')->findAll();
    	return $this->render('front\nav\categorieslist.html.twig',['categories' => $categories]);
    }
}
