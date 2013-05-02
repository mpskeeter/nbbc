<?php
namespace MPeters\NbbcBundle\Manager;

use MPeters\NbbcBundle\src\bbcode as bbcode;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author Al Ganiev <helios.ag@gmail.com>
 * @copyright 2013 Al Ganiev
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class NbbcManager
{
	const NBBC_DEFAULT = "_default";

	/**
	 * @var bbcode[]
	 */
	private $nbbcCollection;
	/**
     * @var array
     */
    private $options = array();


    /**
     * @var bbcode
     */
    private $preConfiguredNbbc;

    /**
     * @param array $options  An array of options
     */
    public function __construct(array $options = array())
    {
        $this->setOptions($options);
    }

	/**
	 * Gets a specific bbcode
	 *
	 * @param string $filterSet  The specific filter_set to apply
	 * @return bbcode
	 */
	public function get($filterSet = self::NBBC_DEFAULT)
	{
//		ladybug_dump($filterSet);
		if (!isset($this->nbbcCollection[strtolower($filterSet)])) {
			$this->set(strtolower($filterSet));
		}

		return $this->nbbcCollection[strtolower($filterSet)];
	}

	/**
     * Sets options.
     *
     * Available options:
     *
     *   * allow_ampersand:
     *   * detect_urls:
     *   * url_targetable:
     *   * rules:
	 *   * smileys:
     *
     * @param array $options An array of options
     *
     * @throws \InvalidArgumentException When unsupported option is provided
     */
    private function setOptions(array $options)
    {
        $this->options = array(
            'allow_ampersand'    => false,
            'detect_urls'        => false,
            'url_targetable'     => false,
			'set_url_target'     => false,
			'local_img_url'      => null,
			'local_img_dir'      => null,
            'rules'              => array(),
			'smileys'            => 'smileys/',
			'extension'          => 'extension',
        );

        // check option names and live merge, if errors are encountered Exception will be thrown
        $invalid = array();
        foreach ($options as $key => $value) {
            if (array_key_exists($key, $this->options)) {
                $this->options[$key] = $value;
            } else {
                $invalid[] = $key;
            }
        }

        if ($invalid) {
            throw new \InvalidArgumentException(sprintf('The nbbcManager does not support the following options: "%s".', implode('\', \'', $invalid)));
        }
    }

    /**
     * Sets an option.
     *
     * @param string $key   The key
     * @param mixed  $value The value
     *
     * @throws \InvalidArgumentException
     */
    private function setOption($key, $value)
    {
        if (!array_key_exists($key, $this->options)) {
            throw new \InvalidArgumentException(sprintf('The nbbcManager does not support the "%s" option.', $key));
        }

        $this->options[$key] = $value;
    }

    /**
     * Gets an option value.
     *
     * @param string $key The key
     *
     * @return mixed The value
     *
     * @throws \InvalidArgumentException
     */
    private function getOption($key)
    {
        if (!array_key_exists($key, $this->options)) {
            throw new \InvalidArgumentException(sprintf('The nbbcManager does not support the "%s" option.', $key));
        }

        return $this->options[$key];
    }

	private function getPreConfiguredNbbc()
	{
		if (null !== $this->preConfiguredNbbc) {
			return $this->preConfiguredNbbc;
		}

		$this->preConfiguredNbbc = new bbcode();

		return $this->preConfiguredNbbc;
	}


	/**
	 * @param $filterSet
	 * @param $nbbc bbcode
	 * @internal param string $filterName
	 */
	private function set($filterSet, bbcode $nbbc = null)
	{
		if ($nbbc == null)
		{
			$nbbc = new bbcode();
		}

		if (isset($this->options)) {
			$options = $this->options;
		}

		if(isset($options['allow_ampersand'])) {
			$nbbc->SetAllowAmpersand($options['allow_ampersand']);
		}

		if (isset($options['detect_urls'])) {
			$nbbc->SetDetectURLs($options['detect_urls']);
		}

		if (isset($options['url_targetable'])) {
			switch ($options['url_targetable']) {
				case 'true':
					$nbbc->SetURLTargetable(true);
					$setURLTarget = true;
					break;
				case 'false':
					$nbbc->SetURLTargetable(false);
					$setURLTarget = false;
					break;
				default:
					$nbbc->SetURLTargetable($options['url_targetable']);
					$setURLTarget = true;
					break;
			}
			if($setURLTarget == true && isset($options['set_url_target'])) {
				switch ($options['set_url_target']) {
					case 'true':
						$nbbc->SetURLTarget(true);
						break;
					case 'false':
						$nbbc->SetURLTarget(false);
						break;
					default:
						$nbbc->SetURLTarget($options['set_url_target']);
						break;
				}
			}
		}

		if (isset($options['local_img_url'])) {
			$nbbc->SetLocalImgURL($options['local_img_url']);
		}

		if (isset($options['local_img_dir'])) {
			$nbbc->SetLocalImgDir($options['local_img_dir']);
		}

		if (isset($options['smileys'])) {
			$nbbc->SetSmileyDir($options['smileys']);
		}

		foreach($options['rules'] as $key => $params)
		{
			$nbbc->AddRule($key, $params);
		}

		$this->nbbcCollection[strtolower($filterSet)] = $nbbc;
	}
}
