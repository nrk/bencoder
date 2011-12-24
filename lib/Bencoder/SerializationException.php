<?php

/*
 * This file is part of the Bencoder package.
 *
 * (c) Daniele Alessandri <suppakilla@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
