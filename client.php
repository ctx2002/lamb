<?php
require_once "TokenizerNormal.php";

$tokenizer = new TokenizerNormal();
$t = $tokenizer->tokenize('( \func.\arg.(left right) \x.x) \'anru,p\'');
var_dump($t);