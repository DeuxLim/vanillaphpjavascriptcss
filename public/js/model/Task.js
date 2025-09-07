export default class Task {
    constructor(data = {}) 
    {
        this.task_title = data.task_title || "";
        this.task_description = data.task_description || "";
        this.task_priority = data.task_priority || "";
        this.task_due = data.task_due || "";
        this.task_completed = data.task_completed || 0;
    }
}