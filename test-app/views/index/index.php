<?php

/**
 * @var \Colibri\Template\Core\Compiler $this
 * @var string $content
 */

$this->layout('layout', [
    'title' => 'Hello from: ' . __FILE__
]);

?>
<style>
    .active, .active * {
        font-weight: bold;
        color: black;
    }
</style>
<div style="border: 3px dotted coral;">
    <code><?=__FILE__?></code>
    <a href="<?= $url->create( 'index:index' ) ?>">hello</a>
    <hr>
    hello
</div>