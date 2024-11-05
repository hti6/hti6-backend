<?php

namespace App\Entities;

class IPCamera
{
    /**
     * Парсит URL камеры и извлекает компоненты
     *
     * @param string $url URL камеры (rtsp://, http://, или просто IP)
     * @param string|null $username Опциональное имя пользователя
     * @param string|null $password Опциональный пароль
     * @param int|null $port Опциональный порт
     * @return array Массив с компонентами URL
     */
    private function parseUrl($url, $username = null, $password = null, $port = null) {
        $result = [
            'protocol' => null,
            'host' => null,
            'port' => null,
            'username' => null,
            'password' => null,
            'path' => null,
            'full_url' => null
        ];

        // Проверяем, является ли URL простым IP-адресом
        if (filter_var($url, FILTER_VALIDATE_IP)) {
            $result['protocol'] = 'rtsp'; // По умолчанию используем RTSP для IP-адресов
            $result['host'] = $url;
        } else {
            // Парсим URL
            $parsed = parse_url($url);

            if ($parsed) {
                $result['protocol'] = isset($parsed['scheme']) ? $parsed['scheme'] : 'rtsp';
                $result['host'] = $parsed['host'] ?? $url;
                $result['path'] = $parsed['path'] ?? '';
                $result['port'] = $parsed['port'] ?? null;
                $result['username'] = $parsed['user'] ?? null;
                $result['password'] = $parsed['pass'] ?? null;
            }
        }

        // Применяем переданные параметры, если они не были извлечены из URL
        if ($username) $result['username'] = $username;
        if ($password) $result['password'] = $password;
        if ($port) $result['port'] = $port;

        // Устанавливаем порт по умолчанию, если он не указан
        if (!$result['port']) {
            $result['port'] = $result['protocol'] === 'rtsp' ? 554 : 80;
        }

        // Формируем полный URL
        $auth = '';
        if ($result['username'] && $result['password']) {
            $auth = urlencode($result['username']) . ':' . urlencode($result['password']) . '@';
        }

        $result['full_url'] = $result['protocol'] . '://' . $auth . $result['host'];
        if ($result['port']) {
            $result['full_url'] .= ':' . $result['port'];
        }
        if ($result['path']) {
            $result['full_url'] .= $result['path'];
        }

        return $result;
    }

    /**
     * Проверяет подключение к камере
     *
     * @param string $url URL камеры
     * @param string|null $username Опциональное имя пользователя
     * @param string|null $password Опциональный пароль
     * @param int|null $port Опциональный порт
     * @return array Результат проверки
     */
    public function checkCamera($url, $username = null, $password = null, $port = null) {
        $parsed = $this->parseUrl($url, $username, $password, $port);
        $result = [
            'success' => false,
            'message' => '',
            'protocol' => $parsed['protocol'],
            'connection_details' => $parsed,
            'response_code' => null
        ];

        // Удаляем пароль из деталей подключения для безопасности
        unset($result['connection_details']['password']);

        try {
            if ($parsed['protocol'] === 'rtsp') {
                return $this->checkRTSPConnection($parsed);
            } else if ($parsed['protocol'] === 'http' || $parsed['protocol'] === 'https') {
                return $this->checkHTTPConnection($parsed);
            } else {
                $result['message'] = "Unsupported protocol: {$parsed['protocol']}";
                return $result;
            }
        } catch (Exception $e) {
            $result['message'] = "Error: " . $e->getMessage();
            return $result;
        }
    }

    /**
     * Проверяет HTTP/HTTPS подключение
     */
    private function checkHTTPConnection($parsed) {
        $result = [
            'success' => false,
            'message' => '',
            'protocol' => $parsed['protocol'],
            'connection_details' => $parsed,
            'response_code' => null
        ];

        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'timeout' => 5,
                'ignore_errors' => true
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false
            ]
        ]);

        if ($parsed['username'] && $parsed['password']) {
            $auth = base64_encode("{$parsed['username']}:{$parsed['password']}");
            $context['http']['header'] = "Authorization: Basic {$auth}\r\n";
        }

        $response = @file_get_contents($parsed['full_url'], false, $context);

        if ($response !== false) {
            $http_response = $http_response_header[0];
            preg_match('/HTTP\/\d\.\d\s+(\d+)/', $http_response, $matches);
            $response_code = $matches[1];

            $result['response_code'] = $response_code;
            if ($response_code == 200) {
                $result['success'] = true;
                $result['message'] = 'Successfully connected to camera';
            } else {
                $result['message'] = "Connection failed with HTTP code: {$response_code}";
            }
        } else {
            $result['message'] = 'Could not connect to camera';
        }

        return $result;
    }

    /**
     * Проверяет RTSP подключение
     */
    private function checkRTSPConnection($parsed) {
        $result = [
            'success' => false,
            'message' => '',
            'protocol' => 'rtsp',
            'connection_details' => $parsed,
            'response_code' => null
        ];

        // Проверяем доступность порта
        $socket = @fsockopen($parsed['host'], $parsed['port'], $errno, $errstr, 5);

        if ($socket) {
            $result['success'] = true;
            $result['message'] = "RTSP port is accessible";
            fclose($socket);
        } else {
            $result['message'] = "Could not connect to RTSP port: {$errstr}";
        }

        return $result;
    }
}
