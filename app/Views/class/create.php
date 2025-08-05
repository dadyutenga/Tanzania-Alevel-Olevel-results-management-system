<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Class</title>
    <link rel="stylesheet" href="<?= base_url('css/bootstrap.min.css') ?>">
</head>
<body>
    <div class="container mt-5">
        <h1>Add New Class</h1>
        <?php if (session()->has('error')): ?>
            <div class="alert alert-danger">
                <?= session('error') ?>
            </div>
        <?php endif; ?>
        <form action="<?= base_url('classes/store') ?>" method="post">
            <div class="mb-3">
                <label for="name" class="form-label">Class Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="level" class="form-label">Level</label>
                <input type="text" class="form-control" id="level" name="level" required>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="<?= base_url('classes') ?>" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
    <script src="<?= base_url('js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
