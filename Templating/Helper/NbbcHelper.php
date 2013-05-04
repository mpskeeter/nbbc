<?php

namespace MPeters\NbbcBundle\Templating\Helper;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Templating\Helper\Helper;
use MPeters\NbbcBundle\Manager\NbbcManager as NbbcManager;

/**
 * @author Al Ganiev <helios.ag@gmail.com>
 * @copyright 2012 Al Ganiev
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class NbbcHelper extends Helper
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
        $this->nbccManager = $nbbcManager;
    }

    /**
     * @param $value string
     *
     * @return string
     *
     * @throws \Twig_Error_Runtime
     */
    public function filter($value)
    {
        if (!is_string($value)) {
            throw new \Twig_Error_Runtime('The filter can be applied to strings only.');
        }

        return $this->nbbcManager->get(NbbcManager::NBBC_DEFAULT)->parse($value);
    }

    public function getName()
    {
        return 'nbbc';
    }

}
