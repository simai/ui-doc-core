<?php
namespace App\Helpers\Interface;

interface CustomTagInterface
{
    /**
     * Регулярное выражение для поиска кастомного блока
     */
    public function getPattern(): string;

    /**
     * Шаблон, в который будет обёрнуто содержимое блока
     */
    public function getTemplate(string $innerHtml): string;

    /**
     * Имя или тип тега (используется в AST)
     */
    public function getType(): string;

    /**
     * Открывающий шаблон (для блоков)
     */
    public function getOpeningPattern(): ?string;

    /**
     * Закрывающий шаблон (для блоков)
     */
    public function getClosingPattern(): ?string;
}

