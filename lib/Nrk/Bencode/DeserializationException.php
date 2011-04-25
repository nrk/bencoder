<?php

namespace Nrk\Bencode;

class DeserializationException extends \Exception {
    private $_offset;

    public function __construct($message, $bufferOffset) {
        parent::__construct($message);
        $this->_offset = $bufferOffset;
    }

    public function getOffset() {
        return $this->_offset;
    }
}
