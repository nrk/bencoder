# Bencoder #

### About ###

__Bencoder__ is an utility class implemented in pure PHP that handles the serialization and deserialization of objects
using the [Bencode](http://en.wikipedia.org/wiki/Bencode) encoding format.

The original implementation of this class dates back to the early months of 2004 and it has been somewhat adapted
and updated in order to work with modern versions of the PHP interpreter right before making it public. It does
not ship with any kind of tests yet (sorry, I was still ignorant back then!) but I will add them in some spare time.
Basically I am making this library open source just because someone might find it useful and it would be a shame to
let it rot in the meanders of my backups.

### Implementation details ###

- As per specifications, this class does not handle float / double values.
- The serialization and deserialization of integers greater than _2147483647_ works only when using a 64bit PHP interpreter.
- PHP arrays containing one or more string values as keys are interpreted as Bencode dictionaries.
- The serializer does not check for circular references and it breaks generating a stack overflow error.

### Example ###

``` php
<?php
require 'autoloader.php';

use Bencoder\Bencode;

$structure = array("oh", "rly?", "ya", "rly!");
$encoded = Bencode::encode($structure);
$decoded = Bencode::decode($encoded);
```

### Author ###

- [Daniele Alessandri](mailto:suppakilla@gmail.com) ([twitter](http://twitter.com/JoL1hAHN))

### License ###

The code for __Bencoder__ is distributed under the terms of the MIT license (see LICENSE).
