<?php declare(strict_types=1);

namespace SupportPal\LanguageTools\Command;

use SupportPal\LanguageTools\IO\Compare\CompareDirectory;
use Symfony\Component\Console\Input\InputArgument;

use function count;
use function sprintf;

use const PHP_EOL;

class CompareCommand extends Command
{
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

    public function handle(): int
    {
        $dir1 = $this->singleArg('dir1');
        $dir2 = $this->singleArg('dir2');

        $this->info(sprintf('# Comparing %s against %s' . PHP_EOL, $dir1, $dir2));

        $comparison = new CompareDirectory($dir1, $dir2);
        $differences = $comparison->diff();
        foreach ($differences as $filename => $diff) {
            $this->error(sprintf('Found differences when comparing %s ...', $filename));
            $this->error($diff);
        }

        return (int) (count($differences) > 0);
    }
}
