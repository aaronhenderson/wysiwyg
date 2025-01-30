<?php

/**
 * @param string $template
 * @return string
 */
function template($template){
    $html = file_get_contents('partials/'. $template . '.html');
    foreach ($_POST as $id => $value){
        if(is_string($id) && is_string($value)){
            $html = str_replace(
                'name="' . $id . '"',
                'name="' . $id . '" value="' . $value . '"',
                $html);
        }
    }
    $html = str_replace('consent is measured', '<strong>consent is measured</strong>', $html);
    return $html;
}

/**
 * @param string $template
 * @param array $vars
 * @return string
 */
function php_template($template, $vars = array()){
    ob_start();
    extract($vars);
    require_once $template;
    $haxtml = ob_get_contents();
    ob_end_clean();

    return $haxtml;
}
