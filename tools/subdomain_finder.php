<?php
$result = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $domain = trim($_POST['domain'] ?? '');

    if (empty($domain)) {
        $error = 'Please enter a domain name.';
    } else {
        $found_subdomains = [];
        $api_errors = [];

        // --- Source 1: crt.sh API Lookup ---
        $crtsh_url = "https://crt.sh/?q=%25.{$domain}&output=json";
        $crtsh_response = false;

        if (function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $crtsh_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_USERAGENT, 'ToolForge Subdomain Finder/1.0');
            $crtsh_response = curl_exec($ch);
            if (curl_errno($ch)) {
                $api_errors[] = 'crt.sh cURL Error: ' . curl_error($ch);
            }
            curl_close($ch);
        } else if (ini_get('allow_url_fopen')) {
            $context = stream_context_create([
                'http' => [
                    'timeout' => 15,
                    'header' => 'User-Agent: ToolForge Subdomain Finder/1.0\r\n'
                ],
            ]);
            $crtsh_response = @file_get_contents($crtsh_url, false, $context);
            if ($crtsh_response === false) {
                $api_errors[] = 'crt.sh file_get_contents Error: Could not connect to crt.sh API.';
            }
        } else {
            $api_errors[] = 'crt.sh: Neither cURL nor allow_url_fopen is enabled.';
        }

        if ($crtsh_response) {
            $crtsh_data = json_decode($crtsh_response, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($crtsh_data)) {
                foreach ($crtsh_data as $entry) {
                    if (isset($entry['common_name'])) {
                        $cn = strtolower($entry['common_name']);
                        if (strpos($cn, $domain) !== false) {
                            $found_subdomains[] = $cn;
                        }
                    }
                    if (isset($entry['name_value'])) {
                        $names = explode('\n', $entry['name_value']);
                        foreach ($names as $name) {
                            $name = strtolower(trim(str_replace('DNS:', '', $name)));
                            if (strpos($name, $domain) !== false && $name !== $domain) {
                                $found_subdomains[] = $name;
                            }
                        }
                    }
                }
            } else {
                $api_errors[] = 'crt.sh: Failed to decode API response or invalid data. Raw response (first 200 chars): ' . substr($crtsh_response, 0, 200) . '...';
            }
        } else if (empty($api_errors)) {
            $api_errors[] = 'crt.sh: No response received from API.';
        }

        // --- Source 2: HackerTarget HostSearch API Lookup ---
        $hackertarget_url = "https://api.hackertarget.com/hostsearch/?q={$domain}";
        $hackertarget_response = false;

        if (function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $hackertarget_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_USERAGENT, 'ToolForge Subdomain Finder/1.0');
            $hackertarget_response = curl_exec($ch);
            if (curl_errno($ch)) {
                $api_errors[] = 'HackerTarget cURL Error: ' . curl_error($ch);
            }
            curl_close($ch);
        } else if (ini_get('allow_url_fopen')) {
            $context = stream_context_create([
                'http' => [
                    'timeout' => 15,
                    'header' => 'User-Agent: ToolForge Subdomain Finder/1.0\r\n'
                ],
            ]);
            $hackertarget_response = @file_get_contents($hackertarget_url, false, $context);
            if ($hackertarget_response === false) {
                $api_errors[] = 'HackerTarget file_get_contents Error: Could not connect to API.';
            }
        } else {
            $api_errors[] = 'HackerTarget: Neither cURL nor allow_url_fopen is enabled.';
        }

        if ($hackertarget_response) {
            $lines = explode("\n", $hackertarget_response);
            foreach ($lines as $line) {
                $parts = explode(',', $line);
                if (isset($parts[0]) && strpos($parts[0], $domain) !== false) {
                    $found_subdomains[] = strtolower($parts[0]);
                }
            }
        } else if (empty($api_errors)) {
            $api_errors[] = 'HackerTarget: No response received from API.';
        }

        $found_subdomains = array_unique($found_subdomains); // Remove duplicates
        sort($found_subdomains); // Sort alphabetically

        if (empty($found_subdomains)) {
            $result = 'No subdomains found for ' . htmlspecialchars($domain) . '. This might be due to API issues or no subdomains existing.';
        } else {
            $result = implode("\n", $found_subdomains);
        }

        if (!empty($api_errors)) {
            $error = "Some API sources encountered issues:\n" . implode("\n", $api_errors) . "\n\nResults below might be incomplete.";
        }
    }
}

include __DIR__ . '/../templates/header.php';
?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><i class="bi bi-globe me-2"></i>Subdomain Finder</h5>
        <p class="card-text">Discover subdomains for a given domain name using multiple public sources like certificate transparency logs (crt.sh) and other OSINT APIs (HackerTarget). Note: External API calls might be restricted on some shared hosting environments.</p>
        <form method="POST" action="?page=subdomain_finder">
            <div class="mb-3">
                <label for="domain" class="form-label">Domain Name</label>
                <input type="text" class="form-control" id="domain" name="domain" value="<?php echo htmlspecialchars($_POST['domain'] ?? ''); ?>" placeholder="example.com" required>
            </div>
            <button type="submit" class="btn btn-primary">Find Subdomains</button>
        </form>

        <?php if ($error): ?>
            <div class="alert alert-danger mt-4" style="white-space: pre-wrap;"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($result !== null): ?>
            <div class="mt-4">
                <label class="form-label">Found Subdomains</label>
                <div class="input-group">
                    <textarea class="form-control" id="subdomainResult" rows="10" readonly><?php echo htmlspecialchars($result); ?></textarea>
                    <button class="btn btn-outline-secondary copy-btn" type="button" data-target="subdomainResult">Copy</button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>