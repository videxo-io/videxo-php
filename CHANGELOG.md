# Changelog

## 0.1.1 (2026-11-12)

- Bound job polling retries and HTTP attempts by the wait deadline, reject late responses, and
  preserve the last job when waiting expires. Custom clients can implement `TimeoutClientInterface`.
- Refuse network retries after consuming a nonseekable request body; keep seekable retries.
- Correct multipart guidance to use caller HTTP creation with `multipart: true`.
- Refuse multipart uploads in the single-upload helpers before sending bytes.
- Regenerate response models, request types and fixtures for the current API, including steps,
  notices, fractional credit usage and optional AI, bill and multipart fields. Existing model
  constructor arguments and older response reads keep working.

## 0.1.0

The first release: jobs (create, read, list, wait, cancel, delete files, compile), the operation
endpoints, uploads with one-time upload URLs, downloads through fresh signed links, presets, fonts,
webhook verification, retries with `Retry-After` and idempotency keys, and typed exceptions.
