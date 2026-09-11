# Module 10: Guidance & Counseling

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
Tracks behavioral records, counseling session logs, career guidance notes (especially relevant since SHS is meant to prepare students for college/work/entrepreneurship).

**Keep in mind:**
- Highest sensitivity data in the system — restrict to Guidance role + Admin only, with strict audit logging.
- Should never be visible to regular subject teachers by default.

---

## Data Model (relevant slice of the master ERD)

```mermaid
erDiagram
    USER ||--o| STUDENT_PROFILE : extends
    STUDENT_PROFILE ||--o{ GUIDANCE_RECORD : has
    USER ||--o{ GUIDANCE_RECORD : logged_by
    USER ||--o{ AUDIT_LOG : triggers

    STUDENT_PROFILE {
        uuid id PK
        uuid user_id FK
        string lrn
        date birth_date
        int grade_level
    }

    GUIDANCE_RECORD {
        uuid id PK
        uuid student_id FK
        uuid logged_by FK
        string category
        text notes
        datetime created_at
    }

    USER {
        uuid id PK
        string full_name
        string email
        string password_hash
        string status
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
    A[Referral trigger: low grades, poor attendance, teacher/self-referral] --> B[Guidance Counselor opens Guidance Record]
    B --> C[Log session notes & category]
    C --> D{Sensitive/restricted case?}
    D -->|Yes| E[Restrict visibility to Guidance + Admin only]
    D -->|No| F[Optional summary shared with Adviser]
    E --> G[Schedule follow-up session]
    F --> G
    G --> H[Track case status: open/monitoring/closed]
    H --> I[Audit log records every access]
```

---

## Cross-Cutting Concerns That Apply Here

- **Data privacy:** Student data (especially minors) requires strict compliance (e.g., Philippine Data Privacy Act / DPA 2012, or GDPR/FERPA equivalents if international). Encrypt sensitive fields, log all access to guidance/counseling records.
- **Auditability:** Grades, attendance, and guidance records all need "who changed what, when" trails — build this in from the start, it's expensive to bolt on later.

---

## Build Phase

**Phase 5 — Oversight** (see the master plan's Suggested Build Phases for the full sequence).

---

## Implementation Notes

Highest-sensitivity module in the system. Every read of GUIDANCE_RECORD must write an AUDIT_LOG row — enforce this at the repository/service layer, not just in policies.

---

## Related Modules

- [Module 1: User & Role Management](./01-user-role-management.md)
- [Module 4: Attendance](./04-attendance.md)
- [Module 13: Reports & Analytics](./13-reports-analytics.md)
