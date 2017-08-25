<?php

/*
 * This file is part of Kazist Framework.
 * (c) Dedan Irungu <irungudedan@gmail.com>
 *  For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 * 
 */

/**
 * Description of WithdrawsController
 *
 * @author sbc
 */

namespace Withdraws\Withdraws\Code\Controllers;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\KazistFactory;
use Kazist\Controller\BaseController;
use Withdraws\Withdraws\Code\Models\WithdrawsModel;

class WithdrawsController extends BaseController {

    public function indexAction($offset = 0, $limit = 10) {


        $this->data_arr['gatewaysinput'] = $this->model->getGetawaysInput();
        $this->data_arr['paidinput'] = $this->model->getPaidInput();

        return parent::indexAction($offset, $limit);
    }

    public function saveAction($form = '') {

        $factory = new KazistFactory();

        $form = $this->request->request->get('form');
        $user = $this->model->getUser();

        $minimum_amount = $factory->getSetting('withdraws_withdraw_minimum_amount');
        $withheld_amount = $factory->getSetting('withdraws_withdraw_withheld_amount');
        $reversed_amount = $minimum_amount + $withheld_amount;

        if ($form['amount'] >= $minimum_amount && $user->money_in >= $reversed_amount) {
            $this->return_url = 'withdraws.withdraws';
            return parent::saveAction($form);
        } else {
            $factory->enqueueMessage(' Wrong amount provided. Enter correct amount.', 'error');
            return $this->redirectToRoute('withdraws.withdraws.add');
        }
    }

    public function addAction() {

        $factory = new KazistFactory();

        $minimum_amount = $factory->getSetting('withdraws_withdraw_minimum_amount');
        $withheld_amount = $factory->getSetting('withdraws_withdraw_withheld_amount');

        $this->model = new WithdrawsModel();

        $item = $this->model->getRecord();
        $user = $this->model->getUser();
        $gateways = $this->model->getWithdrawGateways($user);
        $setting = $this->model->getWithdrawSetting($user);

        if (empty($gateways)) {
            $factory->enqueueMessage(' Please provide all financial details in order to proceed with withdrawal.', 'error');
            return $this->redirectToRoute('withdraws.settings.edit', array('user_id' => $user->id));
        }

        $data_arr['item'] = $item;
        $data_arr['gateways'] = $gateways;
        $data_arr['setting'] = $setting;
        $data_arr['user'] = $user;
        $data_arr['minimum_amount'] = $minimum_amount;
        $data_arr['withheld_amount'] = $withheld_amount;

        $this->html = $this->render('Withdraws:Withdraws:Code:views:edit.index.twig', $data_arr);

        $response = $this->response($this->html);

        return $response;
    }

    public function invoiceAction() {

        $factory = new KazistFactory();

        $minimum_amount = $factory->getSetting('withdraws_withdraw_minimum_amount');

        $this->model = new WithdrawsModel();

        $form = $this->request->get('form');
        $rates = $this->model->getInvoice();
        $user = $this->model->getUser();

        if ($minimum_amount > $form['amount']) {
            $factory->enqueueMessage('The Amount Withdrawn (' . $form['amount'] . ') is less than minimum anount (' . $minimum_amount . ')', 'error');
            return $this->redirectToRoute('withdraws.withdraws.add');
        }

        $data_arr['rates'] = $rates;
        $data_arr['rates_json'] = json_encode($rates);
        $data_arr['form'] = $form;
        $data_arr['user'] = $user;
        $data_arr['minimum_amount'] = $minimum_amount;

        $this->html = $this->render('Withdraws:Withdraws:Code:views:invoice.index.twig', $data_arr);

        $response = $this->response($this->html);



        return $response;
    }

}
