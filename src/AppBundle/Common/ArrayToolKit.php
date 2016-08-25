<?php

namespace AppBundle\Common;

class ArrayToolKit
{
    public static function column(array $array, $columnName)
    {
        if (empty($array)) {
            return array();
        }

        $column = array();

        foreach ($array as $item) {
            if (isset($item[$columnName])) {
                $column[] = $item[$columnName];
            }
        }

        return $column;
    }

    public static function parts(array $array, array $keys)
    {
        foreach (array_keys($array) as $key) {
            if (!in_array($key, $keys)) {
                unset($array[$key]);
            }
        }

        return $array;
    }

    public static function requireds(array $array, array $keys)
    {
        foreach ($keys as $key) {
            if (!array_key_exists($key, $array)) {
                return false;
            }
        }

        return true;
    }

    public static function changes(array $before, array $after)
    {
        $changes = array('before' => array(), 'after' => array());

        foreach ($after as $key => $value) {
            if (!isset($before[$key])) {
                continue;
            }

            if ($value != $before[$key]) {
                $changes['before'][$key] = $before[$key];
                $changes['after'][$key]  = $value;
            }
        }

        return $changes;
    }

    public static function group(array $array, $key)
    {
        $grouped = array();

        foreach ($array as $item) {
            if (empty($grouped[$item[$key]])) {
                $grouped[$item[$key]] = array();
            }

            $grouped[$item[$key]][] = $item;
        }

        return $grouped;
    }

    public static function index(array $array, $name)
    {
        $indexedArray = array();

        if (empty($array)) {
            return $indexedArray;
        }

        foreach ($array as $item) {
            if (isset($item[$name])) {
                $indexedArray[$item[$name]] = $item;
                continue;
            }
        }

        return $indexedArray;
    }

    public static function rename(array $array, array $map)
    {
        $keys = array_keys($map);

        foreach ($array as $key => $value) {
            if (in_array($key, $keys)) {
                $array[$map[$key]] = $value;
                unset($array[$key]);
            }
        }

        return $array;
    }

    public static function filter(array $array, array $specialValues)
    {
        $filtered = array();

        foreach ($specialValues as $key => $value) {
            if (!array_key_exists($key, $array)) {
                continue;
            }

            if (is_array($value)) {
                $filtered[$key] = (array) $array[$key];
            } elseif (is_int($value)) {
                $filtered[$key] = (int) $array[$key];
            } elseif (is_float($value)) {
                $filtered[$key] = (float) $array[$key];
            } elseif (is_bool($value)) {
                $filtered[$key] = (bool) $array[$key];
            } else {
                $filtered[$key] = (string) $array[$key];
            }

            if (empty($filtered[$key])) {
                $filtered[$key] = $value;
            }
        }

        return $filtered;
    }

    /**
     * 二维数组排序, 目前只支持对key进行排序
     * {
     *      {
     *          id => 1,
     *          name => 'hello'
     *      }, {
     *          id => 2,
     *          name => 'world'
     *      }
     * }
     * 可对上述数组按照 id 或 name进行升序或降序排序
     * @param  $sortedKey  key的属性, 上述array 可以用 id 或 name 进行排序
     * @param  $isAscending  true 表示升序, 默认为升序
     */
    public static function sortTwoDimensionArray($array, $sortedKey, $isAscending = true)
    {
        $canSort = true;
        foreach ($array as $key => $value) {
            if (isset($value[$sortedKey])) {
                $array[$key]['array_sortedKey'] = $sortedKey;
                $array[$key]['array_isAscending'] = $isAscending;
            } else {
                $canSort = false;
                break;
            }
        }

        if ($canSort) {
            usort($array, 
                function($element, $compared) {
                    $elementSortedKeyValue = $element[$element['array_sortedKey']];
                    $comparedSortedKeyValue = $compared[$compared['array_sortedKey']];
                    $isAscending = $element['array_isAscending'];
                    if ($elementSortedKeyValue == $comparedSortedKeyValue) {
                        return 0;
                    }

                    $before = -1;
                    $after    = 1;
                    if ($isAscending) {
                        return $elementSortedKeyValue > $comparedSortedKeyValue ? $after : $before;  //升序时, 大的放后面
                    } else {
                        return $elementSortedKeyValue > $comparedSortedKeyValue ? $before : $after;  //降序时, 大的放前面
                    }
                }
            );
        }
        return $array;
    }

}
