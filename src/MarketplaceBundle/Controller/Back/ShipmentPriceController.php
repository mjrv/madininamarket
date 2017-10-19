<?php

namespace MarketplaceBundle\Controller\Back;

use MarketplaceBundle\Entity\ShipmentPrice;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Shipmentprice controller.
 *
 * @Route("shipmentprice")
 */
class ShipmentPriceController extends Controller
{
    /**
     * Lists all shipmentPrice entities.
     *
     * @Route("/", name="admin_shipmentprice_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $shipmentPrices = $em->getRepository('MarketplaceBundle:ShipmentPrice')->findAll();

        return $this->render('back/shipmentprice/index.html.twig', array(
            'shipmentPrices' => $shipmentPrices,
        ));
    }

    /**
     * Creates a new shipmentPrice entity.
     *
     * @Route("/new", name="admin_shipmentprice_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $shipmentPrice = new Shipmentprice();
        $form = $this->createForm('MarketplaceBundle\Form\ShipmentPriceType', $shipmentPrice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($shipmentPrice);
            $em->flush();

            return $this->redirectToRoute('admin_shipmentprice_show', array('id' => $shipmentPrice->getId()));
        }

        return $this->render('back/shipmentprice/new.html.twig', array(
            'shipmentPrice' => $shipmentPrice,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a shipmentPrice entity.
     *
     * @Route("/{id}", name="admin_shipmentprice_show")
     * @Method("GET")
     */
    public function showAction(ShipmentPrice $shipmentPrice)
    {
        $deleteForm = $this->createDeleteForm($shipmentPrice);

        return $this->render('back/shipmentprice/show.html.twig', array(
            'shipmentPrice' => $shipmentPrice,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing shipmentPrice entity.
     *
     * @Route("/{id}/edit", name="admin_shipmentprice_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ShipmentPrice $shipmentPrice)
    {
        $deleteForm = $this->createDeleteForm($shipmentPrice);
        $editForm = $this->createForm('MarketplaceBundle\Form\ShipmentPriceType', $shipmentPrice);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_shipmentprice_edit', array('id' => $shipmentPrice->getId()));
        }

        return $this->render('back/shipmentprice/edit.html.twig', array(
            'shipmentPrice' => $shipmentPrice,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a shipmentPrice entity.
     *
     * @Route("/{id}", name="admin_shipmentprice_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ShipmentPrice $shipmentPrice)
    {
        $form = $this->createDeleteForm($shipmentPrice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($shipmentPrice);
            $em->flush();
        }

        return $this->redirectToRoute('admin_shipmentprice_index');
    }

    /**
     * Creates a form to delete a shipmentPrice entity.
     *
     * @param ShipmentPrice $shipmentPrice The shipmentPrice entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ShipmentPrice $shipmentPrice)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_shipmentprice_delete', array('id' => $shipmentPrice->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
