<?php

/*
 * This file is part of Kazist Framework.
 * (c) Dedan Irungu <irungudedan@gmail.com>
 *  For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 * 
 */

/**
 * Description of SettingsController
 *
 * @author sbc
 */

namespace Withdraws\Settings\Code\Controllers;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\KazistFactory;
use Kazist\Controller\BaseController;

class SettingsController extends BaseController {

    public function addAction() {

        $factory = new KazistFactory();

        $setting_id = $this->model->getWithdrawSetting();

        if ($setting_id) {
            return $this->redirectToRoute('withdraws.settings.edit', array('id' => $setting_id));
        }

        $user = $factory->getUser();
        $gateways = $this->model->getWithdrawGateways();

        $this->data_arr['user'] = $user;
        $this->data_arr['gateways'] = $gateways;

        return parent::addAction();
    }

    public function editAction($id = '') {

        if (!$id) {
            $setting_id = $this->model->getWithdrawSetting();
            return $this->redirectToRoute('withdraws.settings.edit', array('id' => $setting_id));
        }


        $gateways = $this->model->getWithdrawGateways();

        $this->data_arr['gateways'] = $gateways;

        return parent::editAction($id);
    }

}
