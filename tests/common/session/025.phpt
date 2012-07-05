--TEST--
custom save handler, multiple session_start()s, complex data structure test.
--INI--
session.use_cookies=0
session.cache_limiter=
session.name=PHPSESSID
session.serialize_handler=msgpacki
--FILE--
<?php
if (!extension_loaded('msgpacki')) {
    dl('msgpacki.' . PHP_SHLIB_SUFFIX);
}

error_reporting(E_ALL);

class handler {
    public $data;

    function open($save_path, $session_name)
    {
        print "OPEN: $session_name\n";
        return true;
    }
    function close()
    {
        print "CLOSE\n";
        return true;
    }
    function read($key)
    {
        print "READ: $key\n";
        return $GLOBALS["hnd"]->data;
    }

    function write($key, $val)
    {
        echo "WRITE: $key, ", bin2hex($val), "\n";
        $GLOBALS["hnd"]->data = $val;
        return true;
    }

    function destroy($key)
    {
        print "DESTROY: $key\n";
        return true;
    }

    function gc() { return true; }

    function __construct()
    {
        $this->data = pack('H*', '82a362617a83c0a3666f6fa3626172a26f6ba379657301a3617272810383c0a3666f6fa3626172a26f6ba379657301');
    }
}

$hnd = new handler;

class foo {
    public $bar = "ok";
    function method() { $this->yes++; }
}

session_set_save_handler(array($hnd, "open"), array($hnd, "close"), array($hnd, "read"), array($hnd, "write"), array($hnd, "destroy"), array($hnd, "gc"));

session_id("abtest");
session_start();
$baz = $_SESSION['baz'];
$arr = $_SESSION['arr'];
$baz->method();
$arr[3]->method();

var_dump($baz);
var_dump($arr);

session_write_close();

session_set_save_handler(array($hnd, "open"), array($hnd, "close"), array($hnd, "read"), array($hnd, "write"), array($hnd, "destroy"), array($hnd, "gc"));
session_start();
$baz = $_SESSION['baz'];
$arr = $_SESSION['arr'];


$baz->method();
$arr[3]->method();


$c = 123;
$_SESSION['c'] = $c;
var_dump($baz); var_dump($arr); var_dump($c);

session_write_close();

session_set_save_handler(array($hnd, "open"), array($hnd, "close"), array($hnd, "read"), array($hnd, "write"), array($hnd, "destroy"), array($hnd, "gc"));
session_start();
var_dump($baz); var_dump($arr); var_dump($c);

session_destroy();
?>
--EXPECTF--
OPEN: PHPSESSID
READ: abtest
object(foo)#%d (2) {
  ["bar"]=>
  string(2) "ok"
  ["yes"]=>
  int(2)
}
array(1) {
  [3]=>
  object(foo)#%d (2) {
    ["bar"]=>
    string(2) "ok"
    ["yes"]=>
    int(2)
  }
}
WRITE: abtest, 82a362617a83c0a3666f6fa3626172a26f6ba379657302a3617272810383c0a3666f6fa3626172a26f6ba379657302
CLOSE
OPEN: PHPSESSID
READ: abtest
object(foo)#%d (2) {
  ["bar"]=>
  string(2) "ok"
  ["yes"]=>
  int(3)
}
array(1) {
  [3]=>
  object(foo)#%d (2) {
    ["bar"]=>
    string(2) "ok"
    ["yes"]=>
    int(3)
  }
}
int(123)
WRITE: abtest, 83a362617a83c0a3666f6fa3626172a26f6ba379657303a3617272810383c0a3666f6fa3626172a26f6ba379657303a1637b
CLOSE
OPEN: PHPSESSID
READ: abtest
object(foo)#%d (2) {
  ["bar"]=>
  string(2) "ok"
  ["yes"]=>
  int(3)
}
array(1) {
  [3]=>
  object(foo)#%d (2) {
    ["bar"]=>
    string(2) "ok"
    ["yes"]=>
    int(3)
  }
}
int(123)
DESTROY: abtest
CLOSE
