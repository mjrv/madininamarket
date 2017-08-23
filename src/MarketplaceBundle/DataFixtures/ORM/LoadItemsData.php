<?php 
namespace MarketplaceBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use MarketplaceBundle\Entity\Items;
use Doctrine\Common\Persistence\ObjectManager;

/**
* 
*/
class LoadItemsData extends AbstractFixture implements OrderedFixtureInterface
{
	
	public function load(ObjectManager $manager)
	{
		$items1 = new Items();//ON defini notre variable en tant qu 'objet
		$items1
			->setReference('bric-0123456') //on lui defini un attribu donc une valeur
			->setName('marteau')
			->setPicture('https://www.leroymerlin.fr/multimedia/921400082987/produits/marteau-arrache-clous-metal-0-45-kg.jpg')
			->setDescription('outil permettant de clouer')
			->setPriceHt('22')
			// ->setDiscount()
			->setStock('30')
			->setTva($this->getReference('18.5'))
			->setCategory($this->getReference('Bricolage'))
			->setVerify('1');
		$manager->persist($items1);//prepare l objet a etre mis en base de donnee


		$items2 = new Items();//ON defini notre variable en tant qu 'objet
		$items2
			->setReference('inter-0123456') //on lui defini un attribu donc une valeur
			->setName('canoë kayak')
			->setPicture('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSWOZDvG8KLIw8E9ukRcUkMLhYRJmJyw5h4faMxFaEJHGMebvra')
			->setDescription('Le canoë-kayak se pratique en loisir (tourisme nautique, pratique individuelle ou associative) ou en compétition, dans les milieux d\'eau calme (étangs), d\'eau vive (rivières) et maritime (estuaires, mer). La sécurité implique la maîtrise du bateau, un entraînement technique et physique, l\'équipement, l\'information préalable des conditions du parcours (météo, état du parcours), l\'encadrement… variables selon le type de pratique.')
			->setPriceHt('320')
			// ->setDiscount()
			->setStock('20')
			->setTva($this->getReference('18.5'))
			->setCategory($this->getReference('loisir'))
			->setVerify('0');

		$manager->persist($items2);//prepare l objet a etre mis en base de donnee


	

		$manager->flush();//ecrire dans la base ce qui est prepare

		$this->addReference('marteau',$items1);
		$this->addReference('canoë kayak',$items2);
	// 	$this->addReference('20',$items3);
	// 	$this->addReference('8.5',$items4);
	}

	public function getOrder()
	{
		// the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        //l'ordre dans lequel la fonction va etre executee
        return 3;

	}
}