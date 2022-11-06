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

#### [Authenticate and retrieve a temporary authorization token.](https://speca.io/engagingnetworks/engaging-network-services?key=726cda99f0551ef286486bb847f5fb5d#authenticate-to-ens)

```php
$client->authenticate();
```

#### [Make a page request](https://speca.io/engagingnetworks/engaging-network-services?key=726cda99f0551ef286486bb847f5fb5d#process-a-page-request)

```php
$client->pageRequest(pageId: 12345, data: [
    // ...
]);
```

#### [Retrieve a Supporter by ID](https://speca.io/engagingnetworks/engaging-network-services?key=726cda99f0551ef286486bb847f5fb5d#get-supporter-by-id)

```php
$client->getSupporterById(supporterId: 12345, parameters: [
    // ...
]);
```