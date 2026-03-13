# Infrastructure Layer

The **Infrastructure Layer** contains the concrete implementations of the abstractions defined in the Domain and Application layers. This is where the application connects to the "outside world," including the Laravel framework itself.

## Boundaries

- **Inward**: Depends on both the **Domain** and **Application** layers. It implements the interfaces defined by these internal layers.
- **Outward**: This is an "outer" layer. It depends on external factors (Database, Framework, Third-party APIs).

## Core Principles

- **Framework Specific**: This layer is allowed (and expected) to depend on Laravel, Eloquent, and other external packages.
- **Interface Implementation**: Implements `Domain\Repositories` and `Application\Common\Interfaces`.
- **Detail Management**: Handles the technical details like database queries, session management, file systems, and external service integrations.

## Folder Structure

- `Persistence/`: Concrete repository implementations using Eloquent models.
- `Models/`: Eloquent models (framework-only classes). These should stay inside Infrastructure and be mapped to Domain Entities before leaving.
- `Services/`: Concrete implementations of application service interfaces (e.g., `LaravelSessionService`).
- `Web/Providers/`: Service providers that wire up the application (registering middleware, view composers, or binding interfaces to implementations).

## Best Practices

- **Mapping**: Always map Eloquent models to Domain Entities in your repositories. The rest of the application should never see a "Model".
- **Encapsulation**: Keep database-specific logic (like complex joins or raw queries) isolated within this layer.
- **Configuration**: Use Laravel's configuration and service container to manage how these services are instantiated.
