<?php
namespace App\Helpers\Interface;

interface CustomTagInterface
{
    /**
     * @return string
     */
    public function type(): string;

    /**
     * @return string
     */
    public function openRegex(): string;

    /**
     * @return string
     */
    public function closeRegex(): string;

    /**
     * @return string
     */
    public function htmlTag(): string;

    /**
     * @return array
     */
    public function baseAttrs(): array;

    /**
     * @return bool
     */
    public function allowNestingSame(): bool;

    /**
     * @return callable|null
     */
    public function attrsFilter(): ?callable;

    /**
     * @return callable|null
     */
    public function renderer(): ?callable;
}



