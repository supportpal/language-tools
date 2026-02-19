<?php declare(strict_types=1);

namespace SupportPal\LanguageTools\Command;

use SupportPal\LanguageTools\IO\Untranslated\Directory;
use Symfony\Component\Console\Input\InputArgument;

use function count;
use function sprintf;
use function vsprintf;

use const PHP_EOL;

class UntranslatedCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'untranslated';

    /**
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName(self::$defaultName)
            ->setDefinition([
                new InputArgument('dir1', InputArgument::REQUIRED, 'Path to English translation files.'),
                new InputArgument('dir2', InputArgument::REQUIRED, 'Path to directory which needs translations updating.'),
            ])
            ->setDescription('Find language strings which need translating.')
            ->setHelp(<<<EOF
The <info>%command.name%</info> finds language strings which need translating:
<info>php %command.full_name% resources/lang/en/ resources/lang/es/</info>
EOF
            );
    }

    public function handle(): int
    {
        $dir1 = $this->singleArg('dir1');
        $dir2 = $this->singleArg('dir2');

        $this->info(sprintf('# Searching for untranslated language strings in %s' . PHP_EOL, $dir2));

        $instance = new Directory($dir1, $dir2);
        $files = $instance->all();
        foreach ($instance->all() as $filename => $statistics) {
            $this->info(vsprintf('# %d out of %d translations (%d%%) need translating in %s ...', [
                $totalUntranslated = $statistics->totalUntranslated(),
                $totalStrings = $statistics->totalStrings(),
                $totalUntranslated / $totalStrings * 100,
                $filename
            ]));

            foreach ($statistics->untranslated() as $string) {
                $this->info((string) $string);
            }

            $this->output->writeln('');
        }

        return (int) (count($files) > 0);
    }
}
