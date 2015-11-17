# API Client
Part of [PayBreak/api-client](../)

## Abstract API Client
Is a simple API client which uses [GuzzleHttp](https://github.com/guzzle/guzzle) and it could be fitted to format and message structure.

### Implementation

Class should be extended to apply specific headers and authentication methods.

#### Request Body

```php
/**
 * @param array $body
 * @return array
 */
protected abstract function processRequestBody(array $body);
```

This method is used to add *body* to the request. It's should return *Guzzle* options array which will be merged with rest options.

#### Response Body

```php
/**
 * @author WN
 * @param ResponseInterface $response
 * @return array
 * @throws BadResponseException
 */
protected abstract function processResponse(ResponseInterface $response);
```

This method is used to process successful response form API to `array`. In case code is `2xx` but response could not be processed correctly method should thrown `BadResponseException`.

#### Error Response

```php
/**
 * @author WN
 * @param ResponseInterface $response
 * @throws ErrorResponseException
 */
protected abstract function processErrorResponse(ResponseInterface $response);
```

This method is called when `4xx` response was received and it's trying to process this response as expected error response, if so `ErrorResponseException` with user meaningful message should be thrown. And then handled in application as *nice error response*.

All others `Exceptions` are thrown as from *GuzzleHttp*.

### Example

```php
$client = ApiClient::make('http://httpbin.org/');

try {
    $client->post('post', [
        'some' => 'data',
    ]);
} catch (ErrorResponseException $e) {

    pass_error_to_view($e->getMessage());

} catch (\Excpetion $e) {

    pass_error_to_view('API Error');
    log($e->getMessage());
}
```

## Api Client
Example implementation of `AbstractApiClient` for JSON API.
