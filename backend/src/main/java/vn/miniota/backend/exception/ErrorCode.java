package vn.miniota.backend.exception;

import org.springframework.http.HttpStatus;

public enum ErrorCode {

    VALIDATION_FAILED(HttpStatus.BAD_REQUEST, "Validation failed"),
    INVALID_CREDENTIALS(HttpStatus.UNAUTHORIZED, "Invalid email or password"),
    DUPLICATE_EMAIL(HttpStatus.CONFLICT, "Email already registered"),
    UNAUTHORIZED(HttpStatus.UNAUTHORIZED, "Authentication required"),
    FORBIDDEN(HttpStatus.FORBIDDEN, "Access denied"),
    USER_DISABLED(HttpStatus.FORBIDDEN, "User account is disabled"),
    INVALID_REFRESH_TOKEN(HttpStatus.UNAUTHORIZED, "Invalid or expired refresh token"),
    PASSWORD_MISMATCH(HttpStatus.BAD_REQUEST, "Password and confirm password do not match");

    private final HttpStatus httpStatus;
    private final String defaultMessage;

    ErrorCode(HttpStatus httpStatus, String defaultMessage) {
        this.httpStatus = httpStatus;
        this.defaultMessage = defaultMessage;
    }

    public HttpStatus getHttpStatus() { return httpStatus; }
    public String getDefaultMessage() { return defaultMessage; }
}
