Test Task


Write a CRUD in Laravel.


It should have 5 entities: Users, Projects, Labels, Countries & Continents.


It should be possible to add multiple projects to multiple users and multiple labels to multiple projects.

For example: 

User “John” has created a project “MyWebsite” and added labels “personal” & “wordpress” to it.

User “Luis” has created a project “WorkProjectX” and added no labels.

User “Happy” has projects “MyWebsite” and “WorkProjectX” linked.


Projects are visible if a user created it or has been linked to it, but can be only deleted by a user who created it.


Labels are visible if a user created it or has a project linked with this label. Labels can be deleted only by a user who created it.


HTTP Methods:

    Users
    Add users (create & add an email with verification token (plain text) to queue)
    Verify user
    List users (filter by name OR/AND email OR/AND verified OR/AND country)
    Edit users
    Delete users 
    (HINT: Pay attention to “userS”: bulk actions)
    Projects
    Add projects
    Link projects to users
    List projects incl. labels (filter by user.email OR/AND user.continent OR/AND labels)
    Delete projects
    Labels
    Add labels
    Link labels to projects
    List labels (filter by user.email OR/AND projects)
    Delete labels


Requirements:

Use Git with proper commit messages (Make a clean initial commit & multiple afterward)

Use the latest stable Laravel version & follow its concepts

Use MySQL database

Add everything as Seed

Use RabbitMQ or MySQL for queues

Use Laravel’s Migrations (incl. Seeding), Models, Queues & Service Providers

Pay attention to a good MySQL structure

Try to follow good principles of PHP (and Laravel)

NO FRONTEND required, only API (incl. DB)


Entities:

Users: Name, Email, Verified & Country (No Continent here please)

(seed some example data, min. 10 rows)

Projects: Name

Labels: Name

Countries: import from http://country.io/names.json 

Continents: import from http://country.io/continent.json
