<?php 

//src/MarketplaceBundle/DataFixtures/ORM/LoadUserData.pphp
namespace MarketplaceBundle\DataFixtures\ORM;

use Symfony\component\DependencyInjection\ContainerAwareInterface;
use Symfony\component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use MarketplaceBundle\Entity\User;


/**
* 
*/
class LoadUserData extends AbstractFixture implements OrderedFixtureInterface,ContainerAwareInterface
{
	public function load(ObjectManager $manager)
	public function getOrder()
	{
		// the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        //l'ordre dans lequel la fonction va etre executee
        return 4;

	}
}