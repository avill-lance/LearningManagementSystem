# LMS Modules — Documentation Index

This folder contains one Markdown doc per module of the Senior High School LMS.
Each doc is a self-contained brief: purpose, ERD slice, endpoints, tests,
pitfalls, and the reusable build pattern.

The **build pattern** in each module's §10 is what you copy when starting a
new module. The **pitfalls** in §8 are what to read *before* you start, so
you don't hit the same Laravel/PowerShell/Docker rough edges twice.

---

## Module Index

| # | Module | Doc | Backend | Frontend | Notes |
|---|--------|-----|---------|----------|-------|
| 01 | Authentication & Accounts | [01-auth-accounts.md](./01-auth-accounts.md) | ✅ Done | ⏳ Pending Blade port | Login, register (student/teacher), username check, lockout, password reset scaffolding |
| 02 | Enrollment & Academic Structure | [02-enrollment-academic-structure.md](./02-enrollment-academic-structure.md) | 🚧 In progress | ⏳ Not started | Tracks, Strands, Sections, Semesters, Enrollments - Foundation work completed |
| 03 | Class & Scheduling | [03-class-scheduling.md](./03-class-scheduling.md) | ⏳ Not started | ⏳ Not started | Schedules, conflict detection, room allocation |
| 04 | Attendance | [04-attendance.md](./04-attendance.md) | ⏳ Not started | ⏳ Not started | Per-period attendance, audit trail, auto-notify adviser |
| 05 | Content & Learning Materials | [05-content-materials.md](./05-content-materials.md) | ⏳ Not started | ⏳ Not started | Upload/organize materials, versioning, draft vs published |
| 06 | Assignments, Quizzes & Assessments | [06-assessments.md](./06-assessments.md) | ⏳ Not started | ⏳ Not started | Assignments, quizzes, submissions, rubrics |
| 07 | Grading & Report Cards | [07-grading.md](./07-grading.md) | ⏳ Not started | ⏳ Not started | Weighted computation, configurable templates, lock/override |
| 08 | Communication & Announcements | [08-communication.md](./08-communication.md) | ⏳ Not started | ⏳ Not started | Announcements, direct messages, broadcast |
| 09 | Calendar & Events | [09-calendar.md](./09-calendar.md) | ⏳ Not started | ⏳ Not started | Academic calendar, per-role views |
| 10 | Guidance & Counseling | [10-guidance.md](./10-guidance.md) | ⏳ Not started | ⏳ Not started | Restricted-access records, session logs |
| 11 | Library / Resource Management | [11-library.md](./11-library.md) | ⏳ Not started | ⏳ Not started | Catalog, borrowing, e-resources (optional) |
| 12 | Parent / Guardian Portal | [12-guardian-portal.md](./12-guardian-portal.md) | ⏳ Not started | ⏳ Not started | Read-only grades/attendance, multi-ward switching |
| 13 | Reports & Analytics | [13-reports.md](./13-reports.md) | ⏳ Not started | ⏳ Not started | At-risk dashboards, DepEd-compliant exports |
| 14 | Notifications & Alerts | [14-notifications.md](./14-notifications.md) | ⏳ Not started | ⏳ Not started | Centralized engine: in-app, email, SMS |
| 15 | System Administration | [15-system-admin.md](./15-system-admin.md) | ⏳ Not started | ⏳ Not started | School year rollover, backups, permissions |

**Legend:** ✅ Done · 🚧 In progress · ⏳ Not started · ⚠️ Blocked

---

## Current Implementation Roadmap

### Phase 1 — Foundation: COMPLETED
- ✅ User & Role Management (Module 1): Authentication system implemented
- 🚧 Enrollment & Academic Structure (Module 2): Database foundation laid (UserFactory, DatabaseSeeder updated)
- ⏳ Class & Scheduling (Module 3): Not started
- ⏳ Attendance (Module 4): Not started
- ⏳ Content/Materials (Module 5): Not started

### Phase 2 — Daily Operations: NOT STARTED
- ⏳ Scheduling (Module 3)
- ⏳ Attendance (Module 4)
- ⏳ Content/Materials (Module 5)

### Phase 3 — Academics Core: NOT STARTED
- ⏳ Assignments/Quizzes (Module 6)
- ⏳ Grading engine (Module 7)
- ⏳ Report Cards (Module 7)

### Phase 4 — Engagement: NOT STARTED
- ⏳ Communication (Module 8)
- ⏳ Notifications (Module 14)
- ⏳ Calendar (Module 9)
- ⏳ Parent Portal (Module 12)

### Phase 5 — Oversight: NOT STARTED
- ⏳ Guidance (Module 10)
- ⏳ Reports/Analytics (Module 13)
- ⏳ Library (Module 11)

### Phase 6 — Ops Hardening: NOT STARTED
- ⏳ School year rollover (Module 15)
- ⏳ Audit logs
- ⏳ Backups
- ⏳ Multi-tenancy (if needed)

---

## Standard module doc structure

Every module doc follows the same 13-section template (established in
[01-auth-accounts.md](./01-auth-accounts.md)):
