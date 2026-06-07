# Contract: File upload progress

**Feature**: symfinity **050** (v0.3)  
**Reference**: better-upload **patterns only** (XHR progress, status states)  
**Status**: Normative for implement

## Form option

**Key**: `FormUiOptionKeys::UPLOAD_UX` (`ui_upload`)

**Applies to**: `FileType` (and subclasses)

**Shape**:

```php
[
    'max_size' => 5_242_880,     // optional bytes hint
    'accept' => 'image/*',       // optional, passed to input accept
    'xhr_upload' => false,       // default false — when true, Stimulus intercepts submit for single-file AJAX demo
]
```

## MUST

| ID | Rule |
|----|------|
| UPL-1 | Expose `symfinity_form_ui.upload` on file field FormView per catalog |
| UPL-2 | Register Stimulus controller `form-ui--upload` in bundle `assets/controllers/` |
| UPL-3 | Controller updates `progress` and `status` via data attributes / live region — not by mutating FormView server-side mid-request |
| UPL-4 | On validation error after submit, set `upload.error` from field error message |
| UPL-5 | When JS disabled, form POST behaves as standard Symfony file upload |
| UPL-6 | Recipe documents AssetMapper import path for controller |

## MUST NOT

| ID | Rule |
|----|------|
| UPL-N1 | Implement cloud storage adapters (S3 direct upload) in v0.3 |
| UPL-N2 | Require Turbo or Inertia |
| UPL-N3 | Store upload progress server-side |

## Stimulus events

| Event | When |
|-------|------|
| `form-ui:upload:progress` | `detail.progress` 0–100 |
| `form-ui:upload:complete` | Successful XHR (demo mode only) |
| `form-ui:upload:error` | Network or validation failure |

## Verification

- Unit: option parser validates `max_size` positive int
- Integration: enabled option → vars defaults `progress: 0`, `status: idle`
- Manual: dogfood demo shows progress bar animation in xhr_upload demo mode
