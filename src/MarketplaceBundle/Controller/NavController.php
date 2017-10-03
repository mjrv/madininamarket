<?php

namespace MarketplaceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Session\Session;

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

    /**
     * @Route("/shop-nav", name="nav_shop")
     */
    public function shopNavAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$shop = $em->getRepository('MarketplaceBundle:Shop')->findAll(); 
        return $this->render('front\nav\shopnav.html.twig',array('shop' => $shop));
    }

    public function categoriesAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$categories = $em->getRepository('MarketplaceBundle:Category')->findAll();
    	return $this->render('front\nav\categorieslist.html.twig',['categories' => $categories]);
    }

    public function detailCartAction()
    {
       $session = new Session();
       if (!$session->has('cart')) {
           $article = 0;
       }
       else
       {
            $article = count($session->get('cart'));
       }
       $params =['article' => $article];
       return $this->render('front\nav\cart.html.twig', $params);
    }
}
