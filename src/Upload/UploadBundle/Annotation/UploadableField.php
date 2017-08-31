<?php 

namespace Upload\UploadBundle\Annotation;

use Doctrine\Common\Annotations\Annotation\Target;


/**
* @Annotation
* @Target("PROPERTY")
*/
class UploadableField
{

	/**
	 * [$filename description]
	 * @var string
	 */
	private $filename;

	/**
	 * [$path description]
	 * @var string
	 */
	private $path;

	// private $updateAt;
	
	function __construct(array $options)
	{
		// die(var_dump($options));
		if(empty($options['filename'])) throw new \InvalidArgumentException("L'annotation uploadablefields doit avoir un attribut 'filename'");

		if(empty($options['path'])) throw new \InvalidArgumentException("L'annotation uploadablefields doit avoir un attribut 'path'");
		
		// if(empty($options['updateAt'])) throw new \InvalidArgumentException("L'annotation uploadablefields doit avoir un attribut 'updateAt'");
		$this->filename = $options['filename'];
		$this->path = $options['path'];
		// $this->updateAt = $options['updateAt'];
	}

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return mixed
     */
    public function getFilename()
    {
        return $this->filename;
    }

    // /**
    //  * @return mixed
    //  */
    // public function getUpdateAt()
    // {
    //     return $this->updateAt;
    // }
}