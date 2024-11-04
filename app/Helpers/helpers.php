<?php

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;

/**
 * @return User
 * @throws AuthenticationException
 */
function getUser(): User
{
    $user = Auth::guard('sanctum')->user();
    if ($user) {
        return $user;
    } else {
        throw new AuthenticationException();
    }
}


/**
 * @param mixed $data
 * @param string $message
 * @return array
 */
function qck_response(mixed $data = true, string $message = 'Success'): array
{
    return [
        'result' => $data,
        'message' => __($message),
    ];
}

/**
 * @param string $message
 * @return string
 */
function qck_error(string $message): string
{
    return __($message);
}
