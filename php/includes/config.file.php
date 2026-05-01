<?php

class File
{
    public $uploadsDir;
    public $uploadsMarkdownDir;
    public $uploadsNewsLetterDir;

    public function __construct()
    {
        $this->uploadsDir = realpath(__DIR__ . '/../uploads');
        $this->uploadsMarkdownDir = realpath(__DIR__ . '/../uploads/markdown');
        $this->uploadsNewsLetterDir = realpath(__DIR__ . '/../uploads/newsletter');
    }

}