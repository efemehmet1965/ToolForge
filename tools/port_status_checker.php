<?php
$result = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = trim($_POST['host'] ?? '');
    $port = intval($_POST['port'] ?? 80);

    if (empty($host)) {
        $error = 'Please enter a host or IP address.';
    } elseif ($port <= 0 || $port > 65535) {
        $error = 'Please enter a valid port number (1-65535).';
    } else {
        $connection = @fsockopen($host, $port, $errno, $errstr, 2); // 2-second timeout
        if (is_resource($connection)) {
            $result = "Port {$port} on {$host} is OPEN.";
            fclose($connection);
        } else {
            $result = "Port {$port} on {$host} is CLOSED or unreachable. Error: ({$errno}) {$errstr}";
        }
    }
}

include __DIR__ . '/../templates/header.php';
?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><i class="bi bi-hdd-network me-2"></i>Port Status Checker</h5>
        <p class="card-text">Quickly check if a specific port on a host is open or closed.</p>
        <form method="POST" action="?page=port_status_checker">
            <div class="mb-3">
                <label for="host" class="form-label">Host / IP Address</label>
                <input type="text" class="form-control" id="host" name="host" value="<?php echo htmlspecialchars($_POST['host'] ?? ''); ?>" placeholder="example.com or 192.168.1.1" required>
            </div>
            <div class="mb-3">
                <label for="port" class="form-label">Port Number</label>
                <input type="number" class="form-control" id="port" name="port" value="<?php echo htmlspecialchars($_POST['port'] ?? 80); ?>" min="1" max="65535" required>
            </div>
            <button type="submit" class="btn btn-primary">Check Port Status</button>
        </form>

        <?php if ($error): ?>
            <div class="alert alert-danger mt-4"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($result !== null): ?>
            <div class="mt-4">
                <label class="form-label">Result</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="portStatusResult" value="<?php echo htmlspecialchars($result); ?>" readonly>
                    <button class="btn btn-outline-secondary copy-btn" type="button" data-target="portStatusResult">Copy</button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>
