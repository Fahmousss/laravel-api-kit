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

        $this->registerUseCaseInServiceProvider($domain, $name, $dtoClass, $handlerClass, $suffix);
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

        $this->files->put($path, $content);
        $this->components->info(sprintf('Created [%s]', $label));
    }

    private function registerUseCaseInServiceProvider(string $domain, string $name, string $dtoClass, string $handlerClass, string $type): void
    {
        $providerPath = app_path(sprintf('Infrastructure/%s/Providers/%sServiceProvider.php', $domain, $domain));

        if (! $this->files->exists($providerPath)) {
            $this->components->warn(sprintf('Service Provider not found at [%s]. Cannot automatically register use-case.', $providerPath));

            return;
        }

        $content = $this->files->get($providerPath);
        $folder  = $type === 'Command' ? 'Commands' : 'Queries';

        $baseNamespace = sprintf('App\\Application\\Features\\%s\\%s\\%s\\', $domain, $folder, $name);
        $useDto        = 'use '.$baseNamespace.$dtoClass.';';
        $useHandler    = 'use '.$baseNamespace.$handlerClass.';';

        // 1. Add Use Statements
        if (! str_contains($content, $useDto)) {
            // Find the last use statement block
            $pattern = '/^use\s+[^;]+;/m';
            if (preg_match_all($pattern, $content, $matches, PREG_OFFSET_CAPTURE)) {
                $lastMatch = end($matches[0]);
                $insertPos = $lastMatch[1] + mb_strlen($lastMatch[0]);

                $insertion = "\n".$useDto."\n".$useHandler;
                $content   = substr_replace($content, $insertion, $insertPos, 0);
            } else {
                // Fallback to inserting after the namespace declaration
                $namespacePattern = '/^namespace\s+.*Providers;$/m';
                if (preg_match($namespacePattern, $content, $matches, PREG_OFFSET_CAPTURE)) {
                    $insertPos = $matches[0][1] + mb_strlen($matches[0][0]);
                    $insertion = "\n\n".$useDto."\n".$useHandler;
                    $content   = substr_replace($content, $insertion, $insertPos, 0);
                }
            }
        }

        // 2. Add Registration Line
        $busVar       = '$bus';
        $registerLine = sprintf(
            '            %s->register(%s::class, %s::class);',
            $busVar,
            $dtoClass,
            $handlerClass
        );

        if (! str_contains($content, $registerLine)) {
            $busInterface = $type === 'Command' ? 'CommandBusInterface' : 'QueryBusInterface';
            $pattern      = '/\$this->app->singleton\(function \(\): '.$busInterface.' \{.*?return \$bus;/s';

            if (preg_match($pattern, $content, $matches, PREG_OFFSET_CAPTURE)) {
                $block       = $matches[0][0];
                $blockOffset = $matches[0][1];

                $returnPos = mb_strpos($block, 'return $bus;');
                if ($returnPos !== false) {
                    $insertPos = $blockOffset + $returnPos;
                    $content   = substr_replace($content, $registerLine."\n\n", $insertPos, 0);
                }
            }
        }

        $this->files->put($providerPath, $content);
        $this->components->info(sprintf('Registered [%s] in [%sServiceProvider]', $dtoClass, $domain));
    }
}
