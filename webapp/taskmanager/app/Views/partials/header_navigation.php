
<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
    <div class="container">
        <a class="navbar-brand" href="#">Task Manager</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?= ($pageId === 'dashboard') ? 'active' : '' ?>" href="<?= base_url('') ?>">Dashboard</a>
                </li>
                <?php if ($sessionUser["role"] != null && ($sessionUser["role"] === 'Administrator' || $sessionUser["role"] === 'Authority' || $sessionUser["role"] === 'Incharge')) { ?>
                <li class="nav-item">
                    <a class="nav-link <?= ($pageId === 'tasks') ? 'active' : '' ?>"  href="<?= base_url('tasks') ?>">Works</a>
                </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>