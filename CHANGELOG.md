# 1.0.0
2017-05-24

## Features
- Added `RequestInterface` to `processResponse` and `processErrorResponse`

# 0.3.1
2017-04-27

## Features
- Now using Guzzle MockHandler to simulate csv download instead of downloading from an external url

# 0.3.0
2017-04-26

## Features
- Added `RetryApiClient` which extends `ApiClient` to retry failed requests multiple times (143412155)
- Updated `ApiClient` implementations so that they can handle empty JSON responses for `HTTP 204`

# 0.2.0
2016-07-26

## Features
- Added headers support

# 0.1.1
2016-02-22

## Bug Fixes
- Fixed `composer` dependencies

# 0.1.0
2016-02-22

## Initial Release
