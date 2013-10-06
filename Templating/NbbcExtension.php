<?php

namespace MPeters\NbbcBundle\Templating;

use Symfony\Component\DependencyInjection\ContainerInterface;
use MPeters\NbbcBundle\Manager\NbbcManager as NbbcManager;
use Twig_Environment;

/**
 * @author Al Ganiev <helios.ag@gmail.com>
 * @copyright 2012 Al Ganiev
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class NbbcExtension extends \Twig_Extension
{
    /**
     * @var NbbcManager
     */
    protected $nbbcManager;

    /**
     * @param NbbcManager $nbbcManager
     */
    public function __construct(NbbcManager $nbbcManager)
    {
        $this->nbbcManager = $nbbcManager;
    }

    /**
     * (non-PHPdoc)
     * @see Twig_Extension::getFilters()
     * @return array
     */
    public function getFilters()
    {
        return array(
            'bbcode_filter' => new \Twig_Filter_Method($this, 'filter', array('needs_environment' => true,
																			  'is_safe'           => array('html'))),
        );
    }

    /**
	 * @param $env Twig_Environment
	 *
     * @param $value string
     *
     * @return string
     *
     * @throws \Twig_Error_Runtime
     */
    public function filter(Twig_Environment $env,$value)
    {
//		ladybug_dump($value);
        if (!is_string($value)) {
            throw new \Twig_Error_Runtime('The filter can be applied to strings only.');
        }

		$nbbc = $this->nbbcManager->get(NbbcManager::NBBC_DEFAULT);
		$output = $nbbc->parse($value);
//		ladybug_dump($output);
        return $output;
    }

    /**
     * (non-PHPdoc)
     * @see Twig_ExtensionInterface::getName()
     */
    public function getName()
    {
        return 'nbbc';
    }
}
