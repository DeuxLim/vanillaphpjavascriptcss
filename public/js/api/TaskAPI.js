export default class TaskAPI {
    constructor()
    {

    }

    async getTasks()
    {
        const response = await fetch("/tasks");
        const data = await response.json();
        return data;
    }

    async getTask()
    {

    }

    async createTask()
    {

    }

    async updateTask()
    {

    }

    async deleteTask()
    {

    }
}