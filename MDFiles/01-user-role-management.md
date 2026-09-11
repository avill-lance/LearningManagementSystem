# Module 1: User & Role Management

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
Central identity system for Admins, Registrars, Teachers/Advisers, Students, Parents/Guardians, and Guidance Counselors. Handles authentication, role-based access control (RBAC), and profile data.

**Keep in mind:**
- One student may have multiple guardians; one guardian may have multiple children in the system.
- Teachers can be "subject teachers" and/or "advisers" (homeroom) — these are different permission scopes.
- Support account status: active, suspended, graduated, transferred-out.
- Plan for bulk import (CSV) at start-of-year enrollment.

---

## Data Model (relevant slice of the master ERD)

```mermaid
erDiagram
    SCHOOL ||--o{ USER : employs_enrolls
    USER ||--o{ ROLE_ASSIGNMENT : has
    ROLE_ASSIGNMENT }o--|| ROLE : refers_to
    USER ||--o| STUDENT_PROFILE : extends
    USER ||--o| TEACHER_PROFILE : extends
    USER ||--o| GUARDIAN_PROFILE : extends
    GUARDIAN_PROFILE ||--o{ STUDENT_GUARDIAN : links
    STUDENT_PROFILE ||--o{ STUDENT_GUARDIAN : links

    SCHOOL {
        uuid id PK
        string name
        string address
    }

    USER {
        uuid id PK
        string full_name
        string email
        string password_hash
        string status
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

    STUDENT_PROFILE {
        uuid id PK
        uuid user_id FK
        string lrn
        date birth_date
        int grade_level
    }

    TEACHER_PROFILE {
        uuid id PK
        uuid user_id FK
        string employee_id
        string specialization
    }

    GUARDIAN_PROFILE {
        uuid id PK
        uuid user_id FK
        string relationship
        string contact_number
    }

    STUDENT_GUARDIAN {
        uuid id PK
        uuid student_id FK
        uuid guardian_id FK
        boolean is_primary
    }
```

---

## Module Flow

```mermaid
flowchart TD
    A[Admin creates account or bulk-imports CSV] --> B{Role type?}
    B -->|Student| C[Create Student Profile]
    B -->|Teacher| D[Create Teacher Profile]
    B -->|Guardian| E[Create Guardian Profile + Link to Student]
    C --> F[Assign default role permissions]
    D --> F
    E --> F
    F --> G[Send account credentials/invite]
    G --> H[User logs in first time]
    H --> I{Password reset required?}
    I -->|Yes| J[Force password change]
    I -->|No| K[Access role-based dashboard]
    J --> K
```

---

## Cross-Cutting Concerns That Apply Here

- **Data privacy:** Student data (especially minors) requires strict compliance (e.g., Philippine Data Privacy Act / DPA 2012, or GDPR/FERPA equivalents if international). Encrypt sensitive fields, log all access to guidance/counseling records.
- **Multi-tenancy:** If this LMS will serve multiple schools, isolate data per school (tenant_id) from day one — retrofitting multi-tenancy later is painful.
- **Auditability:** Grades, attendance, and guidance records all need "who changed what, when" trails — build this in from the start, it's expensive to bolt on later.

---

## Build Phase

**Phase 1 — Foundation** (see the master plan's Suggested Build Phases for the full sequence).

---

## Implementation Notes

This is the identity/auth backbone every other module depends on — build it first and keep it framework-agnostic where possible (thin controllers, logic in services).

---

## Related Modules

- [Module 2: Enrollment & Academic Structure](./02-enrollment-academic-structure.md)
- [Module 12: Parent/Guardian Portal](./12-parent-guardian-portal.md)
- [Module 15: System Administration](./15-system-administration.md)
