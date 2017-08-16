<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Withdraws\Addons\Withdraws\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\KazistFactory;
use Kazist\Service\Database\Query;

class WithdrawsModel {

    public function getWithdraws() {

        $factory = new KazistFactory();

        $query = $factory->getQueryBuilder('#__withdraws_withdraws', 'ww');

        $query->setFirstResult(0);
        $query->setMaxResults(10);

        $records = $query->loadObjectList();

        return $records;
    }

    public function getWithdrawSetting() {

        $factory = new KazistFactory();

        $query = $factory->getQueryBuilder('#__withdraws_settings', 'wws');

        $query->setFirstResult(0);
        $query->setMaxResults(10);

        $records = $query->loadObjectList();

        return $records;
    }

}
