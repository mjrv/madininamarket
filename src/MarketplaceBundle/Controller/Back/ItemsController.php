<?php

namespace MarketplaceBundle\Controller\Back;

use MarketplaceBundle\Entity\Items;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Item controller.
 *
 * @Route("items")
 */
class ItemsController extends Controller
{
    /**
     * Lists all item entities.
     *
     * @Route("/", name="admin_items_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $items = $em->getRepository('MarketplaceBundle:Items')->findAll();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $items, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

        return $this->render('back\items/index.html.twig', array(
            'items' => $pagination,
        ));
    }

    /**
     * Creates a new item entity.
     *
     * @Route("/new", name="admin_items_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $item = new Items();
        $form = $this->createForm('MarketplaceBundle\Form\ItemsType', $item);
        // $form->handleRequest($request);

        if ($request->getMethod() == 'POST')
        {
            dump($form->handleRequest($request));
            // die(var_dump($form["medias"]));
            if($form->isValid())
            {
                $mediasClone = clone $item->getPicture();
                $item->getPicture()->clear();

                $em = $this->getDoctrine()->getManager();
                $em->persist($item);
                $em->flush();

               foreach($mediasClone as $md){
                    $md->setItems($item);
                    $em->persist($md);
                    // $this->container->get('vich_uploader.storage')->upload($product);
                    $em->flush();
               }

               return $this->redirectToRoute('items_show', array('id' => $item->getId()));
            }
        }

        return $this->render('back\items/new.html.twig', array(
            'item' => $item,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a item entity.
     *
     * @Route("/{id}", name="admin_items_show")
     * @Method("GET")
     */
    public function showAction(Items $item)
    {
        $deleteForm = $this->createDeleteForm($item);

        return $this->render('back\items/show.html.twig', array(
            'item' => $item,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing item entity.
     *
     * @Route("/{id}/edit", name="admin_items_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Items $item)
    {
        $deleteForm = $this->createDeleteForm($item);
        $editForm = $this->createForm('MarketplaceBundle\Form\ItemsType', $item);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
             if($item->getStock() > 0)
                $this->forward('MarketplaceBundle:Notification:notifyBack', ['id' => $id]);

            return $this->redirectToRoute('items_edit', array('id' => $item->getId()));
        }

        return $this->render('back\items/edit.html.twig', array(
            'item' => $item,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a item entity.
     *
     * @Route("/{id}", name="admin_items_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Items $item)
    {
        $form = $this->createDeleteForm($item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($item);
            $em->flush();
        }

        return $this->redirectToRoute('admin_items_index');
    }

    /**
     * Creates a form to delete a item entity.
     *
     * @param Items $item The item entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Items $item)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('items_delete', array('id' => $item->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
