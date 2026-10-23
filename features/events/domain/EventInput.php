<?php
final class EventInput {
    public static function fromArray(array $input): array {
        $result = [];
        foreach (['title', 'description', 'date', 'time', 'location'] as $field) {
            if (!isset($input[$field]) || !is_string($input[$field]) || trim($input[$field]) === '') {
                throw new InvalidArgumentException('Preencha os campos obrigatórios do evento.');
            }
            $result[$field] = trim($input[$field]);
        }
        if (strlen($result['title']) > 255 || strlen($result['location']) > 255) {
            throw new InvalidArgumentException('Título ou local excede o limite.');
        }
        $date = DateTimeImmutable::createFromFormat('!Y-m-d', $result['date']);
        if (!$date || $date->format('Y-m-d') !== $result['date']) throw new InvalidArgumentException('Data inválida.');
        if (!preg_match('/^(?:[01][0-9]|2[0-3]):[0-5][0-9](?::[0-5][0-9])?$/', $result['time'])) {
            throw new InvalidArgumentException('Hora inválida.');
        }
        if (strlen($result['time']) === 5) $result['time'] .= ':00';
        $category = filter_var($input['category'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if ($category === false) throw new InvalidArgumentException('Categoria inválida.');
        $result['category'] = $category;
        return $result;
    }
}
