<?php 

namespace Upload\UploadBundle\Annotation;
use Upload\UploadBundle\Annotation\Uploadable;
use Upload\UploadBundle\Annotation\UploadableField;
// use Upload\UploadBundle\Annotation\UploadAnnotationReader;



/**
* 
*/
class UploadAnnotationReader
{
	/**
	 *@var UploadAnnotationReader
	 */
	private $reader;
	
	function __construct($reader)
	{
		$this->reader = $reader;
	}

	public function isUploadable($entity):bool
	{
		$reflection = new \ReflectionClass(get_class($entity));		
		return $this->reader->getClassAnnotation($reflection, Uploadable::Class) !== null;
	}

	public function getUploadableFields($entity):array
	{
		$reflection = new \ReflectionClass(get_class($entity));

		if(!$this->isUploadable($entity)) return [];

		$properties = [];
		
		foreach ($reflection->getProperties() as $property) 
		{
			$annotation = $this->reader->getPropertyAnnotation($property, UploadableField::Class);
			if($annotation !== null) $properties[$property->getName()] = $annotation;
		}

		return $properties;
	}
}