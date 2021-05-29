<?php declare(strict_types=1);

namespace SupportPal\LanguageTools\Command;

use InvalidArgumentException;
use SupportPal\LanguageTools\CompareDirectory;
use SupportPal\LanguageTools\Output\Formatting;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function count;
use function is_string;
use function sprintf;

use const PHP_EOL;

class CompareCommand extends Command
{
    use Formatting;

    /** @var string */
    protected static $defaultName = 'compare';

    /**
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName(self::$defaultName)
            ->setDefinition([
                new InputArgument('dir1', InputArgument::REQUIRED, 'Path to directory containing the base language files.'),
                new InputArgument('dir2', InputArgument::REQUIRED, 'Path to directory containing the language files you want to compare against.'),
            ])
            ->setDescription('Compares language files in two directories.')
            ->setHelp(<<<EOF
The <info>%command.name%</info> compares language files and displays the differences:
<info>php %command.full_name% resources/lang/en/ resources/lang/es/</info>
EOF
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $dir1 = $this->singleArg($input, 'dir1');
        $dir2 = $this->singleArg($input, 'dir2');

        $this->info($output, sprintf('# Comparing %s against %s' . PHP_EOL, $dir1, $dir2));

        $comparison = new CompareDirectory($dir1, $dir2);
        $differences = $comparison->diff();
        foreach ($differences as $filename => $diff) {
            $this->error($output, sprintf('Found differences when comparing %s ...', $filename));
            $this->error($output, $diff);
        }

        return (int) (count($differences) > 0);
    }

    private function singleArg(InputInterface $input, string $name): string
    {
        $value = $input->getArgument($name);
        if (! is_string($value)) {
            throw new InvalidArgumentException(sprintf('Argument %s should be a string.', $name));
        }

        return $value;
    }
}
