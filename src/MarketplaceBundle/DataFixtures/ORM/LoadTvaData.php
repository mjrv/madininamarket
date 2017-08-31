<?php 
namespace MarketplaceBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use MarketplaceBundle\Entity\Tva;
use Doctrine\Common\Persistence\ObjectManager;

/**
* 
*/
class LoadTvaData extends AbstractFixture implements OrderedFixtureInterface
{
	
	public function load(ObjectManager $manager)
	{
		$tva1 = new Tva();//ON defini notre variable en tant qu 'objet
		$tva1->setValue('18.5');//on lui defini un attribu donc une valeur
		$manager->persist($tva1);//prepare l objet a etre mis en base de donnee



		$tva2 = new Tva();
		$tva2->setValue('5.5');
		$manager->persist($tva2);


		$tva3 = new Tva();
		$tva3->setValue('20');
		$manager->persist($tva3);


		$tva4 = new Tva();
		$tva4->setValue('8.5');
		$manager->persist($tva4);


		$manager->flush();//ecrire dans la base ce qui est prepare

		$this->addReference('18.5',$tva1);
		$this->addReference('5.5',$tva2);
		$this->addReference('20',$tva3);
		$this->addReference('8.5',$tva4);
	}

	public function getOrder()
	{
		// the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        //l'ordre dans lequel la fonction va etre executee
        return 1;

	}
}