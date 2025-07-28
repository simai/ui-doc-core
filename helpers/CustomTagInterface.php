<?php
namespace App\Helpers;

interface CustomTagInterface
{
    public function getTemplate(string $template): string;

    public function getPattern(): string;


}
