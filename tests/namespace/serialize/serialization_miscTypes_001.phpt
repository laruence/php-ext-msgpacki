--TEST--
Test serialize() & unserialize() functions: many types
--INI--
--SKIPIF--
<?php
if (PHP_INT_SIZE != 4) {
    die("skip this test is for 32bit platform only");
}
?>
--FILE--
<?php
namespace MessagePacki;

if (!extension_loaded('msgpacki')) {
    dl('msgpacki.' . PHP_SHLIB_SUFFIX);
}


echo "--- Testing Various Types ---\n";

/* unset variable */
$unset_var = 10;
unset($unset_var);
/* array declaration */
$arr_var = array(0, 1, -2, 3.333333, "a", array(), array(NULL));

$Variation_arr = array(
   /* Integers */
   2147483647,
   -2147483647,
   2147483648,
   -2147483648,

   0xFF00123,  // hex integers
   -0xFF00123,
   0x7FFFFFFF,
   -0x7FFFFFFF,
   0x80000000,
   -0x80000000,

   01234567,  // octal integers
   -01234567,

   /* arrays */
   array(),  // zero elements
   array(1, 2, 3, 12345666, -2344),
   array(0, 1, 2, 3.333, -4, -5.555, TRUE, FALSE, NULL, "", '', " ",
         array(), array(1,2,array()), "string", new \stdclass
        ),
   &$arr_var,  // Reference to an array

  /* nulls */
   NULL,
   null,

  /* strings */
   "",
   '',
   " ",
   ' ',
   "a",
   "string",
   'string',
   "hello\0",
   'hello\0',
   "123",
   '123',
   '\t',
   "\t",

   /* booleans */
   TRUE,
   true,
   FALSE,
   false,

   /* Mixed types */
   @TRUE123,
   "123string",
   "string123",
   "NULLstring",

   /* unset/undefined  vars */
   @$unset_var,
   @$undefined_var,
);

/* Loop through to test each element in the above array */
for( $i = 0; $i < count($Variation_arr); $i++ ) {

  echo "\n-- Iteration $i --\n";
  echo "after serialization => ";
  $serialize_data = serialize($Variation_arr[$i]);
  var_dump(bin2hex($serialize_data));

  echo "after unserialization => ";
  $unserialize_data = unserialize($serialize_data);
  var_dump($unserialize_data);
}

echo "\nDone";
?>
--EXPECTF--
--- Testing Various Types ---

-- Iteration 0 --
after serialization => string(10) "ce7fffffff"
after unserialization => int(2147483647)

-- Iteration 1 --
after serialization => string(10) "d280000001"
after unserialization => int(-2147483647)

-- Iteration 2 --
after serialization => string(18) "cb41e0000000000000"
after unserialization => float(2147483648)

-- Iteration 3 --
after serialization => string(18) "cbc1e0000000000000"
after unserialization => float(-2147483648)

-- Iteration 4 --
after serialization => string(10) "ce0ff00123"
after unserialization => int(267387171)

-- Iteration 5 --
after serialization => string(10) "d2f00ffedd"
after unserialization => int(-267387171)

-- Iteration 6 --
after serialization => string(10) "ce7fffffff"
after unserialization => int(2147483647)

-- Iteration 7 --
after serialization => string(10) "d280000001"
after unserialization => int(-2147483647)

-- Iteration 8 --
after serialization => string(18) "cb41e0000000000000"
after unserialization => float(2147483648)

-- Iteration 9 --
after serialization => string(18) "cbc1e0000000000000"
after unserialization => float(-2147483648)

-- Iteration 10 --
after serialization => string(10) "ce00053977"
after unserialization => int(342391)

-- Iteration 11 --
after serialization => string(10) "d2fffac689"
after unserialization => int(-342391)

-- Iteration 12 --
after serialization => string(2) "90"
after unserialization => array(0) {
}

-- Iteration 13 --
after serialization => string(34) "8500010102020303ce00bc614204d1f6d8"
after unserialization => array(5) {
  [0]=>
  int(1)
  [1]=>
  int(2)
  [2]=>
  int(3)
  [3]=>
  int(12345666)
  [4]=>
  int(-2344)
}

-- Iteration 14 --
after serialization => string(148) "de001000000101020203cb400aa9fbe76c8b4404fc05cbc0163851eb851eb806c307c208c009a00aa00ba1200c900d830001010202900ea6737472696e670f81c0a8737464436c617373"
after unserialization => array(16) {
  [0]=>
  int(0)
  [1]=>
  int(1)
  [2]=>
  int(2)
  [3]=>
  float(3.333)
  [4]=>
  int(-4)
  [5]=>
  float(-5.555)
  [6]=>
  bool(true)
  [7]=>
  bool(false)
  [8]=>
  NULL
  [9]=>
  string(0) ""
  [10]=>
  string(0) ""
  [11]=>
  string(1) " "
  [12]=>
  array(0) {
  }
  [13]=>
  array(3) {
    [0]=>
    int(1)
    [1]=>
    int(2)
    [2]=>
    array(0) {
    }
  }
  [14]=>
  string(6) "string"
  [15]=>
  object(stdClass)#2 (0) {
  }
}

-- Iteration 15 --
after serialization => string(52) "870000010102fe03cb400aaaaa7ded6ba904a1610590068100c0"
after unserialization => array(7) {
  [0]=>
  int(0)
  [1]=>
  int(1)
  [2]=>
  int(-2)
  [3]=>
  float(3.333333)
  [4]=>
  string(1) "a"
  [5]=>
  array(0) {
  }
  [6]=>
  array(1) {
    [0]=>
    NULL
  }
}

-- Iteration 16 --
after serialization => string(2) "c0"
after unserialization => NULL

-- Iteration 17 --
after serialization => string(2) "c0"
after unserialization => NULL

-- Iteration 18 --
after serialization => string(2) "a0"
after unserialization => string(0) ""

-- Iteration 19 --
after serialization => string(2) "a0"
after unserialization => string(0) ""

-- Iteration 20 --
after serialization => string(4) "a120"
after unserialization => string(1) " "

-- Iteration 21 --
after serialization => string(4) "a120"
after unserialization => string(1) " "

-- Iteration 22 --
after serialization => string(4) "a161"
after unserialization => string(1) "a"

-- Iteration 23 --
after serialization => string(14) "a6737472696e67"
after unserialization => string(6) "string"

-- Iteration 24 --
after serialization => string(14) "a6737472696e67"
after unserialization => string(6) "string"

-- Iteration 25 --
after serialization => string(14) "a668656c6c6f00"
after unserialization => string(6) "hello "

-- Iteration 26 --
after serialization => string(16) "a768656c6c6f5c30"
after unserialization => string(7) "hello\0"

-- Iteration 27 --
after serialization => string(8) "a3313233"
after unserialization => string(3) "123"

-- Iteration 28 --
after serialization => string(8) "a3313233"
after unserialization => string(3) "123"

-- Iteration 29 --
after serialization => string(6) "a25c74"
after unserialization => string(2) "\t"

-- Iteration 30 --
after serialization => string(4) "a109"
after unserialization => string(1) "	"

-- Iteration 31 --
after serialization => string(2) "c3"
after unserialization => bool(true)

-- Iteration 32 --
after serialization => string(2) "c3"
after unserialization => bool(true)

-- Iteration 33 --
after serialization => string(2) "c2"
after unserialization => bool(false)

-- Iteration 34 --
after serialization => string(2) "c2"
after unserialization => bool(false)

-- Iteration 35 --
after serialization => string(16) "a754525545313233"
after unserialization => string(7) "TRUE123"

-- Iteration 36 --
after serialization => string(20) "a9313233737472696e67"
after unserialization => string(9) "123string"

-- Iteration 37 --
after serialization => string(20) "a9737472696e67313233"
after unserialization => string(9) "string123"

-- Iteration 38 --
after serialization => string(22) "aa4e554c4c737472696e67"
after unserialization => string(10) "NULLstring"

-- Iteration 39 --
after serialization => string(2) "c0"
after unserialization => NULL

-- Iteration 40 --
after serialization => string(2) "c0"
after unserialization => NULL

Done
