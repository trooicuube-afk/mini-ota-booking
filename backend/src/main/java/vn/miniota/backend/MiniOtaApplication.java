package vn.miniota.backend;

import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;
import org.springframework.boot.context.properties.EnableConfigurationProperties;
import vn.miniota.backend.config.HomePageProperties;

@SpringBootApplication
@EnableConfigurationProperties(HomePageProperties.class)
public class MiniOtaApplication {

    public static void main(String[] args) {
        SpringApplication.run(MiniOtaApplication.class, args);
    }
}
