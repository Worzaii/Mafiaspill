<?php

namespace Pages;

/**
 * Making a template for all pages
 * Not ready yet.
 */
abstract class Template
{
    abstract public function view();
    private ?string $output;

    public function __construct()
    {
        $this->output = '';
    }

    public function render()
    {
        echo $this->output;
    }
}
