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
use JMS\Payment\CoreBundle\Form\ChoosePaymentMethodType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use JMS\Payment\CoreBundle\PluginController\Result;
use JMS\Payment\CoreBundle\Plugin\Exception\Action\VisitUrl;
use JMS\Payment\CoreBundle\Plugin\Exception\ActionRequiredException;

use Payum\Core\PayumBuilder;
use Payum\Core\Payum;
use Payum\Core\Request\GetHumanStatus;
use Payum\Core\Model\Payment;
use Symfony\Component\HttpFoundation\JsonResponse;

use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use Payum\Core\Storage\FilesystemStorage;

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
			return $this->redirect($this->generateUrl('homepage'));
		}

		if (!$orders->getId()) {
			return $this->redirect($this->generateUrl('homepage'));
		}

		$config = [
		    'paypal_express_checkout' => [
		        'return_url' => $this->generateUrl('initPayment',['id'=>$orders->getId()],UrlGeneratorInterface::ABSOLUTE_URL),
		        'cancel_url' => $this->generateUrl('cancelPayment',['id'=>$orders->getId()],UrlGeneratorInterface::ABSOLUTE_URL),
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
	private function createPayment($orders)
	{
	    $instruction = $orders->getPaymentInstruction();
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
			return $this->redirect($this->generateUrl('homepage'));
		}
		if (!$orders->getId()) {
			return $this->redirect($this->generateUrl('homepage'));//s'il n'y a pas de commandeson redirige vers la boutique
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
			return $this->redirect($this->generateUrl('homepage'));//s'il n'y a pas de session panier on redirige vers la boutique
		}

		$em = $this->getDoctrine()->getManager();
		$order = $em->getRepository('MarketplaceBundle:Orders')->find($id);
		if (!$order || $order->getValid() !=0) throw new NotFoundHttpException("La commande n'existe pas");
		$facture = $order->getOrders();
		//mettre a jour les quantitées une fois la commande validée
		foreach ($facture['item'] as $key => $value) {
			$item = $em->getRepository('MarketplaceBundle:Items')->find($key);
			$item->setStock($item->getStock()-$value['qte']);
			$em->persist($item);
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
			
			$message =\Swift_Message::newInstance()
				->setSubject('commande validée')
				->setFrom([$this->container->getParameter('mail_send_user') => 'Madinina Market'])
				->setTo($this->getUser()->getEmailCanonical())
				->setCharset('utf-8')
				->setContentType('text/html')
				->setBody($this->renderView('swiftmail\validation.html.twig', ['order' => $order]));
			$this->get('mailer')->send($message);

			return $this->redirect($this->generateUrl('homepage'));

		
	}

	/**
	 * @Route("/payment/{id}/cancel", name="cancelPayment")
	 */
	public function paymentFailledAction(Request $request, $id)
	{
		throw new NotFoundHttpException("Echec paypal");
		
	}

	// function convert_multi_array($array) {
	//   $out = implode("&",array_map(function($a) {return implode("~",$a);},$array));
	//   print_r($out);
	//   return $out;
	// }
	
	#Payum
	function array_to_pipe($array, $delimeter = '|', $parents = array(), $recursive = false)
	{
	    $result = '';

	    foreach ($array as $key => $value) {
	        $group = $parents;
	        array_push($group, $key);

	        // check if value is an array
	        if (is_array($value)) {
	            if ($merge = $this->array_to_pipe($value, $delimeter, $group, true)) {
	                $result = $result . $merge;
	            }
	            continue;
	        }

	        // check if parent is defined
	        if (!empty($parents)) {
	            $result = $result . PHP_EOL . implode($delimeter, $group) . $delimeter . $value;
	            continue;
	        }

	        $result = $result . PHP_EOL . $key . $delimeter . $value;
	    }

	    // somehow the function outputs a new line at the beginning, we fix that
	    // by removing the first new line character
	    if (!$recursive) {
	        $result = substr($result, 1);
	    }

	    return $result;
	}
	
	/**
	 * @route("/{id}/prepare",name="prepare")
	 */
	 public function prepareAction(Orders $orders) 
    {
  //       $payum = (new PayumBuilder())
		//     ->addGateway('paypal_rest', [
		//         'factory' => 'paypal_rest',
		//         'username' => $this->container->getParameter('paypal_username'),
		//         'password' => $this->container->getParameter('paypal_psw'),
		//         'signature' => $this->container->getParameter('paypal_signature'),
		//         'config_path' => $this->container->getParameter('payum_config'),
		//         'client_id' => "AR1hbx622k13FPIAKh_yJZdUNWAoljvo6N4pyOrDFanXrv6IDvW29vd0DRqa5xliJ6P6nL7pYm-KVjoz",
		//         'client_secret' => "EByKCrPw367QrLYxQOppRpJlLJUA_nK3tb-AHIM0ux4lZC_0bGtOXak7emeKL1WAavW88zbTH750t6xG",
		//         'sandox' => true,
		        
		//     ])
		//     ->addDefaultStorages()
		//     ->getPayum()
		// ;

		// $payum->getGateway('paypal')->execute(new Authorize([
		//     'PAYMENTREQUEST_0_AMT' => $orders->getOrders()['total'],
		//     'PAYMENTREQUEST_0_CURRENCY' => 'EUR',
		// ]));


    	# PAYPAL EXPRESS CHECKOUT
        // $paypalRestPaymentDetailsClass = 'Payum\Paypal\Rest\Model\PaymentDetails';

        $gatewayName = 'paypal_rest';

        $storage = $this->get('payum')->getStorage('MarketplaceBundle\Entity\Payment');

        // $tokenStorage = new FilesystemStorage('/home/vagrant/tmp', 'Paypal\Model\PaymentSecurityToken', 'hash');

        $description = $this->array_to_pipe($orders->getOrders());

        $payment = $storage->create();
        $payment->setNumber(uniqid());
        $payment->setCurrencyCode('EUR');
        $payment->setTotalAmount($orders->getOrders()['total']); // 1.23 EUR
        $payment->setDescription($description); //Mettre notre tableau d'article
        $payment->setClientId($this->getUser()->getId());
        $payment->setClientEmail($this->getUser()->getEmailCanonical());  

        # Fin PAYPAL EXPRESS CHECKOUTs
        
        // $paypalRestPaymentDetailsClass = 'Payum\Paypal\Rest\Model\PaymentDetails';

        /** @var \Payum\Core\Payum $payum */
		// $storage = $payum->getStorage($paypalRestPaymentDetailsClass);

		// $payment = $storage->create();

		// $payer = new Payer();
		// $payer->payment_method = "paypal";

		// $amount = new Amount();
		// $amount->currency = "EUR";
		// $amount->total = $orders->getOrders()['total'];

		// $transaction = new Transaction();
		// $transaction->amount = $amount;
		// $transaction->description = $description;

		// // $captureToken = $payum->getTokenFactory()->createCaptureToken('paypalRest', $payment, 'create_recurring_payment.php');

		// $redirectUrls = new RedirectUrls();
		// $redirectUrls->return_url = $captureToken->getTargetUrl();
		// $redirectUrls->cancel_url = $captureToken->getTargetUrl();

		// $payment->intent = "sale";
		// $payment->payer = $payer;
		// $payment->redirect_urls = $redirectUrls;
		// $payment->transactions = array($transaction);

		$storage->update($payment); 



        $captureToken = $this->get('payum')->getTokenFactory()->createCaptureToken(
            $gatewayName, 
            $payment, 
            'done', // the route to redirect after capture
            ['id'=>$orders->getId()] #parametres de la route
        );
        
        return $this->redirect($captureToken->getTargetUrl()); 

    }

    /**
     * @Route("/{id}/done", name="done")
     */
    public function doneAction(Request $request, Orders $orders)
    {
        $token = $this->get('payum')->getHttpRequestVerifier()->verify($request);
        
        $gateway = $this->get('payum')->getGateway($token->getGatewayName());
        
        // you can invalidate the token. The url could not be requested any more.
        // $this->get('payum')->getHttpRequestVerifier()->invalidate($token);
        
        // Once you have token you can get the model from the storage directly. 
        //$identity = $token->getDetails();
        //$payment = $this->get('payum')->getStorage($identity->getClass())->find($identity);
        
        // or Payum can fetch the model for you while executing a request (Preferred).
        $gateway->execute($status = new GetHumanStatus($token));
        $payment = $status->getFirstModel();
        $response = $status->getValue();
        die(dump($response));
        // you have order and payment status 
        // so you can do whatever you want for example you can just print status and payment details.
        
        // return new JsonResponse(array(
        //     'status' => $status->getValue(),
        //     'payment' => array(
        //         'total_amount' => $payment->getTotalAmount(),
        //         'currency_code' => $payment->getCurrencyCode(),
        //         'details' => $payment->getDetails(),
        //     ),
        // ));
        
        if($response == "captured") return $this->redirect($this->generateUrl( 'payment_complete', ['id' => $orders->getId() ] ));
        else return $this->redirect($this->generateUrl('cancelPayment', ['id' => $orders->getId() ]));
    }
			
}