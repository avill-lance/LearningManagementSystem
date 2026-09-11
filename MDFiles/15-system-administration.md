# Module 15: System Administration

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
School year setup/rollover, curriculum configuration, backup/audit logs, permission management.

**Keep in mind:**
- **School year rollover** is one of the trickiest features — promoting Grade 11 → Grade 12, archiving old sections, carrying forward only the right data (not duplicating). Design this as a first-class workflow, not an afterthought.

---

## Data Model (relevant slice of the master ERD)

```mermaid
erDiagram
    SCHOOL ||--o{ SCHOOL_YEAR : has
    ROLE_ASSIGNMENT }o--|| ROLE : refers_to

    SCHOOL {
        uuid id PK
        string name
        string address
    }

    SCHOOL_YEAR {
        uuid id PK
        uuid school_id FK
        string label
        date start_date
        date end_date
    }

    AUDIT_LOG {
        uuid id PK
        uuid user_id FK
        string action
        string entity_type
        uuid entity_id
        datetime timestamp
    }

    ROLE {
        uuid id PK
        string name
    }

    ROLE_ASSIGNMENT {
        uuid id PK
        uuid user_id FK
        uuid role_id FK
        uuid school_id FK
    }
```

---

## Module Flow

```mermaid
flowchart TD
    A[Admin initiates School Year Rollover] --> B[Archive current School Year data]
    B --> C[Promote Grade 11 students to Grade 12]
    C --> D{Graduating Grade 12?}
    D -->|Yes| E[Mark as Graduated, generate final transcript]
    D -->|No| F[Re-enroll into next Section/Semester]
    E --> G[Deactivate active enrollment]
    F --> H[Carry forward only required records]
    G --> I[New School Year structure created]
    H --> I
    I --> J[Reset/reassign teacher & section assignments]
```

---

## Cross-Cutting Concerns That Apply Here

- **Multi-tenancy:** If this LMS will serve multiple schools, isolate data per school (tenant_id) from day one — retrofitting multi-tenancy later is painful.
- **Auditability:** Grades, attendance, and guidance records all need "who changed what, when" trails — build this in from the start, it's expensive to bolt on later.

---

## Build Phase

**Phase 6 — Ops Hardening** (see the master plan's Suggested Build Phases for the full sequence).

---

## Implementation Notes

School year rollover is the trickiest single workflow in the whole system — model it as an explicit, resumable job/pipeline, not an ad hoc script.

---

## Related Modules

- [Module 1: User & Role Management](./01-user-role-management.md)
- [Module 2: Enrollment & Academic Structure](./02-enrollment-academic-structure.md)
