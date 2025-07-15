<?php
$result = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ip = trim($_POST['ip'] ?? '');

    if (filter_var($ip, FILTER_VALIDATE_IP)) {
        $blacklists = [
            'sbl.spamhaus.org',
            'xbl.spamhaus.org',
            'pbl.spamhaus.org',
            'zen.spamhaus.org',
            'b.barracudacentral.org',
            'bl.spamcop.net',
            'cbl.abuseat.org',
            'dnsbl.sorbs.net',
            'dul.dnsbl.sorbs.net',
            'http.dnsbl.sorbs.net',
            'misc.dnsbl.sorbs.net',
            'smtp.dnsbl.sorbs.net',
            'socks.dnsbl.sorbs.net',
            'web.dnsbl.sorbs.net',
            'zombie.dnsbl.sorbs.net',
        ];

        $reversed_ip = implode('.', array_reverse(explode('.', $ip)));
        $is_blacklisted = false;
        $listed_on = [];

        foreach ($blacklists as $bl) {
            $lookup = $reversed_ip . '.' . $bl;
            if (checkdnsrr($lookup, 'A')) {
                $is_blacklisted = true;
                $listed_on[] = $bl;
            }
        }

        if ($is_blacklisted) {
            $result = ['status' => 'BLACKLISTED', 'lists' => $listed_on];
        } else {
            $result = ['status' => 'CLEAN'];
        }

    } else {
        $error = 'Please enter a valid IP address.';
    }
}

include __DIR__ . '/../templates/header.php';
?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><i class="bi bi-shield-fill-exclamation me-2"></i>IP Blacklist Checker</h5>
        <p class="card-text">Check if an IP address is listed on common spam and abuse blacklists.</p>
        <form method="POST" action="?page=ip_blacklist_checker">
            <div class="mb-3">
                <label for="ip" class="form-label">IP Address</label>
                <input type="text" class="form-control" id="ip" name="ip" value="<?php echo htmlspecialchars($_POST['ip'] ?? ''); ?>" placeholder="e.g., 192.168.1.1" required>
            </div>
            <button type="submit" class="btn btn-primary">Check IP</button>
        </form>

        <?php if ($error): ?>
            <div class="alert alert-danger mt-4"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($result !== null): ?>
            <div class="mt-4">
                <label class="form-label">Result for <?php echo htmlspecialchars($_POST['ip'] ?? ''); ?></label>
                <?php if ($result['status'] === 'BLACKLISTED'): ?>
                    <div class="alert alert-danger">
                        <strong>BLACKLISTED!</strong> This IP is listed on the following blacklists:
                        <ul>
                            <?php foreach($result['lists'] as $list): ?>
                                <li><?php echo htmlspecialchars($list); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php else: ?>
                    <div class="alert alert-success">
                        <strong>CLEAN!</strong> This IP is not found on the checked blacklists.
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>
