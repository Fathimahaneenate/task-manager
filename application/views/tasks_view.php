<!DOCTYPE html>
<html>

<head>
    <title>Task Manager</title>
    <style>
        .urgent {
            background-color: #ffcccc;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-5">

        <h2 class="mb-4 text-center">Task Manager</h2>

        <?php
        $total = count($tasks ?? []);
        $completed_count = 0;
        $pending_count = 0;
        $urgent_count = 0;
        foreach (($tasks ?? []) as $t) {
            if ($t->status == 'completed') $completed_count++;
            // if ($t->status == 'pending') 
              else $pending_count++;  
            $diff = strtotime($t->due_date) - time();
            if ($diff > 0 && $diff <= 86400) $urgent_count++;
        }
        ?>
        <div class="row mb-4 text-center">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Total</h6>
                        <h3 class="fw-bold text-primary mb-0"><?= $total ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Completed</h6>
                        <h3 class="fw-bold text-success mb-0"><?= $completed_count ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h6 class="text-muted mb-1">Pending</h6>
            <h3 class="fw-bold text-warning mb-0"><?= $pending_count ?></h3>
        </div>
    </div>
</div>
            
        </div>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger text-center">
                <?= $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= base_url('index.php/tasks/add'); ?>" class="row g-3 mb-4">

            <div class="col-md-4">
                <input type="text" name="title" class="form-control" placeholder="Task Title" required>
            </div>

            <div class="col-md-3">
                <input type="datetime-local" name="due_date" class="form-control" required>
            </div>

            <div class="col-md-3">
                <select name="priority" class="form-select">
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>
            </div>

            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Add</button>
            </div>

        </form>

        <hr>

        <div class="mb-3 d-flex justify-content-between align-items-center">
            <div>
                <a href="<?= base_url('index.php/tasks?status=all'); ?>"
                    class="btn btn-sm <?= empty($this->input->get('status')) ? 'btn-dark' : 'btn-secondary'; ?>">
                    All
                </a>
                <a href="<?= base_url('index.php/tasks?status=pending'); ?>"
                    class="btn btn-sm <?= $this->input->get('status') == 'pending' ? 'btn-dark' : 'btn-warning'; ?>">
                    Pending
                </a>
                <a href="<?= base_url('index.php/tasks?status=completed'); ?>"
                    class="btn btn-sm <?= $this->input->get('status') == 'completed' ? 'btn-dark' : 'btn-success'; ?>">
                    Completed
                </a>
            </div>
            <div>
                <a href="<?= base_url('index.php/tasks?sort=due_date'); ?>" class="btn btn-outline-dark btn-sm">↕ Sort by Date</a>
                <a href="<?= base_url('index.php/tasks?sort=priority'); ?>" class="btn btn-outline-dark btn-sm">↕ Sort by Priority</a>
            </div>
        </div>

        <br><br>


        <table class="table table-bordered table-hover">

            <thead class="table-dark">
                <tr>
                    <th>Title</th>
                    <th>Due Date</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

                <?php if (!empty($tasks)): ?>
                    <?php foreach ($tasks as $task):
                        $due_time = strtotime($task->due_date);
                        $now = time();
                        $diff_seconds = $due_time - $now;

                        $is_urgent = ($diff_seconds <= 86400 && $task->status != 'completed');
                        $is_overdue = ($diff_seconds < 0 && $task->status != 'completed');
                        // $is_urgent = ($diff_seconds > 0 && $diff_seconds <= 86400);

                    ?>
                        <tr class="<?= ($is_urgent || $is_overdue) ? 'table-danger' : ''; ?>">
                            <td>
                                <?= $task->title; ?>
                                <?php if ($is_overdue): ?>
                                    <span class="badge bg-danger ms-2">Due time finished</span>
                                <?php endif; ?>
                            </td>
                            <!-- <tr class="<?= $is_urgent ? 'table-danger' : ''; ?>">
                            <td><?= $task->title; ?></td> -->
                            <!-- <td><?= $task->due_date; ?></td> -->
                            <td><?= date('d M Y h:i A', strtotime($task->due_date)); ?></td>
                            <!-- PRIORITY -->
                            <td>
                                <?php if ($task->priority == 'high'): ?>
                                    <span class="badge bg-danger">High</span>
                                <?php elseif ($task->priority == 'medium'): ?>
                                    <span class="badge bg-warning text-dark">Medium</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Low</span>
                                <?php endif; ?>
                            </td>

                            <!-- STATUS -->
                            <td>
                                <?php if ($task->status == 'completed'): ?>
                                    <span class="badge bg-success">Completed</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">Pending</span>
                                <?php endif; ?>
                            </td>


                            <td>
                                <?php if ($task->status != 'completed'): ?>
                                    <a href="<?= base_url('index.php/tasks/complete/' . $task->id); ?>" class="btn btn-success btn-sm">
                                        <!-- ✔ -->
                                        Mark as Completed
                                    </a>
                                <?php endif; ?>

                                <!-- <a href="<?= base_url('index.php/tasks/delete/' . $task->id); ?>" class="btn btn-danger btn-sm">✖</a> -->
                            </td>
                        </tr>

                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No tasks found</td>
                    </tr>
                <?php endif; ?>

        </table>
    </div>
</body>

</html>