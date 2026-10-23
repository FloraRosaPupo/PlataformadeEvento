<?php
require_once __DIR__ . '/../../../Classes/Usuarios.php';
function requireEventManager(): void {
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    $user = isset($_SESSION['user']) && is_string($_SESSION['user'])
        ? unserialize($_SESSION['user'], ['allowed_classes' => ['Usuarios']]) : null;
    if (!$user instanceof Usuarios || !in_array($user->getUserType(), ['admin', 'pessoal'], true)) {
        http_response_code(403); exit('Acesso não autorizado.');
    }
    $_SESSION['event_csrf'] ??= bin2hex(random_bytes(32));
}
function requireEventCsrf(): void {
    $token = $_POST['csrf_token'] ?? null;
    if (!is_string($token) || !isset($_SESSION['event_csrf']) || !hash_equals($_SESSION['event_csrf'], $token)) {
        http_response_code(403); exit('Recarregue o formulário e tente novamente.');
    }
}
