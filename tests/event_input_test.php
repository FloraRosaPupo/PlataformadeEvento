<?php
require_once __DIR__ . '/../features/events/domain/EventInput.php';
function check(bool $condition): void { if (!$condition) throw new RuntimeException('Assertion failed'); }
$valid = ['title'=>' Evento ', 'description'=>'Teste', 'date'=>'2026-10-23', 'time'=>'09:30', 'location'=>'Sala 1', 'category'=>'1'];
$parsed = EventInput::fromArray($valid);
check($parsed['title'] === 'Evento' && $parsed['time'] === '09:30:00' && $parsed['category'] === 1);
foreach ([['date'=>'2026-02-30'], ['time'=>'25:00'], ['category'=>'0'], ['title'=>' '], ['title'=>[]]] as $bad) {
    try { EventInput::fromArray(array_replace($valid, $bad)); throw new RuntimeException('Invalid input was accepted'); }
    catch (InvalidArgumentException $expected) {}
}
echo "Event validation tests passed\n";
