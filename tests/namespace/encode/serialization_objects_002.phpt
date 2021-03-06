--TEST--
Test encode() & decode() functions: objects (variations)
--INI--
--FILE--
<?php
namespace MessagePacki;

if (!extension_loaded('msgpacki')) {
    dl('msgpacki.' . PHP_SHLIB_SUFFIX);
}

echo "\n--- Testing Variations in objects ---\n";

class members
{
  private $var_private = 10;
  protected $var_protected = "string";
  public $var_public = array(-100.123, "string", TRUE);
}

class nomembers { }

class C {
  var $a, $b, $c, $d, $e, $f, $g, $h;
  function __construct() {
    $this->a = 10;
    $this->b = "string";
    $this->c = TRUE;
    $this->d = -2.34444;
    $this->e = array(1, 2.22, "string", TRUE, array(),
                     new members(), null);
    $this->f = new nomembers();
    $this->g = $GLOBALS['file_handle'];
    $this->h = NULL;
  }
}

class D extends C {
  function __construct( $w, $x, $y, $z ) {
    $this->a = $w;
    $this->b = $x;
    $this->c = $y;
    $this->d = $z;
  }
}

$variation_obj_arr = array(
  new C(),
  new D( 1, 2, 3333, 444444 ),
  new D( .5, 0.005, -1.345, 10.005e5 ),
  new D( TRUE, true, FALSE, false ),
  new D( "a", 'a', "string", 'string' ),
  new D( array(),
         array(1, 2.222, TRUE, FALSE, "string"),
         array(new nomembers(), $file_handle, NULL, ""),
         array(array(1,2,3,array()))
       ),
  new D( NULL, null, "", "\0" ),
  new D( new members, new nomembers, $file_handle, NULL),
);

/* Testing serialization on all the objects through loop */
foreach( $variation_obj_arr as $object) {

  echo "After Serialization => ";
  $serialize_data = encode( $object );
  var_dump( bin2hex($serialize_data) );

  echo "After Unserialization => ";
  $unserialize_data = decode( $serialize_data );
  var_dump( $unserialize_data );
}

echo "\nDone";
?>
--EXPECTF--
--- Testing Variations in objects ---

Notice: Undefined index: file_handle in %s on line 29

Notice: Undefined variable: file_handle in %s on line 51

Notice: Undefined variable: file_handle in %s on line 55
After Serialization => string(246) "88a1610aa162a6737472696e67a163c3a164cbc002c169c23b7953a1659701cb4001c28f5c28f5c3a6737472696e67c39083ab7661725f707269766174650aad7661725f70726f746563746564a6737472696e67aa7661725f7075626c696393cbc05907df3b645a1da6737472696e67c3c0a16680a167c0a168c0"
After Unserialization => array(8) {
  ["a"]=>
  int(10)
  ["b"]=>
  string(6) "string"
  ["c"]=>
  bool(true)
  ["d"]=>
  float(-2.34444)
  ["e"]=>
  array(7) {
    [0]=>
    int(1)
    [1]=>
    float(2.22)
    [2]=>
    string(6) "string"
    [3]=>
    bool(true)
    [4]=>
    array(0) {
    }
    [5]=>
    array(3) {
      ["var_private"]=>
      int(10)
      ["var_protected"]=>
      string(6) "string"
      ["var_public"]=>
      array(3) {
        [0]=>
        float(-100.123)
        [1]=>
        string(6) "string"
        [2]=>
        bool(true)
      }
    }
    [6]=>
    NULL
  }
  ["f"]=>
  array(0) {
  }
  ["g"]=>
  NULL
  ["h"]=>
  NULL
}
After Serialization => string(62) "88a16101a16202a163cd0d05a164ce0006c81ca165c0a166c0a167c0a168c0"
After Unserialization => array(8) {
  ["a"]=>
  int(1)
  ["b"]=>
  int(2)
  ["c"]=>
  int(3333)
  ["d"]=>
  int(444444)
  ["e"]=>
  NULL
  ["f"]=>
  NULL
  ["g"]=>
  NULL
  ["h"]=>
  NULL
}
After Serialization => string(114) "88a161cb3fe0000000000000a162cb3f747ae147ae147ba163cbbff5851eb851eb85a164cb412e886800000000a165c0a166c0a167c0a168c0"
After Unserialization => array(8) {
  ["a"]=>
  float(0.5)
  ["b"]=>
  float(0.005)
  ["c"]=>
  float(-1.345)
  ["d"]=>
  float(1000500)
  ["e"]=>
  NULL
  ["f"]=>
  NULL
  ["g"]=>
  NULL
  ["h"]=>
  NULL
}
After Serialization => string(50) "88a161c3a162c3a163c2a164c2a165c0a166c0a167c0a168c0"
After Unserialization => array(8) {
  ["a"]=>
  bool(true)
  ["b"]=>
  bool(true)
  ["c"]=>
  bool(false)
  ["d"]=>
  bool(false)
  ["e"]=>
  NULL
  ["f"]=>
  NULL
  ["g"]=>
  NULL
  ["h"]=>
  NULL
}
After Serialization => string(78) "88a161a161a162a161a163a6737472696e67a164a6737472696e67a165c0a166c0a167c0a168c0"
After Unserialization => array(8) {
  ["a"]=>
  string(1) "a"
  ["b"]=>
  string(1) "a"
  ["c"]=>
  string(6) "string"
  ["d"]=>
  string(6) "string"
  ["e"]=>
  NULL
  ["f"]=>
  NULL
  ["g"]=>
  NULL
  ["h"]=>
  NULL
}
After Serialization => string(106) "88a16190a1629501cb4001c6a7ef9db22dc3c2a6737472696e67a1639480c0c0a0a164919401020390a165c0a166c0a167c0a168c0"
After Unserialization => array(8) {
  ["a"]=>
  array(0) {
  }
  ["b"]=>
  array(5) {
    [0]=>
    int(1)
    [1]=>
    float(2.222)
    [2]=>
    bool(true)
    [3]=>
    bool(false)
    [4]=>
    string(6) "string"
  }
  ["c"]=>
  array(4) {
    [0]=>
    array(0) {
    }
    [1]=>
    NULL
    [2]=>
    NULL
    [3]=>
    string(0) ""
  }
  ["d"]=>
  array(1) {
    [0]=>
    array(4) {
      [0]=>
      int(1)
      [1]=>
      int(2)
      [2]=>
      int(3)
      [3]=>
      array(0) {
      }
    }
  }
  ["e"]=>
  NULL
  ["f"]=>
  NULL
  ["g"]=>
  NULL
  ["h"]=>
  NULL
}
After Serialization => string(52) "88a161c0a162c0a163a0a164a100a165c0a166c0a167c0a168c0"
After Unserialization => array(8) {
  ["a"]=>
  NULL
  ["b"]=>
  NULL
  ["c"]=>
  string(0) ""
  ["d"]=>
  string(1) " "
  ["e"]=>
  NULL
  ["f"]=>
  NULL
  ["g"]=>
  NULL
  ["h"]=>
  NULL
}
After Serialization => string(176) "88a16183ab7661725f707269766174650aad7661725f70726f746563746564a6737472696e67aa7661725f7075626c696393cbc05907df3b645a1da6737472696e67c3a16280a163c0a164c0a165c0a166c0a167c0a168c0"
After Unserialization => array(8) {
  ["a"]=>
  array(3) {
    ["var_private"]=>
    int(10)
    ["var_protected"]=>
    string(6) "string"
    ["var_public"]=>
    array(3) {
      [0]=>
      float(-100.123)
      [1]=>
      string(6) "string"
      [2]=>
      bool(true)
    }
  }
  ["b"]=>
  array(0) {
  }
  ["c"]=>
  NULL
  ["d"]=>
  NULL
  ["e"]=>
  NULL
  ["f"]=>
  NULL
  ["g"]=>
  NULL
  ["h"]=>
  NULL
}

Done
