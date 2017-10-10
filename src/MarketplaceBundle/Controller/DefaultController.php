<?php

namespace MarketplaceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use MarketplaceBundle\Entity\Items;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

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
    	// $pictures = $em->getRepository('MarketplaceBundle:Picture')->findAll(); 
    	$items = $em->getRepository('MarketplaceBundle:Items')->findAll(); 
    	// $items = $em->getRepository('MarketplaceBundle:Items')->gelAllItems(); 
        // $t = $test->getPicture()->first();
    	

    	$params = [
    		'shop' => $shop,
    		// 'pictures' => $pictures,
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
            // dump($params);
    		// die;
    		 
        return $this->render('front\index.html.twig',$params);
    }

    # https://hugo-soltys.com/blog/how-to-make-a-sitemap-file-with-symfony
    /**
     * @Route("/sitemap",name="sitemap")
     */
    public function sitemapAction(Request $request) 
    {
        // We define an array of urls
        $urls = [];
        $em = $this->getDoctrine()->getManager();

        // We store the hostname of our website
        $hostname = $request->getHost();

        $urls[] = ['loc' => $this->get('router')->generate('homepage'), 'changefreq' => 'weekly', 'priority' => '1.0'];    

        // Then, we will find all our articles stored in the database
        $shops=$em->getRepository('MarketplaceBundle:Shop')->findAll();
        $items=$em->getRepository('MarketplaceBundle:Items')->findAll();
        $categories=$em->getRepository('MarketplaceBundle:Category')->findAll();


        // We loop on them
        if ($items) {
            foreach ($items as $item) {
                $urls[] = ['loc' => $this->get('router')->generate('show_product_details', ['slug' => $item->getSlug()]), 'changefreq' => 'weekly', 'priority' => '1.0'];
            }
        }

        if ($shops) {
            foreach ($shops as $shop) {
                $urls[] = ['loc' => $this->get('router')->generate('shop_show_details', ['slug' => $shop->getSlug()]), 'changefreq' => 'weekly', 'priority' => '1.0'];
            }
        }

        // if (categories) {
        //     foreach ($categories as $category) {
        //         $urls[] = ['loc' => $this->get('router')->generate('chemin category', ['slug' => $category->getSlug()]), 'changefreq' => 'weekly', 'priority' => '1.0'];
        //     }
        // }

        // Once our array is filled, we define the controller response
        $response = new Response();
        $response->headers->set('Content-Type', 'xml');

        return $this->render('sitemap/sitemap.xml.twig', [
            'urls' => $urls,
            'hostname' => $hostname
        ]);
    }
}
