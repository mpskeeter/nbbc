<?php

	namespace Poa\ForumBundle\Blamer;

	use Poa\ForumBundle\Blamer\AbstractSecurityBlamer;

	class TopicBlamer extends AbstractSecurityBlamer
	{
		public function blame($object)
		{
			$object->setAuthor($this->security->getToken()->getUser());
		}
	}