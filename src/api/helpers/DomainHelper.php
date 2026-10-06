<?php declare(strict_types = 1);

class DomainHelper {

    public static function getDomainOrDie(): string {
        $host = self::extractHost($_SERVER['HTTP_ORIGIN'] ?? '') ?: self::extractHost($_SERVER['HTTP_REFERER'] ?? '');
        if ($host === '') {
            header('HTTP/1.1 400 Bad Request');
            die();
        }
        return preg_replace('/^www\./', '', $host);
    }

    private static function extractHost(string $url): string {
        return strtolower((string) parse_url($url, PHP_URL_HOST));
    }
}
