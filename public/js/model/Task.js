export default class Task {
    constructor(data = {}) 
    {
        this.task_id = data.task_id || 0
        this.task_title = data.task_title || "";
        this.task_description = data.task_description || "";
        this.task_priority = data.task_priority || "";
        this.task_due = data.task_due || "";
        this.created_at = data.created_at || "";
        this.updated_at = data.updated_at || "";
        this.task_owner = data.task_owner || 0;
        this.task_completed = data.task_completed || 0;
        this.task_completed_date = data.task_completed_date || "";
        this.task_deleted = data.task_deleted || 0;
    }
}