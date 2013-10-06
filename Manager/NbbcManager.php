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
			'debug'              => false,
			'tag_marker'         => '[',
            'allow_ampersand'    => false,
			'ignore_new_lines'   => false,
			'plain_mode'         => false,
			'limit'              => 0,
			'limit_precision'    => null,
			'limit_tail'         => '...',
			'pre_trim'           => null,
			'post_trim'          => null,
			'wiki_url'           => null,
            'detect_urls'        => false,
            'url_targetable'     => false,
			'url_target'         => false,
			'local_img_url'      => null,
			'local_img_dir'      => null,
			'rule_html'          => '<hr />',
            'rules'              => array(),
			'smileys_enable'     => false,
			'smileys_url'        => null,
			'smileys_dir'        => 'smileys/'
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

		if(isset($options['debug']) && $options['debug'] != false) {
			$nbbc->SetDebug($options['debug']);
		}

		if(isset($options['tag_marker'])) {
			$nbbc->SetTagMarker($options['tag_marker']);
		}

		if(isset($options['allow_ampersand'])) {
			$nbbc->SetAllowAmpersand($options['allow_ampersand']);
		}

		if(isset($options['ignore_new_lines'])) {
			$nbbc->SetIgnoreNewlines($options['ignore_new_lines']);
		}

		if(isset($options['plain_mode'])) {
			$nbbc->SetPlainMode($options['plain_mode']);
		}

		if(isset($options['limit']) && $options['limit'] != 0) {
			$nbbc->SetLimit($options['limit']);
			if(isset($options['limit_precision']) && !is_null($options['limit_precision'])) {
				$nbbc->SetLimitPrecision($options['limit_precision']);
			}

			if(isset($options['limit_tail'])) {
				$nbbc->SetLimitTail($options['limit_tail']);
			}
		}

		if(isset($options['pre_trim']) && !is_null($options['pre_trim'])) {
			$nbbc->SetPreTrim($options['pre_trim']);
		}

		if(isset($options['post_trim']) && !is_null($options['post_trim'])) {
			$nbbc->SetPostTrim($options['post_trim']);
		}

		if(isset($options['wiki_url']) && !is_null($options['wiki_url'])) {
			$nbbc->SetWikiURL($options['wiki_url']);
		}

		if (isset($options['rule_html'])) {
			$nbbc->SetRuleHTML($options['rule_html']);
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
			if($setURLTarget == true && isset($options['url_target'])) {
				switch ($options['url_target']) {
					case 'true':
						$nbbc->SetURLTarget(true);
						break;
					case 'false':
						$nbbc->SetURLTarget(false);
						break;
					default:
						$nbbc->SetURLTarget($options['url_target']);
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

		if (isset($options['smileys_enable'])) {
			$nbbc->SetEnableSmileys($options['smileys_enable']);
		}

		if (isset($options['smileys_url']) && !is_null($options['smileys_url'])) {
			$nbbc->SetSmileyURL($options['smileys_url']);
		}

		if (isset($options['smileys_dir']) && !is_null($options['smileys_dir'])) {
			$nbbc->SetSmileyDir($options['smileys_dir']);
		}

		foreach($options['rules'] as $key => $params)
		{
			$nbbc->AddRule($key, $params);
		}

		$this->nbbcCollection[strtolower($filterSet)] = $nbbc;
	}
}
