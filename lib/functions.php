<?php

function safepath($name) {
    return strtolower(preg_replace('/\\.+/','',
        preg_replace('/^\\//','',
        preg_replace('/\\/{2,}/','/',
            $name))));
}
