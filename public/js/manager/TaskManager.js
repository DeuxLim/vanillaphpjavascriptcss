import TaskAPI from "../api/TaskAPI.js";
import Task from "../model/Task.js";

export default class TaskManager {
    constructor()
    {
        this.api = new TaskAPI();

        this.tasks = [];
        this.total = 0;
        this.pending = 0;
        this.completed = 0;
    }

    async loadTasks()
    {
        const response = await this.api.getTasks();
        if(response.status === "success"){
            this.tasks = response.data.tasks;
        }
    }

    async addTask(form)
    {
        const formData = Object.fromEntries(new FormData(form).entries());

        // add validation here...
        const taskData = formData;

        await this.api.createTask(taskData);
    }

    getTasks()
    {
        return this.tasks;
    }
}