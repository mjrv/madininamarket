<?php

namespace MarketplaceBundle\Controller\Seller;

use MarketplaceBundle\Entity\Shop;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Shop controller.
 *
 * @Route("shop")
 */
class ShopController extends Controller
{
    /**
     * Lists all shop entities.
     *
     * @Route("/", name="shop_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        
        $user = $this->getUser()->getShop()->getValues();

        dump($user);
        // die;

        $em = $this->getDoctrine()->getManager();

        $shops = $em->getRepository('MarketplaceBundle:Shop')->findAll();

        return $this->render('seller/shop/index.html.twig', array(
            'shops' => $shops,
        ));
    }

    
    /**
     * Finds and displays a shop entity.
     *
     * @Route("/{id}", name="shop_show")
     * @Method("GET")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();//Pour manipuler la base --Doctrine puis dans manager pour la gerer
        $shop = $em->getRepository('MarketplaceBundle:Shop')->find($id);//dire a EM d'aller chercher le repository qui est shop dans le cas present
        
       // die(var_dump($shop));
        if (!$shop) throw $this->createNotFoundException("la page est introuvable");

        return $this->render('seller/shop/show.html.twig', array(
            'shop' => $shop
        ));
    }

    /**
     * Displays a form to edit an existing shop entity.
     *
     * @Route("/{id}/edit", name="shop_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $shop = $em->getRepository('MarketplaceBundle:Shop')->find($id);
        // die(var_dump($shop));
         if (!$shop) throw $this->createNotFoundException("la page est introuvable");

        $editForm = $this->createForm('MarketplaceBundle\Form\ShopSellerType', $shop);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('shop_edit', array('id' => $shop->getId()));
        }

        return $this->render('seller/shop/edit.html.twig', array(
            'shop' => $shop,
            'edit_form' => $editForm->createView()
        ));
    }

    
    /**
     * Creates a form to delete a shop entity.
     *
     * @param Shop $shop The shop entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Shop $shop)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('shop_delete', array('id' => $shop->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
