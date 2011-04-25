<?php

require 'shared.php';

use Nrk\Bencode\Bencoder;

$bencode = file_get_contents('xubuntu-10.10-alternate-amd64.iso.torrent');
$torrent = Bencoder::unserialize($bencode);

// Remove info.pieces as it would print too much binary garbage.
unset($torrent['info']['pieces']);

var_dump($torrent);
