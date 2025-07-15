<?php
$result = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_value = trim($_POST['input_value'] ?? '');
    $from_base = intval($_POST['from_base'] ?? 10);
    $to_base = intval($_POST['to_base'] ?? 10);

    if (empty($input_value)) {
        $error = 'Input value cannot be empty.';
    } elseif (!in_array($from_base, [2, 8, 10, 16]) || !in_array($to_base, [2, 8, 10, 16])) {
        $error = 'Invalid base selected. Please choose from 2, 8, 10, 16.';
    } else {
        try {
            // Convert to decimal first
            $decimal_value = base_convert($input_value, $from_base, 10);
            // Convert from decimal to target base
            $result = base_convert($decimal_value, 10, $to_base);
        } catch (Throwable $e) {
            $error = 'Conversion error: ' . $e->getMessage() . '. Please check your input and bases.';
        }
    }
}

include __DIR__ . '/../templates/header.php';
?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><i class="bi bi-calculator me-2"></i>Number Base Converter</h5>
        <p class="card-text">Convert numbers between different bases (Binary, Octal, Decimal, Hexadecimal). Useful for programmers and engineers working with various number systems.</p>
        <form method="POST" action="?page=number_base_converter">
            <div class="mb-3">
                <label for="input_value" class="form-label">Input Value</label>
                <input type="text" class="form-control" id="input_value" name="input_value" value="<?php echo htmlspecialchars($_POST['input_value'] ?? ''); ?>" required>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="from_base" class="form-label">From Base</label>
                    <select class="form-select" id="from_base" name="from_base">
                        <option value="2" <?php echo (isset($_POST['from_base']) && $_POST['from_base'] == 2) ? 'selected' : ''; ?>>Binary (Base 2)</option>
                        <option value="8" <?php echo (isset($_POST['from_base']) && $_POST['from_base'] == 8) ? 'selected' : ''; ?>>Octal (Base 8)</option>
                        <option value="10" <?php echo (!isset($_POST['from_base']) || $_POST['from_base'] == 10) ? 'selected' : ''; ?>>Decimal (Base 10)</option>
                        <option value="16" <?php echo (isset($_POST['from_base']) && $_POST['from_base'] == 16) ? 'selected' : ''; ?>>Hexadecimal (Base 16)</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="to_base" class="form-label">To Base</label>
                    <select class="form-select" id="to_base" name="to_base">
                        <option value="2" <?php echo (isset($_POST['to_base']) && $_POST['to_base'] == 2) ? 'selected' : ''; ?>>Binary (Base 2)</option>
                        <option value="8" <?php echo (isset($_POST['to_base']) && $_POST['to_base'] == 8) ? 'selected' : ''; ?>>Octal (Base 8)</option>
                        <option value="10" <?php echo (!isset($_POST['to_base']) || $_POST['to_base'] == 10) ? 'selected' : ''; ?>>Decimal (Base 10)</option>
                        <option value="16" <?php echo (isset($_POST['to_base']) && $_POST['to_base'] == 16) ? 'selected' : ''; ?>>Hexadecimal (Base 16)</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Convert</button>
        </form>

        <?php if ($error): ?>
            <div class="alert alert-danger mt-4"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($result !== null): ?>
            <div class="mt-4">
                <label class="form-label">Result</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="numberBaseResult" value="<?php echo htmlspecialchars($result); ?>" readonly>
                    <button class="btn btn-outline-secondary copy-btn" type="button" data-target="numberBaseResult">Copy</button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>
