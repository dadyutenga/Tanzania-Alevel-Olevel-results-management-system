# CRUSH.md - School Results Management System

## Build, Lint, and Test Commands
- **Run Server**: `php spark serve` - Starts the CodeIgniter development server.
- **Run Tests**: `vendor/bin/phpunit` - Executes all unit tests.
- **Run Single Test**: `vendor/bin/phpunit tests/unit/HealthTest.php` - Runs a specific test file.
- **Linting**: Not explicitly defined in the codebase; consider using a PHP linter like PHPCS if needed.

## Code Style Guidelines
- **Imports**: Use fully qualified namespaces for clarity (e.g., `use CodeIgniter\Database\BaseBuilder;`).
- **Formatting**: Follow PSR-12 coding standards for PHP, with consistent indentation (4 spaces) and brace placement.
- **Types**: Use explicit type hints for function parameters and return types where possible.
- **Naming Conventions**: Use CamelCase for class names, snake_case for database tables, and lowercase with underscores for variables.
- **Error Handling**: Utilize try-catch blocks for database operations and return user-friendly messages.
- **File Structure**: Controllers in `app/Controllers`, Models in `app/Models`, Views in `app/Views`.

## Project Structure
- **app/**: Core application files including Controllers, Models, Views, and Config.
- **public/**: Publicly accessible files and entry point (`index.php`).
- **tests/**: Unit and integration tests for the application.

## Additional Notes
- This file is for agentic coding agents to understand repository conventions.
- No specific Cursor or Copilot rules found in the repository.
