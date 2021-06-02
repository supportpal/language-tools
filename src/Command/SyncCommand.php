<?php declare(strict_types=1);

namespace SupportPal\LanguageTools\Command;

use SupportPal\LanguageTools\IO\Sync\SyncDirectory;
use Symfony\Component\Console\Input\InputArgument;

use function sprintf;

use const PHP_EOL;

class SyncCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'sync';

    /**
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName(self::$defaultName)
            ->setDefinition([
                new InputArgument('dir1', InputArgument::REQUIRED, 'Path to directory containing the base language files.'),
                new InputArgument('dir2', InputArgument::REQUIRED, 'Path to directory containing the language files you want to synchronise.'),
            ])
            ->setDescription('Synchronise language files in two directories.')
            ->setHelp(<<<EOF
The <info>%command.name%</info> synchronises translation files with the English version:
<info>php %command.full_name% resources/lang/en/ resources/lang/es/</info>
EOF
            );
    }

    public function handle(): int
    {
        $dir1 = $this->singleArg('dir1');
        $dir2 = $this->singleArg('dir2');

        $this->info(sprintf('# Synchronising %s with %s ...' . PHP_EOL, $dir2, $dir1));

        (new SyncDirectory($dir1, $dir2))->sync();

        return 0;
    }
}
