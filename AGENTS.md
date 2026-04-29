# AGENTS.md - Mini OTA Booking Platform

This file defines mandatory rules for all AI coding agents working in this repository.

Agents must read this file before making any change.

---

## 1. Project Summary

Mini OTA Booking Platform is a booking and property management system for small homestay/hotel businesses.

The system includes:

- Spring Boot backend API
- Thymeleaf SEO website
- React booking app and dashboards
- Website Config mini CMS
- PostgreSQL
- Redis
- Object storage
- Future Flutter mobile app
- Docker/VPS deployment

The core business challenge is preventing double-booking while supporting room search, hold, booking, owner calendar management, sale support, admin operations, and SEO landing pages.

## 2. Non-negotiable Architecture Rules

Do not change these decisions unless the user explicitly asks:

1. Spring Boot modular monolith for MVP.
2. PostgreSQL is the source of truth.
3. Redis is only optimization for lock/cache, not the final correctness layer.
4. Correctness must rely on PostgreSQL transaction, row lock, and constraints.
5. Thymeleaf handles SEO/public indexed pages.
6. React handles `/app/*` only.
7. API lives under `/api/v1/*`.
8. Mobile app uses the same `/api/v1/*`.
9. Website Config CMS controls only Thymeleaf SEO/marketing content.
10. React app text is not controlled by CMS in MVP.
11. No microservices in MVP.
12. No Kubernetes in MVP.
13. No Kafka in MVP.
14. No payment gateway in MVP unless a phase explicitly says so.
15. No Next.js.
16. No Redux.

## 3. Route Namespace Rules

### Thymeleaf SEO Routes

These are server-rendered by Spring Boot + Thymeleaf:

- `/`
- `/rooms/{slug}`
- `/blog`
- `/blog/{slug}`
- `/homestay-*`
- `/dich-vu-quan-ly-homestay`
- `/phan-mem-quan-ly-dat-phong`
- `/sitemap.xml`
- `/robots.txt`

### React Routes

All React routes must be under:

```text
/app/*
```

Examples:

```text
/app/login
/app/register
/app/rooms
/app/rooms/:id
/app/booking/:holdId
/app/me/bookings
/app/dashboard/owner
/app/dashboard/sale
/app/dashboard/admin
```

Never create React routes outside `/app/*`.

### API Routes

All REST APIs must be under:

```text
/api/v1/*
```

### WebSocket

WebSocket endpoint:

```text
/ws
```

## 4. Tech Stack Rules

### Backend

Use:

- Java 17 or Java 21
- Spring Boot
- Spring Web
- Spring Security
- Spring Data JPA
- PostgreSQL
- Flyway
- Redis
- Bean Validation
- Spring Actuator
- Thymeleaf
- Testcontainers for integration tests

### Frontend React

Use:

- React
- Vite
- TypeScript
- React Router DOM
- Axios
- TanStack Query
- Zustand
- Ant Design
- Tailwind CSS
- React Hook Form
- Zod
- Dayjs

Do not use:

- Next.js
- Redux
- Moment.js

## 5. Coding Workflow Rules

Before coding, always:

- Read `AGENTS.md`.
- Read relevant docs in `/docs`.
- State the selected phase.
- List files to create or modify.
- List what is out of scope.
- Confirm acceptance criteria.

After coding, always report:

- Files created/modified.
- What was implemented.
- What was not implemented.
- Commands run.
- Test/build result.
- Known risks or follow-up tasks.

Never silently skip tests.

## 6. Phase Discipline

Do not code the whole project in one task.

Always work phase by phase.

Current planned phases:

1. Thymeleaf CMS Foundation
2. Backend Booking Core
3. React Foundation
4. Customer Booking Flow
5. Owner Calendar
6. Sale/Admin
7. Website Config Admin UI
8. Mobile Customer App
9. Production Hardening

If the user asks for a phase, only implement that phase.

Do not add unrelated features.

## 7. Backend Rules

### Booking Correctness

The booking system must follow:

```text
Customer search room
-> place hold
-> hold active for 15 minutes
-> confirm booking
-> availability becomes BOOKED
```

### Anti-double-booking

Use defense in depth:

- Redis distributed lock for reducing contention.
- PostgreSQL `SELECT FOR UPDATE`.
- Unique constraint on `(room_id, date)` or equivalent.
- Transaction boundary in service layer.
- Audit log for important actions.

Redis failure must not create double-booking.

### Idempotency

`POST /holds` and `POST /bookings` must require:

```text
Idempotency-Key
```

### Authorization

Backend must enforce:

- Role check
- Ownership check
- Resource-level authorization

Frontend guards are only UX. They are not security.

## 8. Frontend Rules

React app must:

- Stay under `/app/*`.
- Use services/hooks for API calls.
- Avoid API calls directly inside JSX.
- Use TanStack Query for server state.
- Use Zustand only for auth/session and light app state.
- Use Ant Design for tables/forms/modals/calendar.
- Use Tailwind for layout and spacing.
- Use Dayjs for date handling.
- Handle loading, empty, error states.
- Handle API error codes explicitly.

Do not create route conflicts with Thymeleaf `/rooms/{slug}`.

React room detail must be:

```text
/app/rooms/:id
```

Thymeleaf room SEO detail must be:

```text
/rooms/{slug}
```

## 9. Thymeleaf / SEO / CMS Rules

Thymeleaf handles public SEO pages.

Website Config CMS controls marketing/SEO content only:

- meta title
- meta description
- hero text
- CTA text
- FAQ
- footer
- SEO content block
- landing sections

Do not use CMS to configure:

- React app labels
- dashboard labels
- validation errors
- system error messages
- booking state names

Featured rooms must store only:

```text
pinnedRoomIds[]
```

Room title, price, image, and address must come from Room Management.

Markdown is only allowed in:

- SEO content
- FAQ answer

Markdown must be sanitized before rendering.

Do not allow raw SVG upload from admin. Use `iconKey` enum.

## 10. Infrastructure Rules

MVP deployment:

- 1 VPS
- Docker Compose
- Nginx
- Spring Boot
- PostgreSQL
- Redis
- local storage or MinIO
- Let's Encrypt SSL

Do not introduce Kubernetes in MVP.

Do not expose PostgreSQL or Redis publicly.

## 11. Security Rules

Mandatory:

- No raw token logging.
- No password logging.
- No raw email/phone logging in production.
- Mask PII in list views.
- Full PII only in detail views if role permits.
- WebSocket must use JWT handshake.
- Prevent IDOR on every resource endpoint.
- Sanitize CMS Markdown.
- Validate file upload MIME and magic bytes.
- Do not commit secrets.

## 12. Testing Rules

Every phase must include tests where applicable.

Backend:

- unit tests for services
- integration tests with Testcontainers for database-heavy logic
- concurrency tests for hold/booking
- authorization tests for wrong role/owner

Minimum commands after code:

Backend:

```text
./mvnw test
```

Frontend:

```text
npm run build
```

If a command fails, fix before reporting done.

## 13. Migration Rules

Use Flyway.

Production migrations must be backward-compatible.

Each migration should do one clear thing.

## 14. Git / Commit Rules

Use small commits.

Commit message format:

```text
feat: ...
fix: ...
docs: ...
test: ...
refactor: ...
chore: ...
```

Do not mix unrelated changes in one commit.

## 15. Forbidden Actions

Do not:

- Code full project in one task.
- Change route namespace.
- Move React outside `/app/*`.
- Put React app labels into CMS.
- Replace Thymeleaf SEO with Next.js.
- Add Redux.
- Add Kubernetes.
- Add Kafka.
- Add payment gateway unless explicitly requested.
- Skip tests.
- Commit `.env`.
- Log secrets or PII.
- Use Redis as the only correctness layer.

## 16. Output Format After Each Task

At the end of each task, report:

- Phase:
- Scope:
- Files changed:
- Commands run:
- Test result:
- What works:
- What is not included:
- Known risks:
- Next recommended step:
