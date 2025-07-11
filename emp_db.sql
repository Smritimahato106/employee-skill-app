CREATE TABLE employees (
    emp_id INT AUTO_INCREMENT PRIMARY KEY,
    emp_name VARCHAR(100) UNIQUE NOT NULL
);

CREATE TABLE skills (
    skill_id INT AUTO_INCREMENT PRIMARY KEY,
    skill_name VARCHAR(100) UNIQUE NOT NULL
);

CREATE TABLE employee_skills (
    emp_id INT,
    skill_id INT,
    PRIMARY KEY (emp_id, skill_id),
    FOREIGN KEY (emp_id) REFERENCES employees(emp_id) ON DELETE CASCADE,
    FOREIGN KEY (skill_id) REFERENCES skills(skill_id) ON DELETE CASCADE
);
