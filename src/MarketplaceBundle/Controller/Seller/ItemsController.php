<?php

namespace MarketplaceBundle\Controller\Seller;

use MarketplaceBundle\Entity\Items;
use MarketplaceBundle\Entity\Shop;
use MarketplaceBundle\Entity\HistoryItem;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

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
     * @Route("/{id}", name="items_index")
     * @Method("GET")
     */
    public function indexAction(Request $request, Shop $shop)
    {
        $em = $this->getDoctrine()->getManager();

        $items = $em->getRepository('MarketplaceBundle:Items')->findByShop($shop);
        // die;
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $items, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            12/*limit per page*/
        );
        // print_r($picture);

        return $this->render('seller/items/index.html.twig', array(
            'items' => $pagination,
            'shop' => $shop
        ));
    }

    /**
     * Creates a new item entity.
     *
     * @Route("/{id}/new", name="items_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Shop $shop)
    {
        $item = new Items();
        $form = $this->createForm('MarketplaceBundle\Form\ItemsSellerType', $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // dump($form->getData());
            $em = $this->getDoctrine()->getManager();
            $item->setShop($shop);
            $item->setVerify(0);
            $em->persist($item);
            $shop->setGenerateAutoRef($shop->getGenerateAutoRef()+1);
            $em->persist($shop);
            $em->flush();

            $mediasClone = clone $item->getPicture();
            $item->getPicture()->clear();

            foreach ($mediasClone as $md) {
                $md->setItems($item);
                $em->persist($md);
                $em->flush();
            }

            return $this->redirectToRoute('items_show', array('id' => $item->getId()));
        }

        return $this->render('seller/items/new.html.twig', array(
            'item' => $item,
            'form' => $form->createView(),
            'shop' => $shop

        ));
    }

    /**
     * Finds and displays a item entity.
     *
     * @Route("/{id}/show", name="items_show")
     * @Method("GET")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $item = $em->getRepository('MarketplaceBundle:Items')->find($id);

        if (!$item) throw $this->createNotFoundException("la page demandee n'existe pas");

        $deleteForm = $this->createDeleteForm($item);

        return $this->render('seller/items/show.html.twig', array(
            'item' => $item,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing item entity.
     *
     * @Route("/{id}/edit", name="items_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, $id)
    {
        $history = new HistoryItem();
        $em = $this->getDoctrine()->getManager();
        $item = $em->getRepository('MarketplaceBundle:Items')->find($id);

        if (!$item) throw $this->createNotFoundException("la page demandee n'existe pas");

        $deleteForm = $this->createDeleteForm($item);
        $editForm = $this->createForm('MarketplaceBundle\Form\ItemsSellerType', $item);
        $editForm->handleRequest($request);//recupere la requet gerer le formulaire

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $history->setUser($this->getUser());
            $history->setItem($item);
            $em->persist($history);
            
            $mediasClone = clone $item->getPicture();
            $item->getPicture()->clear();

            foreach ($mediasClone as $md) {
                $md->setItems($item);
                $em->persist($md);
                $em->flush();
            }
            // a verifier champ :name -description-picture-)
            $data = $editForm->getData(); # Récupere les données du formukaire sous form d'objet de type Items
            $files = 0;
            foreach ($_FILES['marketplacebundle_items']['name']['picture'] as $key => $value) {
                if($value['imageFile']['file'] != '')
                        $files+=1;
            }
            if ($data->getName() != $item->getName() || $data->getReference() != $item->getReference() || $files !== 0) {
                $item->setVerify(0);
                $em->persist($item);
            }
            $em->flush();
            if($item->getStock() > 0)
                $this->forward('MarketplaceBundle:Notification:notifyBack', ['id' => $id]);
            return $this->redirectToRoute('items_edit', array('id' => $item->getId()));
        }

        return $this->render('seller/items/edit.html.twig', array(
            'item' => $item,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            // 'shop' => $item->getShop(),
        ));
    }

    /**
     * Deletes a item entity.
     *
     * @Route("/{id}", name="items_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Items $item)
    {
        $form = $this->createDeleteForm($item);
        $form->handleRequest($request);
        $shopId = $item->getShop()->getId();

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($item);
            $em->flush();
        }

        # si c'est une requette ajax:
        if ($request->isXmlHttpRequest()) 
            return new JsonResponse( $this->generateUrl('items_index', ['id' => $shopId]) );

        # si c'est une requete php :
        else
            return $this->redirectToRoute('items_index', ['id' => $shopId]);
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
