<?php 

namespace Upload\UploadBundle\Listener;

use Doctrine\Common\EventArgs;
use Doctrine\Common\EventSubscriber;
use Upload\UploadBundle\Annotation\UploadAnnotationReader;
use Upload\UploadBundle\Handler\UploadHandler;

/**
* 
*/
class UploadSubscriber implements EventSubscriber
{
	private $reader;
	private $handler;
	
	function __construct(UploadAnnotationReader $reader, UploadHandler $handler)
	{
		$this->reader = $reader;
		$this->handler = $handler;
	}

	public function getSubscribedEvents()
	{
		return [
			'prePersist',
			'preUpadte',
			'postLoad',
			'postRemove'
		];
	}

	private function preEvent(EventArgs $event)
	{		
		$entity = $event->getEntity();
		foreach ($this->reader->getUploadableFields($entity) as $property => $annotation) 
		{
			$this->handler->uploadFile($entity, $property, $annotation);
		}
	}

	public function prePersist(EventArgs $event)
	{
		$this->preEvent($event);
	}

	public function preUpadte(EventArgs $event)
	{
		$this->preEvent($event);
	}

	public function postRemove(EventArgs $event)
	{
		$entity = $event->getEntity();
		foreach ($this->reader->getUploadableFields($entity) as $property => $annotation) 
		{
			$this->handler->removeFile($entity, $property);
		}
	}

	public function postLoad(EventArgs $event)
	{
		$entity = $event->getEntity();
		foreach ($this->reader->getUploadableFields($entity) as $property => $annotation) 
		{
			$this->handler->setFileFromFilename($entity, $property, $annotation);
		}
	}
}