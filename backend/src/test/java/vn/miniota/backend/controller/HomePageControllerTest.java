package vn.miniota.backend.controller;

import org.junit.jupiter.api.Test;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.boot.test.autoconfigure.web.servlet.AutoConfigureMockMvc;
import org.springframework.test.web.servlet.MockMvc;
import vn.miniota.backend.AbstractIntegrationTest;

import static org.springframework.test.web.servlet.request.MockMvcRequestBuilders.get;
import static org.springframework.test.web.servlet.result.MockMvcResultMatchers.content;
import static org.springframework.test.web.servlet.result.MockMvcResultMatchers.status;
import static org.hamcrest.Matchers.containsString;
import static org.hamcrest.Matchers.not;

@AutoConfigureMockMvc
class HomePageControllerTest extends AbstractIntegrationTest {

    @Autowired
    private MockMvc mockMvc;

    @Test
    void homePageReturns200() throws Exception {
        mockMvc.perform(get("/"))
                .andExpect(status().isOk());
    }

    @Test
    void homePageContainsSeoTitle() throws Exception {
        mockMvc.perform(get("/"))
                .andExpect(status().isOk())
                .andExpect(content().string(containsString("MiniOTA")));
    }

    @Test
    void homePageContainsMetaDescription() throws Exception {
        mockMvc.perform(get("/"))
                .andExpect(status().isOk())
                .andExpect(content().string(containsString("name=\"description\"")));
    }

    @Test
    void homePageContainsCanonicalLink() throws Exception {
        mockMvc.perform(get("/"))
                .andExpect(status().isOk())
                .andExpect(content().string(containsString("rel=\"canonical\"")));
    }

    @Test
    void homePageContainsOgMeta() throws Exception {
        mockMvc.perform(get("/"))
                .andExpect(status().isOk())
                .andExpect(content().string(containsString("property=\"og:title\"")))
                .andExpect(content().string(containsString("property=\"og:description\"")))
                .andExpect(content().string(containsString("property=\"og:image\"")));
    }

    @Test
    void homePageContainsHeaderLogoText() throws Exception {
        mockMvc.perform(get("/"))
                .andExpect(status().isOk())
                .andExpect(content().string(containsString("MiniOTA")));
    }

    @Test
    void homePageContainsHeaderNavLinks() throws Exception {
        mockMvc.perform(get("/"))
                .andExpect(status().isOk())
                .andExpect(content().string(containsString("Tìm phòng")))
                .andExpect(content().string(containsString("/app/rooms")));
    }

    @Test
    void homePageContainsLoginRegisterLinks() throws Exception {
        mockMvc.perform(get("/"))
                .andExpect(status().isOk())
                .andExpect(content().string(containsString("/app/login")))
                .andExpect(content().string(containsString("/app/register")));
    }

    @Test
    void homePageContainsHeroBadge() throws Exception {
        mockMvc.perform(get("/"))
                .andExpect(status().isOk())
                .andExpect(content().string(containsString("Mini OTA")));
    }

    @Test
    void homePageContainsHeroTitle() throws Exception {
        mockMvc.perform(get("/"))
                .andExpect(status().isOk())
                .andExpect(content().string(containsString("Đặt phòng homestay nhanh,")));
    }

    @Test
    void homePageContainsHeroTrustBadges() throws Exception {
        mockMvc.perform(get("/"))
                .andExpect(status().isOk())
                .andExpect(content().string(containsString("Lịch trống rõ ràng")))
                .andExpect(content().string(containsString("Giữ phòng 15 phút")))
                .andExpect(content().string(containsString("Không đặt trùng phòng")));
    }

    @Test
    void homePageSearchFormPointsToAppRooms() throws Exception {
        mockMvc.perform(get("/"))
                .andExpect(status().isOk())
                .andExpect(content().string(containsString("action=\"/app/rooms\"")));
    }

    @Test
    void homePageContainsStaticSections() throws Exception {
        mockMvc.perform(get("/"))
                .andExpect(status().isOk())
                .andExpect(content().string(containsString("Phòng nổi bật")))
                .andExpect(content().string(containsString("Điểm đến phổ biến")))
                .andExpect(content().string(containsString("Cách MiniOTA hoạt động")))
                .andExpect(content().string(containsString("Tại sao chọn MiniOTA")))
                .andExpect(content().string(containsString("Câu hỏi thường gặp")));
    }

    @Test
    void homePageDoesNotContainReactRoutesOutsideApp() throws Exception {
        String html = mockMvc.perform(get("/"))
                .andExpect(status().isOk())
                .andReturn()
                .getResponse()
                .getContentAsString();

        // Verify no href="/login" or href="/register" (must be /app/login, /app/register)
        org.junit.jupiter.api.Assertions.assertFalse(
                html.contains("href=\"/login\""),
                "Found bare /login link outside /app/*");
        org.junit.jupiter.api.Assertions.assertFalse(
                html.contains("href=\"/register\""),
                "Found bare /register link outside /app/*");
    }
}
