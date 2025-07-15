<?php
$result = null;
$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = trim($_POST['host'] ?? '');
    $ports_str = trim($_POST['ports'] ?? '80,443,21,22,8080');
    $ports = array_filter(array_map('trim', explode(',', $ports_str)));

    if (filter_var(gethostbyname($host), FILTER_VALIDATE_IP)) {
        if (!empty($ports)) {
            $open_ports = [];
            foreach ($ports as $port) {
                $port = intval($port);
                if ($port > 0 && $port <= 65535) {
                    $connection = @fsockopen($host, $port, $errno, $errstr, 1); // 1-second timeout
                    if (is_resource($connection)) {
                        $open_ports[] = $port;
                        fclose($connection);
                    }
                }
            }
            $result = $open_ports;
        } else {
            $error = 'Please enter a list of ports to scan.';
        }
    } else {
        $error = 'Please enter a valid host or IP address.';
    }
}

include __DIR__ . '/../templates/header.php';
?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><i class="bi bi-broadcast me-2"></i>Port Scanner</h5>
        <p class="card-text">Check for open ports on a specific host. Note: This tool may be slow or blocked on shared hosting due to security restrictions. Use common ports like 80, 443, 21, 22.</p>
        <form method="POST" action="?page=port_scanner">
            <div class="mb-3">
                <label for="host" class="form-label">Host / IP Address</label>
                <input type="text" class="form-control" id="host" name="host" value="<?php echo htmlspecialchars($_POST['host'] ?? ''); ?>" placeholder="example.com" required>
            </div>
            <div class="mb-3">
                <label for="ports" class="form-label">Ports (comma-separated)</label>
                <input type="text" class="form-control" id="ports" name="ports" value="<?php echo htmlspecialchars($_POST['ports'] ?? '80,443,21,22,8080'); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Scan Ports</button>
        </form>

        <?php if ($error): ?>
            <div class="alert alert-danger mt-4"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($result !== null): ?>
            <div class="mt-4">
                <label class="form-label">Scan Result for <?php echo htmlspecialchars($host); ?></label>
                <?php if (empty($result)): ?>
                    <p>No open ports found from the list provided.</p>
                <?php else: ?>
                    <div class="input-group">
                        <textarea class="form-control" id="portScanResult" rows="5" readonly><?php echo implode(", ", $result); ?></textarea>
                        <button class="btn btn-outline-secondary copy-btn" type="button" data-target="portScanResult">Copy</button>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>
