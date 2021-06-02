<?php declare(strict_types=1);

namespace SupportPal\LanguageTools\Command;

use InvalidArgumentException;
use SupportPal\LanguageTools\Command\Output\Formatting;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function is_string;
use function sprintf;

abstract class Command extends \Symfony\Component\Console\Command\Command
{
    use Formatting;

    /** @var InputInterface */
    protected $input;

    /** @var OutputInterface */
    protected $output;

    abstract public function handle(): int;

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        return $this->handle();
    }

    protected function singleArg(string $name): string
    {
        $value = $this->input->getArgument($name);
        if (! is_string($value)) {
            throw new InvalidArgumentException(sprintf('Argument %s should be a string.', $name));
        }

        return $value;
    }
}
