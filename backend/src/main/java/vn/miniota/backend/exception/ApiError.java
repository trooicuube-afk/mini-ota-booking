package vn.miniota.backend.exception;

import com.fasterxml.jackson.annotation.JsonInclude;

import java.time.Instant;
import java.util.List;

@JsonInclude(JsonInclude.Include.NON_NULL)
public class ApiError {

    private String code;
    private String message;
    private int status;
    private Instant timestamp;
    private String path;
    private List<FieldError> errors;

    public ApiError(ErrorCode errorCode, String path) {
        this.code = errorCode.name();
        this.message = errorCode.getDefaultMessage();
        this.status = errorCode.getHttpStatus().value();
        this.timestamp = Instant.now();
        this.path = path;
    }

    public ApiError(ErrorCode errorCode, String message, String path) {
        this.code = errorCode.name();
        this.message = message;
        this.status = errorCode.getHttpStatus().value();
        this.timestamp = Instant.now();
        this.path = path;
    }

    public String getCode() { return code; }
    public String getMessage() { return message; }
    public int getStatus() { return status; }
    public Instant getTimestamp() { return timestamp; }
    public String getPath() { return path; }
    public List<FieldError> getErrors() { return errors; }
    public void setErrors(List<FieldError> errors) { this.errors = errors; }

    public record FieldError(String field, String message) {}
}
