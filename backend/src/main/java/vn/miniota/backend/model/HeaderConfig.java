package vn.miniota.backend.model;

import java.util.ArrayList;
import java.util.List;

public class HeaderConfig {

    private String logoText = "MiniOTA";
    private List<NavItem> nav = new ArrayList<>();
    private String loginLabel = "Đăng nhập";
    private String loginHref = "/app/login";
    private String registerLabel = "Đăng ký";
    private String registerHref = "/app/register";

    public String getLogoText() {
        return logoText;
    }

    public void setLogoText(String logoText) {
        this.logoText = logoText;
    }

    public List<NavItem> getNav() {
        return nav;
    }

    public void setNav(List<NavItem> nav) {
        this.nav = nav;
    }

    public String getLoginLabel() {
        return loginLabel;
    }

    public void setLoginLabel(String loginLabel) {
        this.loginLabel = loginLabel;
    }

    public String getLoginHref() {
        return loginHref;
    }

    public void setLoginHref(String loginHref) {
        this.loginHref = loginHref;
    }

    public String getRegisterLabel() {
        return registerLabel;
    }

    public void setRegisterLabel(String registerLabel) {
        this.registerLabel = registerLabel;
    }

    public String getRegisterHref() {
        return registerHref;
    }

    public void setRegisterHref(String registerHref) {
        this.registerHref = registerHref;
    }

    public static class NavItem {

        private String label;
        private String href;
        private boolean external;

        public NavItem() {
        }

        public NavItem(String label, String href, boolean external) {
            this.label = label;
            this.href = href;
            this.external = external;
        }

        public String getLabel() {
            return label;
        }

        public void setLabel(String label) {
            this.label = label;
        }

        public String getHref() {
            return href;
        }

        public void setHref(String href) {
            this.href = href;
        }

        public boolean isExternal() {
            return external;
        }

        public void setExternal(boolean external) {
            this.external = external;
        }
    }
}
