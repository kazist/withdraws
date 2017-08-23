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

        $query = $factory->getQueryBuilder('#__withdraws_withdraws', 'ww', array('ww.paid_status IS NUll OR ww.paid_status = \'\''));

        $query->setFirstResult(0);
        $query->setMaxResults(10);

        $records = $query->loadObjectList();

        return $records;
    }

    public function getWithdrawSetting() {

        $factory = new KazistFactory();

        $query = $factory->getQueryBuilder('#__withdraws_settings_gateways', 'wsg', array('wsg.is_valid IS NUll OR wsg.is_valid = \'\''));
        $query->addSelect('ws.pin');
        $query->addSelect('ws.id_passport');
        $query->setFirstResult(0);
        $query->setMaxResults(10);

        $records = $query->loadObjectList();

        return $records;
    }

}
