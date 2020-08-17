<?php

namespace Core;

/**
 * View
 *
<<<<<<< HEAD
 * PHP version 7.0
=======
 * PHP version 5.4
>>>>>>> 40af4e4a56966a0ccb88f2ec1ebf37dd27385227
 */
class View
{

    /**
     * Render a view file
     *
     * @param string $view  The view file
     * @param array $args  Associative array of data to display in the view (optional)
     *
     * @return void
     */
    public static function render($view, $args = [])
    {
        extract($args, EXTR_SKIP);

<<<<<<< HEAD
        $file = dirname(__DIR__) . "/App/Views/$view";  // relative to Core directory
=======
        $file = "../App/Views/$view";  // relative to Core directory
>>>>>>> 40af4e4a56966a0ccb88f2ec1ebf37dd27385227

        if (is_readable($file)) {
            require $file;
        } else {
            throw new \Exception("$file not found");
        }
    }

    /**
     * Render a view template using Twig
     *
     * @param string $template  The template file
     * @param array $args  Associative array of data to display in the view (optional)
     *
     * @return void
     */
    public static function renderTemplate($template, $args = [])
    {
        static $twig = null;

        if ($twig === null) {
<<<<<<< HEAD
            $loader = new \Twig\Loader\Filesystemloader(dirname(__DIR__) . '/App/Views');
            $twig = new \Twig\Environment($loader);
=======
            $loader = new \Twig_Loader_Filesystem('../App/Views');
            $twig = new \Twig_Environment($loader);
>>>>>>> 40af4e4a56966a0ccb88f2ec1ebf37dd27385227
        }

        echo $twig->render($template, $args);
    }
}
