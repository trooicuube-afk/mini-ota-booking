package vn.miniota.backend.repository;

import org.springframework.data.jpa.repository.JpaRepository;
import vn.miniota.backend.entity.User;

import java.util.Optional;
import java.util.UUID;

public interface UserRepository extends JpaRepository<User, UUID> {

    Optional<User> findByEmail(String email);

    boolean existsByEmail(String email);
}
