<?php

declare(strict_types=1);

namespace App\Console\Commands\Make;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(name: 'make:domain')]
final class MakeDomainCommand extends GeneratorCommand
{
    protected $name = 'make:domain';

    protected $description = 'Scaffold a new domain: Entity, RepositoryInterface, and domain Exception';

    protected $type = 'Domain';

    public function handle(): ?bool
    {
        $domain = (string) $this->argument('domain');
        $entity = (string) $this->argument('entity');

        $this->createEntity($domain, $entity);
        $this->createRepositoryInterface($domain, $entity);
        $this->createException($domain, $entity);

        $this->components->info(sprintf('Domain [%s/%s] scaffolded successfully.', $domain, $entity));

        return null;
    }

    protected function getStub(): string
    {
        return base_path('stubs/domain/entity.stub');
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\\Domain\\'.$this->argument('domain').'\\Entities';
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
            ['force', 'f', InputOption::VALUE_NONE, 'Overwrite existing files.'],
        ];
    }

    private function createEntity(string $domain, string $entity): void
    {
        $entity = $entity.'Entity';
        $path      = app_path(sprintf('Domain/%s/Entities/%s.php', $domain, $entity));
        $namespace = sprintf('App\\Domain\\%s\\Entities', $domain);

        $content = $this->buildClassFromStub(
            base_path('stubs/domain/entity.stub'),
            ['{{ namespace }}' => $namespace, '{{ class }}' => $entity]
        );

        $this->writeFile($path, $content, sprintf('Domain/Entity [%s]', $entity));
    }

    private function createRepositoryInterface(string $domain, string $entity): void
    {
        $class     = $entity.'RepositoryInterface';
        $path      = app_path(sprintf('Domain/%s/Repositories/%s.php', $domain, $class));
        $namespace = sprintf('App\\Domain\\%s\\Repositories', $domain);

        $content = $this->buildClassFromStub(
            base_path('stubs/domain/repository-interface.stub'),
            ['{{ namespace }}' => $namespace, '{{ class }}' => $class, '{{ domain }}' => $domain, '{{ entity }}' => $entity]
        );

        $this->writeFile($path, $content, sprintf('Domain/RepositoryInterface [%s]', $class));
    }

    private function createException(string $domain, string $entity): void
    {
        $class     = $entity.'NotFoundException';
        $path      = app_path(sprintf('Domain/%s/Exceptions/%s.php', $domain, $class));
        $namespace = sprintf('App\\Domain\\%s\\Exceptions', $domain);

        $content = <<<PHP
        <?php

        declare(strict_types=1);

        namespace {$namespace};

        use RuntimeException;

        final class {$class} extends RuntimeException
        {
            public function __construct(string \$identifier = '')
            {
                parent::__construct(
                    \$identifier !== ''
                        ? "{$entity} not found: {\$identifier}"
                        : '{$entity} not found.'
                );
            }
        }
        PHP;

        $this->writeFile($path, $content, sprintf('Domain/Exception [%s]', $class));
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
            $this->components->warn($label.' already exists. Skipping.');

            return;
        }

        $this->files->put($path, $content);
        $this->components->info('Created '.$label);
    }
}
