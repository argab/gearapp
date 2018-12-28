<?php

namespace lib\grid;

use lib\grid\IGridProvider;
use lib\grid\IGridFormProvider;
use lib\grid\IGrid;

abstract class Grid implements IGrid
{
    protected $model;

    protected $fieldNames = [];

    protected $row = [];

    protected $viewPath;

    protected $template = null;

    protected $rowTemplate;

    protected $tagTemplate;

    protected $tag = '';

    protected $tagAttributes = [];

    protected $rowAttributes = [];

    public function __construct(IGridProvider $model)
    {
        $this->model = $model;

        $this->setFieldNames($model->fieldNames());
    }

    /**
     * @param null $key
     * @param integer $index
     *
     * @return IGridProvider|IGridFormProvider|null
     */
    public function getModel($key = null, int $index = null)
    {
        if ($index !== null && isset($this->model[$index]))

            return $key ? ($this->model[$index]->{$key} ?? null) : $this->model[$index];

        return $key ? ($this->model->{$key} ?? null) : $this->model;
    }

    public function getModelName()
    {
        return (new \ReflectionClass($this->getModel()))->getShortName();
    }

    public static function dashName($name)
    {
        return trim(strtolower(preg_replace('/([A-Z])/', '-$1', $name)), '-');
    }

    public function checkField($key)
    {
        return isset($this->fieldNames[$key]);
    }

    public function setFieldNames(array $data)
    {
        $this->fieldNames = array_merge($this->fieldNames, $data);
    }

    public function getFieldNames()
    {
        return $this->fieldNames;
    }

    public function getFieldName($key)
    {
        return $this->fieldNames[$key] ?? null;
    }

    public function setRow($key, $val, $template = null)
    {
        $this->row[$key] = $val;

        if ($template !== null)

            $this->setTemplate($template, $key);

        return $this;
    }

    public function getRows()
    {
        return $this->row;
    }

    public function checkRow($key)
    {
        return isset($this->row[$key]);
    }

    public function getRow($key, array $data = [])
    {
        if (is_callable($this->row[$key]))

            return call_user_func_array($this->row[$key], array_merge($data, [
                $this->getModel($key, $data['index'] ?? null),
                $this->getModel(),
                $this
            ]));

        return $this->row[$key];
    }

    public function unsetFilds(array $rows)
    {
        foreach ($rows as $row)
        {
            if ($this->checkField($row)) unset($this->fieldNames[$row]);
        }

        return $this;
    }

    public function setViewPath($path)
    {
        $this->viewPath = rtrim($path, '/..\\') . '/';

        return $this;
    }

    public function getViewPath()
    {
        return $this->viewPath;
    }

    public function setTemplate($template, $rowKey = null)
    {
        if ($rowKey)
        {
            $this->rowTemplate[$rowKey] = $template;

            return $this;
        }

        $this->template = $template;

        return $this;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function checkRowTemplate($key)
    {
        return isset($this->rowTemplate[$key]);
    }

    public function getRowTemplate($key)
    {
        return $this->rowTemplate[$key];
    }

    public function setTagTemplate($template)
    {
        $this->tagTemplate = $template;

        return $this;
    }

    public function getTagTemplate()
    {
        return $this->tagTemplate;
    }

    public function render($template)
    {
        $path = $this->getViewPath() ?: dirname(__FILE__) . '/resources/';

        ob_start();

        include($path . trim($template, '/..\\'));

        return ob_get_clean();
    }

    public static function setAttribute(array $src, array $attr)
    {
        foreach ($attr as $k => $v)
        {
            if (isset($src[$k]) && ($v === '' || $v === false || $v === null))
            {
                unset($src[$k]);

                continue;
            }

            if (is_array($v))
            {
                if (isset($src[$k]) == false)

                    $src[$k] = $v;

                else

                    $src[$k] = array_merge(is_array($src[$k]) ? $src[$k] : explode("\x20", $src[$k]), $v);

                foreach ($src[$k] as $kk => $vv)
                {
                    if ($vv === '' || $vv === false || $vv === null)
                    {
                        if (($key = array_search($kk, $src[$k])) !== false)

                            unset($src[$k][$key]);

                        unset($src[$k][$kk]);
                    }
                }

                if (sizeof($src[$k]) == 0)

                    unset($src[$k]);

                continue;
            }

            $src[$k] = $v;
        }

        return $src;
    }

    public static function getAttributes(array $src): string
    {
        $output = [];

        foreach ($src as $k => $v)
        {
            $inn = [];

            if (is_array($v))
            {
                foreach ($v as $kk => $vv)
                {
                    switch ($k)
                    {
                        case 'data':

                            $output[] = sprintf('data-%s="%s"', $kk, $vv);

                            break;

                        case 'style':

                            $inn[] = sprintf('%s:%s;', $kk, $vv);

                            break;

                        default:

                            $inn[] = $vv;

                            break;
                    }
                }

                if ($k === 'data')

                    continue;
            }

            $output[] = sprintf('%s="%s"', $k, $inn ? join("\x20", $inn) : $v);
        }

        return join("\x20", $output);
    }

    public static function checkAttribute(array $src, array $attrData)
    {
        $check = [];

        foreach ($attrData as $k => $attr)
        {
            if (false == isset($src[$k]))
            {
                $check[$k] = false;

                continue;
            }

            if (is_array($attr))
            {
                $check[$k] = [];

                foreach ($attr as $kk => $vv)
                {
                    if (is_array($src[$k]) && (false == isset($src[$k][$kk]) || $src[$k][$kk] !== $vv))
                    {
                        $check[$k][$kk] = false;

                        continue;
                    }

                    foreach ((array) $vv as $vvv)
                    {
                        if (strpos($src[$k], $vvv) === false)
                        {
                            $check[$k] = false;
                        }
                    }
                }
            }
        }

        return $check;
    }

    public function setTag($tag)
    {
        $this->tag = $tag;

        return $this;
    }

    public function getTag(): string
    {
        return $this->tag;
    }

    public function setTagAttributes(array $attr)
    {
        $this->tagAttributes = self::setAttribute($this->tagAttributes, $attr);

        return $this;
    }

    public function getTagAttributes($attribute = null)
    {
        return $attribute ? ($this->tagAttributes[$attribute] ?? []) : $this->tagAttributes;
    }

    public function setRowAttributes(array $attr)
    {
        $this->rowAttributes = self::setAttribute($this->rowAttributes, $attr);

        return $this;
    }

    public function getRowAttributes($attribute = null)
    {
        return $attribute ? ($this->rowAttributes[$attribute] ?? []) : $this->rowAttributes;
    }

}
