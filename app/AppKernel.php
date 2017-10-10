<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            //new AppBundle\AppBundle(),
            new MarketplaceBundle\MarketplaceBundle(),// Bundle de travail un bundle est dossier qui contient l'ensemble des fichiers du site
            new FOS\UserBundle\FOSUserBundle(),// Bundle gestion des utilisateurs doc FOSUserBundle
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),//Bundle pour pagination
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),//gerer des extentios doctrine symfony
            new Vich\UploaderBundle\VichUploaderBundle(),//upload des media bundle
            new Upload\UploadBundle\UploadUploadBundle(),//upload de fichier
            new JMS\I18nRoutingBundle\JMSI18nRoutingBundle(),//generer les routes automatiquement pour chaque langues
            new Corley\MaintenanceBundle\CorleyMaintenanceBundle(), //Mettre le site en maintenance
            new JMS\Payment\CoreBundle\JMSPaymentCoreBundle(),
            new JMS\Payment\PaypalBundle\JMSPaymentPaypalBundle(),
            new WhiteOctober\TCPDFBundle\WhiteOctoberTCPDFBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(), //Permet de convertir les objets en json http://jmsyst.com/bundles/JMSSerializerBundle
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle();// pour Bundle de test -- DoctrineFixturesBundle
            $bundles[] = new CoreSphere\ConsoleBundle\CoreSphereConsoleBundle(); //Console pour acceder aux fonction console depuis le web

            if ('dev' === $this->getEnvironment()) {
                $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
                $bundles[] = new Symfony\Bundle\WebServerBundle\WebServerBundle();
            }
        }

        return $bundles;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        return dirname(__DIR__).'/var/cache/'.$this->getEnvironment();
    }

    public function getLogDir()
    {
        return dirname(__DIR__).'/var/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}
