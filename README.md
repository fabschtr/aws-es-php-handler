# Amazon Elasticsearch PHP Signing Handler

[![Apache 2 License](https://img.shields.io/packagist/l/fabschtr/aws-es-php-handler.svg?style=flat)](https://www.apache.org/licenses/LICENSE-2.0.html)
[![Total Downloads](https://img.shields.io/packagist/dt/fabschtr/aws-es-php-handler.svg?style=flat)](https://packagist.org/packages/fabschtr/aws-es-php-handler)

AWS signing handler for the Elasticsearch-PHP (elasticsearch/elasticsearch) client.

## Installation
```
composer require fabschtr/aws-es-php-handler
```

## Usage
```php
use Elasticsearch\ClientBuilder;
use Fabschtr\AwsEsPhpHandler\AwsElasticsearchPhpHandler;

$handler = new AwsElasticsearchPhpHandler('AWS_KEY', 'AWS_SECRET', 'eu-central-1');

$this->client = ClientBuilder::create()
            ->setHandler($handler)
            ->setHosts('ELASTIC_HOSTS')
            ->build();
```
