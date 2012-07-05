--TEST--
serialization: arrays with references amonst elements
--FILE--
<?php


if (!extension_loaded('msgpacki')) {
    dl('msgpacki.' . PHP_SHLIB_SUFFIX);
}

function check(&$a) {
    var_dump($a);
    $ser = msgpacki_encode($a);
    var_dump(bin2hex($ser));

    $b = msgpacki_decode($ser);
    var_dump($b);
    $b[0] = "b0.changed";
    var_dump($b);
    $b[1] = "b1.changed";
    var_dump($b);
    $b[2] = "b2.changed";
    var_dump($b);
}

echo "\n\n--- No references:\n";
$a = array();
$a[0] = 1;
$a[1] = 1;
$a[2] = 1;
check($a);

echo "\n\n--- 0 refs 1:\n";
$a = array();
$a[0] = &$a[1];
$a[1] = 1;
$a[2] = 1;
check($a);

echo "\n\n--- 0 refs 2:\n";
$a = array();
$a[0] = &$a[2];
$a[1] = 1;
$a[2] = 1;
check($a);

echo "\n\n--- 1 refs 0:\n";
$a = array();
$a[0] = 1;
$a[1] = &$a[0];
$a[2] = 1;
check($a);

echo "\n\n--- 1 refs 2:\n";
$a = array();
$a[0] = 1;
$a[1] = &$a[2];
$a[2] = 1;
check($a);

echo "\n\n--- 2 refs 0:\n";
$a = array();
$a[0] = 1;
$a[1] = 1;
$a[2] = &$a[0];
check($a);

echo "\n\n--- 2 refs 1:\n";
$a = array();
$a[0] = 1;
$a[1] = 1;
$a[2] = &$a[1];
check($a);

echo "\n\n--- 0,1 ref 2:\n";
$a = array();
$a[0] = &$a[2];
$a[1] = &$a[2];
$a[2] = 1;
check($a);

echo "\n\n--- 0,2 ref 1:\n";
$a = array();
$a[0] = &$a[1];
$a[1] = 1;
$a[2] = &$a[1];
check($a);

echo "\n\n--- 1,2 ref 0:\n";
$a = array();
$a[0] = 1;
$a[1] = &$a[0];
$a[2] = &$a[0];
check($a);

echo "Done";
?>
--EXPECTF--


--- No references:
array(3) {
  [0]=>
  int(1)
  [1]=>
  int(1)
  [2]=>
  int(1)
}
string(8) "93010101"
array(3) {
  [0]=>
  int(1)
  [1]=>
  int(1)
  [2]=>
  int(1)
}
array(3) {
  [0]=>
  string(10) "b0.changed"
  [1]=>
  int(1)
  [2]=>
  int(1)
}
array(3) {
  [0]=>
  string(10) "b0.changed"
  [1]=>
  string(10) "b1.changed"
  [2]=>
  int(1)
}
array(3) {
  [0]=>
  string(10) "b0.changed"
  [1]=>
  string(10) "b1.changed"
  [2]=>
  string(10) "b2.changed"
}


--- 0 refs 1:
array(3) {
  [1]=>
  &int(1)
  [0]=>
  &int(1)
  [2]=>
  int(1)
}
string(8) "93010101"
array(3) {
  [0]=>
  int(1)
  [1]=>
  int(1)
  [2]=>
  int(1)
}
array(3) {
  [0]=>
  string(10) "b0.changed"
  [1]=>
  int(1)
  [2]=>
  int(1)
}
array(3) {
  [0]=>
  string(10) "b0.changed"
  [1]=>
  string(10) "b1.changed"
  [2]=>
  int(1)
}
array(3) {
  [0]=>
  string(10) "b0.changed"
  [1]=>
  string(10) "b1.changed"
  [2]=>
  string(10) "b2.changed"
}


--- 0 refs 2:
array(3) {
  [2]=>
  &int(1)
  [0]=>
  &int(1)
  [1]=>
  int(1)
}
string(8) "93010101"
array(3) {
  [0]=>
  int(1)
  [1]=>
  int(1)
  [2]=>
  int(1)
}
array(3) {
  [0]=>
  string(10) "b0.changed"
  [1]=>
  int(1)
  [2]=>
  int(1)
}
array(3) {
  [0]=>
  string(10) "b0.changed"
  [1]=>
  string(10) "b1.changed"
  [2]=>
  int(1)
}
array(3) {
  [0]=>
  string(10) "b0.changed"
  [1]=>
  string(10) "b1.changed"
  [2]=>
  string(10) "b2.changed"
}


--- 1 refs 0:
array(3) {
  [0]=>
  &int(1)
  [1]=>
  &int(1)
  [2]=>
  int(1)
}
string(8) "93010101"
array(3) {
  [0]=>
  int(1)
  [1]=>
  int(1)
  [2]=>
  int(1)
}
array(3) {
  [0]=>
  string(10) "b0.changed"
  [1]=>
  int(1)
  [2]=>
  int(1)
}
array(3) {
  [0]=>
  string(10) "b0.changed"
  [1]=>
  string(10) "b1.changed"
  [2]=>
  int(1)
}
array(3) {
  [0]=>
  string(10) "b0.changed"
  [1]=>
  string(10) "b1.changed"
  [2]=>
  string(10) "b2.changed"
}


--- 1 refs 2:
array(3) {
  [0]=>
  int(1)
  [2]=>
  &int(1)
  [1]=>
  &int(1)
}
string(8) "93010101"
array(3) {
  [0]=>
  int(1)
  [1]=>
  int(1)
  [2]=>
  int(1)
}
array(3) {
  [0]=>
  string(10) "b0.changed"
  [1]=>
  int(1)
  [2]=>
  int(1)
}
array(3) {
  [0]=>
  string(10) "b0.changed"
  [1]=>
  string(10) "b1.changed"
  [2]=>
  int(1)
}
array(3) {
  [0]=>
  string(10) "b0.changed"
  [1]=>
  string(10) "b1.changed"
  [2]=>
  string(10) "b2.changed"
}


--- 2 refs 0:
array(3) {
  [0]=>
  &int(1)
  [1]=>
  int(1)
  [2]=>
  &int(1)
}
string(8) "93010101"
array(3) {
  [0]=>
  int(1)
  [1]=>
  int(1)
  [2]=>
  int(1)
}
array(3) {
  [0]=>
  string(10) "b0.changed"
  [1]=>
  int(1)
  [2]=>
  int(1)
}
array(3) {
  [0]=>
  string(10) "b0.changed"
  [1]=>
  string(10) "b1.changed"
  [2]=>
  int(1)
}
array(3) {
  [0]=>
  string(10) "b0.changed"
  [1]=>
  string(10) "b1.changed"
  [2]=>
  string(10) "b2.changed"
}


--- 2 refs 1:
array(3) {
  [0]=>
  int(1)
  [1]=>
  &int(1)
  [2]=>
  &int(1)
}
string(8) "93010101"
array(3) {
  [0]=>
  int(1)
  [1]=>
  int(1)
  [2]=>
  int(1)
}
array(3) {
  [0]=>
  string(10) "b0.changed"
  [1]=>
  int(1)
  [2]=>
  int(1)
}
array(3) {
  [0]=>
  string(10) "b0.changed"
  [1]=>
  string(10) "b1.changed"
  [2]=>
  int(1)
}
array(3) {
  [0]=>
  string(10) "b0.changed"
  [1]=>
  string(10) "b1.changed"
  [2]=>
  string(10) "b2.changed"
}


--- 0,1 ref 2:
array(3) {
  [2]=>
  &int(1)
  [0]=>
  &int(1)
  [1]=>
  &int(1)
}
string(8) "93010101"
array(3) {
  [0]=>
  int(1)
  [1]=>
  int(1)
  [2]=>
  int(1)
}
array(3) {
  [0]=>
  string(10) "b0.changed"
  [1]=>
  int(1)
  [2]=>
  int(1)
}
array(3) {
  [0]=>
  string(10) "b0.changed"
  [1]=>
  string(10) "b1.changed"
  [2]=>
  int(1)
}
array(3) {
  [0]=>
  string(10) "b0.changed"
  [1]=>
  string(10) "b1.changed"
  [2]=>
  string(10) "b2.changed"
}


--- 0,2 ref 1:
array(3) {
  [1]=>
  &int(1)
  [0]=>
  &int(1)
  [2]=>
  &int(1)
}
string(8) "93010101"
array(3) {
  [0]=>
  int(1)
  [1]=>
  int(1)
  [2]=>
  int(1)
}
array(3) {
  [0]=>
  string(10) "b0.changed"
  [1]=>
  int(1)
  [2]=>
  int(1)
}
array(3) {
  [0]=>
  string(10) "b0.changed"
  [1]=>
  string(10) "b1.changed"
  [2]=>
  int(1)
}
array(3) {
  [0]=>
  string(10) "b0.changed"
  [1]=>
  string(10) "b1.changed"
  [2]=>
  string(10) "b2.changed"
}


--- 1,2 ref 0:
array(3) {
  [0]=>
  &int(1)
  [1]=>
  &int(1)
  [2]=>
  &int(1)
}
string(8) "93010101"
array(3) {
  [0]=>
  int(1)
  [1]=>
  int(1)
  [2]=>
  int(1)
}
array(3) {
  [0]=>
  string(10) "b0.changed"
  [1]=>
  int(1)
  [2]=>
  int(1)
}
array(3) {
  [0]=>
  string(10) "b0.changed"
  [1]=>
  string(10) "b1.changed"
  [2]=>
  int(1)
}
array(3) {
  [0]=>
  string(10) "b0.changed"
  [1]=>
  string(10) "b1.changed"
  [2]=>
  string(10) "b2.changed"
}
Done
