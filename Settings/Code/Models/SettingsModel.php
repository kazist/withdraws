<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Withdraws\Settings\Code\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Model\BaseModel;
use Kazist\KazistFactory;
use Payments\Payments\Code\Models\PaymentsModel;

/**
 * Description of MarketplaceModel
 *
 * @author sbc
 */
class SettingsModel extends BaseModel {

//put your code here

    public function getWithdrawSetting() {

        $user_id = $this->request->get('user_id');

        $factory = new KazistFactory();

        if (!$user_id) {
            $user = $factory->getUser();
            $user_id = $user->id;
        }

        $query = $factory->getQueryBuilder('#__withdraws_settings', 'ws');
        $query->where('ws.user_id=:user_id');
        $query->setParameter('user_id', $user_id);
        $record = $query->loadObject();

        return $record->id;
    }

    public function getWithdrawGateways() {

        $factory = new KazistFactory();
        $paymentsModel = new PaymentsModel();

        $user = $factory->getUser();

        $query = $factory->getQueryBuilder('#__payments_gateways', 'wg');
        $query->where('wg.can_withdraw=1');
        $records = $query->loadObjectList();

        foreach ($records as $key => $record) {

            $is_allowed = $paymentsModel->getIsAllowedInCountry($record->id);
            $is_notallowed = $paymentsModel->getIsNotAllowedInCountry($record->id);
            $count_allowed = $paymentsModel->getCountIsAllowedIn($record->id);

            $param_json = JPATH_ROOT . 'applications/' . $record->form_path . '/param.json';

            if (($count_allowed && !$is_allowed) || $is_notallowed) {
                unset($records[$key]);
            }

            if (file_exists($param_json)) {
                $params = json_decode(file_get_contents($param_json));
                $records[$key] = $this->getWithdrawGatewaysUserParams($user->id, $record, $params);
            } else {
                unset($records[$key]);
            }
        }

        return $records;
    }

    public function getWithdrawGatewaysUserParams($user_id, $gateway, $params) {

        $factory = new KazistFactory();

        $query = $factory->getQueryBuilder('#__withdraws_settings_gateways', 'wsg');
        $query->andWhere('wsg.gateway_id=:gateway_id');
        $query->setParameter('gateway_id', $gateway->id);
        $query->andWhere('wsg.user_id=:user_id');
        $query->setParameter('user_id', $user_id);
        $record = $query->loadObject();

        if (is_object($record)) {
            $saved_params = json_decode($record->params, true);

            foreach ($params as $key => $param) {
                $param_name = $param->name;
                $params[$key]->value = $saved_params[$param_name];
            }
            $gateway->selected = 1;
        } else {
            $gateway->selected = 0;
        }

        $gateway->params = $params;

        return $gateway;
    }

    public function save($form_data = '') {

        $factory = new KazistFactory();

        $user = $factory->getUser();

        $selected_gateways = $form_data['selected_gateways'];
        $gateways = $form_data['gateways'];
        $form_data['user_id'] = $user->id;

        unset($form_data['selected_gateways']);
        unset($form_data['gateways']);

        $id = parent::save($form_data);

        foreach ($selected_gateways as $gateway_id => $selected_gateway) {
            foreach ($gateways[$selected_gateway] as $key => $params) {

                $params_encode = json_encode($params);

                $data = new \stdClass();
                $data->user_id = $user->id;
                $data->gateway_id = $gateway_id;
                $data->setting_id = $id;
                $exist_obj = clone $data;
                $data->params = $params_encode;

                $factory->saveRecord('#__withdraws_settings_gateways', $data, array('user_id=:user_id', 'gateway_id=:gateway_id'), $exist_obj);
            }
        }

        return $id;
    }

}
