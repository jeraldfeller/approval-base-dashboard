<?php

namespace Aiden\Controllers;

use Aiden\Models\Das;
use Aiden\Models\DasUsers;
use Aiden\Models\DasUsersSearch;
use Aiden\Models\Users;
use Aiden\Models\Councils;

class SearchController extends _BaseController {

    public function indexAction() {
        $dasMaxCost = Das::find([
            'conditions' => '1=1 ORDER BY estimated_cost DESC LIMIT 1'
        ]);

        $councils = Councils::find([
            'conditions' => '1=1 ORDER BY name ASC'
        ]);

        $dasMinDate = Das::find([
            'conditions' => '1=1 ORDER BY created ASC LIMIT 1'
        ]);

        $this->view->setVars([
            'page_title' => 'Search',
            "defaultDateRange" => array(date('m/d/Y', strtotime('-1 year')), date('m/d/Y', strtotime('+ 1 days'))),
            "maxCost" => 100000000,
            "maxCostValue" => $dasMaxCost[0]->getEstimatedCost(),
            "councils" => $councils,

        ]);

        $this->view->pick('search/search');
    }

    public function saveAction() {
        $leadId = $this->request->getPost('leadId');
        $userId = $this->getUser()->getId();
        $status = $this->request->getPost('status');
        return json_encode(DasUsersSearch::updateSavedSearch($userId, $leadId, $status));
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
