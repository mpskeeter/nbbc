<?php

	namespace Poa\ForumBundle\Blamer;

	use Poa\ForumBundle\Entity\Post;
	use Poa\ForumBundle\Blamer\AbstractSecurityBlamer;

	class PostBlamer extends AbstractSecurityBlamer
	{
		/**
		 * @param Post $object
		 */
		public function blame(Post $object)
		{
			$object->setAuthor($this->security->getToken()->getUser());
		}

		/**
		 * @param Post $object
		 */
		public function blameUpdate(Post $object)
		{
			$object->setModifiedAuthor($this->security->getToken()->getUser());
		}
	}