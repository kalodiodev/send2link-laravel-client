# 2ln.eu Url Shortener Laravel Client

## Installation

### Step 1. Install dependency
Add repository to **composer.json**
```json
"repositories": [
    {
        "type": "git",
        "url": "https://github.com/kalodiodev/send2link-laravel-client.git"
    }
]
```

Require package
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

/* Get project by UUID */
$client->projects()->getByUuid($project_uuid);

/* Create Project */
$client->projects()->create('test name', 'test description');

/* Update Project */
$client->projects()->update($project_uuid, 'New name', 'New description');

/* Delete Project */
$client->projects()->delete($project_uuid);
```

### Domains
```php
/* Get all available domains */
$client->domains()->getAll();
```

### ShortLinks

```php
/* Get all shortlinks */
$client->shortLinks($project_uuid)->page(page: 1, pageSize: 25)->getAll();

/* Get shortlink by UUID */
$client->shortLinks($project_uuid)->getByUuid($shortlink_uuid);

/* Create ShortLink with Default domain */
$client->shortLinks($project_uuid)->create(destination: "https://example.com", enabled: true);

/* Create ShortLink with domain */
$client->shortLinks($project_uuid)->create(destination: "https://example.com", enabled: true, domain: "2ln.eu");

/* Create a ShortLink that will expire in a given number of days */
$client->shortLinks($project_uuid)->create(destination: "https://example.com", enabled: true, expireInDays: 10);

/* Update ShortLink */
$client->shortLinks($project_uuid)->update($shortlink_uuid, destination: "https://github.com", enabled: false);

/* Delete ShortLink */
$client->shortLinks($project_uuid)->delete($shortlink_uuid);
```
