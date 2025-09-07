export default class TaskAPI {
    constructor()
    {

    }

    async request(uri, method = "GET", bodyData = null, options = {}) {
        const config = {
            method,
            headers: {
                "Content-Type": "application/json",
                ...options.headers
            },
            ...options
        };

        // only attach body for methods that allow it
        if (bodyData && !["GET", "HEAD"].includes(method.toUpperCase())) {
            config.body = JSON.stringify(bodyData);
        }

        const response = await fetch(uri, config);

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return await response.json();
    }

    async getTasks()
    {
        return this.request("/tasks");
    }

    async createTask(data)
    {
        return this.request("/tasks", "POST", data);
    }
}