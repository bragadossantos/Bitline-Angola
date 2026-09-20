<?php
/**
 * Endpoint ultra-leve usado pelo painel "tempo real" da hero.
 * O JavaScript mede o tempo de ida-e-volta até este ficheiro
 * para mostrar a latência REAL entre o visitante e o servidor.
 */

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate');

echo json_encode([
    'status' => 'ok',
    'servidor_hora' => date('H:i:s'),
]);
