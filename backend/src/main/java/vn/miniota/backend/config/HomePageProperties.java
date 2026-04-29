package vn.miniota.backend.config;

import org.springframework.boot.context.properties.ConfigurationProperties;
import vn.miniota.backend.model.HeaderConfig;
import vn.miniota.backend.model.HeroConfig;
import vn.miniota.backend.model.SeoConfig;

@ConfigurationProperties(prefix = "miniota.home")
public class HomePageProperties {

    private SeoConfig seo = new SeoConfig();
    private HeaderConfig header = new HeaderConfig();
    private HeroConfig hero = new HeroConfig();

    public SeoConfig getSeo() {
        return seo;
    }

    public void setSeo(SeoConfig seo) {
        this.seo = seo;
    }

    public HeaderConfig getHeader() {
        return header;
    }

    public void setHeader(HeaderConfig header) {
        this.header = header;
    }

    public HeroConfig getHero() {
        return hero;
    }

    public void setHero(HeroConfig hero) {
        this.hero = hero;
    }
}
