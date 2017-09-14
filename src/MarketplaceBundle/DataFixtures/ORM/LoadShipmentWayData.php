<?php 
namespace MarketplaceBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use MarketplaceBundle\Entity\ShipmentWay;
use Doctrine\Common\Persistence\ObjectManager;

/**
* 
*/
class LoadTShipmentWayData extends AbstractFixture implements OrderedFixtureInterface
{
	
	public function load(ObjectManager $manager)
	{
		$ship1 = new ShipmentWay();//ON defini notre variable en tant qu 'objet
		$ship1	->setName('CHRONOPOST')//on lui defini un attribu donc une valeur
				->setIcon('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRExsRvX950d5EH3eRLyCxhnFvi8lS4phlchuTvV0PvXvBVPBca')
				->setPrice('12');
		$manager->persist($ship1);//prepare l objet a etre mis en base de donnee



		$ship2 = new ShipmentWay();//ON defini notre variable en tant qu 'objet
		$ship2	->setName('COLISSIMO')//on lui defini un attribu donc une valeur
				->setIcon('https://www.paris-prix.com/themes/parisprix/img/cms/livraison-colissimo.png')
				->setPrice('6');
		$manager->persist($ship2);//prepare l objet a etre mis en base de donnee



		$ship3 = new ShipmentWay();//ON defini notre variable en tant qu 'objet
		$ship3	->setName('Retrait en magasin')//on lui defini un attribu donc une valeur
				->setIcon('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRtZX4frabLC28S5PSBZai0lhyng9mSgihirAJ-2loNw9HDyEpn')
				->setPrice('0');
		$manager->persist($ship3);//prepare l objet a etre mis en base de donnee2


		

		$manager->flush();//ecrire dans la base ce qui est prepare
	}

	public function getOrder()
	{
		// the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        //l'ordre dans lequel la fonction va etre executee
        return 7;

	}
}