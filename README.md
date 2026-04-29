# Mini OTA Booking Platform

Mini OTA Booking Platform is a monorepo for a room booking platform, planned around SEO-friendly public pages, a React application workspace, and versioned backend APIs.

## Modules

- `backend/`: backend service workspace (Spring Boot + Thymeleaf).
- `frontend/`: React application workspace, not implemented yet.
- `mobile/`: mobile application workspace, reserved for later phases.
- `infra/`: infrastructure placeholders for local services and deployment notes.
- `docs/`: project documentation, architecture notes, API contract, roadmap, and prototypes.
- `screenshots/`: project screenshots and demo captures.

## Route Convention

- Thymeleaf SEO: `/`, `/rooms/{slug}`, `/blog/*`, `/homestay-*`
- React app: `/app/*`
- API: `/api/v1/*`

## Roles

- `CUSTOMER`
- `OWNER`
- `SALE`
- `ADMIN`

## Core Flow

Customer search room -> hold room -> confirm booking -> owner manages calendar -> sale supports booking -> admin audits.

## Documentation

Documentation is maintained in `/docs`.

## Getting Started

### Backend

```bash
cd backend
./mvnw spring-boot:run
```

Open http://localhost:8080/ to see the landing page.

## Status

Phase CMS-01: Thymeleaf Landing Foundation.
