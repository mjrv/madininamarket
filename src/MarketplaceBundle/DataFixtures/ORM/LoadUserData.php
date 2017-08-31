<?php 

//src/MarketplaceBundle/DataFixtures/ORM/LoadUserData.pphp
namespace MarketplaceBundle\DataFixtures\ORM;

use Symfony\component\DependencyInjection\ContainerAwareInterface;//attention zone de securite
use Symfony\component\DependencyInjection\ContainerInterface;//securite
use Doctrine\Common\Persistence\ObjectManager;//pour manipuler les objets de la base (crud)
use Doctrine\Common\DataFixtures\AbstractFixture;//lie au Ficture pour l'ordre (return ..............)
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;//lie au Ficture pour l'ordre (return ..............)
use MarketplaceBundle\Entity\User;


/**
* 
*/
class LoadUserData extends AbstractFixture implements OrderedFixtureInterface,ContainerAwareInterface
{
	/**
	*@var ContainerInterface 
	*/
	private $container;

	public function setContainer(ContainerInterface $container=null)
		{
			$this->container=$container;
		}

	public function load(ObjectManager $manager)
		{
			$encoder=$this->container->get("security.password_encoder");
			$user1 = new User();
			$password=$encoder->encodePassword($user1,"0000");
			$user1 
				->setUsername('mjrv')
				->setEmail('hervejurin972@gmail.com')
				->setEnabled(1)
				->setPassword($password)
				->setRoles(['ROLE_ADMIN']);
			$manager->persist($user1);

			$user2 = new User();
			$password=$encoder->encodePassword($user2,"0000");
			$user2 
				->setUsername('toto')
				->setEmail('toto@gmail.com')
				->setEnabled(1)
				->setPassword($password);
			$manager->persist($user2);

			$user3 = new User();
			$password=$encoder->encodePassword($user3,"0000");
			$user3 
				->setUsername('tata')
				->setEmail('tata@gmail.com')
				->setEnabled(1)
				->setPassword($password)
				->setRoles(['ROLE_EDITOR']);
			$manager->persist($user3);

			$manager->flush();//ecrire dans la base ce qui est prepare

			$this->addReference('mjrv',$user1);
			$this->addReference('toto',$user2);
			$this->addReference('tata',$user3);
		}	

	public function getOrder()
	{
		// the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        //l'ordre dans lequel la fonction va etre executee
        return 3;

	}
}