<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Withdraws\Withdraws\Code\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Model\BaseModel;
use Kazist\KazistFactory;
use Kazist\Service\Email\Email;
use Payments\Payments\Code\Models\PaymentsModel;
use Payments\Transactions\Code\Models\TransactionsModel;
use Kazist\Service\Database\Query;

/**
 * Description of MarketplaceModel
 *
 * @author sbc
 */
class WithdrawsModel extends BaseModel {

    //put your code here

    public function getQueryBuilder() {

        $factory = new KazistFactory();
        $user = $factory->getUser();

        if (WEB_IS_ADMIN) {
            $user_id = $this->request->get('user_id');
        } else {
            $user_id = $user->id;
        }

        $query = parent::getQueryBuilder('#__withdraws_withdraws', 'ww');
        $query->addSelect('uu.email AS user_id_email');
        $query->addSelect('uu.username AS user_id_username');
        
        if ($user_id) {
            $query->where('ww.user_id=' . $user_id);
        }

        return $query;
    }

    public function save($form = '') {

        $factory = new KazistFactory();
        $user_obj = $factory->getUser();

        $form = $this->request->get('form');

        $amount_withdrawn = $form['amount'];
        if (!WEB_IS_ADMIN) {

            $url = $this->generateUrl('withdraws.withdraws');

            $form['user_id'] = $user_obj->id;
            $form['return_url'] = base64_encode($url);
        }

        $user = $this->getUser($form['user_id']);
        $total_amount = $user->money_in - $user->money_out;

        $params = $this->getInvoice();
        $params = array_reverse($params);
        $total_param = $params[0];

        if ($form['amount'] > $total_amount) {
            $factory->enqueueMessage('Amount to be withdrawn (' . $total_param->amount . ') is more than Available Amount (' . $total_amount . ')');
            return false;
        }

        if ($form['amount'] != $total_param->amount) {
            $form['amount'] = $total_param->amount;
        }

        $withdraw_id = parent::save($form);

        if ($withdraw_id) {
            $this->sendEmail($user, $withdraw_id);
            $this->getDeductCommission($user, $withdraw_id, $params, $amount_withdrawn);
        }

        return $withdraw_id;
    }

    public function sendEmail($user, $withdraw_id) {

        $tmp_array = array();
        $email = new Email();
        $factory = new KazistFactory();

        $tmp_array['user'] = $user;
        $tmp_array['withdraw'] = $factory->getRecord('#__withdraws_withdraws', 'fw', array('id=:id'), array('id' => $withdraw_id));

        $email->sendDefinedLayoutEmail('withdraws.withdraws.fundwithdraw', $tmp_array['user']->email, $tmp_array);
    }

    public function getDeductCommission($user, $withdraw_id, $params, $amount_withdrawn) {

        $factory = new KazistFactory();

        $parent_id = '';

        // $params = array_reverse($params);

        if (!empty($params)) {
            foreach ($params as $key => $param) {
                if (count($params) == 1 || (int) $param->amount != (int) $amount_withdrawn) {

                    $data_obj = new \stdClass();
                    $data_obj->user_id = $user->id;
                    $data_obj->behalf_user_id = $user->id;
                    $data_obj->description = ($param->title != 'Total') ? $param->title . ' For ' . $parent_id : 'Fund Withdrawal To ' . $user->username;
                    $data_obj->item_id = $withdraw_id;
                    $data_obj->rate_id = $param->id;
                    $data_obj->payment_source = 'withdraws.withdraws';
                    $data_obj->debit = abs($param->amount);
                    $data_obj->type = 'fund-withdraw';

                    $id = $factory->saveRecord('#__payments_transactions', $data_obj);

                    if ($param->title == 'Total') {
                        $parent_id = $id;
                    }
                }
            }
        }
    }

    public function getPaidInput() {

        $tmp_array = array();
        $tmp_array[] = array('value' => '1', 'text' => 'Paid');
        $tmp_array[] = array('value' => '0', 'text' => 'Not Paid');
        return $tmp_array;
    }

    public function getGetawaysInput() {

        $tmp_array = array();

        $query = new Query();
        $query->select('pg.*');
        $query->from('#__payments_gateways', 'pg');
        $query->where('pg.published=1');
        $records = $query->loadObjectList();

        if (!empty($records)) {
            foreach ($records as $record) {
                $tmp_array[] = array('value' => $record->id, 'text' => $record->long_name . ' (' . $record->short_name . ')');
            }
        }

        return $tmp_array;
    }

    public function getWithdrawSetting() {

        $factory = new KazistFactory();

        $user = (is_object($user)) ? $user : $this->getUser();

        $query = $factory->getQueryBuilder('#__withdraws_settings', 'ws');
        $query->where('ws.user_id=:user_id');
        $query->setParameter('user_id', $user->id);
        $record = $query->loadObject();

        return $record;
    }

    public function getAvailableAmount($user = '') {
        $factory = new KazistFactory();

        $user = (is_object($user)) ? $user : $this->getUser();

        $query = $factory->getQueryBuilder('#__payments_transactions', 'pt');
        $query->select('SUM(pt.credit) AS credit, SUM(pt.debit) AS debit');
        $query->where('pt.payment_source <> :payment_source');
        $query->setParameter('payment_source', 'payments.deposits');
        $query->where('pt.user_id = :user_id');
        $query->setParameter('user_id', (int) $user->id);
        $record = $query->loadObject();

        return $record->credit - $record->debit;
    }

    public function getWithdrawGateways($user = '') {

        $factory = new KazistFactory();

        $user = (is_object($user)) ? $user : $this->getUser();

        $query = $factory->getQueryBuilder('#__withdraws_settings_gateways', 'wsg');
        $query->addSelect('pg.short_name, pg.long_name ');
        $query->where('wsg.user_id=:user_id');
        $query->setParameter('user_id', (int) $user->id);
        $query->orderBy('wsg.is_default', 'DESC');
        $records = $query->loadObjectList();

        foreach ($records as $key => $record) {

            if (!$record->gateway_id || $record->gateway_id_long_name == '') {
                unset($records[$key]);
                continue;
            }

            $records[$key]->params = json_decode($record->params, true);
        }

        if (!empty($records) && !$records[0]->is_default) {
            $records[0]->is_default = 1;
        }

        return $records;
    }

    public function getInvoice() {

        $tmp_array = array();

        $factory = new KazistFactory();

        $form = $this->request->get('form', null, null);

        $paymentModel = new PaymentsModel();
        $rates = $paymentModel->getPaymentGatewayInvoiceRates($form['amount'], $form['gateway_id'], 'withdraw');

        return $rates;
    }

    public function getForm() {

        $factory = new KazistFactory();


        $form = $this->request->request->get('form', null, null);

        return $form;
    }

    public function getUser($user_id = '') {

        $payment = new PaymentsModel();

        $user = $payment->getUser();

        return $user;
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx  reverseWithdraw
    public function reverseWithdraw() {

        $factory = new KazistFactory();

        $transactionModel = new TransactionsModel();

        $id = $this->request->get('id');

        $transactionModel->reverseTransaction($id, 'withdraws.withdraws');

        $data_obj = new \stdClass();
        $data_obj->id = $id;
        $data_obj->is_canceled = 1;
        $factory->saveRecord('#__withdraws_withdraws', $data_obj);
    }

}
