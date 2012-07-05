--TEST--
Test msgpacki_serialize() & msgpacki_unserialize() functions: objects (abstract classes)
--INI--
--FILE--
<?php
if (!extension_loaded('msgpacki')) {
    dl('msgpacki.' . PHP_SHLIB_SUFFIX);
}

echo "\n--- Testing Abstract Class ---\n";
// abstract class
abstract class Name
{
  public function __construct() {
    $this->a = 10;
    $this->b = 12.222;
    $this->c = "string";
  }
  abstract protected function getClassName();
  public function printClassName () {
    return $this->getClassName();
  }
}
// implement abstract class
class extendName extends Name
{
  var $a, $b, $c;

  protected function getClassName() {
    return "extendName";
  }
}

$obj_extendName = new extendName();
$serialize_data = msgpacki_serialize($obj_extendName);
var_dump( bin2hex($serialize_data) );
$unserialize_data = msgpacki_unserialize($serialize_data);
var_dump( $unserialize_data );

$serialize_data = msgpacki_serialize($obj_extendName->printClassName());
var_dump( bin2hex($serialize_data) );
$unserialize_data = msgpacki_unserialize($serialize_data);
var_dump( $unserialize_data );

echo "\nDone";
?>
--EXPECTF--
--- Testing Abstract Class ---
string(72) "84c0aa657874656e644e616d65a1610aa162cb402871a9fbe76c8ba163a6737472696e67"
object(extendName)#%d (3) {
  ["a"]=>
  int(10)
  ["b"]=>
  float(12.222)
  ["c"]=>
  string(6) "string"
}
string(22) "aa657874656e644e616d65"
string(10) "extendName"

Done