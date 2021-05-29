<?php declare(strict_types=1);

namespace SupportPal\LanguageTools\Output;

use Symfony\Component\Console\Output\OutputInterface;

use function sprintf;

trait Formatting
{
    private function info(OutputInterface $output, string $string): void
    {
        $this->line($output, $string, 'info');
    }

    private function error(OutputInterface $output, string $string): void
    {
        $this->line($output, $string, 'error');
    }

    private function line(OutputInterface $output, string $string, ?string $style = null): void
    {
        $styled = $style ? sprintf('<%s>%s</%s>', $style, $string, $style) : $string;

        $output->writeln($styled);
    }
}
