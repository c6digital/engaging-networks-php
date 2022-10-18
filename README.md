# Engaging Networks PHP

## Installation

```sh
composer require c6digital/engaging-networks-php
```

## Usage

#### Create a new `EngagingNetworks` client.

```php
use C6Digital\EngagingNetworks\EngagingNetworks;

$client = new EngagingNetworks('my-api-key');
```

#### Authenticate and retrieve a temporary authorization token.

```php
$client->authenticate();
```

#### Make a page request

```php
$client->pageRequest(pageId: 12345, data: [
    // ...
]);
```