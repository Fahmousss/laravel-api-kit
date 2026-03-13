# Presentation Layer

The **Presentation Layer** serves as the entry point to the application. It handles incoming HTTP requests, dispatches them to the Application Layer via the Command/Query bus, and formats the results for the user.

## Boundaries

- **Inward**: Depends **solely** on the **Application** layer (via the Command/Query Bus).
- **Outward**: Communicates with the user (Web/API). It should **never** directly depend on the Infrastructure or Domain layers' concrete implementations.

## Core Principles

- **Input/Output Only**: Focuses strictly on HTTP handling, validation, and response formatting.
- **Logic Free**: Should contain zero business logic. All decision-making is delegated to the Application Layer.
- **Bus-Only Interaction**: Controllers should only interact with the `CommandBus` or `QueryBus`. Direct service or repository calls are prohibited.

## Folder Structure

- `Controllers/`:
    - `Api/V1/`: JSON API endpoints, grouped by version and role.
    - `Web/`: Blade-based controllers for the web frontend.
- `Requests/`: Form Request classes for input validation.
- `Resources/`: Eloquent API Resources for transforming DTOs into standardized JSON responses.
- `ViewModels/`: Classes that prepare data specifically for Blade views, ensuring views remain simple and clean.
- `ViewComposers/`: Logic for binding data (like the authenticated user) to global view sections.
- `Shared/`: Traits and helpers shared across presentation components (e.g., `HasAuthenticatedUser`).

## Rules

- **Strict Validation**: All input must be validated via Form Requests before being passed to a Command or Query.
- **No Eloquent**: Never use Eloquent models, the `DB` facade, or the `Auth` facade directly in controllers or views. Use DTOs and ViewModels.
- **Lean Controllers**: A controller should be a simple "bridge" that dispatches to the bus and returns a response.
