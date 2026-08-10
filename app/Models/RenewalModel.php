<?php

namespace App\Models;

use CodeIgniter\Model;

class RenewalModel extends Model
{
    protected $table = 'software_renewal';
    protected $primaryKey = 'id';
    protected $allowedFields = ['next_renewal_date', 'updated_at'];
    protected $useTimestamps = false;

    public function ensureTables(): void
    {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS software_renewal (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                next_renewal_date DATE NOT NULL,
                updated_at DATETIME NOT NULL,
                PRIMARY KEY (id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");

        $this->db->query("
            CREATE TABLE IF NOT EXISTS software_renewal_payments (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                paid_on DATE NOT NULL,
                amc_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                hosting_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                notes TEXT NULL,
                created_at DATETIME NOT NULL,
                PRIMARY KEY (id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");

        $row = $this->db->table('software_renewal')->get()->getRowArray();
        if (!$row) {
            $this->db->table('software_renewal')->insert([
                'next_renewal_date' => '2026-05-05',
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }

    public function getNextRenewalDate(): string
    {
        $this->ensureTables();
        $row = $this->db->table('software_renewal')->orderBy('id', 'ASC')->get()->getRowArray();
        return $row['next_renewal_date'] ?? '2026-05-05';
    }

    public function getFormattedRenewalDate(): string
    {
        $date = $this->getNextRenewalDate();
        return date('d M,Y', strtotime($date));
    }

    public function listPayments(int $limit = 50): array
    {
        $this->ensureTables();
        return $this->db->table('software_renewal_payments')
            ->orderBy('paid_on', 'DESC')
            ->orderBy('id', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    /**
     * Record AMC + hosting payment and push next renewal forward by 1 year.
     */
    public function addPayment(array $data): array
    {
        $this->ensureTables();

        $amc = (float) ($data['amc_amount'] ?? 0);
        $hosting = (float) ($data['hosting_amount'] ?? 0);
        $paidOn = $data['paid_on'] ?? date('Y-m-d');
        $notes = trim((string) ($data['notes'] ?? ''));

        $this->db->table('software_renewal_payments')->insert([
            'paid_on' => $paidOn,
            'amc_amount' => number_format($amc, 2, '.', ''),
            'hosting_amount' => number_format($hosting, 2, '.', ''),
            'notes' => $notes !== '' ? $notes : null,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $current = $this->getNextRenewalDate();
        $base = strtotime($current);
        // If already past due, extend from payment date; otherwise extend from current renewal date.
        if ($base < strtotime(date('Y-m-d'))) {
            $base = strtotime($paidOn);
        }
        $next = date('Y-m-d', strtotime('+1 year', $base));

        $this->db->table('software_renewal')->where('id >', 0)->update([
            'next_renewal_date' => $next,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return [
            'next_renewal_date' => $next,
            'formatted' => date('d M,Y', strtotime($next)),
        ];
    }
}
