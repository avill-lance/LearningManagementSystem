# Module 2: Enrollment & Academic Structure

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
Manages Tracks (Academic, TVL, Sports, Arts & Design), Strands (STEM, ABM, HUMSS, GAS, etc.), Grade Levels (11/12), Sections, and Semesters (SHS runs on 2 semesters/year, not quarters).

**Keep in mind:**
- A student's subjects depend on their Track/Strand + Semester — build this as a rules-driven curriculum map, not hardcoded.
- Handle strand transfers mid-year (rare but happens).
- Track "specialized subjects" vs "core subjects" vs "applied subjects" since SHS curricula separate these.

---

## Data Model (relevant slice of the master ERD)

```mermaid
erDiagram
    SCHOOL_YEAR ||--o{ SEMESTER : has
    TRACK ||--o{ STRAND : contains
    STRAND ||--o{ CURRICULUM_SUBJECT : defines
    SUBJECT ||--o{ CURRICULUM_SUBJECT : used_in
    SEMESTER ||--o{ CURRICULUM_SUBJECT : offered_in
    SECTION ||--o{ ENROLLMENT : contains
    STRAND ||--o{ SECTION : grouped_by
    SCHOOL_YEAR ||--o{ SECTION : belongs_to

    SCHOOL_YEAR {
        uuid id PK
        uuid school_id FK
        string label
        date start_date
        date end_date
    }

    SEMESTER {
        uuid id PK
        uuid school_year_id FK
        int semester_number
        date start_date
        date end_date
    }

    TRACK {
        uuid id PK
        string name
    }

    STRAND {
        uuid id PK
        uuid track_id FK
        string name
    }

    SUBJECT {
        uuid id PK
        string name
        string subject_type
    }

    CURRICULUM_SUBJECT {
        uuid id PK
        uuid strand_id FK
        uuid subject_id FK
        uuid semester_id FK
        boolean is_core
    }

    SECTION {
        uuid id PK
        uuid strand_id FK
        uuid school_year_id FK
        string name
        int grade_level
        uuid adviser_id FK
    }

    ENROLLMENT {
        uuid id PK
        uuid student_id FK
        uuid section_id FK
        uuid semester_id FK
        string status
    }
```

---

## Module Flow

```mermaid
flowchart TD
    A[New school year opens] --> B[Admin configures Tracks/Strands/Semesters]
    B --> C[Define Curriculum Subjects per Strand/Semester]
    C --> D[Student applies/registers for SHS]
    D --> E{Track/Strand selected?}
    E -->|Yes| F[Validate prerequisites e.g. Grade 10 completion]
    E -->|No| G[Guidance counsels student on strand choice]
    G --> E
    F --> H[Assign student to Section]
    H --> I[Create Enrollment record for Semester]
    I --> J[Auto-populate subjects from Curriculum map]
    J --> K[Enrollment confirmed]
```

---

## Cross-Cutting Concerns That Apply Here

- **Scalability of grading rules:** Different strands/tracks have different weight formulas — model this as data (a "Grading Template" table), not code.
- **Auditability:** Grades, attendance, and guidance records all need "who changed what, when" trails — build this in from the start, it's expensive to bolt on later.

---

## Build Phase

**Phase 1 — Foundation** (see the master plan's Suggested Build Phases for the full sequence).

---

## Implementation Notes

CURRICULUM_SUBJECT is the single source of truth that drives scheduling (3.x) and grading templates (7.x) — never hardcode which subjects belong to a strand/semester.

---

## Related Modules

- [Module 1: User & Role Management](./01-user-role-management.md)
- [Module 3: Class & Scheduling](./03-class-scheduling.md)
- [Module 7: Grading & Report Cards](./07-grading-report-cards.md)
- [Module 15: System Administration](./15-system-administration.md)
