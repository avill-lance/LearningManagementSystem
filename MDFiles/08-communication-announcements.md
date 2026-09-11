# Module 8: Communication & Announcements

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
School-wide, section-wide, and subject-specific announcements; direct messaging between teachers/students/guardians; emergency broadcast (e.g., class suspension).

**Keep in mind:**
- Guardians should get a simplified/read-only channel, not full access to internal teacher discussions.
- Push/SMS/email fallback matters — not everyone checks the portal daily.

---

## Data Model (relevant slice of the master ERD)

```mermaid
erDiagram
    USER ||--o{ ANNOUNCEMENT : posts
    SECTION ||--o{ ANNOUNCEMENT : targeted_to
    USER ||--o{ MESSAGE : sends
    USER ||--o{ MESSAGE : receives

    USER {
        uuid id PK
        string full_name
        string email
        string password_hash
        string status
    }

    ANNOUNCEMENT {
        uuid id PK
        uuid posted_by FK
        uuid section_id FK
        string title
        text body
        datetime posted_at
    }

    MESSAGE {
        uuid id PK
        uuid sender_id FK
        uuid receiver_id FK
        text body
        datetime sent_at
    }

    SECTION {
        uuid id PK
        uuid strand_id FK
        uuid school_year_id FK
        string name
        int grade_level
        uuid adviser_id FK
    }
```

---

## Module Flow

```mermaid
flowchart TD
    A[User composes announcement or message] --> B{Audience scope?}
    B -->|School-wide| C[Admin publishes to all users]
    B -->|Section-wide| D[Teacher/Adviser publishes to section]
    B -->|Direct message| E[Send to specific user]
    C --> F[Trigger Notification]
    D --> F
    E --> F
    F --> G{Channel preference?}
    G -->|In-app only| H[Deliver in-app]
    G -->|Email/SMS fallback| I[Deliver via email/SMS + in-app]
```

---

## Cross-Cutting Concerns That Apply Here

- **Offline resilience:** Design APIs to tolerate spotty connections (queue submissions, retry uploads).
- **Localization:** Support Filipino/English bilingual UI if targeting Philippine public schools.

---

## Build Phase

**Phase 4 — Engagement** (see the master plan's Suggested Build Phases for the full sequence).

---

## Implementation Notes

Every announcement/message should raise a NOTIFICATION event (module 14) rather than pushing directly — keep delivery channels decoupled from content creation.

---

## Related Modules

- [Module 1: User & Role Management](./01-user-role-management.md)
- [Module 12: Parent/Guardian Portal](./12-parent-guardian-portal.md)
- [Module 14: Notifications & Alerts](./14-notifications-alerts.md)
