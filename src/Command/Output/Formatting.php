<?php declare(strict_types=1);

namespace SupportPal\LanguageTools\Command\Output;

use function sprintf;

trait Formatting
{
    protected function info(string $string): void
    {
        $this->line($string, 'info');
    }

    protected function error(string $string): void
    {
        $this->line($string, 'error');
    }

    protected function line(string $string, ?string $style = null): void
    {
        $styled = $style ? sprintf('<%s>%s</%s>', $style, $string, $style) : $string;

        $this->output->writeln($styled);
    }
}
