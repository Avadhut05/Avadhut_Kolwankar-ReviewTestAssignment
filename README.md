#  Lecture Scheduling Module (Filament Admin + Instructor Panel)

##  Overview

This project is a **Lecture Scheduling Module** built using **Laravel + Filament**.
It allows administrators to manage courses, assign lectures (batches), and schedule instructors efficiently while ensuring **no scheduling conflicts occur**.

The system enforces strict validation so that:

* An instructor **cannot be assigned multiple lectures on the same date**
* Lecture schedules **never clash**

---

##  Features

###  Admin Panel

Accessible via `/admin`

* View list of all instructors
* Add and manage courses
* Assign lectures (batches) to courses
* Assign instructors to lectures with dates
* Inside INstructors and courses we can see all the associated lectures
* Prevent duplicate instructor scheduling on the same date
* Upload course images

###  Instructor Panel

Accessible via `/instructor`

* Login as instructor
* View assigned lectures
* See course name + lecture date

---

##  Course Structure

Each **Course** contains:

* **Name**
* **Level**
* **Description**
* **Image**
* **Lectures (Batches)**

Each **Lecture** contains:

* Assigned **Instructor**
* **Date**
* (Optional: Time, Duration if implemented)

---

##  Business Rules

*  An instructor **cannot have more than one lecture on the same date**
*  A lecture date cannot overlap for the same instructor
*  Multiple lectures allowed for a course (different batches)
*  Courses can be assigned to different instructors on different dates

---

##  Routes / Links

###  Admin Routes

| Method | Route                        | Description              |
| ------ | ---------------------------- | ------------------------ |
| GET    | `/admin/login`               | Admin Login              |
| POST   | `/admin/logout`              | Admin Logout             |
| GET    | `/admin/users`               | List Users (Instructors) |
| GET    | `/admin/users/create`        | Create User              |
| GET    | `/admin/users/{record}/edit` | Edit User                |

---

###  Instructor Routes

| Method | Route                           | Description            |
| ------ | ------------------------------- | ---------------------- |
| GET    | `/instructor/login`             | Instructor Login       |
| POST   | `/instructor/logout`            | Instructor Logout      |
| GET    | `/instructor`                   | Instructor Dashboard   |
| GET    | `/instructor/lectures`          | List Assigned Lectures |
| GET    | `/instructor/lectures/{record}` | View Lecture Details   |

---

##  Tech Stack

* **Backend:** Laravel
* **Admin Panel:** Filament
* **Database:** MySQL
* **Authentication:** Filament Auth (Multi-panel setup)

---

##  Panels Access

* **Admin Panel:**
  `http://172.236.179.47/admin/login`

* **Instructor Panel:**
  `http://172.236.179.47/instructor/login`

```

##  Author

Developed as part of a lecture scheduling system requirement.

