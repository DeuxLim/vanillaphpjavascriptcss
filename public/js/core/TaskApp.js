import TaskAPI from "../api/TaskAPI.js";
import TaskManager from "../manager/TaskManager.js";
import Task from "../model/Task.js";
import TaskUI from "../ui/TaskUI.js";

export default class TaskApp {
    constructor()
    {
        this.task = new Task;
        this.taskManager = new TaskManager;
        this.ui = new TaskUI;
        this.api = new TaskAPI;
    }

    async start()
    {
        await this.taskManager.loadTasks();
        this.ui.renderTasks(this.taskManager.getTasks());
        this.bindListeners();
    }

    bindListeners()
    {
        // Add Task Functionality
        this.ui.addTaskButton.addEventListener("click", () => this.ui.toggleElement(this.ui.addTaskFormContainer));
        this.ui.addTaskCancelBtn.addEventListener("click", () => this.ui.toggleElement(this.ui.addTaskFormContainer));
        this.ui.addTaskForm.addEventListener("submit", async (event) => await this.handleAddTask(event));

        // Task Card Functionality
        this.ui.tasksContainer.addEventListener("click", (event) => {
            const taskCard = event.target.closest(".task-container");
            if (!taskCard) return;

            switch(true){
                case event.target.matches(".edit-btn, .cancel-btn") : 
                    this.ui.toggleTaskEditForm(taskCard);
                break;

                case event.target.matches(".delete-btn") : 
                    this.handleDeleteTask(taskCard);
                break;

                case event.target.matches(".task_status") : 
                    const now = new Date();
                    const formattedDate = now.toISOString().slice(0, 19).replace("T", " ");
                    this.handleUpdateTask(taskCard, { task_completed : event.target.checked ? 1 : 0, task_completed_date : formattedDate});
                break;
            }
        });

        // Task Card Edit Form
        this.ui.tasksContainer.addEventListener("submit", (event) => {
            event.preventDefault();
            const taskCard = event.target.closest(".task-container");
            if (!taskCard) return;

            switch(true){
                case event.target.matches(".editTaskForm") : 
                    let editFormData = new FormData(event.target);
                    let updatedFields = Object.fromEntries(editFormData);
                    
                    if (updatedFields.task_due) {
                        updatedFields.task_due = updatedFields.task_due.replace("T", " ") + ":00";
                    }                    
                    
                    this.handleUpdateTask(taskCard, updatedFields);
                break;
            }
        });
    }

    async handleAddTask(event)
    {
        event.preventDefault();

        await this.taskManager.addTask(this.ui.addTaskForm);

        await this.taskManager.loadTasks();
        this.ui.addTaskCancelBtn.click();
        this.ui.renderTasks(this.taskManager.getTasks());        
    }

    async handleDeleteTask(taskCard)
    {
        this.taskManager.deleteTask(taskCard.dataset.id);
        this.ui.renderTasks(this.taskManager.getTasks());
    }

    async handleUpdateTask(taskCard, data)
    {
        await this.taskManager.editTask(taskCard.dataset.id, data);
        this.ui.renderTasks(this.taskManager.getTasks());
    }
}