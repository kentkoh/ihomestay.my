<?php
$verificationColors = [
    'verified'             => 'success',
    'pending_verification' => 'warning',
    'unverified'           => 'secondary',
    'rejected'             => 'danger',
    'suspended'            => 'dark',
];
?>

<?php if (empty($owners)): ?>
    <div class="card border-0 shadow-sm text-center p-5 text-muted">No owners registered yet.</div>
<?php else: ?>
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">Owner</th>
                    <th>Email</th>
                    <th>Listings</th>
                    <th>Verification</th>
                    <th>Joined</th>
                    <th class="text-end pe-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($owners as $o): ?>
                <tr>
                    <td class="ps-3">
                        <div class="fw-semibold small"><?= htmlspecialchars($o['name']) ?></div>
                        <?php if ($o['company_name']): ?>
                            <div class="text-muted" style="font-size:.72rem;"><?= htmlspecialchars($o['company_name']) ?></div>
                        <?php endif; ?>
                    </td>
                    <td class="small text-muted"><?= htmlspecialchars($o['email']) ?></td>
                    <td class="small">
                        <span class="badge bg-light text-dark border"><?= (int)$o['listing_count'] ?> published</span>
                    </td>
                    <td>
                        <span class="badge bg-<?= $verificationColors[$o['verification_status']] ?? 'secondary' ?> text-capitalize">
                            <?= str_replace('_', ' ', $o['verification_status']) ?>
                        </span>
                        <?php if ($o['verified_at']): ?>
                            <div class="text-muted" style="font-size:.7rem;">
                                Since <?= date('d M Y', strtotime($o['verified_at'])) ?>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td class="small text-muted"><?= date('d M Y', strtotime($o['created_at'])) ?></td>
                    <td class="text-end pe-3">
                        <div class="d-flex gap-1 justify-content-end">
                            <?php if ($o['verification_status'] !== 'verified'): ?>
                                <form method="POST" action="/admin/owners/<?= $o['id'] ?>/verify">
                                    <?= CSRF::field() ?>
                                    <button class="btn btn-sm btn-success" title="Mark as Verified"
                                            onclick="return confirm('Verify this owner?')">
                                        <i class="bi bi-patch-check-fill"></i> Verify
                                    </button>
                                </form>
                            <?php else: ?>
                                <form method="POST" action="/admin/owners/<?= $o['id'] ?>/unverify">
                                    <?= CSRF::field() ?>
                                    <button class="btn btn-sm btn-outline-secondary" title="Remove Verification"
                                            onclick="return confirm('Remove verification from this owner?')">
                                        <i class="bi bi-patch-exclamation"></i> Unverify
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>
