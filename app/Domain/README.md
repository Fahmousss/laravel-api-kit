# Domain Layer

The **Domain Layer** is the heart of the application. It encapsulates the core business logic and rules that are independent of any external framework, database, or UI.

## Boundaries

- **Inward**: The absolute center of the "onion". Nothing is inside the Domain layer.
- **Outward**: Domain must **never** know about the Application, Infrastructure, or Presentation layers. It is entirely self-contained.

## Core Principles

- **Zero Dependencies**: This layer must not have any dependencies on Laravel, Eloquent, or any third-party packages.
- **Pure PHP**: Code here should be "Plain Old PHP Objects" (POPOs).
- **Stability**: This is the most stable part of the application. Change here should only represent a change in business requirements.

## Folder Structure

- `Entities/`: Pure PHP objects representing business concepts (e.g., `UserEntity`). Use constructor properties for immutability.
- `Enums/`: Domain-specific enumerations (e.g., `UserStatus`).
- `Exceptions/`: Custom exceptions representing business rule violations.
- `Repositories/`: **Interfaces only**. These define standard ways to access and persist entities without knowing the underlying technology (e.g., `UserRepositoryInterface`).
- `Shared/`: Contracts and abstractions shared across multiple domains.

## Best Practices

- Ensure Entities return only other Entities or primitive types.
- Never inject framework classes (like `Illuminate\Http\Request`) here.
- Entities should focus on *what* the data is and *what* it can do, not *how* it is stored.
