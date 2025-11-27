<?php
class Validator
{
    private $errors = [];

    public function validate($data, $rules)
    {
        $this->errors = [];

        foreach ($rules as $field => $ruleSet) {
            $rulesArray = explode('|', $ruleSet);
            $value = $data[$field] ?? null;

            foreach ($rulesArray as $rule) {
                $this->applyRule($field, $value, $rule, $data);
            }
        }

        return empty($this->errors);
    }

    private function applyRule($field, $value, $rule, $allData)
    {
        if (strpos($rule, ':') !== false) {
            list($ruleName, $ruleValue) = explode(':', $rule, 2);
        } else {
            $ruleName = $rule;
            $ruleValue = null;
        }

        switch ($ruleName) {
            case 'required':
                if (empty($value) && $value !== '0') {
                    $this->errors[$field][] = ucfirst(str_replace('_', ' ', $field)) . ' is required';
                }
                break;

            case 'email':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->errors[$field][] = ucfirst(str_replace('_', ' ', $field)) . ' must be a valid email';
                }
                break;

            case 'min':
                if (!empty($value) && strlen($value) < $ruleValue) {
                    $this->errors[$field][] = ucfirst(str_replace('_', ' ', $field)) . ' must be at least ' . $ruleValue . ' characters';
                }
                break;

            case 'max':
                if (!empty($value) && strlen($value) > $ruleValue) {
                    $this->errors[$field][] = ucfirst(str_replace('_', ' ', $field)) . ' must not exceed ' . $ruleValue . ' characters';
                }
                break;

            case 'numeric':
                if (!empty($value) && !is_numeric($value)) {
                    $this->errors[$field][] = ucfirst(str_replace('_', ' ', $field)) . ' must be a number';
                }
                break;

            case 'match':
                if (!empty($value) && $value !== ($allData[$ruleValue] ?? null)) {
                    $this->errors[$field][] = ucfirst(str_replace('_', ' ', $field)) . ' must match ' . str_replace('_', ' ', $ruleValue);
                }
                break;

            case 'date':
                if (!empty($value) && !strtotime($value)) {
                    $this->errors[$field][] = ucfirst(str_replace('_', ' ', $field)) . ' must be a valid date';
                }
                break;
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getFirstError($field = null)
    {
        if ($field) {
            return $this->errors[$field][0] ?? null;
        }

        foreach ($this->errors as $fieldErrors) {
            return $fieldErrors[0] ?? null;
        }

        return null;
    }

    public function hasErrors()
    {
        return !empty($this->errors);
    }
}
