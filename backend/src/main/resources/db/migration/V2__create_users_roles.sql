-- Users table
CREATE TABLE users (
    id         UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    email      VARCHAR(255) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name  VARCHAR(255) NOT NULL,
    phone      VARCHAR(20),
    status     VARCHAR(20)  NOT NULL DEFAULT 'ACTIVE',
    created_at TIMESTAMPTZ  NOT NULL DEFAULT now(),
    updated_at TIMESTAMPTZ  NOT NULL DEFAULT now(),
    CONSTRAINT uk_users_email UNIQUE (email),
    CONSTRAINT chk_users_status CHECK (status IN ('ACTIVE', 'DISABLED'))
);

CREATE INDEX idx_users_email ON users (email);

-- Roles table
CREATE TABLE roles (
    id   UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    code VARCHAR(50) NOT NULL,
    CONSTRAINT uk_roles_code UNIQUE (code)
);

-- User-Role join table
CREATE TABLE user_roles (
    user_id UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    role_id UUID NOT NULL REFERENCES roles(id) ON DELETE CASCADE,
    PRIMARY KEY (user_id, role_id)
);
