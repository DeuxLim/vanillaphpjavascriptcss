<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Basic To do App</title>
    <link rel="stylesheet" href="/css/app/dashboard/style.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <h1 class="logo">Todo App</h1>
            <div class="user-menu">
                <span class="welcome-text">Welcome, <?= htmlspecialchars($_SESSION['user_name'])?>!</span>
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
                <button class="add-task-btn" onclick="showAddTaskForm()">+ Add New Task</button>
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
            <div class="add-task-form" id="addTaskForm" style="display: none;">
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
                            <input type="date" name="task_due" value="${task.task_due}">
                        </div>

                        <div class="form-actions">
                            <button type="button" class="cancel-btn" onclick="hideAddTaskForm()">Cancel</button>
                            <button type="submit" class="submit-btn">Add Task</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tasks List -->
            <div class="tasks-section">
                <h3>Your Tasks</h3>
                <div class="tasks-list">
                    <!-- Sample Task 1 -->
                    <div class="task-item">
                        <div class="task-checkbox">
                            <input type="checkbox" id="task1" />
                            <label for="task1"></label>
                        </div>
                        <div class="task-content">
                            <h4>Complete project documentation</h4>
                            <p>Write comprehensive documentation for the new feature</p>
                            <div class="task-meta">
                                <span class="priority high">High Priority</span>
                                <span class="date">Due: Today</span>
                            </div>
                        </div>
                        <div class="task-actions">
                            <button class="edit-btn">✏️</button>
                            <button class="delete-btn">🗑️</button>
                        </div>
                    </div>

                    <!-- Sample Task 2 -->
                    <div class="task-item completed">
                        <div class="task-checkbox">
                            <input type="checkbox" id="task2" checked />
                            <label for="task2"></label>
                        </div>
                        <div class="task-content">
                            <h4>Review code changes</h4>
                            <p>Review the pull requests from the development team</p>
                            <div class="task-meta">
                                <span class="priority medium">Medium Priority</span>
                                <span class="date">Completed: Yesterday</span>
                            </div>
                        </div>
                        <div class="task-actions">
                            <button class="edit-btn">✏️</button>
                            <button class="delete-btn">🗑️</button>
                        </div>
                    </div>

                    <!-- Sample Task 3 -->
                    <div class="task-item">
                        <div class="task-checkbox">
                            <input type="checkbox" id="task3" />
                            <label for="task3"></label>
                        </div>
                        <div class="task-content">
                            <h4>Update website design</h4>
                            <p>Implement the new design mockups for the homepage</p>
                            <div class="task-meta">
                                <span class="priority low">Low Priority</span>
                                <span class="date">Due: Next Week</span>
                            </div>
                        </div>
                        <div class="task-actions">
                            <button class="edit-btn">✏️</button>
                            <button class="delete-btn">🗑️</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Load your JS file -->
  <script src="/js/tasks.js" defer></script>
</body>
</html>