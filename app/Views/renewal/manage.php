<?php $header = ['title' => 'Manage Renewal']; ?>
<?php echo view('includes/header', $header); ?>
<body class="horizontal-layout horizontal-menu blank-page navbar-floating footer-static" data-open="hover" data-menu="horizontal-menu" data-col="blank-page">
<div class="app-content content">
  <div class="content-wrapper container-xxl p-2">
    <div class="content-body">
      <div class="card" style="max-width:900px;margin:24px auto;">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
          <div>
            <h4 class="card-title mb-0">AMC &amp; Hosting Payments</h4>
            <small class="text-muted">Next Renewal On: <strong><?= esc($next_renewal); ?></strong></small>
          </div>
          <a class="btn btn-outline-secondary btn-sm" href="<?= base_url('renewal/lock'); ?>">Lock</a>
        </div>
        <div class="card-body">
          <?php echo $message_output->run(); ?>

          <h5 class="mb-1">Add payment received</h5>
          <p class="text-muted small">When you save a payment, the Next Renewal date moves forward by 1 year automatically.</p>

          <form action="<?= base_url('renewal/add_payment'); ?>" method="post" class="row g-1 mb-2">
            <div class="col-md-3">
              <label class="form-label">Paid On</label>
              <input type="date" name="paid_on" class="form-control" value="<?= date('Y-m-d'); ?>" required>
            </div>
            <div class="col-md-3">
              <label class="form-label">AMC Amount</label>
              <input type="number" step="0.01" min="0" name="amc_amount" class="form-control" placeholder="0.00">
            </div>
            <div class="col-md-3">
              <label class="form-label">Hosting Amount</label>
              <input type="number" step="0.01" min="0" name="hosting_amount" class="form-control" placeholder="0.00">
            </div>
            <div class="col-md-3">
              <label class="form-label">Notes</label>
              <input type="text" name="notes" class="form-control" placeholder="Optional">
            </div>
            <div class="col-12 mt-1">
              <button type="submit" class="btn btn-primary">Save Payment &amp; Update Renewal</button>
            </div>
          </form>

          <hr>
          <h5 class="mb-1">Payment history</h5>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Paid On</th>
                  <th>AMC</th>
                  <th>Hosting</th>
                  <th>Total</th>
                  <th>Notes</th>
                </tr>
              </thead>
              <tbody>
              <?php if (empty($payments)): ?>
                <tr><td colspan="5" class="text-center text-muted">No payments yet.</td></tr>
              <?php else: ?>
                <?php foreach ($payments as $p): ?>
                  <tr>
                    <td><?= esc(date('d M,Y', strtotime($p['paid_on']))); ?></td>
                    <td>$<?= esc(number_format((float)$p['amc_amount'], 2)); ?></td>
                    <td>$<?= esc(number_format((float)$p['hosting_amount'], 2)); ?></td>
                    <td>$<?= esc(number_format((float)$p['amc_amount'] + (float)$p['hosting_amount'], 2)); ?></td>
                    <td><?= esc($p['notes'] ?? ''); ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php echo view('includes/footer_scripts'); ?>
<?php echo view('includes/footer'); ?>
