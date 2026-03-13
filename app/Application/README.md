# Application Layer

The **Application Layer** orchestrates the application's use cases. it sits between the Presentation and Domain layers, translating input from the outside world into actions performed on the domain.

## Boundaries

- **Inward**: Depends **only** on the **Domain** layer. It consumes Domain Entities and Repository Interfaces.
- **Outward**: Application must **never** depend on the Infrastructure or Presentation layers. It defines Service Interfaces that the Infrastructure must implement.

## Core Principles

- **Use Case Driven**: Organized around features and user actions (e.g., `RegisterUser`, `Login`).
- **Orchestration**: It tells the Domain and Infrastructure what to do, but doesn't do the heavy lifting itself.
- **Dependency Inversion**: Depends on Domain Repository Interfaces and Application-defined Service Interfaces, never on concrete Infrastructure implementations.
- **CQRS**: Strictly separates Write operations (Commands) from Read operations (Queries).

## Folder Structure

- `Bus/`: Implementation of the Command and Query buses.
- `Contracts/`: Interfaces for the buses and other core application services.
- `Features/{Feature}/`:
    - `Commands/`: Input DTOs representing a request to change state, and their corresponding Handlers.
    - `Queries/`: Input DTOs representing a request to read data, and their corresponding Handlers.
    - `DTOs/`: "Read-only" data structures returned by handlers to the Presentation Layer. Never return entities directly.
    - `Common/Interfaces/`: Service interfaces defined by the application but implemented in Infrastructure (e.g., `AuthTokenServiceInterface`).

## Rules

- **Handlers** must receive a single Command or Query and return a DTO or a primitive/boolean.
- **Commands** (Write) should not return data (except perhaps an ID).
- **Queries** (Read) must never change the application state.
- **Persistence Ignorance**: Handlers operate on repository interfaces; they don't know if the data is in MySQL, Redis, or an external API.
