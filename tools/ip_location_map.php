<?php
$result = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ip = trim($_POST['ip'] ?? '');

    if (filter_var($ip, FILTER_VALIDATE_IP)) {
        // Using ip-api.com for IP lookup (free tier, rate limited)
        $api_url = "http://ip-api.com/json/{$ip}?fields=status,message,lat,lon,query";
        $response = @file_get_contents($api_url);
        
        if ($response) {
            $data = json_decode($response, true);
            if ($data['status'] === 'success') {
                $result = $data;
            } else {
                $error = $data['message'] ?? 'Could not retrieve IP location.';
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
        <h5 class="card-title"><i class="bi bi-map-fill me-2"></i>IP Location Map</h5>
        <p class="card-text">Visualize the geographical location of an IP address on a map.</p>
        <form method="POST" action="?page=ip_location_map">
            <div class="mb-3">
                <label for="ip" class="form-label">IP Address</label>
                <input type="text" class="form-control" id="ip" name="ip" value="<?php echo htmlspecialchars($_POST['ip'] ?? ''); ?>" placeholder="e.g., 8.8.8.8" required>
            </div>
            <button type="submit" class="btn btn-primary">Show on Map</button>
        </form>

        <?php if ($error): ?>
            <div class="alert alert-danger mt-4"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($result !== null && isset($result['lat']) && isset($result['lon'])): ?>
            <div class="mt-4">
                <label class="form-label">Location for <?php echo htmlspecialchars($result['query']); ?></label>
                <div class="map-container" style="position: relative; overflow: hidden; padding-top: 56.25%;">
                    <iframe
                        width="100%"
                        height="450"
                        frameborder="0"
                        style="border:0; position: absolute; top: 0; left: 0; width: 100%; height: 100%;"
                        src="https://maps.google.com/maps?q=<?php echo $result['lat']; ?>,<?php echo $result['lon']; ?>&hl=en&z=10&output=embed"
                        allowfullscreen>
                    </iframe>
                </div>
                <div class="mt-3">
                    <p class="text-muted">Latitude: <?php echo htmlspecialchars($result['lat']); ?>, Longitude: <?php echo htmlspecialchars($result['lon']); ?></p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>
