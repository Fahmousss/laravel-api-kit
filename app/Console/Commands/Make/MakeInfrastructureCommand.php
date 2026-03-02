<?php

declare(strict_types=1);

namespace App\Console\Commands\Make;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(name: 'make:infrastructure')]
class MakeInfrastructureCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:infrastructure';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scaffold an Infrastructure layer for an Entity (Model, Migration, Factory, Repository, Provider Binding)';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Infrastructure';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $domain = (string) $this->argument('domain');
        $entity = (string) $this->argument('entity');
        $force = (bool) $this->option('force');

        // 1. Call make:model -m -f
        $this->info("Scaffolding Model, Migration, and Factory...");
        $this->call('make:model', [
            'name' => "{$domain}/{$entity}",
            '--migration' => true,
            '--factory' => true,
        ]);

        // 2. Call make:repository
        $this->info("Scaffolding Eloquent Repository...");
        $this->call('make:repository', array_filter([
            'domain' => $domain,
            'entity' => $entity,
            '--force' => $force,
        ]));

        // 3. Manage the Service Provider
        $this->info("Configuring Service Provider bindings...");
        $this->createOrUpdateProvider($domain, $entity);

        $this->info("Infrastructure for [{$domain}/{$entity}] scaffolded successfully.");
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return '';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['domain', InputArgument::REQUIRED, 'The domain name (e.g. Blog)'],
            ['entity', InputArgument::REQUIRED, 'The entity name (e.g. Post)'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Overwrite existing files'],
        ];
    }

    private function createOrUpdateProvider(string $domain, string $entity): void
    {
        $providerName = "{$domain}ServiceProvider";
        $providerPath = app_path("Infrastructure/{$domain}/Providers/{$providerName}.php");

        if (! file_exists($providerPath)) {
            $this->createProviderFile($domain, $providerName, $providerPath);
            $this->registerProviderInBootstrap($domain, $providerName);
        }

        $this->addRepositoryBinding($domain, $entity, $providerPath);
    }

    private function createProviderFile(string $domain, string $providerName, string $path): void
    {
        $dir = dirname($path);
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $stub = <<<PHP
<?php

declare(strict_types=1);

namespace App\Infrastructure\\{$domain}\Providers;

use Illuminate\Support\ServiceProvider;

final class {$providerName} extends ServiceProvider
{
    public function register(): void
    {
        // Bind repositories
    }
}

PHP;
        file_put_contents($path, $stub);
        $this->info("Created Provider [{$path}]");
    }

    private function registerProviderInBootstrap(string $domain, string $providerName): void
    {
        $bootstrapPath = base_path('bootstrap/providers.php');
        
        if (! file_exists($bootstrapPath)) {
            $this->warn("bootstrap/providers.php not found. Cannot auto-register $providerName.");
            return;
        }

        $content = file_get_contents($bootstrapPath);
        $providerClass = "App\Infrastructure\\{$domain}\Providers\\{$providerName}::class";

        if (! str_contains($content, $providerClass)) {
            // Insert it right before the closing bracket of the return array
            $content = preg_replace('/];/', "    {$providerClass},\n];", $content);
            file_put_contents($bootstrapPath, $content);
            $this->info("Registered {$providerName} in bootstrap/providers.php");
        }
    }

    private function addRepositoryBinding(string $domain, string $entity, string $path): void
    {
        $content = file_get_contents($path);

        $interfaceClass = "App\Domain\\{$domain}\Repositories\\{$entity}RepositoryInterface";
        $repositoryClass = "App\Infrastructure\\{$domain}\Persistence\Eloquent{$entity}Repository";

        if (str_contains($content, $interfaceClass) && str_contains($content, "Eloquent{$entity}Repository")) {
            $this->info("Binding for {$entity} already exists in {$domain}ServiceProvider.");
            return;
        }

        // Add use statements after the namespace declaration
        $useStatements = "use {$interfaceClass};\nuse {$repositoryClass};";
        
        if (! str_contains($content, $useStatements)) {
            $content = preg_replace(
                '/namespace App\\\\Infrastructure\\\\' . $domain . '\\\\Providers;/',
                "namespace App\\Infrastructure\\{$domain}\\Providers;\n\n{$useStatements}",
                $content
            );
        }

        // Add binding inside the register method
        $bindingCode = "        \$this->app->bind({$entity}RepositoryInterface::class, Eloquent{$entity}Repository::class);\n";
        
        if (! str_contains($content, $bindingCode)) {
            $content = preg_replace(
                '/public function register\(\):\s*void\s*\{/',
                "public function register(): void\n    {\n{$bindingCode}",
                $content
            );
        }

        file_put_contents($path, $content);
        $this->info("Bound {$entity}RepositoryInterface in {$domain}ServiceProvider.");
    }
}
