package vn.miniota.backend.controller;

import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import vn.miniota.backend.config.HomePageProperties;

@Controller
public class HomePageController {

    private final HomePageProperties homePageProperties;

    public HomePageController(HomePageProperties homePageProperties) {
        this.homePageProperties = homePageProperties;
    }

    @GetMapping("/")
    public String home(Model model) {
        model.addAttribute("seo", homePageProperties.getSeo());
        model.addAttribute("header", homePageProperties.getHeader());
        model.addAttribute("hero", homePageProperties.getHero());
        return "pages/home";
    }
}
