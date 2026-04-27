<?php

declare(strict_types=1);

namespace App\Console\Commands\Make;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function Laravel\Prompts\text;

#[AsCommand(name: 'make:repository')]
final class MakeRepositoryCommand extends GeneratorCommand
{
    protected $name = 'make:repository';

    protected $description = 'Scaffold an Eloquent repository implementation in the Infrastructure layer';

    protected $type = 'Repository';

    public function handle(): ?bool
    {
        $domain    = (string) $this->argument('domain');
        $entity    = (string) $this->argument('entity');
        $class     = sprintf('Eloquent%sRepository', $entity);
        $namespace = sprintf('App\\Infrastructure\\%s\\Persistence', $domain);
        $path      = app_path(sprintf('Infrastructure/%s/Persistence/%s.php', $domain, $class));

        $this->makeDirectory($path);

        if ($this->files->exists($path) && ! $this->option('force')) {
            $this->components->warn(sprintf('Repository [%s] already exists.', $class));

            return false;
        }

        $stub    = $this->files->get($this->getStub());
        $content = str_replace(
            ['{{ namespace }}', '{{ class }}', '{{ domain }}', '{{ entity }}', '{{ idType }}', '{{ entityVar }}'],
            [$namespace, $class, $domain, $entity, $this->option('uuid') ? 'string' : 'int', Str::camel($entity)],
            $stub
        );

        $this->files->put($path, $content);
        $this->components->info(sprintf('Repository [%s] created at Infrastructure/%s/Persistence/', $class, $domain));

        return null;
    }

    protected function promptForMissingArguments(InputInterface $input, OutputInterface $output): void
    {
        if (! $input->getArgument('domain')) {
            $input->setArgument('domain', text(
                label: 'What is the domain name?',
                placeholder: 'e.g. Blog',
                required: true
            ));
        }

        if (! $input->getArgument('entity')) {
            $input->setArgument('entity', text(
                label: 'What is the entity name?',
                placeholder: 'e.g. Post',
                required: true
            ));
        }
    }

    protected function getStub(): string
    {
        return base_path('stubs/domain/eloquent-repository.stub');
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\\Infrastructure\\'.$this->argument('domain').'\\Persistence';
    }

    /**
     * @return array<array<mixed>>
     */
    protected function getArguments(): array
    {
        return [
            ['domain', InputArgument::REQUIRED, 'The domain name (e.g. Blog)'],
            ['entity', InputArgument::REQUIRED, 'The entity name (e.g. Post)'],
        ];
    }

    /**
     * @return array<array<mixed>>
     */
    protected function getOptions(): array
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Overwrite existing Repository.'],
            ['uuid', null, InputOption::VALUE_NONE, 'Use string UUIDs instead of int increments for IDs.'],
        ];
    }
}
