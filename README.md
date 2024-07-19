# 2Ln.eu Url Shortener Laravel Client

## Installation

### Step 1. Install dependency
```
composer require kalodiodev/send2link-laravel-client
```

### Step 2. Configure API Key
```
php artisan vendor:publish --provider="Kalodiodev\Send2Link\Send2LinkServiceProvider"
```

Add API key to **.env**
```
SEND2LINK_AUTH_KEY=api_key
```

## Usage

### Projects

```php
/* Get all projects */
$client->projects()->page(page: 1, pageSize: 25)->getAll();

/* Create Project */
$client->projects()->create('test name', 'test description');

/* Update Project */
$client->projects()->update($project_uuid, 'New name', 'New description');

/* Delete Project */
$client->projects()->delete($project_uuid);
```

### ShortLinks

```php
/* Get all shortlinks */
$client->shortLinks($project_uuid)->page(page: 1, pageSize: 25)->getAll();

/* Create ShortLink */
$client->shortLinks($project_uuid)->create(destination: "https://example.com", enabled: true);

/* Update ShortLink */
$client->shortLinks($project_uuid)->update($shortlink_uuid, destination: "https://github.com", enabled: false);

/* Delete ShortLink */
$client->shortLinks($project_uuid)->delete($shortlink_uuid);
```
