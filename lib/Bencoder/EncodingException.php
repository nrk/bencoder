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

/**
 * Exceptions class that identifies errors occurred during the
 * encoding process.
 *
 * @author Daniele Alessandri <suppakilla@gmail.com>
 */
class EncodingException extends \Exception
{
    private $object;

    /**
     * @param string $message Exception message.
     * @return mixed $object Object that triggered the encoding error.
     */
    public function __construct($message, $object)
    {
        parent::__construct($message);

        $this->object = $object;
    }

    /**
     * Returns the object that triggered the encoding error.
     *
     * @return mixed
     */
    public function getObject()
    {
        return $this->object;
    }
}
