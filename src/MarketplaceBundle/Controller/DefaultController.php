<?php

namespace MarketplaceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use MarketplaceBundle\Entity\Items;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $test = new Items();
    	$em = $this->getDoctrine()->getManager();
    	$shop = $em->getRepository('MarketplaceBundle:Shop')->findAll(); 
    	$pictures = $em->getRepository('MarketplaceBundle:Picture')->findAll(); 
    	// $items = $em->getRepository('MarketplaceBundle:Items')->findAll(); 
    	$items = $em->getRepository('MarketplaceBundle:Items')->gelAllItems(); 
        // $t = $test->getPicture()->first();
    	

    	$params = [
    		'shop' => $shop,
    		'pictures' => $pictures,
    		'items' => $items,
            // 't' => $t
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
    		// die;
    		 
        return $this->render('front\index.html.twig',$params);
    }
}
