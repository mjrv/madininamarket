<?php

namespace MarketplaceBundle\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="admin")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('back\index.html.twig');
    }

}
