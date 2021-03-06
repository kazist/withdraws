<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Withdraws\Masspay\Code\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Model\BaseModel;
use Kazist\KazistFactory;
use Payments\Payments\Code\Models\PaymentsModel;
use Kazist\Service\Email\Email;
use Kazist\Service\Database\Query;

/**
 * Description of MarketplaceModel
 *
 * @author sbc
 */
class MasspayModel extends BaseModel {

//put your code here

    public $conversion_rate = '';
    public $relative_path = '';
    public $web_path = '';
    public $absolute_path = '';
    public $file_path = '';
    public $file_is_unique = true;

    public function completeProcess() {


        $token = $this->request->get('token');

        $masspay_query = new Query();
        $masspay_query->update('#__withdraws_masspay');
        $masspay_query->where('token=:token');
        $masspay_query->setParameter('token', $token);
        $masspay_query->set('is_processed', '1');
        $masspay_query->execute();

        $withdraw_query = new Query();
        $withdraw_query->update('#__withdraws_withdraws');
        $withdraw_query->where('token=:token');
        $withdraw_query->setParameter('token', $token);
        $withdraw_query->set('paid_status', '1');
        $withdraw_query->execute();
    }

    public function generateMasspay() {

        $gateways = $this->getGateways();

        if (!empty($gateways)) {
            foreach ($gateways as $key => $gateway) {
                $gateway->conversion_rate = $this->getConversionRate($gateway);
                $this->processGatewayMasspay($gateway);
            }
        }
    }

    public function processGatewayMasspay($gateway) {

        $total_withdraws = $this->getTotalWithdraws($gateway->id);

        if ($total_withdraws) {

            $withdraws = $this->getWithdraws($gateway->id, $gateway->file_limit);
      
            $unique_id = substr(uniqid(), -7);
            $this->prepareFile($unique_id, $gateway->short_name, $gateway->file_type);
            file_put_contents($this->file_path, $gateway->withdraw_file_prefix . PHP_EOL, FILE_APPEND | LOCK_EX);

            foreach ($withdraws as $key => $withdraw) {

                $withdraw->amount = $withdraw->amount * $gateway->conversion_rate;
                $this->processSingleWithdrawal($withdraw, $gateway->withdraw_file_structure, $unique_id);
            }

            file_put_contents($this->file_path, $gateway->withdraw_file_suffix . PHP_EOL, FILE_APPEND | LOCK_EX);
            $masspay_id = $this->saveMassPay($gateway, $unique_id);

            $this->sendEmail($gateway, $masspay_id);

            if ($total_withdraws > $gateway->file_limit) {
                $this->processGatewayMasspay($gateway);
            }
        }
    }

    public function sendEmail($gateway, $masspay_id) {

        $email = new Email();
        $factory = new KazistFactory();

        $masspay = $factory->getRecord('#__withdraws_masspay', 'fm', array('fm.id=:id'), array('id' => $masspay_id));
        $users = $factory->getRecords('#__users_users', 'uu', array('uu.is_admin=:is_admin'), array('is_admin' => 1));
        $attachments = array($this->file_path);

        $tmp_array['gateway'] = $gateway;
        $tmp_array['masspay'] = $masspay;

        $email->sendDefinedLayoutEmail('withdraws.masspay.generatefile', $users, $tmp_array, $attachments);
    }

    public function saveMassPay($gateway, $unique_id) {

        $factory = new KazistFactory();

        $year = date('Y');
        $month = date('M');
        $date = date('d');

        $data_obj = new \stdClass();
        $data_obj->year = $year;
        $data_obj->month = $month;
        $data_obj->date = $date;
        $data_obj->type = $gateway->short_name;
        $data_obj->token = $unique_id;
        $data_obj->file = $this->web_path;
        $data_obj->max_limit = $gateway->file_limit;

        return $factory->saveRecord('#__withdraws_masspay', $data_obj);
    }

    public function processSingleWithdrawal($withdraw, $structure, $unique_id) {

        $tmp_array = array();

        $factory = new KazistFactory();
        $paymentsModel = new PaymentsModel();

        $structure_no_space = trim($structure, ' ');
        $structure = (strlen($structure_no_space)) ? $structure_no_space : '{{ user.name }}, {{ user.username }}, {{ user.email }}, {{ withdraw.amount }}, {{ user.id }}, {{ subscription.withdraw_id }}, {{ user.id }} ';

        $user_obj = $paymentsModel->getUser($withdraw->user_id);
        $user = $factory->getRecord('#__users_users', 'uu', array('uu.id=:id'), array('id' => $withdraw->user_id));
        $gateway = $this->getWithdrawGatewaySetting($user->id, $withdraw->gateway_id);

        $tmp_array['withdraw'] = $withdraw;
        $tmp_array['gateway'] = $gateway;
        $tmp_array['setting'] = $gateway;
        $tmp_array['user'] = $user;
        $tmp_array['subscription'] = $user_obj;

        $withdraw_str = $factory->renderString($structure, $tmp_array);

        file_put_contents($this->file_path, $withdraw_str . PHP_EOL, FILE_APPEND | LOCK_EX);

        $data_obj = new \stdClass();
        $data_obj->id = $withdraw->id;
        $data_obj->token = $unique_id;

        $factory->saveRecord('#__withdraws_withdraws', $data_obj);
    }

    public function getWithdrawGatewaySetting($user_id, $gateway_id) {

        $factory = new KazistFactory();

        $query = $factory->getQueryBuilder('#__withdraws_settings_gateways', 'wsg');
        $query->addSelect('pg.short_name, pg.long_name, ws.pin, ws.id_passport');
        $query->andWhere('wsg.user_id=:user_id');
        $query->setParameter('user_id', (int) $user_id);
        $query->andWhere('wsg.gateway_id=:gateway_id');
        $query->setParameter('gateway_id', (int) $gateway_id);
        $record = $query->loadObject();

        $record->params = json_decode($record->params, true);

        return $record;
    }

    public function getTotalWithdraws($gateway_id) {

        $factory = new KazistFactory();


        $query = new Query();
        $query->select('COUNT(*) as total');
        $query->from('#__withdraws_withdraws', 'fg');
        $query->where('(fg.token=\'\' OR fg.token IS NULL)');
        $query->andWhere('(fg.is_canceled=0 OR fg.is_canceled IS NULL)');
        if ($gateway_id) {
            $query->andWhere('fg.gateway_id=:gateway_id');
            $query->setParameter('gateway_id', $gateway_id);
        } else {
            $query->andWhere('1=-1');
        }

        $total = $query->loadResult();

        return $total;
    }

    public function getWithdraws($gateway_id, $limit) {

        $factory = new KazistFactory();


        $limit = ($limit) ? $limit : 2000;

        $query = new Query();
        $query->select('fg.*');
        $query->from('#__withdraws_withdraws', 'fg');
        $query->where('(fg.token=\'\' OR fg.token IS NULL)');
        $query->andWhere('(fg.is_canceled=0 OR fg.is_canceled IS NULL)');
        if ($gateway_id) {
            $query->andWhere('fg.gateway_id=:gateway_id');
            $query->setParameter('gateway_id', $gateway_id);
        } else {
            $query->andWhere('1=-1');
        }

        $query->setFirstResult(0);
        $query->setMaxResults($limit);

        $records = $query->loadObjectList();

        return $records;
    }

    public function getConversionRate($gateway) {

        $query = new Query();
        $query->select('sc.*');
        $query->from('#__setup_currencies', 'sc');
        $query->where('sc.id=:id');
        $query->setParameter('id', $gateway->currency_id);

        $record = $query->loadObject();

        return ($record->selling) ? $record->selling : 1;
    }

    public function getGateways() {

        $factory = new KazistFactory();


        $query = new Query();
        $query->select('pg.*');
        $query->from('#__payments_gateways', 'pg');
        $query->where('pg.can_withdraw=1');

        $records = $query->loadObjectList();

        return $records;
    }

    public function prepareFile($unique_id, $file_name, $file_ext = 'txt') {

        $factory = new KazistFactory();

        $unique_id = ($this->file_is_unique) ? '-' . $unique_id : '';
        $file_ext = ($file_ext <> '') ? $file_ext : 'txt';

        $year = date('Y');
        $month = date('M');
        $date = date('d');

        $this->relative_path = 'uploads/withdraws/masspay/' . $year . '/' . $month . '/' . $date . '/';
        $this->web_path = $this->relative_path . $file_name . $unique_id . '.' . $file_ext;
        $this->absolute_path = rtrim(JPATH_ROOT, '/') . '/' . $this->relative_path;
        $this->file_path = rtrim(JPATH_ROOT, '/') . '/' . $this->web_path;

        $factory->makeDir($this->absolute_path);

        file_put_contents($this->file_path);
        chmod($this->file_path, '775');

        return $this->web_path;
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx  Download Masspay
    public function downloadMasspay() {

        $check_arr = null;
        $factory = new KazistFactory();

        $id = $this->request->get('id');
        $token = $this->request->get('token');

        if ($id) {
            $where_arr = array('fm.id=:id');
            $check_arr = array('id' => $id);
        } elseif ($token <> '') {
            $where_arr = array('fm.token=:token');
            $check_arr = array('token' => $token);
        } else {
            $where_arr = array('1=-1');
        }

        $masspay = $factory->getRecord('#__withdraws_masspay', 'fm', $where_arr, $check_arr);

        if (is_object($masspay)) {

            $file_path = JPATH_ROOT . $masspay->file;
            $file_name_arr = explode('/', $masspay->file);
            $file_name_arr_rev = array_reverse($file_name_arr);

            $quoted = $file_name_arr_rev[0];
            $size = filesize($file_path);

            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . $quoted);
            header('Content-Transfer-Encoding: binary');
            header('Connection: Keep-Alive');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . $size);
            readfile($file_path);

            exit;
        }
    }

}
