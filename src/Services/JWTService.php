<?php

namespace App\Services;

use DateTimeZone;
use DateTimeImmutable;

class JWTService
{
    /**
     * JWT Generation
     * @param array $header
     * @param array $payload
     * @param string $secret
     * @param int $validity
     * @return string
     */

    public function generate(array $header, array $payload, string $secret, int $validity = 2160 /*30min*/): string
    {
        if ($validity > 0) {
            $timezone = new DateTimeZone('Europe/Paris');
            $now = new DateTimeImmutable('now', $timezone);
            $exp = $now->getTimestamp() + $validity;

            $payload['iat'] = $now->getTimestamp();
            $payload['exp'] = $exp;
        }

        //Base64 encoding
        $base64Header = base64_encode(json_encode($header));
        $base64Payload = base64_encode(json_encode($payload));

        $base64Header = str_replace(['+', '/', '='], ['-', '_', ''], $base64Header);
        $base64Payload = str_replace(['+', '/', '='], ['-', '_', ''], $base64Payload);

        //Generate signature
        $secret = base64_encode($secret);
        $signature = hash_hmac('sha256', $base64Header . '.' . $base64Payload, $secret, true);

        $base64Signature = base64_encode($signature);

        $signature = str_replace(['+', '/', '='], ['-', '_', ''], $base64Signature);

        //Create token
        $jwt = $base64Header . '%2e' . $base64Payload . '%2e' . $signature;

        return $jwt;
    }

    public function isValid(string $token): bool
    {
        return preg_match(
            '/^[a-zA-Z0-9\-\_\=]+%2e[a-zA-Z0-9\-\_\=]+%2e[a-zA-Z0-9\-\_\=]+$/',
            $token
        ) === 1;
    }

    public function getHeader(string $token): array
    {
        $array = explode('%2e', $token);

        $header = json_decode(base64_decode($array[0]), true);

        return $header;
    }

    public function getPayload(string $token): array
    {
        $array = explode('%2e', $token);

        $payload = json_decode(base64_decode($array[1]), true);

        return $payload;
    }

    public function isExpired(string $token): bool
    {
        $payload = $this->getPayload($token);

        $timezone = new DateTimeZone('Europe/Paris');
        $now = new DateTimeImmutable('now', $timezone);

        return $payload['exp'] < $now->getTimestamp();
    }

    public function check(string $token, string $secret): bool
    {
        $header = $this->getHeader($token);
        $payload = $this->getPayload($token);

        $verifToken = $this->generate($header, $payload, $secret, 0);

        return $token === $verifToken;
    }
}
