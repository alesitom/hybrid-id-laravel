<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    |
    | The HybridId profile to use: 'compact' (16), 'standard' (20), or
    | 'extended' (24). Custom profiles registered via registerProfile()
    | are also supported.
    |
    */

    'profile' => env('HYBRID_ID_PROFILE', 'standard'),

    /*
    |--------------------------------------------------------------------------
    | Node
    |--------------------------------------------------------------------------
    |
    | A 2-character base62 node identifier. Set this explicitly in clustered
    | deployments to prevent cross-node collisions. When null, a deterministic
    | identifier is derived from hostname + PID.
    |
    */

    'node' => env('HYBRID_ID_NODE'),

    /*
    |--------------------------------------------------------------------------
    | Require Explicit Node
    |--------------------------------------------------------------------------
    |
    | When true, the generator throws if no explicit node is provided.
    | Enable this in production to prevent accidental auto-detection.
    |
    */

    'require_explicit_node' => (bool) env('HYBRID_ID_REQUIRE_NODE', false),

    /*
    |--------------------------------------------------------------------------
    | Blind Mode
    |--------------------------------------------------------------------------
    |
    | When true, timestamps and node identifiers are replaced with
    | HMAC-derived opaque values. IDs remain the same length but are
    | no longer chronologically sortable.
    |
    */

    'blind' => (bool) env('HYBRID_ID_BLIND', false),

    /*
    |--------------------------------------------------------------------------
    | Blind Secret
    |--------------------------------------------------------------------------
    |
    | Optional base64-encoded secret (>= 32 raw bytes) for blind mode.
    | When set, enables key rotation, multi-instance coordination,
    | and authorized auditing. When null, a random secret is generated
    | per instance.
    |
    */

    'blind_secret' => env('HYBRID_ID_BLIND_SECRET'),

];
