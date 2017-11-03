<?php 

// namespace MarketplaceBundle\DataFixtures\ORM;

// use Doctrine\Common\DataFixtures\FixtureInterface;
// use Doctrine\Common\DataFixtures\AbstractFixture;
// use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
// use MarketplaceBundle\Entity\Shop;
// use Doctrine\Common\Persistence\ObjectManager;


// class LoadShopData extends AbstractFixture implements OrderedFixtureInterface
// {

// 	public function load(ObjectManager $manager)
// 	{
// 		$shop1 = new Shop();
// 		$shop1
// 			->setCommercialName('CARREFOUR')
// 			->setRaisonSocial('SADECCO')
// 			->setImmatriculation('12541010114588')
// 			->setApeCode('25254F')
// 			->setNameGerant('MARTIN')
// 			->setPhone('0123456789')
// 			// ->setPhone2()
// 			->setEmail('carr@carrefour.com')
// 			->setAdress('dillon')
// 			->setCity('fort de france')
// 			->setZipcode('97200')
// 			->setLogo('http://lorempixel.com/output/nature-q-c-50-50-4.jpg')
// 			->setCover('http://lorempixel.com/output/food-q-c-640-480-2.jpg')
// 			->setActive(1)
// 			->setPrefixeRef('car')
// 			->setGenerateAutoRef('0')
// 			// ->addItem($this->getReference('marteau'))
// 			->addUser($this->getReference('mjrv'));
// 		$manager->persist($shop1);
// 		$manager->flush();//ecrire dans la base ce qui est prepare

// 		$shop2 = new Shop();
// 		$shop2
// 			->setCommercialName('Nintendo')
// 			->setRaisonSocial('Jeux videos')
// 			->setImmatriculation('8952025820')
// 			->setApeCode('25254F')
// 			->setNameGerant('TAJIRI')
// 			->setPhone('0123456789')
// 			// ->setPhone2()
// 			->setEmail('nintendo@nintendo.com')
// 			->setAdress('tokyo')
// 			->setCity('kyoto')
// 			->setZipcode('000011')
// 			->setLogo('https://www.nintendo.com/images/social/fb-400x400.jpg')
// 			->setCover('http://www.ionik.fr/wp-content/uploads/2016/02/2759200-nintendo-logo.jpg')
// 			->setActive(1)
// 			->setPrefixeRef('nin')
// 			->setGenerateAutoRef('0')
// 			// ->addItem($this->getReference('kayak'))
// 			->addUser($this->getReference('mjrv'));
// 		$manager->persist($shop2);
// 		$manager->flush();//ecrire dans la base ce qui est prepare

// 		$this->addReference('carrefour',$shop1);
// 		$this->addReference('nintendo',$shop2);
// 		// $this->addReference('canoë kayak',$items2);
// 	}
// 	public function getOrder()
// 	{
// 		// the order in which fixtures will be loaded
//         // the lower the number, the sooner that this fixture is loaded
//         //l'ordre dans lequel la fonction va etre executee
//         return 4;
//     }

// }