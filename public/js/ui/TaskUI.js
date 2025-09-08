export default class TaskUI {
    constructor()
    {
        this.getElements();
    }

    getElements()
    {
        this.addTaskFormContainer = document.querySelector("#addTaskForm");
        this.addTaskForm = document.querySelector("#addTaskForm form");
        this.addTaskButton = document.querySelector("#addTaskFormBtn");
        this.addTaskCancelBtn = document.querySelector("#addTaskForm .cancel-btn");
        this.tasksContainer = document.querySelector('.tasks-list');
    }

    toggleElement(element, change = "hidden") 
    {
        element.classList.toggle(change);
    }

    toggleTaskEditForm(taskContainer)
    {
        const taskCard = taskContainer.querySelector(".task-item");
        const editForm = taskContainer.querySelector(".task-edit-form");
        
        this.toggleElement(editForm);
        this.toggleElement(taskCard);
    }

    async renderTasks(tasks){
        this.tasksContainer.innerHTML = "";
        let taskCard = "";
        tasks.forEach((task) => {
            taskCard += `
                <div class="task-container" data-id="${task.task_id}">
                    <div class="task-item ${task.task_completed ? "completed" : ""}" id="task-item-${task.task_id}">
                        <div class="task-checkbox">
                            <input class="task_status" type="checkbox" data-id="${task.task_id}" id="task_status" ${task.task_completed ? "checked" : ""}/>
                            <label for="task_status"></label>
                        </div>
                        <div class="task-content">
                            <h4>${task.task_title}</h4>
                            <p>${task.task_description}</p>
                            <div class="task-meta">
                                <span class="priority ${task.task_priority}">${task.task_priority} Priority</span>
                                <span class="date">${task.task_completed ? "Completed : " + task.task_completed_date : "Due: " + task.task_due}</span>
                            </div>
                        </div>
                        <div class="task-actions">
                            <button class="edit-btn">✏️</button>
                            <button class="delete-btn">🗑️</button>
                        </div>
                    </div>

                    <!-- Edit Task Form (Hidden by default) -->
                    <div class="edit-task-form task_${task.task_id} task-edit-form hidden">
                        <div class="form-wrapper">
                            <h3>Edit Task</h3>
                            <form data-id="${task.task_id}">
                                <div class="form-group">
                                    <label>Task Title</label>
                                    <input type="text" name="task_title" value="${task.task_title}" required />
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea name="task_description" rows="3">${task.task_description}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>Priority</label>
                                    <select name="task_priority">
                                        <option value="low" ${task.task_priority === 'low' ? 'selected' : ''}>Low</option>
                                        <option value="medium" ${task.task_priority === 'medium' ? 'selected' : ''}>Medium</option>
                                        <option value="high" ${task.task_priority === 'high' ? 'selected' : ''}>High</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Due Date</label>
                                    <input type="datetime-local" name="task_due" value="${task.task_due}">
                                </div>
                                <div class="form-actions">
                                    <button type="button" class="cancel-btn">Cancel</button>
                                    <button type="submit" class="submit-btn">Save Task</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            `;
        });

        this.tasksContainer.innerHTML += taskCard;
    }
}