<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Basic To do App</title>
    <link rel="stylesheet" href="/css/app/dashboard/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.23.0/dist/sweetalert2.all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.23.0/dist/sweetalert2.min.css" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <h1 class="logo"><a href="/">TaskFlow</a></h1>
            <div class="user-menu">
                <span class="welcome-text">Welcome <?= $user['first_name']?> ! </span>
                <form method="POST" action="/logout" style="display: inline;">
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Dashboard Header -->
            <div class="dashboard-header">
                <h2>My Tasks</h2>
                <button class="add-task-btn" id="addTaskFormBtn">+ Add New Task</button>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">📋</div>
                    <div class="stat-info total">
                        <h3></h3>
                        <p>Total Tasks</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">✅</div>
                    <div class="stat-info completed">
                        <h3></h3>
                        <p>Completed</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">⏳</div>
                    <div class="stat-info pending">
                        <h3></h3>
                        <p>Pending</p>
                    </div>
                </div>
            </div>

            <!-- Add Task Form (Hidden by default) -->
            <div class="add-task-form hidden" id="addTaskForm">
                <div class="form-wrapper">
                    <h3>Add New Task</h3>
                    <form action="/tasks" method="POST">
                        <div class="form-group">
                            <label for="task_title">Task Title</label>
                            <input type="text" id="task_title" name="task_title" required />
                        </div>
                        <div class="form-group">
                            <label for="task_description">Description</label>
                            <textarea id="task_description" name="task_description" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="task_priority">Priority</label>
                            <select id="task_priority" name="task_priority">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Due Date</label>
                            <input type="datetime-local" name="task_due">
                        </div>

                        <div class="form-actions">
                            <button type="button" class="cancel-btn">Cancel</button>
                            <button type="submit" class="submit-btn">Add Task</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tasks List -->
            <div class="tasks-section">
                <h3>Your Tasks</h3>
                <div class="tasks-list"></div>
            </div>
        </div>
    </main>

    <!-- Load your JS file -->
  <script type="module" src="/js/bootstrap.js" defer></script>
</body>
</html>