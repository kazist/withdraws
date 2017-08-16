<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Withdraws\Addons\Withdraws\Controllers;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Controller\AddonController;
use Withdraws\Addons\Withdraws\Models\WithdrawsModel;

/**
 * Kazist view class for the application
 *
 * @since  1.0
 */
class WithdrawsController extends AddonController {

    function indexAction() {

        $withdraw = new WithdrawsModel();

        $data_arr['items'] = $withdraw->getWithdraws();
      
        $this->html = $this->render('Withdraws:Addons:Withdraws:views:withdraws.twig', $data_arr);

        $response = $this->response($this->html);



        return $response;
    }

    function withdrawSettingAction() {

        $withdraw = new WithdrawsModel();

        $data_arr['items'] = $withdraw->getWithdrawSetting();

        $this->html = $this->render('Withdraws:Addons:Withdraws:views:withdrawsetting.twig', $data_arr);

        $response = $this->response($this->html);



        return $response;
    }

}
