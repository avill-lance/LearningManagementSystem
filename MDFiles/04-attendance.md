# Module 4: Attendance

## System Overview

This file is one module out of a set of module-context files for the **Senior High School LMS**
— a Learning Management System for Grades 11–12 under the Philippine K-12 Tracks/Strands
curriculum (Academic, TVL, Sports, Arts & Design tracks; STEM, ABM, HUMSS, GAS, etc. strands),
adaptable to any SHS setup.

**Tech stack:** Laravel (backend/API) + Vue.js (frontend SPA). See **Section 0 — Tech Stack** in
[`senior-high-school-lms-plan.md`](../senior-high-school-lms-plan.md) for the full stack decision,
the strict scalability/readability rule that governs all code in this project, and an explanation
of the Laravel file structure.

**Full plan:** [`senior-high-school-lms-plan.md`](../senior-high-school-lms-plan.md) contains
the complete module list, the full ERD, all module flowcharts, cross-cutting considerations,
and build phases. This file extracts and expands only what's relevant to this one module so it
can be handed to a developer (or an AI coding assistant) as a self-contained brief.

---

## Function
Daily/per-subject attendance logging, absence/tardy tracking, and automated alerts to guardians.

**Keep in mind:**
- SHS often tracks attendance per subject period, not just once a day.
- Needs an audit trail (who logged/edited an entry and when) — this feeds into official DepEd forms.
- Excessive absence triggers should notify Guidance/Adviser automatically.

---

## Data Model (relevant slice of the master ERD)

```mermaid
erDiagram
    CLASS_SCHEDULE ||--o{ ATTENDANCE_RECORD : generates
    STUDENT_PROFILE ||--o{ ATTENDANCE_RECORD : has

    CLASS_SCHEDULE {
        uuid id PK
        uuid section_id FK
        uuid subject_id FK
        uuid teacher_id FK
        uuid room_id FK
        string day_of_week
        time start_time
        time end_time
    }

    STUDENT_PROFILE {
        uuid id PK
        uuid user_id FK
        string lrn
        date birth_date
        int grade_level
    }

    ATTENDANCE_RECORD {
        uuid id PK
        uuid class_schedule_id FK
        uuid student_id FK
        date attendance_date
        string status
        uuid logged_by FK
    }

    AUDIT_LOG {
        uuid id PK
        uuid user_id FK
        string action
        string entity_type
        uuid entity_id
        datetime timestamp
    }
```

---

## Module Flow

```mermaid
flowchart TD
    A[Class period starts] --> B[Teacher opens attendance sheet]
    B --> C[Mark each student: Present/Late/Absent/Excused]
    C --> D[Submit attendance record]
    D --> E[Log entry in Audit Log]
    E --> F{Student flagged for excessive absences?}
    F -->|Yes| G[Auto-notify Adviser & Guidance]
    F -->|No| H[Update attendance summary]
    G --> H
    H --> I[Guardian portal reflects updated attendance]
```

---

## Cross-Cutting Concerns That Apply Here

- **Auditability:** Grades, attendance, and guidance records all need "who changed what, when" trails — build this in from the start, it's expensive to bolt on later.
- **Offline resilience:** Design APIs to tolerate spotty connections (queue submissions, retry uploads).

---

## Build Phase

**Phase 2 — Daily Operations** (see the master plan's Suggested Build Phases for the full sequence).

---

## Implementation Notes

Attendance is logged per CLASS_SCHEDULE occurrence, not once a day — design the table and queries around that from the start.

---

## Related Modules

- [Module 3: Class & Scheduling](./03-class-scheduling.md)
- [Module 10: Guidance & Counseling](./10-guidance-counseling.md)
- [Module 13: Reports & Analytics](./13-reports-analytics.md)
- [Module 14: Notifications & Alerts](./14-notifications-alerts.md)
