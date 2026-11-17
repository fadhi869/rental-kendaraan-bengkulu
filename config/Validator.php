<?php
class Validator
{
    public static function validate(array $data, array $rules): array
    {
        $errors = [];
        foreach ($rules as $field => $ruleStr) {
            $val = trim((string)($data[$field] ?? ''));
            $ruleList = explode('|', $ruleStr);
            foreach ($ruleList as $rule) {
                if ($rule === 'required' && $val === '') {
                    $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . ' wajib diisi.';
                    break;
                }
                if (str_starts_with($rule, 'min:')) {
                    $min = (int)substr($rule, 4);
                    if ($val !== '' && mb_strlen($val) < $min) {
                        $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . " minimal {$min} karakter.";
                        break;
                    }
                }
                if (str_starts_with($rule, 'max:')) {
                    $max = (int)substr($rule, 4);
                    if (mb_strlen($val) > $max) {
                        $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . " maksimal {$max} karakter.";
                        break;
                    }
                }
                if ($rule === 'numeric' && $val !== '' && !is_numeric($val)) {
                    $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . ' harus berupa angka.';
                    break;
                }
                if ($rule === 'email' && $val !== '' && !filter_var($val, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field] = 'Format email tidak valid.';
                    break;
                }
            }
        }
        return $errors;
    }
}
