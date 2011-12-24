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

class DeserializationException extends \Exception
{
    private $_offset;

    public function __construct($message, $bufferOffset)
    {
        parent::__construct($message);

        $this->_offset = $bufferOffset;
    }

    public function getOffset()
    {
        return $this->_offset;
    }
}
