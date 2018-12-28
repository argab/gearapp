<?php

/* @var \lib\grid\GridForm $this */

if ($this->getTemplate() === null)

    $this->setTemplate('<div {attr}>{label}{input}</div>');

$output = $this->getTagTemplate() ?: '<{tag} {attr}>{token}{rows}</{tag}>';

$rows = '';

foreach (array_keys(array_merge($this->getFieldNames(), $this->getInput(), $this->getRows())) as $k)
{
    $labelTpl = null;

    $inpID = $this->checkInput($k) ? $this->getInputID($k) : null;

    if ($labelName = $this->getLabel($k))
    {
        $labelTpl = sprintf(
            '<label for="%s" %s>%s</label>',
            $inpID,
            $this::getAttributes($this->getLabelAttributes($k) ?: ['class' => ['control-label']]),
            $labelName
        );
    }

    $tr = [
        '{attr}'  => $this->getRowAttributes() ?: ['class' => ['form-group']],
        '{id}'    => $inpID,
        '{label}' => $labelTpl,
        '{input}' => null
    ];

    $template = $this->checkRowTemplate($k) ? $this->getRowTemplate($k) : $this->getTemplate();

    if ($this->checkRow($k))
    {
        $row = $this->getRow($k, ['row' => $tr]);

        if (is_array($row))
        {
            $tr = array_merge($tr, $row);
        }
        else
        {
            $tr['{input}'] = $row;
        }
    }


    if ($this->checkInput($k) && $tr['{input}'] === null)
    {
        $input = null;

        $tpl = '';

        $_type = $this->getInputType($k);

        if ($_type && $_type !== 'radio' && $_type !== 'checkbox' && false == isset($this->getInputAttribute($k)['class']))

            $this->setInputAttribute($k, ['class' => ['form-control']]);

        $data = [
            'tag'   => $this->getInput($k)['tag'],
            'type'  => $_type,
            'id'    => $inpID,
            'attr'  => $this->getInputAttribute($k),
            'name'  => $this->getInputName($k),
            'value' => $this->getInputValue($k),
            'error' => $this->getInput($k)['error'],
        ];

        $tplTime = $tplError = '';

        switch ($data['tag'])
        {
            case 'textarea':

                $tpl = '<textarea id="%s" name="%s" %s>%s</textarea>';

                break;

            case 'select':

                $tpl = '<select id="%s" name="%s" %s>%s</select>';

                $value = $data['value'];

                $data['value'] = '';

                if ($options = $this->getInputOptions($k))
                {
                    $prompt = $this->getInput($k)['prompt'] ?? null;

                    $tplOption = '<option value="%s" %s>%s</option>';

                    if ($prompt !== null)

                        $data['value'] .= sprintf($tplOption, '', '', $prompt);

                    foreach ((array) $options as $key => $val)
                    {
                        $sel = (string) $key === (string) $value ? 'selected' : null;

                        $data['value'] .= sprintf($tplOption, $key, $sel, $val);
                    }
                }

                break;

            default:

                $tpl = '<input id="%s" name="%s" %s value="%s">';

                if ($options = $this->getInputOptions($k))
                {
                    $input = [];

                    $tpl = sprintf('<li>%s</li>', $tpl . "\x20" . '%s');

                    foreach ($options as $key => $val)
                    {
                        $_name = '';

                        $_attr = $data['attr'];

                        $_checked = is_array($data['value'])

                            ? (in_array($key, $data['value']) ? 1 : 0) : ((string) $data['value'] === (string) $key ? 1 : 0);

                        if ($_checked)

                            $_attr['checked'] = 1;

                        if ($data['type'] === 'checkbox')

                            $_name = sprintf('%s[%s]', $data['name'], $key);

                        $input[] = sprintf(
                            $tpl,
                            $data['id'] . '-' . $key,
                            $_name ?: $data['name'],
                            $this::getAttributes($_attr),
                            $key,
                            $val
                        );
                    }

                    $input = sprintf('<ul class="list-unstyled">%s</ul>', join('', $input));
                }

                break;
        }

        if ($data['error'] !== null)
        {
            $tplError = sprintf('<p class="block-error">%s</p>', $data['error']);

            if (is_array($tr['{attr}']))

                $tr['{attr}'] = $this::setAttribute($tr['{attr}'], ['class' => ['has-error']]);
        }

        if ($data['type'] === 'date')
        {
            $time = $this->getInput($k)['time'] ?? null;

            $attr = array_merge($data['attr'], $this->getInput($k)['attr_time'] ?? []);

            $attr['type'] = 'time';

            if (false == isset($attr['style']['width'], $attr['style']['max-width'], $attr['style']['min-width']))

                $attr['style']['max-width'] = $data['attr']['style']['max-width'] = '150px';

            $tplTime = sprintf($tpl, $data['id'] . '-time', $data['name'] . '[time]', $this::getAttributes($attr), $time);

            $data['name'] .= '[date]';

            $data['id'] .= '-date';
        }

        if ($input === null && $tpl)

            $input = sprintf($tpl, $data['id'], $data['name'], $this::getAttributes($data['attr']), $data['value']);

        $input .= $tplTime . $tplError;

        $tr['{input}'] = $input;
    }

    $tr['{attr}'] = is_array($tr['{attr}']) ? $this::getAttributes($tr['{attr}']) : $tr['{attr}'];

    $rows .= strtr($template, $tr);
}

$token = $this->getTokenValue()
    ? sprintf('<input type="hidden" name="%s" value="%s">', $this->getTokenName(), $this->getTokenValue())
    : null;

echo strtr($output, [
    '{tag}'   => $this->getTag(),
    '{attr}'  => $this::getAttributes($this->getTagAttributes()),
    '{token}' => $token,
    '{rows}'  => $rows
]);
