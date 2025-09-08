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

    async editTask(taskId, data){
        this.tasks = this.tasks.map((task) => {
            return task.task_id == Number(taskId) ? { ...task, ...data } : task;
        });

        await this.api.updateTask(taskId, data);   
    }

    async deleteTask(taskId)
    {
        this.tasks = this.tasks.filter(task => task.task_id !== Number(taskId));
        
        await this.api.deleteTask(taskId, { task_deleted : 1 });
    }

    getTasks()
    {
        return this.tasks;
    }
}