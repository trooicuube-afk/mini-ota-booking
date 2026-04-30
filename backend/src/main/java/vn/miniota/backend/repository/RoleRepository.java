package vn.miniota.backend.repository;

import org.springframework.data.jpa.repository.JpaRepository;
import vn.miniota.backend.entity.Role;

import java.util.Optional;
import java.util.UUID;

public interface RoleRepository extends JpaRepository<Role, UUID> {

    Optional<Role> findByCode(String code);
}
