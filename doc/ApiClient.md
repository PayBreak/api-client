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

This method is used to add a *body* to the request. It's should return *Guzzle* options array which will be merged with the rest of the options.

#### Response Body

```php
/**
 * @author WN
 * @param ResponseInterface $response
 * @return array
 * @throws BadResponseException
 */
protected abstract function processResponse(ResponseInterface $response, RequestInterface $request);
```

This method is used to process successful response from an API to `array`. In the case that the code is a `2xx` response, but the response could not be processed correctly, the method should throw a `BadResponseException`.

#### Error Response

```php
/**
 * @author WN
 * @param ResponseInterface $response
 * @throws ErrorResponseException
 */
protected abstract function processErrorResponse(ResponseInterface $response, RequestInterface $request);
```

This method is called when `4xx` response was received and an expected error response is being processed. If an expected error is returned, the `ErrorResponseException` should be thrown with a meaningful message, so they can be handled in your application as *nice error response*.

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
