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
        this.ui.addTaskForm.addEventListener("submit", (event) => this.handleAddTask(event));

        // Task Card Functionality
        this.ui.tasksContainer.addEventListener("click", (event) => {
            const taskContainer = event.target.closest(".task-container");
            if (!taskContainer) return;

            switch(true){
                case event.target.matches(".edit-btn, .cancel-btn") : 
                    this.ui.toggleTaskEditForm(taskContainer);
                break;
            }
        });
    }

    handleAddTask(event)
    {
        event.preventDefault();


    }
}