<?php

if (! function_exists('redirect_if')) {
    /**
     * Redirect to the given route,url, or action if the given condition is true.
     *
     * @param  mixed  $condition
     * @param  string  $route
     * @param  array  $parameters
     *
     * @return mixed
     */
    function redirect_if($condition, $route, ?array $parameters = [])
    {
        if (! $condition) {
            return;
        }

        return $parameters ? redirect($route)->with($parameters) : redirect($route);
    }
}

if (! function_exists('redirect_unless')) {
    /**
     * Redirect to the given route,url, or action unless the given condition is true.
     *
     * @param  mixed  $condition
     * @param  string  $route
     * @param  array  $parameters
     *
     * @return mixed
     */
    function redirect_unless($condition, $route, ?array $parameters = [])
    {
        if ($condition) {
            return;
        }

        return $parameters ? redirect($route)->with($parameters) : redirect($route);
    }
}

if (! function_exists('validate')) {
    /**
     * Validate some data.
     *
     * @param string|array $fields
     * @param string|array $rules
     * @param string|array $messages
     *
     * @return bool
     */
    function validate($fields, $rules, $messages = '')
    {
        if (! is_array($fields)) {
            $fields = ['default' => $fields];
        }

        if (! is_array($rules)) {
            $rules = ['default' => $rules];
        }

        if (! is_array($messages)) {
            $messages = ['default' => $messages];
        }

        return Validator::make($fields, $rules, $messages);
    }
}
