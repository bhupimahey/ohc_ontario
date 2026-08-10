<?php

namespace App\Controllers;

use App\Models\RenewalModel;

class Renewal extends BaseController
{
    /** Static MPIN as requested */
    private const MPIN = '1218';

    private RenewalModel $renewalModel;

    public function __construct()
    {
        helper(['form', 'url']);
        $this->renewalModel = new RenewalModel();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        if ($this->session->get('renewal_unlocked') === true) {
            return redirect()->to(base_url('renewal/manage'));
        }

        return view('renewal/pin', [
            'message_output' => $this->message_output,
            'next_renewal' => $this->renewalModel->getFormattedRenewalDate(),
        ]);
    }

    public function unlock()
    {
        $mpin = trim((string) $this->request->getPost('mpin'));
        if ($mpin === self::MPIN) {
            $this->session->set('renewal_unlocked', true);
            $this->message_output->set_success('Access granted.');
            return redirect()->to(base_url('renewal/manage'));
        }

        $this->message_output->set_error('Invalid MPIN.');
        return redirect()->to(base_url('renewal'));
    }

    public function manage()
    {
        if ($this->session->get('renewal_unlocked') !== true) {
            return redirect()->to(base_url('renewal'));
        }

        return view('renewal/manage', [
            'message_output' => $this->message_output,
            'next_renewal' => $this->renewalModel->getFormattedRenewalDate(),
            'next_renewal_raw' => $this->renewalModel->getNextRenewalDate(),
            'payments' => $this->renewalModel->listPayments(),
        ]);
    }

    public function add_payment()
    {
        if ($this->session->get('renewal_unlocked') !== true) {
            return redirect()->to(base_url('renewal'));
        }

        $amc = (float) $this->request->getPost('amc_amount');
        $hosting = (float) $this->request->getPost('hosting_amount');
        $paidOn = $this->request->getPost('paid_on') ?: date('Y-m-d');
        $notes = (string) $this->request->getPost('notes');

        if ($amc <= 0 && $hosting <= 0) {
            $this->message_output->set_error('Enter AMC and/or Hosting payment amount.');
            return redirect()->to(base_url('renewal/manage'));
        }

        $result = $this->renewalModel->addPayment([
            'amc_amount' => $amc,
            'hosting_amount' => $hosting,
            'paid_on' => $paidOn,
            'notes' => $notes,
        ]);

        $this->message_output->set_success(
            'Payment saved. Next Renewal On updated to ' . $result['formatted'] . '.'
        );

        return redirect()->to(base_url('renewal/manage'));
    }

    public function lock()
    {
        $this->session->remove('renewal_unlocked');
        return redirect()->to(base_url('renewal'));
    }
}
