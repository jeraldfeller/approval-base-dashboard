<?php

namespace Aiden\Controllers;

use Aiden\Models\Das;
use Aiden\Models\DasUsers;
use Aiden\Models\Users;
use Aiden\Models\Councils;

class IndexController extends _BaseController {

    public function indexAction() {
        $boundParams = [];

        $das = Das::find([
                    'conditions' => '1=1 ORDER BY created DESC'
        ]);

        $paginator = new \Phalcon\Paginator\Adapter\Model([
            'data' => $das,
            'limit' => $this->config->dashboardEntriesLimit,
            'page' => $this->request->getQuery('page', 'int')
        ]);

        $this->view->setVars([
            "page" => $paginator->getPaginate(),
            "totalLeads" => Das::find("status = " . Das::STATUS_LEAD)->count(),
            "totalSavedLeads" => DasUsers::find([
                'conditions' => 'status = :status: AND users_id = :users_id:',
                'bind' => [
                    'status' => DasUsers::STATUS_SAVED,
                    'users_id' => $this->getUser()->getId()
                ]
            ])->count(),
        ]);

        $this->view->pick('index/index');

    }


    public function makeMeAdminAction() {

        $user = $this->getUser();
        $user->setLevel(Users::LEVEL_ADMINISTRATOR);
        if ($user->save()) {
            die("You are now an administrator.");
        }
        else {
            die("You are not an administrator, something went wrong.");
        }

    }

}
