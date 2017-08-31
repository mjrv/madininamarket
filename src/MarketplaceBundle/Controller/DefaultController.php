<?php

namespace MarketplaceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$shop = $em->getRepository('MarketplaceBundle:Shop')->findAll(); 
    	$pictures = $em->getRepository('MarketplaceBundle:Picture')->findAll(); 
    	$items = $em->getRepository('MarketplaceBundle:Items')->findAll(); 
    	//$items = $em->getRepository('MarketplaceBundle:Items')->gelAllItems(); 
    	

    	$params = [
    		'shop' => $shop,
    		'pictures' => $pictures,
    		'items' => $items
    		];
    		// foreach ($items as $key => $value) {
    		// 	// echo $key;
    		// 	echo '<hr>';
    		// 	// var_dump($value);
    		// 	foreach ($value as $cle => $val) {
    		// 		echo $cle;
    		// 		var_dump($val);
    		// 		# code...
    		// 	}
    		// 	echo '<br><br>';
    		// 	# code...
    		// }
    		 dump($params);
    		 die;
        return $this->render('front\index.html.twig',$params);
    }
}
