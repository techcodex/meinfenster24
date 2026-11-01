<?php

declare(strict_types=1);

namespace App\View;

final class Renderer
{
    public function __construct(private readonly string $templateDir)
    {
    }

    /** @param array<string, mixed> $vars */
    public function render(string $template, array $vars = []): string
    {
        $file = $this->templateDir . '/' . $template . '.php';
        $render = function (string $__file, array $__vars): string {
            extract($__vars, EXTR_SKIP);
            ob_start();
            require $__file;

            return (string) ob_get_clean();
        };

        return $render($file, $vars);
    }

    public static function e(?string $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
