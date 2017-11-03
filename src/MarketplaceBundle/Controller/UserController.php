<?php

namespace MarketplaceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use MarketplaceBundle\Entity\UserAdress;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;



/**
 * Cart controller.
 *
 * @Route("compte")
 */
class UserController extends Controller
{
    /**
     * @Route("/facture", name="facture")
     * @Method("GET")
     */
    public function showFactureAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $factures = $em->getRepository('MarketplaceBundle:Orders')->findBy([
	        															'user' => $this->getUser()->getUsername(),
	        															'valid' => '1',
	        														], # Second tableau signifie ORDER BY, source https://stackoverflow.com/questions/12048452/how-to-order-results-with-findby-in-doctrine
                                                                    [
                                                                        'id' => 'DESC' 
                                                                    ]);
        dump($factures);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $factures, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            15/*limit per page*/
        );

        $params = [
            'factures' => $pagination
            ];

            // die(var_dump($factures));
        return $this->render('front/user/factures.html.twig', $params);
    } 

    /**
     * @Route("/{id}/facture_details", name="facture_details")
     * @Method("GET")
     */
    public function showFactureDetailsAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $facture = $em->getRepository('MarketplaceBundle:Orders')->find($id);
        if($facture->getUser($this->getUser()->getUsername()) !== $this->getUser()->getUsername()) throw $this->createNotFoundException("La page demendée n'existe pas ...");

        $params = [
            'commande' => $facture
            ];

        return $this->render('front/user/facture.html.twig', $params);
    } 


    /**
     * @Route("/adresse", name="adress")
     */
    public function showAdressAction(Request $request)
    {
        $user = $this->getUser();
        $ua = new UserAdress();
        $form = $this->createForm('MarketplaceBundle\Form\UserAdressType', $ua);

        if($request->isMethod('POST'))
        {
            $form->handleRequest($request);
            if($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $ua->setUser($user);
                $em->persist($ua);
                $em->flush();
            }
        }
        $params = [
            'form' => $form->createView(),
            'user' => $user
        ];

        return $this->render('front/user/adress.html.twig', $params);
    }

    /**
     * @Route("/{id}/suppression_adresse", name="del_adress_account")
     */
    public function deleteAdressAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $adress = $em->getRepository('MarketplaceBundle:UserAdress')->find($id);

        if ($this->container->get('security.token_storage')->getToken()->getUser() != $adress->getUser()  || !$adress) 
        {
            return $this->redirect($this->generateUrl('adress')); 
        }       

        $em->remove($adress);
        $em->flush();

        return $this->redirect($this->generateUrl('adress')); 
    }

    /**
    * @Route("/{id}/pdf", name="get_pdf")
    */
    public function getPdfAction(Request $request, $id)
    {
        // $id = intval($request->get('id'));
        $em = $this->getDoctrine()->getManager();
        $facture = $em->getRepository('MarketplaceBundle:Orders')->findOneBy(['user'=> $this->getUser(), 'valid' => 1, 'id' => $id]);
        // var_dump($facture);
        // die;

        if(!$facture) { return $this->redirectToRoute('facture');  }
        // We store the hostname of our website
        $hostname = $request->getHost();
        // $img = file_get_contents($hostname.'/chocolaterie/web/logo.png');

        $params = [
            'facture' => $facture,
            'hostname' => $hostname,
            // 'img' => $img
            ];

        $html = $this->renderView('front/user/pdf.html.twig',$params);

        $this->returnPDFResponseFromHTML($html, $id);
    }

    # whiteOctober bundle
    public function returnPDFResponseFromHTML($html, $id){
        //set_time_limit(30); uncomment this line according to your needs
        // If you are not in a controller, retrieve of some way the service container and then retrieve it
        //$pdf = $this->container->get("white_october.tcpdf")->create('vertical', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        //if you are in a controlller use :
        $pdf = $this->get("white_october.tcpdf")->create('vertical', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetAuthor('Choco');
        $pdf->SetTitle(('Nom de la boite'));
        // $pdf->SetSubject('Our Code World Subject');
        $pdf->setFontSubsetting(true);
        $pdf->SetFont('helvetica', '', 11, '', true);
        //$pdf->SetMargins(20,20,40, true);
        $pdf->AddPage();
        
        $filename = 'facture';
        
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
        $pdf->Output($filename.$id.".pdf",'I'); // This will output the PDF as a response directly
    }

}
