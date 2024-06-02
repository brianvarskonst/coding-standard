<?php

$var === null;
$var !== null;
$var->field === null;
$var->field !== null;
($var->field + 64) === null;
$var === null;
\NamespaceName\ClassName::method($parameter1, $parameter2) === null;
$this->field($var + 45 / 7) === null;
($a ? $b : $c) === null;
($a ? $b : ($c)) === null;
($a ? $b : some_function($c)) === null;
($a ? $b : some_function($c)) !== null;

if ($this->callSomeSuperLongFunctionToBreakTheLine() === null) {
    return;
}
