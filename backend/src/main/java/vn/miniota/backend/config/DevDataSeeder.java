package vn.miniota.backend.config;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.boot.CommandLineRunner;
import org.springframework.context.annotation.Profile;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.stereotype.Component;
import vn.miniota.backend.entity.Role;
import vn.miniota.backend.entity.User;
import vn.miniota.backend.repository.RoleRepository;
import vn.miniota.backend.repository.UserRepository;

import java.util.Map;
import java.util.Set;

@Component
@Profile("dev")
public class DevDataSeeder implements CommandLineRunner {

    private static final Logger log = LoggerFactory.getLogger(DevDataSeeder.class);

    private final UserRepository userRepository;
    private final RoleRepository roleRepository;
    private final PasswordEncoder passwordEncoder;

    public DevDataSeeder(UserRepository userRepository,
                         RoleRepository roleRepository,
                         PasswordEncoder passwordEncoder) {
        this.userRepository = userRepository;
        this.roleRepository = roleRepository;
        this.passwordEncoder = passwordEncoder;
    }

    @Override
    public void run(String... args) {
        Map<String, String> seedUsers = Map.of(
                "customer@example.com", "CUSTOMER",
                "owner@example.com", "OWNER",
                "sale@example.com", "SALE",
                "admin@example.com", "ADMIN"
        );

        String passwordHash = passwordEncoder.encode("Password123!");

        seedUsers.forEach((email, roleCode) -> {
            if (userRepository.existsByEmail(email)) {
                log.info("Dev user already exists: {}", email);
                return;
            }

            Role role = roleRepository.findByCode(roleCode)
                    .orElseThrow(() -> new IllegalStateException("Role not found: " + roleCode));

            User user = new User();
            user.setEmail(email);
            user.setPasswordHash(passwordHash);
            user.setFullName(roleCode.charAt(0) + roleCode.substring(1).toLowerCase() + " User");
            user.setRoles(Set.of(role));
            userRepository.save(user);
            log.info("Seeded dev user: {} with role {}", email, roleCode);
        });
    }
}
