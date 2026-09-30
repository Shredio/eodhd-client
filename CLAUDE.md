# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Development Commands

- `composer phpstan` - Run static analysis at maximum level
- `composer test` - Run PHPUnit tests
- `composer compile` - Generate mappers (required after adding or changing payload classes)

## Architecture Overview

This is a PHP 8.4+ client library for the EODHD API (https://eodhd.com), built after `shredio/fmp-client`.

### Core Components

- **EodhdClient** (`src/EodhdClient.php`) - Interface defining the API contract
- **SymfonyEodhdClient** (`src/SymfonyEodhdClient.php`) - Implementation using Symfony HTTP Client; sends requests, checks status codes and maps responses
- **CacheEodhdClient** (`src/CacheEodhdClient.php`) - Caching decorator via `PSR-16 SimpleCache`. Caches per-symbol and list endpoints; delegates bulk, real-time, calendar and account endpoints
- **EodhdPromise** - Async operations using PHP Fibers for concurrent API calls
- **LargeResponseParser** - Streaming JSON parser (JsonMachine) with JSON pointer support, e.g. `/earnings` or `/trends/-`

### Key Design Principles

1. **Memory Efficiency**: List responses are streamed; only small documents (fundamentals, user, real-time) are decoded at once
2. **Resilience**: A fundamentals response is mapped section by section and collections item by item (`mapSection()`, `mapItems()`), so one drifted value never discards the whole response
3. **Strong Typing**: All payloads are readonly classes with comprehensive type hints
4. **Validation**: Strict mode turns every drift (including unknown keys and unknown fundamentals sections) into an exception

### API Conventions

- Base URL `https://eodhd.com/api/`, the token goes to the `api_token` query parameter, `fmt=json` is always added
- An unknown symbol answers HTTP 404; per-symbol endpoints map it to `null` or an empty result (`notFoundAsEmpty`), other endpoints throw `UnexpectedHttpCodeException`
- Many numbers arrive as strings (statements, bulk dividends, trends), so the type config uses a lenient number converter guarded by `RepresentableNumberConverter`
- Fundamentals serialize lists as objects keyed by date or position; nested ones are re-indexed by `KeyedList::values()` in the payload, top-level ones by `mapItems()`
- Whole amounts (market cap, share counts) go through `IntegerAmount::roundToInt()`, because they occasionally arrive as floats or strings
- A real-time quote of an unknown symbol has every value `"NA"`, mapped to null via `nullValues`
- Every nullable string is typed `non-empty-string|null`, so an empty string from the API becomes null

### Adding New Endpoints

1. Fetch the response to determine its structure and save the **full** response body to `tests/Unit/fixtures/`. Never commit personal data (e.g. the `user` endpoint returns the account owner's name and email - anonymize it).
2. Create a payload class in `src/Payload/` (see Payload Classes below).
3. Run `composer compile` to generate the mapper in `src/Mapper/`. Mappers are generated - never edit them manually.
4. Add the method to the `EodhdClient` interface with an `@see` annotation containing the endpoint URL without the token. Implement it in `SymfonyEodhdClient` and add the cached or delegated method to `CacheEodhdClient`.
5. Write tests covering success and error cases, run the endpoint in strict mode against several live responses (different exchanges, instruments with missing data) and fix every drift.
6. Update README.md and OVERVIEW.md (method, purpose, parameters, **complete** list of returned fields, API endpoint URL without the token).

### Payload Classes

All payload classes in `src/Payload/` must include:

- **CompileObjectMapper attribute**: `#[CompileObjectMapper]`, with `identifier` set to the property that identifies a row in error messages (e.g. `'code'`, `'date'`)
- **Constructor**: all properties as readonly promoted constructor parameters; API keys that are not valid or descriptive property names are mapped with `#[CompilePropertyOptions(name: 'ApiKey')]`
- **Optional keys**: a key the API sends only for some instruments gets a default value (`= null`) and must be among the last parameters
- **toArray() method**: returns all properties; the shape is declared once as `@phpstan-type <Class>Array` on the class and imported by containers via `@phpstan-import-type`
- **Cache compatibility**: adding, removing or renaming a property of a payload cached by `CacheEodhdClient` requires bumping `CacheEodhdClient::CacheKeyVersion`

When creating tests for payload classes:
- Use `assertSame()` with `toArray()` for payload comparisons, never `assertEquals()`
