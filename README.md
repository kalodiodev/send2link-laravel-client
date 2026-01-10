# Send2Link Laravel Client

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

A fluent Laravel wrapper for the [send2link.eu](https://send2link.eu) URL Shortener API. Easily manage workspaces, projects, and shortlinks directly from your Laravel application.

## Features

- **Fluent API**: Clean, expressive syntax for all API operations.
- **Resource Management**: Complete CRUD for Projects and Shortlinks.
- **Type-Safe**: Uses Data Transfer Objects (DTOs) for all API responses.
- **Laravel Integrated**: Seamlessly integrates with Laravel's Service Container and Facades.
- **Mockable**: Ready for testing with built-in support for Laravel's HTTP fakes.

## Requirements

- PHP 8.2 or higher
- Laravel 10.0 or higher

## Installation

You can install the package via composer:

```bash
composer require kalodiodev/send2link-laravel-client
```

### Configuration

Publish the configuration file using the artisan command:

```bash
php artisan vendor:publish --tag="send2link-config"
```

This will create a `config/sendtolink.php` file. Add your API credentials to your `.env` file:

```env
SEND2LINK_AUTH_KEY=your_api_key
SEND2LINK_SERVER=https://send2link.eu
SEND2LINK_TIMEOUT=10
```

## Usage

This package provides a fluent API to interact with the Send2Link service. You can use it via Dependency Injection or a Facade.

### Dependency Injection (Recommended)

The recommended way to use the client is to inject the `Send2LinkService` into your controllers or services:

```php
use Kalodiodev\Send2Link\Send2LinkService;

public function __construct(protected Send2LinkService $send2link)
{}

public function index()
{
    // Get all workspaces
    $workspaces = $this->send2link->workspaces()->all();
    
    // Get account details
    $account = $this->send2link->account()->get()->getItem();
}
```

### Facade Usage

Alternatively, you can use the `Send2Link` facade:

```php
use Kalodiodev\Send2Link\Facades\Send2Link;

$account = Send2Link::account()->get()->getItem();
```

## Resources

All examples below assume you are using the injected `Send2LinkService` instance (e.g., `$send2link`).

### Workspaces

Retrieve team workspaces associated with your account.

```php
$workspaces = $send2link->workspaces()->all(); // Returns Collection<Workspace>

foreach ($workspaces as $workspace) {
    echo $workspace->getName();
    echo $workspace->getSlug();
}
```

### Account

Retrieve current user profile and statistics.

```php
$account = $send2link->account()->get()->getItem();

echo $account->getFullName();
echo $account->getEmail();
echo $account->getProjectsCount();
```

### Projects

Manage projects within a specific workspace.

```php
// List projects with pagination
$projects = $send2link->projects('my-workspace')
    ->paginate(page: 1, size: 25)
    ->all()
    ->getItems();

// Create a new project
$project = $send2link->projects('my-workspace')
    ->create('Marketing Campaign', 'Tracking links for Q1')
    ->getItem();

// Update/Delete
$send2link->projects('my-workspace')->update('uuid', 'New Name');
$send2link->projects('my-workspace')->delete('uuid');
```

### ShortLinks

Generate and manage shortened URLs.

```php
$resource = $send2link->shortLinks('my-workspace', 'project-uuid');

// Create a basic shortlink
$link = $resource->create('https://example.com', enabled: true)->getItem();

// Advanced creation with custom domain and expiration
$link = $resource->create(
    destination: 'https://example.com',
    enabled: true,
    domain: '2ln.eu',
    expireInDays: 30
)->getItem();

// List with pagination
$links = $resource->paginate(1, 50)->all()->getItems();
```

### Domains

Fetch available domains for your shortlinks.

```php
$domains = $send2link->domains()->all(); // Collection<Domain>
```

## Testing

When testing your application, you can use `Http::fake()` to mock the Send2Link API responses. Here is a standard PHPUnit example:

```php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Kalodiodev\Send2Link\Send2LinkService;

class Send2LinkTest extends TestCase
{
    public function test_it_can_fetch_account()
    {
        // 1. Mock the API response
        Http::fake([
            '*api/v1/account' => Http::response(['email' => 'test@example.com'], 200),
        ]);

        // 2. Resolve the service from the container
        $send2link = app(Send2LinkService::class);

        // 3. Perform the operation
        $account = $send2link->account()->get()->getItem();
        
        // 4. Assertions
        $this->assertEquals('test@example.com', $account->getEmail());
    }
}
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
