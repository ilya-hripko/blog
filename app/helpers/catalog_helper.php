<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


if (!function_exists('ru_to_en')) {

    function ru_to_en($str) {
        $trans = array("а" => "a", "б" => "b", "в" => "v", "г" => "g", "д" => "d", "е" => "e", "ё" => "yo", "ж" => "j", "з" => "z", "и" => "i", "й" => "i", "к" => "k", "л" => "l", "м" => "m", "н" => "n", "о" => "o", "п" => "p", "р" => "r", "с" => "s", "т" => "t", "у" => "u", "ф" => "f", "х" => "h", "ц" => "c", "ч" => "ch", "ш" => "sh", "щ" => "sh", "ы" => "i", "э" => "e", "ю" => "u", "я" => "ya",
            "А" => "A", "Б" => "B", "В" => "V", "Г" => "G", "Д" => "D", "Е" => "E", "Ё" => "Yo", "Ж" => "J", "З" => "Z", "И" => "I", "Й" => "I", "К" => "K", "Л" => "L", "М" => "M", "Н" => "N", "О" => "O", "П" => "P", "Р" => "R", "С" => "S", "Т" => "T", "У" => "U", "Ф" => "F", "Х" => "H", "Ц" => "C", "Ч" => "Ch", "Ш" => "Sh", "Щ" => "Sh", "Ы" => "I", "Э" => "E", "Ю" => "U", "Я" => "Ya",
            "ь" => "", "Ь" => "", "ъ" => "", "Ъ" => ""
        );
        return strtr($str, $trans);
    }

}

if (!function_exists('mb_ucfirst')) {

    function mb_ucfirst($text) {
        return mb_strtoupper(mb_substr($text, 0, 1)) . mb_substr($text, 1);
    }

}

if (!function_exists('get_file_mime')) {

    function get_file_mime($file) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
        $info = finfo_file($finfo, $file);
        finfo_close($finfo);
        return $info;
    }

}

function friendly_url($str, $separator = 'dash', $lowercase = TRUE) {
    if (!$str) {
        return '';
    }
    if (preg_match('![а-я]!si', $str))
        $str = ru_to_en($str);
    if ($separator == 'dash') {
        $search = '_';
        $replace = '-';
    } else {
        $search = '-';
        $replace = '_';
    }

    $trans = array(
        '&\#\d+?;' => '',
        '&\S+?;' => '',
        '\s+' => $replace,
        '[^a-z0-9\-\._]' => '',
        $replace . '+' => $replace,
        $replace . '$' => $replace,
        '^' . $replace => $replace,
        '\.+$' => ''
    );
    $str = str_replace(array(',', '.'), ' ', $str);
    $str = trim($str);
    $str = strip_tags($str);

    foreach ($trans as $key => $val) {
        $str = preg_replace("#" . $key . "#i", $val, $str);
    }

    if ($lowercase === TRUE) {
        $str = strtolower($str);
    }

    return trim(stripslashes($str));
}


function generate_url($type, $param, $base_url = false) {
    if (!$base_url)
        $base_url = base_url();
    $url = '';
    switch ($type) {
        case 'blogCategory':
            $url = $base_url.'category-'.friendly_url($param['name']).'-'.$param['id'].'/';
            break;
        case 'blogPost':
            $url = $base_url.'post-'.friendly_url($param['title']).'-'.$param['id'].'/';
            break;

        default:
            $url = $base_url;
            break;
    }
    return $url;
}


/* End of file catalog_helper.php */
/* Location: ./app/helpers/catalog_helper.php */

