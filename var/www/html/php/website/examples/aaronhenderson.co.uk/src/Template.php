<?php
namespace AaronHenderson;

class Template {

    /**
     * Directory to load templates from
     */
    const TEMPLATES_DIRECTORY = 'storage/templates/';


    /**
     * Fetch a template
     *
     * @param string $template
     * @param array $data
     * @return string
     * @throws Exception
     */
    public static function fetch($template, array $data = array())
    {
        if(!file_exists(self::TEMPLATES_DIRECTORY . $template)) {
            throw new \Exception('Unable to find template: ' . self::TEMPLATES_DIRECTORY . $template);
        }

        $html = file_get_contents(self::TEMPLATES_DIRECTORY . $template, true);

        return self::replace($html, $data);
    }


    /**
     * Replace variables in a string using an array of data
     *
     * @param string $string
     * @param array $data
     * @param string $key_prepend
     * @param string $key_append
     * @return mixed
     */
    public static function replace($string, array $data = array(), $key_prepend = '<!-- ', $key_append = ' -->')
    {
        foreach ($data as $key => $value) {
            $string = str_replace($key_prepend . $key . $key_append, $value, $string);
        }

        return $string;
    }
}