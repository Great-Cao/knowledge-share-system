<?php

namespace AppBundle\Common;

class TimeToolKit
{
    static public function arrayToDetailTime($values)
    {
        if (empty($values)) {
            return array();
        }

        $fields = array();
        foreach ($values as $key => $value) {
            if (array_key_exists('createdTime', $value)) {
                $value['createdTime'] = date('Y-m-d H:i:s', $value['createdTime']);
            } elseif (array_key_exists('updateTime', $value)) {
                $value['updateTime'] = date('Y-m-d H:i:s', $value['updateTime']);
            }
           $fields[$key] = $value; 
        }
        return $fields;
    }

    static public function arrayToTime($values)
    {
        if (empty($values)) {
            return array();
        }

        $fields = array();
        foreach ($values as $key => $value) {
            if (array_key_exists('createdTime', $value)) {
                $value['createdTime'] = date('Y-m-d', $value['createdTime']);
            } elseif (array_key_exists('updateTime', $value)) {
                $value['updateTime'] = date('Y-m-d', $value['updateTime']);
            }
           $fields[$key] = $value; 
        }
        return $fields;
    }

}