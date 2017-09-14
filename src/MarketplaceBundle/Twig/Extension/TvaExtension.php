<?php 

namespace MarketplaceBundle\Twig\Extension;

class TvaExtension extends \Twig_Extension
{
	
	public function getFilters()
	{
		return [new \Twig_Simplefilter('tva', [$this, 'calculTtc'] )];
	}

	public function calculTtc($priceHt, $tva)
	{
		// return round($priceHt * $tva, 2);
		return round( $priceHt * (1+($tva/100)), 2 );
	}

	public function getName()
	{
		return 'tva_extension';
	}
}