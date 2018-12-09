<?php
/**
 * Created by PhpStorm.
 * User: Grabe Grabe
 * Date: 12/9/2018
 * Time: 1:00 PM
 */

namespace Aiden\Controllers;


class PoiController extends _BaseController
{
    public function indexAction() {

        $this->view->setVars([
            'page_title' => 'Point of Interest',
        ]);

        $this->view->pick('poi/index');
    }

    public function primaryAction() {

        $this->view->setVars([
            'page_title' => 'Point of Interest',
        ]);

        $this->view->pick('poi/index');
    }

}