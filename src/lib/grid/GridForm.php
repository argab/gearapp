<?php

namespace lib\grid;

use lib\grid\Grid;
use lib\grid\IGridFormProvider;

class GridForm extends Grid
{
    const DEFAULT_INPUT_TYPE = 'text';

    protected $types = [
        'string'  => 'text',
        'boolean' => 'radio',
        'integer' => 'number',
    ];

    protected $config = [
        'load_values' => true,
    ];

    protected $tagAttributes = [
        'method'  => 'post',
        'name'    => null,
        'action'  => null,
        'enctype' => 'multipart/form-data',
    ];

    protected $tag = 'form';

    protected $token = [];

    protected $input = [];

    protected $label = [];

    protected $inputRequestName = null;

    protected $inputIDPrefix = null;

    protected $inputAttributes = [];

    protected $labelAttributes = [];

    public function __construct(IGridFormProvider $model, array $conf = [])
    {
        parent::__construct($model);

        $this->setForm()->loadInputs();

        $this->config = array_merge($this->config, $conf);

        if ($this->config['load_values'])

            $this->loadValues()->loadErrors();
    }

    public function setForm(array $attr = [])
    {
        $this->setTagAttributes($attr);

        return $this;
    }

    public function setInputRequestName($name = null, $dash = true)
    {
        $name = $name ?: $this->getModelName();

        $this->inputRequestName = $dash ? self::dashName($name) : $name;

        return $this;
    }

    public function getInputRequestName($key = null)
    {
        return ($key && $this->inputRequestName)

            ? sprintf('%s[%s]', $this->inputRequestName, $this->getInputName($key))

            : $this->getInputName($key);
    }

    public function setInputRequestNames($requestName = null, array $keyData = [], $dash = true)
    {
        $this->setInputRequestName($requestName, $dash);

        foreach ($this->getInputKeys() as $k)
        {
            $this->input[$k]['name'] = $keyData[$k] ?? $this->getInputRequestName($k);
        }

        return $this;
    }

    public function getInputName($key)
    {
        return preg_replace('#[^a-z\d\-_\[\]]+#i', '', $this->input[$key]['name']);
    }

    public function setInputIDPrefix($name = null, $dash = true)
    {
        $name = $name ?: $this->getModelName();

        $this->inputIDPrefix = ($dash ? self::dashName($name) : $name) . '-';

        return $this;
    }

    public function getInputIDPrefix($key = null)
    {
        return ($key && $this->inputIDPrefix) ? $this->inputIDPrefix . $this->getInputID($key) : $this->getInputID($key);
    }

    public function setInputID(array $keyData = [], $dash = true)
    {
        if ( ! $this->inputIDPrefix)

            $this->setInputIDPrefix(null, $dash);

        foreach ($this->getInputKeys() as $k)
        {
            $this->input[$k]['id'] = $keyData[$k] ?? $this->getInputIDPrefix($k);
        }

        return $this;
    }

    public function getInputID($key)
    {
        return preg_replace('#[^a-z\d\-_]+#i', '', $this->input[$key]['id']);
    }

    protected function loadInputs()
    {
        if (empty($this->getModel()->fieldTypes()))

            throw new \Exception(sprintf('{%s} - attribute types array is empty.', $this->getModelName()));

        $this->setInputIDPrefix();

        $types = array_merge($this->getModel()->fieldTypes(), $this->getModel()->inputTypes());

        if ($safeInputs = $this->getModel()->getSafeInputs())

            $types = array_diff_key($types, array_flip($safeInputs));

        foreach ($types as $k => $type)
        {
            $this->setInput($k, null, $this->types[$type] ?? self::DEFAULT_INPUT_TYPE);

            $this->setLabel($k, $this->getFieldName($k));

            switch ($type)
            {
                case 'radio':
                case 'checkbox':
                    $this->setInputType($k, $type)->setInputOptions($k, []);
                    break;
                case 'select':
                    $this->setSelect($k, [])->setInputAttribute($k, ['type' => null]);
                    break;
                case 'textarea':
                    $this->setTextarea($k)->setInputAttribute($k, ['type' => null]);
                    break;
                case 'date':
                    $this->setDate($k);
                    break;
                case 'datetime':
                case 'timestamp':
                    $this->setDateTime($k);
                    break;
                case 'float':
                case 'double':
                case 'decimal':
                    $this->setInputType($k, 'number')->setInputAttribute($k, ['step' => '0.1']);
                    break;
                case 'hidden':
                    $this->hideInput($k);
                    break;
            }
        }

        return $this;
    }

    public function loadValues(array $values = [], callable $func = null)
    {
        foreach ($this->getInputKeys() as $k)
        {
            if (isset($values[$k]) == false && is_callable($func))

                $val = call_user_func($func, $k, $this);

            else

                $val = $values[$k] ?? $this->getInputValue($k);

            $this->setValue($k, $val);
        }

        return $this;
    }

    public function loadErrors(array $errors = [], callable $func = null)
    {
        foreach ($this->getInputKeys() as $k)
        {
            if (isset($errors[$k]) == false && is_callable($func))

                $err = call_user_func($func, $k, $this);

            else

                $err = $errors[$k] ?? ($this->getModel()->getErrorMessages()[$k] ?? null);

            $this->setError($k, $err);
        }

        return $this;
    }

    public function setError($key, $message)
    {
        $this->input[$key]['error'] = $message;

        return $this;
    }

    public function getError($key)
    {
        return $this->input[$key]['error'] ?? null;
    }

    public function getErrors()
    {
        return $this->getModel()->getErrorMessages();
    }

    public function unsetInput($key)
    {
        if ($this->checkInput($key))

            unset($this->input[$key]);

        if ($this->checkField($key))

            unset($this->fieldNames[$key]);

        return $this;
    }

    public function unsetInputs(array $keys)
    {
        foreach ($keys as $key)
        {
            $this->unsetInput($key);
        }

        return $this;
    }

    public function setToken($value, $name = '_token')
    {
        $this->token = [$name => $value];

        return $this;
    }

    public function getTokenValue()
    {
        return $this->token[$this->getTokenName()] ?? null;
    }

    public function getTokenName()
    {
        return $this->token ? key($this->token) : null;
    }

    public function inputUnwrap($key)
    {
        $this->setTemplate('{input}', $key);

        return $this;
    }

    public function hideInput($key, $value = null, $attr = [])
    {
        return $this->setInput($key, $value, 'hidden', $attr)->inputUnwrap($key);
    }

    public function hideInputs(array $keys)
    {
        foreach ($keys as $key)
        {
            if ($this->checkInput($key)) $this->hideInput($key);
        }

        return $this;
    }

    public function toggleInput($key, bool $value = false)
    {
        $this->setInputAttribute($key, ['disabled' => ! ! $value]);

        return $this;
    }

    public function toggleInputs(array $keyData)
    {
        foreach ($keyData as $key => $value)
        {
            if ($this->checkInput($key)) $this->toggleInput($key, boolval($value));
        }

        return $this;
    }

    public function setInput($key, $value = null, $type = null, array $attr = [])
    {
        $this->checkInput($key, true);

        $this->setInputTag($key, 'input');

        $this->setInputType($key, $type ?? self::DEFAULT_INPUT_TYPE, true);

        if ($attr)

            $this->setInputAttribute($key, $attr);

        $this->setValue($key, $value);

        return $this;
    }

    protected function setInputTag($key, $tag)
    {
        $this->input[$key]['tag'] = $tag;

        return $this;
    }

    public function getInputValue($key, $withDefault = true)
    {
        $val = $this->input[$key]['value'];

        return ($val === null && $withDefault) ? $this->getInputDefault($key) : $val;
    }

    public function isOptionalInput($key, $checkValues = false)
    {
        return (in_array($this->getInputType($key), ['checkbox', 'radio', 'select']) && ($checkValues ? ! empty($this->input[$key]['options']) : true));
    }

    public function setInputOptions($key, array $opt)
    {
        if ($this->isOptionalInput($key) == false)

            throw new \Exception(sprintf('Wrong input type for {%s}, the input type must be set to `checkbox` or `radio` or `select`.', $key));

        if (empty($opt) && $options = $this->getModel()->getInputOptions())
        {
            $opt = $options[$key] ?? [];
        }

        if ($this->getInputType($key) === 'checkbox' && ! $opt)
        {
            $this->unsetInput($key);

            return $this;
        }

        $this->input[$key]['options'] = ($this->getInputType($key) === 'radio' && sizeof($opt) == 0) ? ['No', 'Yes'] : $opt;

        return $this;
    }

    public function getInputOptions($key)
    {
        return $this->isOptionalInput($key, true) ? ($this->input[$key]['options'] ?? []) : null;
    }

    public function setRadio($key, array $options = [], $value = null, array $attr = [])
    {
        $this->checkInput($key, true);

        $value = is_array($value) ? array_values($value) : $value;

        $this->setInput($key, $value[0] ?? $value, 'radio', $attr);

        $this->setInputOptions($key, $options);

        return $this;
    }

    public function setCheckbox($key, array $options = [], $value = null, array $attr = [])
    {
        $this->checkInput($key, true);

        $this->setInput($key, $value, 'checkbox', $attr);

        $this->setInputOptions($key, $options);

        return $this;
    }

    public function setDate($key, $value = null, array $attr = [])
    {
        $this->checkInput($key, true);

        $value = $value ?: $this->getInputValue($key);

        $this->setInput($key, $value ? date('Y-m-d', strtotime($value)) : null, 'date', $attr);

        return $this;
    }

    public function setDateTime($key, $value = null, array $attrDate = [], array $attrTime = [])
    {
        $this->checkInput($key, true);

        $this->setDate($key, $value, $attrDate);

        $this->input[$key]['time'] = $value ? date('H:i:s', strtotime($value)) : null;

        $this->input[$key]['attr_time'] = $attrTime;

        return $this;
    }

    public function setInputType($key, $type, $setAttribute = true)
    {
        $this->input[$key]['type'] = $type;

        if ($setAttribute)

            $this->setInputAttribute($key, ['type' => $type]);

        return $this;
    }

    public function getInputType($key)
    {
        return $this->getInput($key)['type'] ?? null;
    }

    public function setInputDefault($key, $value)
    {
        $this->input[$key]['default'] = $value;

        return $this;
    }

    public function setInputDefaults(array $keyData)
    {
        foreach ($this->getInputKeys() as $k)
        {
            if (isset($keyData[$k]))

                $this->setInputDefault($k, $keyData[$k]);
        }

        return $this;
    }

    public function getInputDefault($key)
    {
        return $this->input[$key]['default'] ?? null;
    }

    public function checkInput($key, $create = false)
    {
        if ($create && false == isset($this->input[$key]))
        {
            $this->input[$key] = [
                'id'    => $key,
                'name'  => $key,
                'tag'   => null,
                'value' => null,
                'error' => null,
            ];

            $this->input[$key]['name'] = $this->getInputRequestName($key);

            $this->input[$key]['id'] = $this->getInputIDPrefix($key);

            $this->label[$key] = $this->getFieldName($key) ?: $key;
        }

        return isset($this->input[$key]);
    }

    public function getInput($key = null)
    {
        if ($key !== null)
        {
            if ($this->checkInput($key))

                return $this->input[$key];

            throw new \Exception(sprintf('key {%s} not exists.', $key));
        }

        return $this->input;
    }

    public function getInputKeys()
    {
        return array_keys($this->input);
    }

    public function setTextarea($key, $value = null, array $attr = [])
    {
        $this->checkInput($key, true);

        $this->setInputTag($key, 'textarea');

        if ($attr)

            $this->setInputAttribute($key, $attr);

        $this->setValue($key, $value);

        return $this;
    }

    public function setSelect($key, array $options, $prompt = null, $value = null, array $attr = [])
    {
        $this->checkInput($key, true);

        $this->setInputType($key, 'select', false);

        $this->setInputOptions($key, $options);

        $this->setInputTag($key, 'select');

        if ($attr)

            $this->setInputAttribute($key, $attr);

        $this->setValue($key, $value);

        $this->setSelectPrompt($key, $prompt);

        return $this;
    }

    public function setSelectPrompt($key, $prompt)
    {
        if (false == $this->isOptionalInput($key))

            return $this;

        if ($prompt === null)
        {
            if ($this->getSelectPrompt($key) !== null)

                unset($this->input[$key]['prompt']);

            return $this;
        }

        $this->input[$key]['prompt'] = $prompt;

        return $this;
    }

    public function getSelectPrompt($key)
    {
        return $this->input[$key]['prompt'] ?? null;
    }

    public function setLabel($key, $name = null, array $attr = [])
    {
        $this->checkInput($key, true);

        if ($name) $this->label[$key] = $name;

        if ($attr) $this->labelAttributes[$key] = $this::setAttribute($this->labelAttributes, $attr);

        return $this;
    }

    public function getLabel($key)
    {
        return $this->label[$key] ?? null;
    }

    public function getLabelAttributes($key)
    {
        return $this->labelAttributes[$key] ?? [];
    }

    public function getLabels()
    {
        return $this->label;
    }

    public function setValue($key, $value = null)
    {
        $this->input[$key]['value'] = $value !== null ? $value : $this->getModel($key);

        return $this;
    }

    public function unsetValue($key)
    {
        $this->input[$key]['value'] = null;

        return $this;
    }

    public function unsetValues(array $data)
    {
        foreach ($data as $k)
        {
            if ($this->checkInput($k)) $this->unsetValue($k);
        }

        return $this;
    }

    public function resetForm()
    {
        return $this->unsetValues($this->getInputKeys());
    }

    public function setAllInputsAttribute(array $attr, array $exceptTypes = [], array $exceptKeys = [])
    {
        foreach ($this->getInputKeys() as $k)
        {
            if ($exceptTypes && in_array($this->getInputType($k), $exceptTypes))

                continue;

            if ($exceptKeys && in_array($k, $exceptKeys))

                continue;

            $this->setInputAttribute($k, $attr);
        }

        return $this;
    }

    public function checkGetInputAttribute($key)
    {
        return (isset($this->inputAttributes[$key]) && is_array($this->inputAttributes[$key])) ? $this->inputAttributes[$key] : [];
    }

    public function setInputAttribute($key, array $attr)
    {
        $this->inputAttributes[$key] = self::setAttribute($this->checkGetInputAttribute($key), $attr);

        return $this;
    }

    public function getInputAttribute($key, string $attribute = null)
    {
        return $attribute ? ($this->inputAttributes[$key][$attribute] ?? []) : $this->checkGetInputAttribute($key);
    }

    public function render($template = 'grid-form/form.php')
    {
        return parent::render($template);
    }

}
