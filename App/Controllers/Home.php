<?php

namespace App\Controllers;

use \Core\View;

/**
 * Home controller
 *
<<<<<<< HEAD
 * PHP version 7.0
=======
 * PHP version 5.4
>>>>>>> 40af4e4a56966a0ccb88f2ec1ebf37dd27385227
 */
class Home extends \Core\Controller
{

    /**
<<<<<<< HEAD
=======
     * Before filter
     *
     * @return void
     */
    protected function before()
    {
        //echo "(before) ";
        //return false;
    }

    /**
     * After filter
     *
     * @return void
     */
    protected function after()
    {
        //echo " (after)";
    }

    /**
>>>>>>> 40af4e4a56966a0ccb88f2ec1ebf37dd27385227
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
<<<<<<< HEAD
        View::renderTemplate('Home/index.html');
=======
        /*
        View::render('Home/index.php', [
            'name'    => 'Dave',
            'colours' => ['red', 'green', 'blue']
        ]);
        */
        View::renderTemplate('Home/index.html', [
            'name'    => 'Dave',
            'colours' => ['red', 'green', 'blue']
        ]);
>>>>>>> 40af4e4a56966a0ccb88f2ec1ebf37dd27385227
    }
}
