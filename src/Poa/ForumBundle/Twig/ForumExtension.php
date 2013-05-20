<?php

namespace Poa\ForumBundle\Twig;

use Poa\ForumBundle\Entity\Category;
use Poa\ForumBundle\Entity\Topic;
use Poa\ForumBundle\Entity\Post;
use Poa\ForumBundle\Router\ForumUrlGenerator;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToTimestampTransformer;
use Symfony\Component\Translation\Translator;
use Twig_Extension;

class ForumExtension extends Twig_Extension
{
    protected $urlGenerator;
	protected $translator;

    public function __construct(ForumUrlGenerator $urlGenerator,Translator  $translator)
    {
        $this->urlGenerator = $urlGenerator;
		$this->translator = $translator;
	}

    /**
     * Returns a list of global functions to add to the existing list.
     *
     * @return array An array of global functions
     */
    public function getFunctions()
    {
        return array(
            'forum_urlForPost'             => new \Twig_Function_Method($this, 'urlForPost',                  array('is_safe' => array('html'))),
            'forum_urlForCategory'         => new \Twig_Function_Method($this, 'urlForCategory',              array('is_safe' => array('html'))),
            'forum_urlForCategoryAtomFeed' => new \Twig_Function_Method($this, 'urlForCategoryAtomFeed',      array('is_safe' => array('html'))),
            'forum_urlForTopic'            => new \Twig_Function_Method($this, 'urlForTopic',                 array('is_safe' => array('html'))),
            'forum_urlForTopicAtomFeed'    => new \Twig_Function_Method($this, 'urlForTopicAtomFeed',         array('is_safe' => array('html'))),
            'forum_urlForTopicReply'       => new \Twig_Function_Method($this, 'urlForTopicReply',            array('is_safe' => array('html'))),
			'forum_urlForPostEdit'         => new \Twig_Function_Method($this, 'urlForPostEdit',              array('is_safe' => array('html'))),
            'forum_topicNumPages'          => new \Twig_Function_Method($this, 'topicNumPages',               array('is_safe' => array('html'))),
            'forum_autoLink'               => new \Twig_Function_Method($this, 'autoLink',                    array('is_safe' => array('html'))),
			'distance_of_time_in_words'    => new \Twig_Function_Method($this, 'distanceOfTimeInWordsFilter', array('is_safe' => array('html'))),
			'time_ago_in_words'            => new \Twig_Function_Method($this, 'timeAgoInWordsFilter',        array('is_safe' => array('html'))),
            'cut'                          => new \Twig_Function_Method($this, 'filterCut',                   array('length' => false, $wordCut = false, 'appendix' => false))
        );
    }

    public function urlForCategory(Category $category, $absolute = false)
    {
        return $this->urlGenerator->urlForCategory($category, $absolute);
    }

    public function urlForCategoryAtomFeed(Category $category, $absolute = false)
    {
        return $this->urlGenerator->urlForCategoryAtomFeed($category, $absolute);
    }

    public function urlForTopic(Topic $topic, $absolute = false)
    {
        return $this->urlGenerator->urlForTopic($topic, $absolute);
    }

    public function urlForTopicAtomFeed(Topic $topic, $absolute = false)
    {
        return $this->urlGenerator->urlForTopicAtomFeed($topic, $absolute);
    }

    public function urlForTopicReply(Topic $topic, $absolute = false)
    {
        return $this->urlGenerator->urlForTopicReply($topic, $absolute);
    }

    public function urlForPost(Post $post, $absolute = false)
    {
        return $this->urlGenerator->urlForPost($post, $absolute);
    }

	public function urlForPostEdit(Post $post, $absolute = false)
	{
		return $this->urlGenerator->urlForPostEdit($post, $absolute);
	}

    public function topicNumPages(Topic $topic)
    {
        return $this->urlGenerator->getTopicNumPages($topic);
    }

    public function autoLink($text)
    {
        return $this->urlGenerator->autoLink($text);
    }

	/**
	 * Like distance_of_time_in_words, but where to_time is fixed to timestamp()
	 *
	 * @param $from_time String or DateTime
	 * @param bool $include_seconds
	 *
	 * @return mixed
	 */
	function timeAgoInWordsFilter($from_time, $include_seconds = false)
	{
		return $this->distanceOfTimeInWordsFilter($from_time, new \DateTime('now'), $include_seconds);
	}

	/**
	 * Reports the approximate distance in time between two times given in seconds
	 * or in a valid ISO string like.
	 * For example, if the distance is 47 minutes, it'll return
	 * "about 1 hour". See the source for the complete wording list.
	 *
	 * Integers are interpreted as seconds. So, by example to check the distance of time between
	 * a created user an it's last login:
	 * {{ user.createdAt|distance_of_time_in_words(user.lastLoginAt) }} returns "less than a minute".
	 *
	 * Set include_seconds to true if you want more detailed approximations if distance < 1 minute
	 *
	 * @param $from_time String or DateTime
	 * @param $to_time String or DateTime
	 * @param bool $include_seconds
	 *
	 * @return mixed
	 */
	public function distanceOfTimeInWordsFilter($from_time, $to_time = null, $include_seconds = false)
	{
		$datetime_transformer = new DateTimeToStringTransformer(null, null, 'Y-m-d H:i:s');
		$timestamp_transformer = new DateTimeToTimestampTransformer();

		# Transforming to Timestamp
		if (!($from_time instanceof \DateTime) && !is_numeric($from_time)) {
			$from_time = $datetime_transformer->reverseTransform($from_time);
			$from_time = $timestamp_transformer->transform($from_time);
		} elseif($from_time instanceof \DateTime) {
			$from_time = $timestamp_transformer->transform($from_time);
		}

		$to_time = empty($to_time) ? new \DateTime('now') : $to_time;

		# Transforming to Timestamp
		if (!($to_time instanceof \DateTime) && !is_numeric($to_time)) {
			$to_time = $datetime_transformer->reverseTransform($to_time);
			$to_time = $timestamp_transformer->transform($to_time);
		} elseif($to_time instanceof \DateTime) {
			$to_time = $timestamp_transformer->transform($to_time);
		}

		$distance_in_seconds = round(abs($to_time - $from_time));

		$found = 0;
		$o='';
		$t = array('year'=>31556926,'month'=>2629744,'week'=>604800, 'day'=>86400,'hour'=>3600,'minute'=>60,'second'=>1);
		foreach($t as $u=>$s){
			if($s<=$distance_in_seconds && $found <= 1){
				$found++;
				$v = floor($distance_in_seconds/$s);
				$o .= "$v $u".($v==1?'':'s') . ', ';
				$distance_in_seconds -= ($v * $s);
			}
		}
		if($o == '') {
			return 'just now';
		}
		else {
			return substr($o,0,-2).' ago';
		}
//
//
//		$distance_in_minutes = round((abs($to_time - $from_time))/60);
//		$distance_in_seconds = round(abs($to_time - $from_time));
//
//		if ($distance_in_minutes <= 1){
//			if ($include_seconds){
//				if ($distance_in_seconds < 5){
//					return $this->translator->trans('less than %seconds seconds ago', array('%seconds' => 5));
//				}
//				elseif($distance_in_seconds < 10){
//					return $this->translator->trans('less than %seconds seconds ago', array('%seconds' => 10));
//				}
//				elseif($distance_in_seconds < 20){
//					return $this->translator->trans('less than %seconds seconds ago', array('%seconds' => 20));
//				}
//				elseif($distance_in_seconds < 40){
//					return $this->translator->trans('half a minute ago');
//				}
//				elseif($distance_in_seconds < 60){
//					return $this->translator->trans('less than a minute ago');
//				}
//				else {
//					return $this->translator->trans('1 minute ago');
//				}
//			}
//			return ($distance_in_minutes===0) ? $this->translator->trans('less than a minute ago', array()) : $this->translator->trans('1 minute ago', array());
//		}
//		elseif ($distance_in_minutes <= 45){
//			return $this->translator->trans('%minutes minutes ago', array('%minutes' => $distance_in_minutes));
//		}
//		elseif ($distance_in_minutes <= 90){
//			return $this->translator->trans('about 1 hour ago');
//		}
//		elseif ($distance_in_minutes <= 1440){
//			return $this->translator->trans('about %hours hours ago', array('%hours' => round($distance_in_minutes/60)));
//		}
//		elseif ($distance_in_minutes <= 2880){
//			return $this->translator->trans('1 day ago');
//		}
//		else{
//			return $this->translator->trans('%days days ago', array('%days' => round($distance_in_minutes/1440)));
//		}
	}
	/**
	 * @param string $text
	 * @param integer $length
	 * @param boolean $wordCut
	 * @param string $appendix
	 * @return string
	 */
	public function filterCut($text, $length = 160, $wordCut = true, $appendix = ' ...')
	{
		$maxLength = (int)$length - strlen($appendix);
		if (strlen($text) > $maxLength) {
			if($wordCut){
				$text = substr($text, 0, $maxLength + 1);
				$text = substr($text, 0, strrpos($text, ' '));
			}
			else {
				$text = substr($text, 0, $maxLength);
			}
			$text .= $appendix;
		}

		return $text;
	}

    public function getName()
    {
        return 'forum';
    }
}
