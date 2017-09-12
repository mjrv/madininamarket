<?php 
namespace Market\MarketplaceBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use MarketplaceBundle\Entity\Category;
use Doctrine\Common\Persistence\ObjectManager;

/**
* 
*/
class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface
{
	
	public function load(ObjectManager $manager)
	{
		$category1 = new Category();//ON defini notre variable en tant qu 'objet
		$category1->setName('sport');//on lui defini un attribu donc une valeur
		$manager->persist($category1);//prepare l objet a etre mis en base de donnee


		$category2 = new Category();
		$category2->setName('Bricolage');
		$manager->persist($category2);


		$category3 = new Category();
		$category3->setName('high-tech');
		$manager->persist($category3);


		$category4 = new Category();
		$category4->setName('loisir');
		$manager->persist($category4);


		$manager->flush();//ecrire dans la base ce qui est prepare

		$this->addReference('sport',$category1);
		$this->addReference('Bricolage',$category2);
		$this->addReference('high-tech',$category3);
		$this->addReference('loisir',$category4);
	}

	public function getOrder()
	{
		// the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        //l'ordre dans lequel la fonction va etre executee
        return 2;

	}
}