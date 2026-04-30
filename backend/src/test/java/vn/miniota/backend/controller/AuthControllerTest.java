package vn.miniota.backend.controller;

import com.fasterxml.jackson.databind.JsonNode;
import com.fasterxml.jackson.databind.ObjectMapper;
import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.boot.test.autoconfigure.web.servlet.AutoConfigureMockMvc;
import org.springframework.http.MediaType;
import org.springframework.test.web.servlet.MockMvc;
import org.springframework.test.web.servlet.MvcResult;
import vn.miniota.backend.AbstractIntegrationTest;
import vn.miniota.backend.entity.User;
import vn.miniota.backend.repository.RefreshTokenRepository;
import vn.miniota.backend.repository.UserRepository;

import static org.hamcrest.Matchers.*;
import static org.springframework.test.web.servlet.request.MockMvcRequestBuilders.*;
import static org.springframework.test.web.servlet.result.MockMvcResultMatchers.*;

@AutoConfigureMockMvc
class AuthControllerTest extends AbstractIntegrationTest {

    @Autowired
    private MockMvc mockMvc;

    @Autowired
    private ObjectMapper objectMapper;

    @Autowired
    private UserRepository userRepository;

    @Autowired
    private RefreshTokenRepository refreshTokenRepository;

    @BeforeEach
    void cleanUp() {
        refreshTokenRepository.deleteAll();
        userRepository.deleteAll();
    }

    // --- Register ---

    @Test
    void register_success() throws Exception {
        String body = """
                {
                    "fullName": "Test Customer",
                    "email": "test@example.com",
                    "phone": "0901234567",
                    "password": "SecurePass1!",
                    "confirmPassword": "SecurePass1!"
                }
                """;

        mockMvc.perform(post("/api/v1/auth/register")
                        .contentType(MediaType.APPLICATION_JSON)
                        .content(body))
                .andExpect(status().isCreated())
                .andExpect(jsonPath("$.accessToken").isNotEmpty())
                .andExpect(jsonPath("$.refreshToken").isNotEmpty())
                .andExpect(jsonPath("$.user.email").value("test@example.com"))
                .andExpect(jsonPath("$.user.fullName").value("Test Customer"))
                .andExpect(jsonPath("$.user.roles", hasItem("CUSTOMER")))
                .andExpect(jsonPath("$.user.roles", hasSize(1)));
    }

    @Test
    void register_duplicateEmail_returns409() throws Exception {
        String body = """
                {
                    "fullName": "First User",
                    "email": "dup@example.com",
                    "phone": null,
                    "password": "SecurePass1!",
                    "confirmPassword": "SecurePass1!"
                }
                """;

        mockMvc.perform(post("/api/v1/auth/register")
                        .contentType(MediaType.APPLICATION_JSON)
                        .content(body))
                .andExpect(status().isCreated());

        mockMvc.perform(post("/api/v1/auth/register")
                        .contentType(MediaType.APPLICATION_JSON)
                        .content(body))
                .andExpect(status().isConflict())
                .andExpect(jsonPath("$.code").value("DUPLICATE_EMAIL"));
    }

    @Test
    void register_passwordMismatch_returns400() throws Exception {
        String body = """
                {
                    "fullName": "Test User",
                    "email": "mismatch@example.com",
                    "password": "SecurePass1!",
                    "confirmPassword": "DifferentPass1!"
                }
                """;

        mockMvc.perform(post("/api/v1/auth/register")
                        .contentType(MediaType.APPLICATION_JSON)
                        .content(body))
                .andExpect(status().isBadRequest())
                .andExpect(jsonPath("$.code").value("PASSWORD_MISMATCH"));
    }

    @Test
    void register_validationErrors_returns400() throws Exception {
        String body = """
                {
                    "fullName": "",
                    "email": "not-an-email",
                    "password": "short",
                    "confirmPassword": "short"
                }
                """;

        mockMvc.perform(post("/api/v1/auth/register")
                        .contentType(MediaType.APPLICATION_JSON)
                        .content(body))
                .andExpect(status().isBadRequest())
                .andExpect(jsonPath("$.code").value("VALIDATION_FAILED"))
                .andExpect(jsonPath("$.errors").isNotEmpty());
    }

    // --- Login ---

    @Test
    void login_success() throws Exception {
        registerUser("login@example.com", "LoginPass1!");

        String body = """
                {
                    "email": "login@example.com",
                    "password": "LoginPass1!"
                }
                """;

        mockMvc.perform(post("/api/v1/auth/login")
                        .contentType(MediaType.APPLICATION_JSON)
                        .content(body))
                .andExpect(status().isOk())
                .andExpect(jsonPath("$.accessToken").isNotEmpty())
                .andExpect(jsonPath("$.refreshToken").isNotEmpty())
                .andExpect(jsonPath("$.user.email").value("login@example.com"))
                .andExpect(jsonPath("$.user.roles", hasItem("CUSTOMER")));
    }

    @Test
    void login_wrongPassword_returns401() throws Exception {
        registerUser("wrongpw@example.com", "CorrectPass1!");

        String body = """
                {
                    "email": "wrongpw@example.com",
                    "password": "WrongPass1!"
                }
                """;

        mockMvc.perform(post("/api/v1/auth/login")
                        .contentType(MediaType.APPLICATION_JSON)
                        .content(body))
                .andExpect(status().isUnauthorized())
                .andExpect(jsonPath("$.code").value("INVALID_CREDENTIALS"));
    }

    @Test
    void login_nonExistentUser_returns401() throws Exception {
        String body = """
                {
                    "email": "nobody@example.com",
                    "password": "SomePass1!"
                }
                """;

        mockMvc.perform(post("/api/v1/auth/login")
                        .contentType(MediaType.APPLICATION_JSON)
                        .content(body))
                .andExpect(status().isUnauthorized())
                .andExpect(jsonPath("$.code").value("INVALID_CREDENTIALS"));
    }

    @Test
    void login_disabledUser_returns403() throws Exception {
        registerUser("disabled@example.com", "DisabledPass1!");

        User user = userRepository.findByEmail("disabled@example.com").orElseThrow();
        user.setStatus("DISABLED");
        userRepository.save(user);

        String body = """
                {
                    "email": "disabled@example.com",
                    "password": "DisabledPass1!"
                }
                """;

        mockMvc.perform(post("/api/v1/auth/login")
                        .contentType(MediaType.APPLICATION_JSON)
                        .content(body))
                .andExpect(status().isForbidden())
                .andExpect(jsonPath("$.code").value("USER_DISABLED"));
    }

    // --- /auth/me ---

    @Test
    void me_withoutToken_returns401() throws Exception {
        mockMvc.perform(get("/api/v1/auth/me"))
                .andExpect(status().isUnauthorized())
                .andExpect(jsonPath("$.code").value("UNAUTHORIZED"));
    }

    @Test
    void me_withValidToken_returnsUser() throws Exception {
        String accessToken = registerAndGetAccessToken("me@example.com", "MePass1!");

        mockMvc.perform(get("/api/v1/auth/me")
                        .header("Authorization", "Bearer " + accessToken))
                .andExpect(status().isOk())
                .andExpect(jsonPath("$.email").value("me@example.com"))
                .andExpect(jsonPath("$.roles", hasItem("CUSTOMER")));
    }

    // --- Refresh ---

    @Test
    void refresh_success() throws Exception {
        MvcResult result = registerUser("refresh@example.com", "RefreshPass1!");
        JsonNode json = objectMapper.readTree(result.getResponse().getContentAsString());
        String refreshToken = json.get("refreshToken").asText();

        String body = "{\"refreshToken\": \"" + refreshToken + "\"}";

        mockMvc.perform(post("/api/v1/auth/refresh")
                        .contentType(MediaType.APPLICATION_JSON)
                        .content(body))
                .andExpect(status().isOk())
                .andExpect(jsonPath("$.accessToken").isNotEmpty())
                .andExpect(jsonPath("$.refreshToken").isNotEmpty())
                .andExpect(jsonPath("$.user.email").value("refresh@example.com"));
    }

    @Test
    void refresh_invalidToken_returns401() throws Exception {
        String body = "{\"refreshToken\": \"invalid-token-value\"}";

        mockMvc.perform(post("/api/v1/auth/refresh")
                        .contentType(MediaType.APPLICATION_JSON)
                        .content(body))
                .andExpect(status().isUnauthorized())
                .andExpect(jsonPath("$.code").value("INVALID_REFRESH_TOKEN"));
    }

    // --- Logout ---

    @Test
    void logout_revokesRefreshTokens() throws Exception {
        MvcResult result = registerUser("logout@example.com", "LogoutPass1!");
        JsonNode json = objectMapper.readTree(result.getResponse().getContentAsString());
        String accessToken = json.get("accessToken").asText();
        String refreshToken = json.get("refreshToken").asText();

        mockMvc.perform(post("/api/v1/auth/logout")
                        .header("Authorization", "Bearer " + accessToken))
                .andExpect(status().isNoContent());

        String body = "{\"refreshToken\": \"" + refreshToken + "\"}";
        mockMvc.perform(post("/api/v1/auth/refresh")
                        .contentType(MediaType.APPLICATION_JSON)
                        .content(body))
                .andExpect(status().isUnauthorized());
    }

    // --- Helpers ---

    private MvcResult registerUser(String email, String password) throws Exception {
        String body = String.format("""
                {
                    "fullName": "Test User",
                    "email": "%s",
                    "password": "%s",
                    "confirmPassword": "%s"
                }
                """, email, password, password);

        return mockMvc.perform(post("/api/v1/auth/register")
                        .contentType(MediaType.APPLICATION_JSON)
                        .content(body))
                .andExpect(status().isCreated())
                .andReturn();
    }

    private String registerAndGetAccessToken(String email, String password) throws Exception {
        MvcResult result = registerUser(email, password);
        JsonNode json = objectMapper.readTree(result.getResponse().getContentAsString());
        return json.get("accessToken").asText();
    }
}
