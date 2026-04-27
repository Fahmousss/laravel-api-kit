# Project Rules and Architecture

This project follows **Onion Architecture** combined with **Domain-Driven Design (DDD)** and **CQRS (Command Query Responsibility Segregation)** principles.

## 1. Expanded Project Structure

```text
app/
├── Application/                # Application Layer (Orchestration)
│   ├── Bus/                    # Command & Query Bus implementations
│   ├── Contracts/              # Bus and other application-wide interfaces
│   ├── Features/               # Use cases organized by Domain
│   │   └── {Domain}/
│   │       ├── Commands/       # Write operations (State changes)
│   │       ├── Queries/        # Read operations (Data retrieval)
│   │       ├── DTOs/           # Data Transfer Objects (Input/Output)
│   │       └── Common/         # Shared logic/interfaces for this domain's application layer
├── Domain/                     # Domain Layer (Core Business Logic)
│   ├── {Domain}/
│   │   ├── Entities/           # Domain Entities (Pure PHP objects)
│   │   ├── ValueObjects/       # Immutable value objects
│   │   ├── Repositories/       # Repository Interfaces
│   │   ├── Exceptions/         # Domain-specific exceptions
│   │   └── Services/           # Domain Services (Complex business logic)
│   └── Shared/                 # Cross-cutting domain concerns (e.g., Pagination)
├── Infrastructure/             # Infrastructure Layer (External Concerns)
│   ├── {Domain}/
│   │   ├── Persistence/        # Eloquent Repository implementations
│   │   ├── Models/             # Eloquent Models
│   │   ├── Services/           # Implementations of Application/Domain interfaces
│   │   └── Providers/          # Domain-specific Service Providers
│   └── Shared/                 # Shared infrastructure (Models, Traits, Providers)
├── Presentation/               # Presentation Layer (Entry Points)
│   ├── Controllers/            # API Controllers
│   ├── Requests/               # Form Requests (Validation)
│   ├── Resources/              # API Resources (Transformation)
│   ├── Middleware/             # HTTP Middleware
│   └── Shared/                 # Shared presentation logic (Traits)
├── Providers/                  # Global Service Providers
console/                        # Custom Artisan commands and Console logic
routes/                         # Route definitions
stubs/                          # Custom scaffolding stubs
tests/                          # Test suite (Pest)
```

## 2. Layer Explanation & Boundaries

### **Domain Layer**
- **Role**: Contains the "heart" of the business. Pure business logic and rules.
- **Boundaries**: MUST NOT depend on any other layer or framework (Laravel).
- **Implementation**:
    - Use **Entities** to represent objects with identity.
    - Use **Repository Interfaces** to define how data should be accessed, without knowing *where* it comes from.
    - Throw **Domain Exceptions** when business rules are violated.

### **Application Layer**
- **Role**: Orchestrates the flow of data. Implements use cases.
- **Boundaries**: Can depend on the Domain layer. Uses interfaces to interact with Infrastructure.
- **Implementation**:
    - Divided into **Commands** (side-effects) and **Queries** (read-only).
    - **Handlers** receive a Command/Query, interact with Repositories/Services, and return **DTOs**.
    - MUST NOT contain business logic; it only coordinates domain objects.

### **Infrastructure Layer**
- **Role**: Technical implementation of the interfaces defined in Domain/Application.
- **Boundaries**: Can depend on the Domain and Application layers. This is where Laravel-specific code (Eloquent, Mail, Redis) lives.
- **Implementation**:
    - **Eloquent Models** live here.
    - **Repositories** implement the interfaces using Eloquent.
    - **Service Providers** bind interfaces to implementations.

### **Presentation Layer**
- **Role**: Handles HTTP requests and returns responses.
- **Boundaries**: Only interacts with the Application layer via the **Command/Query Bus**.
- **Implementation**:
    - **Controllers** are "thin". They validate input via **Requests**, dispatch a Command/Query, and format output via **Resources**.
    - Never inject Repositories directly into Controllers.
    - Always use ApiResponse Trait to return response.

---

## 3. How to Create a New Module

Always use the custom interactive scaffolding commands to maintain architectural integrity.

### Step 1: Scaffold the Domain
Create the core entities and repository interfaces.
```bash
php artisan make:domain
# Prompts: Domain name (e.g., Shop), Entity name (e.g., Order)
```

### Step 2: Scaffold the Infrastructure
Create the DB model, migration, factory, and repository implementation.
```bash
php artisan make:infrastructure
# Prompts: Domain name (Shop), Entity name (Order)
# This automatically binds the RepositoryInterface to the Eloquent implementation.
```

### Step 3: Create Use Cases
Define what your module can actually *do*.
```bash
# To create a write operation
php artisan make:use-case --command
# Prompts: Domain (Shop), Name (PlaceOrder)

# To create a read operation
php artisan make:use-case --query
# Prompts: Domain (Shop), Name (GetOrderDetails)
```
*Note: This automatically registers the handlers in the domain's ServiceProvider.*

### Step 4: Add Presentation Entry Points
Create the controller and routes.
```bash
# Create Controller
php artisan make:controller Presentation/Controllers/Api/V1/Shop/OrderController

# Create Request
php artisan make:request Presentation/Requests/Api/V1/Shop/PlaceOrderRequest
```

### Step 5: Wire it up
1. In the **Controller**, inject `CommandBusInterface` or `QueryBusInterface`.
2. Dispatch the Command/Query created in Step 3.
3. Map the result to a **Resource**.
4. Define the route in `routes/api/v1.php`.

---

## Dependency Rule
**Inner circles (Domain) must not know anything about outer circles (Infrastructure/Presentation).**
Dependencies should always point **inwards**.
- Domain ← Application ← Infrastructure
- Domain ← Application ← Presentation
