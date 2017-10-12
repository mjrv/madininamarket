<?php 
namespace MarketplaceBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use MarketplaceBundle\Entity\Picture;
use Doctrine\Common\Persistence\ObjectManager;

/**
* 
*/
// class LoadPictureData extends AbstractFixture implements OrderedFixtureInterface
// {
	
// 	public function load(ObjectManager $manager)
// 	{
// 		$picture1 = new Picture();//ON defini notre variable en tant qu 'objet
// 		$picture1
// 				// ->setUrl('https://www.leroymerlin.fr/multimedia/921400082987/produits/marteau-arrache-clous-metal-0-45-kg.jpg')//on lui defini un attribu donc une valeur
// 				->setUpdatedAt(new \Datetime())
// 				->setItems($this->getReference('marteau'));
// 		$manager->persist($picture1);//prepare l objet a etre mis en base de donnee


// 		$picture2 = new Picture();//ON defini notre variable en tant qu 'objet
// 		$picture2
// 				// ->setUrl('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSWOZDvG8KLIw8E9ukRcUkMLhYRJmJyw5h4faMxFaEJHGMebvra')//on lui defini un attribu donc une valeur
// 				->setUpdatedAt(new \Datetime())
// 				->setItems($this->getReference('kayak'));
// 		$manager->persist($picture2);//prepare l objet a etre mis en base de donne2

// 		$manager->flush();//ecrire dans la base ce qui est prepare


// 		$picture3 = new Picture();//ON defini notre variable en tant qu 'objet
// 		$picture3
// 				// ->setUrl('https://c4.eb-cdn.com.au/website/videos/images/screenshots/202879_screenshot_05_l.jpg')//on lui defini un attribu donc une valeur
// 				->setUpdatedAt(new \Datetime())
// 				->setItems($this->getReference('switch'));
// 		$manager->persist($picture3);//prepare l objet a etre mis en base de donne2

// 		$manager->flush();//ecrire dans la base ce qui est prepare

// 		$picture4 = new Picture();//ON defini notre variable en tant qu 'objet
// 		$picture4
// 				// ->setUrl('https://c4.eb-cdn.com.au/website/videos/images/screenshots/202879_screenshot_05_l.jpg')//on lui defini un attribu donc une valeur
// 				->setUpdatedAt(new \Datetime())
// 				->setItems($this->getReference('velo'));
// 		$manager->persist($picture4);//prepare l objet a etre mis en base de donne2

// 		$manager->flush();//ecrire dans la base ce qui est prepare4

// 	}

// 	public function getOrder()
// 	{
// 		// the order in which fixtures will be loaded
//         // the lower the number, the sooner that this fixture is loaded
//         //l'ordre dans lequel la fonction va etre executee
//         return 6;

// 	}
// }