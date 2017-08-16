<?php

/*
 * This file is part of Kazist Framework.
 * (c) Dedan Irungu <irungudedan@gmail.com>
 *  For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 * 
 */

/**
 * Description of MasspayController
 *
 * @author sbc
 */

namespace Withdraws\Masspay\Code\Controllers;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Controller\BaseController;
use Withdraws\Masspay\Code\Models\MasspayModel;

class MasspayController extends BaseController {

    public function addAction() {

        $factory = new KazistFactory();
        $factory->enqueueMessage('Masspay is Only Auto Generated By System', 'error');
        return $this->redirectToRoute('admin.withdraws.masspay');
    }

    public function editAction() {

        $factory = new KazistFactory();
        $factory->enqueueMessage('Masspay is Only Auto Generated By System', 'error');
        return $this->redirectToRoute('admin.withdraws.masspay');
    }

    public function generatemasspayAction() {

        $this->model = new MasspayModel();
        $this->model->generateMasspay();

        return $this->redirectToRoute('admin.withdraws.masspay');
    }

}