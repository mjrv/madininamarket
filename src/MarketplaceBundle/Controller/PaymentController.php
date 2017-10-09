<?php 
namespace MarketplaceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use MarketplaceBundle\Entity\Orders;
use MarketplaceBundle\Entity\SoldItem;
use JMS\Payment\CoreBundle\Form\ChoosePaymentMethodType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use JMS\Payment\CoreBundle\PluginController\Result;
use JMS\Payment\CoreBundle\Plugin\Exception\Action\VisitUrl;
use JMS\Payment\CoreBundle\Plugin\Exception\ActionRequiredException;

// http://jmspaymentcorebundle.readthedocs.io/en/stable/guides/accepting_payments.html#the-order-entity
/**
* @Route("/paiement")
*/
class PaymentController extends Controller
{
	/**
	* @Route("/choix-paiement/{id}",name="paymentWay")
	*/
	public function choosePayment(Request $request, Orders $orders)
	{
		$session = new Session();
		if (!$session->has('cart')) {
			return $this->redirect($this->generateURL('homepage'));
		}

		if (!$orders->getId()) {
			return $this->redirect($this->generateURL('homepage'));
		}

		$config = [
		    'paypal_express_checkout' => [
		        'return_url' => $this->generateURL('initPayment',['id'=>$orders->getId()],UrlGeneratorInterface::ABSOLUTE_URL),
		        'cancel_url' => $this->generateURL('cancelPayment',['id'=>$orders->getId()],UrlGeneratorInterface::ABSOLUTE_URL),
		        'useraction' => 'commit',
		    ],
		];

		$form = $this->createForm(ChoosePaymentMethodType::class, null, [
		    'amount'          => $orders->getOrders()['total'],
		    'currency'        => 'EUR',
		    'predefined_data' => $config,
		]);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$ppc = $this->get('payment.plugin_controller');
	        $ppc->createPaymentInstruction($instruction = $form->getData());

	        $orders->setPaymentInstruction($instruction);

	        $em = $this->getDoctrine()->getManager();
	        $em->persist($orders);
	        $em->flush($orders);

	        return $this->redirect($this->generateUrl('initPayment', [
	            'id' => $orders->getId(),
	        ]));
		}

		$params = [
			'total' => $orders->getOrders()['total'],
			'form'=> $form->createView()
		];
		return $this->render('front/validation/payment.html.twig',$params);

	}
// http://jmspaymentcorebundle.readthedocs.io/en/stable/guides/accepting_payments.html#the-order-entity
	private function createPayment($order)
	{
	    $instruction = $order->getPaymentInstruction();
	    $pendingTransaction = $instruction->getPendingTransaction();

	    if ($pendingTransaction !== null) {
	        return $pendingTransaction->getPayment();
	    }

	    $ppc = $this->get('payment.plugin_controller');
	    $amount = $instruction->getAmount() - $instruction->getDepositedAmount();

	    return $ppc->createPayment($instruction->getId(), $amount);
	}

	/**
	 * @Route("/payment/{id}/create", name="initPayment")
	 */
	public function paymentCreateAction(Orders $orders)
	{
		$session = new Session();
		if (!$session->has('cart')) {
			return $this->redirect($this->generateURL('homepage'));
		}
		if (!$orders->getId()) {
			return $this->redirect($this->generateURL('homepage'));//s'il n'y a pas de commandeson redirige vers la boutique
		}

		$payment = $this->createPayment($orders);

	    $ppc = $this->get('payment.plugin_controller');

	    $result = $ppc->approveAndDeposit($payment->getId(), $payment->getTargetAmount());
	   
	    if ($result->getStatus() === Result::STATUS_SUCCESS) {
	        return $this->redirect($this->generateUrl('payment_complete', [
	            'id' => $orders->getId(),
	        ]));
	    }

		if ($result->getStatus() === Result::STATUS_PENDING) {
		    $ex = $result->getPluginException();

		    if ($ex instanceof ActionRequiredException) {
		        $action = $ex->getAction();

		        if ($action instanceof VisitUrl) {
		            return $this->redirect($action->getUrl());
		        }
		    }
		}
		throw $result->getPluginException();
	}

	/**
	 * @Route("/payment/{id}/complete",name="payment_complete")
	 */
	public function paymentSuccessAction(Request $request, $id)
	{
		$session = new Session();
		if (!$session->has('cart')) {
			return $this->redirect($this->generateURL('homepage'));//s'il n'y a pas de session panier on redirige vers la boutique
		}

		$em = $this->getDoctrine()->getManager();
		$order = $em->getRepository('MarketplaceBundle:Orders')->find($id);
		if (!$order || $order->getValid() !=0) throw new NotFoundHttpException("La commande n'existe pas");
		$facture = $order->getOrders();
		// $vente = $em->getRepository('MarketplaceBundle:SoldItem')->findBy(['sold' => date('now')]);
		// $date = new \DateTime();
		// $today = $date->format('Y-m-d');

		//mettre a jour les quantitées une fois la commande validée + l'historique d'achat
		foreach ($facture['item'] as $key => $value) {
			$item = $em->getRepository('MarketplaceBundle:Items')->find($key);
			$item->setStock($item->getStock()-$value['qte']);
			$em->persist($item);

			$vente = $em->getRepository('MarketplaceBundle:SoldItem')->findBy([
																					// 'soldAt' => $today,
																					'soldAt' => new \DateTime(),
																					// 'soldAt' => date('Y-m-d H:i:s'),
																					// 'soldAt' => date('Y-m-d'),
																					'item' => $key
																				]);
			var_dump($vente);
			if(!$vente)
			{
				$vente = new SoldItem();	
				$vente->setItem($key);
				$vente->setQuantity($value['qte']);
				$em->persist($vente);
			}
			// die;

			$em->flush();
		}
		
		$order
			->setValid(1)
			->setStatut(1)
			->setReference(2);
			// ->setReference($this->container->get('newReference')->reference());

			$em->flush($order);

			$session->remove('adress');
			$session->remove('cart');
			$session->remove('shipment');
			$session->remove('order');

			//envoi d email de recapitulation au client et au vendeur
			
			// $message =\Swift_Message::newInstance()
			// 	->setSubject('commande validée')
			// 	->setFrom([$this->container->getParameter('mail_send_user') => 'Madinina Market'])
			// 	->setTo($this->getUser()->getEmailCanonical())
			// 	->setCharset('utf-8')
			// 	->setContentType('text/html')
			// 	->setBody($this->renderView('swiftmail\validation.html.twig', ['order' => $order]));
			// $this->get('mailer')->send($message);

			return $this->redirect($this->generateUrl('facture'));

		
	}

	/**
	 * @Route("/payment/{id}/cancel", name="cancelPayment")
	 */
	public function paymentFailledAction(Request $request, $id)
	{
		throw new NotFoundHttpException("Echec paypal");
		
	}
	
}