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
| 02 | Enrollment & Academic Structure | [02-enrollment.md](./02-enrollment.md) | ⏳ Not started | ⏳ Not started | Tracks, Strands, Sections, Semesters, Enrollments |
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

## Cross-cutting concerns (referenced by every module)

These apply project-wide and are documented once here rather than repeated:

| Concern | Where it lives | Notes |
|---|---|---|
| **Data privacy** | [01-auth-accounts.md §6](./01-auth-accounts.md#6-cross-cutting-features-built-in) | Encrypt sensitive fields; log access to guidance records |
| **Multi-tenancy** | TBD (Phase 6) | `tenant_id` on every table if this ever serves >1 school |
| **Auditability** | [01-auth-accounts.md §6](./01-auth-accounts.md#6-cross-cutting-features-built-in) | `audit_logs` table is generic/polymorphic — wire in Phase 6 |
| **Mass assignment** | [01-auth-accounts.md §8.2](./01-auth-accounts.md#82-mass-assignment) | Every model needs `$fillable` |
| **Response envelope** | [01-auth-accounts.md §10.3](./01-auth-accounts.md#103-code-conventions-used-in-this-module) | `{success, reason, message, data}` |
| **Service layer** | [01-auth-accounts.md §2](./01-auth-accounts.md#2-architecture-mvc--service-layer) | Business logic in `Services/`, not controllers |
| **Form Requests** | [01-auth-accounts.md §3](./01-auth-accounts.md#3-file-map) | All validation lives here |
| **API Resources** | [01-auth-accounts.md §3](./01-auth-accounts.md#3-file-map) | JSON shape; strip sensitive fields |
| **RBAC (Policies)** | [01-auth-accounts.md §9](./01-auth-accounts.md#9-deferred-work-explicitly-not-done-yet) | Wire in when role-specific dashboards are built |

---

## Standard module doc structure

Every module doc follows the same 13-section template (established in
[01-auth-accounts.md](./01-auth-accounts.md)):
