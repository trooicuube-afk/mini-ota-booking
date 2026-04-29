# Testing: Mini OTA Landing Page (Thymeleaf)

## Overview
The landing page is a Spring Boot + Thymeleaf server-rendered page at `GET /`.
Dynamic sections (SEO meta, Header, Hero) are configured via `@ConfigurationProperties` in `application.yml`.
Static sections (featured rooms, cities, owner CTA, how-it-works, benefits, SEO content, FAQ, footer) are hardcoded HTML.

## Prerequisites
- Java 17+ installed
- Maven wrapper available at `backend/mvnw`

## How to Run
```bash
cd backend && ./mvnw spring-boot:run
# Landing page at http://localhost:8080/
```

## How to Run Unit Tests
```bash
cd backend && ./mvnw test
# Expect 15/15 tests passing
```

## What to Verify in Browser Testing

### Dynamic Sections (from `application.yml` config)
1. **SEO meta** — View source (`Ctrl+U`), verify:
   - `<title>` matches `miniota.home.seo.title`
   - `<meta name="description">` matches config
   - `<link rel="canonical">`, OG tags, Twitter Card, JSON-LD all present and resolved
   - No unresolved Thymeleaf expressions (`${...}`) in rendered HTML
2. **Header** — Check:
   - Logo text matches `miniota.home.header.logo-text`
   - Nav items match `miniota.home.header.nav[]`
   - Login/Register links point to `/app/login` and `/app/register`
3. **Hero** — Check:
   - Badge text matches `miniota.home.hero.badge`
   - Title lines with highlight on `miniota.home.hero.title-highlight` (rendered via `th:utext` with `String.replace()`)
   - Trust badges with correct color tokens
   - Search form `action` matches `miniota.home.hero.search-action` (should be `/app/rooms`)

### Static Sections
- Featured rooms: 3 room cards with placeholder images
- Popular cities: 3 city cards (Đà Lạt, Đà Nẵng, Vũng Tàu)
- Owner CTA: "Bạn là chủ homestay?" with dashboard mockup
- How it works: Customer (3 steps) + Owner (3 steps)
- Benefits: 4 benefit cards
- SEO content: Long-form text block
- FAQ: 5 collapsible `<details>` items (click to verify expand/collapse)
- Footer: Logo, links, copyright

### Route Convention
Verify all `href` attributes in the page follow the AGENTS.md route namespace:
- `/app/*` for booking/auth: `/app/rooms`, `/app/login`, `/app/register`
- Thymeleaf SEO routes: `/`, `/dich-vu-quan-ly-homestay`, `/phan-mem-quan-ly-dat-phong`, `/blog`
- Anchors: `#cities`, `#how-it-works`
- **No bare** `/login`, `/register`, or `/rooms` without `/app/` prefix

Quick verification via shell:
```bash
curl -s http://localhost:8080/ | grep -oP 'href="[^"]*"' | sort -u
```

### Search Form Submission
- Select a city → click "Tìm phòng" → browser navigates to `/app/rooms?city=...`
- The 404 error is expected if React app is not built yet

## Known Gotchas
- **Hero image broken**: CDN URLs (`cdn.miniota.vn`) are placeholders — images show alt text instead. This is expected until real images are uploaded.
- **Room/city card images**: Show placeholder text ("Ảnh phòng", "Ảnh Đà Lạt", etc.) — expected.
- **Tailwind via CDN**: Loaded via `cdn.tailwindcss.com` script tag. Works for dev but should be bundled for production.
- **`/app/rooms` returns 404**: Expected — React app is a future phase.
- **Maven wrapper**: If `./mvnw` fails, ensure `.mvn/wrapper/maven-wrapper.properties` exists and `mvnw` has execute permission (`chmod +x mvnw`).
- **Chrome console tool**: May report "Chrome is not in the foreground" — click the page content area first to focus, or use `curl` commands to verify page source instead.

## Devin Secrets Needed
None — the landing page is public with no authentication required.
