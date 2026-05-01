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

<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger text-center">
        <?= $this->session->flashdata('error'); ?>
    </div>
<?php endif; ?>
<!-- ADD TASK FORM -->
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
<!-- //FILTERS & SORTING -->
<div class="mb-3">
    <a href="<?= base_url('index.php/tasks?status=all'); ?>" 
   class="btn btn-sm <?= empty($this->input->get('status')) ? 'btn-dark' : 'btn-secondary'; ?>">
   All
</a>

<a href="<?= base_url('index.php/tasks?status=pending'); ?>" 
   class="btn btn-sm <?= $this->input->get('status')=='pending' ? 'btn-dark' : 'btn-warning'; ?>">
   Pending
</a>

<a href="<?= base_url('index.php/tasks?status=completed'); ?>" 
   class="btn btn-sm <?= $this->input->get('status')=='completed' ? 'btn-dark' : 'btn-success'; ?>">
   Completed
</a>
    <!-- <a href="<?= base_url('index.php/tasks'); ?>" class="btn btn-secondary btn-sm">All</a>
    <a href="<?= base_url('index.php/tasks?status=pending'); ?>" class="btn btn-warning btn-sm">Pending</a>
    <a href="<?= base_url('index.php/tasks?status=completed'); ?>" class="btn btn-success btn-sm">Completed</a> -->

    <span class="ms-3"></span>

    <a href="<?= base_url('index.php/tasks?sort=due_date'); ?>" class="btn btn-outline-dark btn-sm">Sort by Date</a>
    <a href="<?= base_url('index.php/tasks?sort=priority'); ?>" class="btn btn-outline-dark btn-sm">Sort by Priority</a>
</div>
<br><br>
<!-- TASK LIST -->

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
    <?php foreach($tasks as $task): 
        $due_time = strtotime($task->due_date);
        $now = time();
        $diff_seconds = $due_time - $now;
$hours_left = $diff_seconds / 3600;

$is_urgent = ($diff_seconds > 0 && $diff_seconds <= 86400);
        // echo $task->title . " → " . $hours_left . " hours<br>";
        // $is_urgent = ($due_time - $now) <= 86400;
    ?>

   <tr class="<?= $is_urgent ? 'table-danger' : ''; ?>">
    <td><?= $task->title; ?></td>
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

    <!-- ACTION -->
    <td>
        <?php if ($task->status != 'completed'): ?>
            <a href="<?= base_url('index.php/tasks/complete/'.$task->id); ?>" class="btn btn-success btn-sm">✔</a>
        <?php endif; ?>

        <a href="<?= base_url('index.php/tasks/delete/'.$task->id); ?>" class="btn btn-danger btn-sm">✖</a>
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