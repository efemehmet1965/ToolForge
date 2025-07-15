<?php
$result = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ip = trim($_POST['ip'] ?? '');
    if (filter_var($ip, FILTER_VALIDATE_IP)) {
        // Using ip-api.com for IP lookup (free tier, rate limited)
        $api_url = "http://ip-api.com/json/{$ip}?fields=status,message,country,countryCode,region,regionName,city,zip,lat,lon,timezone,isp,org,as,query";
        $response = @file_get_contents($api_url);
        
        if ($response) {
            $data = json_decode($response, true);
            if ($data['status'] === 'success') {
                $result = $data;
            } else {
                $error = $data['message'] ?? 'Could not retrieve IP information.';
            }
        } else {
            $error = 'Could not connect to IP lookup service.';
        }
    } else {
        $error = 'Please enter a valid IP address.';
    }
}

include __DIR__ . '/../templates/header.php';
?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><i class="bi bi-geo-alt-fill me-2"></i>IP Information Lookup</h5>
        <p class="card-text">Get geographical and network information for an IP address. This tool can help identify the location, ISP, and organization associated with an IP.</p>
        <form method="POST" action="?page=ip_lookup">
            <div class="mb-3">
                <label for="ip" class="form-label">IP Address</label>
                <input type="text" class="form-control" id="ip" name="ip" value="<?php echo htmlspecialchars($_POST['ip'] ?? ''); ?>" placeholder="e.g., 8.8.8.8" required>
            </div>
            <button type="submit" class="btn btn-primary">Lookup IP</button>
        </form>

        <?php if ($error): ?>
            <div class="alert alert-danger mt-4"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($result !== null): ?>
            <div class="mt-4">
                <label class="form-label">IP Details for <?php echo htmlspecialchars($result['query']); ?></label>
                <div class="input-group">
                    <textarea class="form-control" id="ipLookupResult" rows="10" readonly><?php 
                        $ip_output = '';
                        foreach($result as $key => $value) {
                            if (!in_array($key, ['status', 'message', 'query'])) {
                                $ip_output .= htmlspecialchars(str_replace(['_', 'Code', 'Name'], [' ', '', ''], $key)) . ': ' . htmlspecialchars($value) . "\n";
                            }
                        }
                        echo $ip_output;
                    ?></textarea>
                    <button class="btn btn-outline-secondary copy-btn" type="button" data-target="ipLookupResult">Copy</button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>
