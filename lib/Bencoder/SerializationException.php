<?php

namespace Bencoder;

class SerializationException extends \Exception
{
    private $_object;

    public function __construct($message, $object)
    {
        parent::__construct($message);

        $this->_object = $object;
    }

    public function getObject()
    {
        return $this->_object;
    }
}
