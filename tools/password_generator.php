<?php
$result = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $length = intval($_POST['length'] ?? 12);
    $include_uppercase = isset($_POST['include_uppercase']);
    $include_lowercase = isset($_POST['include_lowercase']);
    $include_numbers = isset($_POST['include_numbers']);
    $include_symbols = isset($_POST['include_symbols']);

    $chars = '';
    if ($include_uppercase) $chars .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    if ($include_lowercase) $chars .= 'abcdefghijklmnopqrstuvwxyz';
    if ($include_numbers) $chars .= '0123456789';
    if ($include_symbols) $chars .= '!@#$%^&*()_+-=[]{}|;:,.<>?';

    if (empty($chars)) {
        $error = 'Please select at least one character type.';
    } elseif ($length <= 0) {
        $error = 'Password length must be greater than 0.';
    } else {
        $password = '';
        $chars_length = strlen($chars);
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[rand(0, $chars_length - 1)];
        }
        $result = $password;
    }
}

include __DIR__ . '/../templates/header.php';
?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><i class="bi bi-key-fill me-2"></i>Password Generator</h5>
        <p class="card-text">Generate strong, random passwords with customizable options. Create secure passwords for your accounts to enhance online safety.</p>
        <form method="POST" action="?page=password_generator">
            <div class="mb-3">
                <label for="length" class="form-label">Password Length</label>
                <input type="number" class="form-control" id="length" name="length" value="<?php echo htmlspecialchars($_POST['length'] ?? 12); ?>" min="1" max="64" required>
            </div>
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="include_uppercase" id="include_uppercase" <?php echo (isset($_POST['include_uppercase']) || !isset($_POST['length'])) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="include_uppercase">Include Uppercase (A-Z)</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="include_lowercase" id="include_lowercase" <?php echo (isset($_POST['include_lowercase']) || !isset($_POST['length'])) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="include_lowercase">Include Lowercase (a-z)</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="include_numbers" id="include_numbers" <?php echo (isset($_POST['include_numbers']) || !isset($_POST['length'])) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="include_numbers">Include Numbers (0-9)</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="include_symbols" id="include_symbols" <?php echo (isset($_POST['include_symbols']) || !isset($_POST['length'])) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="include_symbols">Include Symbols (!@#$)</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Generate Password</button>
        </form>

        <?php if ($error): ?>
            <div class="alert alert-danger mt-4"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($result !== null): ?>
            <div class="mt-4">
                <label class="form-label">Generated Password</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="generatedPassword" value="<?php echo htmlspecialchars($result); ?>" readonly>
                    <button class="btn btn-outline-secondary copy-btn" type="button" data-target="generatedPassword">Copy</button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>
