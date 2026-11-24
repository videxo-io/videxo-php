# Changelog

## 0.2.0

The first release after 0.1.0. It carries the fixes made for a 0.1.1 that was never tagged.

- `tts()` for `POST /v1/tts`: text spoken in `locale` as a WAV file, by the language's default voice
  or a ready private voice (`voice_id`). PHPStan type `TtsRequest`.
- `voices` for the private voices of the account: `create` (a voice declared from a short WAV
  recording, its SHA-256 and size, with the voice-use statement), `uploadReference` (the recording's
  bytes; the voice is then `ready`), `get`, `page`, `list` and `delete`. Models `PrivateVoice` and
  `PrivateVoiceList`, PHPStan type `PrivateVoiceCreate`.
- `adaptive()`, `detectSilence()` and `removeSilence()` for `POST /v1/adaptive`,
  `POST /v1/detect_silence` and `POST /v1/remove_silence`, with their PHPStan types.

### Fixes since 0.1.0

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
