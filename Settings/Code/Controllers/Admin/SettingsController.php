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

namespace Withdraws\Settings\Code\Controllers\Admin;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Controller\BaseController;

class SettingsController extends BaseController {

    public function addAction($id = '') {
        
        $gateways = $this->model->getWithdrawGateways();
        
        $this->data_arr['gateways'] = $gateways;

        return parent::addAction($id);
    }

    public function editAction($id = '') {
        
        $item = $this->model->getRecord($id);
      
        $gateways = $this->model->getWithdrawGateways($item->user_id);
       
        $this->data_arr['gateways'] = $gateways;
      
        return parent::editAction($id);
    }

}
