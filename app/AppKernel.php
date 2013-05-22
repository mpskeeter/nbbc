<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new JMS\AopBundle\JMSAopBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),

// extra bundles added after symfony installation
			new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
			new RaulFraile\Bundle\LadybugBundle\RaulFraileLadybugBundle(),

			new FOS\UserBundle\FOSUserBundle(),
			new HWI\Bundle\OAuthBundle\HWIOAuthBundle(),
#			new FOS\FacebookBundle\FOSFacebookBundle(),

			new MPeters\NbbcBundle\NbbcBundle(),
			new MPeters\BBCEditorBundle\BBCEditorBundle(),

			new Sonata\BlockBundle\SonataBlockBundle(),
			new Sonata\jQueryBundle\SonatajQueryBundle(),
			new Sonata\AdminBundle\SonataAdminBundle(),
			new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
			new Sonata\EasyExtendsBundle\SonataEasyExtendsBundle(),
			new Sonata\CacheBundle\SonataCacheBundle(),
			new Sonata\UserBundle\SonataUserBundle('FOSUserBundle'),
			new Sonata\NotificationBundle\SonataNotificationBundle(),
			new Sonata\MediaBundle\SonataMediaBundle(),
			new Sonata\IntlBundle\SonataIntlBundle(),
//			new Sonata\MarkItUpBundle\SonataMarkItUpBundle(),
//			new Knp\Bundle\MarkdownBundle\KnpMarkdownBundle(),
//			new Ivory\CKEditorBundle\IvoryCKEditorBundle(),
//			new Sonata\FormatterBundle\SonataFormatterBundle(),
//			new FM\BbcodeBundle\FMBbcodeBundle(),

			new Knp\Bundle\MenuBundle\KnpMenuBundle(),

			new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
//			new Herzult\Bundle\ForumBundle\HerzultForumBundle(),
			new Ornicar\GravatarBundle\OrnicarGravatarBundle(),

//			new Application\Sonata\UserBundle\ApplicationSonataUserBundle(),
			new Poa\UserBundle\UserBundle(),
			new Poa\AdminBundle\PoaAdminBundle(),
//			new Poa\BlogBundle\PoaBlogBundle(),
//			new Poa\ForumBundle\ForumBundle(),
			new Poa\ForumBundle\PoaForumBundle(),
		);

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Poa\MainBundle\PoaMainBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
