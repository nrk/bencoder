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
 * decoding process.
 *
 * @author Daniele Alessandri <suppakilla@gmail.com>
 */
class DecodingException extends \Exception
{
    private $offset;

    /**
     * @param string $message Exception message.
     * @return int $bufferOffset Estimated position of the error in the buffer.
     */
    public function __construct($message, $bufferOffset)
    {
        parent::__construct($message);

        $this->offset = $bufferOffset;
    }

    /**
     * Returns the estimated position of the error in the buffer.
     *
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }
}
