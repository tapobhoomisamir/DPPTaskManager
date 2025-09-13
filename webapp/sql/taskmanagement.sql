CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
	role ENUM('Viewer','Authority','Administrator','Editor','Commentator') DEFAULT 'Viewer',
    password VARCHAR(255)
);


CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE departments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE tasktypes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE workweeks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    workweek VARCHAR(100),
    month ENUM('January','Febraury','March','April','May','June','July','August','September','October','November','December') DEFAULT 'January',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT,
    user_id INT,
	assign_by INT,
    title VARCHAR(150),
    description TEXT,
	department_id INT,
	tasktype_id INT,
	workweek_id INT,
    status ENUM('Pending','Completed','Awail Approval','Approval','Closed','Hold') DEFAULT 'Pending',	
	start_date TIMESTAMP NULL DEFAULT NULL,
    due_date TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
	FOREIGN KEY (assign_by) REFERENCES users(id) ON DELETE SET NULL,
	FOREIGN KEY (tasktype_id) REFERENCES tasktypes(id) ON DELETE CASCADE,
	FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE CASCADE,
	FOREIGN KEY (workweek_id) REFERENCES workweeks(id) ON DELETE CASCADE
);

CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
	task_id INT,
    comment VARCHAR(100),
    user_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,	
	FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE CASCADE,
	FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE attachments (
    id INT AUTO_INCREMENT PRIMARY KEY,
	task_id INT,
    attachment_url VARCHAR(100),
    user_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,	
	FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE CASCADE,
	FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
