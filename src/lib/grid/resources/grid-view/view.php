<?php

/* @var \lib\grid\GridView $this */

$tagAttr = $this->getTagAttributes();

if ( ! trim($this->getTemplate()))
{
    switch ($this->getTag())
    {
        case 'ol':
        case 'ul':

            $t = '<li %s><div>%s</div><div>%s</div></li>';

            $tagAttr = $tagAttr ?: ['class' => ['list-unstyled']];

            break;
        case 'div':

            $t = '<div %s><div>%s</div><div>%s</div></div>';

            break;
        default:

            $t = '<tr %s><td>%s</td><td>%s</td></tr>';

            $tagAttr = $tagAttr ?: ['class' => ['table', 'table-striped', 'table-bordered']];

            break;
    }

    $this->setTemplate(sprintf($t, '{attr}', '{name}', '{row}'));
}

$output = $this->getTagTemplate() ?: '<{tag} {attr}>{rows}</{tag}>';

$rows = '';

foreach (array_keys(array_merge($this->getFieldNames(), $this->row)) as $k)
{
    $tr = [
        '{name}' => $this->getFieldName($k),
        '{row}'  => $this->getModel($k),
        '{attr}' => $this->getRowAttributes(),
    ];

    if ($this->checkRow($k))
    {
        $row = $this->getRow($k, ['row' => $tr]);

        if (is_array($row))
        {
            $tr = array_merge($tr, $row);
        }
        else
        {
            $tr['{row}'] = $row;
        }
    }

    $tr['{attr}'] = is_array($tr['{attr}']) ? $this::getAttributes($tr['{attr}']) : $tr['{attr}'];

    $rows .= strtr($this->checkRowTemplate($k) ? $this->getRowTemplate($k) : $this->getTemplate(), $tr);
}

echo strtr($output, [
    '{tag}'  => $this->getTag(),
    '{attr}' => $this::getAttributes($tagAttr),
    '{rows}' => $rows
]);
