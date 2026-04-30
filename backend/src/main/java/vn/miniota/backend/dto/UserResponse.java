package vn.miniota.backend.dto;

import vn.miniota.backend.entity.User;

import java.util.List;
import java.util.UUID;

public class UserResponse {

    private UUID id;
    private String email;
    private String fullName;
    private String phone;
    private String status;
    private List<String> roles;

    public static UserResponse from(User user) {
        UserResponse r = new UserResponse();
        r.id = user.getId();
        r.email = user.getEmail();
        r.fullName = user.getFullName();
        r.phone = user.getPhone();
        r.status = user.getStatus();
        r.roles = user.getRoles().stream()
                .map(role -> role.getCode())
                .sorted()
                .toList();
        return r;
    }

    public UUID getId() { return id; }
    public String getEmail() { return email; }
    public String getFullName() { return fullName; }
    public String getPhone() { return phone; }
    public String getStatus() { return status; }
    public List<String> getRoles() { return roles; }
}
