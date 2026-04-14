<?php

require_once __DIR__ . '/../vendor/autoload.php';
class Markdown
{
    private $parser;

    public function __construct()
    {
        $this->parser = new Parsedown();
        $this->parser->setSafeMode(true);
    }

    public function parse(string $text): string
    {
        return $this->parser->text($text);
    }
}