<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class CachedPersonalAccessToken extends SanctumPersonalAccessToken
{
    protected $table = 'personal_access_tokens';

    /**
     * Cache personal access token authentication data as pure primitives
     * to eliminate 5 transatlantic roundtrips (~4-7s) on every authenticated HTTP request.
     */
    public static function findToken($token)
    {
        if (!is_string($token) || strpos($token, '|') === false) {
            return parent::findToken($token);
        }

        $key = 'pat_raw_' . hash('sha256', $token);
        $data = Cache::get($key);

        if ($data && is_array($data)) {
            $tokenModel = new static($data['token_attributes'] ?? []);
            $tokenModel->id = $data['token_id'];
            $tokenModel->exists = true;

            $user = new User($data['user_attributes'] ?? []);
            $user->id = $data['user_id'];
            $user->exists = true;

            if (!empty($data['role_attributes'])) {
                $role = new Role($data['role_attributes']);
                $role->id = $data['role_id'];
                $role->exists = true;
                $user->setRelation('role', $role);
            }

            if (!empty($data['candidate_attributes'])) {
                $candidate = new Candidate($data['candidate_attributes']);
                $candidate->id = $data['candidate_id'];
                $candidate->exists = true;
                $user->setRelation('candidate', $candidate);
            }

            $tokenModel->setRelation('tokenable', $user);
            return $tokenModel;
        }

        $tokenModel = parent::findToken($token);
        if ($tokenModel && $tokenModel->tokenable) {
            $tokenModel->tokenable->loadMissing(['role', 'candidate']);
            $user = $tokenModel->tokenable;

            Cache::put($key, [
                'token_id' => $tokenModel->id,
                'token_attributes' => $tokenModel->getAttributes(),
                'user_id' => $user->id,
                'user_attributes' => $user->getAttributes(),
                'role_id' => $user->role?->id,
                'role_attributes' => $user->role?->getAttributes(),
                'candidate_id' => $user->candidate?->id,
                'candidate_attributes' => $user->candidate?->getAttributes(),
            ], 300);
        }

        return $tokenModel;
    }
}
