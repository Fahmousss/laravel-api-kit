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

#[AsCommand(name: 'make:data')]
final class MakeDtoCommand extends GeneratorCommand
{
    protected $name = 'make:data';

    protected $description = 'Scaffold a typed DTO in the Application layer';

    protected $type = 'Data';

    public function handle(): ?bool
    {
        $domain    = (string) $this->argument('domain');
        $name      = (string) $this->argument('name');
        $class     = Str::studly($name).'DTO';
        $namespace = sprintf('App\\Application\\%s\\DTOs', $domain);
        $path      = app_path(sprintf('Application/%s/DTOs/%s.php', $domain, $class));

        $this->makeDirectory($path);

        if ($this->files->exists($path) && ! $this->option('force')) {
            $this->components->warn(sprintf('DTO [%s] already exists.', $class));

            return false;
        }

        $stub    = $this->files->get($this->getStub());
        $content = str_replace(['{{ namespace }}', '{{ class }}'], [$namespace, $class], $stub);

        $this->files->put($path, $content);
        $this->components->info(sprintf('DTO [%s] created at Application/%s/DTOs/', $class, $domain));

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

        if (! $input->getArgument('name')) {
            $input->setArgument('name', text(
                label: 'What is the DTO name?',
                placeholder: 'e.g. Post',
                required: true
            ));
        }
    }

    protected function getStub(): string
    {
        return base_path('stubs/domain/dto.stub');
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\\Application\\'.$this->argument('domain').'\\DTOs';
    }

    /**
     * @return array<array<mixed>>
     */
    protected function getArguments(): array
    {
        return [
            ['domain', InputArgument::REQUIRED, 'The domain name (e.g. Blog)'],
            ['name',   InputArgument::REQUIRED, 'The DTO name without "DTO" suffix (e.g. Post)'],
        ];
    }

    /**
     * @return array<array<mixed>>
     */
    protected function getOptions(): array
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Overwrite existing DTO.'],
        ];
    }
}
