<?php

declare(strict_types=1);

namespace App\Console\Commands\Make;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use function Laravel\Prompts\select;

#[AsCommand(name: 'make:use-case')]
final class MakeUseCaseCommand extends GeneratorCommand
{
    protected $name = 'make:use-case';

    protected $description = 'Scaffold a CQRS use-case: Command or Query DTO + Handler';

    protected $type = 'UseCase';

    public function handle(): ?bool
    {
        $domain    = (string) $this->argument('domain');
        $name      = (string) $this->argument('name');
        $isCommand = (bool) $this->option('command');
        $isQuery   = (bool) $this->option('query');

        if (! $isCommand && ! $isQuery) {
            $this->components->error('Please specify --command or --query.');

            return false;
        }

        if ($isCommand) {
            $this->createPair($domain, $name, 'Commands', 'command', 'Command');
        }

        if ($isQuery) {
            $this->createPair($domain, $name, 'Queries', 'query', 'Query');
        }

        $type = $isCommand ? 'Command' : 'Query';
        $this->components->info(sprintf('Use-case [%s/%s%s] scaffolded successfully.', $domain, $name, $type));

        return null;
    }

    protected function getStub(): string
    {
        return $this->option('query')
            ? base_path('stubs/domain/query.stub')
            : base_path('stubs/domain/command.stub');
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        $domain = (string) $this->argument('domain');
        $name   = (string) $this->argument('name');
        $folder = $this->option('query') ? 'Queries' : 'Commands';

        return $rootNamespace.'\\Application\\Features\\'.$domain.'\\'.$folder.'\\'.$name;
    }

    /**
     * @return array<array<mixed>>
     */
    protected function getArguments(): array
    {
        return [
            ['domain', InputArgument::REQUIRED, 'The domain name (e.g. Blog)'],
            ['name',   InputArgument::REQUIRED, 'The use-case name (e.g. CreatePost)'],
        ];
    }

    protected function afterPromptingForMissingArguments(InputInterface $input, OutputInterface $output): void
    {
        if ($this->option('command') || $this->option('query')) {
            return;
        }

        $type = select(
            label: 'What type of use-case?',
            options: ['command' => 'Command + CommandHandler', 'query' => 'Query + QueryHandler'],
        );

        $input->setOption($type, true);
    }

    /**
     * @return array<array<mixed>>
     */
    protected function getOptions(): array
    {
        return [
            ['command', null, InputOption::VALUE_NONE, 'Generate a Command + CommandHandler'],
            ['query',   null, InputOption::VALUE_NONE, 'Generate a Query + QueryHandler'],
            ['force',   'f',  InputOption::VALUE_NONE, 'Overwrite existing files.'],
        ];
    }

    private function createPair(string $domain, string $name, string $folder, string $stubPrefix, string $suffix): void
    {
        $dtoClass     = $name.$suffix;
        $handlerClass = sprintf('%s%sHandler', $name, $suffix);
        $namespace    = sprintf('App\\Application\\Features\\%s\\%s\\%s', $domain, $folder, $name);

        $dtoContent = $this->buildClassFromStub(
            base_path(sprintf('stubs/domain/%s.stub', $stubPrefix)),
            ['{{ namespace }}' => $namespace, '{{ class }}' => $dtoClass]
        );

        $handlerContent = $this->buildClassFromStub(
            base_path(sprintf('stubs/domain/%s-handler.stub', $stubPrefix)),
            ['{{ namespace }}' => $namespace, '{{ class }}' => $handlerClass, sprintf('{{ %s }}', $stubPrefix) => $dtoClass]
        );

        $this->writeFile(
            app_path(sprintf('Application/Features/%s/%s/%s/%s.php', $domain, $folder, $name, $dtoClass)),
            $dtoContent,
            $dtoClass
        );

        $this->writeFile(
            app_path(sprintf('Application/Features/%s/%s/%s/%s.php', $domain, $folder, $name, $handlerClass)),
            $handlerContent,
            $handlerClass
        );
    }

    /**
     * @param array<string, string> $replacements
     */
    private function buildClassFromStub(string $stubPath, array $replacements): string
    {
        $stub = $this->files->get($stubPath);

        return str_replace(array_keys($replacements), array_values($replacements), $stub);
    }

    private function writeFile(string $path, string $content, string $label): void
    {
        $this->makeDirectory($path);

        if ($this->files->exists($path) && ! $this->option('force')) {
            $this->components->warn(sprintf('[%s] already exists. Skipping.', $label));

            return;
        }

        $this->files->put($path, $content);
        $this->components->info(sprintf('Created [%s]', $label));
    }
}
