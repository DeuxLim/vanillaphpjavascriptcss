import TaskAPI from "../api/TaskAPI.js";

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

    getTasks()
    {
        return this.tasks;
    }
}