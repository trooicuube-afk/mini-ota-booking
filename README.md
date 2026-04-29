# Mini OTA Booking Platform

Mini OTA Booking Platform is a monorepo for a room booking platform, planned around SEO-friendly public pages, a React application workspace, and versioned backend APIs.

## Modules

- `backend/`: backend service workspace (Spring Boot + Thymeleaf).
- `frontend/`: React application workspace, not implemented yet.
- `mobile/`: mobile application workspace, reserved for later phases.
- `infra/`: infrastructure — Docker Compose for local services.
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

### Prerequisites

- Java 17+
- Docker & Docker Compose (for PostgreSQL and Redis)
- Maven wrapper included (`./mvnw`)

### 1. Start Infrastructure

```bash
docker compose -f infra/docker-compose.yml up -d postgres redis
```

This starts:
- **PostgreSQL 16** on port 5432 (user: `miniota`, password: `miniota`, db: `miniota`)
- **Redis 7** on port 6379

### 2. Run Backend (dev profile)

```bash
cd backend
./mvnw spring-boot:run -Dspring-boot.run.profiles=dev
```

Open http://localhost:8080/ to see the landing page.

### 3. Run Tests

Tests use Testcontainers — Docker must be running but you do NOT need to start postgres/redis manually.

```bash
cd backend
./mvnw test
```

### 4. Verify Health

```bash
# Custom health endpoint
curl http://localhost:8080/api/v1/health
# {"status":"OK"}

# Spring Actuator (includes DB + Redis status)
curl http://localhost:8080/actuator/health
# {"status":"UP","components":{"db":{"status":"UP",...},"redis":{"status":"UP",...}}}
```

### Environment Variables

| Variable | Default | Description |
|----------|---------|-------------|
| `DATABASE_URL` | `jdbc:postgresql://localhost:5432/miniota` | JDBC connection URL |
| `DATABASE_USERNAME` | `miniota` | PostgreSQL username |
| `DATABASE_PASSWORD` | `miniota` | PostgreSQL password |
| `REDIS_HOST` | `localhost` | Redis host |
| `REDIS_PORT` | `6379` | Redis port |

## Status

Phase BE-01: Backend Data Foundation.
